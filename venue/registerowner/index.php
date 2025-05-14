<?php
// registrationpage.php - The page to handle both user registration and OTP verification

// Check for any success or failure message from actions
$message = isset($_GET['message']) ? $_GET['message'] : '';
$otpVerified = isset($_GET['otpVerified']) ? $_GET['otpVerified'] : '';

// If OTP is successfully verified, redirect to homepage
if ($otpVerified === 'true') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .modal-content {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<!-- Registration Form -->
<div class="container">
    <h2 class="text-center">Sign Up</h2>
   <div id="registrationSection" style="display:block;"> 
    <!-- Registration Form -->
    <form id="signupForm">
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" required>
        </div>
        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="phoneNumber" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
    </form>
    </div>

    <!-- OTP Verification Form (Hidden initially) -->
    <div id="otpVerificationSection" style="display: none;">
        <h4 class="text-center mt-3">Verify OTP</h4>
        <div class="mb-3">
            <label for="pinInput" class="form-label">Enter the 6-digit OTP sent to your email:</label>
            <input type="text" class="form-control" id="pinInput" placeholder="6-digit OTP" required>
        </div>
        <button class="btn btn-danger w-100" id="verifyPinButton">Verify OTP</button>
        <div class="alert alert-danger mt-3" id="otpErrorMessage" style="display: none;"></div>
    </div>

    <div class="alert alert-danger mt-3" id="error-message" style="display: none;"></div>
</div>

<script>
    // Handle Signup Form Submission
    document.getElementById("signupForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {
            firstName: formData.get("firstName"),
            lastName: formData.get("lastName"),
            email: formData.get("email"),
            phoneNumber: formData.get("phoneNumber"),
            username: formData.get("username"),
            password: formData.get("password"),
            role: "ROLE_OWNER",
        };

        // Submit registration request using Fetch API (will use PHP cURL internally for POST request)
        fetch('registerOwner.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)  // This sends data in JSON format
        })
        .then(response => response.json())
        .then(data => {
            if (data.code === "200" && data.message === "SUCCESS") {
                // Registration successful, show OTP verification section
                document.getElementById("registrationSection").style.display = 'none';
                document.getElementById("otpVerificationSection").style.display = 'block';
                document.getElementById("error-message").style.display = 'none';
            } else {
                // Registration failed, show error message
                const errorMessage = document.getElementById('error-message');
                errorMessage.style.display = 'block';
                errorMessage.textContent = data.message || 'Something went wrong, please try again.';
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Something went wrong. Please try again.");
        });
    });

    // Handle OTP Verification
    document.getElementById("verifyPinButton").addEventListener("click", function () {
        const pin = document.getElementById("pinInput").value;
        if (pin.length === 6) {
            // Send OTP for verification using Fetch API
            const otpData = {
                otp: pin
            };

            fetch('../verifyOTP.php', {  // This endpoint should handle the OTP verification via cURL
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(otpData)  // json_encode($otpData) is used here in the POST request
            })
            .then(response => response.json())
            .then(data => {
                if (data.code === "200") {
                    // OTP Verified Successfully
                    alert("OTP verified successfully!");
                    window.location.href = "index.php"; // Redirect to homepage on success
                } else {
                    // Invalid OTP
                    const otpErrorMessage = document.getElementById('otpErrorMessage');
                    otpErrorMessage.style.display = 'block';
                    otpErrorMessage.textContent = "Invalid OTP. Please try again.";
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Something went wrong while verifying the OTP.");
            });
        } else {
            alert("Please enter a valid 6-digit OTP.");
        }
    });
</script>

</body>
</html>
