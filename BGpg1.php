<?php
session_start();
include 'db.php';

if($_SESSION['id']){
  $user_id = $_SESSION['id'];
}
else{
  header('location:index.php');
}

// Initialize variables
$transferable = '';
$expiryDate = '';
$amount = '';
$currency = '';
$is_edit = false;
$message = "";

// Check if there's already an entry for the logged-in user
$query = "SELECT * FROM bank_guarantee_pg1 WHERE user_id = ? AND isSubmitted = FALSE ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the data and prefill the form
  $row = $result->fetch_assoc();
    
  $bank_guarantee_pg_id = $row['id'];
  $transferable = $row['transferable'];
  $expiryDate = $row['expiry_date'];
  $amount = $row['amount'];
  $currency = $row['currency'];
  // $is_edit = true;  
}
else{
  $insert_query = "INSERT INTO bank_guarantee_pg1 (user_id, transferable, expiry_date, amount, currency) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($insert_query);
  $stmt->bind_param("issds", $user_id, $transferable, $expiryDate, $amount, $currency);
  $stmt->execute();
  $bank_guarantee_pg_id = $stmt->insert_id;
  $_SESSION['bank_guarantee_pg1'] = $bank_guarantee_pg_id;
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $transferable = $_POST['transferable'];
    $expiryDate = $_POST['expiryDate'];
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];

    
        // Update existing record
        $update_query = "UPDATE bank_guarantee_pg1 SET transferable = ?, expiry_date = ?, amount = ?, currency = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssdsi", $transferable, $expiryDate, $amount, $currency, $bank_guarantee_pg_id);
     
    // else {
    //     // Insert new record
    //     $insert_query = "INSERT INTO bank_guarantee_pg1 (user_id, transferable, expiry_date, amount, currency) VALUES (?, ?, ?, ?, ?)";
    //     $stmt = $conn->prepare($insert_query);
    //     $stmt->bind_param("issds", $user_id, $transferable, $expiryDate, $amount, $currency);
    // }

    // Execute the statement and show a message
    if ($stmt->execute()) {
        // Redirect to BGpg2.php after successful form submission
        
        // $bank_guarantee_pg_id = $conn->insert_id;
        
        $_SESSION['bank_guarantee_pg1'] = $bank_guarantee_pg_id;
        header("Location: BGpg2.php");
        exit(); // Make sure no further code is executed after the redirect
    } else {
        $message = "<div class='text-red-600 font-bold mb-4'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Onboarding - Bank Guarantee Form</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-white font-sans">

<!-- Header Section -->
<header class="bg-white w-full shadow-md">
  <div class="max-w-4xl mx-auto p-4 flex justify-between items-center">
  <img alt="" class="mb-4 logo-img" src="logo.jpg" width="100" height="100"/> 
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
</header>

<!-- Navigation Section -->
<nav class="bg-red-800 text-white py-4 w-full">
  <div class="max-w-4xl mx-auto flex justify-between items-center">
    <div class="text-lg font-bold">
      TRADE FINANCE – BANK GUARANTEE
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

<!-- Main Content Section -->
<main class="max-w-4xl mx-auto p-4 mt-8">
  <?php if (isset($message)) echo $message; ?>

  <div class="text-red-600 font-bold mb-2">1/3</div>
  <div class="text-red-600 mb-4">This data is collected for the application “Bank Guarantee”.</div>
  <div class="text-2xl font-bold mb-4 text-red-600">TERMS OF BANK GUARANTEE (“BG”)</div>

  <form method="post" id="bgpg1form">
    <!-- Transferable Field -->
    <div class="mb-4">
      <label class="block text-gray-700 font-bold mb-2">Transferable</label>
      <div class="flex items-center">
        <input class="mr-2" id="transferableYes" name="transferable" type="radio" value="Yes" required 
          <?php if ($transferable === 'Yes') echo 'checked'; ?> />
        <label class="mr-4" for="transferableYes">Yes</label>
        <input class="mr-2" id="transferableNo" name="transferable" type="radio" value="No" required 
          <?php if ($transferable === 'No') echo 'checked'; ?> />
        <label for="transferableNo">No</label>
      </div>
    </div>
    
    <!-- Expiry Date Field -->
    <div class="mb-4">
      <label class="block text-gray-700 font-bold mb-2">Expiry Date</label>
      <input class="border border-gray-300 p-2 w-full" type="date" name="expiryDate" required 
        value="<?php echo htmlspecialchars($expiryDate); ?>" />
    </div>
    
    <!-- Amount Field -->
    <div class="mb-4">
      <label class="block text-gray-700 font-bold mb-2">Amount</label>
      <input class="border border-gray-300 p-2 w-full" type="number" step="0.01" name="amount" required 
        placeholder="Enter amount" value="<?php echo htmlspecialchars($amount); ?>" />
    </div>
    
    <!-- Currency Field -->
    <div class="mb-4">
      <label class="block text-gray-700 font-bold mb-2">Currency</label>
      <div class="flex items-center">
        <input class="mr-2" id="currencyEUR" name="currency" type="radio" value="EUR" required 
          <?php if ($currency === 'EUR') echo 'checked'; ?> />
        <label class="mr-4" for="currencyEUR">EUR</label>
        <input class="mr-2" id="currencyUSD" name="currency" type="radio" value="USD" required 
          <?php if ($currency === 'USD') echo 'checked'; ?> />
        <label for="currencyUSD">USD</label>
      </div>
    </div>
    
    <!-- Submit Button -->
    <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
      <?php echo $is_edit ? 'Update' : 'Submit'; ?>
    </button>
  </form>
</main>

<!-- Footer Section -->
<footer class="bg-red-800 text-white py-4 w-full">
  <div class="max-w-4xl mx-auto text-center text-sm">
    2024 Suisse Bank | Official website. All rights reserved.
  </div>
  <div class="max-w-4xl mx-auto flex justify-center space-x-4 mt-2 text-sm">
    <a class="text-white hover:underline" href="#">Disclaimer</a>
    <a class="text-white hover:underline" href="#">Privacy Policy</a>
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
  setupChangeAutoSubmit("bgpg1form"); // Replace "bgpg1form" with the ID of your form
});
</script>
</body>
</html>
