<?php
  session_start();
  include('db.php');

  $user_id = 2;

  $isBGForm = 0; $isPOFForm = 0; $isSBLCForm = 0; $isWarrantyForm = 0; $isLCForm = 0;



  $query = "SELECT * FROM bank_guarantee_pg1 WHERE user_id = ? AND isSubmitted = true ORDER BY created_at DESC";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id); // Assuming user_id is an integer
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Fetch the data and prefill the form
    $row = $result->fetch_assoc();
    $BGtransferable = $row['transferable'];
    $BGexpiryDate = $row['expiry_date'];
    $BGamount = $row['amount'];
    $BGcurrency = $row['currency'];
    $bg_pg1_id = $row['id'];

    $query = "SELECT * FROM bank_guarantee_pg2 WHERE bank_guarantee_pg1_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bg_pg1_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $isBGForm = 1;
      // Data exists, fetch it
      $row = $result->fetch_assoc();
      $BGbeneficiary_type = $row['beneficiary_type'];
      $BGstreet = $row['street'];
      $BGadditional_address = $row['additional_address'];
      $BGpostal_code = $row['postal_code'];
      $BGcity = $row['city'];
      $BGcountry = $row['country'];
      $BGemail = $row['email'];
      $BGmobile_number = $row['mobile_number'];
      $BGrelationship = $row['relationship'];
      $BGcontract_details = $row['contract_details'];
      $BGcontact_person = $row['contact_person'];
      $BGbank_name = $row['bank_name'];
      $BGbank_street = $row['bank_street'];
      $BGbank_additional_address = $row['bank_additional_address'];
      $BGaccount_number = $row['account_number'];
      $BGiban = $row['iban'];
      $BGswift = $row['swift'];
      $BGdue_diligence = $row['due_diligence'];
      $documentId = $row['document_id'];

      $stmt = $conn->prepare("SELECT document_content,document_name,document_type from documents where document_id = ?");
      $stmt->bind_param("i", $documentId);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($BGfileData, $BGfileName, $BGfileType);
      $stmt->fetch();
    }
    else{
      $isBGForm = 0;
    }
  }
  $stmt->close();

  $query = "SELECT * FROM pof_pg1 WHERE user_id = ? AND isSubmitted = true ORDER BY created_at DESC";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      // Fetch the data and prefill the form
    $row = $result->fetch_assoc();
    $POFseller_code = $row['seller_code'];
    $POFbuyer_code = $row['buyer_code'];
    $POFcontract_no = $row['contract_no'];
    $POFexpiryDate = $row['expiry_date'];
    $POFamount = $row['amount'];
    $POFcurrency = $row['currency'];
    $pof_pg1_id = $row['id'];

    $query = "SELECT * FROM pof_pg2 WHERE pof_pg1_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $pof_pg1_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Data exists, fetch it
        $row = $result->fetch_assoc();
        $POFbeneficiary_type = $row['beneficiary_type'];
        $POFstreet = $row['street'];
        $POFadditional_address = $row['additional_address'];
        $POFpostal_code = $row['postal_code'];
        $POFcity = $row['city'];
        $POFcountry = $row['country'];
        $POFemail = $row['email'];
        $POFmobile_number = $row['mobile_number'];
        $POFrelationship = $row['relationship'];
        $POFcontract_details = $row['contract_details'];
        $POFcontact_person = $row['contact_person'];
        $POFbank_name = $row['bank_name'];
        $POFbank_street = $row['bank_street'];
        $POFbank_additional_address = $row['bank_additional_address'];
        $POFaccount_number = $row['account_number'];
        $POFiban = $row['iban'];
        $POFswift = $row['swift'];
        $POFdue_diligence = $row['due_diligence'];

        $documentId = $row['document_id'];

        $stmt = $conn->prepare("SELECT document_content,document_name,document_type from documents where document_id = ?");
        $stmt->bind_param("i", $documentId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($POFfileData, $POFfileName, $POFfileType);
        $stmt->fetch();

        $isPOFForm = 1;
    }
  }
  $stmt->close();

  $query = "SELECT * FROM sblc_pg1 WHERE user_id = ? AND isSubmitted = true ORDER BY created_at DESC";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Fetch the data and prefill the form
    $row = $result->fetch_assoc();
    $SBLCtransferable = $row['transferable'];
    $SBLCexpiryDate = $row['expiry_date'];
    $SBLCamount = $row['amount'];
    $SBLCcurrency = $row['currency'];
    $sblc_pg1_id = $row['id'];

    $query = "SELECT * FROM sblc_pg2 WHERE sblc_pg1_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sblc_pg1_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Data exists, fetch it
      $row = $result->fetch_assoc();
      $SBLCbeneficiary_type = $row['beneficiary_type'];
      $SBLCstreet = $row['street'];
      $SBLCadditional_address = $row['additional_address'];
      $SBLCpostal_code = $row['postal_code'];
      $SBLCcity = $row['city'];
      $SBLCcountry = $row['country'];
      $SBLCemail = $row['email'];
      $SBLCmobile_number = $row['mobile_number'];
      $SBLCrelationship = $row['relationship'];
      $SBLCcontract_details = $row['contract_details'];
      $SBLCcontact_person = $row['contact_person'];
      $SBLCbank_name = $row['bank_name'];
      $SBLCbank_street = $row['bank_street'];
      $SBLCbank_additional_address = $row['bank_additional_address'];
      $SBLCaccount_number = $row['account_number'];
      $SBLCiban = $row['iban'];
      $SBLCswift = $row['swift'];
      $SBLCdue_diligence = $row['due_diligence'];

      $documentId = $row['document_id'];

      $stmt = $conn->prepare("SELECT document_content,document_name,document_type from documents where document_id = ?");
      $stmt->bind_param("i", $documentId);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($SBLCfileData, $SBLCfileName, $SBLCfileType);
      $stmt->fetch();

      $isSBLCForm = 1;
    }
  }
  $stmt->close();

  $query = "SELECT * FROM warranty_pg1 WHERE user_id = ? AND isSubmitted = true ORDER BY created_at DESC";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      // Fetch the data and prefill the form
    $row = $result->fetch_assoc();
    $Warrantyseller_code = $row['seller_code'];
    $Warrantybuyer_code = $row['buyer_code'];
    $Warrantycontract_no = $row['contract_no'];
    $WarrantyexpiryDate = $row['expiry_date'];
    $Warrantyamount = $row['amount'];
    $Warrantycurrency = $row['currency'];

    $warranty_pg1_id = $row['id'];

    $query = "SELECT * FROM warranty_pg2 WHERE warranty_pg1_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $warranty_pg1_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Data exists, fetch it
      $row = $result->fetch_assoc();
      $Warrantybeneficiary_type = $row['beneficiary_type'];
      $Warrantystreet = $row['street'];
      $Warrantyadditional_address = $row['additional_address'];
      $Warrantypostal_code = $row['postal_code'];
      $Warrantycity = $row['city'];
      $Warrantycountry = $row['country'];
      $Warrantyemail = $row['email'];
      $Warrantymobile_number = $row['mobile_number'];
      $Warrantyrelationship = $row['relationship'];
      $Warrantycontract_details = $row['contract_details'];
      $Warrantycontact_person = $row['contact_person'];
      $Warrantybank_name = $row['bank_name'];
      $Warrantybank_street = $row['bank_street'];
      $Warrantybank_additional_address = $row['bank_additional_address'];
      $Warrantyaccount_number = $row['account_number'];
      $Warrantyiban = $row['iban'];
      $Warrantyswift = $row['swift'];
      $Warrantydue_diligence = $row['due_diligence'];
      
      $documentId = $row['document_id'];

      $stmt = $conn->prepare("SELECT document_content,document_name,document_type from documents where document_id = ?");
      $stmt->bind_param("i", $documentId);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($WarrantyfileData, $WarrantyfileName, $WarrantyfileType);
      $stmt->fetch();

      $isWarrantyForm = 1;
    }

  }
  $stmt->close();

  $query = "SELECT * FROM lc_pg1 WHERE user_id = ? AND isSubmitted = true ORDER BY created_at DESC";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      // Fetch the data and prefill the form
    $row = $result->fetch_assoc();
    $LCtransferable = $row['transferable'];
    $LCexpiryDate = $row['expiry_date'];
    $LCamount = $row['amount'];
    $LCcurrency = $row['currency'];
    $LCpayment_atSight = $row['payment_atSight'];
    $LCincoterm = $row['incoterm'];
    $LCtype = $row['type'];
    $LCpartialShipment = $row['partial_shipment'];
    $LCtransshipment = $row['transshipment'];
    $LCloadingPort = $row['loading_port'];
    $LCdischargePort = $row['discharge_port'];
    $LClatestDate = $row['latest_date'];
    $LCcertificate = $row['certificate'];
    $LCcontractNums = $row['contract_nums'];
    $LCproformaNums = $row['proforma_nums'];
    $LCshippingMarks = $row['shipping_marks'];
    $LCspecialConditions = $row['special_conditions'];

    $lc_pg1_id = $row['id'];

    $query = "SELECT * FROM lc_pg2 WHERE lc_pg1_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $lc_pg1_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Data exists, fetch it
      $row = $result->fetch_assoc();
      $LCbeneficiary_type = $row['beneficiary_type'];
      $LCstreet = $row['street'];
      $LCadditional_address = $row['additional_address'];
      $LCpostal_code = $row['postal_code'];
      $LCcity = $row['city'];
      $LCcountry = $row['country'];
      $LCemail = $row['email'];
      $LCmobile_number = $row['mobile_number'];
      $LCrelationship = $row['relationship'];
      $LCcontract_details = $row['contract_details'];
      $LCcontact_person = $row['contact_person'];
      $LCbank_name = $row['bank_name'];
      $LCbank_street = $row['bank_street'];
      $LCbank_additional_address = $row['bank_additional_address'];
      $LCaccount_number = $row['account_number'];
      $LCiban = $row['iban'];
      $LCswift = $row['swift'];
      $LCdue_diligence = $row['due_diligence'];

      $documentId = $row['document_id'];

      $stmt = $conn->prepare("SELECT document_content,document_name,document_type from documents where document_id = ?");
      $stmt->bind_param("i", $documentId);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($LCfileData, $LCfileName, $LCfileType);
      $stmt->fetch();


      $isLCForm = 1;
    }
  }
