<?php
if (!$orderint) {
  $orderint = 0;
}

$data = [
  'text' => $_POST['text'],
  'author' => $_POST['author'],
  'date' => !empty($_POST['date']) ? $_POST['date'] : NULL,
  'orderint' => $orderint
];
$query = "INSERT INTO letters
(
  text,
  author,
  date,
  orderint
)
VALUES
(
  :text,
  :author,
  :date,
  :orderint
)
";
$pdo->prepare($query)->execute($data);
?>