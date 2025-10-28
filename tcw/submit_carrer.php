<?php
// Include database configuration
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);
    
    // Handle file upload
    $resume_path = "";
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $allowed_types = array('pdf', 'doc', 'docx');
        $file_name = $_FILES['resume']['name'];
        $file_tmp = $_FILES['resume']['tmp_name'];
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (in_array($file_type, $allowed_types)) {
            $new_file_name = uniqid() . '_' . $file_name;
            $upload_dir = "uploads/";
            
            // Create uploads directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $resume_path = $upload_dir . $new_file_name;
            move_uploaded_file($file_tmp, $resume_path);
        }
    }
    
    // Insert into database
    $sql = "INSERT INTO career_applications (full_name, email, phone, position, cover_letter, resume_path, application_date) 
            VALUES ('$full_name', '$email', '$phone', '$position', '$cover_letter', '$resume_path', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        // Send email notification
        $email_subject = $subject_prefix . "Career Application: " . $position;
        $email_message = "You have received a new career application:\n\n";
        $email_message .= "Full Name: " . $full_name . "\n";
        $email_message .= "Email: " . $email . "\n";
        $email_message .= "Phone: " . $phone . "\n";
        $email_message .= "Position: " . $position . "\n";
        $email_message .= "Cover Letter: " . $cover_letter . "\n";
        
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        
        if (mail($to_email, $email_subject, $email_message, $headers)) {
            echo "<script>alert('Thank you for your application! We will review it and get back to you soon.'); window.location.href = 'index.html#careers';</script>";
        } else {
            echo "<script>alert('Application saved but email notification failed. We will still review your application.'); window.location.href = 'index.html#careers';</script>";
        }
    } else {
        echo "<script>alert('Sorry, there was an error submitting your application. Please try again.'); window.location.href = 'index.html#careers';</script>";
    }
    
    $conn->close();
}
?>