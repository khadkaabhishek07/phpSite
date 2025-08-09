<?php
// registrationpage.php - The page to handle user registration

// Check for success or failure message based on previous action
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

    <div class="alert alert-danger mt-3" id="error-message" style="display: none;"></div>
</div>

<!-- OTP Modal -->
<div class="modal fade" id="pinModal" tabindex="-1" aria-labelledby="pinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pinModalLabel">Enter OTP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="pinInput" class="form-label">Enter the 6-digit OTP sent to your email:</label>
                <input type="text" class="form-control" id="pinInput" placeholder="6-digit OTP" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="verifyPinButton">Verify OTP</button>
            </div>
        </div>
    </div>
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
            role: "ROLE_USER",
        };

        fetch('https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/user/authenticate/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.code === "200") {
                // Registration successful, show OTP modal
                const pinModal = new bootstrap.Modal(document.getElementById('pinModal'));
                pinModal.show();
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
            fetch(`https://bandobasta-latest-5u7o.onrender.com/bandobasta/api/v1/user/validateOTP?otp=${pin}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.code === "200") {
                    alert("OTP verified successfully!");
                    window.location.href = "index.php"; // Redirect to homepage on success
                } else {
                    alert("Invalid OTP. Please try again.");
                    window.location.href = "registrationpage.php"; // Stay on the registration page if OTP is invalid
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Something went wrong while verifying the OTP.");
                window.location.href = "registrationpage.php"; // Stay on the registration page if error occurs
            });
        } else {
            alert("Please enter a valid 6-digit OTP.");
        }
    });
</script>

</body>
</html>
