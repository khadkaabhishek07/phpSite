<?php
session_start();

// Ensure menuDetailIds are set in the session
if (!isset($_SESSION['menuDetailIds']) || empty($_SESSION['menuDetailIds'])) {
    header('Location: process_form.php'); // Redirect to process_form.php if no menuDetailIds
    exit;
}

// Retrieve available menuDetailIds from session
$menuDetailIds = $_SESSION['menuDetailIds'];
$menuDetailId = $menuDetailIds[0] ?? null; // Default to the first menuDetailId if available

// Handle form submission for confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $venueId = $_POST['venueId'] ?? null; // Venue ID
    $selectedFoodIds = $_POST['selectedFoods'] ?? []; // Selected food IDs
    $eventDate = $_POST['eventDate'] ?? 'Not specified'; // Event date from POST
    $numberOfGuests = $_POST['numberOfGuests'] ?? null; // Number of guests
    $eventType = $_POST['eventType'] ?? null; // Event type
    $userId = $_POST['userId'] ?? null; // User ID
    $availabilityId = $_POST['availabilityId'] ?? null; // Availability ID

    // Prepare the booking payload with the selected details
    $bookingData = [
        'userId' => $userId,
        'id' => $availabilityId,
        'numberOfGuests' => $numberOfGuests,
        'menuId' => $menuDetailId, // Use the selected menu detail ID
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
            // Show toast notification after successful booking
            echo "
            <div class='toast-container position-fixed top-0 end-0 p-3'>
                <div id='successToast' class='toast' role='alert' aria-live='assertive' aria-atomic='true'>
                    <div class='toast-header'>
                        <strong class='me-auto'>Success</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='toast' aria-label='Close'></button>
                    </div>
                    <div class='toast-body'>
                        Your booking has been confirmed.
                    </div>
                </div>
            </div>
            <script>
                var toastEl = document.getElementById('successToast');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            </script>
            ";
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
    echo "<div class='booking-card'>";
    echo "<h4 class='text-center mb-4'>Booking Preview:</h4>";

    // Display Selected Event Details
    echo "<p><strong>Event Type:</strong> " . htmlspecialchars($_SESSION['eventType']) . "</p>";
    echo "<p><strong>Number of Guests:</strong> " . htmlspecialchars($_SESSION['numberOfGuests']) . "</p>";
    echo "<p><strong>Event Date:</strong> " . htmlspecialchars($_SESSION['eventDate']) . "</p>";

    // Check if food IDs are available
    if (!empty($_POST['selectedFoods'])) {
        echo "<p><strong>Selected Food Items:</strong> " . htmlspecialchars(implode(', ', $_POST['selectedFoods'])) . "</p>";
    } else {
        echo "<p><strong>No food items selected.</strong></p>";
    }

    // Add a confirmation text with faded style
    echo "<p class='faded-text'><strong>Please review your booking details carefully before confirming. Once confirmed, your booking will be finalized and cannot be changed.</strong></p>";
    
    // Confirm Booking Button
    echo "<form method='POST' class='text-center'>";
    echo "<input type='hidden' name='confirm' value='true'>";
    echo "<input type='hidden' name='venueId' value='" . htmlspecialchars($venueId) . "'>";
    echo "<input type='hidden' name='eventType' value='" . htmlspecialchars($eventType) . "'>";
    echo "<input type='hidden' name='numberOfGuests' value='" . htmlspecialchars($numberOfGuests) . "'>";
    echo "<input type='hidden' name='eventDate' value='" . htmlspecialchars($eventDate) . "'>";
    echo "<input type='hidden' name='userId' value='" . htmlspecialchars($userId) . "'>";
    echo "<input type='hidden' name='availabilityId' value='" . htmlspecialchars($availabilityId) . "'>";
    echo "<input type='hidden' name='selectedFoods' value='" . htmlspecialchars(implode(',', $_POST['selectedFoods'] ?? [])) . "'>"; // Pass food IDs if available
    echo "<button type='submit' class='btn btn-primary'>Confirm Booking</button>";
    echo "</form>";

    echo "</div>"; // Close booking-card div
}
?>

<!-- Custom CSS -->
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .booking-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        max-width: 700px;
        margin: 50px auto;
        padding: 30px;
        text-align: center;
    }

    .booking-card h4 {
        color: #2c3e50;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .food-list {
        list-style: none;
        padding-left: 0;
    }

    .food-item {
        background-color: #f1f1f1;
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
    }

    .btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 30px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 200px;
    }

    .btn:hover {
        background-color: #218838;
    }

    .faded-text {
        color: #6c757d; /* Light gray color */
        opacity: 0.7;
    }

    /* Responsive styling */
    @media (max-width: 768px) {
        .booking-card {
            padding: 20px;
            margin: 20px;
            height: ;
        }

        .btn {
            width: 100%;
            padding: 15px;
        }
    }
</style>

<!-- Custom JS (Optional: Enhance functionality) -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Optional JavaScript code for further interactions can go here
    });
</script>
