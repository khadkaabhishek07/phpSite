<?php
// registerUser.php - Handles User Registration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['firstName'], $inputData['lastName'], $inputData['email'], $inputData['phoneNumber'], $inputData['username'], $inputData['password'])) {
        // Prepare data for registration API
        $data = [
            'firstName' => $inputData['firstName'],
            'lastName' => $inputData['lastName'],
            'email' => $inputData['email'],
            'phoneNumber' => $inputData['phoneNumber'],
            'username' => $inputData['username'],
            'password' => $inputData['password'],
            'role' => 'ROLE_OWNER',
        ];

        // cURL setup for registration request
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://bandobasta.onrender.com/bandobasta/api/v1/user/authenticate/register',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($data)  // This is where we use json_encode($data)
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        // Decode the API response
        $responseData = json_decode($response, true);

        // Return response back to frontend
        if ($responseData['code'] === "200") {
            echo json_encode([
                'code' => '200',
                'message' => 'SUCCESS'
            ]);
        } else {
            echo json_encode([
                'code' => '400',
                'message' => $responseData['message'] ?? 'Registration failed'
            ]);
        }
    } else {
        echo json_encode([
            'code' => '400',
            'message' => 'All fields are required'
        ]);
    }
}
?>
