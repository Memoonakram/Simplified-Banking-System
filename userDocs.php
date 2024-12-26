<?php
session_start();
include('db.php');



if($_SESSION['id']){
    $user_id = $_SESSION['id'];
}
else{
    header('location:index.php');
  }

$user_id = $_SESSION['id']; // Get user ID from session
$BGDocumentId = 0;


// Handle document deletion
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete_doc_id'])) {
    $delete_doc_id = $_GET['delete_doc_id'];
    // $table_pg1 = $_GET['delete_doc_table']."_pg1";
    // $table_pg2 = $_GET['delete_doc_table']."_pg2";

    // Delete the document from the database
    $stmt = $conn->prepare("UPDATE documents 
          SET document_type = NULL, document_content = NULL, document_name = NULL
          WHERE document_id = ?");
    $stmt->bind_param("i", $delete_doc_id);
    
    if ($stmt->execute()) {
        echo "Document deleted successfully.";

        // $stmt = $conn->prepare("UPDATE $table_pg1 bg1
        // JOIN $table_pg2 bg2 ON bg1.id = bg2.bank_guarantee_pg1_id
        // SET bg1.isSubmitted = 0
        // WHERE bg2.document_id = ?
        // ");
        // $stmt->bind_param("i", $delete_doc_id);
        // if($stmt->execute()){
            header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page to refresh
        // }
        // else
        //     echo "error";
    } else {
        echo "Error deleting document.";
    }
}

// Handle document update (edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_doc_id'])) {
    $doc_id = $_POST['update_doc_id'];
    $file = $_FILES['document'];

    // Validate file upload
    if ($file['error'] === UPLOAD_ERR_OK) {
        $docData = file_get_contents($file['tmp_name']);
        $docType = $file['type'];
        $docName = $file['name'];

        // Update the document in the database
        $stmt = $conn->prepare("UPDATE documents SET document_name = ?, document_type = ?, document_content = ? WHERE document_id = ?");
        $stmt->bind_param("sssi", $docName, $docType, $docData, $doc_id);
        if ($stmt->execute()) {
            echo "Document updated successfully.";
        } else {
            echo "Error updating document.";
        }
    } else {
        echo "Error uploading the document.";
    }
}

// Retrieve the document details
$stmt = $conn->prepare("SELECT 
    d.document_id, d.document_content, d.document_name, d.document_type FROM documents d JOIN 
    bank_guarantee_pg2 bg2 ON d.document_id = bg2.document_id JOIN bank_guarantee_pg1 bg1 ON bg2.bank_guarantee_pg1_id = bg1.id 
    WHERE bg1.user_id = ? AND bg1.isSubmitted = 1 ORDER BY bg1.created_at DESC LIMIT 1;");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($BGDocumentId, $BGDocumentData, $BGDocumentName, $BGDocumentType);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
} 
// else {
//     echo "No doc";
// }

$stmt = $conn->prepare("SELECT 
    d.document_id, d.document_content, d.document_name, d.document_type FROM documents d JOIN 
    pof_pg2 pof2 ON d.document_id = pof2.document_id JOIN pof_pg1 pof1 ON pof2.pof_pg1_id = pof1.id 
    WHERE pof1.user_id = ? AND pof1.isSubmitted = 1 ORDER BY pof1.created_at DESC LIMIT 1;");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($POFDocumentId, $POFDocumentData, $POFDocumentName, $POFDocumentType);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
} 
// else {
//     echo "No doc";
// }

$stmt = $conn->prepare("SELECT 
    d.document_id, d.document_content, d.document_name, d.document_type FROM documents d JOIN 
    sblc_pg2 sblc2 ON d.document_id = sblc2.document_id JOIN sblc_pg1 sblc1 ON sblc2.sblc_pg1_id = sblc1.id 
    WHERE sblc1.user_id = ? AND sblc1.isSubmitted = 1 ORDER BY sblc1.created_at DESC LIMIT 1;");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($sblcDocumentId, $sblcDocumentData, $sblcDocumentName, $sblcDocumentType);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
} 
// else {
//     echo "No doc";
// }

