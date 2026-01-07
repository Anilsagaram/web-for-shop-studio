<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php"); exit();
}

$conn = new mysqli("localhost","root","","webforshop");
$msg = "";

if (isset($_POST['change'])) {
  $old = $_POST['old'];
  $new = $_POST['new'];

  $check = $conn->query(
    "SELECT * FROM admin_users 
     WHERE username='admin' AND password='$old'"
  );

  if ($check->num_rows == 1) {
    $conn->query(
      "UPDATE admin_users 
       SET password='$new' 
       WHERE username='admin'"
    );
    $msg = "Password changed successfully";
  } else {
    $msg = "Old password is wrong";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>
<style>
body{background:#0b0f1a;color:#fff;font-family:Poppins}
form{width:300px;margin:140px auto;background:#11162a;padding:30px;border-radius:15px;text-align:center}
input{width:240px;padding:10px;margin:10px auto;display:block;border-radius:8px;border:none}
button{width:160px;padding:10px;border-radius:20px;border:none;background:#4dd0ff;font-weight:600}
p{color:#4dd0ff}
</style>
</head>
<body>

<form method="post">
<h2>Change Password</h2>
<?php if($msg) echo "<p>$msg</p>"; ?>
<input type="password" name="old" placeholder="Old Password" required>
<input type="password" name="new" placeholder="New Password" required>
<button name="change">Change</button>
</form>

</body>
</html>
