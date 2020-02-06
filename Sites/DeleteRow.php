<?php

require './SourceController/HotelController.php';
$HotelController = new HotelController();
//header("location:javascript://history.go(-1)");

//$pkv = array();
//$pks = array();
$pks =unserialize(urldecode($_GET['pk']));
$pkv = unserialize(urldecode($_GET['pkv']));
$content = $HotelController->DeleteRow($_GET['table'],$pks,$pkv );
$url = $_SERVER['HTTP_REFERER']; // right back to the referrer page from where you came.
echo '<meta http-equiv="refresh" content="0;URL=' . $url . '">';
?>