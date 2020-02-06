
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

if($table != 'rental_contract' && $table != 'booking') {
    $list = "<a href='AddRow.php?table=$table&condition=$cond_enc'>Add new $table";
    if ($view_cond !== " "){
        $list .= " for $view_cond";
    }
    $list .= "</a>";
}

$list .= $HotelController->listAll($_GET['table'], $condition);
//echo $condition;

$title = "List $table";
$content = '<head>
  <title>HTML Reference</title>
</head>'.$list;
include 'Template.php';
?>
