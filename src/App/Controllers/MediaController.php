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
        $str = file_get_contents($url);
        $json = json_decode($str, true);
        //echo '<pre>' . print_r($json, true) . '</pre>';  
         
        
        /*$geo = array($place, $latitude, $longitude);
        $vec = array($idPhoto, $geo);*/
        /*
        $length = count($json['data']);
        $array = array($length);
        for ($i = 0; $i <= $length; $i++) {
            $idPhoto = "Id: " . $id;
            $latitude = "Latitude: ". $json['data'][$i]['location']['latitude'];  
            $longitude = "Longitude: ". $json['data'][$i]['location']['longitude'];  
            $place = "Place: ". $json['data'][$i]['location']['name']; 

           //auxVec = array($latitude,$longitude,$place);
            //$array($i) = $auxVec;
        }*/

        $idPhoto = $id;
            $latitude = $json['data'][0]['location']['latitude'];  
            $longitude = $json['data'][0]['location']['longitude'];  
            $place = $json['data'][0]['location']['name']; 

            $objeto = new mediaService();
            $objeto->id = $idPhoto;
            $objeto->latitud = $latitude;
            $objeto->longitud = $longitude;
            $objeto->address = $place;

            /*$objeto = new mediaService($idPhoto,$latitude,$longitude,$place);
            $vars = get_object_vars ($objeto);*/
        return new JsonResponse($objeto->getBruno());
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
