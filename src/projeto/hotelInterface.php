<?php

namespace projeto;

/**
 * HotelInterface is an interface for hotel objects that can calculate their distance from a given location.
 */
interface HotelInterface {
    
    /**
     * Calculates the distance between the hotel's location and the given location.
     *
     * @param float $latitude The latitude of the location.
     * @param float $longitude The longitude of the location.
     * 
     * @return float The distance in kilometers between the hotel's location and the given location.
     */
    public function calculateDistance($latitude, $longitude);
}
