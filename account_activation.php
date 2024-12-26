<?php
  session_start();
  include 'db.php';

  $user_id = 2;

  $accountPurpose = ''; $payment = ''; $paymentFlow = ''; $transfer = ''; $incomingTransfers = ''; $outgoingTransfers = '';
  $monthlyTransfers = ''; $average_amountTransfers = ''; $currencies = '';

  $query = "SELECT * FROM account_activation WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $accountPurpose = $row['account_purpose'];
    $payment = $row['payment'];
    $paymentFlow = $row['payment_flow'];
    $transfer = $row['transfer'];
    $incomingTransfers = $row['incoming_transfers'];
    $outgoingTransfers = $row['outgoing_transfers'];
    $monthlyTransfers = $row['monthly_transfers'];
    $average_amountTransfers = $row['average_amount_transfers'];
    $currencies = $row['currencies'];
  }
  else{
    $insert_query = "INSERT INTO account_activation (
      user_id, account_purpose, payment, payment_flow, transfer, incoming_transfers, outgoing_transfers, monthly_transfers,
      average_amount_transfers, currencies) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  
  $stmt = $conn->prepare($insert_query);
  $stmt->bind_param(
      "isssssssss", $user_id, $accountPurpose, $payment, $paymentFlow, $transfer, $incomingTransfers,
       $outgoingTransfers, $monthlyTransfers, $average_amountTransfers, $currencies
  );
  $stmt->execute();
  $stmt->close();
  
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $accountPurpose = $_POST['accountPurpose'];
    $payment = $_POST['payment'];
    $paymentFlow = $_POST['paymentFlow'];
    $transfer = $_POST['transfers'];
    $incomingTransfers = $_POST['incomingTransfer'];
    $outgoingTransfers = $_POST['outgoingTransfer'];
    $monthlyTransfers = $_POST['monthlyTransfers'];
    $average_amountTransfers = $_POST['average_amountTransfers'];
    $currencies = $_POST['currencies'];

    $update_query = "UPDATE account_activation SET account_purpose = ?, payment = ?, payment_flow = ?, transfer = ?, 
    incoming_transfers = ?, outgoing_transfers = ?, monthly_transfers = ?, average_amount_transfers = ?,
    currencies = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssssssi", $accountPurpose, $payment, $paymentFlow,$transfer, $incomingTransfers, $outgoingTransfers, 
    $monthlyTransfers, $average_amountTransfers, $currencies, $user_id);
    // Execute the statement and show a message
    if ($stmt->execute()) {
      header("Location: user_dashboard.php");
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
    <title>Account Activation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
      rel="stylesheet"
    />
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
          <!-- <a class="text-red-600" href="#">change email/password</a> -->
          <a class="text-red-600 flex items-center space-x-1" href="logout.php">
  <i class="fas fa-sign-out-alt"></i>
  <span>Sign Out</span>
</a>

        </div>
      </div>
    </div>

    <!-- Title Section -->
    <div class="bg-gray-800 text-white p-4">
      <h1 class="text-center text-lg font-bold">ACCOUNT ACTIVATION</h1>
    </div>

    <!-- Navigation Section -->
    <div class="bg-red-600 text-white flex justify-around py-4">
      <div class="text-center">
        <i class="fas fa-user text-2xl"></i>
        <p class="mt-2">Type of Account</p>
      </div>
      <div class="text-center">
        <i class="fas fa-info-circle text-2xl"></i>
        <p class="mt-2">General Information</p>
      </div>
      <div class="text-center">
        <i class="fas fa-file-alt text-2xl"></i>
        <p class="mt-2">Source of Wealth</p>
      </div>
    </div>

    <!-- Main Content Section -->
    <div class="max-w-4xl mx-auto p-8">
      <form action="" id = "activation" method = "POST">
        <div id="page1" class="">
          <div class="flex items-center mb-4">
            <span class="text-red-600 text-lg font-bold">1/2</span>
            <span class="ml-2 text-red-600 text-lg"
              >General Information</span
            >
          </div>
          <h2 class="text-2xl font-bold mb-4">Account Details</h2>

          <!-- Account Purpose Section -->

          <div class="mb-6">
            <h3 class="text-xl font-bold mb-2">1. Purpose of account</h3>
            <p class="mb-2">
              Clearly detail why you are registering for an account, outlining
              the requirements i.e. you will need to convert GBP to USD in order
              to pay your suppliers (foreign investment, utility bills, etc.)
            </p>
            <textarea
              class="w-full h-24 p-2 border border-gray-300 rounded"
              name="accountPurpose"
            >
            <?php echo $accountPurpose; ?>
            </textarea>
          </div>

          <!-- Payment Flow Section -->
          <div class="mb-6">
            <h3 class="text-xl font-bold mb-2">2. Payment Flow</h3>
            <p class="mb-2">What is the payment flow?</p>
            <textarea
              class="w-full h-24 p-2 border border-gray-300 rounded"
              name="payment"
            >
            <?php echo $payment; ?>
            </textarea>
          </div>

          <!-- Additional Content After Payment Flow -->
          <div class="mb-6">
            <h2 class="text-lg font-semibold">What is the payment flow?</h2>
            <div class="mt-2">
              <label class="block">
                <input
                  class="mr-2"
                  type="radio"
                  name="paymentFlow"
                  value="1st to 1st"
                  <?php if ($paymentFlow === '1st to 1st') echo 'checked'; ?>
                />
                Treasury (1st to 1st)
              </label>
              <label class="block">
                <input
                  class="mr-2"
                  type="radio"
                  name="paymentFlow"
                  value="1st to 3rd"
                  <?php if ($paymentFlow === '1st to 3rd') echo 'checked'; ?>
                />
                Paying suppliers (1st to 3rd)
              </label>
              <label class="block">
                <input
                  class="mr-2"
                  type="radio"
                  name="paymentFlow"
                  value="3rd to 1st"
                  <?php if ($paymentFlow === '3rd to 1st') echo 'checked'; ?>
                />
                Receiving payments from customers (3rd to 1st)
              </label>
              <label class="block">
                <input
                  class="mr-2"
                  type="radio"
                  name="paymentFlow"
                  value="3rd to 3rd"
                  <?php if ($paymentFlow === '3rd to 3rd') echo 'checked'; ?>
                />
                Receiving from customers and paying suppliers (3rd to 3rd)
              </label>
            </div>
          </div>

          <div class="mb-6">
            <h3 class="text-lg font-semibold">3. Transfers</h3>
            <p class="mt-2">
              You will be sending and receiving funds from your own account or
              from third parties?
            </p>
            <textarea
              class="w-full mt-2 p-2 border border-gray-300 rounded"
              rows="4"
              name="transfers"
            >
            <?php echo $transfer; ?>
            </textarea>
          </div>

          <div class="mb-6">
            <h3 class="text-lg font-semibold">
              4. What is the purpose of transfers?
            </h3>
            <div class="mt-2">
              <div class="flex justify-between items-center">
                <span>Incoming</span>
                <input
                  class="border border-gray-300 rounded p-2 w-1/2"
                  type="text"
                  name="incomingTransfer"
                  value = "<?php echo $incomingTransfers; ?>"
                />
              </div>
              <div class="flex justify-between items-center mt-4">
                <span>Outgoing</span>
                <input
                  class="border border-gray-300 rounded p-2 w-1/2"
                  type="text"
                  name="outgoingTransfer"
                  value = "<?php echo $outgoingTransfers; ?>"
                />
              </div>
            </div>
          </div>

          <div class="mb-6">
            <h3 class="text-lg font-semibold">
              5. How many transfers, average amount and currencies?
            </h3>
          </div>

          <!-- Additional Form Section -->

          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2"
              >Monthly transfers</label
            >
            <div class="flex items-center space-x-4">
              <label class="inline-flex items-center">
                <input
                  class="form-radio text-gray-600"
                  name="monthlyTransfers"
                  type="radio"
                  value = "upto 10"
                  <?php if ($monthlyTransfers == 'upto 10') echo 'checked'; ?>
                />
                <span class="ml-2">up to 10</span>
              </label>
              <label class="inline-flex items-center">
                <input
                  class="form-radio text-gray-600"
                  name="monthlyTransfers"
                  type="radio"
                  value = "upto 20"
                  <?php if ($monthlyTransfers == 'upto 20') echo 'checked'; ?>
                />
                <span class="ml-2">up to 20</span>
              </label>
              <label class="inline-flex items-center">
                <input
                  class="form-radio text-gray-600"
                  name="monthlyTransfers"
                  type="radio"
                  value = "more than 20"
                  <?php if ($monthlyTransfers == 'more than 20') echo 'checked'; ?>
                />
                <span class="ml-2">more than 20</span>
              </label>
            </div>
          </div>
          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2"
              >Average amount transfers</label
            >
            <div class="flex items-center space-x-4">
              <label class="inline-flex items-center">
                <input
                  class="form-radio text-gray-600"
                  name="average_amountTransfers"
                  type="radio"
                  value = "up to 50,000"
                  <?php if ($average_amountTransfers === 'upto 50,000') echo 'checked'; ?>
                />
                <span class="ml-2">up to 50,000</span>
              </label>
              <label class="inline-flex items-center">
                <input
                  class="form-radio text-gray-600"
                  name="average_amountTransfers"
                  type="radio"
                  value = "upto 100,000"
                  <?php if ($average_amountTransfers === 'upto 100,000') echo 'checked'; ?>
                />
                <span class="ml-2">up to 100,000</span>
              </label>
              <label class="inline-flex items-center">
                <input
                  class="form-radio text-gray-600"
                  name="average_amountTransfers"
                  type="radio"
                  value = "more than 100,000"
                  <?php if ($average_amountTransfers === 'more than 100,000') echo 'checked'; ?>
                />
                <span class="ml-2">more than 100,000</span>
              </label>
            </div>
          </div>
          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2"
              >Currencies: List the currencies you will need to buy and
              sell</label
            >
            <textarea
              class="w-full h-24 p-2 border border-gray-300 rounded"
              name="currencies"
            >
              <?php echo $currencies; ?>
            </textarea>
          </div>
          <div class="flex items-center space-x-4">
            <button
              class="px-4 py-2 border border-gray-300 rounded bg-white text-black"
              type="button"
            >
              Back
            </button>
            <button
              class="px-4 py-2 border border-gray-300 rounded bg-red-500 text-white"
              type="button"
              onclick="showPage2()"
            >
              Continue
            </button>
          </div>
        </div>

        <!-- Page 2 -->
        <div id="page2" class="hidden">
          <div class="flex justify-between items-center mb-4">
            <span class="text-red-600 font-bold">2/2</span>
            <span class="text-red-600">General Information</span>
          </div>
          <div class="mb-6">
            <h2 class="font-bold text-lg">6. Mandatory Documents</h2>
            <p class="mt-2">
              Please provide by email to
              <a class="text-blue-600" href="mailto:onboarding@suissebank.com"
                >onboarding@suissebank.com</a
              >
              at least 2 examples each of purchase & sale invoices.
            </p>
          </div>
          <div class="mb-6">
            <h2 class="font-bold text-lg">
              7. I will provide an invoice and explanation along with client
              contract or any other documentation (for example POI and POA)
              needed to validate the third-party funds.
            </h2>
          </div>
          <div class="mb-6">
            <h2 class="font-bold text-lg">Disclaimer</h2>
            <p class="mt-2">
              Payment services for Suisse Capital Payment Provider Ltd are
              provided by Equals Connect Limited, a company incorporated in
              England & Wales.
            </p>
            <p>Registration No: 07131446</p>
            <p class="mt-2">
              Equals Connect Limited is licensed and regulated by HMRC as a
              Money Service Business (MSB).
            </p>
            <p>Licence No: 12594438</p>
            <p class="mt-2">
              Equals Connect Limited is authorised by the Financial Conduct
              Authority as an Authorised Payment Institution Firm.
            </p>
            <p>Reference Number: 671508</p>
            <p class="mt-2 font-bold">
              Any assumption of liability on the part of Suisse Capital Payment
              Provider Ltd, Suisse Capital LLC, and Suisse Bank Plc is excluded.
            </p>
          </div>
          <div class="flex items-center space-x-4">
            <button
              class="px-4 py-2 border border-gray-300 rounded bg-white text-black"
              type="button"
            >
              Back
            </button>
            <button
              class="px-4 py-2 border border-gray-300 rounded bg-red-500 text-white"
              type="submit"
            >
              Submit
            </button>
          </div>
          <div class="flex justify-end">
          <img alt="" class="mb-4 logo-img" src="logo.jpg" width="100" height="100"/> 
          </div>
        </div>
      </form>
    </div>

    <script>
      function showPage2() {
        document.getElementById("page1").classList.add("hidden");
        document.getElementById("page2").classList.remove("hidden");
      }
    </script>
    <!-- Footer Section -->
    <div class="bg-gray-800 text-white py-4 mt-8">
      <div class="max-w-4xl mx-auto flex justify-between items-center">
        <p>2024 Suisse Bank | Official website. All rights reserved.</p>
        <div class="flex space-x-4">
          <a class="hover:underline" href="#">Disclaimer</a>
          <a class="hover:underline" href="#">Privacy Policy</a>
        </div>
        <img
          alt="Bank logo"
          class="w-10 h-10"
          src="https://storage.googleapis.com/a1aa/image/g5aqniEyLl5EL93Jh0OsnPCsbPTdGWW7r5hXCb7xT9CuKG7E.jpg"
        />
      </div>
    </div>
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
        console.log(formData);

        // Send form data using fetch
        fetch(form.action, {
          method: 'POST',
          body: formData
        })
      });
    });
  }

    // Example usage:
    // Call the function when the DOM is fully loaded
    document.addEventListener("DOMContentLoaded", () => {
    setupChangeAutoSubmit("activation"); // Replace "bgpg1form" with the ID of your form
  });


  </script>
  </body>
</html>
