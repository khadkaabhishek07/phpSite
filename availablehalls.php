<?php
// Start the session to use session variables
session_start();

// Retrieve user token from session
$userToken = isset($_SESSION['token']) ? htmlspecialchars($_SESSION['token']) : 'No user token available';

// Retrieve form data using the GET method
$eventType = isset($_GET['eventType']) ? htmlspecialchars($_GET['eventType']) : 'Not specified';
$numberOfGuests = isset($_GET['numberOfGuests']) ? htmlspecialchars($_GET['numberOfGuests']) : 'Not specified';
$eventDate = isset($_GET['checkAvailableDate']) ? htmlspecialchars($_GET['checkAvailableDate']) : 'Not specified';
$venueId = isset($_GET['venueId']) ? htmlspecialchars($_GET['venueId']) : 'Not specified';

// Example of using the access token to make an API request (optional)
$apiResponse = null;
if ($userToken !== 'No user token available') {
    // Prepare CURL request for an example API call
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://example.com/api/endpoint', // Replace with your actual API endpoint
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $userToken, // Use the stored access token
            'Content-Type: application/json'
        ),
    ));

    $apiResponse = curl_exec($curl);
    if ($apiResponse === false) {
        $apiResponse = 'CURL error: ' . curl_error($curl);
    }
    curl_close($curl);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Hall</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Availability Details</h1>
        <div class="card shadow-lg p-4">
            <h3>Your Selections:</h3>
            <ul class="list-group">
                <li class="list-group-item"><strong>Event Type:</strong> <?php echo $eventType; ?></li>
                <li class="list-group-item"><strong>Number of Guests:</strong> <?php echo $numberOfGuests; ?></li>
                <li class="list-group-item"><strong>Event Date:</strong> <?php echo $eventDate; ?></li>
                <li class="list-group-item"><strong>Venue ID:</strong> <?php echo $venueId; ?></li>
                <li class="list-group-item"><strong>User Token:</strong> <?php echo $userToken; ?></li>
            </ul>

            <!-- Optional section to prompt user to log in if no token -->
            <?php if ($userToken === 'No user token available'): ?>
                <div class="alert alert-warning mt-3" role="alert">
                    You are not logged in. Please log in to access additional features.
                </div>
            <?php else: ?>
                <!-- Display API response if available -->
                <h4 class="mt-4">API Response:</h4>
                <pre><?php echo htmlspecialchars($apiResponse); ?></pre>
            <?php endif; ?>

            <a href="index.php" class="btn btn-primary mt-3">Go Back</a>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
