<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
  exit();
}

// Gather inputs
$otp = trim($_POST['otp'] ?? $_POST['token'] ?? '');
$redirect = $_POST['redirect'] ?? $_POST['uri'] ?? ($_SERVER['HTTP_REFERER'] ?? 'index.php');

// Basic redirect safety (avoid open redirects)
if (preg_match('/^\s*https?:\/\//i', $redirect) || strpos($redirect, '//') === 0) {
  $redirect = 'index.php';
}

// Validate OTP/token format (6 digits)
if (!preg_match('/^\d{6}$/', $otp)) {
  $_SESSION['otp_error'] = 'Please enter a valid 6-digit OTP.';
  $_SESSION['otp_required'] = true;
  header("Location: $redirect");
  exit();
}

// Call new OTP confirmation API
$url = 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/user/authenticate/register/confirm?token=' . urlencode($otp);

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
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

// Check for code === 200 and message === "SUCCESS"
$data = json_decode($response, true);
$codeOk = is_array($data) && isset($data['code']) && ((string)$data['code'] === '200' || (int)$data['code'] === 200);
$messageOk = is_array($data) && isset($data['message']) && strtoupper(trim($data['message'])) === 'SUCCESS';

if ($httpCode === 200 && $codeOk && $messageOk) {
  unset($_SESSION['otp_required'], $_SESSION['otp_error']);
  $_SESSION['success'] = 'OTP verified successfully.';
  header("Location: $redirect");
  exit();
}

$_SESSION['otp_error'] = is_array($data) && isset($data['message']) ? htmlspecialchars($data['message']) : 'Invalid or expired OTP.';
$_SESSION['otp_required'] = true;
header("Location: $redirect");
exit();
