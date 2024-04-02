<?php 

//TO DO
//print menu
//add assignemnt
//list assignments
//	not completed
//	completed
//	all
//change assignment due date
//mark completed
//get connection

function printMenu() {
	echo "1. Add assignment\n";
	echo "2. List assignments\n";
	echo "3. Completed assignments\n";
	echo "4. Uncomplete Assignments\n";
	echo "5. Change due date\n";
	echo "6. Change Completion\n";
	echo "7. Exit\n";
}

function getConnection() {
	// Setup variables to access DB.
	$server = 'localhost';
	$user   = 'braden';
	$pass   = 'M@rtyRock$';
	$db     = 'braden_HappyCruise';

	// Create a DB connection.
	$conn = new mysqli($server, $user, $pass, $db);

	if ($conn->connect_error) {
		die('Connection failed: '.$conn->connect_error);
	}
	return $conn;
}

do {

	printMenu();
	echo"\n";
	echo "Enter your choice: ";
	$choice = trim(fgets(STDIN));

	if ($choice == 1) addAssignment();
	else if ($choice == 2) listAssignments();
	else if ($choice == 3) listCompleted();
	else if ($choice == 4) listUncomplete();
	else if ($choice == 5) changeDueDate();
	else if ($choice == 6) changeCompletion();
	
} while ($choice != 7);

function addAssignment() {
	$conn = getConnection();
	echo 'Enter the Assignment Name: ';
	$assignment  = $conn->real_escape_string(trim(fgets(STDIN)));
	echo 'Enter the class: ';
	$class  = $conn->real_escape_string(trim(fgets(STDIN)));
	echo 'Enter the due date (dd-mm-yyyy hh:mm:ss): ';
	$due  = strtotime(trim(fgets(STDIN)));

	$sql = 'INSERT INTO assignmentTracker(assignmentName, class, dueDate) ';
	$sql .= "VALUES('$assignment', '$class', FROM_UNIXTIME($due))";

	if ($conn->query($sql)) {
		echo "Row inserted successfully.\n";
	} else {
		echo "Error inserting contact.\n";
		echo $conn->error."\n";
	}

	$conn->close();	

}

function listAssignments() {
	$conn = getConnection();

	$sql = "select * from assignmentTracker";

	$results = $conn->query($sql);
	if($results->num_rows > 0) {
		printf("\n%-5s%-30s%-10s%-20s%10s\n", 'ID', 'Assignment', 'Class', 'Due Date', 'Completed');
		while($row = $results->fetch_assoc()) {
			$id = $row['id'];
			$assign = $row['assignmentName'];
			$class = $row['class'];
			$dueDate = $row['dueDate'];
			$completed = $row['complete'];

			printf("\n%-5s%-30s%-10s%-20s%10s\n", $id, $assign, $class, $dueDate, $completed);
		}
		echo"\n";
	} else {
		echo"No assignments found\n";
	}
	$conn->close();	
}

function listCompleted() {
	$conn = getConnection();

	$sql = "select * from assignmentTracker where complete = 1;";

	$results = $conn->query($sql);
	if($results->num_rows > 0) {
		printf("\n%-5s%-30s%-10s%-20s%10s\n", 'ID', 'Assignment', 'Class', 'Due Date', 'Completed');
		while($row = $results->fetch_assoc()) {
			$id = $row['id'];
			$assign = $row['assignmentName'];
			$class = $row['class'];
			$dueDate = $row['dueDate'];
			$completed = $row['complete'];

			printf("\n%-5s%-30s%-10s%-20s%10s\n", $id, $assign, $class, $dueDate, $completed);
		}
		echo"\n";
	} else {
		echo"No assignments found\n";
	}
	$conn->close();	
}

function listUncomplete() {
	$conn = getConnection();

	$sql = "select * from assignmentTracker where complete = 0;";

	$results = $conn->query($sql);
	if($results->num_rows > 0) {
		printf("\n%-5s%-30s%-10s%-20s%10s\n", 'ID', 'Assignment', 'Class', 'Due Date', 'Completed');
		while($row = $results->fetch_assoc()) {
			$id = $row['id'];
			$assign = $row['assignmentName'];
			$class = $row['class'];
			$dueDate = $row['dueDate'];
			$completed = $row['complete'];

			printf("\n%-5s%-30s%-10s%-20s%10s\n", $id, $assign, $class, $dueDate, $completed);
		}
		echo"\n";
	} else {
		echo"No assignments found\n\n\n";
	}

	$conn->close();	
}

function changeDueDate() {
	$conn = getConnection();

	echo "Enter assignment ID: ";
	$id = $conn->real_escape_string(trim(fgets(STDIN)));

	echo 'Enter the due date (dd-mm-yyyy hh:mm:ss): ';
	$date  = strtotime(trim(fgets(STDIN)));

	$sql = "UPDATE assignmentTracker SET dueDate = FROM_UNIXTIME($date) where id = $id";

	if ($conn->query($sql)) {
		echo "Date updated inserted successfully.\n";
	} else {
		echo "Error updating due date.\n";
		echo $conn->error."\n";
	}

	$conn->close();	
}

function changeCompletion() {
	$conn = getConnection();

	echo "Enter assignment ID: ";
	$id = $conn->real_escape_string(trim(fgets(STDIN)));

	$sql = "UPDATE assignmentTracker SET complete = not complete where id = '$id'";

	if ($conn->query($sql)) {
		echo "Assignment set to complete\n";
	} else {
		echo "Error updating completion\n";
		echo $conn->error."\n";
	}

	$conn->close();	

}
?>
