
<?php

require './SourceController/HotelController.php';
$HotelController = new HotelController();

$content = $HotelController->ChooseHotel();

if (isset($_POST['submit'])) {

    $hotel = $_POST['hotel'];
    $hotel = urlencode("name = '$hotel'");
        $url = "ListView.php?table=room_sizes_available&condition=$hotel";
    echo "<meta http-equiv='refresh' content='0;url=$url'>";
    exit();

}
$title = 'Please choose a Hotel';
$content = '<head>
  <title>HTML Reference</title>
</head>'.$content;
include 'Template.php';
?>
