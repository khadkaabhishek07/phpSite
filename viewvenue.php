<?php
session_start();
// Now you can access $_SESSION['token']
$userToken = $_SESSION['token'] ?? null;
// Assume 'user_logged_in' is a session variable that holds the login status
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Get the venueId from the URL, if it's set, otherwise use a default value
$venueId = isset($_GET['venueId']) ? $_GET['venueId'] : null;

// Initialize cURL session for venue address
$curl1 = curl_init();

// Set cURL options for the venue address request
curl_setopt_array($curl1, array(
    CURLOPT_URL => 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/venue/findAll?size=100',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));

// Execute cURL request for venue address and capture response
$response1 = curl_exec($curl1);

// Check for errors in the cURL request for venue
if (curl_errno($curl1)) {
    echo 'cURL Error for Venue: ' . curl_error($curl1);
    curl_close($curl1);
    exit;
}

// Close cURL session for venue
curl_close($curl1);

// Decode the JSON response for venue
$responseData1 = json_decode($response1, true);

// Initialize variables
$venueAddress = '';
$description = '';
$name = '';
$startingPrice = '';

// Check if venue data is available and fetch the necessary information for the dynamic venueId
foreach ($responseData1['data']['venues'] as $venue) {
    if ($venue['id'] == $venueId) {
        $venueAddress = $venue['address'];  // Store address in the variable
        $description = $venue['description'];  // Store description in the variable
        $name = $venue['name']; 
        $startingPrice = $venue['startingPrice']; // Store starting price in the variable
        break;
    }
}
// Initialize cURL session for hall image
$curl2 = curl_init();

// Set cURL options for the hall request using the dynamic venueId
curl_setopt_array($curl2, array(
    CURLOPT_URL => 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/hall/findAll?venueId=' . $venueId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $userToken, // Use the stored access token
        'Content-Type: application/json'
    ),
));

// Execute cURL request for hall data and capture response
$response2 = curl_exec($curl2);

// Check for errors in the cURL request for hall
if (curl_errno($curl2)) {
    echo 'cURL Error for Hall: ' . curl_error($curl2);
    curl_close($curl2);
    exit;
}

// Close cURL session for hall
curl_close($curl2);

// Decode the JSON response for hall
$responseData2 = json_decode($response2, true);

// Initialize image URL and capacity variables
//$imageUrl = '';
$capacity = 0;

/// Initialize an array to hold all image URLs
$imageUrls = [];

// Check if hall image data is available and fetch all image URLs
if (isset($responseData2['data']['hallDetails'][0]['hallImagePaths'])) {
    $imageUrls = $responseData2['data']['hallDetails'][0]['hallImagePaths'];
} else {
    echo 'No hall images found.';
    exit;
}


// Fetch the capacity
if (isset($responseData2['data']['hallDetails'][0]['capacity'])) {
    $capacity = $responseData2['data']['hallDetails'][0]['capacity'];
} else {
    echo 'No hall details found.';
    exit;
}
// hall deatils
if (isset($responseData2['data']['hallDetails'])) {
    $hallDetailinfo = $responseData2['data']['hallDetails'];

    // Initialize the variable to store all card HTML
    $hallCardsHtml = '';

    // Loop through each hall and build the card design
    foreach ($hallDetailinfo as $hall) {
        $hallCardsHtml .= '<div class="card hall-card">';
        //$hallCardsHtml .= '<img src="' . htmlspecialchars($hall['hallImagePaths'][0]) . '" class="card-img-top" alt="' . htmlspecialchars($hall['name']) . '">';
        $hallCardsHtml .= '<div class="card-body">';
        $hallCardsHtml .= '<h5 class="card-title">' . htmlspecialchars($hall['name']) . '</h5>';
        $hallCardsHtml .= '<p class="card-text">Capacity: ' . htmlspecialchars($hall['capacity']) . '</p>';
        //$hallCardsHtml .= '<p class="card-text">Hall ID: ' . htmlspecialchars($hall['id']) . '</p>';
        //$hallCardsHtml .= '<a href="#" class="btn btn-primary">View Details</a>';
        $hallCardsHtml .= '</div>';
        $hallCardsHtml .= '</div>';
    }
} else {
    // If no hall details, set a default message
    $hallCardsHtml = '<p>No hall details found.</p>';
}



// At this point, the venue and hall details are available and can be used for further processing.
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />    <title>Venue Listings |Bandobata</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Add Bootstrap CSS -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa; /* Light background */
        }
        .navbar {
            background-color: #343a40; /* Dark navbar */
        }
        .navbar-brand, .nav-link {
            color: #ffffff; /* White text */
        }
        .carousel-item img {
            border-radius: 10px; /* Rounded images */
        }
        .btn {
            border-radius: 20px; /* Rounded buttons */
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Card shadow */
        }
        /* Add hover effects */
        #checkMenuBtn:hover {
        background-color: #c82333; /* Dark Red */
        border-color: #bd2130;  /* Dark Red Border */
    }
    .booking-form {
    height: 480px; /* Adjust this value as needed */
}
.information{
    margin-top:-35px;
}
.booking-form {
    display: flex;
    flex-direction: column;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
}

