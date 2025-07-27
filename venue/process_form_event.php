<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
if (!$is_logged_in) {
    header('Location: login.php');
    exit;
}

// Access the token from the session
$userToken = $_SESSION['token'] ?? null;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $userId = htmlspecialchars($_POST['userId']);
    $eventDate = htmlspecialchars($_POST['eventDate']);
    $venueId = htmlspecialchars($_POST['venueId']);
    $numberOfGuests = htmlspecialchars($_POST['numberOfGuests']);
    $message = htmlspecialchars($_POST['message']);
    $timeslot = htmlspecialchars($_POST['timeSlot']);

    // Initialize cURL
    $curl = curl_init();

    // Prepare POST fields for the cURL request
    $postFields = json_encode([
        "venueId" => $venueId,
        "userId" => $userId,
        "requestedDate" => $eventDate,
        "numberOfGuests" => $numberOfGuests,
        "message" => $message,
        "timeSlot" => $timeslot
    ]);

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/booking/hall/bookingDateRequest',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $userToken,
            'Content-Type: application/json'
        ],
    ]);

    // Execute the cURL request and capture the response
    $response = curl_exec($curl);

    // Check for errors in the cURL request
    if ($response === false) {
        echo "Error in cURL request: " . curl_error($curl);
    } else {
        // Decode the API response
        $responseData = json_decode($response, true);

        // Check if the API request was successful
        if (isset($responseData['code']) && $responseData['code'] === "200") {
            // Display a success message
            echo '
            <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <h2 style="text-align: center; color: #4CAF50;">Thank You for Your Booking Request!</h2>
                <p style="text-align: center; font-size: 16px; color: #333;">
                    Your booking request has been successfully submitted. Our team will contact you shortly via email or phone call.
                </p>
                <p style="text-align: center; font-size: 14px; color: #666;">
                    We appreciate your trust in our services!
                </p>
            </div>
            ';

            // Redirect to the index page after 5 seconds
            header("Refresh: 5; url=index.php");
        } else {
            // Display an error message if the API request failed
            echo '
            <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ff0000; border-radius: 10px; background-color: #ffe6e6; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <h2 style="text-align: center; color: #ff0000;">Booking Request Failed</h2>
                <p style="text-align: center; font-size: 16px; color: #333;">
                    There was an error processing your booking request. Please try again later.
                </p>
                <p style="text-align: center; font-size: 14px; color: #666;">
                    Error: ' . ($responseData['message'] ?? 'Unknown error') . '
                </p>
            </div>
            ';
        }
    }

    // Close the cURL session
    curl_close($curl);
} else {
    // If the script is accessed directly without form submission
    echo "No form data submitted.";
}
?>