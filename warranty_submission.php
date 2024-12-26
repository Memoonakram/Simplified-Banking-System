<?php
// Database connection
session_start();
include('db.php');

if($_SESSION['id']){
  $user_id = $_SESSION['id'];
}
else{
  header('location:index.php');
}

if($_SESSION['warranty_pg1_id'])
    $warranty_pg1_id = $_SESSION['warranty_pg1_id'];
else
    header("Location: warranty.php");
$user_id = 2;

$beneficiary_type = $street = $additional_address = $postal_code = $city = $country = $email = "";
$mobile_number = $relationship = $contract_details = $contact_person = $bank_name = $bank_street = "";
$bank_additional_address = $account_number = $iban = $swift = "";
$due_diligence = 0; // default unchecked

// Fetch existing data if it exists (e.g., based on user_id)
// Replace with dynamic user ID if applicable
$query = "SELECT * FROM warranty_pg2 WHERE warranty_pg1_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $warranty_pg1_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Data exists, fetch it
    $row = $result->fetch_assoc();
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
}

$seller_code = '';$buyer_code='';$contract_no = '';  $expiryDate = '';$amount = '';$currency = '';$is_edit = false;$message = "";

$query = "SELECT * FROM warranty_pg1 WHERE user_id = ? ORDER BY created_at desc";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the data and prefill the form
    $row = $result->fetch_assoc();
    $seller_code = $row['seller_code'];
    $buyer_code = $row['buyer_code'];
    $contract_no = $row['contract_no'];
    $expiryDate = $row['expiry_date'];
    $amount = $row['amount'];
    $currency = $row['currency'];
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE warranty_pg1 SET isSubmitted = TRUE WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $warranty_pg1_id);
    if($stmt->execute()){
        unset($_SESSION['warranty_pg1_id']);
        header("Location: warranty.php");
        echo "form submitted";
    }
    else
        echo "error";
    $stmt->close();
}

?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <title>Onboarding - Submission</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
        font-family: Arial, sans-serif;
        }
    </style>
    <script>
        function submitForm() {
        // Add your form submission logic here
        alert("Form submitted successfully!");
        // You may want to redirect to another page after submission
        }
    </script>
    </head>
    <body class="bg-white">

        <!-- Header Section -->
        <header class="bg-white w-full shadow-md">
            <div class="max-w-4xl mx-auto p-4 flex justify-between items-center">
            <img alt="Company Logo" class="w-12 h-12" src="https://storage.googleapis.com/a1aa/image/thD2nM46lPpVJldTVhh2rA8DLTw6p2Zsu8fFa6iJGapfetYnA.jpg" />
            <div class="flex items-center space-x-4 text-sm text-gray-700">
                <span class="flex items-center space-x-1">
                <i class="fas fa-envelope"></i>
                <!-- <span class="text-red-600">onboarding@suissebank.com</span> -->
                </span>
                <!-- <a class="text-red-600 hover:underline" href="#">Change Email/Password</a> -->
                <a class="text-red-600 flex items-center space-x-1" href="logout.php">
  <i class="fas fa-sign-out-alt"></i>
  <span>Sign Out</span>
