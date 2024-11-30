<?php
require_once('../../../pdo.php');
session_start();

if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../login/');
  exit();
}

if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $stmt = $pdo->prepare("SELECT * FROM letters WHERE id=?");
  $stmt->execute([$id]);
  $entry = $stmt->fetch();
  if (!$entry) {
    header('Location: ../');
    exit();
  } 

  $pdo->prepare("DELETE FROM letters WHERE id=?")->execute([$id]);

  $_SESSION['success'] = 'Запись удалена!';
}

header('Location: ../');
exit();
?>