<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $redirect = $_POST['redirect'] ?? 'index.php'; // Default to index if not set

    // Validate input
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Username and password are required.';
        header("Location: $redirect");
        exit();
    }

    // Prepare CURL request for authentication
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/user/authenticate/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            "username" => $username,
            "password" => $password
        )),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    if ($response === false) {
        // CURL error handling
        $_SESSION['error'] = 'Unable to connect to the server. Please try again.';
        curl_close($curl);
        header("Location: $redirect");
        exit();
    }

    curl_close($curl);

    // Decode response and check login status
    $responseData = json_decode($response, true);

    if (isset($responseData['code']) && $responseData['code'] === "200") {
        // Successful login
        $_SESSION['user_logged_in'] = true;
        $_SESSION['username'] = htmlspecialchars($responseData['data']['username']); // Sanitize output
        $_SESSION['token'] = htmlspecialchars($responseData['data']['accessToken']); // Store access token in session
        $_SESSION['userId'] = htmlspecialchars($responseData['data']['id']);

        // Redirect back to the specified page with parameters
        header("Location: $redirect");
        exit();
    } else {
        // Authentication failed
        $_SESSION['error'] = htmlspecialchars($responseData['message'] ?? 'Invalid username or password.');
        header("Location: $redirect");
        exit();
    }
} else {
    // Redirect to the index page if accessed directly
    header("Location: index.php");
    exit();
}
?>