</a>

            </div>
            </div>
            <nav class="bg-gray-800 text-white py-4 w-full">
            <div class="max-w-4xl mx-auto flex justify-between items-center">
                <div class="text-lg font-bold">
                TRADE FINANCE – Warranty Aval
                </div>
            </div>
            </nav>
            
            <div class="bg-red-600 text-white p-4 w-full">
            <div class="max-w-4xl mx-auto flex justify-between items-center">
                <div class="flex items-center">
                <i class="fas fa-user mr-2"></i>
                <a class="text-white font-bold hover:underline" href="#">Beneficiary</a>
                </div>
                <div class="flex items-center">
                <i class="fas fa-file-alt mr-2"></i>
                <a class="text-white hover:underline" href="#">Terms &amp; Conditions</a>
                </div>
                <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <a class="text-white hover:underline" href="#">Finalization</a>
                </div>
            </div>
            </div>

            <div class="max-w-4xl mx-auto my-4">
            <div class="text-red-600 font-bold mb-2">
                3/3
            </div>
            <div class="text-red-600 mb-4">
                This data is collected for the application “Warranty Aval”.
            </div>
            </div>
            <div class="max-w-4xl mx-auto">
      <h1 class="text-2xl font-bold mb-4">
        Summary
      </h1>
      <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
          <h2 class="text-lg font-bold">
            Terms of Trade Finance
          </h2>
          <div class="flex items-center text-red-600">
            <a href="warranty.php" class="flex items-center text-red-600">
                <span class="mr-1">Edit</span>
                <i class="fas fa-pencil-alt"></i>
            </a>
          </div>
        </div>
        <div class="ml-4">
          <p class="mb-1">
            <span class="font-semibold">Seller Code:</span><?php echo htmlspecialchars($seller_code); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Buyer Code:</span><?php echo htmlspecialchars($buyer_code); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Contract No:</span><?php echo htmlspecialchars($contract_no); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Expiry Date:</span><?php echo htmlspecialchars($expiryDate); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Amount:</span><?php echo htmlspecialchars($amount); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Currency:</span><?php echo htmlspecialchars($currency); ?>"
          </p>
        </div>
      </div>
      <div>
        <div class="flex items-center justify-between mb-2">
          <h2 class="text-lg font-bold">Beneficiary</h2>
          <div class="flex items-center text-red-600">
            <a href="warrantypg2.php" class="flex items-center text-red-600">
                <span class="mr-1">Edit</span>
                <i class="fas fa-pencil-alt"></i>
            </a>
          </div>
        </div>
        <div class="ml-4">
          <p class="mb-1">
            <span class="font-semibold">Beneficiary Type:</span><?php echo htmlspecialchars($beneficiary_type); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">E-mail:</span><?php echo htmlspecialchars($email); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Mobile Number:</span><?php echo htmlspecialchars($mobile_number); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Relationship of Beneficiary to Applicant:</span><?php echo htmlspecialchars($relationship); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Details of contract incl. contract number in respect of which the BG is requested:</span><?php echo htmlspecialchars($contract_details); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Contact Person [name]:</span><?php echo htmlspecialchars($contact_person); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Street / Building No.:</span><?php echo htmlspecialchars($street); ?>"
          </p>
          <p class="mb-1">
            <span class="font-semibold">Additional Address:</span> <?php echo htmlspecialchars($additional_address); ?>"
          </p>
        </div>
        <div class="max-w-4xl mx-auto p-8">
          <div class="mb-8">
            <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($postal_code); ?>"</p>
            <p><strong>City:</strong> <?php echo htmlspecialchars($city); ?>"</p>
            <p><strong>Country:</strong> <?php echo htmlspecialchars($country); ?>"</p>
            <p><strong>Title:</strong> Ms.</p>
            <p><strong>First Name:</strong> HAFIZ MUHAMMAD HUSSAIN</p>
            <p><strong>Last Name:</strong> ZAKA</p>
            <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($bank_name); ?>"</p>
            <p><strong>IBAN:</strong> <?php echo htmlspecialchars($iban); ?>"</p>
            <p><strong>SWIFT:</strong> <?php echo htmlspecialchars($swift); ?>"</p>
            <p><strong>Street / Building No.:</strong> <?php echo htmlspecialchars($street); ?>"</p>
            <p><strong>Postal Code:</strong> <?php echo htmlspecialchars($postal_code); ?>"</p>
            <p><strong>City:</strong> <?php echo htmlspecialchars($city); ?>"</p>
            <p><strong>Country:</strong> <?php echo htmlspecialchars($country); ?>"</p>
          </div>
          <div class="flex items-start mb-8">
            <input class="mr-2 mt-1" type="checkbox"/>
            <p>I declare that I have reviewed the information contained in this form (and that I have made the information accurate and complete to the best of my knowledge and belief). I undertake to notify the Bank without delay of any change in circumstances which results in the information contained in this form no longer being correct and to provide the Bank with new self-assessment within 30 days of the change in circumstances.</p>
          </div>
          <div class="flex justify-between">
            <button class="bg-gray-200 text-black py-2 px-4 rounded" onclick="window.history.back();">Back</button>
            <form action="" method="POST">
                <button id="continue-button" class="bg-red-500 text-white py-2 px-4 rounded" type="submit">Submit Form</button>
            </form>
          </div>
        </div>
        <div class="fixed bottom-4 right-4">
          <img alt="Logo of a stylized horse head in a circular gold background" height="100" src="https://storage.googleapis.com/a1aa/image/TIW3wDbWfd1CGqe2EIibS7bNwuGrF9z3KefQ6tF8I7OVF93OB.jpg" width="100"/>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
