<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Exceptions;
use App\Entities\Location;
use App\Services\iInformationPhotoService;
use App\Services\iInformationAddressService;
use App\Services\iStaticMapService;

class MediaController
{
    private $information_photo_service;
    private $information_address_service;
    private $static_map_service;

    public function __construct(IInformationPhotoService $information_photo_service, IInformationAddressService $information_address_service,
        IStaticMapService $static_map_service)
    {
        $this->information_photo_service = $information_photo_service;
        $this->information_address_service = $information_address_service;
        $this->static_map_service = $static_map_service;
    }
    /**
     *	getInformationPhotoById's method.
     * 	This method receives an ID photo and a Token ID from Instagram and returns a Json with a lot of information about the photo ID and the first 19 nearest places.
     */
    public function getInformationPhotoById($id, Request $request)
    {
        $token_id = $request->query->get("instagram_token");
        $location_array_org = $this->information_photo_service->getPhotoInformationFromService($id, $token_id);
        if ($this->information_photo_service->__get("code")) {
            if (!is_null($location_array_org)) {
                $latitude_photo = $location_array_org['latitude'];
                $longitude_photo = $location_array_org['longitude'];
                $array_places = $this->information_photo_service->getPhotoInformationFromServiceNearestPlaces($latitude_photo, $longitude_photo);

                $id_place_photo = $array_places[0]['id'];
                $name_place_photo = $array_places[0]['name'];
                $address_photo = $this->information_address_service->getAddressFromService($latitude_photo, $longitude_photo);
                $image_photo = $this->static_map_service->getURLMapLocation($latitude_photo, $longitude_photo);

                $location_photo = new Location($id_place_photo, $latitude_photo, $longitude_photo, $name_place_photo, $address_photo, $image_photo);
                $array_media['Status'] = 200;
                $array_media['Id'] = $id;
                $array_media = $this->getLocationAsArray($array_media, $location_photo);

                $array_nearest = array();
                $length = count($array_places);
                $is_first = true;
                for ($i = 0; $i < $length; $i++) {
                    if ($is_first) {
                        /*the first element is the same that receives getURLInstagramNearestPlaces and we used in the array_media.*/
                        $is_first = false;
                    } else {
                        $id_place = $array_places[$i]['id'];
                        $latitude_place = $array_places[$i]['latitude'];
                        $longitude_place = $array_places[$i]['longitude'];
                        $name_place = $array_places[$i]['name'];
                        $address_place = $this->information_address_service->getAddressFromService($latitude_place, $longitude_place);

                        $aux_array_nearest_places = array();
                        $aux_array_nearest_places[0]['Location'][0]['Geopoint'][0]['Latitude'] = $latitude_place;
                        $aux_array_nearest_places[0]['Location'][0]['Geopoint'][0]['Longitude'] = $longitude_place;
                        $image_place = $this->static_map_service->getURLMapRelation($latitude_photo, $longitude_photo, $aux_array_nearest_places);

                        $location_place = new Location($id_place, $latitude_place, $longitude_place, $name_place, $address_place, $image_place);

                        $array_my_nearest = array();
                        $array_my_nearest = $this->getLocationAsArray($array_my_nearest, $location_place);
                        array_push($array_nearest, $array_my_nearest);
                    }
                }
                $array_media['General Map'][0]['Image'] = $this->static_map_service->getURLMapRelation($latitude_photo, $longitude_photo, $array_nearest);
                $array_media['Nearest Places'] = $array_nearest;
            } else {
                throw new MediaLocationException("Photo's location can not be Null.");
            }
        } else {
            throw new MediaLocationException("An error ocurred getting the id photo of instagram.");
        }
        return new JsonResponse($array_media);
    }

    /**
     *	getLocationAsArray's method.
     * 	This method receives a Location Array and an instance of Location and returns the same array with all the information that contains location_place.
     */
    private function getLocationAsArray(Array &$locations_array, Location $location_place)
    {
        $location_array['Location'][0]['Id'] = $location_place->__get("id");
        $location_array['Location'][0]['Geopoint'][0]['Latitude'] = $location_place->__get("latitude");
        $location_array['Location'][0]['Geopoint'][0]['Longitude'] = $location_place->__get("longitude");
        $location_array['Location'][0]['Place'][0]['Name'] = $location_place->__get("name");
        $location_array['Location'][0]['Place'][0]['Address'] = $location_place->__get("address");
        $location_array['Location'][0]['Map'][0]['Image'] = $location_place->__get("image");

        return $location_array;
    }
}
