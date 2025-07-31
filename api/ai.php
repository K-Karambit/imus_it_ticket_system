<?php

include __DIR__ . '/../config/config.php';
include __DIR__ . '/../config/headers.php';


use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;


function ai($text = null, $action = null)
{
    if ($action === 'translate') {
        $prompt = "Translate the following text into English. Ignore if already in English: " . $text;
    } elseif ($action === 'grammarCheck') {
        $prompt = "Check the grammar of the following text and correct it: " . $text;
    } elseif ($action === 'summarize') {
        $prompt = "Summarize the following text: " . $text;
    } elseif ($action === 'rephrase') {
        $prompt = "Rephrase the following text and please keep the original language: " . $text;
    } elseif ($action === 'generate') {
        $prompt = "Generate a description base on subject:" . $_GET['short_description'];
    } else {
        http_response_code(400);
        return json_encode(['error' => 'Invalid action specified']);
    }

    $prompt .= "\n\nPlease provide a response without any additional formatting or any explanation.";
    $prompt .= "\n\nPlease return result in pure raw json format {status: bool, message: changes, result: result}";

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . GEMINI_KEY;

    $client = new Client();

    $response = $client->request('POST', $url, [
        'headers' => [
            'Content-Type' => 'application/json',
        ],
        'json' => [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => $prompt
                        ]
                    ]
                ]
            ]
        ]
    ]);

    $body = $response->getBody()->getContents();
    $result = json_decode($body, true);

    $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

    $cleaned = preg_replace('/```(?:json|text)?\n?|\n?```/', '', $text);

    return ($cleaned);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo ai($_POST['text'], $_POST['action']);
} else {
    echo false;
}
