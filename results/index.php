<?php

// redirect the user to results for the current year
$year = date("Y");
header("Location: http://www.eecs.umich.edu/cseduweek/results/$year");

?>