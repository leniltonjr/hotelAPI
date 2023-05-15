<?php

namespace projeto;

/**
 * A class for interacting with hotels.
 *
 * This class provides methods for finding hotels near a user's location.
 */
class HotelAPI {
    private $hotels = [];

    /**
     * Creates a new HotelAPI instance.
     *
     * @param HotelInterface[] $hotels An array of HotelInterface objects.
     *
     * @throws \InvalidArgumentException if the input array contains objects that are not HotelInterface.
     */
    public function __construct(array $hotels) {
        foreach ($hotels as $hotel) {
            if (!($hotel instanceof HotelInterface)) {
                throw new \InvalidArgumentException('HotelAPI expects an array of HotelInterface objects.');
            }
        }
        $this->hotels = $hotels;
    }

    /**
     * Gets the nearby hotels from a user's location.
     *
     * @param float $userLatitude The user's latitude.
     * @param float $userLongitude The user's longitude.
     * @param string $orderBy The order to sort the hotels (by proximity or price per night).
     *
     * @return HotelInterface[] An array of HotelInterface objects sorted by the given order.
     */
    public function getNearbyHotels(float $userLatitude, float $userLongitude, string $orderBy = "proximity"): array {

        
        // Calculates the distance of each hotel from the user's location.
        foreach ($this->hotels as $hotel) {
            // Calculates the distance between the hotel and the user's location.
            $distance = $hotel->calculateDistance($userLatitude, $userLongitude);
            $hotel->distance =$distance;
        }

        // Sorts the hotels by the given order (by proximity or price per night).
        if ($orderBy == "proximity") {
            usort($this->hotels, function($a, $b) {
                return $a->getDistance() - $b->getDistance();
            });
        } elseif ($orderBy == "pricepernight") {
            usort($this->hotels, function($a, $b) {
                return $a->getPrice() - $b->getPrice();
            });
        }

        // Returns the sorted hotels.
        return $this->hotels;
    }
}
