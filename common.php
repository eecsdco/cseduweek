<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$db = null;
$file = "/w/web/cseduweek/records.sqlite";

// connect to and setup database
if ( $db = new PDO("sqlite:".$file) ) {
	$sql = "CREATE TABLE IF NOT EXISTS puzzleone(
		user TEXT UNIQUE NOT NULL, 
		time INTEGER NOT NULL
		)";
	$db->exec($sql);
	$sql = "CREATE TABLE IF NOT EXISTS puzzletwo(
		user TEXT UNIQUE NOT NULL, 
		time INTEGER NOT NULL
		)";
	$db->exec($sql);
	$sql = "CREATE TABLE IF NOT EXISTS users(
		user TEXT UNIQUE NOT NULL,
		name TEXT NOT NULL,
		major TEXT NOT NULL
		)";
	$db->exec($sql);
}
else die("Unable to open database!");

// ADD 
function puzzleOneAdd($user,$name,$major) {
	global $db;
	if ( $user != "" ) {
		$time = time();
		$sql = "INSERT INTO puzzleone (user,time) VALUES ('$user',$time);";
		if ( $db->exec($sql) ) {
			// now, update users table
			if ( updateUser($user,$name,$major) ) return true;
			else return false;
		}
		else return false;
	}
	else return false;
}

function puzzleTwoAdd($user,$name,$major) {
	global $db;
	if ( $user != "" ) {
		$time = time();
		$sql = "INSERT INTO puzzletwo (user,time) VALUES ('$user',$time);";
		if ( $db->exec($sql) ) {
			// now, update users table
			if ( updateUser($user,$name,$major) ) return true;
			else return false;
		}
		else return false;
	}
	else return false;
}

// REMOVE
function puzzleOneRemove($user) {
	global $db;
	if ( $user != "" ) {
		$sql = "DELETE FROM puzzleone WHERE user = '$user';";
		if ( $db->exec($sql) ) return true;
		else return false;
	}
	else return false;
}

function puzzleTwoRemove($user) {
	global $db;
	if ( $user != "" ) {
		$sql = "DELETE FROM puzzletwo WHERE user = '$user';";
		if ( $db->exec($sql) ) return true;
		else return false;
	}
	else return false;
}

// USER FUNCTIONS
function updateUser($user,$name,$major) {
	global $db;
	if ( $user != "" && $name != "" && $major != "" ) {
		$sql = "INSERT OR REPLACE INTO users (user,name,major) VALUES ('$user','$name','$major');";
		if ( $db->exec($sql) ) return true;
		else return false;
	}
	else return false;
}

function getUser($user) {
	global $db;
	if ( $user != "" ) {
		$sql = "SELECT user, name, major FROM users WHERE user = '$user';";
		$results = $db->query($sql);
		if ( $results ) {
			foreach ( $results as $row ) {
				$details = array();
				$details["user"] = $row["user"];
				$details["name"] = $row["name"];
				$details["major"] = $row["major"];
				return $details;
			}
		}
		else return false;
	}
	else return false;
}

// RESULTS
function puzzleOneSolvers() {
	global $db;
	$sql = "SELECT user FROM puzzleone ORDER BY time ASC;";
	$all = array();
	$results = $db->query($sql);
	if ( $results ) {
		foreach ( $results as $row ) {
			if ( isset($row["user"]) ) $all[] = $row["user"];
		}
	}
	return $all;
}

function puzzleTwoSolvers() {
	global $db;
	$sql = "SELECT user FROM puzzletwo ORDER BY time ASC;";
	$all = array();
	$results = $db->query($sql);
	if ( $results ) {
		foreach ( $results as $row ) {
			if ( isset($row["user"]) ) $all[] = $row["user"];
		}
	}
	return $all;
}

function puzzleBothSolvers() {
	global $db;
	// get a list of all users
	$sql = "SELECT user FROM users;";
	$users = array();
	$results = $db->query($sql);
	if ( $results ) {
		foreach ( $results as $row ) {
			if ( isset($row["user"]) ) $users[] = $row["user"];
		}
	}
	// for each user, find all puzzle submissions
	$all = array();
	foreach ( $users as $user ) {
		// get puzzle one submission
		$timeone = 0;
		$sql = "SELECT time FROM puzzleone WHERE user = '$user'";
		$results = $db->query($sql);
		if ( $results ) {
			foreach ( $results as $row ) {
				$timeone = $row["time"];
			}
		}
		// get puzzle two submission
		$timetwo = 0;
		$sql = "SELECT time FROM puzzletwo WHERE user = '$user'";
		$results = $db->query($sql);
		if ( $results ) {
			foreach ( $results as $row ) {
				$timetwo = $row["time"];
			}
		}
		// add to table, if both have been submitted
		if ( $timeone != 0 && $timetwo != 0 ) {
			$latest = ($timeone > $timetwo) ? $timeone : $timetwo;
			$all[] = array("user"=>$user,"time"=>$latest);
		}
	}
	// sort array by time
	usort($all,"compare_times");
	// now, construct the final array
	$all_users = array();
	foreach( $all as $entry ) {
		$all_users[] = $entry["user"];
	}
	return $all_users;	
}

function compare_times($a,$b) {
	if ( $a["time"] == $b["time"] ) return 0;
	return ($a["time"] < $b["time"]) ? -1 : 1;
}

// LDAP
// query user info from UMICH LDAP directory
// ldap_query($user,"cn")
function ldap_query($uid,$entry)
{
	$conn = ldap_connect("ldap.itd.umich.edu","389");
	if ($conn)
	{
		if (ldap_bind($conn))
		{
			$dn = "ou=People,dc=umich,dc=edu";
			$filter = "uid=".$uid;
			$result=ldap_search($conn, $dn, $filter);
		    $entries = ldap_get_entries($conn, $result);
			$result = $entries[0][$entry][0];
			// if entry is private, only 'mail' returns
			if ( $result == "" and $entry == "cn" ) return ldap_query($uid,"mail");
			else return $result;
		}
		else return false;
		ldap_close($conn);
	}
	else return false;
}

// ADMIN USERS
function isAdmin($user) {
	$admin_users = array(
		"mcolf",
		"laura",
		"scrang"
	);
	if ( in_array($user,$admin_users) ) return true;
	else return false;
}

?>