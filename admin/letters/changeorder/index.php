<?php
require_once('../../../pdo.php');
session_start();

# Login
if ($_SESSION['logged_in'] !== 'true') {
  header('Location: ../../login/');
  exit();
}

if (isset($_POST['order'])) {
  $data = json_decode($_POST['order'], true);
  print_r($data);
  foreach ($data['links'] as $key => $value) {
    $query = "UPDATE letters SET orderint=? WHERE id=?";
    $pdo->prepare($query)->execute([$key + 1, $value]);
  }

  $_SESSION['success'] = 'Новый порядок установлен!';
}

header('Location: ../');
exit();
?>