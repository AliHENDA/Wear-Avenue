<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;

class Mail {

    private $api_key_public ="e11cdfb8af3d6fcc6ab010c3d9dfffb9";
    private $api_key_secret = "5441d1a013b2d734bd76680d82fdaf45";

    public function send($to_email, $to_name, $subject, $content) {

            $mj = new Client($this->api_key_public, $this->api_key_secret,true,['version' => 'v3.1']);
            $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "sapes-avenue@outlook.fr",
                        'Name' => "Sapes"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 4852285,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());
    }
}