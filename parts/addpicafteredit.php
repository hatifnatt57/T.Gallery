<?php
function notemptynorid($val, $key) {
  return ($val !== '' && $key !== 'id');
}

$image_name = $_FILES['image']['name'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$dest = "../../assets/tmp/$image_name";
move_uploaded_file($image_tmp_name, $dest);
$format;
if (strpos($image_name, '.jpg') !== false
|| strpos($image_name, '.jpeg') !== false) $format = 'jpg';
else $format = 'png';
$size = getimagesize($dest);
$widthor = $size[0];
$heightor = $size[1];
$width = $widthor;
$height = $heightor;
$coef = $height / $width;
if ($width > 1920) {
  $width = 1920;
  $height = $width * $coef;
}
if ($height > 1080) {
  $height = 1080;
  $width = $height / $coef;
}

$data = [
  'title' => $_POST['title'],
  'size' => $_POST['size'],
  'technique' => $_POST['technique'],
  'year' => $_POST['year'],
  'description' => $_POST['description'],
  'format' => $format,
  'category' => $_POST['category'],
  'search' => $search,
  'orderint' => $entry['orderint'],
  'home_orderint' => $entry['home_orderint'],
  'title_en' => $_POST['title_en'],
  'technique_en' => $_POST['technique_en'],
  'size_en' => $_POST['size_en'],
  'description_en' => $_POST['description_en'],
  'search_en' => $search_en
];
$query = "INSERT INTO pics
(
  title,
  size,
  technique,
  year,
  description,
  format,
  category,
  search,
  orderint,
  home_orderint,
  title_en,
  technique_en,
  size_en,
  description_en,
  search_en
)
VALUES
(
  :title,
  :size,
  :technique,
  :year,
  :description,
  :format,
  :category,
  :search,
  :orderint,
  :home_orderint,
  :title_en,
  :technique_en,
  :size_en,
  :description_en,
  :search_en
)
";
$pdo->prepare($query)->execute($data);
$id = $pdo->lastInsertId();
$square = 500;
$startx = $widthor / 2 - $square / 2;
$starty = $heightor / 2 - $square / 2;

if ($format === 'jpg') {
  $im = imagecreatefromjpeg($dest);
  $im_pic = imagescale($im, $width);
  imagejpeg($im_pic, "../../assets/pics/$id.$format");
  $im_cropped = imagecrop($im, ['x' => $startx, 'y' => $starty, 'width' => $square, 'height' => $square]);
  $im_icon = imagescale($im_cropped, 100);
  imagejpeg($im_icon, "../../assets/icons/$id.$format");
  unlink($dest);
  imagedestroy($im);
}
else {
  $im = imagecreatefrompng($dest);
  $im_pic = imagescale($im, $width);
  imagepng($im_pic, "../../assets/pics/$id.$format");
  $im_cropped = imagecrop($im, ['x' => $startx, 'y' => $starty, 'width' => $square, 'height' => $square]);
  $im_icon = imagescale($im_cropped, 100);
  imagepng($im_icon, "../../assets/icons/$id.$format");
  unlink($dest);
  imagedestroy($im);
}
?>