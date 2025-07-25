<?php
session_start(); // Start the session

// Retrieve location from GET parameter or session
$location = isset($_GET['location']) ? htmlspecialchars($_GET['location']) : (isset($_SESSION['location']) ? $_SESSION['location'] : 'default-location');
// Assume 'user_logged_in' is a session variable that holds the login status
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
// Save location in session for future use
if (!empty($location)) {
    $_SESSION['location'] = $location;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandobasta Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">

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

<div class="container py-4 ">
<div class="flex items-center justify-between w-full px-4 py-2 relative">
  <!-- Back Button -->
  <button 
    onclick="history.back()" 
    class="text-sm bg-gray-200 px-3 py-1 rounded hover:bg-gray-300 flex items-left space-x-1 whitespace-nowrap">
    <i class="fas fa-arrow-left"></i>
    <span>Back</span>
  </button>

  <!-- Placeholder element to balance the layout -->
  <div class="w-24"></div>

    </div>
<h3 class="mb-3"> Search Results</h3>
    <div id="featured-venues" class="row">

    <?php
    // Initialize cURL
    $curl = curl_init();

    // API endpoint with dynamic location
    $apiUrl = 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/venue/findAll?location=' . urlencode($location);

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    if ($response === false) {
        echo '<p class="text-center text-muted">Unable to fetch venue data. Please try again later.</p>';
        curl_close($curl);
        return;
    }

    curl_close($curl);

    $data = json_decode($response, true);

    $data = json_decode($response, true);

    if ($data && isset($data['data']['venues'])) {
        foreach ($data['data']['venues'] as $venue) {
            echo '<div class="col-md-4 col-sm-6 mb-4">';
            echo '<div class="card shadow-lg h-100 border-0 rounded-lg">';
            echo '<div class="position-relative">';
            echo '<img src="' . htmlspecialchars($venue['venueImagePaths'][0]) . '" class="card-img-top venue-image rounded-top" alt="' . htmlspecialchars($venue['name']) . '">';
            echo '<div class="position-absolute top-0 start-0 bg-danger text-white px-3 py-1 rounded-end">Featured</div>'; // Badge
            echo '</div>';
            echo '<div class="card-body d-flex flex-column">';
            echo '<h5 class="card-title fw-bold mb-3">' . htmlspecialchars($venue['name']) . '</h5>';
        
            // Address
            echo '<p class="card-text text-secondary d-flex align-items-center mb-2">';
            echo '<i class="bi bi-geo-alt-fill text-danger me-2"></i>'; // Address Icon
            echo '<span class="small">' . htmlspecialchars($venue['address']) . '</span>';
            echo '</p>';
        
            // Remove the decimal part
            $price = intval($venue['menuPrice']); // This will remove the decimal part
            // Starting Price
            echo '<p class="card-text text-muted d-flex align-items-center mb-3">';
            echo '<strong class="me-2">Starting Price:</strong>';
            echo '<span class="text-primary fw-bold small"> Rs ' . htmlspecialchars($price) . ' ONWARDS </span>';
            echo '</p>';
        
            // Facilities (icons or text)
            echo '<div class="mb-3">';
            echo '<span class="badge bg-light text-dark me-2"><i class="bi bi-wifi"></i> Free WiFi</span>';
            echo '<span class="badge bg-light text-dark me-2"><i class="bi bi-cup-straw"></i> Catering</span>';
            echo '<span class="badge bg-light text-dark"><i class="bi bi-tv"></i> AV Equipment</span>';
            echo '</div>';
        
            // Button for Booking
            if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
                echo '<a href="viewvenue.php?venueId=' . htmlspecialchars($venue['id']) . '" class="btn btn-danger mt-auto">Book Now</a>';
            } else {
                echo '<button class="btn btn-danger mt-auto" data-bs-toggle="modal" data-bs-target="#loginModal">View More</button>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        
        
    } else {
        echo '<p class="text-center text-muted">No venues available at the moment.</p>';
    }
    ?>
    </div>
</div>

<!-- Footer Navigation -->
<footer class="fixed-bottom bg-light d-flex justify-content-around py-2">
    <a href="../venue/" class="text-danger text-center" style="text-decoration: none;">
        <i class="bi bi-house-door-fill" style="font-size: 1.5rem;"></i>
        <p class="mb-0" style="font-size: 0.75rem;">Home</p>
    </a>
    <?php if ($is_logged_in): ?>
    <a href="#" class="text-danger text-center" style="text-decoration: none;">
        <i class="bi bi-heart-fill" style="font-size: 1.5rem;"></i>
        <p class="mb-0" style="font-size: 0.75rem;">Favorites</p>
    </a>
    <?php else: ?>
        <a href="#" class="text-danger text-center" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#loginModal">
        <i class="bi bi-heart" style="font-size: 1.5rem;"></i>
        <p class="mb-0" style="font-size: 0.75rem;">Favorites</p>
    </a>
    <?php endif; ?>

    <?php if ($is_logged_in): ?>
    <a href="history.php?token=<?php echo htmlspecialchars($userToken); ?>" class="text-danger text-center" style="text-decoration: none;">
        <i class="bi bi-clock-history" style="font-size: 1.5rem;"></i>
        <p class="mb-0" style="font-size: 0.75rem;">History</p>
    </a>
    <?php else: ?>
                <a href="#" class="text-danger text-center" style="text-decoration: none;"  data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-clock-history" style="font-size: 1.5rem;"></i>
                    <p class="mb-0" style="font-size: 0.75rem;">History</p>
                </a>
    <?php endif; ?>

    
    <?php if ($is_logged_in): ?>
        <a href="logout.php" class="text-danger text-center" style="text-decoration: none;">
            <i class="bi bi-box-arrow-right" style="font-size: 1.5rem;"></i>
            <p class="mb-0" style="font-size: 0.75rem;">Logout</p>
        </a>
    <?php else: ?>
        <a href="#" class="text-danger text-center" style="text-decoration: none;">
            <i class="bi bi-person-fill" style="font-size: 1.5rem;" data-bs-toggle="modal" data-bs-target="#loginModal"></i>
            <p class="mb-0" style="font-size: 0.75rem;">Profile</p>
        </a>
    <?php endif; ?>
</footer>

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
                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
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

<!-- Warning Modal 
<div class="modal fade" id="authWarningModal" tabindex="-1" aria-labelledby="authWarningModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="authWarningModalLabel">Authentication Required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You need to log in first to view your booking history.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="proceedToLoginBtn">OK</button>
      </div>
    </div>
  </div>
</div>
<script>
  document.getElementById("proceedToLoginBtn").addEventListener("click", function () {
    // Hide the warning modal
    var warningModal = bootstrap.Modal.getInstance(document.getElementById('authWarningModal'));
    warningModal.hide();

    // Show the login modal
    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
  });
</script>
-->
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
