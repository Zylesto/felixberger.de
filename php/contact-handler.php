<?php
// php/contact-handler.php with reCAPTCHA v3 verification

// reCAPTCHA secret key (v3)
$recaptchaSecret = 'SECRET-KEY';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /404.html');
    exit;
}

// reCAPTCHA v3: verify token and check score/action
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
$verifyUrl         = 'https://www.google.com/recaptcha/api/siteverify';

// Send verification request to Google
$response = @file_get_contents(
    $verifyUrl
    . '?secret='   . urlencode($recaptchaSecret)
    . '&response=' . urlencode($recaptchaResponse)
);

// If the API request fails
if ($response === false) {
    http_response_code(502);
    header('Content-Type: application/json');
    echo json_encode([
        'message' => 'Fehler beim Senden der Nachricht. Bitte versuche es später erneut.'
    ]);
    exit;
}

$decoded = json_decode($response, true);

// Minimum acceptable score (0.0–1.0; here: 0.7)
$minScore = 0.7;

// Validate that verification succeeded, action matches, and score is high enough
if (
    empty($decoded['success'])
    || ($decoded['action'] ?? '')  !== 'contact'
    || ($decoded['score']   ?? 0)  <  $minScore
) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'message' => 'Fehler beim Senden der Nachricht. Bitte versuche es später erneut.'
    ]);
    exit;
}

// Helper function to sanitize input
function sanitize_input(string $data): string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Collect form data
$name    = sanitize_input($_POST['name']    ?? '');
$email   = trim($_POST['email'] ?? '');
$email   = $email === '' ? false : filter_var($email, FILTER_VALIDATE_EMAIL);
$message = sanitize_input($_POST['message'] ?? '');

// Validate fields
if (empty($name) || !$email || empty($message)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode([
        'message' => 'Bitte fülle alle Felder korrekt aus.'
    ]);
    exit;
}

// Prepare email
$to      = 'felix.berger98@icloud.com';
$subject = "Kontaktanfrage von $name";
$body    = "Name: $name\nE-Mail: $email\n\nNachricht:\n$message\n";
$headers = [
    'From' => 'kontakt@felixberger.de',
    'Reply-To' => $email,
    'Content-Type' => 'text/plain; charset=UTF-8'
];

// Send email
$mailSuccess = mail($to, $subject, $body, $headers);

if ($mailSuccess) {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode([
        'message' => 'Vielen Dank! Deine Nachricht wurde gesendet.'
    ]);
} else {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'message' => 'Fehler beim Senden der Nachricht. Bitte versuche es später erneut.'
    ]);
}
