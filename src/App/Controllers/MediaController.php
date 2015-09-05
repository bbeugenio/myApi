<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\mediaService;

class MediaController
{

    public function __construct()
    {

    }

    public function getPhotoById($id)
    {
        $url = "https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=5fa500be04134056ab745cc48cf0382f&max_tag_id=" . $id;
        $urlPlaces = "";
        $str = file_get_contents($url);
        $json = json_decode($str, true);

        $idPhoto = $id;
        $latitude = $json['data'][0]['location']['latitude'];  
        $longitude = $json['data'][0]['location']['longitude'];  
        
        $urlPlaces = "https://api.instagram.com/v1/locations/search?lat=".$latitude."&lng=".$longitude. "&client_id=5fa500be04134056ab745cc48cf0382f";
        $aux = file_get_contents($urlPlaces);
        $jsonPlaces = json_decode($aux, true);
        $length = count($jsonPlaces['data']);

        $array = array();
        for ($i = 0; $i < $length; $i++) {
            $idPhoto = "";
            $latitude = "";
            $longitude = "";  
            $place = ""; 

            $idPhoto = $id;
            if(!is_null($jsonPlaces['data'][$i]['latitude'] ))
            {
                $latitude = $jsonPlaces['data'][$i]['latitude'];  
            }
            if(!is_null($jsonPlaces['data'][$i]['longitude'] ))
            {
                $longitude = $jsonPlaces['data'][$i]['longitude'];  
            }
            if(!is_null($jsonPlaces['data'][$i]['name']))
            {
                $address = $jsonPlaces['data'][$i]['name'];  
            }
            $objeto = new mediaService($idPhoto,$latitude,$longitude,$address);

            array_push($array, $objeto);
        }

        return new JsonResponse($array);
    }

    public function getJson()
    {
        $str = file_get_contents("https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=5fa500be04134056ab745cc48cf0382f");
        $json = json_decode($str, true);
        //echo '<pre>' . print_r($json, true) . '</pre>';  
        $tag = $json['data'][0]['tags'];  
        $tags = "";
        foreach ($json['data'][0]['tags'] as $field => $value) {
            $tags .= $value . "\n";    
        }
        //echo '<pre>' . print_r($tags) . '</pre>';  
        return new JsonResponse($tag);
    }
}
