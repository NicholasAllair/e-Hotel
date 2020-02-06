
<?php


$empl = $_GET['employee'];
$condition = "check_in_empl='$empl';";
$cond_enc = urlencode($condition);
$content = "<p>FIND A CUSTOMER OR CREATE ONE BEFORE PROCEEDING</p>";
$content .="<form action='' method='post'>

  <div class='container'>
    <label for='first_name'><b>First Name</b></label>
    <input type='text' placeholder='Enter First Name' name='first_name'>

    <label for='last_name'><b>Last Name</b></label>
    <input type='text' placeholder='Enter Last Name' name='last_name'>

    <label for='email'><b>Email</b></label>
    <input type='text' placeholder='Enter Email' name='email'>

    <button type='submit' value='Add new $table' name='submit'>Search</button>
    
  </div>


</form>";

if (isset($_POST['submit'])) {
    $condition = "";
    foreach($_POST as $key => $value){
        echo $key. $value;
        if ($value !== '' && $key != "submit") $condition .= "$key = '$value' and ";

    }
    $condition = rtrim($condition, ' and ');
    $condition.= ";";
    if ($condition == ";") $condition = " *";
    $condition = urlencode($condition);
    $url = "ListAll.php?table=customer&employee=$empl&condition=$condition";
    echo "<meta http-equiv='refresh' content='0;url=$url'>";
    exit();


}
$title = "User: $email";

include 'Template.php';
?>
