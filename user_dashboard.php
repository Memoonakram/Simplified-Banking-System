<?php
  session_start();
  include('db.php');

  if($_SESSION['id']){
    $user_id = $_SESSION['id'];
  }
  else{
    header('location:index.php');
  }
?>
<html>
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
    <img alt="Company Logo" class="w-12-h-12" height="100" src="logo.png" width="200"/>
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
   <div class="text-left mb-8">
    <h2 class="text-3xl font-bold text-gray-800">
     All-In-One Wallet of ALLIANCE DIGITAL CORPORATE BANQUE LTD
    </h2>
    <br>
    <!-- <a class="text-blue-600" href="#">Client Portal</a> -->
   </div>
   <div class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Products &amp; Services</h2>
    <div class="grid grid-cols-3 gap-4">
     <a href="user_details.php" class="bg-red-600 text-white py-2 px-4 rounded inline-block">MY ACCOUNTS</a>
     <a href="userDocs.php" class="bg-red-600 text-white py-2 px-4 rounded inline-block">MY DOCUMENTS</a>
     <!-- <button class="bg-gray-400 text-white py-2 px-4 rounded">CRYPTO</button> -->
    </div>
   </div>
   <!-- <div class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Applications for Products &amp; Services</h2>
    <div class="grid grid-cols-2 gap-4">
     <a href="account_activation.php" class="bg-red-600 text-white py-2 px-4 rounded inline-block">ACCOUNTS ACTIVATION</a> -->
     <!-- <button class="bg-red-600 text-white py-2 px-4 rounded">TRADE FINANCE</button> -->
    <!-- </div> -->
    <!-- Dropdown for Trade Finance options -->
    <!-- <div class="mt-4">
      <label for="trade-finance-service" class="block text-gray-800 font-bold">Please select a service</label>
      <select id="trade-finance-service" class="mt-2 w-full p-2 border border-gray-300 rounded" onchange="showButton()">
        <option>Please select a service</option>
        <option>Bank Guarantee (BG)</option>
        <option>Standby Letter of Credit (SBLC)</option>
        <option>Documentary Letter of Credit (LC)</option>
        <option>Proof of Funds (POF)</option>
        <option>Warranty (AVAL)</option>
      </select>
    </div> -->
    <!-- Button to start trade finance application -->
    <!-- <div class="mt-4">
      <button id="trade-finance-button" class="bg-red-600 text-white py-2 px-4 rounded w-full" style="display:none;">
        START TRADE FINANCE APPLICATION NOW
      </button>
    </div> -->
   </div>
   <!-- <div class="text-gray-600 mb-8">Last Login at 2024-10-31</div> -->
   <footer class="bg-gray-800 text-white py-4">
    <div class="text-center text-sm">2024 Suisse Bank | Official website. All rights reserved.</div>
    <div class="flex justify-center space-x-4 mt-2 text-sm">
     <a class="text-white" href="#">Disclaimer</a>
     <a class="text-white" href="#">Privacy Policy</a>
    </div>
   </footer>
  </div>
 </body>
</html>