$stmt = $conn->prepare("SELECT 
    d.document_id, d.document_content, d.document_name, d.document_type FROM documents d JOIN 
    lc_pg2 lc2 ON d.document_id = lc2.document_id JOIN lc_pg1 lc1 ON lc2.lc_pg1_id = lc1.id 
    WHERE lc1.user_id = ? AND lc1.isSubmitted = 1 ORDER BY lc1.created_at DESC LIMIT 1;");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($lcDocumentId, $lcDocumentData, $lcDocumentName, $lcDocumentType);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
} 
// else {
//     echo "No doc";
// }

$stmt = $conn->prepare("SELECT 
    d.document_id, d.document_content, d.document_name, d.document_type FROM documents d JOIN 
    warranty_pg2 warranty2 ON d.document_id = warranty2.document_id JOIN warranty_pg1 warranty1 ON warranty2.warranty_pg1_id = warranty1.id 
    WHERE warranty1.user_id = ? AND warranty1.isSubmitted = 1 ORDER BY warranty1.created_at DESC LIMIT 1;");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($warrantyDocumentId, $warrantyDocumentData, $warrantyDocumentName, $warrantyDocumentType);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
} 
// else {
//     echo "No doc";
// }



?>

<!-- Edit Document Modal -->
<?php if ($BGDocumentId > 0) { ?>
    <!-- <h1>Bank Guarantee Form Document</h1> -->
    <?php
    echo "<h3>Document: $BGDocumentName</h3>";

    // Display the document based on type
    if (strpos($BGDocumentType, "image") !== false) {
        echo '<img src="data:' . $BGDocumentType . ';base64,' . base64_encode($BGDocumentData) . '" width="300"/>';
    } elseif ($BGDocumentType == "application/pdf") {
        echo '<embed src="data:application/pdf;base64,' . base64_encode($BGDocumentData) . '" width="600" height="400"/>';
    } else {
        echo "No Document";
    }
    echo "<br><a href='#edit_modal' class='btn btn-warning' onclick='openEditModal($BGDocumentId)'>Edit</a>";
    echo "<a href='?delete_doc_id=$BGDocumentId' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this document?\");'>Delete</a>";

    echo "<hr>";


    // Fetch files from the database
$sql = "SELECT * FROM uploaded_documents ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<div id="uploaded-documents" style="margin-top: 30px;">
    <h3>Uploaded Documents:</h3>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo htmlspecialchars($row['category']) . " - " . htmlspecialchars($row['filename']); ?>
                <a href="<?php echo $row['filepath']; ?>" target="_blank">View</a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
    
<?php } ?>



<?php if ($POFDocumentId > 0) { ?>
    <h1>Proof of Funds Form Document</h1>
    <?php
    echo "<h3>Document: $POFDocumentName</h3>";

    // Display the document based on type
    if (strpos($POFDocumentType, "image") !== false) {
        echo '<img src="data:' . $POFDocumentType . ';base64,' . base64_encode($POFDocumentData) . '" width="300"/>';
    } elseif ($POFDocumentType == "application/pdf") {
        echo '<embed src="data:application/pdf;base64,' . base64_encode($POFDocumentData) . '" width="600" height="400"/>';
    } else {
        echo "Unsupported file type.";
    }
    echo "<br><a href='#edit_modal' class='btn btn-warning' onclick='openEditModal($POFDocumentId)'>Edit</a>";
    echo "<a href='?delete_doc_id=$POFDocumentId' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this document?\");'>Delete</a>";

    echo "<hr>";
    ?>

    
<?php } ?>

<?php if ($sblcDocumentId > 0) { ?>
    <h1>Standby Letter of Credit Form Document</h1>
    <?php
    echo "<h3>Document: $sblcDocumentName</h3>";

    // Display the document based on type
    if (strpos($sblcDocumentType, "image") !== false) {
        echo '<img src="data:' . $sblcDocumentType . ';base64,' . base64_encode($sblcDocumentData) . '" width="300"/>';
    } elseif ($sblcDocumentType == "application/pdf") {
        echo '<embed src="data:application/pdf;base64,' . base64_encode($sblcDocumentData) . '" width="600" height="400"/>';
    } else {
        echo "Unsupported file type.";
    }
    echo "<br><a href='#edit_modal' class='btn btn-warning' onclick='openEditModal($sblcDocumentId)'>Edit</a>";
    echo "<a href='?delete_doc_id=$sblcDocumentId' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this document?\");'>Delete</a>";

    echo "<hr>";
    ?>
<?php } ?>

