
<?php

require './SourceController/HotelController.php';
$HotelController = new HotelController();

$table = $_GET['table'];
$condition = urldecode($_GET["condition"]);
$cond_enc = urlencode($condition);
$view_cond = str_replace('=',':',$condition);
$view_cond = str_replace(';','',$view_cond);
$view_cond = str_replace('*','',$view_cond);
$view_cond = str_replace('\'','',$view_cond);



$list .= $HotelController->listView($_GET['table'], $condition);

$title = "List $table";
$content = '<head>
  <title>HTML Reference</title>
</head>'.$list;
include 'Template.php';
?>
