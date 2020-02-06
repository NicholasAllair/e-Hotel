<?php


Require("Model/db_connection.php");


class HotelModel
{
    private $db;

    function __construct()
    {
        $this->db = new db_connection();
    }



    function add_row($table, $att, $values){
        //echo $att;
        //echo $values;
        $sql = "INSERT INTO $table ($att) VALUES ($values)";
        //echo $sql;
        return $this->db->query($sql);
    }

    function get_table_attributes($table)
    {

        $sql = "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = '$table';";
        $res = $this->db->query($sql);
        $att_array = array();
        while($row=pg_fetch_row($res)){
            array_push($att_array, $row[0]);
        }
        return $att_array;
        //while($row = mysqli_fetch_array($result)
        //}


    }
    function find_PK($table)
    {

        $sql = <<<EOF

SELECT               
  pg_attribute.attname, 
  format_type(pg_attribute.atttypid, pg_attribute.atttypmod) 
FROM pg_index, pg_class, pg_attribute, pg_namespace 
WHERE 
  pg_class.oid = '$table'::regclass AND 
  indrelid = pg_class.oid AND 
  nspname = 'public' AND 
  pg_class.relnamespace = pg_namespace.oid AND 
  pg_attribute.attrelid = pg_class.oid AND 
  pg_attribute.attnum = any(pg_index.indkey)
 AND indisprimary
;
EOF;
        $res=$this->db->query($sql);
        $pks_array= array();
        while($row=pg_fetch_row($res)){
                array_push($pks_array, $row[0]);
            }
//        if ($table == "hotel"){
//            array_push($pks_array, 'name');
//        }



        return $pks_array;

        //while($row = mysqli_fetch_array($result)
        //}


    }

    function get_rows_by_condition($table, $condition)
    {

        if($condition != " *") {
            $sql = "SELECT * FROM $table WHERE $condition";
        }
        else{
            $sql = "SELECT * FROM $table";

        }
        //echo nl2br($sql . "\r\n\r\n\r\nhello");
        return $this->db->query($sql);

        //while($row = mysqli_fetch_array($result)
        //}


    }

    function edit_row($table, $attributes, $condition)
    {
        if ($condition != "") {
            $sql = "UPDATE $table SET $attributes WHERE $condition";
            return $this->db->query($sql);
        } else {
            return false;
        }

    }

    function delete_row($table, $condition)
    {
        if ($condition != "") {
            $sql = "DELETE FROM $table WHERE $condition";
            return $this->db->query($sql);
        } else {
            return false;
        }
        function find_PK($table)
        {
            $sql = <<<EOF
SELECT;
SELECT c.column_name, c.data_type
FROM information_schema.table_constraints tc 
JOIN information_schema.constraint_column_usage AS ccu USING (constraint_schema, constraint_name) 
JOIN information_schema.columns AS c ON c.table_schema = tc.constraint_schema
                AND tc.table_name = c.table_name AND ccu.column_name = c.column_name
WHERE constraint_type = 'PRIMARY KEY' and tc.table_name = '$table';
EOF;
            return $this->db . query($sql);
        }
    }
    function att_ref_other_table($table){
        $sql = "select 
  (select r.relname from pg_class r where r.oid = c.confrelid) as ftable ,
  (select array_to_json(array_agg(attname)) from pg_attribute 
   where attrelid = c.conrelid and ARRAY[attnum] <@ c.conkey) as col 
  
from pg_constraint c 
where c.conrelid = (select oid from pg_class where relname = '$table');";
        $result = $this->db->query($sql);
        $res_array = array();
        while($row=pg_fetch_row($result)){
            if($row[0] !== null) {
                $list_pks = json_decode($row[1]);
                //foreach($list_pks as $as) echo  $as;
                $res_array[$row[0]] = $list_pks;
            }

        }

        return $res_array;

    }
    function att_ref_this_table($table){
        $sql ="select 
  (select r.relname from pg_class r where r.oid = c.conrelid) as table, 
  (select  array_to_json(array_agg(attname)) from pg_attribute 
   where attrelid = c.conrelid and ARRAY[attnum] <@ c.conkey) as col 
from pg_constraint c 
where c.confrelid = (select oid from pg_class where relname = '$table');";
        $result = $this->db->query($sql);
        $res_array = array();
        while($row=pg_fetch_row($result)){
            $list_pks = json_decode($row[1]);
            //foreach($list_pks as $as) echo  $as;
            $res_array[$row[0]] = $list_pks;
        }
        return $res_array;


    }
    function find_distinct_values($table, $attribute){
        $sql = "select distinct($attribute) from $table";
        //echo $sql;
        $result = $this->db->query($sql);
        $res = array();
        while($row=pg_fetch_row($result)) {

            array_push($res, $row[0]);
        }
        //foreach($res as $a) echo $a;
        return $res;
    }
    function find_available_rooms($condition, $start_date, $end_date){
        $sql = "select * 
from all_rooms 
where $condition (hotel_id, room_num)  not in
(select  C.hotel_id, C.room_num 
from checked_booked_in_rooms C where (C.check_Out_Date BETWEEN '$start_date' and '$end_date') or (C.check_In_Date BETWEEN '$start_date' and '$end_date') );
";
        //echo $sql;
        return $this->db->query($sql);


    }
    function create_booking($customer, $hotel,$room, $cin, $cout){
        $sql = "INSERT INTO booking (customer, hotel, room,  check_In_Date, check_Out_Date, cancelled) values ('$customer', $hotel, $room, '$cin', '$cout', 'false');";
        //echo $sql;
        return $this->db->query($sql);
    }
    function create_rental($customer, $hotel,$room, $cin, $cout, $employee){
        $sql = "INSERT INTO rental_contract (customer, hotel, room,  check_In_Date, check_Out_Date, check_in_empl) values ('$customer', $hotel, $room, '$cin', '$cout', '$employee');";
        //echo $sql;
        return $this->db->query($sql);
    }
    function retrieve_lastRow_id($table){
        $pks= $this->find_PK($table);
        if(count($pks)==1) {
            $att = $pks[0];
        }
        else{
            $att = "(";
            foreach($pks as $pk) $att .= "$pk, ";
            $att .= rtrim($att ,", ");
            $att .= ")";
        }
        $sql = "SELECT $att FROM $table ORDER BY $att DESC LIMIT 1";
        $ret = $this->db->query($sql);
        $ret = pg_fetch_array($ret);
        return $ret[0];

    }
}


