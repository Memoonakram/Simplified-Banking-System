<?php
  session_start();
  include 'db.php';

  if($_SESSION['id']){
    $user_id = $_SESSION['id'];
  }
  else{
    header('location:index.php');
  }

  $seller_code = '';
  $buyer_code = '';
  $contract_no = '';
  $expiryDate = '';
  $amount = '';
  $currency = '';

  $query = "SELECT * FROM pof_pg1 WHERE user_id = ? AND isSubmitted = FALSE ORDER BY created_at DESC";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
      
    $pof_pg1_id = $row['id'];
    $seller_code = $row['seller_code'];
    $buyer_code = $row['buyer_code'];
    $contract_no = $row['contract_no'];
    $expiryDate = $row['expiry_date'];
    $amount = $row['amount'];
    $currency = $row['currency'];
  }
  else{
    $insert_query = "INSERT INTO pof_pg1 (user_id, seller_code, buyer_code,contract_no,expiry_date, amount, currency) VALUES (?, ?, ?, ?, ?,?,?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("issssds", $user_id, $seller_code, $buyer_code, $contract_no, $expiryDate, $amount, $currency);
    $stmt->execute();
    $pof_pg1_id = $stmt->insert_id;
    $_SESSION['pof_pg1_id'] = $pof_pg1_id;
  }
  $stmt->close();

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $seller_code = $_POST['seller_code'];
    $buyer_code = $_POST['buyer_code'];
    $contract_no = $_POST['contract_no'];
    $expiryDate = $_POST['expiryDate'];
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];

    
        // Update existing record
    $update_query = "UPDATE pof_pg1 SET seller_code = ?, buyer_code = ?, contract_no = ?, expiry_date = ?, amount = ?, currency = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssdsi", $seller_code, $buyer_code, $contract_no,$expiryDate, $amount, $currency, $pof_pg1_id);

    // Execute the statement and show a message
    if ($stmt->execute()) {
        $_SESSION['pof_pg1_id'] = $pof_pg1_id;
        header("Location: pofpg2.php");
        exit(); // Make sure no further code is executed after the redirect
    } else {
        $message = "<div class='text-red-600 font-bold mb-4'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>pof</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <style>
      body {
        font-family: Arial, sans-serif;
      }
    </style>
  </head>
  <body class="bg-white text-gray-800 font-sans">
    <!-- Header Section -->
    <header class="bg-white w-full shadow-md">
      <div class="max-w-4xl mx-auto p-4 flex justify-between items-center">
      <img alt="" class="mb-4 logo-img" src="logo.jpg" width="100" height="100"/> 
        <div class="flex items-center space-x-4 text-sm text-gray-700">
          <span class="flex items-center space-x-1">
            <i class="fas fa-envelope"></i>
            <!-- <span class="text-red-600">onboarding@suissebank.com</span> -->
          </span>
          <!-- <a class="text-red-600 hover:underline" href="#"
            >Change Email/Password</a
          > -->
          <a class="text-red-600 flex items-center space-x-1" href="logout.php">
  <i class="fas fa-sign-out-alt"></i>
  <span>Sign Out</span>
</a>

        </div>
      </div>
    </header>

    <!-- Navigation Section -->
    <nav class="bg-gray-800 text-white py-4 w-full">
      <div class="max-w-4xl mx-auto flex justify-between items-center">
        <div class="text-lg font-bold">TRADE FINANCE – pof</div>
      </div>
    </nav>
    <div class="bg-red-600 text-white p-4 w-full">
      <div class="max-w-4xl mx-auto flex justify-between items-center">
        <div class="flex items-center">
          <i class="fas fa-user mr-2"></i>
          <a class="text-white font-bold hover:underline" href="#"
            >Beneficiary</a
          >
        </div>
        <div class="flex items-center">
          <i class="fas fa-file-alt mr-2"></i>
          <a class="text-white hover:underline" href="#"
            >Terms &amp; Conditions</a
          >
        </div>
        <div class="flex items-center">
          <i class="fas fa-info-circle mr-2"></i>
          <a class="text-white hover:underline" href="#">Finalization</a>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto p-4 mt-8">
      <div class="text-red-600 font-bold mb-2">1/3</div>
      <div class="text-red-600 mb-4">
        This data is collected for the application “pof”.
      </div>
      <div class="text-2xl font-bold mb-4">
        TERMS OF PROOF OF FUNDS (“AVAL”)
      </div>
      <form method="post" id="pofpg1form">
        <!-- Transferable Field -->
        <div class="max-w-4xl mx-auto p-8 bg-white shadow-md mt-10">
          <div class="grid grid-cols-2 gap-4 mb-4">
            <label class="text-gray-700"> Seller’s Code </label>
            <input
              id="sellersCode"
              class="border-b border-gray-300 focus:outline-none focus:border-gray-500"
              type="text"
              name = "seller_code"
              value="<?php echo htmlspecialchars($seller_code); ?>"
            />
            <label class="text-gray-700"> Buyer’s Code </label>
            <input
              id="buyersCode"
              class="border-b border-gray-300 focus:outline-none focus:border-gray-500"
              type="text"
              name = "buyer_code"
              value="<?php echo htmlspecialchars($buyer_code); ?>"
            />
            <label class="text-gray-700">
              Underlying Relationship (Contract No.)
            </label>
            <input
              id="contractNo"
              class="border-b border-gray-300 focus:outline-none focus:border-gray-500"
              type="text"
              name = "contract_no"
              value="<?php echo htmlspecialchars($contract_no); ?>"
            />
            <label class="text-gray-700"> Expiry Date </label>
            <input
              id="expiryDate"
              class="border-b border-gray-300 focus:outline-none focus:border-gray-500"
              type="date"
              name = "expiryDate"
              value="<?php echo htmlspecialchars($expiryDate); ?>"
            />
            <label class="text-gray-700"> Amount </label>
            <input
              id="amount"
              class="border-b border-gray-300 focus:outline-none focus:border-gray-500"
              type="number"
              name = "amount"
              value="<?php echo htmlspecialchars($amount); ?>"
            />
            <label class="text-gray-700"> Currency </label>
            <div class="flex items-center">
              <input class="mr-2" id="currencyEUR" name="currency" type="radio" value="EUR" required 
                <?php if ($currency === 'EUR') echo 'checked'; ?> />
              <label class="mr-4" for="currencyEUR">EUR</label>
              <input class="mr-2" id="currencyUSD" name="currency" type="radio" value="USD" required 
                <?php if ($currency === 'USD') echo 'checked'; ?> />
              <label for="currencyUSD">USD</label>
            </div>
          </div>
          <button
            class="bg-red-600 text-white py-2 px-4 rounded"
            type="submit"
          >
            Continue
          </button>
        </div>
      </form>
      <div class="mt-4">
        <a class="text-gray-700 hover:underline" href="#"
          >Information about data entry</a
        >
      </div>
    </main>

    <!-- Fixed Image -->
    <div class="fixed bottom-4 right-4">
    <img alt="" class="mb-4 logo-img" src="logo.jpg" width="100" height="100"/> 
    </div>

    <!-- Footer Section -->
    <footer class="bg-gray-800 text-white py-4 w-full">
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
    setupChangeAutoSubmit("pofpg1form"); // Replace "bgpg1form" with the ID of your form
  });


  </script>
  </body>
</html>
