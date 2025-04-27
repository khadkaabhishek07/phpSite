<?php
// Start the session to use session variables
session_start();

// Retrieve user token and userId from session
$userToken = isset($_SESSION['token']) ? htmlspecialchars($_SESSION['token']) : null;
$userId = isset($_SESSION['userId']) ? htmlspecialchars($_SESSION['userId']) : null;

// Retrieve form data using the GET method
$eventType = isset($_GET['eventType']) ? htmlspecialchars($_GET['eventType']) : 'Not specified';
$numberOfGuests = isset($_GET['numberOfGuests']) ? htmlspecialchars($_GET['numberOfGuests']) : 'Not specified';
$eventDate = isset($_GET['eventDate']) ? htmlspecialchars($_GET['eventDate']) : 'Not specified';
//$nepalidatepicker = isset($_GET['nepali-datepicker']) ? htmlspecialchars($_GET['nepali-datepicker']) : 'Not specified';
$venueId = isset($_GET['venueId']) ? htmlspecialchars($_GET['venueId']) : 'Not specified';

// Store data in session for later use
$_SESSION['eventType'] = $eventType;
$_SESSION['numberOfGuests'] = $numberOfGuests;
//$_SESSION['nepalidatepicker'] = $nepalidatepicker;
$_SESSION['eventDate'] = $eventDate;
$_SESSION['venueId'] = $venueId;
$_SESSION['userId'] = $userId; // Ensure userId is stored in session
// Initialize API response variable
$apiResponse = null;

if ($userToken) {
    // Prepare CURL request for hall availability
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/hall/availability?numberOfGuests=$numberOfGuests&venueId=$venueId&date=$eventDate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $userToken,
            'Content-Type: application/json'
        ),
    ));

    $apiResponse = curl_exec($curl);

    if ($apiResponse === false) {
        $curlError = curl_error($curl);
        curl_close($curl);
        die("<div class='alert alert-danger'>CURL Error: $curlError</div>");
    }

    curl_close($curl);
}

// Decode JSON response
$data = json_decode($apiResponse, true);
$hallAvailability = $data['data']['hallAvailabilityDetails'] ?? null;

// Include Bootstrap CSS/JS (once)
echo "
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
<script src='https://code.jquery.com/jquery-3.3.1.slim.min.js' integrity='sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' crossorigin='anonymous'></script>
<script src='https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
";

