<?php
// Database connection details
include('db.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a user ID has been provided via POST or GET
$userId = null;
if (isset($_POST['file_id'])) {
    $userId = $_POST['file_id'];
} elseif (isset($_GET['file_id'])) {
    $userId = $_GET['file_id'];
}

if ($userId !== null) {
    // Prepare SQL statement to retrieve the file using the user_id
    // $stmt = $conn->prepare("
    //     SELECT documents.document_content, documents.document_name, documents.document_type
    //     FROM documents
    //     JOIN bank_guarantee_pg2 ON documents.document_id = bank_guarantee_pg2.document_id
    //     JOIN bank_guarantee_pg1 ON bank_guarantee_pg2.bank_guarantee_pg1_id = bank_guarantee_pg1.id
    //     WHERE bank_guarantee_pg1.user_id = ?
    // ");
    $stmt = $conn->prepare("SELECT document_content,document_name,document_type from documents where user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($fileData, $fileName, $fileType);
    if ($stmt->num_rows > 0) {
        while ($stmt->fetch()) {
            // Display each file (assuming it's an image or PDF)
            echo "<h3>Document: $fileName</h3>";
            if (strpos($fileType, "image") !== false) {
                echo '<img src="data:' . $fileType . ';base64,' . base64_encode($fileData) . '" width="300"/>';
            } elseif ($fileType == "application/pdf") {
                echo '<embed src="data:application/pdf;base64,' . base64_encode($fileData) . '" width="600" height="400"/>';
            } else {
                echo "Unsupported file type.";
            }
            echo "<hr>"; // Add a horizontal line between documents for clarity
        }
    } else {
        echo "File not found for the given user.";
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
}
?>


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View File</title>
</head>
<body>
    <h2>View a File</h2>
    <form action="view_file.php" method="post">
        <label for="file_id">Enter File ID:</label>
        <input type="text" id="file_id" name="file_id" required>
        <input type="submit" value="View File">
    </form>
</body>
</html>