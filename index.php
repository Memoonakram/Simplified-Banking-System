<?php
session_start();
include 'db.php'; // Ensure db.php establishes a connection using $conn

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['sendOtp'])) {
        // Sanitize user inputs
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Use password hashing for better security (e.g., password_hash and password_verify)
            if (password_verify($password, $user['password'])) {
                $otp = rand(100000, 999999);
                $_SESSION['otp'] = $otp;
                $_SESSION['email'] = $email;
                $_SESSION['id'] = $user['id'];

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'ebraheemgillani1@gmail.com'; // Replace with your email
                    $mail->Password = 'dhss amdt lygs gxgy'; // Use a secure method to handle credentials
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('ebraheemgillani1@gmail.com', 'Your Name');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = "Your OTP Code";
                    $mail->Body = "Your OTP code is: <strong>$otp</strong>";

                    $mail->send();
                    $_SESSION['success'] = "OTP sent to your email.";
                    echo "<script>window.onload = function() { showOtpVerificationForm(); }</script>";
                } catch (Exception $e) {
                    $_SESSION['error'] = "Failed to send OTP. Please try again.";
                    unset($_SESSION['email']);
                    unset($_SESSION['id']);
                }
            } else {
                $_SESSION['error'] = "Invalid password.";
                unset($_SESSION['email']);
                unset($_SESSION['id']);
            }
        } else {
            $_SESSION['error'] = "No account found with that email.";
            unset($_SESSION['email']);
            unset($_SESSION['id']);
        }
        $stmt->close();
    } elseif (isset($_POST['verifyOtp'])) {
        $enteredOtp = $_POST['otp'];

        if ($enteredOtp == $_SESSION['otp']) {
            $_SESSION['success'] = "OTP verified. You are logged in.";
            unset($_SESSION['otp']);
            $id = $_SESSION['id'];

            $query = "SELECT role FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $_SESSION['role'] = $row['role'];

                // Set session expiration time for 3 days (259200 seconds)
                $_SESSION['expire'] = time() + (3 * 24 * 60 * 60);

                // Clear any output buffer to avoid header issues
                ob_end_clean();

                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($_SESSION['role'] === 'user') {
                    header("Location: user_dashboard.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "User role not found. Please contact support.";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid OTP.";
            echo "<script>window.onload = function() { showOtpVerificationForm(); }</script>";
        }
    } elseif (isset($_POST['forgotPassword'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ebraheemgillani1@gmail.com';
                $mail->Password = 'dhss amdt lygs gxgy';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('ebraheemgillani1@gmail.com', 'Your Name');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = "Password Reset OTP";
                $mail->Body = "Your OTP for password reset is: <strong>$otp</strong>";

                $mail->send();
                $_SESSION['success'] = "OTP sent to your email for password reset.";
                echo "<script>window.onload = function() { showOtpResetForm(); }</script>";
            } catch (Exception $e) {
                $_SESSION['error'] = "Failed to send OTP. Please try again.";
            }
        } else {
            $_SESSION['error'] = "No account found with that email.";
        }
        $stmt->close();
    } elseif (isset($_POST['resetPassword'])) {
        $enteredOtp = $_POST['otp'];
        $newPassword = $_POST['new_password'];

        if ($enteredOtp == $_SESSION['otp']) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashedPassword, $_SESSION['email']);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Password reset successful. You can now log in with your new password.";
                unset($_SESSION['otp'], $_SESSION['email']);
            } else {
                $_SESSION['error'] = "Failed to reset password. Please try again.";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Invalid OTP for password reset.";
            echo "<script>window.onload = function() { showOtpResetForm(); }</script>";
        }
    } elseif (isset($_POST['resendOtp'])) {
        if (isset($_SESSION['email'])) {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'ebraheemgillani1@gmail.com'; // Replace with your email
                $mail->Password = 'dhss amdt lygs gxgy'; // Use a secure method to handle credentials
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('ebraheemgillani1@gmail.com', 'Your Name');
                $mail->addAddress($_SESSION['email']);
                $mail->isHTML(true);
                $mail->Subject = "Your OTP Code";
                $mail->Body = "Your OTP code is: <strong>$otp</strong>";

                $mail->send();
                $_SESSION['success'] = "OTP resent to your email.";
                echo "<script>window.onload = function() { showOtpVerificationForm(); }</script>";
            } catch (Exception $e) {
                $_SESSION['error'] = "Failed to resend OTP. Please try again.";
            }
        } else {
            $_SESSION['error'] = "No session found. Please login again.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Login / Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-white-100 flex items-center justify-center min-h-screen w-full h-full overflow-hidden">
    <div class="bg-white shadow-lg flex w-full h-full">
        <div class="w-1/2 h-full bg-white-800 relative">
            <img src="logo123.jpg" alt="Background" class="absolute inset-0 w-full h-full object-cover"/>
        </div>
        <div class="w-1/2 p-8 flex flex-col justify-center items-center h-full overflow-y-auto">
        <div class="flex flex-col items-center">
    <!-- <img alt="" class="mb-4" height="100" src="logo.jpeg" width="300" height="100"/> -->
</div>

                <p class="text-center text-gray-600 mb-6">Payment services for Suisse Capital are provided by Equals Connect Limited</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form id="loginForm" class="w-full max-w-sm" action="index.php" method="POST">
                    <div class="mb-4">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600" placeholder="Email" type="email" name="email" required/>
                    </div>
                    <div class="mb-4">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600" placeholder="Password" type="password" name="password" required/>
                    </div>
                    <button class="w-full bg-yellow-600 text-white py-2 rounded-lg" type="submit" name="sendOtp">Send OTP</button>
                    <div class="flex justify-between w-full max-w-sm mt-4">
                        <a href="#" id="forgotPasswordLink" class="text-teal-600" onclick="showForgotPasswordForm()">Forgot Password?</a>
                    </div>
                </form>

                <!-- Forgot Password Form -->
                <form id="forgotPasswordForm" class="w-full max-w-sm hidden" action="index.php" method="POST">
                    <div class="mb-4">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600" placeholder="Enter your email" type="email" name="email" required/>
                    </div>
                    <button class="w-full bg-teal-600 text-white py-2 rounded-lg" type="submit" name="forgotPassword">Send OTP for Password Reset</button>
                </form>

                <!-- OTP Verification Form -->
                <form id="otpVerificationForm" class="w-full max-w-sm hidden" action="index.php" method="POST">
                    <div class="mb-4">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600" placeholder="Enter OTP" type="text" name="otp"/>
                    </div>
                    <button class="w-full bg-teal-600 text-white py-2 rounded-lg" type="submit" name="verifyOtp">Verify OTP</button>
                    <!-- Resend OTP Button -->
                    <button class="w-full bg-gray-500 text-white py-2 mt-2 rounded-lg" name="resendOtp">Resend OTP</button>
                </form>

                <!-- OTP Reset Password Form -->
                <form id="otpResetForm" class="w-full max-w-sm hidden" action="index.php" method="POST">
                    <div class="mb-4">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600" placeholder="Enter OTP" type="text" name="otp" required/>
                    </div>
                    <div class="mb-4">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600" placeholder="New Password" type="password" name="new_password" required/>
                    </div>
                    <button class="w-full bg-teal-600 text-white py-2 rounded-lg" type="submit" name="resetPassword">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function showForgotPasswordForm() {
        document.getElementById('loginForm').classList.add('hidden');
        document.getElementById('forgotPasswordForm').classList.remove('hidden');
    }

    function showOtpVerificationForm() {
        document.getElementById('loginForm').classList.add('hidden');
        document.getElementById('otpVerificationForm').classList.remove('hidden');
    }

    function showOtpResetForm() {
        document.getElementById('forgotPasswordForm').classList.add('hidden');
        document.getElementById('otpResetForm').classList.remove('hidden');
        document.getElementById('loginForm').classList.add('hidden'); // Ensure login form stays hidden
    }
</script>

</body>
</html>

