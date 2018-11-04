<?php

namespace App\Http\Controllers;

use App\Crawler;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request) {
        $crawler = new Crawler($request->name, $request->addr);
        $extract = $crawler->ExtractJSON();
        $info = $crawler->ExtractInformations();        
        if ($extract === false) {
            return false;
        }
        $crawler->ExtractLocalInfo($info);
        return $crawler->Days;
    }

}
