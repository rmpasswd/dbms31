<?php

// creating connection to DB
$connec = mysqli_connect("localhost", "root", "","project") or die ("Could not connect: " . mysql_error()); 


if ($_SERVER["REQUEST_METHOD"] == "POST") {

	extract($_POST);

	if (count($_POST) ==5){ // registration

			// add person to person table.
		$sql1 = "INSERT INTO `person` VALUES ('$nid','$name', '$district', '$subdistrict', '$phone')"; 

		mysqli_query($connec, $sql1) or die("registration failed, try again later". mysqli_error($connec));


		$dose1date = gendate(); // dose 1 date.
		$sql2 = "INSERT INTO `dosage_info` VALUES('$nid', '$dose1date', null)";

		mysqli_query($connec, $sql2) or die("Couldn't find a date, try again later". mysqli_error($connec));

		$center = $subdistrict . " Upazilla Health Complex" ;
		$centerloc = $subdistrict . ", " . $district ;

		$sql3 = "INSERT INTO `center` VALUES('$nid', '$center', '$centerloc')";

		mysqli_query($connec, $sql3) or die("Couldn't find a Location, try again later". mysqli_error($connec));


		 // vaccinated status = 0 initially. 
		$sql4 = "INSERT INTO `status`(`NID`,`Vaccinated`) VALUES('$nid',0)";

		mysqli_query($connec, $sql4) or die("status set failed, try again later". mysqli_error($connec)); // 1 to vaccinated status.

		echo "Registration Complete.<br>
		Looking forward to seeing you on <b>" . date_format(date_create($dose1date), 'd M Y') . "</b><br>
		at <b>$center</b>, $centerloc ";

	}

	elseif(count($_POST) == 1){ // gen. dosedate2
		extract($_POST);

		$dose2date = gendate();
		echo "Your next appoinment date is: $dose2date";

		$sql = "update `dosage_info` set `Dose2`= '$dose2date' where `NID`= '$nidupdate'";
		mysqli_query($connec,$sql) or die("date assign failed, try again later". mysqli_error($connec));
	}

	else{ // "vaccinated complete" button is clicked.
		extract($_POST);

		$sql = "update `status` set `Vaccinated`= 1 where `NID`= '$nidupdate'";
		mysqli_query($connec,$sql) or die("status update failed, try again later". mysqli_error($connec));
	}

}

elseif($_SERVER["REQUEST_METHOD"] == "GET") {
	extract($_GET);
	if ($nidcheck != null) { // nid provided.
		$sql = "select Dose1, Dose2, Center_Name, Location from `dosage_info` natural join `center` where `NID` = '$nidcheck' ";
	}
	else{ // name provided, not nid.
		$sql = "select Dose1, Dose2, Center_Name, Location from `dosage_info` natural join `center` where NID = (select NID from person where Name='$namecheck')";
	}
	
	$query = mysqli_query($connec,$sql) or die("Someting is wrong, please try again later". mysqli_error($connec));	

	// got the query result. now make an html table.
	$rows = mysqli_fetch_assoc($query);
	extract($rows);
	
	echo "Your Center is: $Center_Name <br> Location: $Location<br>";
	echo "First Dose Date: ". date_format(date_create($Dose1), "d M Y") ."<br>";


	
	if($Dose2 != NULL){
		echo "Second Dose Date: ". date_format(date_create($Dose2), "d M Y") ."<br>";
	}	

}

// closing db connection
mysqli_close($connec);

function gendate(){

	$start = strtotime("10 February 2021");
	$end = strtotime("30 July 2021");

	return date("Y-m-d", mt_rand($start, $end));
}

?>
