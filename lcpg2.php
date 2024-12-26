<?php
session_start();
// Database connection
include('db.php');

if($_SESSION['id']){
    $user_id = $_SESSION['id'];
  }
  else{
    header('location:index.php');
  }

if($_SESSION['lc_pg1_id'])
    $lc_pg1_id = $_SESSION['lc_pg1_id'];
else
    header("Location: lc.php");
$lc_pg2_id = 0;

$document_id = null;

$beneficiary_type = $street = $additional_address = $postal_code = $city = $country = $email = "";
$mobile_number = $relationship = $contract_details = $contact_person = $bank_name = $bank_street = "";
$bank_additional_address = $account_number = $iban = $swift = "";
$due_diligence = 0; // default unchecked

// Fetch existing data if it exists (e.g., based on user_id)
$query = "SELECT * FROM lc_pg2 WHERE lc_pg1_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $lc_pg1_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Data exists, fetch it
    $row = $result->fetch_assoc();
    $lc_pg2_id = $row['id'];
    $beneficiary_type = $row['beneficiary_type'];
    $street = $row['street'];
    $additional_address = $row['additional_address'];
    $postal_code = $row['postal_code'];
    $city = $row['city'];
    $country = $row['country'];
    $email = $row['email'];
    $mobile_number = $row['mobile_number'];
    $relationship = $row['relationship'];
    $contract_details = $row['contract_details'];
    $contact_person = $row['contact_person'];
    $bank_name = $row['bank_name'];
    $bank_street = $row['bank_street'];
    $bank_additional_address = $row['bank_additional_address'];
    $account_number = $row['account_number'];
    $iban = $row['iban'];
    $swift = $row['swift'];
    $due_diligence = $row['due_diligence'];
    $document_id = $row['document_id'];
}
else{
    $stmt = $conn->prepare("
        INSERT INTO lc_pg2 (
            beneficiary_type, street, additional_address, postal_code, city, country, email, 
            mobile_number, relationship, contract_details, contact_person, bank_name, bank_street, 
            bank_additional_address, account_number, iban, swift, due_diligence, document_id,lc_pg1_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param(
            "ssssssssssssssssssii",
            $beneficiary_type, $street, $additional_address, $postal_code, $city, $country, 
            $email, $mobile_number, $relationship, $contract_details, $contact_person, 
            $bank_name, $bank_street, $bank_additional_address, $account_number, $iban, 
            $swift, $due_diligence, $document_id,$lc_pg1_id
        );
        $stmt->execute();
        $lc_pg2_id = $stmt->insert_id;
}
$stmt->close();

// Handling form data and file upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data
    $beneficiary_type = $_POST['beneficiary'];
    $street = $_POST['street'];
    $additional_address = $_POST['additional-address'];
    $postal_code = $_POST['postal-code'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile-number'];
    $relationship = $_POST['relationship'];
    $contract_details = $_POST['contract-details'];
    $contact_person = $_POST['contact-person'];
    $bank_name = $_POST['bank-name'];
    $bank_street = $_POST['street-building'];
    $bank_additional_address = $_POST['bank-additional-address'];
    $account_number = $_POST['account-number'];
    $iban = $_POST['iban'];
    $swift = $_POST['swift'];
    $due_diligence = isset($_POST['due-diligence']) ? 1 : 0;

    // File upload logic (saving as BLOB)
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $fileName = $_FILES["document"]["name"];
        $fileType = $_FILES["document"]["type"];
        $fileContent = file_get_contents($_FILES["document"]["tmp_name"]);
        $user_id = $_SESSION['id'];

    $check_query = "SELECT 1 FROM documents WHERE document_id = ? AND document_name = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("isi", $document_id, $fileName, $user_id);

    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
    // Entry exists
        echo "An entry with the same document_id, document_name, and user_id already exists.";
    } else {
    // Entry does not exist; proceed with the INSERT
        $insert_query = "INSERT INTO documents (document_content, document_name, document_type, user_id) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("bssi", $fileContent, $fileName,$fileType,$user_id);
        $insert_stmt->send_long_data(0, $fileContent);

        if ($insert_stmt->execute()) {
            echo "New document inserted successfully.";
            $document_id = $insert_stmt->insert_id;
        } else {
            echo "Error: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    }
    }
        // Update existing record
    $stmt = $conn->prepare("
    UPDATE lc_pg2
    SET 
        beneficiary_type = ?, street = ?, additional_address = ?, postal_code = ?, city = ?, 
        country = ?, email = ?, mobile_number = ?, relationship = ?, contract_details = ?, 
        contact_person = ?, bank_name = ?, bank_street = ?, bank_additional_address = ?, 
        account_number = ?, iban = ?, swift = ?, due_diligence = ?, document_id = ?, 
        lc_pg1_id = ?
        WHERE id = ?
    ");
    $stmt->bind_param(
        "ssssssssssssssssssiii",
        $beneficiary_type, $street, $additional_address, $postal_code, $city, $country, 
        $email, $mobile_number, $relationship, $contract_details, $contact_person, 
        $bank_name, $bank_street, $bank_additional_address, $account_number, $iban, 
        $swift, $due_diligence, $document_id, $lc_pg1_id, $lc_pg2_id
    );
    
    if ($stmt->execute()) {
        echo "Form Submitted";
        header("Location: lc_submission.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Account Activation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
</head>
<body class="font-roboto bg-white text-gray-800">

    <!-- Header Section -->
    <div class="max-w-4xl mx-auto py-2">
        <div class="flex justify-between items-center mb-4">
        <img alt="" class="mb-4 logo-img" src="logo.jpg" width="100" height="100"/> 
            <div class="flex items-center space-x-4 text-sm text-gray-700">
                <span class="flex items-center space-x-1">
                    <i class="fas fa-envelope"></i>
                    <!-- <span class="text-red-600">onboarding@suissebank.com</span> -->
                </span>
                <!-- <a class="text-red-600" href="#">Change Email/Password</a> -->
                <a class="text-red-600 flex items-center space-x-1" href="logout.php">
  <i class="fas fa-sign-out-alt"></i>
  <span>Sign Out</span>
</a>

            </div>
        </div>
    </div>

    <!-- Navigation and Progress Indicator -->
    <nav class="bg-gray-800 text-white p-4">
        <h1 class="text-center text-lg font-bold">TRADE FINANCE – Standby Letter of Credit</h1>
    </nav>
    <div class="bg-red-600 text-white flex justify-around py-4">
        <div class="text-center">
            <i class="fas fa-user text-2xl"></i>
            <p>Beneficiary</p>
        </div>
        <div class="text-center">
            <i class="fas fa-file-alt text-2xl"></i>
            <p class="text-gray-300">Terms &amp; Conditions</p>
        </div>
        <div class="text-center">
            <i class="fas fa-info-circle text-2xl"></i>
            <p class="text-gray-300">Finalization</p>
        </div>
    </div>

    <!-- Main Content Section -->
    <main class="p-8 max-w-4xl mx-auto">
        <div class="text-red-600 text-sm mb-4">
            <span>2/3</span>
            <span>This data is collected for the application “Standby Letter of Credit”.</span>
        </div>
        <h2 class="text-2xl font-bold mb-6">BENEFICIARY</h2>
        <!-- Update form to allow file upload -->
        <form class="space-y-6" id="lcpg2form" action="" method="POST" enctype="multipart/form-data">
            <div class="flex items-center space-x-4">
                <label class="font-bold" for="beneficiary-type">The BENEFICIARY is a*</label>
                <div class="flex items-center space-x-2">
                    <input id="person" name="beneficiary" type="radio" value="person" required
                    <?php if ($beneficiary_type === 'person') echo 'checked'; ?>/>
                    <label for="person">Person</label>
                </div>
                <div class="flex items-center space-x-2">
                    <input id="company" name="beneficiary" type="radio" value="company" required
                    <?php if ($beneficiary_type === 'company') echo 'checked'; ?>/>
                    <label for="company">Company</label>
                </div>
            </div>
            <div>
                <label class="block font-bold" for="street">Street / Building No.*</label>
                <input class="w-full border-b border-gray-400 focus:outline-none" id="street" name="street" type="text" placeholder="Enter street or building number"
                value="<?php echo htmlspecialchars($street); ?>" required/>
            </div>
            <div>
                <label class="block font-bold" for="additional-address">Additional Address</label>
                <input class="w-full border-b border-gray-400 focus:outline-none" id="additional-address" name="additional-address" type="text" placeholder="Enter additional address"
                value="<?php echo htmlspecialchars($additional_address); ?>" />
            </div>
            <div>
                <label class="block font-bold" for="postal-code">Postal code*</label>
                <input class="w-full border-b border-gray-400 focus:outline-none" id="postal-code" name="postal-code" type="text" placeholder="Enter postal code" required
                value="<?php echo htmlspecialchars($postal_code); ?>"/>
            </div>
            <div>
                <label class="block font-bold" for="city">City*</label>
                <input class="w-full border-b border-gray-400 focus:outline-none" id="city" name="city" type="text" placeholder="Enter city" required
                value="<?php echo htmlspecialchars($city); ?>"/>
            </div>
            <div>
                <label class="block font-bold" for="country">Country*</label>
                <select class="w-full border-b border-gray-400 focus:outline-none" id="country" name="country" required value="<?php echo htmlspecialchars($country); ?>" >
                    <option value="">Please Choose</option>
                    <option value="USA">USA</option>
                    <option value="Canada">Canada</option>
                    <option value="UK">UK</option>
                </select>
            </div>
            <div>
                <label class="block font-bold" for="email">E-Mail*</label>
                <input class="w-full border-b border-gray-400 focus:outline-none" id="email" name="email" type="email" placeholder="Enter email address" required
                value="<?php echo htmlspecialchars($email); ?>"/>
            </div>
            <div>
                <label class="block font-bold" for="mobile-number">Mobile Number*</label>
                <input class="border-t border-b border-gray-300 px-3 py-2 w-16" name="mobile-number" placeholder="89" type="text" required
                value="<?php echo htmlspecialchars($mobile_number); ?>"/>
            </div>
            <div>
                <label class="block font-bold" for="relationship">Relationship of Beneficiary to Applicant*</label>
                <input class="border border-gray-300 rounded w-full px-3 py-2" name="relationship" type="text" required
                value="<?php echo htmlspecialchars($relationship); ?>"/>
            </div>
            <div>
                <label class="block font-bold" for="contract-details">Details of contract incl. contract number in respect of which the BG is requested*</label>
                <input class="border border-gray-300 rounded w-full px-3 py-2" name="contract-details" type="text" required
                value="<?php echo htmlspecialchars($contract_details); ?>"/>
            </div>
            <div>
                <label class="block font-bold" for="contact-person">Contact Person [name]*</label>
                <input class="border border-gray-300 rounded w-full px-3 py-2" name="contact-person" type="text" required
                value="<?php echo htmlspecialchars($contact_person); ?>"/>
            </div>
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">PLEASE UPLOAD FOLLOWING DOCUMENTS:</h2>
                <p class="mb-4">1. Please provide a copy of the contract for which the Standby Letter of Credit is being requested.</p>
                <!-- File upload field for document -->
                <input class="bg-red-500 text-white font-bold py-2 px-4 rounded" type="file" name="document" required/>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">BENEFICIARY BANK</h2>
                <div>
                    <label class="block font-bold" for="bank-name">Name of the Bank*</label>
                    <input class="border border-gray-300 rounded w-full px-3 py-2" name="bank-name" type="text" required
                    value="<?php echo htmlspecialchars($bank_name); ?>"/>
                </div>
                <div>
                    <label class="block font-bold" for="street-building">Street / Building No.*</label>
                    <input class="border border-gray-300 rounded w-full px-3 py-2" name="street-building" type="text" required
                    value="<?php echo htmlspecialchars($bank_street); ?>"/>
                </div>
                <div>
                    <label class="block font-bold" for="bank-additional-address">Additional Address</label>
                    <input class="border border-gray-300 rounded w-full px-3 py-2" name="bank-additional-address" type="text"
                    value="<?php echo htmlspecialchars($bank_additional_address); ?>"/>
                </div>
                <div>
                    <label class="block font-bold" for="account-number">Account Number*</label>
                    <input class="border border-gray-300 rounded w-full px-3 py-2" name="account-number" type="text" required
                    value="<?php echo htmlspecialchars($account_number); ?>"/>
                </div>
                <div>
                    <label class="block font-bold" for="iban">IBAN*</label>
                    <input class="border border-gray-300 rounded w-full px-3 py-2" name="iban" type="text" required
                    value="<?php echo htmlspecialchars($iban); ?>"/>
                </div>
                <div>
                    <label class="block font-bold" for="swift">SWIFT/BIC*</label>
                    <input class="border border-gray-300 rounded w-full px-3 py-2" name="swift" type="text" required
                    value="<?php echo htmlspecialchars($swift); ?>"/>
                </div>
            </div>
            <div class="flex items-center mt-4">
                <input class="mr-2" name="due-diligence" type="checkbox"/>
                <label>I have read and understood the due diligence requirements.</label>
            </div>
            <div class="flex justify-end mt-8">
                <button class="bg-red-500 text-white font-bold py-2 px-8 rounded" type="submit">Submit</button>
            </div>
        </form>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <p class="text-sm">© 2024 Suisse Bank | Official website. All rights reserved.</p>
            <div class="flex space-x-4">
                <a class="text-sm hover:underline" href="#">Disclaimer</a>
                <a class="text-sm hover:underline" href="#">Privacy Policy</a>
            </div>
            <div>
            <img alt="" class="mb-4 logo-img" src="logo.jpg" width="100" height="100"/> 
            </div>
        </div>
    </footer>
    <script>
  // Function to auto-submit the form on change event
  function setupChangeAutoSubmit(formId) {
  const form = document.getElementById(formId);
  if (!form) {
    console.error(`Form with ID "${formId}" not found.`);
    return;
  }

  // Add change event listeners to all form fields
  form.querySelectorAll("input, select, textarea").forEach(field => {
    field.addEventListener("change", () => {
      // Prevent form submission to avoid page reload
      event.preventDefault();

      // Collect form data
      const formData = new FormData(form);

      // Send form data using fetch
      fetch(form.action, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json()) // Assume your PHP returns a JSON response
      .then(data => {
        console.log("Data saved:", data); // Handle the server's response
      })
      .catch(error => {
        console.error("Error:", error); // Handle any errors that occur
      });
    });
  });
}

// Example usage:
// Call the function when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
  setupChangeAutoSubmit("lcpg2form"); // Replace "bgpg1form" with the ID of your form
});


</script>
</body>
</html>
