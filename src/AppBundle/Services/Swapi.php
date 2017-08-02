<?php

namespace AppBundle\Services;

use GuzzleHttp\Client;
use JMS\Serializer\Serializer;


class Swapi
{
    /** @var Client $swapiClient */
    private $swapiClient;
    /** @var Serializer $serializer */
    private $serializer;
    /** @var  $baseUri */
    private $baseUri;

    /**
     * Swapi constructor.
     * @param Client $swapiClient
     * @param Serializer $serializer
     * @param $baseUri
     */
    public function __construct(Client $swapiClient, Serializer $serializer, $baseUri)
    {
        $this->swapiClient = $swapiClient;
        $this->serializer = $serializer;
        $this->baseUri = $baseUri;
    }

    /**
     * get All films from Swapi API
     * @return array
     */
    public function getFilms()
    {
        // call API
        $uri = $this->baseUri;
        try {
            $response = $this->swapiClient->get($uri);
        } catch (\Exception $e) {
            //log error
            return ['error' => 'Informations are not available for the moment.'];
        }
        // deserialize json data
        $data = $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');

        // construct [] of movies with characters associated
        $films = [];
        foreach ($data['results'] as $key => $film) {
            $films[$key]['title'] = $film['title'];
            $films[$key]['abstract'] = $film['opening_crawl'];
            $films[$key]['date'] = date_create_from_format('Y-m-d', $film['release_date']);

            $characters = $film['characters'];
            $charactersList = [];
            foreach ($characters as $character) {
                try {
                    $response = $this->swapiClient->get($character);
                }  catch (\Exception $e) {
                    return ['error' => 'Informations are not available for the moment.'];
                }
                $dataCharacter = $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');
                $charactersList[]['name'] = $dataCharacter['name'];
            }
            $films[$key]['characters'] = $charactersList;
        }
        return $films;
    }

}