<?php

namespace App;

use Curl\Curl;
use App\Crawler;

class Place {

    private $apiKey = "";
    private $curl;
    public $results;
    public $lat;
    public $lon;
    public $radius = 2000;

    function __construct($apiKey, $lat, $lon, $radius) {
        $this->apiKey = $apiKey;
        $this->curl = new Curl();
        $this->lat = $lat;
        $this->lon = $lon;
        $this->radius = $radius;
    }

    function findNearBy($string) {
        $results = $this->curl->get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", array(
            "key" => $this->apiKey,
            "query" => $string,
            "location" => $this->lat . "," . $this->lon,
            "language" => "pt-BR",
            "keyword" => $string,
            "rankby"=> "distance"
        ));
        $response = json_decode($results->response, 1);

        if (!isset($response["results"])) {
            return array();
        }

        return $response["results"];
    }

    function findByText($string) {
        /*$results = $this->curl->get("https://maps.googleapis.com/maps/api/place/findplacefromtext/json", array(
            "key" => $this->apiKey,
            "input" => $string,
            "locationbias" => "circle:20000@-22.999156,-43.3679863",
            "inputtype" => "textquery",
            "fields" => "photos,formatted_address,name,rating,opening_hours,geometry,place_id"
        ));*/
        $results = $this->curl->get("https://maps.googleapis.com/maps/api/place/textsearch/json", array(
            "key" => $this->apiKey,
            "query" => $string,
            "location" => $this->lat . "," . $this->lon,
            "radius" => $this->radius,
            "language" => "pt-BR"
        ));

        $response = json_decode($results->response, 1);

        if (!isset($response["results"])) {
            return array();
        }

        return $response["results"];
    }

    public function convertDay($dayNumber) {
        $dayW = ($dayNumber == 0) ? 7 : intval($dayNumber);
        return $dayW;
    }

    public function sortByHours($results) {
        date_default_timezone_set("America/Sao_Paulo");
        $response = array();
        $jumped = array();
        $day = date("w");
        $hour = intval(date("h"));
        $convertedDay = $this->convertDay($day);
        $lesser = array(); # INDEX, VALUE
        foreach($results as $index => $result) {
            # INSERE ENTRADAS SEM DADOS DE HORAS EM ARRAY SEPARADO
            if (count($result["days"]) == 0) {
                $jumped[] = $index;
                continue;
            }
            if (!isset($result["days"][$convertedDay]["values"]) or $result["days"][$convertedDay]["values"][$hour] == 0) {
                $jumped[] = $index;
                continue;
            }
            # CALCULA MENOR FRENQUENCIA
            $lesser[$index] = $result["days"][$convertedDay]["values"][$hour];
            /*foreach ($result["days"] as $dayNumber => $hours) {
                $convertedDay = $this->convertDay($dayNumber);
                # VERIFICA SE É O MESMO DIA
                if ($day != $convertedDay) {
                    continue;
                }
                # VERIFICA SE É A MESMA HORA
                foreach ($hours["values"] as $hour => $popularity) {
                    if (intval($hour) != $hour) {
                        continue;
                    }
                    $lesser[$index] = $popularity;
                }
            }*/
        }

        # ORGANIZA ARRAY DE HORARIOS E POPULARIDADE
        asort($lesser);

        foreach ($lesser as $index => $value) {
            $response[] = $results[$index];
        }

        foreach ($jumped as $index => $value) {
            $response[] = $results[$value];
        }        

        return $response;
    }

    public function parseResults($results, $sortBy) {

        $index = 0;
        $limit = 5;
        $response = array();
        foreach ($results as $key => $result) {
            $placeIdentifier = array();
            # VERIFICA SE INDEX "name" ESTÁ PRESENTE NO RESULTADO
            if (isset($result["name"])) {
                $placeIdentifier[] = $result["name"];
            }
            # VERIFICA SE INDEX "vicinity" ESTÁ PRESENTE NO RESULTADO
            if (isset($result["vicinity"])) {
                $placeIdentifier[] = $result["vicinity"];
                $result["final_address"] = $result["vicinity"];
            }
            # VERIFICA SE INDEX "formatted_address" ESTÁ PRESENTE NO RESULTADO
            else if (isset($result["formatted_address"])) {
                $placeIdentifier[] = $result["formatted_address"];
                $result["final_address"] = $result["formatted_address"];
            }
            if (count($placeIdentifier) == 0) {
                continue;
            }

            $placeIdentifierStr = implode(", ", $placeIdentifier);
            
            $placeSearch = new Crawler($placeIdentifierStr);
            $extract = $placeSearch->ExtractJSON();
            $jump = false;
            if ($extract === false) {
                $jump = true;
            }
            $info = $placeSearch->ExtractInformation();
            if ($jump == true or $info === false) {
                $placeSearch->Days = $placeSearch->noHours($result);
            } else {
                $placeSearch->ExtractLocalInfo($info);
            }
            $result["days"] = $placeSearch->Days;
            $response[] = $result;
        }

        /*
        * SORT BY
        */

        if ($sortBy == "popularity") {
            $response = $this->sortByHours($response);   
        }

        return $response;

    }

}