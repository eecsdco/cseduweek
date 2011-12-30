<?php

include_once("common.php");
$message = "";

// USER SUBMIT
if ( isset($_REQUEST["user"]) && isset($_REQUEST["puzzle"]) && isset($_REQUEST["action"]) && isset($_REQUEST["name"]) && isset($_REQUEST["major"])) {
		
	$user = $_REQUEST["user"];
	$name = $_REQUEST["name"];
	$major = $_REQUEST["major"];
	$puzzle = $_REQUEST["puzzle"];
	$action = $_REQUEST["action"];
	
	if ( $action == "add" ) {
		if ( strlen($name) < 2 ) {
			$message = "You must enter a name. Please go back and try again.";
		}
		else if ( strlen($major) < 2 ) {
			$message = "You must enter a major. Please go back and try again.";
		}
		else if ( $puzzle == 1 ) {
			if ( puzzleOneAdd($user,$name,$major) ) $message = "Your submission has been recorded!";
			else $message = "Sorry, we were unable to record your submission. Please remember that you can only sign once.";
		}
		else if ( $puzzle == 2 ) {
			if ( puzzleTwoAdd($user,$name,$major) ) $message = "Your submission has been recorded!";
			else $message = "Sorry, we were unable to record your submission. Please remember that you can only sign once.";
		}
		else $message = "Unknown puzzle.";
	}
	else $message = "Unknown action.";
	$year = date("Y");
	header("Location: http://www.eecs.umich.edu/cseduweek/results/$year/index.php?message=$message");
}

// ADMIN EDIT
else if ( isset($_REQUEST["user"]) && isset($_REQUEST["puzzle"]) && isset($_REQUEST["action"]) ) {
	
	$user = $_REQUEST["user"];
	$puzzle = $_REQUEST["puzzle"];
	$action = $_REQUEST["action"];
	
	if ( $action == "remove" ) {
		if ( $puzzle == 1 ) {
			if ( puzzleOneRemove($user) ) $message = "User has been removed from puzzle 1!";
			else $message = "Sorry, unable to remove user from puzzle 1.";
		}
		else if ( $puzzle == 2 ) {
			if ( puzzleTwoRemove($user) ) $message = "User has been removed from puzzle 2!";
			else $message = "Sorry, unable to remove user from puzzle 2.";
		}
		else $message = "Unknown puzzle.";
	}
	else $message = "Unknown action.";
	
	header("Location: https://www.eecs.umich.edu/cseduweek/admin/index.php?message=$message");
}



?>