.booking-form .card-body {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.booking-form .price {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0;
    color: #333;
}

.booking-form .price span {
    font-size: 1rem;
    font-weight: normal;
    color: #666;
}

.booking-form .minimum {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1rem;
}

.booking-form form {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.booking-form .form-label {
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.booking-form .form-control {
    border: 1px solid #ced4da;
    border-radius: 5px;
    padding: 0.5rem;
}

.booking-form .btn-primary {
    background-color: #B00302;
    border-color: #B00302;
    padding: 0.75rem;
    font-size: 1.1rem;
}
.btn-outline-danger:hover{
    background-color:#B00302;
    color:white;
}
.btn-danger:hover{
    background-color:#B00302;

}

.booking-form .btn-primary:hover {
    background-color: #8f0202;
    border-color: #8f0202;
}
.card {
    border: 1px solid #e6e6e6;
    border-radius: 10px;
}

.card h1 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
}

.card .text-warning i {
    color: #fbc02d; /* Brighter yellow for stars */
}

.card a {
    text-decoration: none;
}

.card a:hover {
    text-decoration: underline;
    color: #0056b3;
}
#viewPhotosBtn {
    position: absolute;
    bottom: 20px;
    right: 20px;
    z-index: 10;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    background-color: rgba(255, 255, 255, 0.8);
    color: #333;
    border: none;
    transition: background-color 0.3s;
}
#viewPhotosBtn:hover {
    background-color: rgba(255, 255, 255, 1);
} 
.card-title{
  font-size: 1rem;  
}
.row1 {
    display: flex; /* Align items in a row */
    flex-wrap: nowrap; /* Prevent cards from wrapping */
    gap: 15px; /* Add spacing between cards */
    overflow-x: auto; /* Allow horizontal scrolling for overflow */
}

.hall-card {
    flex: 0 0 auto; /* Prevent shrinking or wrapping */
    width: 180px; /* Set fixed card width */
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
}

.hall-card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    width: 100%; /* Make image fit the card width */
    height: 120px; /* Adjust height as needed */
    object-fit: cover; /* Keep image proportions */
}


   
        /* Custom class for margin on larger screens */
@media (min-width: 992px) { /* Adjust this breakpoint as needed */
                        /* Style for the specific host-info card */
            .host-info.card.mb-3.custom-margin {
                width:420px;
                background-color: #f8f9fa; /* Light background color */
                border: 1px solid #ddd;    /* Border color */
                border-radius: 10px;       /* Rounded corners */
                padding: 20px;             /* Padding inside the card */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
                margin-left: 15px; 
                margin-top:-5px;
                padding:40px;
                /* Margin between cards */
            }
            
            /* If you want to target specific elements inside this card */
            .host-info.card.mb-3.custom-margin .card-title {
                font-size: 1.25rem;        /* Adjust title font size */
                font-weight: bold;         /* Make title bold */
            }
            
            .host-info.card.mb-3.custom-margin .card-body {
                font-size: 1rem;           /* Adjust body font size */
                color: #333;               /* Text color */
            }
            .carousel-container{
                height:480px;
            }

        }
        
