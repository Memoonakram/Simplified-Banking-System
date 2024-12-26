<?php
  session_start();
  include('db.php');

  if($_SESSION['id']){
    $user_id = $_SESSION['id'];
  }
  else{
    header('location:index.php');
  }

  
  $company = ''; $preferred = ''; $foundationDate = ''; $registeredEmail = ''; $busCountryCode = ''; $busPhoneNo = '';

  $foundationAddress1 = ''; $foundationAddress2 = ''; $foundationState = ''; $foundationCity = ''; $foundationCountry = '';
  $foundationPostalCode = ''; $operationalAddress1 = ''; $operationalAddress2 = ''; $operationalState = ''; $operationalCity = '';
  $operationalCountry = ''; $operationalPostalCode = ''; 

  $idType = ''; $idNumber = ''; $issuanceCountry = ''; $issueDate = ''; $expiryDate = '';

  $name = ''; $email = ''; $phoneNo = ''; $address = ''; $role = '';

  if($user_id == -1){

    $defaultPassword = '123';
    $defaultRole = 'user';

    $insert_query = "INSERT INTO users (email, password, role, Name, PhoneNo, Address) 
                     VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssssss", $email, $defaultPassword, $defaultRole, $name, $phoneNo, $address);
    
    if ($stmt->execute()) {
        // Get the newly inserted user's ID
        $new_user_id = $conn->insert_id;
        $user_id = $new_user_id;
    } else {
        echo "Error inserting user: " . $conn->error;
    }
    $stmt->close();
  }
  else{
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['Name'];
        $phoneNo = $row['PhoneNo'];
        $address = $row['Address'];
        $email = $row['email'];
        $role = $row['role'];
    } else {
        echo "No user found with ID: " . $user_id;
    }
    $stmt->close();
  }

  $query = "SELECT * FROM business_details WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $company = $row['company'];
    $preferred = $row['preferred'];
    $foundationDate = $row['foundation_date'];
    $registeredEmail = $row['registered_email'];
    $busCountryCode = $row['business_country_code'];
    $busPhoneNo = $row['business_phone_no'];
    $stmt->close();
  }
  else{
    $insert_query = "INSERT INTO business_details (user_id, company, preferred, foundation_date, registered_email, business_country_code, 
    business_phone_no) VALUES (?, ?, ?, ?, ?, ?, ?)";
  
  $insert_stmt = $conn->prepare($insert_query);
  $insert_stmt->bind_param("issssss", $user_id, $company, $preferred, $foundationDate, $registeredEmail, $busCountryCode, $busPhoneNo);
  $insert_stmt->execute();
  $insert_stmt->close();

  }


  $query = "SELECT * FROM address_details WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $foundationAddress1 = $row['foundation_address1'];
    $foundationAddress2 = $row['foundation_address2'];
    $foundationState = $row['foundation_state'];
    $foundationCity = $row['foundation_city'];
    $foundationCountry = $row['foundation_country'];
    $foundationPostalCode = $row['foundation_postal_code'];
    $operationalAddress1 = $row['operational_address1'];
    $operationalAddress2 = $row['operational_address2'];
    $operationalState = $row['operational_state'];
    $operationalCity = $row['operational_city'];
    $operationalCountry = $row['operational_country'];
    $operationalPostalCode = $row['operational_postal_code'];

    $stmt->close();
} else {
    $insert_query = "INSERT INTO address_details (
        user_id,
        foundation_address1, foundation_address2, foundation_state, foundation_city, foundation_country, foundation_postal_code,
        operational_address1, operational_address2, operational_state, operational_city, operational_country, operational_postal_code
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param(
        "issssssssssss", 
        $user_id, 
        $foundationAddress1, $foundationAddress2, $foundationState, $foundationCity, $foundationCountry, $foundationPostalCode,
        $operationalAddress1, $operationalAddress2, $operationalState, $operationalCity, $operationalCountry, $operationalPostalCode
    );
    $insert_stmt->execute();
    $insert_stmt->close();
}


  // $query = "SELECT * FROM address_details WHERE user_id = ?";
  // $stmt = $conn->prepare($query);
  // $stmt->bind_param("i", $user_id);
  // $stmt->execute();
  // $result = $stmt->get_result();

  // if ($result->num_rows > 0) {
  //   $row = $result->fetch_assoc();

  //   $foundationAddress1 = $row['foundAddr1'];
  //   $foundationAddress2 = $row['foundAddr2'];
  //   $foundationState = $row['foundState'];
  //   $foundationCity = $row['foundCity'];
  //   $foundationCountry = $row['foundCountry'];
  //   $foundationPostalCode = $row['foundPostalCode'];

  //   $operationalAddress1 = $row['opAddr1'];
  //   $operationalAddress2 = $row['opAddr2'];
  //   $operationalState = $row['opState'];
  //   $operationalCity = $row['opCity'];
  //   $operationalCountry = $row['opCountry'];
  //   $operationalPostalCode = $row['opPostalCode'];
  //   $stmt->close();
  // }
  // else{
  //   $insert_query = "INSERT INTO address_details (user_id) VALUES (?)";
  
  //   $insert_stmt = $conn->prepare($insert_query);
  //   $insert_stmt->bind_param("i", $user_id);
  //   $insert_stmt->execute();
  //   $insert_stmt->close();

  // }

  $query = "SELECT * FROM tax_details WHERE user_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      // Fetch existing data
      $row = $result->fetch_assoc();

      $idType = $row['idType'];
      $idNumber = $row['idNumber'];
      $issuanceCountry = $row['issuance_Country'];
      $issueDate = $row['issueDate'];
      $expiryDate = $row['expiryDate'];
      $stmt->close();
  } else {
      // Insert default data if no record exists
      $insert_query = "INSERT INTO tax_details (user_id, idType, idNumber, issueDate, expiryDate) VALUES (?, ?, ?, ?, ?)";
      $insert_stmt = $conn->prepare($insert_query);

      // Example default values (you can customize as needed)

      $insert_stmt->bind_param("issss", $user_id, $idType, $idNumber, $issueDate, $expiryDate);
      $insert_stmt->execute();
      $insert_stmt->close();
  }


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which form was submitted based on the submit button's name
    if (isset($_POST['submit_businessform'])) {
        // Business form submission
        $company = $_POST['company'];
        $preferred = $_POST['preferred'];
        $foundationDate = $_POST['foundationDate'];
        $registeredEmail = $_POST['registeredEmail'];
        $busCountryCode = $_POST['busCountryCode'];
        $busPhoneNo = $_POST['busPhoneNo'];


        // Example database update query for business details
        $stmt = $conn->prepare("UPDATE business_details 
                                SET company = ?, preferred = ?, foundation_date = ?, registered_email = ?, business_country_Code = ?, 
                                business_phone_no = ? WHERE user_id = ?");
        $stmt->bind_param("ssssssi", $company, $preferred, $foundationDate, $registeredEmail, $busCountryCode, $busPhoneNo, $user_id);
        
        if ($stmt->execute()) {
            echo "Business details updated successfully!";
        } else {
            echo "Error updating business details!";
        }
        $stmt->close();
    }

    if (isset($_POST['submit_addressform'])) {

      $foundationAddress1 = $_POST['foundAddr1'];
      $foundationAddress2 = $_POST['foundAddr2'];
      $foundationState = $_POST['foundState'];
      $foundationCity = $_POST['foundCity'];
      $foundationCountry = $_POST['foundCountry'];
      $foundationPostalCode = $_POST['foundPostalCode'];

      $operationalAddress1 = $_POST['opAddr1'];
      $operationalAddress2 = $_POST['opAddr2'];
      $operationalState = $_POST['opState'];
      $operationalCity = $_POST['opCity'];
      $operationalCountry = $_POST['opCountry'];
      $operationalPostalCode = $_POST['opPostalCode'];


      // Example database update query for business details
      $update_query = "UPDATE address_details 
                 SET foundation_address1 = ?, foundation_address2 = ?, foundation_state = ?, foundation_city = ?, foundation_country = ?, foundation_postal_code = ?, 
                     operational_address1 = ?, operational_address2 = ?, operational_state = ?, operational_city = ?, operational_country = ?, operational_postal_code = ?, 
                     updated_at = NOW() 
                 WHERE user_id = ?";

      $update_stmt = $conn->prepare($update_query);
      $update_stmt->bind_param("ssssssssssssi", 
      $foundationAddress1, $foundationAddress2, $foundationState, $foundationCity, $foundationCountry, $foundationPostalCode,
      $operationalAddress1, $operationalAddress2, $operationalState, $operationalCity, $operationalCountry, $operationalPostalCode,
      $user_id
      );
      $update_stmt->execute();
      $update_stmt->close();

  }
  if (isset($_POST['submit_userform'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneNo = $_POST['phoneNo'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    $update_query = "UPDATE users 
                 SET name = ?, email = ?, phoneNo = ?, address = ?, role = ?
                 WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssssi", 
    $name, $email, $phoneNo, $address, $role, $user_id
    );
    $update_stmt->execute();
    $update_stmt->close();

  }
  if (isset($_POST['submit_taxDetailsForm'])) {
    $idType = $_POST['idType'];
    $idNumber = $_POST['idNum'];
    $issuanceCountry = $_POST['issuanceCountry'];
    $issueDate = $_POST['issueDate'];
    $expiryDate = $_POST['expiryDate'];

    $query = "UPDATE tax_details 
          SET idType = ?, idNumber = ?, issuance_Country = ?, issueDate = ?, expiryDate = ? WHERE user_id = ?";

      $stmt = $conn->prepare($query);
      $stmt->bind_param("sssssi", $idType, $idNumber, $issuanceCountry, $issueDate, $expiryDate, $user_id);
      $stmt->execute();
      $stmt->close();
    
  }
  if (isset($_POST['viewForms'])) {
    header("Location: userForms.php");
  }
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alliance Digital - Admin Dashboard</title>
    
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
          Oxygen, Ubuntu, Cantarell, sans-serif;
      }

      body {
        background-color: #f5f5f5;
      }

      .header {
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      }

      .logo {
        font-weight: 600;
        font-size: 1.2rem;
      }

      .back-link {
        padding: 1rem 2rem;
        color: #666;
        text-decoration: none;
        display: inline-block;
      }

      .back-link:hover {
        color: #333;
      }

      .container {
        display: flex;
        gap: 2rem;
        padding: 0 2rem;
        margin-top: 1rem;
      }

      .sidebar {
        width: 250px;
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        height: fit-content;
      }

      .sidebar-menu {
        list-style: none;
      }

      .sidebar-menu button {
        width: 100%;
        text-align: left;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 6px;
        background: none;
        color: #666;
        cursor: pointer;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
      }

      .sidebar-menu button.active {
        background-color: #fef2f2;
        color: #dc2626;
        font-weight: 500;
      }

      .sidebar-menu button:hover:not(.active) {
        background-color: #f5f5f5;
      }

      .main-content {
        flex: 1;
        background: white;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        display: none;
      }

      .main-content.active {
        display: block;
      }

      .page-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 2rem;
      }

      .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
      }

      .form-group {
        margin-bottom: 1rem;
      }

      .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
      }

      .required::after {
        content: "*";
        color: #dc2626;
        margin-left: 2px;
      }

      .form-control {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
      }

      .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 1px #2563eb;
      }

      .select-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
      }

      .country-select {
        position: relative;
      }

      .country-select .form-control {
        padding-left: 2rem;
      }

      .save-btn {
        margin-top: 2rem;
        background: #dc2626;
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
      }

      .save-btn:hover {
        background: #b91c1c;
      }
      /* Style for both Dashboard and Logout buttons */
