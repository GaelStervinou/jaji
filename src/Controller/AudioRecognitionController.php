<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[Route('/audio-recognition')]
class AudioRecognitionController extends AbstractController
{
  #[Route('/', name: 'app_audioRecognition_index', methods: ['GET'])]
  public function index(): Response
  {
      return $this->render('audio_recognition.html.twig');
  }

  #[Route('/send-audio', name: 'app_audioRecognition_sendAudio', methods: ['POST'])]
  public function sendAudio(Request $request): Response
  {
      $audioFile = $request->files->get('audioFile');

      if (!$audioFile) {
          return new Response('No audio file uploaded', Response::HTTP_BAD_REQUEST);
      }

      $url = 'https://api.openai.com/v1/audio/transcriptions';

      $token = 'token';

      $client = HttpClient::create();

      try {
          $response = $client->request('POST', $url, [
              'headers' => [
                  'Authorization' => 'Bearer ' . $token,
                  'Content-Type' => 'multipart/form-data',
              ],
              'body' => [
                  'file' => fopen($audioFile->getPathname(), 'r'),
                  'model' => 'whisper-1',
                  'response_format' => 'text',
              ],
          ]);

          $transcription = $response->getContent();

          return new Response($transcription, Response::HTTP_OK);

      } catch (TransportExceptionInterface $e) {
          return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
      }
  }
}