<?php
session_start();

// Ensure necessary session variables are set
if (!isset($_SESSION['eventType'], $_SESSION['numberOfGuests'], $_SESSION['eventDate'], $_SESSION['venueId'])) {
    header('Location: process_form.php'); // Redirect if data is missing
    exit;
}

// Retrieve session data
$eventType = $_SESSION['eventType'];
$numberOfGuests = $_SESSION['numberOfGuests'];
$eventDate = $_SESSION['eventDate'];
$venueId = $_SESSION['venueId'];

// Handle form submission for confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? null; // User ID
    $availabilityId = $_POST['availabilityId'] ?? null; // Availability ID
    $selectedFoodIds = $_POST['selectedFoods'] ?? []; // Selected food IDs

    // Prepare the booking payload with the selected details
    $bookingData = [
        'userId' => $userId,
        'id' => $availabilityId,
        'numberOfGuests' => $numberOfGuests,
        'menuId' => $_SESSION['menuDetailIds'][0] ?? null, // Use the first menu detail ID
        'eventType' => $eventType,
        'foodIds' => $selectedFoodIds, // Selected food items
        'eventDate' => $eventDate
    ];

    // Make the booking request using cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/booking/hall",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($bookingData),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $_SESSION['token'],
            "Content-Type: application/json"
        ],
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo "<div class='alert alert-danger'>Error posting booking: " . curl_error($curl) . "</div>";
    } else {
        $responseData = json_decode($response, true);
        if (isset($responseData['code']) && $responseData['code'] === "200" && isset($responseData['message']) && $responseData['message'] === 'successful') {
            echo "<div id='successToast' class='toast' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='3000'>
                    <div class='toast-header'>
                        <strong class='me-auto'>Success</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='toast' aria-label='Close'></button>
                    </div>
                    <div class='toast-body'>
                        Your booking has been confirmed.
                    </div>
                </div>";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php'; // Redirect to index.php
                    }, 3000); // 3 seconds delay
                  </script>";
            exit; // Stop further script execution
        } else {
            $errorMessage = $responseData['message'] ?? 'Unknown error occurred.';
            echo "<div class='alert alert-danger text-center mt-5' role='alert'>
                    <strong>Error!</strong> Failed to confirm booking: $errorMessage
                  </div>";
        }
    }
    curl_close($curl);
} else {
    // If the form wasn't submitted yet, show the confirmation page
    // Display the booking preview here
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Booking Confirmation</h1>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Booking Details</h5>
            <p><strong>User ID:</strong> <?= htmlspecialchars($userId) ?></p>
            <p><strong>Availability ID:</strong> <?= htmlspecialchars($availabilityId) ?></p>
            <p><strong>Number of Guests:</strong> <?= htmlspecialchars($numberOfGuests) ?></p>
            <p><strong>Event Type:</strong> <?= htmlspecialchars($eventType) ?></p>
            <p><strong>Selected Food IDs:</strong> <?= !empty($selectedFoodIds) ? htmlspecialchars(implode(', ', $selectedFoodIds)) : 'None' ?></p>
            <p><strong>Event Date:</strong> <?= htmlspecialchars($eventDate) ?></p>
        </div>
    </div>

    <div class="d-flex justify-content-center my-4">
        <a href="confirmbooking.php" class="btn btn-primary">Confirm Booking</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