$stmt->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Onboarding - Submission</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/summary.css"/>
    <!-- <style>
      body {
        font-family: Arial, sans-serif;
      }
    </style> -->
  </head>
  <body class="bg-white">
    
  <header class="bg-white w-full shadow-md">
  <div class="max-w-4xl ml-0 p-4 flex justify-start items-center">
    <img
      alt="Company Logo"
      class="w-12 h-12"
      src="https://storage.googleapis.com/a1aa/image/thD2nM46lPpVJldTVhh2rA8DLTw6p2Zsu8fFa6iJGapfetYnA.jpg"
    />
    <div class="flex items-center space-x-4 text-sm text-gray-700">
      <span class="flex items-center space-x-1">
        <i class="fas fa-envelope"></i>
        <!-- <span class="text-yellow-600">onboarding@suissebank.com</span> -->
      </span>
          <!-- <a class="text-yellow-600 hover:underline" href="#"
            >Change Email/Password</a
          > -->
          <a class="text-red-600 flex items-center space-x-1" href="logout.php">
  <i class="fas fa-sign-out-alt"></i>
  <span>Sign Out</span>
</a>

        </div>
      </div>
    </header>

    <!-- BANK GUARANTEE FORM -->

    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
  <!-- Summary Container (Extreme Left) -->
  <div class="lg:w-2/5">
    <div class="summary-container bg-white p-6 rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold mb-4 text-gray-800">Summary</h1>

      <div class="summary-content">
        <div class="flex items-center justify-between mb-2">
          <h2 class="text-lg font-semibold text-gray-700">Terms of Trade Finance</h2>
        </div>

        <div class="ml-4">
          <table class="table-auto w-full border-collapse">
            <tr class="border-b">
              <td class="font-semibold text-gray-600 py-2 px-4">Transferable:</td>
              <td class="py-2 px-4"><?php echo htmlspecialchars($BGtransferable); ?></td>
            </tr>
            <tr class="border-b">
              <td class="font-semibold text-gray-600 py-2 px-4">Expiry Date:</td>
              <td class="py-2 px-4"><?php echo htmlspecialchars($BGexpiryDate); ?></td>
            </tr>
            <tr class="border-b">
              <td class="font-semibold text-gray-600 py-2 px-4">Amount:</td>
              <td class="py-2 px-4"><?php echo htmlspecialchars($BGamount); ?></td>
            </tr>
            <tr>
              <td class="font-semibold text-gray-600 py-2 px-4">Currency:</td>
              <td class="py-2 px-4"><?php echo htmlspecialchars($BGcurrency); ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-lg mt-8">
      <h1 class="text-2xl font-bold mb-4 text-gray-800">Beneficiary</h1>

      <div class="ml-4">
        <table class="table-auto w-full border-collapse">
          <tr class="border-b">
            <td class="font-semibold text-gray-600 py-2 px-4">Beneficiary Type:</td>
            <td class="py-2 px-4"><?php echo htmlspecialchars($BGbeneficiary_type); ?></td>
          </tr>
          <tr class="border-b">
            <td class="font-semibold text-gray-600 py-2 px-4">E-mail:</td>
            <td class="py-2 px-4"><?php echo htmlspecialchars($BGemail); ?></td>
          </tr>
          <tr class="border-b">
            <td class="font-semibold text-gray-600 py-2 px-4">Mobile Number:</td>
            <td class="py-2 px-4"><?php echo htmlspecialchars($BGmobile_number); ?></td>
          </tr>
          <tr class="border-b">
            <td class="font-semibold text-gray-600 py-2 px-4">Relationship of Beneficiary to Applicant:</td>
            <td class="py-2 px-4"><?php echo htmlspecialchars($BGrelationship); ?></td>
          </tr>
          <tr class="border-b">
            <td class="font-semibold text-gray-600 py-2 px-4">Contract Details:</td>
            <td class="py-2 px-4"><?php echo htmlspecialchars($BGcontract_details); ?></td>
          </tr>
          <tr class="border-b">
            <td class="font-semibold text-gray-600 py-2 px-4">Contact Person [name]:</td>
            <td class="py-2 px-4"><?php echo htmlspecialchars($BGcontact_person); ?></td>
          </tr>
          <tr class="border-b">
            <td class="font-semibold text-gray-600 py-2 px-4">Street / Building No.:</td>
            <td class="py-2 px-4"><?php echo htmlspecialchars($BGstreet); ?></td>
          </tr>
          <tr>
            <td class="font-semibold text-gray-600 py-2 px-4">Additional Address:</td>
            <td class="py-2 px-4"><?php echo htmlspecialchars($BGadditional_address); ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <!-- Document Section (Expanded Size) -->
  <div class="lg:w-3/5 flex justify-center">
    <div class="w-full bg-white p-6 rounded-lg shadow-lg">
      <h1 class="text-2xl font-bold mb-4 text-gray-800">Document</h1>
      <?php 
        if (strpos($BGfileType, "image") !== false) {
          echo '<div class="mb-4"><img src="data:' . $BGfileType . ';base64,' . base64_encode($BGfileData) . '" class="w-full rounded-lg shadow-lg" /></div>';
        } elseif ($BGfileType == "application/pdf") {
          echo '<div class="mb-4"><embed src="data:application/pdf;base64,' . base64_encode($BGfileData) . '" width="100%" height="600px" class="rounded-lg shadow-lg" /></div>';
        } else {
          echo "<p class='text-gray-600'>Unsupported file type.</p>";
        }
      ?>
    </div>
  </div>