@media(max-width:440px){
            
        #viewPhotosBtn {
            bottom: 5px;
            right: 5px;
            padding: 4px 8px;
            font-size: 0.6rem;
        }
        .col-lg-4{
            margin-top:20px;
        }
        .host-info{
            width:400px;
        }
        .card-title{
    font-size: 1rem;  
    }

    .hall-card {
        flex: 0 0 auto; /* Prevent shrinking or wrapping */
        width: 180px; /* Set fixed card width */
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .hall-card:hover {
        transform: translateY(-5px);
    }

    .card-img-top {
        width: 100%; /* Make image fit the card width */
        height: 120px; /* Adjust height as needed */
        object-fit: cover; /* Keep image proportions */
    }

}
@media only screen and (min-width: 440px) and (max-width: 991px){
    .col-lg-4{
        margin-top:20px;
    }
}
 @media only screen and (min-device-width: 1024px) and (max-device-width: 1366px) 
 {
    #viewPhotosBtn {
        
        font-size: 1rem;
        bottom:20px;
    }  
    .carousel-container{
                height:420px;
            }
    .booking-form{
        height:415px;
        width:340px;
    }
    .information{
        margin-top:-20px;
    }
     .host-info.card.mb-3.custom-margin {
                width:340px;
                background-color: #f8f9fa; /* Light background color */
                border: 1px solid #ddd;    /* Border color */
                border-radius: 10px;       /* Rounded corners */
                padding: 30px;             /* Padding inside the card */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
                margin-left: 15px; 
                margin-top:-5px;
                padding:40px;
                /* Margin between cards */
            }
            
    .moveup{
        margin-top: -10px;
    }
 
 }
    </style>
</head>
<body style="margin-bottom: 60px;">
<nav class="navbar navbar-expand-lg bg-white">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="logo.png" alt="Bandobasta Logo" style="height: 60px;"> <!-- Logo image -->
        </a>
        <div class="d-flex ms-auto"> <!-- Use ms-auto to push the button to the right -->
                <button class="btn btn-outline-danger" id="checkMenuBtn" onclick="window.location.href='./registerowner'">Register Your Venue</button>
        </div>
    </div>
</nav>


    <div class="container mt-4">
        <div class="row mb-5">
            <!-- Carousel -->
            <div class="col-lg-8">
                <div class="carousel-container">
                <div id="venueCarousel" class="carousel slide" data-bs-interval="false">
    <div class="carousel-inner">
        <?php if (!empty($imageUrls)): ?>
            <!-- First image as active -->
            <div class="carousel-item active">
                <img src="<?php echo htmlspecialchars($imageUrls[0]); ?>" alt="Hall Image 1" class="d-block w-100"/>
            </div>
            
            <!-- Loop through remaining images -->
            <?php for ($i = 1; $i < count($imageUrls); $i++): ?>
                <div class="carousel-item">
                    <img src="<?php echo htmlspecialchars($imageUrls[$i]); ?>" alt="Hall Image <?php echo $i + 1; ?>" class="d-block w-100"/>
                </div>
            <?php endfor; ?>
        <?php else: ?>
            <div class="carousel-item active">
                <p>No image available.</p>
            </div>
        <?php endif; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#venueCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#venueCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
    <button id="viewPhotosBtn" class="btn">
        <i class="fas fa-camera me-2"></i>View all photos
    </button>
</div>
</div>
</div>

