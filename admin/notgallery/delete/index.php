<?php
require_once('../../../pdo.php');
session_start();

if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../login/');
  exit();
}

if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $stmt = $pdo->prepare("SELECT * FROM links WHERE id=?");
  $stmt->execute([$id]);
  $entry = $stmt->fetch();
  if (!$entry) {
    header('Location: ../');
    exit();
  } 

  $pdo->prepare("DELETE FROM links WHERE id=?")->execute([$id]);

  $filename = $entry['id'].'.'.$entry['format'];
  unlink("../../../assets/linkimgs/$filename");

  $_SESSION['success'] = 'Запись удалена!';
}

header('Location: ../');
exit();
?>