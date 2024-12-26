<?php
// Include the database connection file
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $beneficiaryType = $_POST['beneficiary'];
    $street = $_POST['street'];
    $additionalAddress = $_POST['additional-address'];
    $postalCode = $_POST['postal-code'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $email = $_POST['email'];
    $countryCode = $_POST['country-code'];
    $mobileNumber = $_POST['mobile-number'];
    $relationship = $_POST['relationship'];
    $contractDetails = $_POST['contract-details'];
    $contactPerson = $_POST['contact-person'];
    $bankName = $_POST['bank-name'];
    $bankStreetAddress = $_POST['bank-street'];
    $bankPostalCode = $_POST['bank-postal-code'];
    $bankCity = $_POST['bank-city'];

    // File upload processing
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        // Get the file content
        $document = file_get_contents($_FILES['document']['tmp_name']);
        $documentName = $_FILES['document']['name'];
        $documentType = $_FILES['document']['type'];
    } else {
        die("Error uploading document.");
    }

    // Prepare the SQL query using MySQLi
    $stmt = $conn->prepare("INSERT INTO pg2 
                            (beneficiary_type, street, additional_address, postal_code, city, country, email,country_code ,mobile_number, relationship, contract_details, contact_person, bank_name, bank_street, bank_postal_code, bank_city,document,documentType,documentName) 
                            VALUES 
                            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)");

    // Bind parameters to the prepared statement
    $stmt->bind_param("sssssssssssssssssss", 
        $beneficiaryType, 
        $street, 
        $additionalAddress, 
        $postalCode, 
        $city, 
        $country, 
        $email, 
        $countryCode,
        $mobileNumber, // Concatenate country code with the mobile number
        $relationship, 
        $contractDetails, 
        $contactPerson,  
        $bankName, 
        $bankStreetAddress, 
        $bankPostalCode, 
        $bankCity,
        $document,
        $documentType,
        $documentName
    );

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "Form submitted successfully.";
    } else {
        echo "Error submitting form: " . $stmt->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
</head>
<body class="font-roboto bg-white text-gray-800">

    <!-- Header Section -->
    <div class="max-w-4xl mx-auto py-2">
        <div class="flex justify-between items-center mb-4">
            <img alt="Company Logo" class="w-12 h-12" src="https://storage.googleapis.com/a1aa/image/thD2nM46lPpVJldTVhh2rA8DLTw6p2Zsu8fFa6iJGapfetYnA.jpg"/>
            <div class="flex items-center space-x-4 text-sm text-gray-700">
                <span class="flex items-center space-x-1">
                    <i class="fas fa-envelope"></i>
                    <span class="text-yellow-600">onboarding@suissebank.com</span>
                </span>
                <a class="text-yellow-600" href="#">Change Email/Password</a>
                <a class="text-yellow-600 flex items-center space-x-1" href="#">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sign Out</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation and Progress Indicator -->
    <nav class="bg-gray-800 text-white p-4">
        <h1 class="text-center text-lg font-bold">TRADE FINANCE – BANK GUARANTEE</h1>
    </nav>
    <div class="bg-yellow-600 text-white flex justify-around py-4">
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
        <div class="text-yellow-600 text-sm mb-4">
            <span>2/3</span>
            <span>This data is collected for the application “Bank Guarantee”.</span>
        </div>
        <h2 class="text-2xl font-bold mb-6">BENEFICIARY</h2>
        <form class="space-y-6" method="POST" action="" enctype="multipart/form-data">
            <!-- Beneficiary Type -->
            <div class="flex items-center space-x-4">
                <label class="font-bold" for="beneficiary-type">The BENEFICIARY is a*</label>
                <div class="flex items-center space-x-2">
                    <input id="person" name="beneficiary" type="radio" value="person" required/>
                    <label for="person">Person</label>
                </div>
                <div class="flex items-center space-x-2">
                    <input id="company" name="beneficiary" type="radio" value="company" required/>
                    <label for="company">Company</label>
                </div>
            </div>

            <!-- Address -->
            <div class="space-y-4">
                <div>
                    <label for="street" class="block text-sm font-medium text-gray-700">Street Address</label>
                    <input type="text" name="street" id="street" placeholder="Enter street address" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
                <div>
                    <label for="additional-address" class="block text-sm font-medium text-gray-700">Additional Address</label>
                    <input type="text" name="additional-address" id="additional-address" placeholder="Enter additional address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="postal-code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                        <input type="text" name="postal-code" id="postal-code" placeholder="Enter postal code" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="city" id="city" placeholder="Enter city name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                    </div>
                </div>
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <input type="text" name="country" id="country" placeholder="Enter country name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
            </div>

            <!-- Contact Details -->
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
                <div class="flex items-center space-x-2">
                    <label for="mobile-number" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                    <select name="country-code" class="mt-1 px-3 py-2 border border-gray-300 rounded-md">
                        <option value="+1">+1 (USA)</option>
                        <option value="+44">+44 (UK)</option>
                        <option value="+91">+92 (Pakistan)</option>
                        <option value="+49">+49 (Germany)</option>
                        <option value="+61">+61 (Australia)</option>
                        <!-- Add more options as necessary -->
                    </select>
                    <input type="text" name="mobile-number" id="mobile-number" placeholder="Enter mobile number e.g: +923112345678" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
                <div>
                    <label for="relationship" class="block text-sm font-medium text-gray-700">Relationship to Applicant</label>
                    <input type="text" name="relationship" id="relationship" placeholder="Enter relationship" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
                <div>
                    <label for="contract-details" class="block text-sm font-medium text-gray-700">Contract Details</label>
                    <input type="text" name="contract-details" id="contract-details" placeholder="Enter contract details" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
                <div>
                    <label for="contact-person" class="block text-sm font-medium text-gray-700">Contact Person</label>
                    <input type="text" name="contact-person" id="contact-person" placeholder="Enter contact person's name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
            </div>

            <!-- Document and Bank Details -->
            <div class="space-y-4">
                <div>
                    <label for="document" class="block text-sm font-medium text-gray-700">Upload Document</label>
                    <input type="file" name="document" id="document" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"/>
                </div>
                <div>
                    <label for="bank-name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                    <input type="text" name="bank-name" id="bank-name" placeholder="Enter bank name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
                <div>
                    <label for="bank-street" class="block text-sm font-medium text-gray-700">Bank Street Address</label>
                    <input type="text" name="bank-street" id="bank-street" placeholder="Enter bank street address" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="bank-postal-code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                        <input type="text" name="bank-postal-code" id="bank-postal-code" placeholder="Enter postal code" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                    </div>
                    <div>
                        <label for="bank-city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="bank-city" id="bank-city" placeholder="Enter city" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm"/>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <button type="submit" class="px-6 py-3 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-500">Next</button>
            </div>
        </form>
    </main>

</body>
</html>
