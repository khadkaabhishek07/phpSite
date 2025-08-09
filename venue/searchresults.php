<?php
session_start(); // Start the session to check login status
// Store form data in session variables
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['eventType'] = isset($_GET['eventType']) ? $_GET['eventType'] : '';
    $_SESSION['numberOfGuests'] = isset($_GET['numberOfGuests']) ? $_GET['numberOfGuests'] : '';
    $_SESSION['checkAvailableDate'] = isset($_GET['checkAvailableDate']) ? $_GET['checkAvailableDate'] : '';
}
// Assume 'user_logged_in' is a session variable that holds the login status
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandobasta Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        section {
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom:10px;
        }

        button.btn-outline-danger {
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
        }

        button.btn-danger.btn-lg {
            padding: 12px 40px;
            border-radius: 25px;
            font-size: 1.2rem;
        }

        .venue-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            object-position: center;
        }

        .btn-light {
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 0.5rem;
            color: white;
            background-color: #B00302;
        }

        .btn-light i {
            font-size: 1.2rem;
        }

        p {
            text-align: center;
            margin: 0;
            font-size: 0.75rem;
        }

        h3 {
            margin-left:10px;
        }

        h2 {
            font-size:1rem;
        }

        .hello {
            margin-left: 20px;
        }

        @media screen and (max-width: 480px) {
            .hello {
                margin-left: 0px;
            }
        }
    </style>
</head>
<body style="padding-bottom: 60px;">

<div class="container py-4">
    <header class="text-center mb-4">
        <img src="logo.png" style="align-items: center; width: 200px;">
    </header>
<h3 class="mb-3"> Search Results</h3>
<div id="featured-venues" class="row">
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve the authorization token dynamically
$authToken = isset($_SESSION['auth_token']) ? $_SESSION['auth_token'] : 'default-token-placeholder';

// Retrieve parameters from the previous page (using GET method)
$numberOfGuests = isset($_GET['numberOfGuests']) ? intval($_GET['numberOfGuests']) : 0;
$checkAvailableDate = isset($_GET['checkAvailableDate']) ? htmlspecialchars($_GET['checkAvailableDate']) : '';

// Validate the inputs
if ($numberOfGuests <= 0 || empty($checkAvailableDate)) {
    echo '<p class="text-center text-danger">Invalid input. Please provide a valid number of guests and event date.</p>';
    return;
}

// Initialize cURL
$curl = curl_init();

$apiUrl = 'https://bandobasta.onrender.com/bandobasta/api/v1/venue/checkVenueAvailability';
$apiUrl .= '?numberOfGuests=' . $numberOfGuests . '&checkAvailableDate=' . urlencode($checkAvailableDate);

curl_setopt_array($curl, array(
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $authToken
    ),
));

$response = curl_exec($curl);

if ($response === false) {
    echo '<p class="text-center text-muted">Unable to fetch venue data. Please try again later.</p>';
    curl_close($curl);
    return;
}

curl_close($curl);

$data = json_decode($response, true);

if ($data && isset($data['data']['venues']) && !empty($data['data']['venues'])) {
    foreach ($data['data']['venues'] as $venue) {
        echo '<div class="col-md-4 col-sm-6 mb-4">';
        echo '<div class="card shadow-sm h-100">';
        echo '<img src="' . htmlspecialchars($venue['venueImagePaths'][0]) . '" class="card-img-top venue-image" alt="' . htmlspecialchars($venue['name']) . '">';
        echo '<div class="card-body d-flex flex-column">';
        echo '<h5 class="card-title">' . htmlspecialchars($venue['name']) . '</h5>';
        echo '<p class="card-text text-muted mb-3"><strong>Starting Price:</strong> ' . htmlspecialchars($venue['startingPrice']) . '</p>';
        echo '<a href="viewvenue.php?venueId=' . htmlspecialchars($venue['id']) . '" class="btn btn-danger mt-auto">View More</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p class="text-center text-muted">No venues available for the selected date and number of guests.</p>';
}


?>

        
</div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="loginModalLabel">Welcome! Please log in to explore more.</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                        echo htmlspecialchars($_SESSION['error']);
                        unset($_SESSION['error']); // Clear error after displaying
                        ?>
                    </div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username or Email or Phone Number</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Footer Navigation -->
<footer class="fixed-bottom bg-light d-flex justify-content-around py-2">
    <a href="#" class="text-danger text-center" style="text-decoration: none;">
        <i class="bi bi-house-door-fill" style="font-size: 1.5rem;"></i>
        <p class="mb-0" style="font-size: 0.75rem;">Home</p>
    </a>
    <a href="#" class="text-danger text-center" style="text-decoration: none;">
        <i class="bi bi-heart" style="font-size: 1.5rem;"></i>
        <p class="mb-0" style="font-size: 0.75rem;">Favorites</p>
    </a>
    <a href="#" class="text-danger text-center" style="text-decoration: none;">
        <i class="bi bi-clock-history" style="font-size: 1.5rem;"></i>
        <p class="mb-0" style="font-size: 0.75rem;">History</p>
    </a>
    
    <?php if ($is_logged_in): ?>
        <a href="logout.php" class="text-danger text-center" style="text-decoration: none;">
            <i class="bi bi-box-arrow-right" style="font-size: 1.5rem;"></i>
            <p class="mb-0" style="font-size: 0.75rem;">Logout</p>
        </a>
    <?php else: ?>
        <a href="#" class="text-danger text-center" style="text-decoration: none;">
            <i class="bi bi-person-fill" style="font-size: 1.5rem;"></i>
            <p class="mb-0" style="font-size: 0.75rem;">Profile</p>
        </a>
    <?php endif; ?>
</footer>

<!-- Test Site Modal -->
<div class="modal fade" id="testSiteModal" tabindex="-1" aria-labelledby="testSiteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testSiteModalLabel">Test Site Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>This is a test site. Any booking made on this page is for testing purposes only. No actual booking is made.</p>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="termsCheck">
                    <label class="form-check-label" for="termsCheck">I agree to accept the terms and conditions.</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="acceptTermsBtn" disabled>Accept</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to check if the user has already accepted the terms
    function hasAcceptedTerms() {
        return localStorage.getItem('testSiteAccepted') === 'true';
    }

    // Automatically show the modal only if the user hasn't accepted the terms
    document.addEventListener('DOMContentLoaded', function () {
        // Check if the user has already accepted the terms
        if (hasAcceptedTerms()) {
            return; // Exit if the user has already accepted
        }

        var testSiteModal = new bootstrap.Modal(document.getElementById('testSiteModal'), {});
        testSiteModal.show();

        // Enable/Disable the "Accept" button based on checkbox state
        var termsCheck = document.getElementById('termsCheck');
        var acceptTermsBtn = document.getElementById('acceptTermsBtn');

        termsCheck.addEventListener('change', function () {
            acceptTermsBtn.disabled = !termsCheck.checked;
        });

        // Handle the "Accept" button click
        acceptTermsBtn.addEventListener('click', function () {
            // Store the acceptance in localStorage to prevent showing the modal again
            localStorage.setItem('testSiteAccepted', 'true');
            testSiteModal.hide(); // Close the modal when accepted
        });
    });
</script>


<script>
    // Automatically open the login modal if loginError is present in the URL
    document.addEventListener('DOMContentLoaded', function () {
        if (window.location.search.includes('loginError=1')) {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'), {});
            loginModal.show();
        }
    });
</script>
</body>
</html>
