<?php

require './SourceController/HotelController.php';


$HotelController = new HotelController();

$table = $_GET['table'];
$email = urldecode($_GET['customer']);
$condition = "customer='$email';";
$cond_enc = urlencode($condition);
$title = "View $table";

$pks =unserialize(urldecode($_GET['pk']));
$pkv = unserialize(urldecode($_GET['pkv']));
$content = $HotelController->createViewList($_GET['table'],$pks,$pkv );
//echo $booking;
if (isset($_POST['submit'])) {
    //echo "here";
    if($_GET['book'] == "booking" && $table == "all_rooms"){
        //echo "here" . $HotelController->check_room_is_available($_POST['hotel'],$_POST['room'], $_GET['checkin'], $_GET['checkout']);
        if($HotelController->check_room_is_available($_POST['hotel'],$_POST['room'], $_GET['checkin'], $_GET['checkout'])) {
            //echo $_GET['checkout'];
            $HotelController->create_booking($email, $_POST['hotel'], $_POST['room'], $_GET['checkin'], $_GET['checkout']);
            //$cond_enc
            $url = "ListAll.php?table=booking&condition=$cond_enc";
            echo "<meta http-equiv='refresh' content='0;url=$url'>";
            exit();
        }
        else echo "<p> Cant book this room its already reserved</p>";
    }
    elseif($_GET['book'] == "Rent"&& $table == "all_rooms"){
        if($HotelController->check_room_is_available($_POST['hotel'],$_POST['room'], $_GET['checkin'], $_GET['checkout'])) {

            //echo $_GET['checkout'];
            $HotelController->create_rental($email, $_POST['hotel'], $_POST['room'], $_GET['checkin'], $_GET['checkout'], $_GET['employee']);
            $url = "ListAll.php?table=rental_contract&condition=$cond_enc";
            echo "<meta http-equiv='refresh' content='0;url=$url'>";
            exit();
        }
        else echo "<p> Cant rent this room its already reserved</p>";

    }
    elseif($table =="booking" && $_GET['employee']){
        if ($HotelController->is_not_Converted($_POST['booking_id'])) {

            //echo $_GET['checkout'];
            $HotelController->convert_booking($_POST['booking_id'], $_GET['employee'], $_POST['customer'], $_POST['hotel'], $_POST['room'], $_POST['check_in_date'], $_POST['check_out_date']);
            $email = $_POST['customer'];
            $condition = "customer='$email';";
            $cond_enc = urlencode($condition);
            $url = "ListAll.php?table=rental_contract&condition=$cond_enc";
            echo "<meta http-equiv='refresh' content='0;url=$url'>";
            exit();
        }
        else {
            echo "<p> Room Already converted</p>";
        }
    }
}

include './Template.php';
?>


