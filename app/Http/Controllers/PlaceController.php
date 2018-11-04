<?php

namespace App\Http\Controllers;

use App\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index(Request $request) {
        $errors = array();
        $Params = array("name", "lat", "lon", "radius", "sortBy");
        foreach ($Params as $Param) {
            if (!isset($request->$Param) or $request->$Param == "") {
                $errors[] = "Parâmetro " . $Param . " não pode ser vazio.";
            }
        }
        if (count($errors) > 0) {
            return array("status" => "failure", "errors" => $errors);
        }

        //$place = new Place($_ENV["GOOGLE_KEY"], "-22.999156", "-43.3679863", 2000);
        $place = new Place("AIzaSyDxDE5o3g9rdrS6_-HSv-Gz54qU4LUazBE", $request->lat, $request->lon, $request->radius, $request->sortBy);
        $results = $place->findNearBy($request->name);        
        if (count($results) > 0) {
            return $place->parseResults($results, $request->sortBy);
        }

        $results = $place->findByText($request->name);
        if (count($results) > 0) {
            return $place->parseResults($results, $request->sortBy);
        }

        return array();
    }

}
