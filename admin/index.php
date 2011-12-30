<html>
<head>
<title>Computer Science Education Week</title>
<link rel="stylesheet" type="text/css" href="/cseduweek/style.css" />
</head>
<body>

<div class="content">

<?php
include_once("../common.php");

if ( !isAdmin($_SERVER['REMOTE_USER']) ) die("Unauthorized user! Go <a href='/cseduweek'>back</a>.");
?>


<h2><img src="/cseduweek/CSE_Banner.jpg"></h2>
<h2>Current Puzzle Results - Admin View</h2>

<?php
if ( isset($_REQUEST["message"]) ) {
	echo "<p class='notice'><strong>".$_REQUEST["message"]."</strong></p>";
}

// show SQL errors
//echo "<pre>";
//print_r($db->errorInfo());
//echo "</pre>";

?>

<table class="results">
<tr>
	<td colspan="2">
		<h3>Top Puzzle Wizards</h3>
		<p>Congratulations to the people on this list! You have successfully found and decoded both of our secret puzzles.</p>
		
		<?php
			$solvers = puzzleBothSolvers();
			if ( count($solvers) > 0 ) {
				echo "<ol>";
				foreach ( $solvers as $user ) {
					$details = getUser($user);
					echo "<li>".$details["name"]." (".$details["major"].")</li>";
				}
				echo "</ol>";
			}
			else echo "<p>No winners yet!</p>";
		?>
		
	</td>
</tr>
<tr>
	<td>
		<h3>Puzzle One Solvers</h3>
		<p>Good job! You've found and solved our first puzzle. Find the second one and move to the list of Top Puzzle Winners!</p>
			
		
		<?php
			$solvers = puzzleOneSolvers();
			if ( count($solvers) > 0 ) {
				echo "<ol>";
				foreach ( $solvers as $user ) {
					$details = getUser($user);
					echo "<li>".$details["name"]." (".$details["major"].") ";
					if ( isAdmin($_SERVER['REMOTE_USER']) ) echo "<a href='../form_process.php?action=remove&puzzle=1&user=$user'>Remove</a>";
					echo "</li>";
				}
				echo "</ol>";
			}
			else echo "<p>No winners yet!</p>";
		?>
		
	</td>
	<td>	
		<h3>Puzzle Two Solvers</h3>
		<p>Good job! You've found and solved our second puzzle. Find the first one and move to the list of Top Puzzle Winners!</p>
		
		<?php
			$solvers = puzzleTwoSolvers();
			if ( count($solvers) > 0 ) {
				echo "<ol>";
				foreach ( $solvers as $user ) {
					$details = getUser($user);
					echo "<li>".$details["name"]." (".$details["major"].") ";
					if ( isAdmin($_SERVER['REMOTE_USER']) ) echo "<a href='../form_process.php?action=remove&puzzle=2&user=$user'>Remove</a>";
					echo "</li>";
				}
				echo "</ol>";
			}
			else echo "<p>No winners yet!</p>";
		?>
		
	</td>
</tr>
</table>

<br />
<small><a href="http://www.eecs.umich.edu/cseduweek">Main</a> | <a href="http://www.eecs.umich.edu/cseduweek/results">Public Results</a></small>

</div>

</body>
</html>