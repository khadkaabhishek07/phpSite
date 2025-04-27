<?php

// Your Geoapify API Key
$apiKey = 'ce7f1215c8754a6c9b59950b0240f1bc';

// Base URL for the Geoapify Place Search API
$baseUrl = 'https://api.geoapify.com/v2/places';

// Location for Kathmandu (latitude and longitude)
$latitude = 27.7000;
$longitude = 85.3000;

// Search for "banquet" within a radius (e.g., 5000 meters)
$query = 'banquet'; // Changed to 'banquet' as per original intent
$radius = 5000; // in meters

// Prepare the URL with the parameters
$requestUrl = $baseUrl . '?categories=building:public&filter=circle:' . $longitude . ',' . $latitude . ',' . $radius . '&text=' . urlencode($query) . '&apiKey=' . $apiKey;

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $requestUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification if necessary (not recommended for production)

// Execute the cURL request and get the response
$response = curl_exec($ch);

// Check if there was an error in the cURL request
if ($response === false) {
    echo "Error in API request: " . curl_error($ch);
    exit;
}

// Close cURL session
curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

// Check if there are any results
if (isset($data['features']) && count($data['features']) > 0) {
    echo "<h1>Banquet Halls in Kathmandu:</h1>";
    echo "<ul>";
    foreach ($data['features'] as $feature) {
        $name = $feature['properties']['name'] ?? 'No name available';
        $address = $feature['properties']['address_line1'] ?? 'No address available';
        $distance = $feature['properties']['distance'] ?? 'Unknown distance';

        echo "<li><strong>$name</strong><br/>Address: $address<br/>Distance: $distance meters</li>";
    }
    echo "</ul>";
} else {
    echo "No banquet halls found in Kathmandu.";
}

?>
