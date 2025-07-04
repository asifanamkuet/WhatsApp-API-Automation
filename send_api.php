<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "POST request only."]);
    exit;
}

$target = $_POST['target_name'] ?? '';
$message = $_POST['message'] ?? '';

if (empty($target) || empty($message)) {
    http_response_code(400);
    echo json_encode(["error" => "Missing target_name or message"]);
    exit;
}

$target_escaped = escapeshellarg($target);
$message_base64 = base64_encode($message);  // encode safely
$message_escaped = escapeshellarg($message_base64);

// Run the Python script with base64 message
$command = "python3 send_whatsapp.py $target_escaped $message_escaped";
exec($command . " 2>&1", $output, $status);

if ($status === 0) {
    echo json_encode(["success" => true, "output" => $output]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to send message", "output" => $output]);
}
?>
