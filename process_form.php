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

// Initialize variables
$eventType = '';
$numberOfGuests = '';
$eventDate = '';
$venueId = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $eventType = htmlspecialchars($_POST['eventType']);
    $numberOfGuests = htmlspecialchars($_POST['numberOfGuests']);
    $eventDate = htmlspecialchars($_POST['eventDate']);
    $venueId = htmlspecialchars($_POST['venueId']);

    // Store data in session for later use
    $_SESSION['eventType'] = $eventType;
    $_SESSION['numberOfGuests'] = $numberOfGuests;
    $_SESSION['eventDate'] = $eventDate;
    $_SESSION['venueId'] = $venueId;

    // Redirect to view menu page
    header('Location: viewmenu.php?venueId=' . urlencode($venueId));
    exit;
} else {
    // If the script is accessed directly without form submission
    echo "No form data submitted.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-4">
    <h1 class="text-center">Event Booking Form</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="eventType" class="form-label">Event Type</label>
            <input type="text" class="form-control" id="eventType" name="eventType" required>
        </div>
        <div class="mb-3">
            <label for="numberOfGuests" class="form-label">Number of Guests</label>
            <input type="number" class="form-control" id="numberOfGuests" name="numberOfGuests" required>
        </div>
        <div class="mb-3">
            <label for="eventDate" class="form-label">Event Date</label>
            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
        </div>
        <div class="mb-3">
            <label for="venueId" class="form-label">Venue ID</label>
            <input type="text" class="form-control" id="venueId" name="venueId" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>