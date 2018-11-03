<?php 

namespace App;

class Crawler {

    public $UserAgent = array("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1)", "AppleWebKit/537.36 (KHTML, like Gecko)", "Chrome/54.0.2840.98 Safari/537.36");
    public $PlaceIdentifier = "";
    public $JSON = array();
    public $GeneralInfo;
    public $PopularHours;
    public $Days = array(
        1 => ["name" => "Segunda-feira"],
        2 => ["name" => "Terça-feira"],
        3 => ["name" => "Quarta-feira"],
        4 => ["name" => "Quinta-feira"],
        5 => ["name" => "Sexta-feira"],
        6 => ["name" => "Sábado"],
        7 => ["name" => "Domingo"]
    );


    /*
    * GERA O IDENTIFICADOR DE LOCAL COM BASE NO NOME E ENDEREÇO
    */

    function __construct($Name, $Addr) {
        $this->PlaceIdentifier = $Name.", ".$Addr;
    }

    /*
    * PREPARA A URL PARA CHAMADA HTTP
    */

    function PrepareURL() {
        $params = array(
            "tbm" => "map",
            "tch" => 1,
            "hl" => "pt-br",
            "q" => $this->PlaceIdentifier,
            "pb" => "!4m12!1m3!1d4005.9771522653964!2d-122.42072974863942!3d37.8077459796541!2m3!1f0!2f0!3f0!3m2!1i1125!2i976"
                .  "!4f13.1!7i20!10b1!12m6!2m3!5m1!6e2!20e3!10b1!16b1!19m3!2m2!1i392!2i106!20m61!2m2!1i203!2i100!3m2!2i4!5b1"
                .  "!6m6!1m2!1i86!2i86!1m2!1i408!2i200!7m46!1m3!1e1!2b0!3e3!1m3!1e2!2b1!3e2!1m3!1e2!2b0!3e3!1m3!1e3!2b0!3e3!"
                .  "1m3!1e4!2b0!3e3!1m3!1e8!2b0!3e3!1m3!1e3!2b1!3e2!1m3!1e9!2b1!3e2!1m3!1e10!2b0!3e3!1m3!1e10!2b1!3e2!1m3!1e"
                .  "10!2b0!3e4!2b1!4b1!9b0!22m6!1sa9fVWea_MsX8adX8j8AE%3A1!2zMWk6Mix0OjExODg3LGU6MSxwOmE5ZlZXZWFfTXNYOGFkWDh"
                .  "qOEFFOjE!7e81!12e3!17sa9fVWea_MsX8adX8j8AE%3A564!18e15!24m15!2b1!5m4!2b1!3b1!5b1!6b1!10m1!8e3!17b1!24b1!"
                .  "25b1!26b1!30m1!2b1!36b1!26m3!2m2!1i80!2i92!30m28!1m6!1m2!1i0!2i0!2m2!1i458!2i976!1m6!1m2!1i1075!2i0!2m2!"
                .  "1i1125!2i976!1m6!1m2!1i0!2i0!2m2!1i1125!2i20!1m6!1m2!1i0!2i956!2m2!1i1125!2i976!37m1!1e81!42b1!47m0!49m1"
                .  "!3b1"
        );
        $query = http_build_query($params);
        $search_url = "https://www.google.com.br/search?".$query."";   
        return $search_url;
    }

    /*
    * EXTRAI DADOS JSON DA CHAMADA HTTP
    */

    function ExtractJSON() {
        $AgentLimit = (count($this->UserAgent) - 1);
        $AgentID = rand(0, $AgentLimit);
        $UserAgent = $this->UserAgent[$AgentID];

        $url = $this->PrepareURL();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);       
        curl_close ($ch);
        
        $data = explode("/\*\*/", $response);
        $end = strrpos($data[0], "}");
        
        if ($end === false) {
            return false;
        }

        $raw_json = substr($data[0], 0, ($end + 1));
        $base = json_decode($raw_json, 1);        
        $json = json_decode(substr($base["d"], 4), 1);

        $this->JSON = $json;

        return $json;
    }

    /*
    * FUNÇÃO PARA EXTRAIR INDICES DE ARRAY MULTIDIMENSIONAL
    */

    function ExtractIndex() {
        $Args = func_get_args();
        if (!is_array($Args[0])) {
            return false;
        }
        $Array = $Args[0];
        $ArgCount = count($Args);
        for($i=1;$i<$ArgCount;$i++) {
            $Index = $Args[$i];
            if (!isset($Array[$Index])) {
                echo "INDEX $Index not found!";
                return false;
            }
            $Array = $Array[$Index];
        }
        return $Array;
    }

    /*
    * EXTRAI INFORMAÇÕES PRINCIPAIS DO OBJETO JSON
    */

    function ExtractInformations() {        
        return $this->ExtractIndex($this->JSON, 0, 1, 0, 14);
    }

    function ExtractRating($Array) {
        return $this->ExtractIndex($Array, 4, 7);
    }

    function ExtractRatingNumeric($Array) {
        return $this->ExtractIndex($Array, 4, 8);
    }

    function ExtractLocalInfo($Array) {
        $PopularTimes = $this->ExtractIndex($Array, 84, 0);
        $this->StorePopularHours($PopularTimes);
    }

    /*
    * SALVA MOVIMENTAÇÃO DO ENDEREÇO NO OBJETO
    */

    function StorePopularHours($Data) {
        $PopularHours = array();
        foreach ($Data as $DayData) {
            $DayNumber = $DayData[0];
            # GERA ARRAY DE HORAS
            $DayHours = array();
            for($i=0;$i<24;$i++) {
                $DayHours[$i] = 0;
            }
            if (isset($DayData[1]) and count($DayData[1]) > 0) {
                # POPULA ARRAY DE HORAS
                foreach ($DayData[1] as $DayHour) {
                    $Hour = $DayHour[0];
                    $Popularity = $DayHour[1];
                    $DayHours[$Hour] = $Popularity;
                }            
            }
            # SALVA NO ARRAY
            $this->Days[$DayNumber]["values"] = $DayHours;
        }
        # SALVA RESULTADO NO OBJETO
        $this->PopularHours = $PopularHours;
    }

}