<html>
<head>
<title>Computer Science Education Week</title>
<link rel="stylesheet" type="text/css" href="/cseduweek/style.css" />
</head>
<body>

<div class="content">



<h2><img src="/cseduweek/CSE_Banner.jpg"></h2>
<br />

<?php
	// list all result years from 2010 until current year
	for ( $year = intval(date("Y")); $year >= 2010 ; $year-- ) {
		echo "<h2><a href='results/$year/'>$year Puzzle Results</a></h2>";
	}
?>


</div>

</body>
</html>
