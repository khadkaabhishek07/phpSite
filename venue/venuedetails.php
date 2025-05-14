<?php

// Check if venueId is provided in the URL
if (isset($_GET['venueId'])) {
    $venueId = htmlspecialchars($_GET['venueId']); // Sanitize input to prevent XSS attacks

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bandobasta.onrender.com/bandobasta/api/v1/hall/findAll?venueId=' . $venueId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer your_auth_token_here' // Replace with the actual authorization token
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    // Decode JSON response
    $data = json_decode($response, true);

    // Check if the API call was successful
    if ($data && $data['code'] === "200" && isset($data['data']['hallDetails'])) {
        $hallDetails = $data['data']['hallDetails'];
    } else {
        $hallDetails = [];
    }
} else {
    // Handle missing venueId
    echo "Venue ID not provided.";
    $hallDetails = [];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container{
            width: 600px;
            height: auto;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            max-width: 150px;
        }
        .venue-header img {
            width:100%;
            border-radius: 10px;
            align-items: center;
        }
        .venue-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .venue-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .venue-actions .btn {
            flex: 1 1 calc(50% - 10px); /* Default for mobile: Two buttons per row */
        }
        .btn-red {
            background-color: #d9534f;
            color: white;
        }
        .btn-red:hover {
            background-color: #c9302c;
        }

        /* Media query for larger screens */
        @media (min-width: 768px) {
            .container{
                width: 600px;
                height:auto;
            }
            .venue-card {
                padding: 20px;
                margin-bottom: 30px;
            }
            .venue-actions .btn {
                flex: 1 1 calc(25% - 10px); /* Four buttons in one row on larger screens */
            }
        }
        @media (max-width: 430px) {
            .container{
                width: 430px;
                height:auto;
            }
            .venue-card {
                padding: 20px;
                margin-bottom: 30px;
            }
            .venue-actions .btn {
                flex: 1 1 calc(25%-5px); /* Four buttons in one row on larger screens */
            }
        }
    </style>
</head>
<body>
<div class="container py-3">
    <!-- Center-Aligned Logo -->
    <div class="logo-container">
        <img src="logo.png" alt="Logo" class="img-fluid">
    </div>

    <!-- Venue Details -->
    <div class="venue-card">
        <div class="venue-header">
            <img src="<?= $hallDetails[0]['hallImagePaths'][0] ?? 'https://via.placeholder.com/800x400' ?>" alt="Venue Image">
        </div>
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($hallDetails[0]['name'] ?? 'Venue Title') ?></h5>
            <p class="card-text"><?= htmlspecialchars($hallDetails[0]['description'] ?? 'Venue description goes here.') ?></p>
            <div class="venue-actions">
                <button class="btn btn-success">Our Food Menu</button>
                <button class="btn btn-danger">Create a Package</button>
                <button class="btn btn-success">View Halls</button>
                <button class="btn btn-danger">View Packages</button>
            </div>
        </div>
        <div class="d-flex align-items-center mt-3">
            <div class="me-2">
                <span class="text-warning">★★★★☆</span>
                <small class="text-muted">(5 Reviews)</small>
            </div>
            <a href="#" class="text-primary ms-auto">Read all</a>
        </div>
        <div class="mt-3 d-flex align-items-center">
            <i class="bi bi-geo-alt-fill text-muted"></i>
            <p class="mb-0 ms-2">456-Kamalpokhari, Kathmandu, Lalitpur</p>
        </div>
        <div class="mt-3 text-center">
            <!-- Book Now Button for Main Hall -->
            <a href="viewmenu.php?venueId=<?= $venueId ?>" class="btn btn-red px-4">Book Now</a>
        </div>

    </div>

    <!-- Additional Halls Section -->
    <?php if (count($hallDetails) > 1): ?>
        <div class="mt-4">
            <h5>Other Halls</h5>
            <div class="row">
                <?php foreach (array_slice($hallDetails, 1) as $hall): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="venue-card">
                            <img src="<?= $hall['hallImagePaths'][0] ?? 'https://via.placeholder.com/800x400' ?>" alt="<?= htmlspecialchars($hall['name']) ?>" class="img-fluid">
                            <h5 class="mt-3"><?= htmlspecialchars($hall['name']) ?></h5>
                            <p><?= htmlspecialchars($hall['description']) ?></p>
                            <ul class="list-unstyled">
                                <li><strong>Floor:</strong> <?= htmlspecialchars($hall['floorNumber']) ?></li>
                                <li><strong>Capacity:</strong> <?= htmlspecialchars($hall['capacity']) ?> people</li>
                                <li><strong>Status:</strong> <span class="<?= $hall['status'] === 'AVAILABLE' ? 'text-success' : 'text-danger' ?>"><?= htmlspecialchars($hall['status']) ?></span></li>
                            </ul>
                            <a href="viewmenu.php?venueId=<?= $venueId ?>" class="btn btn-red w-100">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
