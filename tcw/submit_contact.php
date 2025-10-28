<?php
// Include database configuration
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Insert into database
    $sql = "INSERT INTO contact_submissions (name, email, subject, message, submission_date) 
            VALUES ('$name', '$email', '$subject', '$message', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        // Send email notification
        $email_subject = $subject_prefix . "Contact Form: " . $subject;
        $email_message = "You have received a new contact form submission:\n\n";
        $email_message .= "Name: " . $name . "\n";
        $email_message .= "Email: " . $email . "\n";
        $email_message .= "Subject: " . $subject . "\n";
        $email_message .= "Message: " . $message . "\n";
        
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        
        if (mail($to_email, $email_subject, $email_message, $headers)) {
            echo "<script>alert('Thank you for your message! We will get back to you soon.'); window.location.href = 'index.html#contact';</script>";
        } else {
            echo "<script>alert('Message saved but email notification failed. We will still get back to you soon.'); window.location.href = 'index.html#contact';</script>";
        }
    } else {
        echo "<script>alert('Sorry, there was an error submitting your message. Please try again.'); window.location.href = 'index.html#contact';</script>";
    }
    
    $conn->close();
}
?>