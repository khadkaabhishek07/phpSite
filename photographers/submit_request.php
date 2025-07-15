<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/sites/35b/d/d056ee809c/public_html/phpmailer/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $package_id = filter_input(INPUT_POST, 'package_id', FILTER_SANITIZE_STRING);
    $package_name = filter_input(INPUT_POST, 'package_name', FILTER_SANITIZE_STRING); // Collect the package name
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $event_date = filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Setup PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com'; // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = '845ed5001@smtp-brevo.com'; // Your SMTP username
        $mail->Password   = 'kshd0FWc3T4XbGft'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('nepalbandobasta@gmail.com', 'Bandobasta Nepal');
        $mail->addAddress('saleemthp@gmail.com', $name); // Vendor's Email
        $mail->addCC('nepalbandobasta@gmail.com'); // CC to Bandobasta
        $mail->addCC('fotoboxnepal@gmail.com');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Photography Service Request - Bandobasta';
        $mail->Body    = "
    <html>
    <head>
        <style>
            body { background-color: #f4f4f4; font-family: Arial, sans-serif; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
            h2 { font-size: 24px; color: #333; font-weight: bold; margin-bottom: 20px; }
            .highlight { background-color: #f0f8ff; padding: 15px; border-left: 4px solid #007bff; margin-bottom: 20px; }
            .highlight p { margin: 5px 0; font-size: 16px; color: #555; }
            .footer { font-size: 14px; color: #666; margin-top: 20px; }
            .footer a { color: #007bff; text-decoration: none; }
            .footer a:hover { text-decoration: underline; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Wedding Photography Service Request</h2>
            <div class='highlight'>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Event Date:</strong> $event_date</p>
                <p><strong>Package:</strong> $package_name</p>
                <p><strong>Message:</strong> $message</p>
            </div>
            
            <div class='footer' style='font-size: 14px; color: #666; margin-top: 20px;'>
                <p style='margin-bottom: 10px; font-weight: bold;'>Please contact the customer to confirm and finalize the pricing.</p>
                <p style='margin-bottom: 10px;'>For any inquiries or assistance, feel free to reach out to us at +977-9763683078 or  <a href='mailto:info@bandobasta.com' style='color: #007bff; text-decoration: none;'>info@bandobasta.com</a></p>
                <p style='font-size: 12px; color: #999;'>We are here to help with any questions you may have!</p>
            </div>
        </div>
    </body>
    </html>
";

        // Send the email
        $mail->send();

        // Redirect to index.php with success message
        header("Location: index.php?status=success");
        exit();

    } catch (Exception $e) {
        // Redirect to index.php with error message
        header("Location: index.php?status=error&message=" . urlencode($mail->ErrorInfo));
        exit();
    }
}
?>
