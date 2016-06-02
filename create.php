<?php 
if($_SERVER['REQUEST_METHOD'] === 'POST') { //if post, run this script

    ini_set('display_errors', 'On');
    include 'storedInfo.php';

    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "jenkinss-db", $myPassword, "jenkinss-db");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    } 

    /*
    //used to create the table the first time
    if (!$mysqli->query("DROP TABLE IF EXISTS users") ||
        !$mysqli->query("CREATE TABLE users(id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255) UNIQUE, password VARCHAR(255), PRIMARY KEY(id))") ||
        !$mysqli->query("INSERT INTO users(username, password) VALUES ('testuser', 'testuser')")) {
        echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
    } 
    */

    //assign data sent from post to variables
    $username = $_POST['username'];
    $password = $_POST['password1'];

    //using prepared statement
    if (!($stmt = $mysqli->prepare("INSERT INTO users(username, password) VALUES ('$username', '$password')"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    //execute prepared statement
    if (!$stmt->execute()) {
        //echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        if($mysqli->errno == 1062) {
            echo "<p class='error'>That username is already in use. Please try another.</p>";
            return;
        }
        else {
            echo "<p class='error'>User account creation error.</p>";
            return;
        }
    }
    else {
        echo "User account creation successful! Please <a href='login.php'> log in!</a>";
    }

    //close statement
    $stmt->close();

    //using prepared statement
    if (!($stmt = $mysqli->prepare("CREATE TABLE $username(EnrollmentID INT NOT NULL AUTO_INCREMENT, username VARCHAR(255), CourseID VARCHAR(255), CourseCredits INT, CreditsEarned FLOAT, Grade VARCHAR(255), Term INT, Year INT, PRIMARY KEY(EnrollmentID), UNIQUE(CourseID))"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    //execute prepared statement
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    //close statement
    $stmt->close();
}

else{ //if not post, display create account page
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

function checkForm(){
    var username = document.getElementById("username").value.trim();
    var pw1 = document.getElementById("password1").value.trim();
    var pw2 = document.getElementById("password2").value.trim();

    $('#error').html("");
    $('#output').html("");


    if (username.length < 5){
        $('#error').html("Username too short!");
        return;
    }

    if (username.length > 9){
        $('#error').html("Username too long!");
        return;
    }

    if (username.charAt(0).match(/[0-9]/)) {
        $('#error').html("Username cannot start with a number!");
        return;
    }

    if (pw1.length < 8 || pw2.length < 8){
        $('#error').html("Password too short!");
        return;
    }

    if (pw1.length > 12 || pw2.length > 12){
        $('#error').html("Password too long!");
        return;
    }

    if (pw1 !== pw2){
        $('#error').html("Your passwords must match.");
        return;
    }

    if (username.match(/[^A-Za-z0-9]/)) {
       $('#error').html("Your username contains invalid characters.");
        return;
    }

    if (pw1.match(/[^A-Za-z0-9]/) || pw2.match(/[^A-Za-z0-9]/)) {
       $('#error').html("Your password contains invalid characters.");
        return;
    }

    addToTable();
}

function addToTable() {
    //runs post to create.php, with specified data, and success function writes the result to the output div
    var request_data = $('#login').serialize(); //puts data in right format for php
    $.post('create.php', request_data, function(data, textStatus, jqXHR){
        $('#output').html(data);
        }, 'html');
}
</script>
</head>
<h1>Create an account</h1>
<br><br><br><br>
<form class='account' id="login" action ="#" onsubmit="checkForm();return false;" method="post">
    <input id="username" name="username" type="text" placeholder="Username">
    <br> 
    <input id="password1" name="password1" type="password" placeholder="Password">
    <br>
    <input id="password2" type="password" placeholder="Verify Password">
    <br><br>
    <input type="submit" value="Create Account">
</form>

<div class = 'smalltext'>
<br>
Username must be 5 to 9 characters long (alphanumeric).
<br>
Password must be 8 to 12 characters long (alphanumeric).
</div>
<div class='smalltext' id="output"></div><div id='error'></div>
<br>
<div>
<a href='login.php'> Return to login </a>
</div>
</html>
<?php
}
?>