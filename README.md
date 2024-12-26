# Simplified Banking Project 

# Overview
A feature-rich banking web app offering secure OTP login, a dynamic dashboard, and seamless form management. Perfectly integrates Trade Finance and Account Activation with smooth data flow and admin oversight.

# Key Features
## For Users
OTP Login: Secure access with email verification.
Dashboard Access:
My Account: Manage account settings.
My Documents: View, edit, or delete uploaded files (PDF).
Account Activation: Complete activation via form.
Trade Finance: Submit forms for:
Bank Guarantee (BG)
Standby Letter of Credit (SBLC)
Documentary Letter of Credit (LC)
Proof of Funds (POF)
Warranty (AVAL)
## For Admins
Exclusive Registration Page: Hidden from users; autosaves drafts.
Data Reports: Receive user activity updates via email.


## Website Highlights
Responsive Design: Mobile and desktop-friendly.
Demo-Based Structure: Up to 10 pages with placeholder content.
Theme Compliance: Matches Alliance Digital colors.


## Tech Stack
Frontend: React.js/HTML/CSS
Backend: Node.js/Express.js
Database: MongoDB/Hostinger SQL
Email Services: SMTP for OTP and notifications
Hosting: Hostinger



## Setup Guide
Clone Repository:

## git clone <repository_url>  
## cd simplified-banking-project  
## Install Dependencies:

## npm install  
## Configure Environment: Add .env file:

 
**DB_URI=<database_connection_string>  
EMAIL_SERVICE=<your_email_service>  
EMAIL_USER=<your_email_address>  
EMAIL_PASS=<your_email_password>**

  
## Run Application:

npm start  
Access: Open http://localhost:3000.


## Data Flow
Autosaved Forms: Registration, Account Activation, and Trade Finance.
Dashboard Management:
My Account: User details.
My Documents: Uploaded files.
Trade Finance: Submitted forms.
Admin Reports: Email notifications for user activities.

