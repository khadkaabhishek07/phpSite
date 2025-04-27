<?php
// You can include any dynamic logic here if needed
// For now, we'll just output the iframe and its container
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embedded Iframe</title>
    <style>
        /* Responsive iframe container */
        .iframe-container {
            position: relative;
            padding-top: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
            max-width: 100%;
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none; /* Ensures there's no border */
        }
    </style>
</head>
<body>

    <div class="iframe-container">
        <iframe 
            src="https://cdn.crichdplays.ru/embed2.php?id=starsp" 
            name="iframe_a" 
            title="Live stream" 
            scrolling="no" 
            width="100%" 
            height="520">
        </iframe>
    </div>

</body>
</html>
