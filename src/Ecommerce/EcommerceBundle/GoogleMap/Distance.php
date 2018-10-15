<?php

namespace Ecommerce\EcommerceBundle\GoogleMap;

use Ecommerce\EcommerceBundle\GoogleMap\GmapApi;
use Ecommerce\EcommerceBundle\GoogleMap\Misc;

class Distance
{

    public $data;

    public function __construct($adresse,$cp,$ville)
    {
        $data =GmapApi::geocodeAddress($adresse.$cp.$ville);

        $this->data= $data; 
    }

   public function valideAdresse()
   {

        if (!empty($this->data['lat']) && !empty($this->data['lng'])) 
        {
            return 1;
        }
        else
        {
            return 0;
        }

    }


   public function distanceCheck(Distance $adresse2)
   {

    $distance = round(Misc::distance($this->data['lat'], $this->data['lng'], $adresse2->data['lat'], $adresse2->data['lng']));

    return $distance;
   }
   

}