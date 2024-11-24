<?php
$image_name = $_FILES['image']['name'];
$image_tmp_name = $_FILES['image']['tmp_name'];
$dest = "../../assets/tmp/$image_name";
move_uploaded_file($image_tmp_name, $dest);
$format;
if (strpos(strtolower($image_name), '.jpg') !== false
|| strpos(strtolower($image_name), '.jpeg') !== false) $format = 'jpg';
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

switch ($_POST['category']) {
  case 'Графика':
    $category_en = 'Graphics';
    break;
  
  case 'Пастель':
    $category_en = 'Pastel';
    break;

  case 'Акрил':
    $category_en = 'Acrylic';
    break;
}

$post_ru = array(
  $_POST['title'],
  $_POST['year'],
  $_POST['category'],
  $_POST['technique'],
  $_POST['size'],
  $_POST['description']
);

$post_en = array(
  $_POST['title_en'],
  $_POST['year'],
  $category_en,
  $_POST['technique_en'],
  $_POST['size_en'],
  $_POST['description_en']
);

$search_ru = join(' ', array_filter($post_ru, function($val) { return $val !== ''; }));
$search_en = join(' ', array_filter($post_en, function($val) { return $val !== ''; }));

$data = [
  'title' => $_POST['title'],
  'size' => $_POST['size'],
  'technique' => $_POST['technique'],
  'year' => $_POST['year'],
  'description' => $_POST['description'],
  'format' => $format,
  'category' => $_POST['category'],
  'search' => $search_ru,
  'orderint' => 0,
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