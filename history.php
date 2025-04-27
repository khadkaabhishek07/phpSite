<?php
session_start(); // Start the session

// Regenerate session ID to prevent session fixation
session_regenerate_id(true);

// Check if the token exists in the session
if (!isset($_SESSION['token'])) {
echo '<p class="alert alert-danger text-center">Error: Token not found. Please log in again.</p>';
exit;
}

$token = $_SESSION['token']; // Retrieve the token from the session
// Assumed 'user_logged_in' is a session variable that holds the login status
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;

// Retrieve userId from the session
$userId = $_SESSION['userId'] ?? null;
if ($userId === null) {
echo '<p class="alert alert-danger text-center">Error: User ID not found. Please log in again.</p>';
exit;
}

// Validate JWT token expiration
$decoded_token = jwt_decode($token); // Function to decode JWT (you need to implement it)
if ($decoded_token['exp'] < time()) {
echo '<p class="alert alert-danger text-center">Error: Token has expired. Please log in again.</p>';
exit;
}

// Initialize cURL
$curl = curl_init();

// Replace the hardcoded userId with the dynamic value from the session
curl_setopt_array($curl, array(
CURLOPT_URL => "https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/booking/hall/user?userId=$userId", // Use the session userId dynamically
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_HTTPHEADER => array(
"Authorization: Bearer $token", // Use token from session
"Content-Type: application/json",
),
));

$response = curl_exec($curl);

// Check if cURL failed
if ($response === false) {
error_log('cURL error: ' . curl_error($curl)); // Log the error for debugging
echo '<p class="alert alert-danger text-center">Error: Could not fetch data from the API. Please try again later.</p>';
exit;
}

curl_close($curl);

// Decode the JSON response
$data = json_decode($response, true);

// Check if bookings are available
if (isset($data['data']['bookings']) && !empty($data['data']['bookings'])) {
$bookings = $data['data']['bookings'];
} else {
$bookings = []; // Initialize as empty if no bookings found
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Bookings</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
body {
background-color: #f7f7f7;
font-family: Arial, sans-serif;
}
.container {
margin-top: 20px;
}
.booking-card {
transition: transform 0.2s;
}
.booking-card:hover {
transform: scale(1.05);
box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}
.card-title {
font-size: 1.5rem;
font-weight: bold;
}
.badge {
font-size: 0.85rem;
}
@media (max-width: 768px) {
.card-title {
font-size: 1.25rem;
}
}
</style>
</head>
<body style="margin-bottom: 60px;">
<div class="container">
<h2 class="text-center">Your Bookings</h2>

<?php if (!empty($bookings)): ?>
<?php foreach ($bookings as $booking): ?>
<?php
$venueName = htmlspecialchars($booking['venueName'] ?? 'N/A');
$hallName = htmlspecialchars($booking['hallDetail']['name'] ?? 'N/A');
$bookedForDate = htmlspecialchars($booking['bookedForDate'] ?? 'N/A');
$status = htmlspecialchars($booking['status'] ?? 'N/A');
$id = htmlspecialchars($booking['id']??'N/A');
?>
<div class="card booking-card mb-3">
<div class="card-body">
<h5 class="card-title"><?php echo $venueName; ?></h5>
<p class="card-text"><strong>Hall:</strong> <?php echo $hallName; ?></p>
<p class="card-text"><strong>Booked For Date:</strong> <?php echo $bookedForDate; ?></p>
<p class="card-text"><strong>Status:</strong> <span class="badge <?php echo getStatusClass($status); ?>"><?php echo $status; ?></span></p>
<p class="card-text"><strong>Booking Id:</strong><?php echo $id ?></p>
<p class="card-text"><strong>User Id:</strong><?php echo $userId ?></p>
</div>
</div>
<?php endforeach; ?>
<?php else: ?>
<p class="alert alert-warning text-center">No bookings found.</p>
<?php endif; ?>
</div>
<footer class="fixed-bottom bg-light d-flex justify-content-around py-2">
<a href="index.php" class="text-danger text-center" style="text-decoration: none;">
<i class="bi bi-house-door-fill" style="font-size: 1.5rem;"></i>
<p class="mb-0" style="font-size: 0.75rem;">Home</p>
</a>
<a href="#" class="text-danger text-center" style="text-decoration: none;">
<i class="bi bi-heart-fill" style="font-size: 1.5rem;"></i>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js"></script>

<?php
// Function to determine status badge class
function getStatusClass($status)
{
switch (strtolower($status)) {
case 'confirmed':
return 'bg-success';
case 'pending':
return 'bg-warning text-dark';
case 'cancelled':
return 'bg-danger';
default:
return 'bg-secondary';
}
}

// JWT decode function (simplified)
function jwt_decode($token) {
list($header, $payload, $signature) = explode('.', $token);
$decoded_payload = base64_decode($payload);
return json_decode($decoded_payload, true);
}
?>
</body>
</html>