if ($data && isset($data['code']) && $data['code'] === "200" && !empty($hallAvailability)) {
    // Extract hall IDs and store them in session
    $hallIds = [];
    foreach ($hallAvailability as $hall) {
        $hallIds[] = $hall['hallId'];
    }

    // Store hall IDs in session
    $_SESSION['hallIds'] = $hallIds;

    // Organize halls by their ID and add time slots
    $halls = [];
    foreach ($hallAvailability as $hall) {
        $hallId = $hall['hallId'];
        if (!isset($halls[$hallId])) {
            $halls[$hallId] = [
                'hallName' => $hall['hallName'],
                'description' => $hall['description'],
                'capacity' => $hall['capacity'],
                'id'=>$hall['id'],
                'timeSlots' => []
            ];
        }

        $halls[$hallId]['timeSlots'][] = [
            'id' => $hall['id'],
            'date' => $hall['date'],
            'startTime' => $hall['startTime'],
            'endTime' => $hall['endTime'],
            'status' => $hall['status']
        ];
    }

    // Now, render the available halls and their time slots in the HTML form
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Available Halls</title>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body style='padding:40px;'>";

    echo "<div class='container my-4'>";
    echo "<h2 class='text-center'>Available Halls</h2>";
    echo "<form method='POST' action='viewmenu.php?venueId=$venueId'>"; 

    // Include the venueId as a hidden field
    echo "<input type='hidden' name='venueId' value='" . htmlspecialchars($venueId) . "'>";
    
    foreach ($halls as $hallId => $hallDetails) {
        echo "<div class='card mb-4 shadow-lg' style='border: 2px solid #007bff;'>";
        echo "<div class='card-header bg-primary text-white'><strong>" . htmlspecialchars($hallDetails['hallName']) . "</strong></div>";
        echo "<div class='card-body'>";
        echo "<p><strong>Capacity:</strong> " . htmlspecialchars($hallDetails['capacity']) . "</p>";
        echo "<h5>Select a Time Slot:</h5><div class='row'>";
        
        // Save the availability id in the session
        $_SESSION['availabilityId'] = $hallDetails['id'];   

        foreach ($hallDetails['timeSlots'] as $slot) {
            $slotStatusClass = strtolower($slot['status']) === 'available' ? 'bg-success' : 'bg-danger';
            $slotStatusText = strtolower($slot['status']) === 'available' ? 'Available' : 'Unavailable';
            echo "<div class='col-md-4 mb-3'>"; // Use columns for layout
            echo "<div class='card $slotStatusClass text-white'>"; // Card for each slot
            echo "<div class='card-body'>";
            //echo "<h6 class='card-title'><strong>Slot ID:</strong> " . htmlspecialchars($slot['id']) . "</h6>";
            echo "<p class='card-text'><strong>Date:</strong> " . htmlspecialchars($slot['date']) . "</p>";
            echo "<p class='card-text'><strong>Time:</strong> " . htmlspecialchars($slot['startTime']) . " - " . htmlspecialchars($slot['endTime']) . "</p>";
            
            // Centering the radio button
            echo "<div class='text-center'>"; // Centering div
            echo "<input type='radio' name='selectedSlot' value='{$hallId}_{$slot['id']}' required class='form-check-input' style='transform: scale(1.5);'> ";
            echo "<label class='form-check-label' style='font-weight: bold;'> Select 
            this Time Slot</label>"; // Label for the radio button
            echo "</div>"; // Close centering div
            echo "</div></div></div>"; // Close slot card
        }
    
        echo "</div></div></div>"; // Close row and hall card
    }
    
    echo "<div class='text-center'>";
    echo "<button type='submit' class='btn btn-success'>Book Selected Slot</button>";
    echo "</div>";
    echo "</form>
    </div>";

    echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js'></script>";
    echo "</body></html>";
} else {
    // Display fallback form if no data or error
echo "
<form style='padding: 140px;' method='POST' action='process_form.php'>
    <h1 class='text-center'>Venue Request Form</h1>
    <hr/>
    <div class='form-row'>
       
        <div class='form-group col-md-6'>
        <label for='inputEventType'>Event Type</label>
        <input type='text' class='form-control' id='inputEventDate' value='".htmlspecialchars($eventType) ."' disabled>
        <input type='hidden' name='eventType' value='".htmlspecialchars($eventType) ."'> <!-- Hidden input -->
        
        </div>
            <input type='hidden' name='userId' value='".htmlspecialchars($userId) ."'> <!-- Hidden input -->
        <div class='form-group col-md-6'>
            <label for='inputSlot'>Select Time Slot</label>
            <select class='form-control' id='inputSlot' name='timeSlot'>
                <option value='morning'>Morning</option>
                <option value='day'>Day</option>
                <option value='evening'>Evening</option>
            </select>
        </div>
    </div>
    <div class='form-row'>
        <div class='form-group col-md-6'>
            <label for='inputEventDate'>Requested Date</label>
            <input type='date' class='form-control' id='inputEventDate' value='".htmlspecialchars($eventDate) ."' disabled>
            <input type='hidden' name='eventDate' value='".htmlspecialchars($eventDate) ."'> <!-- Hidden input -->
        </div>
        <div class='form-group col-md-6'>
            <label for='inputGuests'>Number of Guests</label>
            <input type='number' class='form-control' id='inputGuests' placeholder='Estimated Number of Guests'
            value='".htmlspecialchars($numberOfGuests)."' disabled>
            <input type='hidden' name='numberOfGuests' value='".htmlspecialchars($numberOfGuests) ."'> <!-- Hidden input -->
            <input type='hidden' name='venueId' value='".htmlspecialchars($venueId) ."'> <!-- Hidden input -->

        </div>
    </div>
    <div class='form-group'>
        <label for='inputMessage'>Additional Requirements or Questions</label>
        <textarea class='form-control' id='inputMessage' name='message' rows='4' placeholder='Enter any specific requirements or queries'></textarea>
    </div>
    <div class='form-group'>
        <div class='form-check'>
            <input class='form-check-input' type='checkbox' id='gridCheck' name='terms'>
            <label class='form-check-label' for='gridCheck'>
                I agree to the terms and conditions
            </label>
        </div>
    </div>
    <button type='submit' class='btn btn-primary'>Submit Enquiry</button>
</form>
";

}
?>
