<?php
$image_name = $_FILES['image']['name'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$format;
if (strpos($image_name, '.jpg') !== false
|| strpos($image_name, '.jpeg') !== false) $format = 'jpg';
else $format = 'png';

if ($_POST['resourse_url'] === '') {
  $resourse_name = $_FILES['resourse']['name'];
  $resourse_tmp_name = $_FILES['resourse']['tmp_name'];
  $dest = "../../../assets/resourses/$resourse_name";
  move_uploaded_file($resourse_tmp_name, $dest);
  $resourse_url = $resourse_name;
}
else {
  $resourse_url = $_POST['resourse_url'];
}

$data = [
  'resourse_url' => $resourse_url,
  'format' => $format,
  'text' => $_POST['text']
];
$query = "INSERT INTO links
(
  resourse_url,
  format,
  text
)
VALUES
(
  :resourse_url,
  :format,
  :text
)
";
$pdo->prepare($query)->execute($data);
$id = $pdo->lastInsertId();

$dest = "../../../assets/linkimgs/$id.$format";
move_uploaded_file($image_tmp_name, $dest);
?>