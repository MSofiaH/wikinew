<?php
//Sample processing script for your server:
//Will be called with PlagScan unique identifier as querystring after check is completed.
//$_SERVER["QUERY_STRING"]

//Note: PlagScan will only call your script, but not necessarily wait for it to finish.
//If using PHP you might want to use "ignore_user_abort(true);" to avoid interruption.

$yourEmail="[Admin's email address]";


//You also need to include a link to ~/RetrievePlagScanReport.php with the 
mail($yourEmail,"PlagScan notification","Plagiarism analysis complete for: ".$_SERVER["QUERY_STRING"]);

?>
