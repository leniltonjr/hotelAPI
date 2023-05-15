<?php

namespace projeto;

class Hotel implements HotelInterface {
    public $name;
    private $latitude;
    private $longitude;
    public $price;
    public $distance;

    public function __construct($name, $latitude, $longitude, $price, $distance) {
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->price = $price;
        $this->distance = $distance;
    }

    public function getDistance() {
        return $this->distance;
    }
    public function getPrice() {
        return $this->price;
    }

    /**
     * Calculates the distance between the hotel's location and the user's location.
     *
     * @param float $userLatitude The user's latitude.
     * @param float $userLongitude The user's longitude.
     *
     * @return float The distance between the hotel's location and the user's location, in kilometers.
     */
    public function calculateDistance($userLatitude, $userLongitude) {
        // Implementation to calculate the distance between the hotel's location and the user's location.

        // Convert latitude and longitude values to floats.
        $this->latitude = floatval($this->latitude);
        $this->longitude = floatval($this->longitude);

        // Return 0 if latitude or longitude values are not valid.
        if (!is_float($this->latitude) || !is_float($this->longitude)) {
            return 0;
        }

        // Return 0 if user's latitude or longitude values are not valid.
        if (!is_float($userLatitude) || !is_float($userLongitude)) {
            return 0;
        }

        // Calculate the distance using the Haversine formula.
        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($userLatitude);
        $lon2 = deg2rad($userLongitude);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = 6371 * $c; // 6371 is the average radius of the Earth in kilometers

        return $distance;
    }
}
