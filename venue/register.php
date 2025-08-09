<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php'); exit();
}

$redirect = $_POST['redirect'] ?? ($_SERVER['HTTP_REFERER'] ?? 'index.php');

$firstName   = trim($_POST['firstName'] ?? '');
$lastName    = trim($_POST['lastName'] ?? '');
$middleName  = trim($_POST['middleName'] ?? '');
$email       = trim($_POST['email'] ?? '');
$phoneNumber = trim($_POST['phoneNumber'] ?? '');
$username    = trim($_POST['username'] ?? '');
$role        = trim($_POST['role'] ?? '');
$password    = trim($_POST['password'] ?? '');

if (!$firstName || !$lastName || !$email || !$phoneNumber || !$username || !$role || !$password) {
  $_SESSION['error'] = 'All required fields must be filled.';
  header("Location: $redirect"); exit();
}

$payload = [
  "firstName"   => $firstName,
  "lastName"    => $lastName,
  "middleName"  => $middleName,
  "email"       => $email,
  "phoneNumber" => $phoneNumber,
  "username"    => $username,
  "role"        => $role, // e.g. ROLE_USER
  "password"    => $password,
];

$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/user/authenticate/register',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
]);

$response = curl_exec($curl);

if ($response === false) {
  $_SESSION['error'] = 'Unable to reach registration service.';
  curl_close($curl);
  header("Location: $redirect"); exit();
}

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$data = json_decode($response, true);
$codeField = is_array($data) && isset($data['code']) ? (string)$data['code'] : null;

if ($httpCode === 200 || $codeField === '200') {
  $_SESSION['otp_required'] = true;
  $_SESSION['info'] = 'Enter the 6-digit OTP sent to your email/phone.';
  $_SESSION['otp_redirect'] = $redirect;
  header("Location: $redirect"); exit();
}

$_SESSION['error'] = is_array($data) && isset($data['message']) ? htmlspecialchars($data['message']) : 'Registration failed.';
header("Location: $redirect"); exit();