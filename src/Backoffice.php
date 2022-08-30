<?php

namespace Erikgreasy\Backoffice;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class Backoffice {
    private Client $client;
    private string $apiUrl = 'https://api.zoznamrealit.sk/';
    private string $key;
    private string $secret;

    public function __construct(string $key, string $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->client = $this->setupClient();
    }

    public function get($path): object
    {
        $response = $this->client->get( $path );

        return json_decode( $response->getBody()->getContents() );
    }

    /**
     * Prepare Guzzle client using oauth
     */
    private function setupClient(): Client
    {
        $stack = HandlerStack::create();

        $auth = new Oauth1([
            'consumer_key' => $this->key,
            'consumer_secret' => $this->secret,
        ]);

        $stack->push($auth);

        return new Client([
            'base_uri' => $this->apiUrl,
            'handler' => $stack,
            'auth' => 'oauth'
        ]);
    }
}