<?php if ($lcDocumentId > 0) { ?>
    <h1>Standby Letter of Credit Form Document</h1>
    <?php
    echo "<h3>Document: $lcDocumentName</h3>";

    // Display the document based on type
    if (strpos($lcDocumentType, "image") !== false) {
        echo '<img src="data:' . $lcDocumentType . ';base64,' . base64_encode($lcDocumentData) . '" width="300"/>';
    } elseif ($lcDocumentType == "application/pdf") {
        echo '<embed src="data:application/pdf;base64,' . base64_encode($lcDocumentData) . '" width="600" height="400"/>';
    } else {
        echo "Unsupported file type.";
    }
    echo "<br><a href='#edit_modal' class='btn btn-warning' onclick='openEditModal($lcDocumentId)'>Edit</a>";
    echo "<a href='?delete_doc_id=$lcDocumentId' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this document?\");'>Delete</a>";

    echo "<hr>";
    ?>
<?php } ?>

<?php if ($warrantyDocumentId > 0) { ?>
    <h1>Standby Letter of Credit Form Document</h1>
    <?php
    echo "<h3>Document: $warrantyDocumentName</h3>";

    // Display the document based on type
    if (strpos($warrantyDocumentType, "image") !== false) {
        echo '<img src="data:' . $warrantyDocumentType . ';base64,' . base64_encode($warrantyDocumentData) . '" width="300"/>';
    } elseif ($warrantyDocumentType == "application/pdf") {
        echo '<embed src="data:application/pdf;base64,' . base64_encode($warrantyDocumentData) . '" width="600" height="400"/>';
    } else {
        echo "Unsupported file type.";
    }
    echo "<br><a href='#edit_modal' class='btn btn-warning' onclick='openEditModal($warrantyDocumentId)'>Edit</a>";
    echo "<a href='?delete_doc_id=$warrantyDocumentId' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this document?\");'>Delete</a>";

    echo "<hr>";
    ?>
<?php } ?>

    <div id="edit_modal" style="display:none;">
        <h3>Edit Document:</h3>
        <form action="userDocs.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="update_doc_id" id="docId">
            <label for="document">Upload New Document:</label>
            <input type="file" name="document" required><br><br>
            <input type="submit" name="submit" value="Update Document">
        </form>
    </div>

    <script>
        function openEditModal(id) {
            document.getElementById('edit_modal').style.display = 'block';
            document.getElementById('docId').value = id;
        }
    </script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mailbox of ALLIANCE DIGITAL CORPORATE BANQUE LTD</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .logo {
            width: 50px;
            height: 50px;
            background: #1a365d;
            border-radius: 50%;
        }
        .user-controls {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .user-controls a {
            color: #666;
            text-decoration: none;
        }
        .user-controls a:hover {
            text-decoration: underline;
        }
        .session-timer {
            color: #dc2626; /* Red color */
        }
        .main-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .breadcrumb {
            margin-bottom: 20px;
        }
        .breadcrumb a {
            color: #2563eb;
            text-decoration: none;
        }
        h1 {
            color: #1a365d;
            margin-bottom: 30px;
        }
        .document-section {
            margin-bottom: 30px;
        }
        .download-all {
            background: #dc2626; /* Red color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 20px 0;
            display: inline-block;
            text-decoration: none;
        }
        .upload-section {
            margin-top: 40px;
        }
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .upload-btn {
            background: #dc2626; /* Red color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        .flex {
            display: flex;
            justify-content: space-between; /* Ensures the logo is on the left, and the buttons are on the right */
            align-items: center; /* Aligns items vertically */
        }
    </style>
</head>

<body class="bg-white-100">
    <div class="max-w-4xl mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
        <img alt="" class="mb-4 logo-img" src="logo.png" width="100" height="100"/> 
            <div class="flex items-center space-x-4 text-sm text-gray-700">
                <span class="flex items-center space-x-1">
                    <i class="fas fa-envelope"></i>
                </span>
                <a class="text-red-600 flex items-center space-x-1" href="logout.php">
  <i class="fas fa-sign-out-alt"></i>
  <span>Sign Out</span>
</a>

            </div>
        </div>

        <a href="user_dashboard.php" class="text-blue-600">Home</a> / Documents

        <h1>Mailbox of ALLIANCE DIGITAL CORPORATE BANQUE LTD</h1>

        <div class="document-section">
            <p>Click on the document you want to download:</p>
            <a href="#" class="download-all">Download all documents</a>
        </div>

        <!-- <div class="upload-section">
    <p>Please select the process for document upload:</p>
    <select id="document-category">
        <option>Select document category...</option>
        <option>Compliance Application (CA) #SBCA/221221/5</option>
        <option>All-In-One Wallet (AIOW) #SCAO/241012/1</option>
        <option>All-In-One Wallet (AIOW) #SCAO/241012/2</option>
    </select>
    <form id="upload-form" enctype="multipart/form-data">
        <label for="file-upload">Choose a file to upload:</label>
        <input type="file" id="file-upload" name="file-upload" multiple required>
    </form>
    <button class="upload-btn" id="upload-button">Upload documents</button>
</div> -->

<!-- Section to display uploaded documents below the upload section -->
<div class="upload-section">
    <p>Please select the process for document upload:</p>
    <select id="document-category">
        <option>Select document category...</option>
        <option>Compliance Application (CA) #SBCA/221221/5</option>
        <option>All-In-One Wallet (AIOW) #SCAO/241012/1</option>
        <option>All-In-One Wallet (AIOW) #SCAO/241012/2</option>
    </select>
    <form id="upload-form" enctype="multipart/form-data">
        <label for="file-upload">Choose a file to upload:</label>
        <input type="file" id="file-upload" name="file-upload" multiple required>
    </form>
    <button class="upload-btn" id="upload-button">Upload documents</button>
</div>

<!-- Section to display uploaded documents below the upload section -->
<div id="uploaded-documents" style="margin-top: 30px;">
    <h3>Uploaded Documents:</h3>
    <ul id="documents-list"></ul>
    <div id="documents-preview" style="margin-top: 20px; display: flex; flex-wrap: wrap; gap: 20px;"></div> <!-- This will show the previews -->
</div>

<script>
    document.getElementById('upload-button').addEventListener('click', function() {
        const category = document.getElementById('document-category').value;
        const fileInput = document.getElementById('file-upload');
        const documentList = document.getElementById('documents-list');
        const previewContainer = document.getElementById('documents-preview');

        if (category === "Select document category...") {
            alert("Please select a document category.");
            return;
        }

        if (fileInput.files.length === 0) {
            alert("Please choose a file to upload.");
            return;
        }

        // Loop through the selected files and display them
        for (let i = 0; i < fileInput.files.length; i++) {
            const file = fileInput.files[i];
            const listItem = document.createElement('li');
            listItem.textContent = `${category} - ${file.name}`;

            // Append the list item to the uploaded documents list
            documentList.appendChild(listItem);

            // Create a preview for the uploaded file (image, PDF, or text)
            const reader = new FileReader();

            reader.onload = function(event) {
                const fileContent = event.target.result;
                let previewElement;

                if (file.type.startsWith('image/')) {
                    // If it's an image, display it
                    previewElement = document.createElement('img');
                    previewElement.src = fileContent;
                    previewElement.style.maxWidth = '600px'; // Set larger image size horizontally
                    previewElement.style.marginTop = '10px';
                } else if (file.type === 'application/pdf') {
                    // If it's a PDF, show a PDF preview (needs a PDF viewer library like PDF.js for full functionality)
                    previewElement = document.createElement('embed');
                    previewElement.src = fileContent;
                    previewElement.type = 'application/pdf';
                    previewElement.style.width = '600px'; // Set larger width for PDF preview
                    previewElement.style.height = '400px';
                } else if (file.type.startsWith('text/')) {
                    // If it's a text file, display its content
                    previewElement = document.createElement('pre');
                    previewElement.textContent = fileContent.substring(0, 500); // Display first 500 characters
                    previewElement.style.whiteSpace = 'pre-wrap';
                    previewElement.style.wordBreak = 'break-word';
                } else {
                    // For unsupported files, just show a message
                    previewElement = document.createElement('p');
                    previewElement.textContent = `Cannot preview this file type: ${file.name}`;
                }

                previewContainer.appendChild(previewElement);
            };

            // Read the file content to trigger the preview
            reader.readAsDataURL(file);
        }

        // Clear the file input after displaying files
        fileInput.value = '';
    });
</script>

    </div>
</body>
</html>