</div>

<!-- Additional Information Section -->
<div class="bg-white p-6 rounded-lg shadow-lg mt-8 max-w-xl">
  <h1 class="text-2xl font-bold mb-4 text-gray-800">Additional Information</h1>

  <div class="ml-4">
    <table class="table-auto w-full border-collapse">
      <tr class="border-b">
        <td class="font-semibold text-gray-600 py-2 px-4">Postal Code:</td>
        <td class="py-2 px-4"><?php echo htmlspecialchars($BGpostal_code); ?></td>
      </tr>
      <tr class="border-b">
        <td class="font-semibold text-gray-600 py-2 px-4">City:</td>
        <td class="py-2 px-4"><?php echo htmlspecialchars($BGcity); ?></td>
      </tr>
      <tr class="border-b">
        <td class="font-semibold text-gray-600 py-2 px-4">Country:</td>
        <td class="py-2 px-4"><?php echo htmlspecialchars($BGcountry); ?></td>
      </tr>
      <tr class="border-b">
        <td class="font-semibold text-gray-600 py-2 px-4">Bank Name:</td>
        <td class="py-2 px-4"><?php echo htmlspecialchars($BGbank_name); ?></td>
      </tr>
      <tr class="border-b">
        <td class="font-semibold text-gray-600 py-2 px-4">IBAN:</td>
        <td class="py-2 px-4"><?php echo htmlspecialchars($BGiban); ?></td>
      </tr>
      <tr>
        <td class="font-semibold text-gray-600 py-2 px-4">SWIFT:</td>
        <td class="py-2 px-4"><?php echo htmlspecialchars($BGswift); ?></td>
      </tr>
    </table>
  </div>
