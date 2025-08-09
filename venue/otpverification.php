<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
  exit();
}

// Gather inputs
$otp = trim($_POST['otp'] ?? '');
$redirect = $_POST['redirect'] ?? $_POST['uri'] ?? ($_SERVER['HTTP_REFERER'] ?? 'index.php');

// Basic redirect safety (avoid open redirects)
if (preg_match('/^\s*https?:\/\//i', $redirect) || strpos($redirect, '//') === 0) {
  $redirect = 'index.php';
}

// Validate OTP format
if (!preg_match('/^\d{6}$/', $otp)) {
  $_SESSION['otp_error'] = 'Please enter a valid 6-digit OTP.';
  $_SESSION['otp_required'] = true;
  header("Location: $redirect");
  exit();
}

// Call OTP validation API
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/user/validateOTP?otp=' . urlencode($otp),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
]);

$response = curl_exec($curl);

if ($response === false) {
  $_SESSION['otp_error'] = 'Unable to verify OTP. Please try again.';
  $_SESSION['otp_required'] = true;
  curl_close($curl);
  header("Location: $redirect");
  exit();
}

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

// Parse API response optionally carrying { code: "200", ... }
$data = json_decode($response, true);
$ok = ($httpCode === 200) || (is_array($data) && isset($data['code']) && (string)$data['code'] === '200');

if ($ok) {
  unset($_SESSION['otp_required'], $_SESSION['otp_error']);
  $_SESSION['success'] = 'OTP verified successfully.';
  header("Location: $redirect");
  exit();
}

$_SESSION['otp_error'] = is_array($data) && isset($data['message']) ? htmlspecialchars($data['message']) : 'Invalid OTP.';
$_SESSION['otp_required'] = true;
header("Location: $redirect");
exit();
