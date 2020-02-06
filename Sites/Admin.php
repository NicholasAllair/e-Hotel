<?php

$title = "Administration";

$content = '<h3>Administrations</h3>
            <a href="listall.php?table=hotel_chain&condition= *">Add/Edit/Delete a hotel chain</a><br/>
            <p></p>
            <a href="listall.php?table=hotel&condition= *">Add/Edit/Delete a hotel</a><br/>
            <p></p>
             <a href="listall.php?table=customer&condition= *">Add/Edit/Delete a customer</a><br/>
            <p></p>
             <a href="listall.php?table=employee&condition= *">Add/Edit/Delete a employee</a><br/>
            <p></p>
             <a href="listall.php?table=room&condition= *">Add/Edit/Delete a room</a><br/>
            <p></p>';



include './Template.php';
?>
