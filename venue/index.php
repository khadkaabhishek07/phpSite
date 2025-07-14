<?php
session_start(); // Start the session to check login status

// Assume 'user_logged_in' is a session variable that holds the login status
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Bandobasta Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .navbar-nav .nav-link {
    padding: 0.5rem 1rem; /* Adjust padding */
    font-size: 1.1rem; /* Adjust font size */
}

.navbar-nav .nav-link.active {
    font-weight: bold; /* Make the active link bold */
    color: #e60000 !important; /* Change color of active link */
}

        section {
            background-color: #f8f9fa;
            border-radius: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom:10px;
        }
        .card-text {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    text-align: justify;
}

.venue-image {
    max-height: 200px;
    object-fit: cover;
}

        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }
        .card-img-top {
            transition: transform 0.3s ease-in-out;
        }
        .card-title {
    font-size: 1.2rem;
    font-weight: bold;
    color: #343a40;
}

.card-text {
    font-size: 0.9rem;
    line-height: 1.4;
}

.card-text .small {
    font-size: 0.85rem;
}

.card-text .text-primary {
    font-size: 1rem;
    font-weight: bold;
}

.card img {
    object-fit: cover;
    height: 200px;
    width: 100%;
}

.card-body {
    padding: 1rem;
}

.badge {
    font-size: 0.8rem;
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

        /* Default Styles for Tablets */
@media (max-width: 1024px) and (min-width: 768px) {
    body {
        font-size: 16px; /* Default font size for readability */
        line-height: 1.5; /* More readable line spacing */
    }

    .card-title {
        font-size: 1.2rem; /* Smaller title font size for tablets */
    }

    .card-text {
        font-size: 0.95rem; /* Slightly smaller text for readability */
    }

    .venue-image {
        height: auto; /* Ensure images maintain aspect ratio */
        width: 100%; /* Ensure the image fits within the container */
    }

    .card-body {
        padding: 1rem; /* Reduced padding for a more compact layout */
    }

    .col-md-3 {
        flex: 0 0 48%; /* 2 items per row for smaller tablets */
        max-width: 48%; /* Ensures 2 items per row */
    }

    .col-sm-6 {
        flex: 0 0 48%; /* Make sure on smaller screens (e.g., portrait mode) it's 2 per row */
        max-width: 48%;
    }

    .mb-4 {
        margin-bottom: 1.5rem; /* Reduced margin to keep items closer */
    }

    .badge {
        font-size: 0.9rem; /* Smaller badge text */
        padding: 0.4rem 0.6rem; /* Slightly smaller padding in badges */
    }

    .btn-outline-danger {
        font-size: 0.9rem; /* Smaller button text */
        padding: 0.5rem 1rem; /* Adjusted padding */
    }

    .row {
        display: flex;
        flex-wrap: wrap; /* Make sure items wrap properly */
    }

    .position-relative {
        height: 200px; /* Fix image height for better layout */
        overflow: hidden;
    }

    /* Reduce space around the card */
    .card {
        margin-bottom: 1.5rem; /* Reduced space between cards */
    }
}

/* iPad Pro (12.9-inch) or larger tablets (landscape and portrait) */
@media (max-width: 1366px) and (min-width: 1025px) {
    .card-title {
        font-size: 1.3rem; /* Slightly bigger title for larger tablets */
    }

    .card-text {
        font-size: 1rem; /* Larger text for easier reading */
    }

    .col-md-3 {
        flex: 0 0 23%; /* 4 items per row for larger tablets */
        max-width: 23%; /* 4 per row */
    }

    .col-sm-6 {
        flex: 0 0 23%;
        max-width: 23%;
    }

    .btn-outline-danger {
        font-size: 1rem; /* Larger button text for bigger screens */
    }

    /* Space out cards more on larger tablets */
    .mb-4 {
        margin-bottom: 2rem;
    }

    .badge {
        font-size: 1rem;
        padding: 0.5rem 0.8rem; /* Slightly larger badges */
    }

    /* Adjust image size */
    .venue-image {
        height: 240px; /* Larger images for larger devices */
    }

    /* Ensure flex wrapping works properly */
    .row {
        display: flex;
        flex-wrap: wrap;
    }

    .card-body {
        padding: 1.5rem;
    }
}

    </style>
</head>
<body style="padding-bottom: 60px;">
<nav class="navbar navbar-expand-lg bg-white">
    <div class="container">
        <!-- Logo Section -->
        <a class="navbar-brand" href="index.php">
            <img src="logo.png" alt="Bandobasta Logo" style="height: 60px;">
        </a>

        <!-- Navbar Links (Centered) -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Venues</a> <!-- Active link -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" target="_blank">Events</a> <!-- Link to Events -->
                </li>
            </ul>
        </div>

        <!-- Register Button (Right Aligned, Hidden on Small Screens) -->
        <div class="d-flex ms-auto d-none d-sm-block d-lg-block">
            <button class="btn btn-outline-danger" id="checkMenuBtn" onclick="window.location.href='./registerowner'">Register Your Venue</button>
        </div>
    </div>
</nav>


<div class="container py-4">
   <!-- <header class="text-center mb-4">
        <img src="logo.png" style="align-items: center; width: 200px;">
    </header>-->

    <!--<section class="p-3 bg-light">
        <div class="container" style="padding-bottom:20px;">
       
             Event Type Selection 
            <div class="mb-3">
                <h5 class="mb-2">Event Type</h5>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-outline-danger">Wedding</button>
                    <button class="btn btn-outline-danger">Birthday</button>
                    <button class="btn btn-outline-danger">Corporate Event</button>
                    <button class="btn btn-outline-danger">Party</button>
                </div>
            </div>

          
            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label for="event-date" class="form-label">Select Date</label>
                    <input type="date" id="event-date" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="guests-no" class="form-label">Number of Guests</label>
                    <input type="number" id="guests-no" class="form-control" placeholder="Enter number of guests">
                </div>
            </div> 

          
            <div class="text-center">
                <button class="btn btn-danger btn-lg">Check Availability</button>
            </div>
        </div>
    </section>-->
    <section>
    <div class="container" style="max-width: 100vw; margin: 0 auto; padding: 20px;">
        <form action="searchvenues.php" method="GET">
            <div class="row">
                <!-- Event Type Dropdown -->
                <div class="col-md-3 mb-3">
                    <label for="eventType" class="form-label">Event Type</label>
                    <select id="eventType" name="eventType" class="form-select" style="height: 50px; border-radius: 20px;">
                        <option value="" disabled selected>Select Event Type</option>
                        <option value="wedding">Wedding</option>
                        <option value="conference">Conference</option>
                        <option value="party">Party</option>
                        <option value="meeting">Meeting</option>
                    </select>
                </div>
                
                <!-- Number of Guests Input -->
                <div class="col-md-3 mb-3">
                    <label for="guestCount" class="form-label">Number of Guests</label>
                    <input 
                        type="number" 
                        id="guestCount" 
                        name="numberOfGuests" 
                        class="form-control" 
                        placeholder="Enter number of guests" 
                        style="height: 50px; border-radius: 20px;" 
                        min="1" 
                        required>
                </div>
                
               
                <div class="col-md-3 mb-3">
                    <label for="location" class="form-label">Location</label>
                    <select id="location" name="location" class="form-select" style="height: 50px; border-radius: 20px;">
                        <option value="" disabled selected>Select Location</option>
                        <option value="kathmandu">Kathmandu</option>
                        <option value="lalitpur">Lalitpur</option>
                        <option value="bhaktapur">Bhaktapur</option>
                    </select>
                </div>
                <!-- Search Venues Button -->
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-danger w-100" style="height: 50px; border-radius: 20px;">
                        Search Venues
                    </button>
                </div>
            </div>
        </form>

    </div>
</section>





       <!-- Featured Services Heading -->
<div class="container" style="padding-bottom: 20px;">
    <h2 class="text-start" style="font-size: 24px; font-weight: bold; margin-bottom: 20px;">
        Featured Services
    </h2>

    <!-- Booking Options -->
    <div class="row g-3 justify-content-center">
        <div class="col d-flex flex-column align-items-center">
            <button class="btn btn-light">
                <i class="bi bi-building"></i>
            </button>
            <p class="text-center">Event Venues</p>
        </div>
        <div class="col d-flex flex-column align-items-center">
            <button class="btn btn-light">
                <i class="bi bi-camera"></i>
            </button>
            <p class="text-center">Photographer</p>
        </div>
        <div class="col d-flex flex-column align-items-center">
            <button class="btn btn-light">
                <i class="bi bi-person"></i>
            </button>
            <p class="text-center">Henna Artist</p>
        </div>
        <div class="col d-flex flex-column align-items-center">
            <button class="btn btn-light">
                <i class="bi bi-brush"></i>
            </button>
            <p class="text-center">Makeup Artist</p>
        </div>
    </div>
</div>

<h3 class="mb-3">Featured Venues</h3>
<div id="featured-venues" class="row">
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/venue/findAll?size=100',
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

    if ($data && isset($data['data']['venues'])) {
        foreach ($data['data']['venues'] as $venue) {
            echo '<div class="col-md-3 col-sm-6 mb-4">';
            echo '<div class="card shadow-lg h-100 border-0 rounded-lg">';
            echo '<div class="position-relative">';
            echo '<img src="' . htmlspecialchars($venue['venueImagePaths'][0]) . '" class="card-img-top venue-image rounded-top" alt="' . htmlspecialchars($venue['name']) . '">';
            echo '</div>';
            echo '<div class="card-body d-flex flex-column">';
            echo '<h5 class="card-title fw-bold mb-3">' . htmlspecialchars($venue['name']) . '</h5>';
            
            // Split the address by commas
            // Remove leading numbers and hyphen (e.g., "10-" from "10-Kupondole Heights")
            $clean_address = preg_replace('/^\d+-/', '', $venue['address']);

            // Split the address by commas
            $address_parts = explode(',', $clean_address);

            // Get only the first two parts (e.g., "Kupondole Heights, Lalitpur")
            $short_address = trim($address_parts[0]) . ',' . trim($address_parts[1]);

            echo '<p class="card-text text-secondary d-flex align-items-center mb-2 text-wrap text-justify">';
            echo '<i class="bi bi-geo-alt-fill text-danger me-2"></i>'; // Address Icon
            echo '<span class="small">' . htmlspecialchars($short_address) . '</span>';
            echo '</p>';
            // Starting Price
            // Remove the decimal part
            $price = intval($venue['menuPrice']); // This will remove the decimal part

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
            echo '<a href="viewvenue.php?venueId=' . htmlspecialchars($venue['id']) . '" class="btn btn-outline-danger mt-auto">View More</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
     else {
        echo '<p class="text-center text-muted">No venues available at the moment.</p>';
    }
    ?>
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



<!-- Footer Navigation -->
<footer class="fixed-bottom bg-light d-flex justify-content-around py-2">
    <a href="#" class="text-danger text-center" style="text-decoration: none;">
        <i class="bi bi-house-door-fill" style="font-size: 1.5rem;"></i>
        <p class="mb-0" style="font-size: 0.75rem;">Home</p>
    </a>
    <a href="#" class="text-danger text-center" style="text-decoration: none;">
        <i class="bi bi-heart-fill" style="font-size: 1.5rem;"></i>
        <p class="mb-0" style="font-size: 0.75rem;">Favorites</p>
    </a>
<?php if ($is_logged_in): ?>
<a href="history.php?token=<?php echo htmlspecialchars($userToken); ?>" class="text-danger text-center" style="text-decoration: none;">
    <i class="bi bi-clock-history" style="font-size: 1.5rem;"></i>
    <p class="mb-0" style="font-size: 0.75rem;">History</p>
</a>
<?php else: ?>
             <a href="#" class="text-danger text-center" style="text-decoration: none;">
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
            <i class="bi bi-person-fill" style="font-size: 1.5rem;"></i>
            <p class="mb-0" style="font-size: 0.75rem;">Profile</p>
        </a>
    <?php endif; ?>
</footer>
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
