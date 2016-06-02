<?php
session_start();
if($_SESSION['isSet'] !== 1){ //Jump to log in page
	header('Location: http://web.engr.oregonstate.edu/~jenkinss/cs494/final/login.php');
	exit;
}
if($_SERVER['REQUEST_METHOD'] === 'POST') { //If post, run this script

	ini_set('display_errors', 'On');
    include 'storedInfo.php';

    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "jenkinss-db", $myPassword, "jenkinss-db");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    //If this is set we will add a row to the table
    if (isset($_POST['course']) && isset($_POST['grade']) && isset($_POST['term']) && isset($_POST['yearpicker'])) {

	    //First we get the course credits for the entered course.
	    if (!($stmt = $mysqli->prepare( "SELECT CourseCredits FROM courses WHERE CourseNum = ?"))) {
	        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    }

	    $stmt->bind_param("s", $_POST['course']);

	    //execute prepared statement
	    if (!$stmt->execute()) {
	        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    }

	    $stmt->bind_result($courseCredits);
	    $stmt->fetch();
	    $stmt->close();

	    //Calculate the credits earned by the user.
	    floatval($courseCredits);
	    if ($_POST['grade'] === "A") {
	    	$creditsEarned = $courseCredits * 4.0;
	    }
	    elseif ($_POST['grade'] === "A-") {
			$creditsEarned = $courseCredits * 3.7;
	    }
	    elseif ($_POST['grade'] === "B+") {
			$creditsEarned = $courseCredits * 3.3;
	    }
	    elseif ($_POST['grade'] === "B") {
			$creditsEarned = $courseCredits * 3.0;
	    }
	    elseif ($_POST['grade'] === "B-") {
			$creditsEarned = $courseCredits * 2.7;
	    }
	    elseif ($_POST['grade'] === "C+") {
			$creditsEarned = $courseCredits * 2.3;
	    }
	    elseif ($_POST['grade'] === "C") {
			$creditsEarned = $courseCredits * 2.0;
	    }
	    elseif ($_POST['grade'] === "C-") {
			$creditsEarned = $courseCredits * 1.7;
	    }
	    elseif ($_POST['grade'] === "D+") {
			$creditsEarned = $courseCredits * 1.3;
	    }
	    elseif ($_POST['grade'] === "D") {
			$creditsEarned = $courseCredits * 1.0;
	    }
	    elseif ($_POST['grade'] === "D-") {
			$creditsEarned = $courseCredits * 0.7;
	    }
	    elseif ($_POST['grade'] === "F") {
			$creditsEarned = $courseCredits * 0.0;
	    }
	 
	    //Insertion
	    if (!($stmt = $mysqli->prepare("INSERT INTO " . $_SESSION['username'] . "(username, CourseID, CourseCredits, CreditsEarned, Grade, Term, Year) VALUES (?, ?, ?, ?, ?, ?, ?)"))) {
	        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    }

	    $stmt->bind_param("ssddsii", $_SESSION['username'], $_POST['course'], $courseCredits, $creditsEarned, $_POST['grade'], $_POST['term'], $_POST['yearpicker']);

	    //Execute prepared statement
	    if (!$stmt->execute()) {
	        //Error
	        $error = "Cannot add duplicate classes.";
	    }

	    $stmt->close();
	}

	//if this is set we will remove a row from the table
	if (isset($_POST['remove'])) {
		
		//Delete the specified row.
		if (!($stmt = $mysqli->prepare( "DELETE FROM " . $_SESSION['username'] . " WHERE EnrollmentID = ?"))) {
	        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    }

	    $stmt->bind_param("i", $_POST['remove']);

	    //execute prepared statement
	    if (!$stmt->execute()) {
	        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    }

	    $stmt->close(); 
	}

	//if this is set we will modify a row in the table
	if (isset($_POST['modify']) && isset($_POST['grade']) && isset($_POST['term']) && isset($_POST['yearpicker'])) {

		if (!($stmt = $mysqli->prepare( "SELECT CourseCredits FROM " . $_SESSION['username'] . " WHERE EnrollmentID = ?"))) 
	    {
	        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    }

	    $stmt->bind_param("s", $_POST['modify']);

	    //execute prepared statement
	    if (!$stmt->execute())
	    {
	        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    }

	    $stmt->bind_result($courseCredits);
	    $stmt->fetch();
	    $stmt->close();

	    floatval($courseCredits);
	    //calculate credits earned
	    if ($_POST['grade'] === "A") {
	    	$creditsEarned = $courseCredits * 4.0;
	    }
	    elseif ($_POST['grade'] === "A-") {
			$creditsEarned = $courseCredits * 3.7;
	    }
	    elseif ($_POST['grade'] === "B+") {
			$creditsEarned = $courseCredits * 3.3;
	    }
	    elseif ($_POST['grade'] === "B") {
			$creditsEarned = $courseCredits * 3.0;
	    }
	    elseif ($_POST['grade'] === "B-") {
			$creditsEarned = $courseCredits * 2.7;
	    }
	    elseif ($_POST['grade'] === "C+") {
			$creditsEarned = $courseCredits * 2.3;
	    }
	    elseif ($_POST['grade'] === "C") {
			$creditsEarned = $courseCredits * 2.0;
	    }
	    elseif ($_POST['grade'] === "C-") {
			$creditsEarned = $courseCredits * 1.7;
	    }
	    elseif ($_POST['grade'] === "D+") {
			$creditsEarned = $courseCredits * 1.3;
	    }
	    elseif ($_POST['grade'] === "D") {
			$creditsEarned = $courseCredits * 1.0;
	    }
	    elseif ($_POST['grade'] === "D-") {
			$creditsEarned = $courseCredits * 0.7;
	    }
	    elseif ($_POST['grade'] === "F") {
			$creditsEarned = $courseCredits * 0.0;
	    }
		
		if (!($stmt = $mysqli->prepare( "UPDATE " . $_SESSION['username'] . " SET Grade=?, CreditsEarned=? WHERE EnrollmentID = ?"))) {
	        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    }

	    $stmt->bind_param("sdi", $_POST['grade'], $creditsEarned, $_POST['modify']);

	    //execute prepared statement
	    if (!$stmt->execute()) {
	        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	    }

	    $stmt->close(); 
	}

	    //Display a graph
    if (isset($_POST['graph'])) {
    	
    	if ($_POST['graph'] == 0) {

    		//Select all the grades for the user.
			if (!($stmt = $mysqli->prepare( "SELECT Grade FROM " . $_SESSION['username'] . " WHERE username = ?"))) 
		    {
		        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		    }

		    $stmt->bind_param("s", $_SESSION['username']);

		    //Execute prepared statement
		    if (!$stmt->execute())
		    {
		        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
		    }

		    $stmt->bind_result($result);
		   
		    $grades = array();
		    
		    while($stmt->fetch()) {

		    	array_push($grades, $result); //Store the data in an array.
		    }
		    	
		    echo json_encode($grades); //Output encoded array to javascript
		  
		    $stmt->close();
		}
    	exit; //Leave php
    }

	//get data and output table
    if (!($stmt = $mysqli->prepare( "SELECT EnrollmentID, CourseID, CourseCredits, CreditsEarned, Grade, Term, Year FROM " . $_SESSION['username'] . " WHERE username = ? ORDER BY Year DESC, Term DESC"))) 
    {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    $stmt->bind_param("s", $_SESSION['username']);

    //execute prepared statement
    if (!$stmt->execute())
    {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    $stmt->bind_result($col1, $col2, $col3, $col4, $col5, $col6, $col7);
    
    //set sums to 0
	$creditsEarnedSum = 0.0;
	$courseCreditsSum = 0.0;
	//For outputting the Charts, and the new input fields
	$modify = "<div class='form'> <form id='modify' action ='main.php' onsubmit='modifyTable();return false;' method='post'><select name = 'modify'><option value='' disabled selected>Course</option>";
	$remove = "<form id='remove' action ='main.php' onsubmit='removeFromTable();return false;' method='post'><select name = 'remove'><option value='' disabled selected>Course</option>";
	
	//begin html table
	$table = "<br><div><table>";
	$table .= "<tr>";//create row
	$table .= "<td>" . "Course Number" . "</td>";
	$table .= "<td>" . "Course Credits" . "</td>";
	$table .= "<td>" . "Credits Earned" . "</td>";
	$table .= "<td>" . "Grade" . "</td>";
	$table .= "<td>" . "Term" . "</td>";
	$table .= "<td>" . "Year" . "</td>";
	$table .= "</tr>"; //end row

	//fetches the elements of the query
	while ($stmt->fetch()) {

		//Gets the correct terms
		if ($col6 === 2) {
	    	$term = "Summer";
	    }
	    elseif ($col6 === 3) {
	    	$term = "Fall";
	    }
		elseif ($col6 === 0) {
	    	$term = "Winter";
	    }
	    elseif ($col6 === 1) {
	    	$term = "Spring";
	    }

	    $table .= "<tr>";//create row
	    //column for each item.
	    $table .= "<td>" . $col2 . "</td>";
	    $table .= "<td>" . $col3 . "</td>";
	    $table .= "<td>" . number_format($col4, 2, '.', '') . "</td>";
	    $table .= "<td>" . $col5 . "</td>";
	    $table .= "<td>" . $term . "</td>";
	    $table .= "<td>" . $col7 . "</td>";
	    $table .= "</tr>"; //end row

	    $creditsEarnedSum += $col4; //calculates sum
	    $courseCreditsSum += $col3; //calculates sum

	    $modify .= "<option value='" . $col1 . "'>" . $col2 . "</option>";
	    $remove .= "<option value='" . $col1 . "'>" . $col2 . "</option>";
	}

	$stmt->close(); 

	//Adds the selection menus for the modify option.
	$modify .= "</select>
	<select name='grade'>
	  <option value='' disabled selected>Grade</option>
	  <option value='A'>A</option>
	  <option value='A-'>A-</option>
	  <option value='B+'>B+</option>
	  <option value='B'>B</option>
	  <option value='B-'>B-</option>
	  <option value='C+'>C+</option>
	  <option value='C'>C</option>
	  <option value='C-'>C-</option>
	  <option value='D+'>D+</option>
	  <option value='D'>D</option>
	  <option value='D-'>D-</option>
	  <option value='F'>F</option>
	</select>
	<select name='term'>
	  <option value='' disabled selected>Term</option>
	  <option value='2'>Summer</option>
	  <option value='3'>Fall</option>
	  <option value='0'>Winter</option>
	  <option value='1'>Spring</option>
	</select>
	<select name='yearpicker' id='yearpicker'>
	  <option value='' disabled selected>Year</option>";

	//Creates a date picker for the modify option
	for ($i = 2014; $i > 1900; $i--) {
		$modify .= "<option value='" . $i . "'>" . $i . "</option>";
	}

	$modify .= "</select> <input type='submit' class='button' value='Modify entry'></form>";
	$remove .= "</select> <input type='submit' value='Remove from table'></form></div>";
	
	$table .= "</table></div>"; //end html table

	//Only output the selection fields if there is data in the chart.
	if ($col1 > 0) {
		echo $modify;
		echo $remove;
		echo $table;
	}

	//Outputs the blank table
	

	//Outputs the GPA if they have taken courses.
	if ($courseCreditsSum !== 0.0) {
	//output the sum
		echo "<br><p id ='gpa' class='smalltext'>Your GPA is ";
		echo number_format($creditsEarnedSum / $courseCreditsSum, 2, '.', '');
		echo ".</p>";
		//return;
	}

	//Outputs the error
	if (isset($error)) {
		echo "<p id ='error' class='smalltext'>";
		echo $error;
		echo "</p>";
		return;
	}
}
else {	
?>
<!DOCTYPE html>
<html>
<head>
<script src="jquery-1.11.0.js"></script>
<script src="amcharts.js" type="text/javascript"></script>
<script src="pie.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css">
<title>Final Project</title>
<script>
//This runs on load. Gets the data to load from the server and displays it.
$(document).ready(function() {$.post('main.php', function(data, textStatus, jqXHR){
        $('#output').html(data);
        displayChart(); //Display the chart.
        }, 'html');

	
});

function addToTable() {
    //Runs post to main.php, with specified data to add, and success function writes the results to the output div
    // clear errors
    $('#pageerror').html("");
    if (document.getElementById("error")) {
    	$('#error').html("");
    }

    var request_data = $('#input').serialize(); //puts data in right format for php

    if (request_data.length < 44) {
    	$('#pageerror').html("<p id ='error' class='smalltext'>Must enter all fields.</p>");
    	return;
    }

    $.post('main.php', request_data, function(data, textStatus, jqXHR){
        $('#output').html(data);
        displayChart(); //Display the chart.
        }, 'html');  
}

function removeFromTable() {
    //Runs post to main.php, with specified data, and success function writes the remaining results to the output div
    // clear errors
    $('#pageerror').html("");
    if (document.getElementById("error")) {
    	$('#error').html("");
    }

    var request_data = $('#remove').serialize(); //puts data in right format for php

	if (request_data.length === 0) {
    	$('#pageerror').html("<p id ='error' class='smalltext'>Select a course to remove.</p>")
    	return;
    }

    $.post('main.php', request_data, function(data, textStatus, jqXHR){
        $('#output').html(data);
        displayChart(); //Display the chart.
        }, 'html');
}

function modifyTable() {
    //Runs post to main.php, with specified data, and success function writes the modified results to the output div
    // clear errors
    $('#pageerror').html("");
    if (document.getElementById("error")) {
    	$('#error').html("");
    }

  	var request_data = $('#modify').serialize(); //puts data in right format for php

    if (request_data.length < 40) {
    	$('#pageerror').html("<p id ='error' class='smalltext'>Must enter all fields.</p>")
    	return;
    }

    $.post('main.php', request_data, function(data, textStatus, jqXHR){
        $('#output').html(data);
        displayChart(); //Display the chart.
        }, 'html');    
}

function displayChart() { 
		//Set the count variables to zero.
		var aCount = 0;
		var aMCount = 0;
		var bPCount = 0;
		var bCount = 0;
		var bMCount = 0;
		var cPCount = 0;
		var cCount = 0;
		var cMCount = 0;
		var dPCount = 0;
		var dCount = 0;
		var dMCount = 0;
		var fCount = 0;

	    $.post('main.php', "graph=0", function(data, textStatus, jqXHR) {

	    	//If returns an empty JSON string then we return
	    	if (data === "[]") {
	    		document.getElementById("chartdiv").innerHTML = ""; 
	    		return;
	    	}

	    	//Gets the data from the server.
	    	data = JSON.parse(data);

	    	var temp = "";

	    	//counts the number of each grade for pie chart.
	    	for (var i = 0; i < data.length; i++) {
	    		
	    		temp = data[i];

       			if (temp === "A") {
       				aCount++;
       			} 
       			else if (temp === "A-") {
       				aMCount++;
       			} 
       			else if (temp === "B+") {
       				bPCount++;
       			} 
       			else if (temp === "B") {
       				bCount++;
       			} 
       			else if (temp === "B-") {
       				bMCount++;
       			} 
       			else if (temp === "C+") {
       				cPCount++;
       			} 
       			else if (temp === "C") {
       				cCount++;
       			} 
       			else if (temp === "C-") {
       				cMCount++;
       			} 
       			else if (temp === "D+") {
       				dPCount++;
       			} 
       			else if (temp === "D") {
       				dCount++;
       			}
       			else if (temp === "D-") {
       				dMCount++;
       			} 
       			else if (temp === "F") {
       				fCount++;
       			} 
    		}
    		//Creates a pie chart of the earned grades. Taken from amchart.js templates and modified to fit my needs.
    		AmCharts.makeChart("chartdiv", {
                "type": "pie",
                "dataProvider": [{
                    "grade": "A",
                        "count": aCount
                }, {
                    "grade": "A-",
                        "count": aMCount
                }, {
                    "grade": "B+",
                        "count": bPCount
                }, {
                    "grade": "B",
                        "count": bCount
                }, {
                    "grade": "B-",
                        "count": bMCount
                }, {
                    "grade": "C+",
                        "count": cPCount
                }, {
                    "grade": "C",
                        "count": cCount
                }, {
                    "grade": "C-",
                        "count": cMCount
                }, {
                    "grade": "D+",
                        "count": dPCount
                }, {
                    "grade": "D",
                        "count": dCount
                }, {
                    "grade": "D-",
                        "count": dMCount
                }, {
                    "grade": "F",
                        "count": fCount
                }],
                "titleField": "grade",
                "valueField": "count",
                "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                "legend": {
                    "align": "center",
                    "markerType": "circle"
                }
            });
       	}, 'html');
}
</script>
</head>
<h1>Course Grade Tracker</h1>
<div id="logout" class = "right">
<a href='logout.php'>Log out <?php echo $_SESSION['username']; ?></a>
</div>
<br>
<div class='form'>
<form id="input" action ="main.php" onsubmit="addToTable();return false;" method="post">
	<select name="course">
	  <option value="" disabled selected>Course</option>
	  <option value="CS 165">CS 165</option>
	  <option value="CS 225">CS 225</option>
	  <option value="CS 261">CS 261</option>
	  <option value="CS 271">CS 271</option>
	  <option value="CS 352">CS 352</option>
	  <option value="CS 494">CS 494</option>
	  <option value="CS 275">CS 275</option>
	  <option value="CS 344">CS 344</option>
	  <option value="CS 325">CS 325</option>
	  <option value="CS 361">CS 361</option>
	  <option value="CS 362">CS 362</option>
	  <option value="CS 372">CS 372</option>
	  <option value="CS 496">CS 496</option>
	  <option value="CS 419">CS 419</option>
	</select>
	<select name="grade">
	  <option value="" disabled selected>Grade</option>
	  <option value="A">A</option>
	  <option value="A-">A-</option>
	  <option value="B+">B+</option>
	  <option value="B">B</option>
	  <option value="B-">B-</option>
	  <option value="C+">C+</option>
	  <option value="C">C</option>
	  <option value="C-">C-</option>
	  <option value="D+">D+</option>
	  <option value="D">D</option>
	  <option value="D-">D-</option>
	  <option value="F">F</option>
	</select>
	<select name="term">
	  <option value="" disabled selected>Term</option>
	  <option value="2">Summer</option>
	  <option value="3">Fall</option>
	  <option value="0">Winter</option>
	  <option value="1">Spring</option>
	</select>
	<select name="yearpicker" id="yearpicker1">
	  <option value="" disabled selected>Year</option>
	</select>
	<script>
			//Inserts the data picker input field
			for (i = new Date().getFullYear(); i > 1900; i--) {
			    $('#yearpicker1').append($('<option />').val(i).html(i));
			}
	</script>
	<input type="submit" value="Add to table" class="button">
</form>
</div>
<div id="output"></div>
<div id="pageerror"></div>
<div id="chartdiv" style="width: 640px; height: 400px;"></div>
</html>
<?php
}
?>