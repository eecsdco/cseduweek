<html>
<head>
<title>Computer Science Education Week</title>
<link rel="stylesheet" type="text/css" href="/cseduweek/style.css" />
</head>
<body>

<div class="content">


<h2><img src="/cseduweek/CSE_Banner.jpg"></h2>
<h2>Puzzle Two</h2>

<?php
	include_once("../common.php");
	// get uniquename
	$user = $_SERVER['REMOTE_USER'];
	$name = $major = "";
	// get real name
	if ( $details = getUser($user) ) {
		$name = $details["name"];
		$major = $details["major"];	
	}
	if ( $name == "" ) {
		if ( $result = ldap_query($user,"cn") ) $name = $result;
	}
?>

<p>Congratulations, you are a secret scanning decoding wizard! </p>
<p>Please only sign this page once and don't tell your friends about this page unless they find it for themselves.</p>

<form action = "../form_process.php" method = "post">
<table class="form">
<tr>
	<td width="150px">Uniquename:</td>
	<td>
		<input id="user_string" name="user_string" type="text" disabled='disabled' value='<?=$user?>' size="90" />
	</td>
</tr>
<tr>
	<td>Name:</td>
	<td>
		<input id="name" name="name" type="text" value="<?=$name?>" size="90" />
	</td>
</tr>
<tr>
	<td>Major:</td>
	<td>
		<input id="major" name="major" type="text" value="<?=$major?>" size="90" />
	</td>
</tr>
<tr>
	<td>Date:</td>
	<td>
		<input id="time_string" name="time_string" type="text" disabled="disabled" value='<?=date("m/d/Y h:i A")?>' size="90" />
	</td>
</tr>

</table>

<input name="user" value="<?=$user?>" type="hidden" />
<input name="action" value="add" type="hidden" />
<input name="puzzle" value="2" type="hidden" />

<p>Sign below to save your name to the results list.</p>
<input type="submit" value="Sign Now" />

</form>

</div>

</body>
</html>