<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
use Exception;
use App\Services\Location;

class MediaController
{
	private $token_id;

    public function __construct()
    {

    }
    /***
        Method: getInformationPhotoById
        Description: This method receives an ID photo and a Token ID from Instagram and returns a Json with a lot of information about the photo ID and the first 19 nearest places.
    ***/
    public function getInformationPhotoById($id,$token_id)
    {
    	$this->token_id = $token_id;
        $client = new Client();
		$url = $client->request('GET', $this->getURLInstagramPhoto($id), ['verify' => false]);
		$json_org = json_decode((string)$url->getBody(), true);
        if($json_org['meta']['code'] == 200)
        {
            if(!is_null($json_org['data'][0]['location']))
            {
                $latitude_photo = $json_org['data'][0]['location']['latitude'];
                $longitude_photo = $json_org['data'][0]['location']['longitude'];

				$url_places = $client->request('GET', $this->getURLInstagramNearestPlaces($latitude_photo,$longitude_photo), ['verify' => false]);
                $json_places = json_decode((string)$url_places->getBody(), true);

                $id_place_photo = $json_places['data'][0]['id'];
                $name_place_photo = $json_places['data'][0]['name'];
                $address_photo = $this->getAddressByLatitudeLongitude($latitude_photo,$longitude_photo);
                $image_photo = $this->getURLMapLocation($latitude_photo,$longitude_photo);

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
                        $address_place = $this->getAddressByLatitudeLongitude($latitude_place,$longitude_place);
                        $image_place = $this->getURLMapRelation($latitude_photo,$longitude_photo,$latitude_place,$longitude_place);

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
                $array_media['General Map'][0]['Image'] = $this->getAllGeopointsURLMap($array_media,$array_nearest);
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

    /***
        Method: getURLInstagramPhoto
        Description: This method receives an ID photo from Instagram and returns a Json with a lot of information about the photo ID.
        Example: location.
    ***/
    private function getURLInstagramPhoto($id)
    {
        //$url_photo = "https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=5fa500be04134056ab745cc48cf0382f&max_tag_id=" . $id;
        $url_photo = "https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=". $this->token_id ."&max_tag_id=" . $id;
        return $url_photo;
    }

    /***
        Method: getURLInstagramNearestPlaces
        Description: This method receives a photo's latitude and longitude from Instagram and returns a Json with information about the nearest places of the location that receive.
        Example: latitude, longitude.
    ***/
    private function getURLInstagramNearestPlaces($latitude, $longitude)
    {
        //$url_places = "https://api.instagram.com/v1/locations/search?lat=". $latitude ."&lng=". $longitude ."&client_id=5fa500be04134056ab745cc48cf0382f";
        $url_places = "https://api.instagram.com/v1/locations/search?lat=". $latitude ."&lng=". $longitude ."&client_id=" . $this->token_id;
        return $url_places;
    }

    /***
        Method: getAddressByLatitudeLongitude
        Description: This method receives a place's latitude and longitude and returns a Json with information about this place.
    ***/

    private function getAddressByLatitudeLongitude($latitude, $longitude)
    {
    	$client = new Client();
		$url_address = $client->request('GET', "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&sensor=true", ['verify' => false]);
        $json = json_decode($url_address->getBody(), true);
        $address = $json['results'][0]['formatted_address'];
        return $address;
    }

    /***
        Method: getURLMapLocation
        Description: This method receives a place's latitude and longitude and returns a URL from a Static map that marks that place.
    ***/

    private function getURLMapLocation($latitude, $longitude)
    {
        $url_map = "http://maps.googleapis.com/maps/api/staticmap?center=". $latitude .",". $longitude ."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $latitude. ",". $longitude;
        return $url_map;
    }

    /***
        Method: getURLMapRelation
        Description: This method receives two different place's latitudes and longitudes. The first two coordinates will be used to center and mark the map.
                     The other two will be used to mark the other place. This will be returned a static map that marks the main places on red and the other one on green and a line will be traced between this two points marking the road.
    ***/

    private function getURLMapRelation($latitudePhoto, $longitudePhoto, $nearestLaditude, $nearestLongitude)
    {
        $url_map = "http://maps.googleapis.com/maps/api/staticmap?center=". $latitudePhoto.",".$longitudePhoto."&zoom=15&scale=false&size=640x480&path=color:0x0000ff|weight:5|".$latitudePhoto.",".$longitudePhoto."|".$nearestLaditude.",".$nearestLongitude."&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C".$latitudePhoto.",".$longitudePhoto."&markers=size:mid%7Ccolor:0x2dbd02%7Clabel:-%7C". $nearestLaditude. ",". $nearestLongitude;
        return $url_map;
    }

    /***
        Method: getAllGeopointsURLMap
        Description: This method receives two Arrays. The first array contains information about the ID photo that we use in the getInformationPhotoById method.
                     The other one contains information about the nearest places. The method will be returned a static map that marks the main place on red and the nearest places on green.

    ***/

    private function getAllGeopointsURLMap($arrayMedia,$arrayNearest)
    {
        $url_map = "http://maps.googleapis.com/maps/api/staticmap?center=".$arrayMedia['Location'][0]['Geopoint'][0]['Latitude'].",".$arrayMedia['Location'][0]['Geopoint'][0]['Longitude']."&zoom=15&scale=false&size=640x480&maptype=roadmap&format=png&visual_refresh=true&";
        $url_map.= "markers=size:mid%7Ccolor:0xff3900%7Clabel:A%7C". $arrayMedia['Location'][0]['Geopoint'][0]['Latitude']. "," .$arrayMedia['Location'][0]['Geopoint'][0]['Longitude']. "&";

        for($i = 0; $i < count($arrayNearest);$i++)
        {
            $url_map.= "markers=size:mid%7Ccolor:0x2dbd02%7Clabel:-%7C". $arrayNearest[$i]['Location'][0]['Geopoint'][0]['Latitude']. ",". $arrayNearest[$i]['Location'][0]['Geopoint'][0]['Longitude'] ."&";
        }
        $url_map = rtrim($url_map,"&");
        return $url_map;
    }
}
