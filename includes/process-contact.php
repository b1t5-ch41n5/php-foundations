<head>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

</head>
<?php
// 📧 Process contact form submissions
// Include PHPMailer for sending emails
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// 🌐 Function to get the real IP address of the user
function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// 🌍 Function to get geolocation information based on IP
function getLocationInfo($ip) {
    // Attempt to fetch data from ipapi.co
    $url = "https://ipapi.co/{$ip}/json/";
    $response = @file_get_contents($url);
    
    if ($response) {
        // Decode the response to an array
        $data = json_decode($response, true);
        // Check if the response is valid
        if ($data && !isset($data['error'])) {
            return [
                'ip' => $data['ip'] ?? $ip,
                'country' => $data['country_name'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'region' => $data['region'] ?? 'Unknown',
                'timezone' => $data['timezone'] ?? 'Unknown',
                'isp' => $data['org'] ?? 'Unknown'
            ];
        }
    }
    
    // Fallback: attempt to fetch data from ip-api.com
    $url2 = "http://ip-api.com/json/{$ip}";
    $response2 = @file_get_contents($url2);
    
    if ($response2) {
        $data2 = json_decode($response2, true);
        // Check if the response is valid
        if ($data2 && $data2['status'] === 'success') {
            return [
                'ip' => $data2['query'] ?? $ip,
                'country' => $data2['country'] ?? 'Unknown',
                'city' => $data2['city'] ?? 'Unknown',
                'region' => $data2['regionName'] ?? 'Unknown',
                'timezone' => $data2['timezone'] ?? 'Unknown',
                'isp' => $data2['isp'] ?? 'Unknown'
            ];
        }
    }
    
    // If all attempts fail, return basic data
    return [
        'ip' => $ip,
        'country' => 'Unknown',
        'city' => 'Unknown',
        'region' => 'Unknown',
        'timezone' => 'Unknown',
        'isp' => 'Unknown'
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Get geolocation data
    $user_ip = getRealIpAddr();
    
    // Prioritize frontend data, if not available use backend
    $location_info = [
        'ip' => $_POST['user_ip'] ? $_POST['user_ip'] : $user_ip,
        'country' => $_POST['user_country'] ? $_POST['user_country'] : '',
        'city' => $_POST['user_city'] ? $_POST['user_city'] : '',
        'region' => $_POST['user_region'] ? $_POST['user_region'] : '',
        'timezone' => $_POST['user_timezone'] ? $_POST['user_timezone'] : ''
    ];
    
    //if any location info is missing, fetch from backend
    if (empty($location_info['country'])) {
        $location_info = getLocationInfo($user_ip);
    }
    
    // Get user agent and timestamp
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    //$timestamp = date('Y-m-d H:i:s') H means 24-hour format, i means minutes, s means seconds
    $timestamp = date('Y-m-d H:i:s');
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "<h2>Error</h2>";
        echo "<p>Please complete all required fields.</p>";
        echo "<a href='contact-form.php'>Back to Form</a>";
        exit;
    }
    
    // Validate email
    if ( $var = !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h2>Error</h2>";
        echo "<p>The email is not valid.</p>";
        echo "<a href='contact-form.php'>Back to Form</a>";
        exit;
    }
    
    // Create PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // SMTP server configuration (original credentials)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = ''; // Original email
        $mail->Password   = ''; // Original password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Email configuration
        $mail->setFrom('', 'FORM FROM B1T5 CH41N5');
        $mail->addAddress($email, 'Recipient'); // Email to receive messages
        $mail->addReplyTo($email, $name);
        
        // Email content with location information
        $mail->isHTML(true);
        $mail->Subject = 'Contact from web: ' . $subject;
        
        $mail->Body = "
        <h2>🌍 New message from the contact form</h2>
        
        <h3>📝 Contact information:</h3>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Subject:</strong> $subject</p>
        
        <h3>📍 Location information:</h3>
        <p><strong>IP:</strong> {$location_info['ip']}</p>
        <p><strong>Country:</strong> {$location_info['country']}</p>
        <p><strong>City:</strong> {$location_info['city']}</p>
        <p><strong>Region:</strong> {$location_info['region']}</p>
        <p><strong>Timezone:</strong> {$location_info['timezone']}</p>
        
        <h3>🕒 Technical information:</h3>
        <p><strong>Date and time:</strong> $timestamp</p>
        <p><strong>Browser:</strong> $user_agent</p>
        
        <h3>💬 Message:</h3>
        <div style='background-color: #f5f5f5; padding: 15px; border-left: 4px solid #007cba;'>
            $message
        </div>";
        
        $mail->send();

        ?> 
        <div class="container mt-5">
            <div class="alert alert-success">
                <h4>✅ Message Sent</h4>
                <p>Thanks for contacting us. We will get back to you as soon as possible.</p>
                <a href="contact-form.php" class="btn btn-secondary">← Back to Form</a>
            </div>
        </div>
        <?php
        
    } catch (Exception $e) {
        
        echo "<h2>Error</h2>";
        "<p>Message could not be sent. Error: {$mail->ErrorInfo}</p>";
    }
}

?>