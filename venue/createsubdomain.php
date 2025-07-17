<?php
$apiToken = getenv("CF_API_TOKEN");
$zoneId = getenv("CF_ZONE_ID");
$domain = getenv("CF_DOMAIN");
$targetIp = getenv("CF_TARGET_IP");

$sub = "vendor1"; // Or fetch from form input
$fullSubdomain = "$sub.$domain";

$data = [
    "type" => "A",
    "name" => $fullSubdomain,
    "content" => $targetIp,
    "ttl" => 3600,
    "proxied" => false
];

$ch = curl_init("https://api.cloudflare.com/client/v4/zones/$zoneId/dns_records");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiToken",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if ($result["success"]) {
    echo "✅ Subdomain '$fullSubdomain' created successfully!";
} else {
    echo "❌ Error:<br>";
    print_r($result["errors"]);
}
?>
