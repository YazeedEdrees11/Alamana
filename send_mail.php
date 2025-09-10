<?php 
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'PHPMailer/src/Exception.php';
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';

// if (isset($_POST['name'])    &&
// 	isset($_POST['email'])   &&
// 	isset($_POST['phone'])   &&
//     isset($_POST['service']) &&
//     isset($_POST['message']) 
// 	) {
	
// 	$name = $_POST['name'];
// 	$email = $_POST['email'];
// 	$phone = $_POST['phone'];
// 	$service = $_POST['service.value'];
// 	$message = $_POST['message'];
    
//     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     	$em = "Invalid email format";
//     	header("Location: index.php?error=$em");
//     }

//     if (empty($name) || empty($service) || empty($message) ) {
//     	$em = "Fill out all required entry fields";
//     	header("Location: index.php?error=$em");
//     }

// 	//Create an instance; passing `true` enables exceptions
// 	$mail = new PHPMailer(true);

// 	try {
// 	    $mail->isSMTP();                               
// 	    $mail->Host = 'smtp.gmail.com'; 
// 	    $mail->SMTPAuth   = true;
// 	    //Your Email
// 	    $mail->Username= 'yazeededrees11@gmail.com';
// 	    //App password
// 	    $mail->Password = 'ndyi  neapaggijbnu'; 
// 	    $mail->SMTPSecure = "ssl";          
// 	    $mail->Port       = 465;                                  
// 	    //Recipients
// 	    $mail->setFrom($email, $name);   
// 	    // your Email
// 	    $mail->addAddress('yazeededrees11@gmail.com'); 

// 	    //Content
// 	    $mail->isHTML(true);                             
// 	    $mail->Subject = $service;
// 	    $mail->Body    = "
// 	           <h3>Contact Form</h3>
// 			   <p><strong>Name</strong>: $name</p>
// 			   <p><strong>Email</strong>: $email</p>
// 			   <p><strong>Phone</strong>: $phone</p> 
// 			   <p><strong>Service</strong>: $service</p>
// 			   <p><strong>Message</strong>: $message</p>
// 	                     ";
// 	    $mail->send();
// 	    $sm= 'Message has been sent';
//     	header("Location: index.php?success=$sm");
// 	} catch (Exception $e) {
// 	    $em = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//     	header("Location: index.php?error=$em");
// 	}




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (
    isset($_POST['name']) &&
    isset($_POST['email']) &&
    isset($_POST['phone']) &&
    isset($_POST['service']) &&
    isset($_POST['message'])
) {
    // Sanitize inputs
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone   = htmlspecialchars(trim($_POST['phone']));
    $service = htmlspecialchars(trim($_POST['service']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $em = "Invalid email format";
        header("Location: index.html?error=" . urlencode($em));
        exit();
    }

    // Check required fields
    if (empty($name) || empty($service) || empty($message)) {
        $em = "Fill out all required fields";
        header("Location: index.html?error=" . urlencode($em));
        exit();
    }

    // Create PHPMailer instance for admin notification
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'yazeededrees11@gmail.com';  // Your Gmail
        $mail->Password   = 'apsdbmzgmwtkjkog';          // App Password (no spaces!)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL encryption
        $mail->Port       = 465;

        // Sender & recipient
        $mail->setFrom('yazeededrees11@gmail.com', 'Website Contact Form');
        $mail->addReplyTo($email, $name);
        $mail->addAddress('yazeededrees11@gmail.com'); // Your email

        // Email content to YOU
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission";
        $mail->Body = "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Service:</strong> {$service}</p>
            <p><strong>Message:</strong><br> {$message}</p>
        ";

        // Send main email
        $mail->send();

        /*
        ================================================
        SECOND EMAIL → Send confirmation to the user
        ================================================
        */
        $confirmMail = new PHPMailer(true);
        $confirmMail->isSMTP();
        $confirmMail->Host       = 'smtp.gmail.com';
        $confirmMail->SMTPAuth   = true;
        $confirmMail->Username   = 'yazeededrees11@gmail.com';
        $confirmMail->Password   = 'apsdbmzgmwtkjkog';
        $confirmMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $confirmMail->Port       = 465;

        // Set confirmation email sender and recipient
        $confirmMail->setFrom('yazeededrees11@gmail.com', 'Yazeed Edrees - Support');
        $confirmMail->addAddress($email, $name);

        // Email content for the user
        $confirmMail->isHTML(true);
        $confirmMail->Subject = "Thank you for contacting us, $name!";
        $confirmMail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333; padding: 15px; border: 1px solid #eee; border-radius: 10px;'>
                <h2 style='color: #007BFF;'>Hi $name,</h2>
                <p>Thank you for reaching out to us. We have received your message and will get back to you shortly.</p>
                <p><strong>Here's what you submitted:</strong></p>
                <ul>
                    <li><strong>Name:</strong> $name</li>
                    <li><strong>Email:</strong> $email</li>
                    <li><strong>Phone:</strong> $phone</li>
                    <li><strong>Service:</strong> $service</li>
                    <li><strong>Message:</strong> $message</li>
                </ul>
                <p style='margin-top: 15px;'>Best regards,<br><strong>Yazeed Edrees Team</strong></p>
                <hr>
                <p style='font-size: 12px; color: #777;'>This is an automated message. Please do not reply directly to this email.</p>
            </div>
        ";

        // Send confirmation email
        $confirmMail->send();

        // Redirect on success
        $sm = "Message has been sent successfully!";
        header("Location: index.html?success=" . urlencode($sm));
        exit();

    } catch (Exception $e) {
        $em = "Message could not be sent. Error: {$mail->ErrorInfo}";
        header("Location: index.html?error=" . urlencode($em));
        exit();
    }
} else {
    $em = "Invalid form submission";
    header("Location: index.html?error=" . urlencode($em));
    exit();
}
?>

