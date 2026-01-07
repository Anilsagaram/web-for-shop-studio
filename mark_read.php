<?php
session_start();
if(!isset($_SESSION['admin'])){
  header("Location: login.php"); exit();
}

$conn = new mysqli("localhost","root","","webforshop");
$id = intval($_GET['id']);
$conn->query("UPDATE contact_messages SET status='read' WHERE id=$id");
header("Location: dashboard.php");
