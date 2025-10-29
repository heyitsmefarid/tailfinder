# 🐾 TailFinder: Pet Adoption Management System

TailFinder is a **web-based pet adoption management system** designed to simplify the process of adopting pets and managing adoption-related data.  
It bridges the communication gap between **adopters** and **administrators**, making the adoption process more efficient, transparent, and accessible.

## 📘 Project Overview

TailFinder serves as a digital platform where:
- **Adopters** can register, browse available pets, and submit adoption requests.
- **Administrators** can manage pet records, approve adoptions, and handle user inquiries.

The system ensures a **centralized**, **automated**, and **user-friendly** environment for managing pet adoption workflows.
## 🌟 Features Implemented
### 👩‍💻 Adopter Module
- **User Registration & Login**
  - Secure authentication using email and password.
- **Pet Browsing**
  - View all available pets with images, breeds, and other details.
- **Adoption Requests**
  - Submit requests to adopt pets.
  - Check status of requests: *Pending*, *Approved*, or *Rejected*.
- **Inquiry System**
  - Send inquiries to the admin for assistance or questions.
- **Profile Management**
  - Update contact details, password, and personal info.

### 🧑‍🏫 Administrator Module
- **Pet Management**
  - Add, edit, delete pet records.
  - Upload pet images and descriptions.
- **Adoption Management**
  - Review and approve/reject adoption requests.
  - Update pet status automatically upon approval.
- **User Management**
  - View all adopters and their information.
- **Inquiry Management**
  - View and reply to adopter inquiries.
- **Reports**
  - Generate and view system reports for:
    - Pets
    - Users
    - Adoptions
    - Inquiry
 - **Edit Admin Profile**
    -  Admin can edit his/her information

## ⚙️ Setup Instructions (How to Run Locally)

Follow these steps to set up TailFinder on your local machine using **XAMPP** or **WAMP**.

### 🔧 Step 1: Clone the Repository
```bash
git clone https://github.com/heyitsmefarid/tailfinder.git
Step 2: Move Files to Server Directory

Move the cloned folder to your server root:

For XAMPP: C:\xampp\htdocs\tailfinder

For WAMP: C:\wamp64\www\tailfinder

🔧 Step 3: Configure the Database

Open phpMyAdmin (http://localhost/phpmyadmin).

Create a new database named:

tailfinder_db


Import the SQL file located in the project root:

tailfinder_db.sql

🔧 Step 4: Update Database Connection

Edit the file db_connect.php:

<?php
function connectDB() {
    $host = "localhost";
    $dbname = "tailfinder_db";
    $username = "root";
    $password = "";
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
?>

🔧 Step 5: Run the Project
Open your browser and navigate to:

http://localhost/tailfinder/index.php

🗃️ Database Structure
user_tbl
Column Name	      Type	                                                  Description
user_id	          INT (PK, AUTO_INCREMENT)	                              Unique identifier for each user
first_name	      VARCHAR(45)	                                            User’s first name
last_name	        VARCHAR(45)	                                            User’s last name
middle_initial	  VARCHAR(45)	                                            Middle initial of the user
email	            VARCHAR(75)	                                            User’s email address (used for login)
password	        VARCHAR(255)	                                          Encrypted user password
contact_number	  VARCHAR(20)	                                            User’s contact number
street	          VARCHAR(75)	                                            Street address
barangay	        VARCHAR(80)	                                            Barangay location
city	            VARCHAR(75)	                                            City location
province	        VARCHAR(75)	                                            Province location
date_registered	  DATE	                                                  Account registration date
role	            ENUM('Admin', 'Adopter')	                              Defines the user’s role
verify_code	      VARCHAR(10)	                                            Code for email verification
status	          ENUM('verified', 'unverified')	                        Email/account verification status

inquiry_tbl
Column Name	      Type	                                                  Description
inquiry_id	      INT (PK, AUTO_INCREMENT)	                              Unique inquiry ID
content	          VARCHAR(999)	                                          Inquiry message content
admin_response	  VARCHAR(999)	                                          Admin’s response to the inquiry
date_sent	        DATE	                                                  Date when inquiry was submitted
user_id	          INT (FK)	                                              ID of the user who sent the inquiry

pet_tbl
Column Name	      Type	                                                  Description
pet_id	          INT (PK, AUTO_INCREMENT)	                              Unique pet ID
pet_name	        VARCHAR(45)                                            	Name of the pet
type	            ENUM('Dog', 'Cat', 'Others')                          	Type of pet
breed	            VARCHAR(45)	                                            Breed of the pet
age	              VARCHAR(5)	                                            Pet age
gender	          INT	Pet gender                                          (1 = Male, 2 = Female)
image	            VARCHAR(255)	                                          Image file path
description	      VARCHAR(255)                                           	Short description about the pet
pet_status	      ENUM('Available', 'Not Available', 'Adopted')	          Availability status of the pet

adoption_request_tbl
Column Name     	Type                                                    Description
request_id	      INT (PK, AUTO_INCREMENT)                              	Unique request ID
application_date	DATE	                                                  Date when adoption request was submitted
adoption_status	  ENUM('Approved', 'Rejected', 'Pending', '')	            Status of the adoption application
user_id	          INT (FK)	                                              ID of the requesting user
pet_id	          INT (FK)	                                              ID of the pet being requested for adoption

PHP Functions and Their Purposes
Function	                                    File	                  Description
connectDB()	                                  db_connect.php	        Establishes a secure PDO connection to MySQL
sanitizeInput($data)	                        functions.php	          Cleans user input and prevents SQL injection
registerUser()	                              register.php	          Inserts new adopter account into user_tbl
verifyUser($email, $code)	                    verify.php	            Verifies user account using verification code
loginUser($email, $password)	                login.php	              Authenticates user credentials and starts a session
fetchAllPets()	                              pets.php	              Retrieves all pets from pet_tbl
getPetById($pet_id)	                          pet_details.php       	Fetches single pet information
submitAdoptionRequest($user_id, $pet_id)	    adopt.php	              Creates an adoption request record
getUserRequests($user_id)	                    adoptions.php	          Displays adoption requests by user
updatePetStatus($pet_id, $status)	            update_pet.php	        Changes pet availability
sendInquiry($user_id, $content)	               inquiry.php	          Saves user inquiry message

API Endpoints and Descriptions
Endpoint	                             Method	            Description
register.php	                         POST	              Registers a new user account
verify.php	                           POST	              Verifies user account via verification code
login.php	                             POST	              Logs in a user and starts a session
pets.php	                             GET	              Fetches all available pets
pet_details.php?id={pet_id}	           GET	              Retrieves specific pet details
adopt.php	                             POST	              Submits a new adoption request
adoptions.php	                         GET	              Retrieves user’s adoption request list
update_pet.php	                       POST	              Updates pet status (admin only)
delete_pet.php	                       POST	              Removes pet record from database
messages.php	                         POST	              Sends an inquiry message
admin_messages.php	                   POST	              Admin sends a reply to user inquiry
reports_adoption.php	                 GET	              Generates adoption reports for admin use
reports_user.php                       GET	              Generates user reports for admin use
reports_pet.php	                       GET	              Generates pet reports for admin use
reports_inquiry.php	                   GET	              Generates inquiry reports for admin use