<?php
$image_name = $_FILES['image']['name'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$format;
if (strpos($image_name, '.jpg') !== false
|| strpos($image_name, '.jpeg') !== false) $format = 'jpg';
else $format = 'png';

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

$dest = "../../../assets/tmp/$id.$format";
move_uploaded_file($image_tmp_name, $dest);
$size = getimagesize($dest);
$widthor = $size[0];
$heightor = $size[1];
$width = $widthor;
$height = $heightor;
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
if ($format === 'jpg') {
  $im = imagecreatefromjpeg($dest);
  $im_pic = imagescale($im, $width);
  imagejpeg($im_pic, "../../../assets/linkimgs/$id.$format");
}
else {
  $im = imagecreatefrompng($dest);
  $im_pic = imagescale($im, $width);
  imagepng($im_pic, "../../../assets/linkimgs/$id.$format");
}
unlink($dest);
imagedestroy($im);
?>