<html>
    <head>
        <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }

            tr:nth-child(even) {
                background-color: #dddddd;
            }
        </style>
    </head>
    <body>
        <?php
        require 'Model/HotelModel.php';




        class HotelController
        {


            function DeleteRow($table, $pks, $values)
            {
                $HotelModel = new HotelModel();
                $where = "";
                $where .= "$pks[0] = '$values[0]'";
                for ($i = 0; $i < count($pks); $i += 1) {
                    $where .= " and $pks[0] = '$values[0]'";
                }
                $HotelModel->delete_row($table, $where);


            }

            function EditRow($table, $pks, $values)
            {
                $HotelModel = new HotelModel();
                $where = "";
                $where .= "$pks[0] = '$values[0]'";
                for ($i = 0; $i < count($pks); $i += 1) {
                    $where .= " and $pks[0] = '$values[0]'";
                }
                $edit = "";
                foreach ($_POST as $key => $value) {
                    if (!in_array($key, $pks) && $key != "submit") {
                        $edit .= " $key = '$value',";
                    }
                }
                $edit = rtrim($edit, ", ");
                $HotelModel->edit_row($table, $edit, $where);


            }

            function AddRow($table)
            {
                $HotelModel = new HotelModel();
                $keys = "";
                $values = "";
                foreach ($_POST as $key => $value) {
                    if ($key != "submit") {
                        $values .= " '$value',";
                        $keys .= " $key,";
                    }
                }
                //echo $keys;
                //echo $values;
                $values = rtrim($values, ", ");
                $keys = rtrim($keys, ", ");

                $HotelModel->add_row($table, $keys, $values);
            }

            function ListAll($table, $condition)
            {
                $HotelModel = new HotelModel();
                $res = $HotelModel->get_rows_by_condition($table, $condition);
                $pks = $HotelModel->find_PK($table);
                $result = "<table><tr>";
                $view_cond = str_replace('=', ':', $condition);
                $view_cond = str_replace(';', '', $view_cond);
                $view_cond = str_replace('*', '', $view_cond);
                $view_cond = str_replace('\'', '', $view_cond);

                foreach ($pks as $row2) {
                    $result = $result .
                        "<th>$row2 of $view_cond</th>";
                }

                $result = $result . "<th>View:</th>";
                if ($table == "customer" && $_GET['employee'] !== NULL) {
                    $result = $result . "<th>Rent:</th>";
                }

                $result = $result . "<th>Edit:</th><th>Delete:</th></tr>";

                while ($row1 = pg_fetch_assoc($res)) {
                    //echo $row1[0];
                    $result .= "<tr>";
                    $values_array = array();
                    foreach ($pks as $row2) {
                        $result .= "<td>$row1[$row2]</td> ";

                        array_push($values_array, $row1[$row2]);
                    }
                    $send_pks = urlencode(serialize($pks));
                    $send_values = urlencode(serialize($values_array));
                    $empl = $_GET['employee'];
                    $result .= "<td><a href='ViewRow.php?pk=$send_pks&pkv=$send_values&table=$table&employee=$empl'>View</a></td>";
                    if ($table == "customer" && $_GET['employee'] !== NULL) {
                        $result .= "<td><a href='FindRoom.php?customer=$values_array[0]&employee=$empl&table=Rent'>Rent a room or view a rental</a></td>";
                    }
                    $result .= "<td><a href='EditRow.php?pk=$send_pks&pkv=$send_values&table=$table'>Edit</a></td>";

                    $result .= "<td><a href='DeleteRow.php?pk=$send_pks&pkv=$send_values&table=$table'>Delete</a></td></tr>";
                }
                $result .= "</table>";
                return $result;

            }

            function createEmptyForm($table, $condition)
            {
                $HotelModel = new HotelModel();
                $pks = $HotelModel->find_PK($table);
                $res = $HotelModel->get_table_attributes($table);
                $result = "<form action='' method='post'><fieldset><legend>Create $table</legend>";
                $cond_array = array();
                if ($condition != " *") {
                    $array_cond = explode(' and ', $condition);
                    foreach ($array_cond as $item) {
                        $item_array = explode('=', $item);
                        $item_array[1] = str_replace("'", "", $item_array[1]);
                        $item_array[1] = str_replace(";", "", $item_array[1]);
                        $cond_array += [trim($item_array[0]) => trim($item_array[1])];
                    }
                }

                foreach ($res as $row1) {


                    if (array_key_exists($row1, $cond_array)) {
                        $result .= "<label for='$row1'>$row1: </label>
        <input type='text' value='$cond_array[$row1]' name='$row1' readonly><br/>";

                    } else if (in_array($row1, $pks)) {
                        $result .=
                            "<label for='$row1'>$row1: </label>
        <input type='text' class='inputField'  name='$row1' required><br/>";

                    } else if ((strlen(stristr($row1, "date")) > 0)) {
                        $today = date('Y-m-d');
                        $result .=
                            "<label for='$row1'>$row1: </label>
        <input type='date' value='$today' min=$today name='$row1'><br/>";
                    } else {
                        $result .=
                            "<label for='$row1'>$row1: </label>
        <input type='text' class='inputField' name='$row1'><br/>";
                    }
                }
                $result .= "<input type='submit' value='Add new $table' name='submit'></fieldset>
</form>";


                return $result;
            }

            function createPopulatedForm($table, $pks, $values)
            {
                $HotelModel = new HotelModel();
                $where = "";
                $where .= "$pks[0]='$values[0]';";
                for ($i = 1; $i < count($pks); $i += 1) {
                    $where .= " and $pks[$i] = $values[$i]";
                }
                $res = $HotelModel->get_rows_by_condition($table, $where);
                $result = "<form action='' method='post'><fieldset><legend>Edit $table:";
                foreach ($values as $value) {
                    $result .= " $value";
                }
                $result .= "</legend>";
                while ($row1 = pg_fetch_assoc($res)) {

                    for ($i = 0; $i < pg_num_fields($res); $i += 1) {
                        $field_name = pg_field_name($res, $i);
                        $field_value = $row1[$field_name];
                        if (in_array($field_name, $pks)) {
                            $result .=
                                "<label for='$field_name'>$field_name: </label>
        <input type='text' class='inputField' value='$field_value' name='$field_name' readonly><br/>";

                        } else if ((strlen(stristr($field_name, "date")) > 0)) {
                            $today = date('Y-m-d');
                            $result .=
                                "<label for='$field_name'>$field_name: </label>
        <input type='date' value='$field_value' min=$today name='$field_name'><br/>";
                        } else {
                            $result .=
                                "<label for='$field_name'>$field_name: </label>
        <input type='text' class='inputField' value='$field_value' name='$field_name'><br/>";
                        }
                    }
                    $result .= "<input type='submit' value='Submit Change' name='submit'></fieldset>
</form>";

                }
                return $result;
            }

            function createViewList($table, $pks, $values)
            {
                $HotelModel = new HotelModel();
                $where = "";
                $where .= "$pks[0]='$values[0]'";
                for ($i = 1; $i < count($pks); $i += 1) {
                    $where .= " and $pks[$i] = $values[$i]";
                }
                $where .= ";";
                //echo $where;
                $res = $HotelModel->get_rows_by_condition($table, $where);
                $parent_pks = $HotelModel->att_ref_other_table($table);
                $children_pks = $HotelModel->att_ref_this_table($table);
                $result = "<table><legend>View $table:";
                foreach ($values as $value) {
                    $result .= " $value";
                }
                $result .= "</legend>";
                while ($row1 = pg_fetch_assoc($res)) {

                    for ($i = 0; $i < pg_num_fields($res); $i += 1) {
                        $field_name = pg_field_name($res, $i);
                        $field_value = $row1[$field_name];
                        $parent_table = NULL;
                        $parent_pkv = NULL;
                        //echo $parent_pks;
                        foreach ($parent_pks as $key_p => $value_p) {

                            if (in_array($field_name, $value_p)) {
                                $parent_table = $key_p;
                                $parent_pkv = array();
                                foreach ($value_p as $x) {
                                   // echo $row1[$x];
                                    array_push($parent_pkv, $row1[$x]);

                                }
                            }
                        }
                        if ($parent_table !== NULL) {
                            $send_pks = urlencode(serialize($HotelModel->find_PK($parent_table)));
                            //foreach ($parent_pkv as $value) echo $value;
                            $send_values = urlencode(serialize($parent_pkv));
                            $result .=
                                "<tr><td>$field_name: </td><td><a href='ViewRow.php?pk=$send_pks&pkv=$send_values&table=$parent_table'>$field_value</a></td></tr>";

                        } else {
                            $result .=
                                "<tr><td>$field_name: </td><td>$field_value</td></tr>";

                        }
                    }
                    foreach ($children_pks as $key => $value_c) {

                        //echo $value;
                        //echo $key;
                        //foreach($row1 as $v) echo $v;
                        $condition_passed = "";
                        for ($i = 0; $i < count($value_c); $i++) {
                            //echo $values[$i];
                            $condition_passed .= "$value_c[$i] ='$values[$i]' and ";
                        }
                        $condition_passed = rtrim($condition_passed, ' and ');
                        $condition_passed .= ";";
                        //echo $condition_passed;
                        $condition_passed = urlencode($condition_passed);
                        $result .= "<tr><td>$key: </td><td><a href='ListALL.php?table=$key&condition=$condition_passed'>View</a></td></tr>";
                    }
                    $result .= "</table>";


                    if ($table == "all_rooms" && $_GET['book'] !== NULL) {
                        $book = $_GET['book'];
                        $cin = $_GET['checkin'];
                        $cout = $_GET['checkout'];
                        $result .= "<form action='' method='post'><table><tr><td>Do you want to create $book for this Room from $cin to $cout? 
<button type='submit' value='submit' name='submit'>YES</button> </td></tr></table></form>";
                        $_POST['hotel'] = $row1['hotel_id'];
                        $_POST['room'] = $row1['room_num'];

                    } elseif ($table == "booking" && $_GET['employee'] !== NULL && $_GET['employee'] != "") {
                        //echo $_GET['employee'];
                        $booking = $row1['booking_id'];
                        if (pg_numrows($HotelModel->get_rows_by_condition('convert_booking', "booking = '$booking'")) == 0) {
                            $result .= "<form action='' method='post'><table><tr><td>Do you want to convert booking into a rental agreement? <button type='submit' value='submit' name='submit'>YES</button> </td></tr></table></form>";
                            $_POST['booking_id'] = $row1['booking_id'];
                            $_POST['hotel'] = $row1['hotel'];
                            $_POST['room'] = $row1['room'];
                            $_POST['customer'] = $row1['customer'];
                            //echo $row1['customer'];
                            $_POST['check_in_date'] = $row1['check_in_date'];
                            $_POST['check_out_date'] = $row1['check_out_date'];
                        } else {
                            $result .= "<table><tr><td> Booking already converted to rental contract </td></tr></table>";
                        }

                    }


                }
                return $result;
            }

            function CheckLogin($uname, $password, $type)
            {
                $HotelModel = new HotelModel();

                if ($type == "Customer") {
                    $condition = "email='$uname' and password='$password';";
                    $result = $HotelModel->get_rows_by_condition($type, $condition);
                } else {
                    $condition = "ssn_sin='$uname' and password='$password';";
                    $result = $HotelModel->get_rows_by_condition($type, $condition);
                }
                return (pg_num_rows($result) > 0);


            }

            function FindRoom()
            {

                $HotelModel = new HotelModel();

                $result = "<form action='' method='post'><div class='container'><fieldset><legend>Search Room:</legend>";
                $today = date('Y-m-d');
                $tomorrow = (new DateTime('tomorrow'))->format('Y-m-d');

                $result .= "<label for='Check_in_date'>Check in Date: </label>
        <input type='date' value=$today min=$today name='Check_in_date'><br/>";
                $result .= "<label for='Check_out_date'>Check out Date: </label>
        <input type='date' value=$tomorrow  min=$tomorrow name='Check_out_date'><br/>";
                $fields = array("Province", "City", "Chain", "Rating", "Capacity", "Hotel", "number_of_rooms");
                foreach ($fields as $res) {
                    $result .= "<label for='$res'>$res: </label><select class='inputField' name='$res'>";
                    $dist = $HotelModel->find_distinct_values("all_rooms", $res);
                    $result .= "<option value='Not Selected'>Not Selected</option> ";
                    foreach ($dist as $v) {
                        $result .= "<option value='$v'>$v</option> ";
                    }
                    $result .= "</select>";
                }
                $result .= "<button type='submit' value='submit' name='submit'>Find Room</button></div></form>";
                return $result;

            }

            function list_room_search($search_criteria, $booking, $customer, $employee)
            {
                $condition = "";
                $start_date = "";
                $end_date = "";
                foreach ($search_criteria as $key => $value) {

                    if ($key == "Check_in_date") $start_date = $value;
                    elseif ($key == "Check_out_date") $end_date = $value;
                    else {
                        if ($value != "Not Selected") $condition .= "$key ='$value' and ";
                    }

                }
                $HotelModel = new HotelModel();
                $res = $HotelModel->find_available_rooms($condition, $start_date, $end_date);
                $result = "<table><tr>";


                $view_value = array('Chain', 'Hotel', 'City', 'Province', 'Capacity', 'Rating');
                foreach ($view_value as $view1) {
                    $result = $result . "<th>$view1</th>";
                }
                $result = $result . "<th>View Room:</th></tr>";
                $pks = array('hotel_id', 'room_num');
                while ($row1 = pg_fetch_assoc($res)) {
                    $result .= "<tr>";
                    $values_array = array();
                    foreach ($view_value as $view) {
                        $view = strtolower($view);

                        $result .= "<td>$row1[$view]</td> ";

                    }


                    foreach ($pks as $row2) {
                        array_push($values_array, $row1[$row2]);
                    }
                    $send_pks = urlencode(serialize($pks));
                    $send_values = urlencode(serialize($values_array));
                    if ($employee !== NULL)
                        $result .= "<td><a href='ViewRow.php?pk=$send_pks&pkv=$send_values&table=all_rooms&book=$booking&checkin=$start_date&checkout=$end_date&customer=$customer&employee=$employee'>View available room</a></td>";
                    else
                        $result .= "<td><a href='ViewRow.php?pk=$send_pks&pkv=$send_values&table=all_rooms&book=$booking&checkin=$start_date&checkout=$end_date&customer=$customer'>View available room</a></td>";


                }


                $result .= "</table>";
                return $result;


            }

            function create_booking($customer, $hotel, $room, $cin, $cout)
            {
                $HotelModel = new HotelModel();

                return $HotelModel->create_booking($customer, $hotel, $room, $cin, $cout);
            }

            function create_rental($customer, $hotel, $room, $cin, $cout, $employee)
            {
                $HotelModel = new HotelModel();

                return $HotelModel->create_rental($customer, $hotel, $room, $cin, $cout, $employee);
            }

            function convert_booking($booking_id, $employee, $customer, $hotel, $room, $cin, $cout)
            {
                $HotelModel = new HotelModel();

                $HotelModel->add_row('rental_contract', 'hotel, room, customer, check_in_empl, check_In_Date, check_Out_Date',
                    "$hotel, $room, '$customer', '$employee', '$cin', '$cout'");
                $rental_id = $HotelModel->retrieve_lastRow_id('rental_contract');
                $HotelModel->add_row('Convert_booking', "Booking, rental_agreement", "$booking_id, $rental_id");

            }

            function ListView($table, $condition)
            {
                $HotelModel = new HotelModel();
                $res = $HotelModel->get_rows_by_condition($table, $condition);
                $result = "<table><tr>";


                $count = pg_num_fields($res);
                for ($j = 0; $j < $count; $j++) {
                    $field_name = pg_field_name($res, $j);

                    $result = $result . "<th>$field_name</th>";
                }
                $result = $result . "</tr>";

                while ($row1 = pg_fetch_row($res)) {
                    //echo $row1[0];
                    $result .= "<tr>";
                    for ($j = 0; $j < $count; $j++) {
                        $result .= "<td>$row1[$j]</td> ";

                    }
                    $result .= "</tr>";

                }

                    $result .= "</table>";
                    return $result;

                }
        function ChooseHotel(){
            $HotelModel = new HotelModel();

            $result = "<form action='' method='post'><div class='container'><fieldset><legend>Search Room:</legend>";
            $result .= "<label for='hotel'>hotel: </label><select class='inputField' name='hotel'>";
            $dist = $HotelModel->find_distinct_values("all_rooms", 'hotel');
            $result .= "<option value='Not Selected'>Not Selected</option> ";
                    foreach ($dist as $v) {
                        $result .= "<option value='$v'>$v</option> ";
                    }
                    $result .= "</select>";

                $result .= "<button type='submit' value='submit' name='submit'>Show available capacity</button></div></form>";
                return $result;

        }
        function check_room_is_available($hotel, $room, $cin, $cout){
            $HotelModel = new HotelModel();
            $condition = "room_num = $room and hotel_id = $hotel and ";
            $rooms = $HotelModel->find_available_rooms($condition, $cin, $cout);
           return pg_num_rows($rooms) > 0;

        }
        function is_not_Converted($booking)
        {
            if($booking === NULL || $booking == "") return FALSE;
            $HotelModel = new HotelModel();
            return (pg_numrows($HotelModel->get_rows_by_condition('convert_booking', "booking = $booking;")) == 0);
        }

        }