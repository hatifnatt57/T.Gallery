<?php
require_once('../../pdo.php');
session_start();

if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../admin/login/');
}

if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $stmt = $pdo->prepare("SELECT * FROM pics WHERE id=?");
  $stmt->execute([$id]);
  $entry = $stmt->fetch();
  if (!$entry) {
    header('Location: ../../admin/');
    exit();
  } 

  $pdo->prepare("DELETE FROM pics WHERE id=?")->execute([$id]);

  $filename = $entry['id'].'.'.$entry['format'];
  unlink("../../assets/pics/$filename");
  unlink("../../assets/icons/$filename");

  $_SESSION['success'] = 'Запись удалена!';
}

header('Location: ../../admin/');
exit();
?>