<!-- Booking Form -->
<div class="col-lg-4">
    <div class="booking-form card">
        <div class="card-body">
            <form action="availablehall.php" method="GET">
                <div class="row">
                    <!-- Event Type Dropdown -->
                    <div class="col-12 mb-4 mt-3">
                        <label for="eventType" class="form-label">Event Type</label>
                        <select id="eventType" name="eventType" class="form-select" required style="height: 50px; border-radius: 20px;">
                            <option value="" disabled selected>Select Event Type</option>
                            <option value="WEDDING">Wedding</option>
                            <option value="CONFERENCE">Conference</option>
                            <option value="PARTY">Party</option>
                            <option value="MEETING">Meeting</option>
                        </select>
                    </div>

                    <!-- Number of Guests Input -->
                    <div class="col-12 mb-4">
                        <label for="guestCount" class="form-label">Number of Guests</label>
                        <input type="number" id="guestCount" name="numberOfGuests" required placeholder="e.g., 50" min="1" max="1000" step="1" class="form-control" style="height: 50px; border-radius: 20px;">
                    </div>
                    <div class="col-12 mb-4">
                        <label for="eventDate" class="form-label">Event Date</label>
                        <input type="date" id="eventDate" name="eventDate" required class="form-control" style="height: 50px; border-radius: 20px;">
                    </div>

                    <!-- Hidden Venue ID -->
                    <input type="hidden" name="venueId" value="<?php echo htmlspecialchars($venueId); ?>">

                    <!-- Search Venues Button -->
                    <div class="col-12 d-flex align-items-end mt-2">
                        <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                            <button type="submit" class="btn btn-outline-danger mt-auto w-100">Check Availability</button>
                        <?php else: ?>
                            <button type="button" class="btn btn-outline-danger mt-auto w-100" data-bs-toggle="modal" data-bs-target="#loginModal">Login to Proceed</button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="row mt-5">
            <!-- Hotel Info -->
    <div class="col-lg-8 information">
        <div class="container">
            <div class="card-body">
                <div class="venue-info">
                    <!-- Venue Header -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3">
                        <h1 class="mb-2 mb-sm-0"><?php echo $name; ?></h1>
                        <button class="btn btn-outline-danger btn-sm mt-2 mt-sm-0" id="addToFavorites">
                            <i class="far fa-heart"></i> Favourite
                        </button>
                    </div>
                    
                    <!-- Star Rating and Reviews -->
                    <div class="d-flex align-items-center mb-3">
                        <span class="text-warning me-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </span>
                        <span>5 reviews - <a href="#" class="text-primary">Read all</a></span>
                    </div>

                    <!-- Address and Map Link -->
                    <p>
                        <i class="fas fa-map-marker-alt"></i> <?php echo $venueAddress; ?> 
                        <a href="#" class="text-primary" id="showMapLink">Show map</a>
                    </p>

                    <!-- Features -->
                    <div class="row mb-4">
                        <div class="col-auto me-4 mb-2">
                            <i class="fas fa-home"></i> Whole venue
                        </div>
                        <div class="col-auto mb-2">
                            <i class="fas fa-chair"></i> Up to <?php echo htmlspecialchars($capacity); ?>
                        </div>
                    </div>
                    
                    <!-- About Section -->
                    <h2>About this space</h2>
                    <p><?php echo $description; ?></p>
                    <h2> Halls</h2>
                    <div class="container mt-4">
                        <div class="row1 justify-content-start">
                            <!-- Insert the generated card HTML here -->
                            <?php echo $hallCardsHtml; ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

            <!-- Host Info -->
    <div class="col-lg-4 d-flex flex-column">
                <div class="host-info card mb-3 custom-margin">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center mb-2">
                            <img src="host.jpg" alt="Carly R." class="rounded-circle me-2" width="50" height="50">
                            <div>
                                <h5 class="mb-0">Jhon Rai</h5>
                                <p class="text-muted mb-0">Your Personal Event Manager from Bandobasta</p>
                            </div>
                        </div>
                        <div class="mb-2">
                            <p class="mb-1"><i class="fas fa-chart-line me-2"></i>Response rate - 98%</p>
                            <p class="mb-0"><i class="far fa-clock me-2"></i>Response time - 1h</p>
                        </div>
                        <button class="btn btn-outline-primary w-100">Message host</button>
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
    <a href="history.php?token=<?php echo htmlspecialchars($userToken); ?>" class="text-danger text-center" style="text-decoration: none;">
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
    <!-- 
    Map Overlay 
    <div id="mapOverlay" class="map-overlay">
        <div class="map-content">
            <span class="close-btn">&times;</span>
            <h2>Hotel Annapurna </h2>
            <div id="map" style="width: 100%; height: 400px;"></div>
        </div>
    </div>
-->

<div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoModalLabel">All Photos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="photoCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <!-- Images will be injected here via JavaScript -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.getElementById('navbarNav');
        
        navbarToggler.addEventListener('click', function() {
            // Toggle the collapse class when the hamburger menu is clicked
            if (navbarCollapse.classList.contains('show')) {
                navbarCollapse.classList.remove('show');
            }
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
<script>
document.getElementById('viewPhotosBtn').addEventListener('click', function() {
    // Fetch dynamic images from PHP
    const images = <?php echo json_encode($imageUrls); ?>; // Convert PHP array to JavaScript array
    
    const carouselInner = document.querySelector('#photoCarousel .carousel-inner');
    carouselInner.innerHTML = ''; // Clear existing images

    images.forEach((imgSrc, index) => {
        const carouselItem = document.createElement('div');
        carouselItem.className = 'carousel-item' + (index === 0 ? ' active' : '');
        carouselItem.innerHTML = `<img src="${imgSrc}" class="d-block w-100" alt="Photo ${index + 1}">`;
        carouselInner.appendChild(carouselItem);
    });

    // Show the modal
    var myModal = new bootstrap.Modal(document.getElementById('photoModal'));
    myModal.show();
});
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.2.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- jQuery (only needed for Bootstrap 4, not 5) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>



