<?php
include 'db.php';
// Database connection details
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a file has been submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    // Get file details
    $fileName = $_FILES['file']['name'];
    $fileType = $_FILES['file']['type'];
    $fileData = file_get_contents($_FILES['file']['tmp_name']);

    // Prepare SQL statement to insert the file
    $stmt = $conn->prepare("INSERT INTO uploaded_files (file_name, file_type, file_data) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fileName, $fileType, $fileData);

    // Execute the query and check if successful
    if ($stmt->execute()) {
        echo "File successfully uploaded!<br>";

        // Display the uploaded file (assuming it's an image or PDF)
        if (strpos($fileType, "image") !== false) {
            echo '<img src="data:' . $fileType . ';base64,' . base64_encode($fileData) . '" width="300"/>';
        } elseif ($fileType == "application/pdf") {
            echo '<embed src="data:application/pdf;base64,' . base64_encode($fileData) . '" width="600" height="400"/>';
        }
    } else {
        echo "Error uploading file: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>
    <h2>Upload a File</h2>
    <form action="uploadTest.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload File</button>
    </form>
</body>
</html>