<?php
// php/contact-handler.php with reCAPTCHA verification

// reCAPTCHA secret key
$recaptchaSecret = getenv('RECAPTCHA_SECRET') ?: '6LcY-8wZAAAAAIwa_zq163QNrn_rP8ZBg_c7kAyg';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

// Verify reCAPTCHA
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents($verifyUrl . '?secret=' . urlencode($recaptchaSecret) . '&response=' . urlencode($recaptchaResponse));
$decoded = json_decode($response, true);
if (!isset($decoded['success']) || $decoded['success'] !== true) {
    http_response_code(400);
    echo 'reCAPTCHA verification failed. Bitte versuche es erneut.';
    exit;
}

// Sanitize helper
function sanitize_input(string $data): string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Collect and validate inputs
$name    = sanitize_input($_POST['name'] ?? '');
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$message = sanitize_input($_POST['message'] ?? '');

if (empty($name) || !$email || empty($message)) {
    http_response_code(400);
    echo 'Bitte fülle alle Felder korrekt aus.';
    exit;
}

// Prepare email
$to      = 'fberger@felixberger.de';
$subject = "Kontaktanfrage von $name";
$body    = "Name: $name\nE-Mail: $email\n\nNachricht:\n$message\n";
$headers = [
    'From'         => sprintf('%s <%s>', $name, $email),
    'Reply-To'     => $email,
    'Content-Type' => 'text/plain; charset=UTF-8',
];

// Send mail
if (mail($to, $subject, $body, $headers)) {
    http_response_code(200);
    echo 'Vielen Dank! Deine Nachricht wurde gesendet.';
} else {
    http_response_code(500);
    echo 'Fehler beim Senden der Nachricht. Bitte versuche es später erneut.';
}