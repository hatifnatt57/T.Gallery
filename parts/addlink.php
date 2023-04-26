<?php
$image_name = $_FILES['image']['name'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$format;
if (strpos($image_name, '.jpg') !== false
|| strpos($image_name, '.jpeg') !== false) $format = 'jpg';
else $format = 'png';

$data = [
  'format' => $format,
  'text' => $_POST['text']
];
$query = "INSERT INTO links
(
  format,
  text
)
VALUES
(
  :format,
  :text
)
";
$pdo->prepare($query)->execute($data);
$id = $pdo->lastInsertId();

$dest = "../../../assets/linkimgs/$id.$format";
move_uploaded_file($image_tmp_name, $dest);
?>