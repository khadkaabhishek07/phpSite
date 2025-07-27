<?php
// verifyOTP.php - Handles OTP Verification

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);

    if (isset($inputData['otp'])) {
        $otp = $inputData['otp'];

        // cURL setup for OTP verification with the updated URL
        $curl = curl_init();

        // Construct the final URL by appending the OTP token
        $url = 'https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/user/authenticate/register/confirm?token=' . $otp;

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        // Decode the API response
        $responseData = json_decode($response, true);

        // Return response back to frontend
        if ($responseData['code'] === "200") {
            echo json_encode([
                'code' => '200',
                'message' => 'OTP verified successfully'
            ]);
        } else {
            echo json_encode([
                'code' => '400',
                'message' => 'Invalid OTP'
            ]);
        }
    } else {
        echo json_encode([
            'code' => '400',
            'message' => 'OTP is required'
        ]);
    }
}
?>
