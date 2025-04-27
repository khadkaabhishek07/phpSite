<?php
session_start();

// Retrieve session data
$eventType = $_SESSION['eventType'] ?? '';
$numberOfGuests = $_SESSION['numberOfGuests'] ?? '';
$eventDate = $_SESSION['eventDate'] ?? '';
$venueId = $_SESSION['venueId'] ?? '';
$hallAvailability = $_SESSION['hallAvailability'] ?? [];

// Retrieve venueId dynamically from URL
if (isset($_GET['venueId'])) {
    // Get the venueId from URL
    $venueId = htmlspecialchars($_GET['venueId']);
    
    // Save the venueId to session
    $_SESSION['venueId'] = $venueId;

    // API call to fetch menu details
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/menu/findAll?venueId=' . $venueId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($response, true);

    // Check if API response is valid
    if ($data && $data['code'] === "200" && isset($data['data']['menuDetails'])) {
        $menuDetails = $data['data']['menuDetails'];
        
        // Store the first menu detail ID in session (or all IDs if needed)
        $menuDetailIds = [];
        foreach ($menuDetails as $menuDetail) {
            // Save each menu detail's ID to the session array
            $menuDetailIds[] = $menuDetail['id'];
        }

        // Store the array of menu detail IDs in session
        $_SESSION['menuDetailIds'] = $menuDetailIds;

        // Extract max selection limits
        $maxSelections = [];
        foreach ($menuDetails[0]['menuItemSelectionRangeDetails'] as $range) {
            $maxSelections[$range['foodSubCategory']] = $range['maxSelection'];
        }
        
    } else {
        $menuDetails = [];
        $maxSelections = [];
        $_SESSION['menuDetailIds'] = [];  // Clear menu details in session if API fails
    }
} else {
    echo "Venue ID not provided.";
    $menuDetails = [];
    $maxSelections = [];
    $_SESSION['menuDetailIds'] = [];  // Clear menu details if no venue ID is provided
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        .menu-book {
            max-width: 100%;
            margin: auto;
            padding: 15px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .food-category-title {
            text-align: center;
            margin-top: 20px;
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .food-subcategory-title {
            font-weight: bold;
            margin-top: 15px;
            font-size: 1.5rem;
            color: #343a40;
        }
        .food-card {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .food-card:hover {
            box-shadow: 0 4px 12px rgba(0, 123, 255, .2);
        }
        .food-card.selected {
            border-color: #007bff; /* Highlight border color */
            background-color: #e7f3ff; /* Light blue background for selected */
        }

        /* Responsive Styles */
        @media (max-width: 576px) {
            .food-category-title {
                font-size: 1.8rem; /* Smaller title on mobile */
                padding-bottom: 5px; /* Reduced padding */
            }
            .food-subcategory-title {
                font-size: 1.3rem; /* Smaller subcategory title */
                margin-top: 10px; /* Reduced margin */
            }
            .food-card {
                padding: 10px; /* Less padding for cards */
                margin-bottom: 10px; /* Reduced gap between cards */
                font-size: 14px; /* Smaller font size for mobile */
            }
        }

        @media (min-width: 577px) {
            .food-card {
                display: flex; /* Flex layout for larger screens */
                justify-content: space-between; /* Space between items */
                align-items: center; /* Center items vertically */
                padding-left: 20px; /* More padding for larger screens */
                padding-right: 20px; /* More padding for larger screens */
            }
        }

    </style>
</head>
<body>
<div class="container my-4 menu-book">
    <h1 class="text-center mb-4">Menu for Venue <?= htmlspecialchars($venueId) ?></h1>

    <?php if (!empty($menuDetails)): ?>
        <form id="menuForm" action="confirmbooking.php" method="POST">
            <div id="stepProgress" class="step-progress">
                <?php
                // Group food items by category and sub-category
                $categories = [];
                foreach ($menuDetails as $menu) {
                    foreach ($menu['foodDetails'] as $food) {
                        $categories[$food['foodCategory']][$food['foodSubCategory']][] = $food;
                    }
                }
                ?>

                <!-- Step Progress Container -->
                <div id="stepsContainer">
                    <?php foreach ($categories as $category => $subCategories): ?>
                        <div class="food-category-title"><?= htmlspecialchars($category) ?></div>
                        <?php foreach ($subCategories as $subCategory => $foods): ?>
                            <div class="food-group">
                                <h4 class="food-subcategory-title">
                                    <?= htmlspecialchars($subCategory) ?> 
                                    (Select up to <?= $maxSelections[$subCategory] ?? 'Unlimited' ?> items)
                                </h4>
                                <div id="selection-count-<?= htmlspecialchars($subCategory) ?>" class="selection-count text-muted mb-2">
                                    Selected: 0 / <?= $maxSelections[$subCategory] ?? 'Unlimited' ?>
                                </div>
                                <div class="row">
                                    <?php foreach ($foods as $food): ?>
                                        <div class="col-12">
                                            <div class="food-card" data-food-id="<?= $food['id'] ?>" data-food-subcategory="<?= htmlspecialchars($subCategory) ?>">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <!-- Checkbox visible next to food item -->
                                                    <div class="checkbox-wrapper me-2">
                                                        <input type="checkbox" name="selectedFoods[]" value="<?= $food['id'] ?>" id="food-<?= $food['id'] ?>" data-food-id="<?= $food['id'] ?>" style="cursor:pointer;">
                                                    </div>
                                                    <div class="food-item-name flex-grow-1 mx-2">
                                                        <h5><?= htmlspecialchars($food['name']) ?></h5>
                                                        <p><?= htmlspecialchars($food['description']) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="venueId" value="<?= htmlspecialchars($venueId) ?>">
                <input type="hidden" name="eventType" value="<?= htmlspecialchars($eventType) ?>">
                <input type="hidden" name="numberOfGuests" value="<?= htmlspecialchars($numberOfGuests) ?>">
                <input type="hidden" name="eventDate" value="<?= htmlspecialchars($eventDate) ?>">
                <input type="hidden" name="userId" value="<?= htmlspecialchars($_SESSION['userId'] ?? '1') ?>">
                <input type="hidden" name="availabilityId" value="<?= htmlspecialchars($_SESSION['availabilityId'] ?? '548') ?>">
            </div>

            <div class="d-flex justify-content-center my-4">
                <button type="button" class="btn btn-success" id="confirmMenuButton">Confirm Menu</button>
            </div>
        </form>
    <?php else: ?>
        <form id="noMenuForm" action="hallbooking.php" method="POST">
            <input type="hidden" name="venueId" value="<?= htmlspecialchars($venueId) ?>">
            <input type="hidden" name="eventType" value="<?= htmlspecialchars($eventType) ?>">
            <input type="hidden" name="numberOfGuests" value="<?= htmlspecialchars($numberOfGuests) ?>">
            <input type="hidden" name="eventDate" value="<?= htmlspecialchars($eventDate) ?>">
            <input type="hidden" name="userId" value="<?= htmlspecialchars($_SESSION['userId'] ?? '1') ?>">
            <input type="hidden" name="availabilityId" value="<?= htmlspecialchars($_SESSION['availabilityId'] ?? '548') ?>">
            <div class="d-flex justify-content-center my-4">
                <button type="submit" class="btn btn-warning">Proceed Without Menu</button>
            </div>
        </form>
    <?php endif; ?>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS for selecting food items with max selection logic -->
<script>
    // Store current selections count per subcategory
    const currentSelections = {};

    // Set max selections based on PHP variable
    const maxSelections = <?= json_encode($maxSelections); ?>;

    document.querySelectorAll('.food-card').forEach(card => {
        card.addEventListener('click', function() {
            const subCategory = this.getAttribute('data-food-subcategory');

            // Initialize count for the subcategory if not set
            if (!currentSelections[subCategory]) {
                currentSelections[subCategory] = { count: 0 };
            }

            const checkbox = this.querySelector('input[type="checkbox"]');

            // Toggle selected state
            if (this.classList.contains('selected')) {
                // If already selected, deselect it
                this.classList.remove('selected');
                checkbox.checked = false;

                // Decrement count
                currentSelections[subCategory].count--;

                // Re-enable all checkboxes in that subcategory
                enableCheckboxes(subCategory);
            } else if (currentSelections[subCategory].count < maxSelections[subCategory]) {
                // If not selected and within limit, select it
                this.classList.add('selected');
                checkbox.checked = true;

                // Increment count
                currentSelections[subCategory].count++;

                // Disable other checkboxes if necessary
                if (currentSelections[subCategory].count === maxSelections[subCategory]) {
                    disableCheckboxes(subCategory);
                }
            } else {
                // Alert user if they exceed max selection limit and prevent further action
                alert(`You can only select up to ${maxSelections[subCategory]} items from ${subCategory}.`);
            }

            // Update selection count display (if implemented)
            updateSelectionCountDisplay(subCategory);
        });
    });

    // Function to disable checkboxes in a specific subcategory when max is reached
    function disableCheckboxes(subCategory) {
        document.querySelectorAll(`.food-card[data-food-subcategory="${subCategory}"]`).forEach(card => {
            const checkbox = card.querySelector('input[type="checkbox"]');
            if (!card.classList.contains('selected')) { // Disable only unselected cards
                checkbox.disabled = true; 
                card.style.opacity = '0.5'; // Visually indicate disabled state
            }
        });
    }

    // Function to enable checkboxes in a specific subcategory when an item is unselected
    function enableCheckboxes(subCategory) {
        document.querySelectorAll(`.food-card[data-food-subcategory="${subCategory}"]`).forEach(card => {
            const checkbox = card.querySelector('input[type="checkbox"]');
            checkbox.disabled = false; 
            card.style.opacity = '1'; // Reset opacity when enabling
        });
    }

    // Function to update selection count display (if implemented)
    function updateSelectionCountDisplay(subCategory) {
        const countDisplayElement = document.getElementById(`selection-count-${subCategory}`);
        if (countDisplayElement) {
            countDisplayElement.innerText = `Selected: ${currentSelections[subCategory].count} / ${maxSelections[subCategory]}`;
        }
    }

    // Modal logic
    document.getElementById('confirmMenuButton').addEventListener('click', function() {
        const selectedFoods = [];
        document.querySelectorAll('input[name="selectedFoods[]"]:checked').forEach(checkbox => {
            selectedFoods.push(checkbox.closest('.food-card').querySelector('.food-item-name h5').innerText);
        });

        // Show modal with selected items and additional details
        const modalBody = document.getElementById('modalBody');
        const additionalDetails = `
            <strong>Event Type:</strong> ${<?= json_encode($eventType) ?>}<br>
            <strong>Number of Guests:</strong> ${<?= json_encode($numberOfGuests) ?>}<br>
            <strong>Event Date:</strong> ${<?= json_encode($eventDate) ?>}<br>
            <strong>Venue:</strong> ${<?= json_encode($venueId) ?>}<br>
            <strong>Selected Food Items:</strong><br>
            ${selectedFoods.length > 0 ? selectedFoods.join('<br>') : 'No items selected.'}
        `;
        modalBody.innerHTML = additionalDetails;
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        modal.show();
    });

    // Confirm selection in modal
    document.getElementById('confirmSelection').addEventListener('click', function() {
        // Submit the form
        document.getElementById('menuForm').submit();
    });
</script>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Your Selection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Selected items and additional details will be displayed here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSelection">Confirm Selection</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal logic
    document.getElementById('confirmMenuButton').addEventListener('click', function() {
        const selectedFoods = [];
        document.querySelectorAll('input[name="selectedFoods[]"]:checked').forEach(checkbox => {
            selectedFoods.push(checkbox.closest('.food-card').querySelector('.food-item-name h5').innerText);
        });

        // Show modal with selected items and additional details
        const modalBody = document.getElementById('modalBody');
        const additionalDetails = `
            <strong>Event Type:</strong> ${<?= json_encode($eventType) ?>}<br>
            <strong>Number of Guests:</strong> ${<?= json_encode($numberOfGuests) ?>}<br>
            <strong>Event Date:</strong> ${<?= json_encode($eventDate) ?>}<br>
            <strong>Venue:</strong> ${<?= json_encode($venueId) ?>}<br>
            <strong>Selected Food Items:</strong><br>
            ${selectedFoods.length > 0 ? selectedFoods.join('<br>') : 'No items selected.'}
        `;
        modalBody.innerHTML = additionalDetails;
        const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        modal.show();
    });

    // Confirm selection in modal
    document.getElementById('confirmSelection').addEventListener('click', function() {
        // Submit the form
        document.getElementById('menuForm').submit();
    });
</script>





</body>
</html>
