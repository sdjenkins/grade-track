<?php 
session_start();
if($_SESSION['isSet'] === 1) { //jump to main
    header('Location: http://web.engr.oregonstate.edu/~jenkinss/cs494/final/main.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') { //if post, run this script
    
    ini_set('display_errors', 'On');
    include 'storedInfo.php';

    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "jenkinss-db", $myPassword, "jenkinss-db");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    } 

    if (!($stmt = $mysqli->prepare( "SELECT id, username, password FROM users WHERE username = ?"))) 
    {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    $stmt->bind_param("s", $_POST['username']);

    //execute prepared statement
    if (!$stmt->execute())
    {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    $stmt->bind_result($col1, $col2, $col3);
    $stmt->fetch();

    if ($col2 !== $_POST['username']) {
        echo "Username not found. Please create an account.";
        $stmt->close();
        return;
    }

    if ($col3 !== $_POST['password']) {
        echo "Incorrect password. Please try again.";
        $stmt->close();
        return;
    }

    if ($col2 === $_POST['username'] && $col3 === $_POST['password']) {
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['isSet'] = 1;
        echo "success";
    }

//close statement
    $stmt->close();
}
else{
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src="jquery-1.11.0.js"></script>
<link href="bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css">
<title>Final Project</title>
<script type="text/javascript">
function logIn() {
    //runs post to test.php, with specified data, and success function writes the result to the output div
    var request_data = $('#login').serialize(); //puts data in right format for php
    $.post('login.php', request_data, function(data, textStatus, jqXHR){
        if (data === "success"){
            window.location.href = "http://web.engr.oregonstate.edu/~jenkinss/cs494/final/main.php";
        }
        else{
            $('#output').html(data);
        }
        }, 'html');
}
</script>
</head>
<h1>Course Grade Tracker</h1>
<br><br><br><br>
<div class="main">
<form id="login" class='account' action ="#" onsubmit="logIn();return false;" method="post">
    <input name="username" type="text" placeholder="Username">
    <br>
    <input name="password" type="password" placeholder="Password">
    <br><br>
    <input type="submit" value="Log in">
</form>
<br>
<div>
<a href='create.php'> New user? Create account </a>
</div>
<br>
<div>
<div class="error" class ="smalltext"id="output"></div>
</div>
</div>
</html>
<?php
}
?>