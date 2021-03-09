<?php

$connec = mysqli_connect("localhost", "root", "","project") or die ("Could not connect: " . mysql_error()); 

if ($_SERVER["REQUEST_METHOD"] == "GET") {

$dis = $_GET["dis"];

if( $dis == "All"){
$sqlreg = "select count(Name) as var1 from  `person`";
$sqldose= "select count(NID) as var2 from  `dosage_info` where `Dose2` is null";
$sqlvac= "select count(NID) as var3 from  `status` where Vaccinated=1";
}

else{

$sqlreg =  "select count(NID) as var1 from person where district='$dis'";
$sqldose= " select count(NID) as var2 from dosage_info where NID in ( select NID from person where district= '$dis') and Dose2 is null";
$sqlvac= "select count(NID) as var3 from  `status` where Vaccinated=1 and NID in (select NID from person where district= '$dis') ";

}

$r1 = mysqli_query($connec, $sqlreg) or die("query failed, try again later". mysqli_error($connec));
$r2 = mysqli_query($connec, $sqldose) or die("query failed, try again later". mysqli_error($connec));
$r3 = mysqli_query($connec, $sqlvac) or die("query failed, try again later". mysqli_error($connec));

$v1 = mysqli_fetch_array($r1)['var1'];
$v2 = mysqli_fetch_array($r2)['var2'];
$v3 = mysqli_fetch_array($r3)['var3'];

echo $v1 . " People Registered.<br>".
	$v2 . " People have taken the 1st dose.<br>".
	$v3 . " People Vaccinated.";


}

mysqli_close($connec);

?>
