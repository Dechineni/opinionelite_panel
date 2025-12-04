<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer (use Composer or manual include)
require 'vendor/autoload.php'; // Composer method
// OR manually:
// require 'src/PHPMailer.php';
// require 'src/SMTP.php';
// require 'src/Exception.php';

$mail = new PHPMailer(true);

try {
    // Server settings
   $mail->isSMTP();
$mail->Host       = 'smtp.office365.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'noreply@opinionelite.com';  // Microsoft 365 email
$mail->Password   = 'kkzzxtbjxmhpkjvm';  // Real password or App Password
$mail->SMTPSecure = 'tls';
$mail->Port       = 587;

$mail->setFrom('noreply@opinionelite.com', 'Opinion Elite');
$mail->addAddress('rajeshwariganji1979@gmail.com', 'Rajeshwari');
$mail->isHTML(true);
$mail->Subject = 'Welcome to Opinion Elite!';
$mail->Body = '
<div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; background-color: #f4f4f4;">
  <div style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); padding: 30px;">
    <h2 style="color: #333333; text-align: center;">Welcome to <span style="color: #0078d4;">Opinion Elite</span>!</h2>
    <p style="font-size: 16px; color: #555555;">
      Hi Rajeshwari,
    </p>
    <p style="font-size: 16px; color: #555555; line-height: 1.6;">
      Thanks for signing up with <strong>Opinion Elite</strong>!<br>
      Were glad to have you on board. You can now participate in exclusive surveys and share your valuable opinions.
    </p>
    <div style="text-align: center; margin: 30px 0;">
      <a href="https://opinionelite.com" style="background-color: #0078d4; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
        Get Started
      </a>
    </div>
    </div>
</div>';


    $mail->send();
    echo '✅ Email sent successfully!';
} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
