<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;
use App\Services\Location;
use App\Services\iInformationPhotoService;
use App\Services\iInformationAddressService;
use App\Services\iStaticMapService;

class MediaController
{
    private $iInformationPhotoService;
    private $iInformationAddressService;
    private $iStaticMapService;
    private $location;

    public function __construct(iInformationPhotoService $iInformationPhotoService, iInformationAddressService $iInformationAddressService,
        iStaticMapService $iStaticMapService, Location $location)
    {
        $this->iInformationPhotoService = $iInformationPhotoService;
        $this->iInformationAddressService = $iInformationAddressService;
        $this->iStaticMapService = $iStaticMapService;
        $this->location = $location;
    }
    /***
        Method: getInformationPhotoById
        Description: This method receives an ID photo and a Token ID from Instagram and returns a Json with a lot of information about the photo ID and the first 19 nearest places.
    ***/
    public function getInformationPhotoById($id, $token_id)
    {
        $location_array_org = $this->iInformationPhotoService ->getPhotoInformationFromService($id, $token_id);
        if ($this->iInformationPhotoService->__get("code")) {
            if (!is_null($location_array_org) && !is_null($location_array_org)) {
                $latitude_photo = $location_array_org[0]['latitude'];
                $longitude_photo = $location_array_org[0]['longitude'];
                $array_places = $this->iInformationPhotoService->getPhotoInformationFromServiceNearestPlaces($latitude_photo, $longitude_photo, $token_id);

                $id_place_photo = $array_places[0]['id'];
                $name_place_photo = $array_places[0]['name'];
                $address_photo = $this->iInformationAddressService->getAddressFromService($latitude_photo, $longitude_photo);
                $image_photo = $this->iStaticMapService->getURLMapLocation($latitude_photo, $longitude_photo);

                $location_photo = $this->location;
                $location_photo->__set("id", $id_place_photo);
                $location_photo->__set("latitude", $latitude_photo);
                $location_photo->__set("longitude", $longitude_photo);
                $location_photo->__set("name", $name_place_photo);
                $location_photo->__set("address", $address_photo);
                $location_photo->__set("image", $image_photo);
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
                $length = count($array_places);
                $is_first = true;
                for ($i = 0; $i < $length; $i++) {
                    if ($is_first) {
                        //the first element is the same that receives getURLInstagramNearestPlaces and we used in the array_media.
                        $is_first = false;
                    } else {
                        $id_place = $array_places[$i]['id'];
                        $latitude_place = $array_places[$i]['latitude'];
                        $longitude_place = $array_places[$i]['longitude'];
                        $name_place = $array_places[$i]['name'];
                        $address_place = $this->iInformationAddressService->getAddressFromService($latitude_place, $longitude_place);

                        $aux_array_nearest_places = array();
                        $aux_array_nearest_places[0]['Location'][0]['Geopoint'][0]['Latitude'] = $latitude_place;
                        $aux_array_nearest_places[0]['Location'][0]['Geopoint'][0]['Longitude'] = $longitude_place;
                        $image_place = $this->iStaticMapService->getURLMapRelation($latitude_photo, $longitude_photo, $aux_array_nearest_places);

                        $location_place = $this->location;
                        $location_place->__set("id", $id_place);
                        $location_place->__set("latitude", $latitude_place);
                        $location_place->__set("longitude", $longitude_place);
                        $location_place->__set("name", $name_place);
                        $location_place->__set("address", $address_place);
                        $location_place->__set("image", $image_place);
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
                $array_media['General Map'][0]['Image'] = $this->iStaticMapService->getURLMapRelation($latitude_photo, $longitude_photo, $array_nearest);
                $array_media['Nearest Places'] = $array_nearest;
            } else {
                throw new Exception("Photo's location can not be Null.");
            }
        } else {
            throw new Exception("An error ocurred getting the id photo of instagram.");
        }
        return new JsonResponse($array_media);
    }
}
