
<?php

require './SourceController/HotelController.php';
$HotelController = new HotelController();

//$condition = urldecode($_GET["type"]);
//$cond_enc = urlencode($condition);
$list = "<form action='' method='post'>

  <div class='container'>
    <label for='uname'><b>Email</b></label>
    <input type='text' placeholder='Enter Email' name='uname' required>

    <label for='psw'><b>Password</b></label>
    <input type='password' placeholder='Enter Password' name='psw' required>

    <button type='submit' value='Add new $table' name='submit'>Login</button>
    
  </div>


</form>";
if (isset($_POST['submit'])) {
    if($HotelController->CheckLogin($_POST['uname'], $_POST['psw'],"Customer")){
        $email = $_POST['uname'];
        //$list .="<meta http-equiv='refresh' content='0;url=Customer.php?email=$email'>";
        $url = "Customer.php?email=$email";
        echo "<meta http-equiv='refresh' content='0;url=$url'>";
        exit();
    }
    else{
        $list .= "<div class='container'><textarea>'email or password incorrect'</textarea></div>";
    }
}

$title = "Sign in";
$content = '<head>
  <title>HTML Reference</title>
</head>'.$list;
include 'Template.php';
?>
