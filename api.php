<?php

require __DIR__ . '/vendor/autoload.php';

use projeto\Hotel;
use projeto\HotelAPI;
use projeto\SourceURLs;

header('Content-Type: application/json');

// Populate the list of hotels
$hotels = [];

// Validate latitude and longitude values
$latitude = filter_input(INPUT_GET, 'latitude', FILTER_VALIDATE_FLOAT);
$longitude = filter_input(INPUT_GET, 'longitude', FILTER_VALIDATE_FLOAT);

if ($latitude === false || $longitude === false) {
    http_response_code(400); // set HTTP response code 400 Bad Request
    $error = ['error' => 'Latitude and longitude values must be valid decimal numbers.'];
    echo json_encode($error, JSON_PRETTY_PRINT);
    exit; // stop the script execution
}

if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
    http_response_code(400); // set HTTP response code 400 Bad Request
    $error = ['error' => 'Latitude and longitude values must be within the valid range.'];
    echo json_encode($error, JSON_PRETTY_PRINT);
    exit; // stop the script execution
}

// Set the orderby value
$orderby = isset($_GET['orderby']) && in_array($_GET['orderby'], ['proximity', 'pricepernight']) ? $_GET['orderby'] : 'proximity';

// List of hotel data URLs
$source_urls = new SourceURLs([
    'https://xlr8-interview-files.s3.eu-west-2.amazonaws.com/source_1.json',
    'https://xlr8-interview-files.s3.eu-west-2.amazonaws.com/source_2.json'
    // add more URLs as needed
]);

// Load hotel data from each URL
foreach ($source_urls->getURLs() as $url) {
    try {
        $data = file_get_contents($url);
        if (!$data) {
            throw new Exception("Could not access URL: $url");
        }
        $json = json_decode($data, true);
        if (!$json || !isset($json['message'])) {
            throw new Exception("Hotel data not found in URL: $url");
        }
        // Create hotel objects and store them in an array
        foreach ($json['message'] as $hotelData) {
            $hotel = new Hotel($hotelData[0], $hotelData[1], $hotelData[2], $hotelData[3], 0); // Create new hotel object
            $hotels[] = $hotel; // Store the hotel object in an array
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}

// Create a new HotelAPI object and get nearby hotels
$hotelAPI = new HotelAPI($hotels);
$nearbyHotels = $hotelAPI->getNearbyHotels($latitude, $longitude, $orderby); // Add the orderby parameter to the function call

// Format and store the results
$results = [];
foreach ($nearbyHotels as $hotel) {
    $result = [
        $hotel->name, 
        number_format($hotel->distance, 2) . ' KM', 
        number_format($hotel->price, 2) . ' EUR',
    ];
    $results[] = $result;
}

// Return the results as JSON
$json = json_encode($results, JSON_PRETTY_PRINT);
echo $json;

?>
