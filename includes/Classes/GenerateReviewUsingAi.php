<?php

namespace Manage\Review\Classes;

use Exception;
use GuzzleHttp\Client;
class GenerateReviewUsingAi{
    static public function generateReviewUsingChatGPT( $description ) {
        $apiKey = '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'; // Replace with your actual API key
        $client = new Client();

        try {
            $response = $client->post('https://api.openai.com/v1/engines/davinci-codex/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'prompt' => "Read this product description and write a review: $description",
                    'max_tokens' => 150,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['text'] ?? 'No review generated.';
        } catch (Exception $e) {
            return 'Error generating review: ' . $e->getMessage();
        }
    }

}