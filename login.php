<?php
session_start();
$error = "";

$conn = new mysqli("localhost", "root", "", "webforshop");
if ($conn->connect_error) {
  die("Database connection failed");
}

if (isset($_POST['login'])) {

  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  $sql = "SELECT * FROM admin_users WHERE username='$username' LIMIT 1";
  $result = $conn->query($sql);

  if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // plain password check (stable version)
    if ($password === $row['password']) {
      $_SESSION['admin'] = true;
      header("Location: dashboard.php");
      exit();
    }
  }

  $error = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <style>
    body{
      background:#0b0f1a;
      color:#fff;
      font-family:Poppins;
    }
    form{
      width:300px;
      margin:140px auto;
      background:#11162a;
      padding:30px;
      border-radius:15px;
      text-align:center;
    }
    input{
      width:240px;
      padding:10px;
      margin:10px auto;
      display:block;
      border-radius:8px;
      border:none;
    }
    button{
      width:140px;
      padding:10px;
      border-radius:20px;
      border:none;
      background:#4dd0ff;
      font-weight:600;
      cursor:pointer;
    }
    p{color:red;}
  </style>
</head>
<body>

<form method="post">
  <h2>Admin Login</h2>

  <?php if ($error) { ?>
    <p><?php echo $error; ?></p>
  <?php } ?>

  <input type="text" name="username" placeholder="Username" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit" name="login">Login</button>
</form>

</body>
</html>
