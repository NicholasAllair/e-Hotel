<?php
// Login Data for Database
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class db_connection
{
   private $host, $port,$dbname, $credentials, $connection;

   function __construct() {

      $this->host = "host = localhost";
      $this->port = "port = 5432";
      $this->dbname = "dbname = postgres";
      $this->credentials = "user = postgres password=Hello123";


   }
   function query($sql)
   {
      $this->connection = pg_connect("$this->host $this->port $this->dbname $this->credentials");
      if(!$this->connection){
         echo "Error : Unable to open database\n";
      }
      else {
         $result = pg_query($this->connection, $sql);

         if($result)
         {
            return $result;
         }
         else
         {
            $error = pg_last_error($this->connection);

            // you need to adapt this regex
            if (preg_match('/duplicate/i', $error))
            {
               echo "this value already exists";
            }
            // you need to adapt this regex
            elseif(preg_match('/violates check constraint/i', $error))
            {
               echo "one of the attributes is not in the right format";
            }
            elseif(preg_match("/violates foreign key constraint/i", $error))
               echo "one of the attributes is not correct";

            else
            {
               echo $error;
            }
         }

         pg_close($this->connection);
      }
   }
}
?>

