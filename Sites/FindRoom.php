
<?php

require './SourceController/HotelController.php';
$HotelController = new HotelController();

$booking = $_GET['table'];
$content = $HotelController->FindRoom();
$customer = urldecode($_GET['customer']);
$employee = $_GET['employee'];
$customer_condition = "customer = '$customer'";
$customer_condition = urlencode($customer_condition);
if ($employee !== NULL) {
    $content .= "<p><a href='ListAll.php?table=booking&condition=$customer_condition&employee=$employee'>View bookings</a></p>";
    $content .= "<p><a href='ListAll.php?table=rental_contract&condition=$customer_condition'>View rentals</a></p>";
}
if (isset($_POST['submit'])) {
    $array = array();
    foreach($_POST as $key => $value) {
        echo $value;
        $array += [$key => $value];
    }
    unset($array["submit"]);
    $values = urlencode(serialize($array));
    $customer = $_GET['customer'];

    if ($booking == "booking") {
        $url = "ListRooms.php?values=$values&table=$booking&customer=$customer";
    }
    else {
        $employee = $_GET['employee'];
        $url = "ListRooms.php?values=$values&table=$booking&customer=$customer&employee=$employee";
    }
    echo "<meta http-equiv='refresh' content='0;url=$url'>";
    exit();

}
$title = $name;
$content = '<head>
  <title>HTML Reference</title>
</head>'.$content;
include 'Template.php';
?>
