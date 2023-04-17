<?php 
session_start();
if (isset($_SESSION['logged_in'])) {
  if ($_SESSION['logged_in'] === 'true') {
    unset($_SESSION['logged_in']);
  }
}

header('Location: ../../admin/');
exit();
?>