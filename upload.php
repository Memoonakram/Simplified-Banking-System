<?php
session_start();
include('db.php');

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    header('location:index.php');
    exit();
}

$user_id = $_SESSION['id']; // Get user ID from session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['document_category'];

    // Ensure category is selected
    if ($category == "Select document category...") {
        die("Please select a valid document category.");
    }

    // Handle file upload
    if (!empty($_FILES['file_upload']['name'])) {
        $file = $_FILES['file_upload'];

        $filename = basename($file['name']);
        $fileTmpName = $file['tmp_name'];
        $fileType = $file['type'];

        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }

        // Move file to upload directory
        $targetFilePath = $uploadDir . $filename;

        if (move_uploaded_file($fileTmpName, $targetFilePath)) {
            // Insert file info into the database, linking it to the user
            $stmt = $conn->prepare("INSERT INTO uploaded_documents (user_id, category, filename, filepath) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $category, $filename, $targetFilePath);

            if ($stmt->execute()) {
                // Redirect to the page after successful upload
                header("Location: user_docs.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Please select a file to upload.";
    }
}

$conn->close();
?>
