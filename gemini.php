<?php
// gemini.php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data["message"] ?? "";

$apiKey = "AIzaSyDSwxNhePFNfCIVJTzKoxni0kQ2aQo7X9g"; // Replace with your real key

$projectContext = "
You are a helpful assistant for SafeNet, a platform to report Domestic Violence and Child Abuse.
- Users can report incidents and contact volunteer lawyers.
- You provide guidance on reporting, safety, privacy, and navigating the platform.
";

$ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
  "contents" => [[
    "role" => "user",
    "parts" => [["text" => $projectContext . "\nUser: " . $userMessage]]
  ]]
]));

$response = curl_exec($ch);
curl_close($ch);

error_log($response);

$result = json_decode($response, true);
$reply = $result["candidates"][0]["content"]["parts"][0]["text"] ?? "Gemini returned no reply. Please try again later.";

echo json_encode(["reply" => $reply]);
?>