</div>

<!-- Back Button -->
<div class="flex justify-between mt-8">
  <button class="bg-gray-200 text-black py-2 px-4 rounded hover:bg-gray-300" onclick="window.history.back();">
    Back
  </button>
</div>





<!-- STANDBY LETTER OF CREDIT FORM -->

<!-- <h1 class="text-2xl font-bold mb-4">Summary</h1>
<div class="mb-8">
  <div class="flex items-center justify-between mb-2">
    <h2 class="text-lg font-bold">Terms of Trade Finance</h2>
  </div>
  <div class="ml-0">
    <p class="mb-1">
      <span class="font-semibold">Transferable:</span>
      <?php echo htmlspecialchars($SBLCtransferable); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">Expiry Date:</span>
      <?php echo htmlspecialchars($SBLCexpiryDate); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">Amount:</span>
      <?php echo htmlspecialchars($SBLCamount); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">Currency:</span>
      <?php echo htmlspecialchars($SBLCcurrency); ?>
    </p>
  </div>
</div>

<div class="mb-8">
  <div class="flex items-center justify-between mb-2">
    <h2 class="text-lg font-bold">Beneficiary</h2>
  </div>
  <div class="ml-0">
    <p class="mb-1">
      <span class="font-semibold">Beneficiary Type:</span>
      <?php echo htmlspecialchars($SBLCbeneficiary_type); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">E-mail:</span>
      <?php echo htmlspecialchars($SBLCemail); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">Mobile Number:</span>
      <?php echo htmlspecialchars($SBLCmobile_number); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">Relationship of Beneficiary to Applicant:</span>
      <?php echo htmlspecialchars($SBLCrelationship); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">Details of contract incl. contract number in respect of which the SBLC is requested:</span>
      <?php echo htmlspecialchars($SBLCcontract_details); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">Contact Person [name]:</span>
      <?php echo htmlspecialchars($SBLCcontact_person); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">Street / Building No.:</span>
      <?php echo htmlspecialchars($SBLCstreet); ?>
    </p>
    <p class="mb-1">
      <span class="font-semibold">Additional Address:</span>
      <?php echo htmlspecialchars($SBLCadditional_address); ?>
    </p>
  </div>

  <?php 
    echo "<h3 class='text-lg font-bold mb-2'>Document: $SBLCfileName</h3>";
    if (strpos($SBLCfileType, "image") !== false) {
        echo '<div class="mb-4"><img src="data:' . $SBLCfileType . ';base64,' . base64_encode($SBLCfileData) . '" width="300" class="rounded-lg shadow-lg"/></div>';
    } elseif ($SBLCfileType == "application/pdf") {
        echo '<div class="mb-4"><embed src="data:application/pdf;base64,' . base64_encode($SBLCfileData) . '" width="600" height="400" class="rounded-lg shadow-lg"/></div>';
    } else {
        echo "<p class='text-red-500'>Unsupported file type.</p>";
    }
    echo "<hr class='my-4'>";
  ?>

  <div class="max-w-4xl mx-auto p-8">
    <div class="mb-8">
      <p class="mb-2">
        <strong class="font-semibold">Postal Code:</strong>
        <?php echo htmlspecialchars($SBLCpostal_code); ?>
      </p>
      <p class="mb-2">
        <strong class="font-semibold">City:</strong>
        <?php echo htmlspecialchars($SBLCcity); ?>
      </p>
      <p class="mb-2">
        <strong class="font-semibold">Country:</strong>
        <?php echo htmlspecialchars($SBLCcountry); ?>
      </p>
      <p class="mb-2">
        <strong class="font-semibold">Bank Name:</strong>
        <?php echo htmlspecialchars($SBLCbank_name); ?>
      </p>
      <p class="mb-2">
        <strong class="font-semibold">IBAN:</strong>
        <?php echo htmlspecialchars($SBLCiban); ?>
      </p>
      <p class="mb-2">
        <strong class="font-semibold">SWIFT:</strong>
        <?php echo htmlspecialchars($SBLCswift); ?>
      </p>
      <p class="mb-2">
        <strong class="font-semibold">Street / Building No.:</strong>
        <?php echo htmlspecialchars($SBLCstreet); ?>
      </p>
      <p class="mb-2">
        <strong class="font-semibold">Postal Code:</strong>
        <?php echo htmlspecialchars($SBLCpostal_code); ?>
      </p>
      <p class="mb-2">
        <strong class="font-semibold">City:</strong>
        <?php echo htmlspecialchars($SBLCcity); ?>
      </p>
      <p class="mb-2">
        <strong class="font-semibold">Country:</strong>
        <?php echo htmlspecialchars($SBLCcountry); ?>
      </p>
    </div>

    <div class="flex justify-between">
      <button
        class="bg-gray-200 text-black py-2 px-4 rounded shadow-md hover:bg-gray-300"
        onclick="window.history.back();"
      >
        Back
      </button>
    </div>
  </div>

  <div class="fixed bottom-4 right-4">
    <img
      alt="Logo of a stylized horse head in a circular gold background"
      height="100"
      src="https://storage.googleapis.com/a1aa/image/TIW3wDbWfd1CGqe2EIibS7bNwuGrF9z3KefQ6tF8I7OVF93OB.jpg"
      width="100"
      class="rounded-full border-2 border-white shadow-lg"
    />
  </div>
