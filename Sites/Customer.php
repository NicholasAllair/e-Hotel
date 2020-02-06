
<?php


$email = $_GET['email'];
$condition = "customer='$email';";
$cond_enc = urlencode($condition);
$email = urlencode($email);
$cond_num = urlencode(" *");

$content = "<table><tr><td><a href='ListAll.php?table=booking&condition=$cond_enc'>My Bookings\n</a></td></tr>";
$content .= "<tr><td><a href='FindRoom.php?table=booking&customer=$email'>Find a room</a></td></tr>";
$content .= "<tr><td><a href='ListView.php?table=number_of_rooms_per_area&condition=$cond_num'>Number of rooms per area</a></td></tr></table>";
$content .= "<tr><td><a href='CapacityList.php'>View room capacities per hotel</a></td></tr></table>";

$title = "User: $email";

include 'Template.php';
?>
