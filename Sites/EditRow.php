<?php

require './SourceController/HotelController.php';
$HotelController = new HotelController();
$table = $_GET['table'];

$title = "Edit $table";

//$pkv = array();
//$pks = array();
$pks =unserialize(urldecode($_GET['pk']));
$pkv = unserialize(urldecode($_GET['pkv']));
$content = $HotelController->createPopulatedForm($_GET['table'],$pks,$pkv );
if (isset($_POST['submit'])) {
    $HotelController->EditRow($_GET['table'], $pks, $pkv);
    //window.history.goto(-1);
    //echo "<meta http-equiv='refresh' content='0'>";
}

include './Template.php';
?>


