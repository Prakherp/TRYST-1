<?php
require 'vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;

    function getQRCode($name,$email,$mobile){
        // code to generate QR code
        
        // fetch qr from goqr.com
        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=';
        // encrypt data
        $data = encryptData($name,$email,$mobile);
        // send request to goqr.com
        $response = file_get_contents($url.$data);
        // save qr code
        file_put_contents('qr.png',$response);
        // return qr code
        return $response;

    }

    function encryptData($name,$email,$mobile){
        // code to encrypt data
        $data = $name.",/|".$email.",/|".$mobile;
        $data = base64_encode($data);
        return $data;
    }

// Email details
$to = $email;
$subject = "Test Email";
$message = "This is Test Email";

// SMTP server details
$smtpHost = "us2.smtp.mailhostbox.com";
$smtpUsername = "trystsample@gmail.com";
$smtpPassword = "TrystSample@1";

// Email headers
$headers = array(
    "From:trystsample@gmail.com",
    "Reply-To: ${email}",
    "X-Mailer: PHP/" . phpversion()
);

// Setup SMTP connection
$smtp = @fsockopen($smtpHost, 587, $errno, $errstr, 10);

if (!$smtp) {
    echo "Error: " . $errstr . " (" . $errno . ")";
} else {
    $data = fgets($smtp, 1024);
    fputs($smtp, "EHLO " . $_SERVER['HTTP_HOST'] . "\r\n");
    $data = fgets($smtp, 1024);
    fputs($smtp, "STARTTLS\r\n");
    $data = fgets($smtp, 1024);
    stream_socket_enable_crypto($smtp, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
    fputs($smtp, "EHLO " . $_SERVER['HTTP_HOST'] . "\r\n");
    $data = fgets($smtp, 1024);
    fputs($smtp, "AUTH LOGIN\r\n");
    $data = fgets($smtp, 1024);
    fputs($smtp, base64_encode($smtpUsername) . "\r\n");
    $data = fgets($smtp, 1024);
    fputs($smtp, base64_encode($smtpPassword) . "\r\n");
    $data = fgets($smtp, 1024);
    fputs($smtp, "MAIL FROM: <trystsample@gmail.com>\r\n");
    $data = fgets($smtp, 1024);
    fputs($smtp, "RCPT TO: <" . $to . ">\r\n");
    $data = fgets($smtp, 1024);
    fputs($smtp, "DATA\r\n");
    $data = fgets($smtp, 1024);
    fputs($smtp, "Subject: " . $subject . "\r\n");
    foreach ($headers as $header) {
        fputs($smtp, $header . "\r\n");
    }
    fputs($smtp, "\r\n" . $message . "\r\n.\r\n");
    $data = fgets($smtp, 1024);
    fputs($smtp, "QUIT\r\n");
    fclose($smtp);
    echo "Email sent successfully.";
}

?>