</div> -->



    <!-- Proof of Funds -->

    <!-- <h1 class="text-2xl font-bold mb-4">Summary</h1>
    <div class="mb-8">
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-lg font-bold">Terms of Trade Finance</h2>
      </div>
      <div class="ml-4">
        <p class="mb-1">
          <span class="font-semibold">Seller Code:</span
          ><?php echo htmlspecialchars($POFseller_code); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Buyer Code:</span
          ><?php echo htmlspecialchars($POFbuyer_code); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Contract No:</span
          ><?php echo htmlspecialchars($POFcontract_no); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Expiry Date:</span
          ><?php echo htmlspecialchars($POFexpiryDate); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Amount:</span
          ><?php echo htmlspecialchars($POFamount); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Currency:</span
          ><?php echo htmlspecialchars($POFcurrency); ?>"
        </p>
      </div>
    </div>
    <div>
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-lg font-bold">Beneficiary</h2>
      </div>
      <div class="ml-4">
        <p class="mb-1">
          <span class="font-semibold">Beneficiary Type:</span>
          <?php echo htmlspecialchars($POFbeneficiary_type); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">E-mail:</span>
          <?php echo htmlspecialchars($POFemail); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Mobile Number:</span>
          <?php echo htmlspecialchars($POFmobile_number); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold"
            >Relationship of Beneficiary to Applicant:</span
          >
          <?php echo htmlspecialchars($POFrelationship); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold"
            >Details of contract incl. contract number in respect of which the
            POF is requested:</span
          >
          <?php echo htmlspecialchars($POFcontract_details); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Contact Person [name]:</span>
          <?php echo htmlspecialchars($POFcontact_person); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Street / Building No.:</span>
          <?php echo htmlspecialchars($POFstreet); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Additional Address:</span>
          <?php echo htmlspecialchars($POFadditional_address); ?>"
        </p>
      </div>
      <?php 
        echo "<h3>Document: $POFfileName</h3>";
        if (strpos($POFfileType, "image") !== false) {
            echo '<img src="data:' . $POFfileType . ';base64,' . base64_encode($POFfileData) . '" width="300"/>';
        } elseif ($POFfileType == "application/pdf") {
            echo '<embed src="data:application/pdf;base64,' . base64_encode($POFfileData) . '" width="600" height="400"/>';
        } else {
            echo "Unsupported file type.";
        }
        echo "<hr>";
      ?>
      <div class="max-w-4xl mx-auto p-8">
        <div class="mb-8">
          <p>
            <strong>Postal Code:</strong>
            <?php echo htmlspecialchars($POFpostal_code); ?>"
          </p>
          <p>
            <strong>City:</strong>
            <?php echo htmlspecialchars($POFcity); ?>"
          </p>
          <p>
            <strong>Country:</strong>
            <?php echo htmlspecialchars($POFcountry); ?>"
          </p>
          <p>
            <strong>Bank Name:</strong>
            <?php echo htmlspecialchars($POFbank_name); ?>"
          </p>
          <p>
            <strong>IBAN:</strong>
            <?php echo htmlspecialchars($POFiban); ?>"
          </p>
          <p>
            <strong>SWIFT:</strong>
            <?php echo htmlspecialchars($POFswift); ?>"
          </p>
          <p>
            <strong>Street / Building No.:</strong>
            <?php echo htmlspecialchars($POFstreet); ?>"
          </p>
          <p>
            <strong>Postal Code:</strong>
            <?php echo htmlspecialchars($POFpostal_code); ?>"
          </p>
          <p>
            <strong>City:</strong>
            <?php echo htmlspecialchars($POFcity); ?>"
          </p>
          <p>
            <strong>Country:</strong>
            <?php echo htmlspecialchars($POFcountry); ?>"
          </p>
        </div>
        <div class="flex justify-between">
          <button
            class="bg-gray-200 text-black py-2 px-4 rounded"
            onclick="window.history.back();"
          >
            Back
          </button>
          </form>
        </div>
      </div>
      <div class="fixed bottom-4 right-4">
        <img
          alt="Logo of a stylized horse head in a circular gold background"
          height="100"
          src="https://storage.googleapis.com/a1aa/image/TIW3wDbWfd1CGqe2EIibS7bNwuGrF9z3KefQ6tF8I7OVF93OB.jpg"
          width="100"
        />
      </div>
    </div> -->

    <!-- Warranty AVAL -->

    <!-- <h1 class="text-2xl font-bold mb-4">Summary</h1>
    <div class="mb-8">
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-lg font-bold">Terms of Trade Finance</h2>
      </div>
      <div class="ml-4">
        <p class="mb-1">
          <span class="font-semibold">Seller Code:</span
          ><?php echo htmlspecialchars($Warrantyseller_code); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Buyer Code:</span
          ><?php echo htmlspecialchars($Warrantybuyer_code); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Contract No:</span
          ><?php echo htmlspecialchars($Warrantycontract_no); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Expiry Date:</span
          ><?php echo htmlspecialchars($WarrantyexpiryDate); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Amount:</span
          ><?php echo htmlspecialchars($Warrantyamount); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Currency:</span
          ><?php echo htmlspecialchars($Warrantycurrency); ?>"
        </p>
      </div>
    </div>
    <div>
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-lg font-bold">Beneficiary</h2>
      </div>
      <div class="ml-4">
        <p class="mb-1">
          <span class="font-semibold">Beneficiary Type:</span>
          <?php echo htmlspecialchars($Warrantybeneficiary_type); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">E-mail:</span>
          <?php echo htmlspecialchars($Warrantyemail); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Mobile Number:</span>
          <?php echo htmlspecialchars($Warrantymobile_number); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold"
            >Relationship of Beneficiary to Applicant:</span
          >
          <?php echo htmlspecialchars($Warrantyrelationship); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold"
            >Details of contract incl. contract number in respect of which the
            Warranty is requested:</span
          >
          <?php echo htmlspecialchars($Warrantycontract_details); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Contact Person [name]:</span>
          <?php echo htmlspecialchars($Warrantycontact_person); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Street / Building No.:</span>
          <?php echo htmlspecialchars($Warrantystreet); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Additional Address:</span>
          <?php echo htmlspecialchars($Warrantyadditional_address); ?>"
        </p>
      </div>
      <?php 
        echo "<h3>Document: $WarrantyfileName</h3>";
        if (strpos($WarrantyfileType, "image") !== false) {
            echo '<img src="data:' . $WarrantyfileType . ';base64,' . base64_encode($WarrantyfileData) . '" width="300"/>';
        } elseif ($WarrantyfileType == "application/pdf") {
            echo '<embed src="data:application/pdf;base64,' . base64_encode($WarrantyfileData) . '" width="600" height="400"/>';
        } else {
            echo "Unsupported file type.";
        }
        echo "<hr>";
      ?>
      <div class="max-w-4xl mx-auto p-8">
        <div class="mb-8">
          <p>
            <strong>Postal Code:</strong>
            <?php echo htmlspecialchars($Warrantypostal_code); ?>"
          </p>
          <p>
            <strong>City:</strong>
            <?php echo htmlspecialchars($Warrantycity); ?>"
          </p>
          <p>
            <strong>Country:</strong>
            <?php echo htmlspecialchars($Warrantycountry); ?>"
          </p>
          <p>
            <strong>Bank Name:</strong>
            <?php echo htmlspecialchars($Warrantybank_name); ?>"
          </p>
          <p>
            <strong>IBAN:</strong>
            <?php echo htmlspecialchars($Warrantyiban); ?>"
          </p>
          <p>
            <strong>SWIFT:</strong>
            <?php echo htmlspecialchars($Warrantyswift); ?>"
          </p>
          <p>
            <strong>Street / Building No.:</strong>
            <?php echo htmlspecialchars($Warrantystreet); ?>"
          </p>
          <p>
            <strong>Postal Code:</strong>
            <?php echo htmlspecialchars($Warrantypostal_code); ?>"
          </p>
          <p>
            <strong>City:</strong>
            <?php echo htmlspecialchars($Warrantycity); ?>"
          </p>
          <p>
            <strong>Country:</strong>
            <?php echo htmlspecialchars($Warrantycountry); ?>"
          </p>
        </div>

        <div class="flex justify-between">
          <button
            class="bg-gray-200 text-black py-2 px-4 rounded"
            onclick="window.history.back();"
          >
            Back
          </button>
          </form>
        </div>
      </div>
      <div class="fixed bottom-4 right-4">
        <img
          alt="Logo of a stylized horse head in a circular gold background"
          height="100"
          src="https://storage.googleapis.com/a1aa/image/TIW3wDbWfd1CGqe2EIibS7bNwuGrF9z3KefQ6tF8I7OVF93OB.jpg"
          width="100"
        />
      </div>
    </div> -->

    <!-- LETTER OF CREDIT -->
