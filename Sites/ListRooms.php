
<?php

require './SourceController/HotelController.php';
$HotelController = new HotelController();
$customer = $_GET['customer'];
$employee = $_GET['employee'];

$table = $_GET['table'];
$array = unserialize(urldecode($_GET['values']));
$list .= $HotelController->list_room_search($array, $_GET['table'], $customer, $employee);

$title = "Available Rooms";
$content = '<head>
  <title>HTML Reference</title>
</head>'.$list;
include 'Template.php';
?>
