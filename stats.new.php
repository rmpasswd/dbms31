<?php

$connec = mysqli_connect("localhost", "root", "","project") or die ("Could not connect: " . mysql_error()); 

if ($_SERVER["REQUEST_METHOD"] == "GET") {


	extract($_GET);


	if( $dis == "All"){

		$sqlreg = "select count(Name) as var1 from  `person`";
		$sqldose= "select count(NID) as var2 from  `dosage_info` where `Dose2` is null";
		$sqlvac= "select count(NID) as var3 from  `status` where Vaccinated=1";
		if( $appdate !=null){

			$sqlappdate = "select count(*) as var4 from dosage_info where Dose1= '$appdate' or Dose2= '$appdate' ";

			$r4 = mysqli_query($connec, $sqlappdate) or die("query failed, try again later". mysqli_error($connec));
			$v4 = mysqli_fetch_array($r4)['var4'];
		
			echo "<strong>" . $v4 . " people have an appointment on ".
					date_format(date_create($appdate), "d M Y"). "</strong><br>";	

		}

		if( $tildate !=null && $fromdate != null){

			$sqltildate = " select count(*) as var5 from dosage_info where Dose2 < '$tildate' and Dose2 > '$fromdate' and nid in (select nid from status where vaccinated= 1) ";

			$r5 = mysqli_query($connec, $sqltildate) or die("query failed, try again later". mysqli_error($connec));
			$v5 = mysqli_fetch_array($r5)['var5'];

			echo "<strong>Total " . $v5 . " people are vaccinated from ". 
				date_format(date_create($fromdate), "d M Y"). " to " .
				date_format(date_create($tildate), "d M Y"). "</strong><br>";

		}
		
		$today = date("Y-m-d"); // returns today's date.
		if( $absentdate !=null && $absentdate < $today){

			$sqlabsentnum = " select *, count(*) as var6 from person where nid in (select nid from dosage_info where Dose1 ='$absentdate' and Dose2 is null)";

			$r5 = mysqli_query($connec, $sqlabsentnum) or die("query failed, try again later". mysqli_error($connec));
			$v5 = mysqli_fetch_array($r5)['var6'];
			echo $v5 . " People were absent on " . date_format(date_create($absentdate), "d M Y"). "<br>" ;
			if ($v5 > 0) {
				
					// 3rd feature.
			}

		}

	}

	else{ // A district name is also selected.

		$sqlreg =  "select count(NID) as var1 from person where district='$dis'";
		$sqldose= " select count(NID) as var2 from dosage_info where NID in ( select NID from person where district= '$dis') and Dose2 is null";
		$sqlvac= "select count(NID) as var3 from  `status` where Vaccinated=1 and NID in (select NID from person where district= '$dis') ";
		
		if( $appdate !=null){

			$sqlappdate = "select count(*) as var4 from dosage_info where (Dose1= '$appdate' or Dose2= '$appdate') and nid in (select nid from person where district= '$dis') ";

			$r4 = mysqli_query($connec, $sqlappdate) or die("query failed, try again later". mysqli_error($connec));
			$v4 = mysqli_fetch_array($r4)['var4'];
		
			echo "<strong>" . $v4 . " people of " . $dis . " district have an appointment on ".
					date_format(date_create($appdate), "d M Y"). "</strong><br>";	

		}
		if( $tildate !=null && $fromdate != null){

			$sqlappdate = " select count(*) as var5 from dosage_info where Dose2 < '$tildate' and Dose2 > '$fromdate' and nid in (select nid from status where vaccinated= 1) and nid in (select nid from person where district= '$dis') ";

			$r5 = mysqli_query($connec, $sqlappdate) or die("query failed, try again later". mysqli_error($connec));
			$v5 = mysqli_fetch_array($r5)['var5'];

			echo "<strong>Total " . $v5 . " people of " . $dis . " district are vaccinated from ".
				date_format(date_create($fromdate), "d M Y"). " to " .
				date_format(date_create($tildate), "d M Y"). "</strong><br>"; 
		}


	}

	$r1 = mysqli_query($connec, $sqlreg) or die("query failed, try again later". mysqli_error($connec));
	$r2 = mysqli_query($connec, $sqldose) or die("query failed, try again later". mysqli_error($connec));
	$r3 = mysqli_query($connec, $sqlvac) or die("query failed, try again later". mysqli_error($connec));
	

	$v1 = mysqli_fetch_array($r1)['var1']; // var1 is the column name .
	$v2 = mysqli_fetch_array($r2)['var2'];
	$v3 = mysqli_fetch_array($r3)['var3'];

	echo $v1 . " People Registered.<br>".
		$v2 . " People have taken the 1st dose.<br>".
		$v3 . " People Vaccinated.";


}

mysqli_close($connec);

?>
