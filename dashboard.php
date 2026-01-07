<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}

$conn = new mysqli("localhost","root","","webforshop");
if ($conn->connect_error) {
  die("Database connection failed");
}

/* COUNTS */
$totalRow  = $conn->query("SELECT COUNT(*) AS c FROM contact_messages");
$total     = $totalRow ? $totalRow->fetch_assoc()['c'] : 0;

$unreadRow = $conn->query("SELECT COUNT(*) AS c FROM contact_messages WHERE status='unread'");
$unread    = $unreadRow ? $unreadRow->fetch_assoc()['c'] : 0;

$readRow   = $conn->query("SELECT COUNT(*) AS c FROM contact_messages WHERE status='read'");
$read      = $readRow ? $readRow->fetch_assoc()['c'] : 0;

/* MESSAGES */
$result = $conn->query(
  "SELECT * FROM contact_messages 
   ORDER BY status='unread' DESC, id DESC"
);
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard v2.0 | WebForShop</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;font-family:Poppins}
body{margin:0;background:#0b0f1a;color:#fff;padding:30px}

.top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:25px
}

h2{color:#4dd0ff}

.btn{
  background:#4dd0ff;
  color:#000;
  padding:8px 16px;
  border-radius:20px;
  text-decoration:none;
  font-weight:600;
  margin-left:10px
}

/* STATS */
.stats{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
  gap:20px;
  margin-bottom:35px
}
.stat{
  background:#11162a;
  padding:25px;
  border-radius:18px;
  text-align:center
}
.stat p{color:#cfd8ff;font-size:14px;margin-bottom:8px}
.stat h3{margin:0;font-size:28px}
.blue{color:#4dd0ff}
.orange{color:#ffb74d}
.green{color:#7cff8b}

/* TABLE */
table{
  width:100%;
  border-collapse:collapse;
  background:#11162a;
  border-radius:18px;
  overflow:hidden
}
th,td{
  padding:14px;
  border-bottom:1px solid #1f2645;
  font-size:14px;
  text-align:left
}
th{color:#4dd0ff}
tr.unread{background:#1a2340}

.action a{
  text-decoration:none;
  margin-right:10px;
  font-weight:600
}
.read{color:#4dd0ff}
.delete{color:#ff6b6b}
</style>
</head>

<body>

<!-- HEADER -->
<div class="top">
  <h2>
    Contact Messages
    <?= $unread > 0 ? "<span style='color:#ffb74d;font-size:14px'> ($unread new)</span>" : "" ?>
  </h2>
  <div>
    <a class="btn" href="change_password.php">Change Password</a>
    <a class="btn" href="logout.php">Logout</a>
  </div>
</div>

<!-- STATS -->
<div class="stats">
  <div class="stat">
    <p>Total Messages</p>
    <h3 class="blue"><?= $total ?></h3>
  </div>
  <div class="stat">
    <p>Unread Messages</p>
    <h3 class="orange"><?= $unread ?></h3>
  </div>
  <div class="stat">
    <p>Read Messages</p>
    <h3 class="green"><?= $read ?></h3>
  </div>
</div>

<!-- TABLE -->
<table>
<tr>
  <th>Name</th>
  <th>Email</th>
  <th>Message</th>
  <th>Date</th>
  <th>Action</th>
</tr>

<?php if($result && $result->num_rows>0){
while($row=$result->fetch_assoc()){
$class = ($row['status']=="unread") ? "unread" : "";
?>
<tr class="<?= $class ?>">
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= htmlspecialchars($row['email']) ?></td>
  <td><?= htmlspecialchars($row['message']) ?></td>
  <td><?= $row['created_at'] ?></td>
  <td class="action">
    <?php if($row['status']=="unread"){ ?>
      <a class="read" href="mark_read.php?id=<?= $row['id'] ?>">Mark Read</a>
    <?php } ?>
    <a class="delete"
       href="delete.php?id=<?= $row['id'] ?>"
       onclick="return confirm('Delete this message?')">
       Delete
    </a>
  </td>
</tr>
<?php }} else { ?>
<tr><td colspan="5">No messages found</td></tr>
<?php } ?>
</table>

</body>
</html>
