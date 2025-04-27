<?php
session_start();
// Now you can access $_SESSION['token']
$userToken = $_SESSION['token'] ?? null;
// Assume 'user_logged_in' is a session variable that holds the login status
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Get the venueId from the URL, if it's set, otherwise use a default value
$venueId = isset($_GET['venueId']) ? $_GET['venueId'] : null;

// Initialize cURL session for venue address
$curl1 = curl_init();

// Set cURL options for the venue address request
curl_setopt_array($curl1, array(
    CURLOPT_URL => 'https://bandobasta.onrender.com/bandobasta/api/v1/venue/findAll',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));

// Execute cURL request for venue address and capture response
$response1 = curl_exec($curl1);

// Check for errors in the cURL request for venue
if (curl_errno($curl1)) {
    echo 'cURL Error for Venue: ' . curl_error($curl1);
    curl_close($curl1);
    exit;
}

// Close cURL session for venue
curl_close($curl1);

// Decode the JSON response for venue
$responseData1 = json_decode($response1, true);

// Initialize variables
$venueAddress = '';
$description = '';
$name = '';
$startingPrice = '';

// Check if venue data is available and fetch the necessary information for the dynamic venueId
foreach ($responseData1['data']['venues'] as $venue) {
    if ($venue['id'] == $venueId) {
        $venueAddress = $venue['address'];  // Store address in the variable
        $description = $venue['description'];  // Store description in the variable
        $name = $venue['name']; 
        $startingPrice = $venue['startingPrice']; // Store starting price in the variable
        break;
    }
}

// Initialize cURL session for hall image
$curl2 = curl_init();

// Set cURL options for the hall request using the dynamic venueId
curl_setopt_array($curl2, array(
    CURLOPT_URL => 'https://bandobasta.onrender.com/bandobasta/api/v1/hall/findAll?venueId=' . $venueId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $userToken, // Use the stored access token
        'Content-Type: application/json'
    ),
));

// Execute cURL request for hall data and capture response
$response2 = curl_exec($curl2);

// Check for errors in the cURL request for hall
if (curl_errno($curl2)) {
    echo 'cURL Error for Hall: ' . curl_error($curl2);
    curl_close($curl2);
    exit;
}

// Close cURL session for hall
curl_close($curl2);

// Decode the JSON response for hall
$responseData2 = json_decode($response2, true);

// Initialize image URL and capacity variables
$imageUrls = [];
$capacity = 0;

// Check if hall image data is available and fetch all image URLs
if (isset($responseData2['data']['hallDetails'][0]['hallImagePaths'])) {
    $imageUrls = $responseData2['data']['hallDetails'][0]['hallImagePaths'];
} else {
    echo 'No hall images found.';
    exit;
}

// Fetch the capacity
if (isset($responseData2['data']['hallDetails'][0]['capacity'])) {
    $capacity = $responseData2['data']['hallDetails'][0]['capacity'];
} else {
    echo 'No hall details found.';
    exit;
}

// At this point, the venue and hall details are available and can be used for further processing.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Booking</title>
    <style>
        /* Add the CSS styling from the previous message */
    </style>
</head>
<body>
    <div class="container">
        <div class="venue-content">
            <div class="image-container">
                <!-- Display the main image -->
                <img src="<?= $imageUrls[0] ?>" alt="Venue" class="main-image">
            </div>

            <div class="details-booking-container">
                <div class="venue-details">
                    <div class="venue-header">
                        <div>
                            <h1 class="venue-title"><?= htmlspecialchars($name) ?></h1>
                            <p class="venue-subtitle"><?= htmlspecialchars($venueAddress) ?></p>
                        </div>
                        <div class="rating">
                            <span>★</span>
                            <span>4.88</span> <!-- This is just a placeholder, you can replace it with dynamic ratings if needed -->
                        </div>
                    </div>

                    <div class="host-section">
                        <img src="./host.jpg" alt="Host" class="host-image">
                        <div>
                            <h3>Managed by Bandobasta</h3>
                            <p class="venue-subtitle">Bandobasta Host for 1 year</p>
                        </div>
                    </div>

                    <div class="features">
                        <div class="feature">
                            <div>🎯</div>
                            <div>
                                <h3>Great check-in experience</h3>
                                <p class="venue-subtitle">Recent guests loved the smooth start to this stay.</p>
                            </div>
                        </div>
                        <div class="feature">
                            <div>🅿️</div>
                            <div>
                                <h3>Park for free</h3>
                                <p class="venue-subtitle">This is one of the few places in the area with free parking.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="booking-card">
                    <form class="booking-form" id="bookingForm">
                        <div class="input-group">
                            <label>EVENT TYPE</label>
                            <input type="text" id="eventType" required>
                        </div>
                        <div class="input-group">
                            <label>No of Guests</label>
                            <input type="number" id="guestno" required>
                        </div>
                        <div class="input-group">
                            <label>EVENT DATE</label>
                            <input type="date" id="eventDate" required>
                        </div>
                        <button type="submit">Book Venue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle form submission
            const form = document.getElementById('bookingForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const eventType = document.getElementById('eventType').value;
                const guestno = document.getElementById('guestno').value;
                const eventDate = document.getElementById('eventDate').value;

                console.log(`Event Type: ${eventType}`);
                console.log(`Guest Number: ${guestno}`);
                console.log(`Event Date: ${eventDate}`);
            });
        });
    </script>
</body>
</html>
