<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "webforshop");
if ($conn->connect_error) {
  die("Database error");
}

// Get form data
$name    = trim($_POST['name']);
$email   = trim($_POST['email']);
$message = trim($_POST['message']);

// Save to database
$stmt = $conn->prepare(
  "INSERT INTO contact_messages (name, email, message, status, created_at)
   VALUES (?, ?, ?, 'unread', NOW())"
);
$stmt->bind_param("sss", $name, $email, $message);
$stmt->execute();

/* ==========================
   EMAIL NOTIFICATION TO ADMIN
   ========================== */

// 🔴 CHANGE THIS TO YOUR EMAIL
$adminEmail = "anilthipparalla@gmail.com";

$subject = "🚨 New client message received";

$body = "
New message received from WebForShop website:

Name: $name
Email: $email

Message:
$message
";

$headers  = "From: WebForShop <noreply@webforshop.in>\r\n";
$headers .= "Reply-To: $email\r\n";

// Send mail
@mail($adminEmail, $subject, $body, $headers);

// Done
echo "success";
