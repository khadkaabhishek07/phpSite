<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the selected slot is set
    if (isset($_POST['selectedSlot'])) {
        // Get the selected slot value
        $selectedSlot = $_POST['selectedSlot'];
        
        // Split the selected slot into hallId and slotId
        list($hallId, $slotId) = explode('_', $selectedSlot);

        // Retrieve additional details (For example, you may want to fetch more data about the hall or slot)
        // You can call another API or retrieve details from the database here

        // Display the selected hall and time slot information
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Selection Confirmation</title>
            <link href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body>";

        echo "<div class='container my-4'>";
        echo "<h2 class='text-center'>Selection Confirmation</h2>";
        echo "<div class='card shadow-lg'>";
        echo "<div class='card-header bg-success text-white'>You have selected the following slot:</div>";
        echo "<div class='card-body'>";
        echo "<p><strong>Hall ID:</strong> " . htmlspecialchars($hallId) . "</p>";
        echo "<p><strong>Slot ID:</strong> " . htmlspecialchars($slotId) . "</p>";
        
        // Here you could query the hall details or show more info about the slot, such as date and time
        echo "<p><strong>Selected Time Slot:</strong> Slot ID: " . htmlspecialchars($slotId) . " | Date: (insert date) | Time: (insert time range)</p>";

        // If you want to add additional details about the selected hall, you could fetch them here.
        echo "<div class='text-center'>
                <a href='index.php' class='btn btn-primary'>Go Back</a>
              </div>";
        echo "</div></div></div>";
        
        echo "</body></html>";
    } else {
        echo "<div class='alert alert-danger'>No slot selected. Please go back and choose a slot.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Invalid request method.</div>";
}
?>
