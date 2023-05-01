<?php
$image_name = $_FILES['image']['name'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$image = imagecreatefromstring(file_get_contents($image_tmp_name));
$exif = exif_read_data($image_tmp_name);
if(!empty($exif['Orientation'])) {
  switch($exif['Orientation']) {
    case 8:
      $image = imagerotate($image,90,0);
      break;
    case 3:
      $image = imagerotate($image,180,0);
      break;
    case 6:
      $image = imagerotate($image,-90,0);
      break;
  }
}
$format;
if (strpos($image_name, '.jpg') !== false
|| strpos($image_name, '.jpeg') !== false) $format = 'jpg';
else $format = 'png';

if (!$orderint) {
  $orderint = 0;
}

$data = [
  'format' => $format,
  'text' => $_POST['text'],
  'orderint' => $orderint
];
$query = "INSERT INTO links
(
  format,
  text,
  orderint
)
VALUES
(
  :format,
  :text,
  :orderint
)
";
$pdo->prepare($query)->execute($data);
$id = $pdo->lastInsertId();

$width = imagesx($image);
$height = imagesy($image);
$coef = $height / $width;
if ($width < 1920 && $height < 1080) {
  //
}
else {
  if ($width > 1920) {
    $width = 1920;
    $height = $width * $coef;
  }
  if ($height > 1080) {
    $height = 1080;
    $width = $height / $coef;
  }
}
$image_scaled = imagescale($image, $width);

if ($format === 'jpg') {
  imagejpeg($image_scaled, "../../../assets/linkimgs/$id.$format");
}
else {
  imagepng($image_scaled, "../../../assets/linkimgs/$id.$format");
}
imagedestroy($image);
?>