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
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

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
            $prompt = "You are a hospital doctor. You will find, in a JSON object, a list of information about a patient. The first JSON key contains all the messages the patient as sent to the hospital. The second key contains all the events concerning this patient (surgeries, medical consultations...) briefly describe. The third key contains all the weights record received directly from the patient smart weighing scale, if patient has one. The fourth JSON key contains a list of observations made by doctors about the patient.You will answer a JSON object that I will describe you.With all the information given, you will give a score concerning the patient's mental health, from 0 to 10, 0 is a perfect mental health and 10 is a terrible one, needing help, in a JSON key name mental_health_score. You will also write a summary that will cite the elements you receive ( sources ) that allowed you to give this score, in the JSON key mental_health_reason. This summary has to be a JSON object containing an array of objects (diagnostic item) containing a title ( the diagnostic name in one or two word ), a short conclusion on this specific element ( a few words ), the source you based an observation on ( the exact content of the source ), the type of the element ( image, audio, text or weight, event), a severity score from 1 to 3, and finally the path linked to the media if it's an audio or an image. A diagnostic item looks like this, and will be in french only: {title: Dépression,conclusion: Le patient semble dépressif au vu du message qu'il nous envoie ici,source: Je me sens triste tout le temps, je n'ai plus goût à rien,type: text,severity: 3}.You will also write a short summary of the state of the patient's mental health, in a few words inside the JSON key mental_health_description. If there is no mental health issue, please write: Aucun problème de santé mentale n'a été détecté. inside mental_health_description key and send an empty array inside mental_health_reason.You will also give a score from 0 to 10 concerning the risks are the complications the patient may encounter, 0 is no risk at all and 10 is very risky in the JSON key risks_score and write a summary that will cite the elements that allowed you to give this score in the JSON key risk_reason.This summary has to be a JSON object containing an array of objects (diagnostic item) containing a title ( the diagnostic name in one or two word ), a short conclusion on this specific element ( a few words ), the source you based an observation on ( the exact content of the source ), the type of the element ( image, audio, text or weight, event), a severity score from 1 to 3, and finnaly the path linked to the media if it's an audio or an image.A diagnostic item looks like this, and will be in french only: {title: Infection,conclusion: Le patient semble avoir une infection au vu de la photo de sa cicatrice,source: Cette image montre une cicatrice en train de suinter,type: image,severity: 2,path: /path/to/image.jpg}.You will also write a short summary of the risks the patient may encounter, in a few words inside the JSON key risk_description.If there is no risk, please write: Aucun risque n'a été identifié. inside risk_description key and send an empty array inside risk_reason.Please, answer in french only! Please, respect the JSON format.At the end, your answers should looks like that : {mental_health_score: 4,mental_health_description: Le patien semble avoir des troubles de santé mentale, il est recommandé de consulter un professionnel de la santé mentale.,mental_health_reason: [{title: Dépression,conclusion: Le patient semble dépressif au vu du message qu'il nous envoie ici,source: Je me sens triste tout le temps, je n'ai plus goût à rien,type: text,severity: 3},{title: Pensées suicidaires,conclusion: Le patient semble vouloir passer à l'acte en se pednant.,source: Cette image montre un tabouret ainsi qu'une corde au-dessus.,type: image,severity: 3}],risk_score: 0,risk_description: Aucun risque n'a été identifié,risk_reason: []}";
            $token = getenv('OPENAI_API_KEY');
            $url = 'https://api.openai.com/v1/chat/completions';
            $model = "gpt-3.5-turbo";
            $messages = [
                [
                    "role" => "system",
                    "content" => $prompt
                ],
                [
                    "role" => "user",
                    "content" => $patientInformation
                ]
            ];
            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => $messages,
                    'stop' => ['\n'],
                ],
                'timeout' => 12000,
                'max_duration' => 0,
            ]);
            /* This is the code to use ollama in local
            $response = $this->httpClient->request('POST', 'http://host.docker.internal:11434/api/generate', [
                'json' => [
                    'model' => 'mistral',
                    'system' => "You are a hospital doctor. You will find, in a JSON object, a list of information about a patient. The first JSON key contains all the messages the patient as sent to the hospital. The second key contains all the events concerning this patient (surgeries, medical consultations...) briefly describe. The third key contains all the weights record received directly from the patient smart weighing scale, if patient has one. The fourth JSON key contains a list of observations made by doctors about the patient. With all those information, you will give a score concerning the patient mental health, from 0 to 10, 0 is a perfect mental health and 10 is a terrible one, needing help, in a JSON key name mental_health_score. You will also write a summary that will cite the elements that allowed you to give this score in the JSON key mental_health_reason. 
                    This summary has to be a JSON object containing an array of objects (diagnostic item) containing a title ( the diagnostic name in one or two word ), a short conclusion on this specific element ( a few words ), the element you cite, the type of the element ( image, audio, text or weight, event), a severity score from 1 to 3, and finnaly the path linked to the media if it's an audio or an image. A diagnostic item looks like this, and will be in french only: 
                    {
                        title: Dépression,
                        conclusion: Le patient semble dépressif au vu du message qu'il nous envoie ici,
                        source: Je me sens triste tout le temps, je n'ai plus goût à rien,
                        type: text,
                        severity: 3
                    }.
                     You will also write a short summary of the state of the patient's mental health, in a few words inside the JSON key mental_health_description. You will also give a score from 0 to 10 concerning the risks are the complications the patient may encounter, 0 is no risk at all and 10 is very risky in the JSON key risks_score and write a summary that will cite the elements that allowed you to give this score in the JSON key risk_reason.
                     This summary has to be a JSON object containing an array of objects (diagnostic item) containing a title ( the diagnostic name in one or two word ), a short conclusion on this specific element ( a few words ), the element you cite, the type of the element ( image, audio, text or weight, event), a severity score from 1 to 3, and finnaly the path linked to the media if it's an audio or an image. A diagnostic item looks like this, and will be in french only: 
                    {
                        title: Infection,
                        conclusion: Le patient semble avoir une infection au vu de la photo de sa cicatrice,
                        source: Cette image montre une cicatrice en train de suinter,
                        type: image,
                        severity: 2,
                        path: /path/to/image.jpg
                    }.
                    You will also write a short summary of the state of the patient's mental health, in a few words inside the JSON key risk_description. Please, answer in french only!",
                    'prompt' => $patientInformation,
                    'stream' => false,
                ],
                'timeout' => 12000,
                'max_duration' => 0,
            ]);*/
        } catch (\Throwable $e) {
            throw new \Exception('Unable to generate diagnostics', 0, $e);
        }

        try {
            /* This is the code to use ollama in local
            $content = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
            $decodedResponse = json_decode($content->response, false, 512, JSON_THROW_ON_ERROR);*/
            $content = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
            $decodedResponse = json_decode($content->choices[0]->message->content, false, 512, JSON_THROW_ON_ERROR);
            $currentDate = new \DateTimeImmutable();
            $mentalHealthDescription = (new DiagnosticMentalHealth())
                ->setValue($decodedResponse->mental_health_score)
                ->setReasons(json_encode($decodedResponse->mental_health_reason, JSON_THROW_ON_ERROR))
                ->setContent($decodedResponse->mental_health_description)
                ->setPatient($patient)
                ->setCreatedAt($currentDate);

            $risksDescription = (new DiagnosticRisks())
                ->setValue($decodedResponse->risks_score)
                ->setReasons(json_encode($decodedResponse->risk_reason, JSON_THROW_ON_ERROR))
                ->setContent($decodedResponse->risk_description)
                ->setPatient($patient)
                ->setCreatedAt($currentDate);
        } catch (\Throwable $e) {
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