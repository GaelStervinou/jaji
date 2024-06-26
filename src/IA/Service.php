<?php

namespace App\IA;

use App\Entity\DiagnosticMentalHealth;
use App\Entity\DiagnosticRisks;
use App\Entity\MessageMedia;
use App\Entity\Messages;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Service
{
    private EntityManagerInterface $entityManager;
    private HttpClientInterface $httpClient;
    private ParameterBagInterface $params;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $httpClient, ParameterBagInterface $params)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
        $this->params = $params;
    }

    public function generateDiagnostics(int $patientId): void
    {
        $patient = $this->entityManager->getRepository(Patient::class)->find($patientId);
        if (!$patient) {
            throw new NotFoundHttpException('Patient not found');
        }
        try {
            $patientInformation = PatientInformation::retrievePatientInformation($patient, $this->entityManager)->toJson();
        } catch (\Throwable $e) {
            throw new \RuntimeException('Unable to retrieve patient information', 0, $e);
        }
        try {
            /*$finalObject = json_encode((object)[
                "mental_health_score" => 4,
                "mental_health_description" => "Le patient semble avoir des troubles de santé mentale, il est recommandé de consulter un professionnel de la santé mentale.",
                "mental_health_reason" => [
                    [
                        "title" => "Dépression",
                        "conclusion" => "Le patient semble dépressif au vu du message qu'il nous envoie ici",
                        "source" => "Je me sens triste tout le temps, je n'ai plus goût à rien",
                        "type" => "text",
                        "severity" => 3
                    ],
                    [
                        "title" => "Pensées suicidaires",
                        "conclusion" => "Le patient semble vouloir passer à l'acte en se pednant.",
                        "source" => "Cette image montre un tabouret ainsi qu'une corde au-dessus.",
                        "type" => "image",
                        "severity" => 3
                    ]
                ],
                "risk_score" => 0,
                "risk_description" => "Aucun risque n'a été identifié",
                "risk_reason" => []
            ], JSON_THROW_ON_ERROR);*/
            $response = $this->httpClient->request('POST', 'http://host.docker.internal:11434/api/generate', [
                'json' => [
                    'model' => 'mistral',
                    'system' => "You are a hospital doctor. You will find, in a JSON object, a list of information about a patient. The first JSON key contains all the messages the patient as sent to the hospital. The second key contains all the events concerning this patient (surgeries, medical consultations...) briefly describe. The third key contains all the weights record received directly from the patient smart weighing scale, if patient has one. The fourth JSON key contains a list of observations made by doctors about the patient. With all those information, you will give a score concerning the patient mental health, from 0 to 10, 0 is a perfect mental health and 10 is a terrible one, needing help, in a JSON key name mental_health_score. You will also write a summary that will cite the elements that allowed you to give this score in the JSON key mental_health_reason. This summary has to be a JSON object containing an array of objects . You will also write a short summary of the state of the patient's mental health, in a few words inside the JSON key mental_health_description. You will also give a score from 0 to 10 concerning the risks are the complications the patient may encounter, 0 is no risk at all and 10 is very risky in the JSON key risks_score and write a summary that will cite the elements that allowed you to give this score in the JSON key risk_reason.You will also write a short summary of the state of the patient's mental health, in a few words inside the JSON key risk_description. Please, answer in french only!",
                    'prompt' => $patientInformation,
                    'stream' => false,
                ],
                'timeout' => 12000,
                'max_duration' => 0,
            ]);
        } catch (\Throwable $e) {
            throw new \Exception('Unable to generate diagnostics', 0, $e);
        }



        try {
            $content = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
            $decodedResponse = json_decode($content->response, false, 512, JSON_THROW_ON_ERROR);
            $mentalHealthDescription = (new DiagnosticMentalHealth())
                ->setValue($decodedResponse->mental_health_score)
                ->setReasons(json_encode($decodedResponse->mental_health_reason, JSON_THROW_ON_ERROR))
                ->setContent($decodedResponse->mental_health_description)
                ->setPatient($patient)
                ->setCreatedAt(new \DateTimeImmutable());

            $risksDescription = (new DiagnosticRisks())
                ->setValue($decodedResponse->risks_score)
                ->setReasons(json_encode($decodedResponse->risk_reason, JSON_THROW_ON_ERROR))
                ->setContent($decodedResponse->risk_description)
                ->setPatient($patient)
                ->setCreatedAt(new \DateTimeImmutable());
        } catch (\Throwable $e) {
            dd($e, $content);
            throw new \RuntimeException('Unable to decode response', 0, $e);
        }

        $this->entityManager->persist($mentalHealthDescription);
        $this->entityManager->persist($risksDescription);
        $this->entityManager->flush();
    }

    public function generateMessageContent(int $messageId): void
    {
        $message = $this->entityManager->getRepository(Messages::class)->find($messageId);
        if (!$message) {
            throw new NotFoundHttpException('Message not found');
        }
        if ($message->getMedia() === MessageMedia::IMAGE) {
            $filePath = $this->params->get('kernel.project_dir') . '/public/uploads/' . $message->getPath();
            if (file_exists($filePath)) {
                $fileContent = file_get_contents($filePath);
                $base64Image = base64_encode($fileContent);
                try {
                    $response = $this->httpClient->request('POST', 'http://host.docker.internal:11434/api/generate', [
                        'json' => [
                            'model' => 'llava',
                            'prompt' => "You are a doctor who has received an image of a wound. Describe only the picture by only summarizing the conclusions no need to provide context, not exceeding 255 characters. Respond in French.",
                            'stream' => false,
                            'images' => [
                                $base64Image
                            ],
                        ],
                        'timeout' => 120,
                    ]);
                } catch (\Throwable $e) {
                    throw new \Exception('Unable to generate diagnostics', 0, $e);
                }

                try {
                    $content = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

                    $message->setContent($content->response);
                    $this->entityManager->flush();
                } catch (\Throwable $e) {
                    throw new \RuntimeException('Unable to decode response', 0, $e);
                }
            } else {
                throw new \Exception('File not found');
            }
        }
    }
}