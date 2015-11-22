<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;
use App\Services\Location;
use App\Services\InformationPhotoService;

class MediaController
{
    public function __construct()
    {

    }
    /***
        Method: getInformationPhotoById
        Description: This method receives an ID photo and a Token ID from Instagram and returns a Json with a lot of information about the photo ID and the first 19 nearest places.
    ***/
    public function getInformationPhotoById($id,$token_id)
    {
		$informationPhotoService = new InformationPhotoService();
		$json_org = $informationPhotoService->getPhotoInformationFromService($id,$token_id);
        if($json_org['meta']['code'] == 200)
        {
            if(!is_null($json_org['data'][0]['location']))
            {
                $latitude_photo = $json_org['data'][0]['location']['latitude'];
                $longitude_photo = $json_org['data'][0]['location']['longitude'];
                $json_places = $informationPhotoService->getPhotoInformationFromServiceNearestPlaces($latitude_photo,$longitude_photo, $token_id);

                $id_place_photo = $json_places['data'][0]['id'];
                $name_place_photo = $json_places['data'][0]['name'];
                $address_photo = $informationPhotoService->getAddressFromService($latitude_photo, $longitude_photo);
                $image_photo = $informationPhotoService->getURLMapLocation($latitude_photo,$longitude_photo);

                $location_photo = new location($id_place_photo,$latitude_photo,$longitude_photo,$name_place_photo,$address_photo,$image_photo);
                $array_media = array();
                $array_media['Status'] = 200;
                $array_media['Id'] = $id;
                $array_media['Location'][0]['Id'] = $location_photo->__get("id");
                $array_media['Location'][0]['Geopoint'][0]['Latitude'] = $location_photo->__get("latitude");
                $array_media['Location'][0]['Geopoint'][0]['Longitude'] = $location_photo->__get("longitude");
                $array_media['Location'][0]['Place'][0]['Name'] = $location_photo->__get("name");
                $array_media['Location'][0]['Place'][0]['Address'] = $location_photo->__get("address");
                $array_media['Location'][0]['Map'][0]['Image'] = $location_photo->__get("image");

                $array_nearest = array();
                $length = count($json_places['data']);
                $is_first = true;
                for ($i = 0; $i < $length; $i++) {
                    if($is_first)
                    {
                        //the first element is the same that receives getURLInstagramNearestPlaces and we used in the array_media.
                        $is_first = false;
                    }
                    else
                    {
                        $id_place = $json_places['data'][$i]['id'];
                        $latitude_place = $json_places['data'][$i]['latitude'];
                        $longitude_place = $json_places['data'][$i]['longitude'];
                        $name_place = $json_places['data'][$i]['name'];
                        $address_place = $informationPhotoService->getAddressFromService($latitude_place, $longitude_place);

                        $aux_array_nearest_places = array();
                        $aux_array_nearest_places[0]['Location'][0]['Geopoint'][0]['Latitude'] = $latitude_place;
                        $aux_array_nearest_places[0]['Location'][0]['Geopoint'][0]['Longitude'] = $longitude_place;
                        $image_place = $informationPhotoService->getURLMapRelation($latitude_photo,$longitude_photo,$aux_array_nearest_places);

                        $location_place = new location($id_place,$latitude_place,$longitude_place,$name_place,$address_place,$image_place);
                        $array_my_nearest = array();
                        $array_my_nearest['Location'][0]['Id'] = $location_place->__get("id");
                        $array_my_nearest['Location'][0]['Geopoint'][0]['Latitude'] = $location_place->__get("latitude");
                        $array_my_nearest['Location'][0]['Geopoint'][0]['Longitude'] = $location_place->__get("longitude");
                        $array_my_nearest['Location'][0]['Place'][0]['Name'] = $location_place->__get("name");
                        $array_my_nearest['Location'][0]['Place'][0]['Address'] = $location_place->__get("address");
                        $array_my_nearest['Location'][0]['Map'][0]['Image'] = $location_place->__get("image");

                        array_push($array_nearest, $array_my_nearest);
                    }
                }
                $array_media['General Map'][0]['Image'] = $informationPhotoService->getURLMapRelation($latitude_photo,$longitude_photo,$array_nearest);
                $array_media['Nearest Places'] = $array_nearest;
            }
            else
            {
                throw new Exception("Photo's location can not be Null.");
            }
        }
        else
        {
            throw new Exception("An error ocurred getting the id photo of instagram.");
        }
        return new JsonResponse($array_media);
    }
}
