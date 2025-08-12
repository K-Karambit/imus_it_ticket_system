<?php

include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';

use GuzzleHttp\Client;

function ai($text = null, $action = null)
{
    switch ($action) {
        case 'translate':
            $prompt = "Translate the following text into English. Ignore if already in English: $text";
            break;
        case 'grammarCheck':
            $prompt = "Check the grammar of the following text and correct it: $text";
            break;
        case 'summarize':
            $prompt = "Summarize the following text: $text";
            break;
        case 'rephrase':
            $prompt = "Rephrase the following text and please keep the original language: $text";
            break;
        case 'generate':
            $subject = $_GET['short_description'] ?? '';
            $prompt = "Generate a description base on subject: $subject";
            break;
        default:
            http_response_code(400);
            return json_encode(['error' => 'Invalid action specified']);
    }

    $prompt .= "\n\nPlease provide a response without any additional formatting or any explanation.";
    $prompt .= "\n\nPlease return result in pure raw json format {status: bool, message: changes, result: result}";

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . GEMINI_KEY;
    $client = new Client();

    try {
        $response = $client->request('POST', $url, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt]
                        ]
                    ]
                ]
            ]
        ]);
        $body = $response->getBody()->getContents();
        $result = json_decode($body, true);
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $cleaned = preg_replace('/```(?:json|text)?\n?|\n?```/', '', $text);
        return $cleaned;
    } catch (Exception $e) {
        http_response_code(500);
        return json_encode(['error' => 'AI request failed', 'details' => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo ai($_POST['text'] ?? '', $_POST['action'] ?? '');
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
