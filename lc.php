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
$transferable = ''; $expiryDate = ''; $amount = ''; $currency = ''; $payment_atSight = ''; $incoterm = ''; $type = ''; $partialShipment = '';
$transshipment = '';$loadingPort = '';$dischargePort = '';$latestDate = '';$certificate = '';$contractNums = '';$proformaNums = '';
$shippingMarks = '';$specialConditions = '';

$message = "";

// Check if there's already an entry for the logged-in user
$query = "SELECT * FROM lc_pg1 WHERE user_id = ? AND isSubmitted = FALSE ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
    
  $lc_pg1_id = $row['id'];
  $transferable = $row['transferable'];
  $expiryDate = $row['expiry_date'];
  $amount = $row['amount'];
  $currency = $row['currency'];
  $payment_atSight = $row['payment_atSight'];
  $incoterm = $row['incoterm'];
  $type = $row['type'];
  $partialShipment = $row['partial_shipment'];
  $transshipment = $row['transshipment'];
  $loadingPort = $row['loading_port'];
  $dischargePort = $row['discharge_port'];
  $latestDate = $row['latest_date'];
  $certificate = $row['certificate'];
  $contractNums = $row['contract_nums'];
  $proformaNums = $row['proforma_nums'];
  $shippingMarks = $row['shipping_marks'];
  $specialConditions = $row['special_conditions'];
}
else{
  $insert_query = "INSERT INTO lc_pg1 (user_id, transferable, expiry_date, amount, currency,payment_atSight,incoterm,type,partial_shipment,
  transshipment, loading_port, discharge_port, latest_date, certificate, contract_nums, proforma_nums, shipping_marks, special_conditions )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($insert_query);
  $stmt->bind_param("issdssssssssssssss", $user_id, $transferable, $expiryDate, $amount, $currency, $payment_atSight, $incoterm, $type,
    $partialShipment, $transshipment, $loadingPort, $dischargePort, $latestDate, $certificate, $contractNums, $proformaNums,
    $shippingMarks, $specialConditions);
  $stmt->execute();
  $lc_pg1_id = $stmt->insert_id;
  $_SESSION['lc_pg1_id'] = $lc_pg1_id;
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $transferable = $_POST['transferable'];
    $expiryDate = $_POST['expiryDate'];
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];
    $payment_atSight = $_POST['payment_atSight'];
    $incoterm = $_POST['incoterm'];
    $type = $_POST['type'];
    $partialShipment = $_POST['partial_shipment'];
    $transshipment = $_POST['transshipment'];
    $loadingPort = $_POST['loadingPort'];
    $dischargePort = $_POST['dischargePort'];
    $latestDate = $_POST['latestDate'];
    $certificate = $_POST['certificate'];
    $contractNums = $_POST['contractNums'];
    $proformaNums = $_POST['proformaNums'];
    $shippingMarks = $_POST['shippingMarks'];
    $specialConditions = $_POST['specialConditions'];

    $update_query = "
    UPDATE lc_pg1 
    SET 
        transferable = ?, expiry_date = ?, amount = ?, currency = ?, payment_atSight = ?, incoterm = ?, type = ?, partial_shipment = ?, 
        transshipment = ?, loading_port = ?, discharge_port = ?, latest_date = ?, certificate = ?, contract_nums = ?, proforma_nums = ?, 
        shipping_marks = ?, special_conditions = ?
    WHERE id = ?";

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param(
        "ssdssssssssssssssi", 
        $transferable, $expiryDate, $amount, $currency, $payment_atSight, $incoterm, $type, $partialShipment, $transshipment, $loadingPort, 
        $dischargePort, $latestDate, $certificate, $contractNums, $proformaNums, $shippingMarks, $specialConditions, $lc_pg1_id
    );


    // Execute the statement and show a message
    if ($stmt->execute()) {
        $_SESSION['lc_pg1_id'] = $lc_pg1_id;
        header("Location: lcpg2.php");
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
  <meta charset="UTF-8">
  <title>Documentary Letter of Credit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
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
        <!-- <a class="text-red-600 hover:underline" href="#">Change Email/Password</a> -->
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
      <div class="text-lg font-bold">
        TRADE FINANCE – DOCUMENTARY LETTER OF CREDIT
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
  <main class="max-w-4xl mx-auto p-8">
    <h1 class="text-2xl font-bold mb-6">TERMS OF DOCUMENTARY LETTER OF CREDIT (“LC”)</h1>
    <form method = "post" id="lcpg1form">
      <div class="text-red-600 text-sm mb-4">
          <span>1/3</span>
          <span>This data is collected for the application “documentary letter of credit”.</span>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Transferable</label>
        <div class="flex items-center mt-2">
          <input class="mr-2" id="transferableYes" name="transferable" type="radio" value = "YES"
            <?php if ($transferable == 'YES') echo 'checked'; ?>/>
          <label class="mr-4" for="transferableYes">Yes</label>
          <input class="mr-2" id="transferableNo" name="transferable" type="radio" value = "NO"
            <?php if ($transferable == 'NO') echo 'checked'; ?>/>
          <label for="transferableNo">No</label>
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Expiry Date</label>
        <input class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-500 mt-2" name= "expiryDate" type="date" 
            value="<?php echo htmlspecialchars($expiryDate); ?>"/>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Amount</label>
        <input class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-500 mt-2" name="amount" type="number" 
        value="<?php echo htmlspecialchars($amount); ?>"/>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Currency</label>
        <div class="flex items-center mt-2">
          <input class="mr-2" id="currencyEUR" name="currency" type="radio" value = "EUR"
          <?php if ($currency == 'EUR') echo 'checked'; ?>/>
          <label class="mr-4" for="currencyEUR">EUR</label>
          <input class="mr-2" id="currencyUSD" name="currency" type="radio" value = "USD"
          <?php if ($currency == 'USD') echo 'checked'; ?>/>
          <label for="currencyUSD">USD</label>
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Payment at Sight</label>
        <div class="flex items-center mt-2">
          <input class="mr-2" id="paymentYes" name="payment_atSight" type="radio" value = "yes"
          <?php if ($payment_atSight == 'yes') echo 'checked'; ?>/>
          <label class="mr-4" for="paymentYes">Yes</label>
          <input class="mr-2" id="paymentNo" name="payment_atSight" type="radio" value = "no"
          <?php if ($payment_atSight == 'no') echo 'checked'; ?>/>
          <label for="paymentNo">No</label>
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Incoterms</label>
        <div class="flex items-center mt-2">
          <input class="mr-2" id="incotermFOB" name="incoterm" type="radio" value = "FOB"
          <?php if ($incoterm == 'FOB') echo 'checked'; ?>/>
          <label class="mr-4" for="incotermFOB">FOB</label>
          <input class="mr-2" id="incotermCFR" name="incoterm" type="radio" value = "CFR"
          <?php if ($incoterm == 'CFR') echo 'checked'; ?>/>
          <label class="mr-4" for="incotermCFR">CFR</label>
          <input class="mr-2" id="incotermCIP" name="incoterm" type="radio" value = "CIP"
          <?php if ($incoterm == 'CIP') echo 'checked'; ?>/>
          <label class="mr-4" for="incotermCIP">CIP</label>
          <input class="mr-2" id="incotermCIF" name="incoterm" type="radio" value = "CIF"
          <?php if ($incoterm == 'CIF') echo 'checked'; ?>/>
          <label for="incotermCIF">CIF</label>
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Type</label>
        <div class="flex items-center mt-2">
          <input class="mr-2" id="typeAirfreight" name="type" type="radio" value = "airfreight"
          <?php if ($type === 'airfreight') echo 'checked'; ?>/>
          <label class="mr-4" for="typeAirfreight">AIRFREIGHT</label>
          <input class="mr-2" id="typeSea" name="type" type="radio" value = "sea"
          <?php if ($type === 'sea') echo 'checked'; ?>/>
          <label class="mr-4" for="typeSea">SEA</label>
          <input class="mr-2" id="typeRoadHaulage" name="type" type="radio" value = "road haulage"
          <?php if ($type === 'road haulage') echo 'checked'; ?>/>
          <label for="typeRoadHaulage">ROAD HAULAGE</label>
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Partial Shipment</label>
        <div class="flex items-center mt-2">
          <input class="mr-2" id="partialAllowed" name="partial_shipment" type="radio" value = "yes"
          <?php if ($partialShipment === 'yes') echo 'checked'; ?>/>
          <label class="mr-4" for="partialAllowed">ALLOWED</label>
          <input class="mr-2" id="partialNotAllowed" name="partial_shipment" type="radio" value = "no"
          <?php if ($partialShipment === 'no') echo 'checked'; ?>/>
          <label for="partialNotAllowed">NOT ALLOWED</label>
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Transshipment</label>
        <div class="flex items-center mt-2">
          <input class="mr-2" id="transshipmentAllowed" name="transshipment" type="radio" value = "yes"
          <?php if ($transshipment === 'yes') echo 'checked'; ?>/>
          <label class="mr-4" for="transshipmentAllowed">ALLOWED</label>
          <input class="mr-2" id="transshipmentNotAllowed" name="transshipment" type="radio" value = "no"
          <?php if ($transshipment === 'no') echo 'checked'; ?>/>
          <label for="transshipmentNotAllowed">NOT ALLOWED</label>
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Port of Loading/ Airport of Departure/ Place of Taking in Charge</label>
        <input class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-500 mt-2" type="text" name="loadingPort"
        value="<?php echo $loadingPort; ?>" />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Port of Discharge/ Airport of Destination/ Place of Final Destination</label>
        <input class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-500 mt-2" type="text" name="dischargePort"
        value="<?php echo $dischargePort; ?>" />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Latest Date of Shipment</label>
        <input class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-500 mt-2" name="latestDate" placeholder="YYYYMMDD" type="date"
        value="<?php echo $latestDate; ?>" />
      </div>
      
      <div class="mb-4">
        <label class="block text-gray-700">Certificate of Origin</label>
        <select class="w-full border-b border-gray-300 focus:outline-none focus:border-gray-500 mt-2" required
        name="certificate" value="<?php echo $certificate; ?>" >
          <option value="" disabled selected>Please Choose</option>
          <option value="UK">United Kingdom</option>
          <option value="USA">United States</option>
          <option value="Canada">Canada</option>
        </select>
      </div>

      <!-- Details of Contract Section -->
      <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">DETAILS OF CONTRACT IN RESPECT OF WHICH THE LC IS REQUESTED</h2>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="contractNums">Contract(s) Number(s)</label>
          <textarea class="w-full p-2 border border-gray-300 rounded" id="contractNums" name="contractNums" rows="3">
            <?php echo $contractNums; ?>
          </textarea>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="proformaNums">Proforma Invoice(s) Number(s)</label>
          <textarea class="w-full p-2 border border-gray-300 rounded" id="proformaNums" name="proformaNums" rows="3">
            <?php echo $proformaNums; ?>
          </textarea>
        </div>
      </div>

      <!-- Shipping Marks Section -->
      <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">SHIPPING MARKS</h2>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="shippingMarks">Shipping Marks</label>
          <textarea class="w-full p-2 border border-gray-300 rounded" id="shippingMarks" name="shippingMarks" rows="3">
            <?php echo $shippingMarks; ?>
          </textarea>
        </div>
      </div>

      <!-- Special Conditions Section -->
      <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">SPECIAL CONDITIONS (IF PRESENT)</h2>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="specialConditions">Special Conditions</label>
          <textarea class="w-full p-2 border border-gray-300 rounded" id="specialConditions" name="specialConditions" rows="3">
            <?php echo $specialConditions; ?>
          </textarea>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="bg-red-600 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
        Continue
      </button>
    </form>
    
    <div class="mt-4">
      <a class="text-gray-700 hover:underline" href="#">Information about data entry</a>
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
  setupChangeAutoSubmit("lcpg1form"); // form
});


</script>
</body>
</html>