button {
  padding: 10px 20px;
  background-color: #f44336; /* Red color */
  color: white;
  border: none;
  cursor: pointer;
  font-size: 16px;
  border-radius: 5px; /* Rounded corners */
  font-weight: bold;
  text-transform: uppercase;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #d32f2f; /* Darker red on hover */
}


      .search-section {
        margin-bottom: 2rem;
      }

      .button-group {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
      }

      .btn-secondary {
        background: white;
        border: 1px solid #d1d5db;
        padding: 0.5rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.5rem;
      }

      th,
      td {
        border: 1px solid #e5e7eb;
        padding: 0.75rem;
        text-align: left;
      }

      th {
        background-color: #f9fafb;
        font-weight: 500;
      }

      td {
        color: #374151;
      }
    </style>
  </head>
  <body>
  <header class="header">
  <div class="logo">
    <div style="display: flex;">

      <img alt="" class="mb-4 logo-img" src="logo.png" width="100" height="100"/> 
      <span style="margin-top: 14px; margin-left: 5px" >Alliance Digital</span>
    </div>
    
</div>
<div class= "logo"><span> Dashboard  </span></div>
    <button class="logout-btn" onclick="window.location.href='logout.php';">Logout</button>
  </header>
  
   

    <div class="container">
      <aside class="sidebar">
        <nav class="sidebar-menu">
          <button data-tab="business">Business Details</button>
          <button data-tab="address">Address Details</button>
          <button data-tab="tax">Alliance Digital Banque</button>
          <button data-tab="account">Account DBS</button>
          <!-- <button data-tab="kyb">KYB Details</button>
          <button data-tab="transaction">Transaction History</button> -->
        </nav>
      </aside>

      <main id="business" class="main-content">
        <h1 class="page-title">Business Details</h1>
        <form action="" id="businessform" method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label required">Company Name</label>
              <input type="text" class="form-control" name="company" value="<?php echo htmlspecialchars($company); ?>"/>
            </div>
            <div class="form-group">
              <label class="form-label required">Preferred Name</label>
              <input type="text" class="form-control" name="preferred" value="<?php echo htmlspecialchars($preferred); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Foundation Date</label>
              <input type="date" class="form-control" name="foundationDate" value="<?php echo htmlspecialchars($foundationDate); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Registered Email</label>
              <input type="email" class="form-control" name="registeredEmail" value="<?php echo htmlspecialchars($registeredEmail); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Country Code</label>
              <input type="text" class="form-control" name="busCountryCode" value="<?php echo htmlspecialchars($busCountryCode); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Phone No</label>
              <input type="text" class="form-control" name="busPhoneNo" value="<?php echo htmlspecialchars($busPhoneNo); ?>" />
            </div>
          </div>
          <button class="save-btn" type="submit" name="submit_businessform">Save Changes</button>
        </form>
      </main>

      <main id="address" class="main-content">
        <h1 class="page-title">Address Details</h1>
        <form action="" method = "POST" id="addressform">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label required">Foundation Address 1</label>
              <input type="text" class="form-control" name="foundAddr1" value="<?php echo htmlspecialchars($foundationAddress1); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Foundation Address 2</label>
              <input type="text" class="form-control" name="foundAddr2" value="<?php echo htmlspecialchars($foundationAddress2); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Foundation State</label>
              <input type="text" class="form-control" name="foundState" value="<?php echo htmlspecialchars($foundationState); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Foundation City</label>
              <input type="text" class="form-control" name="foundCity" value="<?php echo htmlspecialchars($foundationCity); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Foundation Country</label>
              <input type="text" class="form-control" name="foundCountry" value="<?php echo htmlspecialchars($foundationCity); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Foundation Postal Code</label>
              <input type="text" class="form-control" name="foundPostalCode" value="<?php echo htmlspecialchars($foundationPostalCode); ?>" />
            </div>
          </div>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label required">Operational Address 1</label>
              <input type="text" class="form-control" name="opAddr1" value="<?php echo htmlspecialchars($operationalAddress1); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Operational Address 2</label>
              <input type="text" class="form-control" name="opAddr2" value="<?php echo htmlspecialchars($operationalAddress2); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Operational State</label>
              <input type="text" class="form-control" name="opState" value="<?php echo htmlspecialchars($operationalState); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Operational City</label>
              <input type="text" class="form-control" name="opCity" value="<?php echo htmlspecialchars($operationalCity); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Operational Country</label>
              <input type="text" class="form-control" name="opCountry" value="<?php echo htmlspecialchars($operationalCountry); ?>" />
            </div>
            <div class="form-group">
              <label class="form-label required">Operational Postal Code</label>
              <input type="text" class="form-control" name="opPostalCode" value="<?php echo htmlspecialchars($operationalPostalCode); ?>" />
            </div>
          </div>
          <button class="save-btn" type="submit" name="submit_addressform">Save Changes</button>
        </form>
      </main>

      <main id="tax" class="main-content">

      <h1 class="page-title">Alliance Digital Banque</h1>
        <head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script>
      function showButton() {
        const dropdown = document.getElementById("trade-finance-service");
        const button = document.getElementById("trade-finance-button");
        const selectedValue = dropdown.value;

        if (selectedValue !== "Please select a service") {
          button.style.display = "block";

          // Set URL based on selected value
          let url = "";
          switch (selectedValue) {
            case "Bank Guarantee (BG)":
              url = "BGpg1.php";  // Opens onboard.html for "Bank Guarantee (BG)"
              break;
            case "Standby Letter of Credit (SBLC)":
              url = "sblc.php";
              break;
            case "Documentary Letter of Credit (LC)":
              url = "lc.php";
              break;
            case "Proof of Funds (POF)":
              url = "pof.php";
              break;
            case "Warranty (AVAL)":
              url = "warranty.php";
              break;
            default:
              url = "#";
          }

          // Update button link
          button.onclick = function(event) {
            event.preventDefault();
            window.open(url, "_blank");
          };
        } else {
          button.style.display = "none";
        }
      }
    </script>
  </head>
  <body class="bg-white-100">
    <div class="max-w-4xl mx-auto p-4">
      <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-4 text-sm text-gray-700"></div>
      </div>
      <style>
        /* Reset body and html margins/paddings */
        html, body {
          margin: 0;
          padding: 0;
          height: 100%;
        }

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: Arial, sans-serif;
        }

        body {
          background-color: #f4f4f9;
        }

        /* Adjust container size */
        .container {
          width: 100%;
          max-width: 1200px;
          margin: 20px auto;
          background-color: white;
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          padding: 20px;
          max-height: 1000px; /* Set max height */
          /* overflow-y: auto; Scroll if content exceeds max height */
        }

        /* Account Balance and Info Section */
        .balance-section {
          display: flex;
          justify-content: space-between;
          gap: 20px;
          margin-bottom: 20px;
        }

        .balance-box {
          flex: 1;
          border: 2px solid #ccc;
          border-radius: 10px;
          padding: 20px;
          text-align: center;
          height: 360px; /* Set smaller height */
        }

        .balance {
          font-size: 2.5rem;
          margin: 20px 0;
        }

        .payment-btn {
          padding: 10px 20px;
          background-color: #e74c3c;
          color: white;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          font-size: 1rem;
        }

        .payment-btn:hover {
          background-color: #c0392b;
        }

        .account-info {
          flex: 2;
          border: 2px solid #ccc;
          border-radius: 10px;
          padding: 20px;
          height: 360px;
          width: 360px;
           /* Set smaller height */
          /* overflow-y: auto; Scroll if content exceeds max height */
        }

        .info-row {
          display: flex;
          justify-content: space-between;
          padding: 5px 0;
        }

        .info-row.full-width {
          flex-direction: column;
        }

        /* Transactions Section */
        .transactions-section {
          margin-bottom: 20px;
        }

        .transactions-box {
          height: 350px; /* Set smaller height */
          border: 2px solid #ccc;
          border-radius: 10px;
          padding: 20px;
          background-color: #f9f9f9;
        }
      </style>

      <div class="container">
        <!-- Account Balance and Info Section -->
        <div class="balance-section">
          <div class="balance-box">
            <h3>Account Balance</h3>
            <div class="balance">
              <span class="amount">0.00</span>
              <span class="currency">USD</span>
            </div>
            <p><b>CREDIT LINE FACILITY</b> </p>
            <!-- <button class="payment-btn">PAYMENT</button> -->
          </div>

          <div class="account-info">
            <div class="info-row">
              <p><strong>Account Number</strong></p><br>
              <p>23456754324567876543</p><br>
            </div>
            <div class="info-row">
              <p><strong>Account Holder Name</strong></p><br>
              <p>John Doe</p><br>
            </div>
            <div class="info-row">
              <p><strong>Bank Name</strong></p><br>
              <p>ADCB</p><br>
            </div>
            <div class="info-row">
              <p><strong>Bank Code</strong></p><br>
              <p>3232ADS</p><br>
            </div>
            <div class="info-row">
              <p><strong>Country</strong></p><br>
              <p>UAE</p><br>
            </div>
            <div class="info-row">
              <p><strong>Created Date</strong></p><br>
              <p>2024/25/12</p><br>
            </div>
            <div class="info-row full-width">
              <p><strong>Bank Address</strong></p><br>
              <p>23, 443, Business Bay, Dubai</p><br>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <label for="trade-finance-service" class="block text-gray-800 font-bold">Please select a service</label>
          <select id="trade-finance-service" class="mt-2 w-full p-2 border border-gray-300 rounded" onchange="showButton()">
            <option>Please select a service</option>
            <option>Bank Guarantee (BG)</option>
            <option>Standby Letter of Credit (SBLC)</option>
            <option>Documentary Letter of Credit (LC)</option>
            <option>Proof of Funds (POF)</option>
            <!-- <option>Warranty (AVAL)</option> -->
          </select><br>
          <div class="mt-4">
            
          <button id="trade-finance-button" class="bg-red-600 text-white py-1 px-4 rounded w-full" style="display:none;" >
            START APPLICATION NOW
          </button>
        </div>
      </main>
      </main>

      <main id="account" class="main-content">
        <h1 class="page-title">Alliance Digital Banque</h1>
        <head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script>
      function showButton() {
        const dropdown = document.getElementById("trade-finance-service");
        const button = document.getElementById("trade-finance-button");
        const selectedValue = dropdown.value;

        if (selectedValue !== "Please select a service") {
          button.style.display = "block";

          // Set URL based on selected value
          let url = "";
          switch (selectedValue) {
            case "Bank Guarantee (BG)":
              url = "BGpg1.php";  // Opens onboard.html for "Bank Guarantee (BG)"
              break;
            case "Standby Letter of Credit (SBLC)":
              url = "sblc.php";
              break;
            case "Documentary Letter of Credit (LC)":
              url = "lc.php";
              break;
            case "Proof of Funds (POF)":
              url = "pof.php";
              break;
            case "Warranty (AVAL)":
              url = "warranty.php";
              break;
            default:
              url = "#";
          }

          // Update button link
          button.onclick = function(event) {
            event.preventDefault();
            window.open(url, "_blank");
          };
        } else {
          button.style.display = "none";
        }
      }
    </script>
  </head>
  <body class="bg-white-100">
    <div class="max-w-4xl mx-auto p-4">
      <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-4 text-sm text-gray-700"></div>
      </div>
      <style>
        /* Reset body and html margins/paddings */
        html, body {
          margin: 0;
          padding: 0;
          height: 100%;
        }

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: Arial, sans-serif;
        }

        body {
          background-color: #f4f4f9;
        }

        /* Adjust container size */
        .container {
          width: 100%;
          max-width: 1200px;
          margin: 20px auto;
          background-color: white;
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          padding: 20px;
          max-height: 1000px; /* Set max height */
          /* overflow-y: auto; Scroll if content exceeds max height */
        }

        /* Account Balance and Info Section */
        .balance-section {
          display: flex;
          justify-content: space-between;
          gap: 20px;
          margin-bottom: 20px;
        }

        .balance-box {
          flex: 1;
          border: 2px solid #ccc;
          border-radius: 10px;
          padding: 20px;
          text-align: center;
          height: 360px; /* Set smaller height */
        }

        .balance {
          font-size: 2.5rem;
          margin: 20px 0;
        }

        .payment-btn {
          padding: 10px 20px;
          background-color: #e74c3c;
          color: white;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          font-size: 1rem;
        }

        .payment-btn:hover {
          background-color: #c0392b;
        }

        .account-info {
          flex: 2;
          border: 2px solid #ccc;
          border-radius: 10px;
          padding: 20px;
          height: 360px;
          width: 360px;
           /* Set smaller height */
          /* overflow-y: auto; Scroll if content exceeds max height */
        }

        .info-row {
          display: flex;
          justify-content: space-between;
          padding: 5px 0;
        }

        .info-row.full-width {
          flex-direction: column;
        }

        /* Transactions Section */
        .transactions-section {
          margin-bottom: 20px;
        }

        .transactions-box {
          height: 350px; /* Set smaller height */
          border: 2px solid #ccc;
          border-radius: 10px;
          padding: 20px;
          background-color: #f9f9f9;
        }
      </style>

      <div class="container">
        <!-- Account Balance and Info Section -->
        <div class="balance-section">
          <div class="balance-box">
            <h3>Account Balance</h3>
            <div class="balance">
              <span class="amount">0.00</span>
              <span class="currency">USD</span>
            </div>
            <p><b>CREDIT LINE FACILITY</b> </p>
            <!-- <button class="payment-btn">PAYMENT</button> -->
          </div>

          <div class="account-info">
            <div class="info-row">
              <p><strong>Account Number</strong></p><br>
              <p>23456754324567876543</p><br>
            </div>
            <div class="info-row">
              <p><strong>Account Holder Name</strong></p><br>
              <p>John Doe</p><br>
            </div>
            <div class="info-row">
              <p><strong>Bank Name</strong></p><br>
              <p>ADCB</p><br>
            </div>
            <div class="info-row">
              <p><strong>Bank Code</strong></p><br>
              <p>3232ADS</p><br>
            </div>
            <div class="info-row">
              <p><strong>Country</strong></p><br>
              <p>UAE</p><br>
            </div>
            <div class="info-row">
              <p><strong>Created Date</strong></p><br>
              <p>2024/25/12</p><br>
            </div>
            <div class="info-row full-width">
              <p><strong>Bank Address</strong></p><br>
              <p>23, 443, Business Bay, Dubai</p><br>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <label for="trade-finance-service" class="block text-gray-800 font-bold">Please select a service</label>
          <select id="trade-finance-service" class="mt-2 w-full p-2 border border-gray-300 rounded" onchange="showButton()">
            <option>Please select a service</option>
            <option>Bank Guarantee (BG)</option>
            <option>Standby Letter of Credit (SBLC)</option>
            <option>Documentary Letter of Credit (LC)</option>
            <option>Proof of Funds (POF)</option>
            <!-- <option>Warranty (AVAL)</option> -->
          </select><br>
          <div class="mt-4">
            
          <button id="trade-finance-button" class="bg-red-600 text-white py-1 px-4 rounded w-full" style="display:none;" >
            START APPLICATION NOW
          </button>
        </div>
      </main>
        
        
        

        
        <!-- <form action="" method="post">
          <button type="submit" name="viewForms">View User Forms</button>
        </form>
      </main>

      <main id="kyb" class="main-content">
        <h1 class="page-title">KYB Details</h1>
        <div class="form-grid"></div>
        <button class="save-btn">Save Changes</button>
      </main> -->

      <!-- <main id="transaction" class="main-content">
        <h1 class="page-title">Transaction History</h1>
        <div class="search-section">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Transaction Type</label>
              <select class="form-control select-control">
                <option>All Types</option>
                <option>Deposit</option>
                <option>Withdrawal</option>
                <option>Transfer</option>
                <option>Payment</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">From Date</label>
              <input type="date" class="form-control" />
            </div>
            <div class="form-group">
              <label class="form-label">To Date</label>
              <input type="date" class="form-control" />
            </div>
            <div class="form-group">
              <label class="form-label">Limit</label>
              <input type="number" class="form-control" />
            </div>
          </div>
          <div class="button-group">
            <button class="btn-secondary">Clear</button>
            <button class="save-btn">Search</button>
          </div>
        </div>
        <table>
          <thead>
            <tr>
              <th>Transaction ID</th>
              <th>Date</th>
              <th>Type</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="5" style="text-align: center">
                No transactions found
              </td>
            </tr>
          </tbody>
        </table>
      </main> -->
    

    <script>
      document.querySelector(".main-content").classList.add("active");
      document.querySelector(".sidebar-menu button").classList.add("active");

      document.querySelectorAll(".sidebar-menu button").forEach((button) => {
        button.addEventListener("click", () => {
          document
            .querySelectorAll(".sidebar-menu button")
            .forEach((b) => b.classList.remove("active"));
          document
            .querySelectorAll(".main-content")
            .forEach((content) => content.classList.remove("active"));

          button.classList.add("active");
          document.getElementById(button.dataset.tab).classList.add("active");
        });
      });
    </script>
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
    field.addEventListener("change", (event) => {
      // Prevent form submission to avoid page reload
      event.preventDefault();

      // Collect form data
      const formData = new FormData(form);

      // Dynamically set the button name based on the form ID or another unique identifier
      // For example: append a name based on the form ID
      formData.append('submit_' + formId, 'submit');  // Dynamically sets the name of the button

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
// Call the function when the DOM is fully loaded for each form
document.addEventListener("DOMContentLoaded", () => {
  setupChangeAutoSubmit("userform");
  setupChangeAutoSubmit("businessform"); 
  setupChangeAutoSubmit("addressform"); 
  setupChangeAutoSubmit("taxDetailsForm");
});



  </script>
  <script>
    
  </script>
  </body>
</html>
