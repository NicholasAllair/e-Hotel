<?php

require './SourceController/HotelController.php';
$HotelController = new HotelController();
$table = $_GET['table'];

$title = "Add $table";
$condition = urldecode($_GET["condition"]);
$content = $HotelController->createEmptyForm($_GET['table'], $condition );
if (isset($_POST['submit'])) {
    $HotelController->AddRow($_GET['table']);
    //echo '<meta http-equiv="refresh" content="0;URL=' . $url . '">';
}

include './Template.php';
?>