<!-- 
    <h1 class="text-2xl font-bold mb-4">Summary</h1>
    <div class="mb-8">
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-lg font-bold">Terms of Trade Finance</h2>
      </div>
      <div class="ml-4">
        <p class="mb-1">
          <span class="font-semibold">Transferable: </span
          ><?php echo htmlspecialchars($LCtransferable); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Expiry Date: </span
          ><?php echo htmlspecialchars($LCexpiryDate); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Amount: </span
          ><?php echo htmlspecialchars($LCamount); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Currency: </span
          ><?php echo htmlspecialchars($LCcurrency); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Payment at Sight: </span
          ><?php echo htmlspecialchars($LCpayment_atSight); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Incoterm: </span
          ><?php echo htmlspecialchars($LCincoterm); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Type: </span
          ><?php echo htmlspecialchars($LCtype); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Partial Shipment: </span
          ><?php echo htmlspecialchars($LCpartialShipment); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Trans Shipment: </span
          ><?php echo htmlspecialchars($LCtransshipment); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold"
            >Port of Loading/ Airport of Departure/ Place of Taking in Charge: </span
          ><?php echo htmlspecialchars($LCloadingPort); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold"
            >Port of Discharge/ Airport of Destination/ Place of Final
            Destination: </span
          ><?php echo htmlspecialchars($LCdischargePort); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Latest Date of Shipment: </span
          ><?php echo htmlspecialchars($LClatestDate); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Certificate of Origin: </span
          ><?php echo htmlspecialchars($LCcertificate); ?>
        </p>
        <h3>DETAILS OF CONTRACT IN RESPECT OF WHICH THE LC IS REQUESTED</h3>
        <p class="mb-1">
          <span class="font-semibold">Contract Numbers: </span
          ><?php echo htmlspecialchars($LCcontractNums); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Porforma Invoice Numbers: </span
          ><?php echo htmlspecialchars($LCproformaNums); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Shipping Marks: </span
          ><?php echo htmlspecialchars($LCshippingMarks); ?>
        </p>
        <p class="mb-1">
          <span class="font-semibold">Special Conditions: </span
          ><?php echo htmlspecialchars($LCspecialConditions); ?>
        </p>
      </div>
    </div>
    <div>
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-lg font-bold">Beneficiary</h2>
      </div>
      <div class="ml-4">
        <p class="mb-1">
          <span class="font-semibold">Beneficiary Type:</span>
          <?php echo htmlspecialchars($LCbeneficiary_type); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">E-mail:</span>
          <?php echo htmlspecialchars($LCemail); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Mobile Number:</span>
          <?php echo htmlspecialchars($LCmobile_number); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold"
            >Relationship of Beneficiary to Applicant:</span
          >
          <?php echo htmlspecialchars($LCrelationship); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold"
            >Details of contract incl. contract number in respect of which the
            LC is requested:</span
          >
          <?php echo htmlspecialchars($LCcontract_details); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Contact Person [name]:</span>
          <?php echo htmlspecialchars($LCcontact_person); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Street / Building No.:</span>
          <?php echo htmlspecialchars($LCstreet); ?>"
        </p>
        <p class="mb-1">
          <span class="font-semibold">Additional Address:</span>
          <?php echo htmlspecialchars($LCadditional_address); ?>"
        </p>
      </div>
      <?php 
        echo "<h3>Document: $LCfileName</h3>";
        if (strpos($LCfileType, "image") !== false) {
            echo '<img src="data:' . $LCfileType . ';base64,' . base64_encode($LCfileData) . '" width="300"/>';
        } elseif ($LCfileType == "application/pdf") {
            echo '<embed src="data:application/pdf;base64,' . base64_encode($LCfileData) . '" width="600" height="400"/>';
        } else {
            echo "Unsupported file type.";
        }
        echo "<hr>";
      ?>
      <div class="max-w-4xl mx-auto p-8">
        <div class="mb-8">
          <p>
            <strong>Postal Code:</strong>
            <?php echo htmlspecialchars($LCpostal_code); ?>"
          </p>
          <p>
            <strong>City:</strong>
            <?php echo htmlspecialchars($LCcity); ?>"
          </p>
          <p>
            <strong>Country:</strong>
            <?php echo htmlspecialchars($LCcountry); ?>"
          </p>
          <p>
            <strong>Bank Name:</strong>
            <?php echo htmlspecialchars($LCbank_name); ?>"
          </p>
          <p>
            <strong>IBAN:</strong>
            <?php echo htmlspecialchars($LCiban); ?>"
          </p>
          <p>
            <strong>SWIFT:</strong>
            <?php echo htmlspecialchars($LCswift); ?>"
          </p>
          <p>
            <strong>Street / Building No.:</strong>
            <?php echo htmlspecialchars($LCstreet); ?>"
          </p>
          <p>
            <strong>Postal Code:</strong>
            <?php echo htmlspecialchars($LCpostal_code); ?>"
          </p>
          <p>
            <strong>City:</strong>
            <?php echo htmlspecialchars($LCcity); ?>"
          </p>
          <p>
            <strong>Country:</strong>
            <?php echo htmlspecialchars($LCcountry); ?>"
          </p>
        </div>

        <div class="flex justify-between">
          <button
            class="bg-gray-200 text-black py-2 px-4 rounded"
            onclick="window.history.back();"
          >
            Back
          </button>
        </div>
      </div>
      <div class="fixed bottom-4 right-4">
        <img
          alt="Logo of a stylized horse head in a circular gold background"
          height="100"
          src="https://storage.googleapis.com/a1aa/image/TIW3wDbWfd1CGqe2EIibS7bNwuGrF9z3KefQ6tF8I7OVF93OB.jpg"
          width="100"
        />
      </div> -->

      
    </div>
  </body>
</html>

