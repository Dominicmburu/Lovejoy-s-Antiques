### **Project Documentation: Lovejoy's Antiques Evaluation System**

---

## **Project Overview**
The "Lovejoy's Antiques Evaluation System" is a secure web application designed to facilitate antique evaluations. The system enables users to register, log in, submit antique evaluation requests, and upload photos for appraisal. Additionally, administrators can view, manage, and update the status of these evaluation requests.

---

## **Features**
### **1. User Features**
- **User Registration:**
  - Allows users to register by providing their name, email, phone number, and password.
  - Implements password validation and secure storage using hashing.
- **Login and Authentication:**
  - Secure user authentication with password verification.
  - Redirects users to the evaluation request page after login.
- **Password Recovery:**
  - Users can reset their passwords by receiving a secure email link.
  - The link expires after 1 hour for added security.
- **Submit Evaluation Request:**
  - Users can submit detailed antique evaluation requests with photos.
  - Supports file uploads and ensures input validation.

### **2. Admin Features**
- **View Evaluation Requests:**
  - Lists all evaluation requests with details such as user email, request date, object description, and uploaded photo.
- **Manage Requests:**
  - Admins can view, approve, or reject requests with appropriate status updates.
- **Access Control:**
  - Only admins can access the admin dashboard and management features.

### **3. Security Features**
- **Password Hashing:**
  - User passwords are securely hashed before storage using `password_hash()`.
- **CSRF Protection:**
  - CSRF tokens are used for secure form submissions.
- **Email Validation:**
  - Users receive a confirmation email for password recovery.
- **Session Management:**
  - Session-based authentication ensures secure user access.

---

## **Technologies Used**
- **Frontend:**
  - HTML5, CSS3, Bootstrap 5 for responsive design.
- **Backend:**
  - PHP for server-side scripting.
- **Database:**
  - MySQL for data storage and retrieval.
- **Libraries:**
  - PHPMailer for sending emails.
- **Server Environment:**
  - XAMPP (Apache, MySQL, PHP) for local development.

---

## **System Requirements**
- **Operating System:** Windows, macOS, or Linux.
- **Server Environment:** XAMPP (or any PHP and MySQL-supported environment).
- **Browser:** Chrome, Firefox, or any modern browser.
- **PHP Version:** 7.4 or higher.
- **MySQL Version:** 5.7 or higher.

---

## **Installation Guide**

### **Step 1: Download the Project**
1. Download the project `.zip` file.
2. Extract it into a folder.

### **Step 2: Move Project to Server**
1. Copy the extracted project folder into the `htdocs` directory of XAMPP (`C:\xampp\htdocs\`).

### **Step 3: Set Up the Database**
1. Start Apache and MySQL from XAMPP.
2. Navigate to `http://localhost/phpmyadmin`.
3. Create a new database (e.g., `lovejoys_db`).
4. Import the provided `lovejoys_antiques.sql` file into the database.

### **Step 4: Configure Database Connection**
1. Open the `db.php` file in a text editor.
2. Update the database credentials:
   ```php
   $host = 'localhost';
   $username = 'root';
   $password = ''; // Leave blank for XAMPP
   $database = 'lovejoys_db';
   ```

### **Step 5: Run the Project**
1. Open a browser and go to `http://localhost/lovejoys_antiques`.
2. The home page of the application should appear.

---

## **How to Use the Application**

### **1. For Users**
- **Register:**
  - Navigate to the "Register" page and create an account.
- **Login:**
  - Use your registered email and password to log in.
- **Submit Request:**
  - Fill out the "Request Evaluation" form with details about your antique and upload a photo.
- **Password Recovery:**
  - If you forget your password, use the "Forgot Password" link to receive a recovery email.

### **2. For Admins**
- **Login:**
  - Use admin credentials to log in (check `users` table for admin accounts).
- **View Requests:**
  - Access the admin dashboard to view all evaluation requests.
- **Manage Requests:**
  - Approve or reject requests as appropriate.
- **Logout:**
  - Click the "Logout" button to end your session.

---

## **Database Structure**

### **Users Table**
| Column Name    | Data Type   | Description                        |
|----------------|-------------|------------------------------------|
| id             | INT (PK)    | Unique identifier for each user.  |
| name           | VARCHAR(255)| Full name of the user.            |
| email          | VARCHAR(255)| User's email address.             |
| password       | VARCHAR(255)| Hashed password.                  |
| is_admin       | TINYINT(1)  | Indicates if the user is an admin.|
| created_at     | DATETIME    | Account creation timestamp.       |

### **Evaluation Requests Table**
| Column Name    | Data Type   | Description                        |
|----------------|-------------|------------------------------------|
| id             | INT (PK)    | Unique identifier for each request.|
| user_id        | INT (FK)    | ID of the user submitting the request.|
| object_details | TEXT        | Description of the antique object.|
| contact_method | VARCHAR(50) | Preferred contact method.          |
| photo_path     | VARCHAR(255)| Path to the uploaded photo.        |
| request_date   | DATETIME    | Timestamp of the request.          |
| status         | VARCHAR(50) | Current status (Pending/Approved/Rejected).|

### **Password Resets Table**
| Column Name    | Data Type   | Description                        |
|----------------|-------------|------------------------------------|
| id             | INT (PK)    | Unique identifier for the reset request.|
| user_id        | INT (FK)    | ID of the user requesting the reset.|
| token          | VARCHAR(255)| Unique reset token.                |
| expires_at     | DATETIME    | Token expiration time.             |

---

## **Testing**
1. **Registration and Login:**
   - Test user registration and login functionality.
2. **Request Submission:**
   - Submit evaluation requests with valid inputs and verify database updates.
3. **Password Recovery:**
   - Test the password recovery email and reset functionality.
4. **Admin Actions:**
   - Log in as admin and test viewing, approving, and rejecting requests.

---

## **Troubleshooting**
1. **Database Connection Error:**
   - Verify `db.php` credentials and ensure the database is correctly imported.
2. **Email Not Sent:**
   - Check SMTP settings in `send_recovery_email()` function.
   - Ensure the SMTP server is reachable and credentials are correct.
3. **Page Not Found:**
   - Ensure the project folder is in the correct `htdocs` directory.
4. **File Upload Issues:**
   - Verify permissions for the `uploads/` directory.

---

## **Credits**
- Developed by DOMINIC MBURU.
- Libraries used:
  - PHPMailer for email functionality.
  - Bootstrap for responsive design.
- Database: MySQL.

---

