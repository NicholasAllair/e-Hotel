SQL that supports functionality
the following are the templates that the code dynamically creates into specific SQL code.

INSERT INTO $table ($att) VALUES ($values)

SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = '$table';

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
 AND indisprimary;

SELECT * FROM $table WHERE $condition;

UPDATE $table SET $attributes WHERE $condition;

DELETE FROM $table WHERE $condition;

select 
  (select r.relname from pg_class r where r.oid = c.confrelid) as ftable ,
  (select array_to_json(array_agg(attname)) from pg_attribute 
   where attrelid = c.conrelid and ARRAY[attnum] <@ c.conkey) as col 
  
from pg_constraint c 
where c.conrelid = (select oid from pg_class where relname = '$table');

select 
  (select r.relname from pg_class r where r.oid = c.conrelid) as table, 
  (select  array_to_json(array_agg(attname)) from pg_attribute 
   where attrelid = c.conrelid and ARRAY[attnum] <@ c.conkey) as col 
from pg_constraint c 
where c.confrelid = (select oid from pg_class where relname = '$table');

select distinct($attribute) from $table;

select * 
from all_rooms 
where $condition (hotel_id, room_num)  not in
(select  C.hotel_id, C.room_num 
from checked_booked_in_rooms C where (C.check_Out_Date BETWEEN '$start_date' and '$end_date') or (C.check_In_Date BETWEEN '$start_date' and '$end_date') );

INSERT INTO booking (customer, hotel, room,  check_In_Date, check_Out_Date, cancelled) values ('$customer', $hotel, $room, '$cin', '$cout', 'false');

INSERT INTO rental_contract (customer, hotel, room,  check_In_Date, check_Out_Date, check_in_empl) values ('$customer', $hotel, $room, '$cin', '$cout', '$employee');

SELECT $att FROM $table ORDER BY $att DESC LIMIT 1;

select * from all_rooms;

select * 
from all_rooms 
where chain = 'Marriot' and (hotel_id, room_num)  not in
(select  C.hotel_id, C.room_num 
from checked_booked_in_rooms C where (C.check_Out_Date BETWEEN '2019-04-07' and '2019-04-17') or (C.check_In_Date BETWEEN '2019-04-07' and '2019-04-17') );
where (C.check_Out_Date >= '2019-04-07' and C.check_In_Date <= '2019-04-07') or (C.check_Out_Date >= '2019-04-17' and C.check_In_Date <= '2019-04-17');


select Distinct(R.TABLE_NAME), U.COLUMN_NAME
from INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE u
inner join INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS FK
    on U.CONSTRAINT_CATALOG = FK.UNIQUE_CONSTRAINT_CATALOG
    and U.CONSTRAINT_SCHEMA = FK.UNIQUE_CONSTRAINT_SCHEMA
    and U.CONSTRAINT_NAME = FK.UNIQUE_CONSTRAINT_NAME
inner join INFORMATION_SCHEMA.KEY_COLUMN_USAGE R
    ON R.CONSTRAINT_CATALOG = FK.CONSTRAINT_CATALOG
    AND R.CONSTRAINT_SCHEMA = FK.CONSTRAINT_SCHEMA
    AND R.CONSTRAINT_NAME = FK.CONSTRAINT_NAME
WHERE U.TABLE_NAME = 'room'

select 
  (select r.relname from pg_class r where r.oid = c.conrelid) as table, 
  (select  array_to_json(array_agg(attname)) from pg_attribute 
   where attrelid = c.conrelid and ARRAY[attnum] <@ c.conkey) as col 
from pg_constraint c 
where c.confrelid = (select oid from pg_class where relname = 'hotel');

select 
  (select r.relname from pg_class r where r.oid = c.confrelid) as ftable ,
  (select array_agg(attname) from pg_attribute 
   where attrelid = c.conrelid and ARRAY[attnum] <@ c.conkey) as col 
  
from pg_constraint c 
where c.conrelid = (select oid from pg_class where relname = 'archive_room');
select * from archive;

select  C.hotel_id, C.room_num 
from checked_booked_in_rooms C where (C.check_Out_Date BETWEEN '2019-03-23' and '2019-03-24') or (C.check_In_Date BETWEEN '2019-03-23' and '2019-03-24') ;

