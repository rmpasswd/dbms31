<html>
<head> <title>Vaccination Data Manager</title> </head>
<link rel="stylesheet"  href="./style.css"/>
<body>


	<div class=menubar>
		<button class="buttons" onclick="hideothers('reg')" >Registration</button>
		<button class="buttons" onclick="hideothers('chkappnt')" >Check Dates</button>
		<button class="buttons" onclick="hideothers('update')" >Update Info</button>
		<button class="buttons" onclick="hideothers('stats')" >Statistics</button>
	</div>

	<! ------ -->
	<! -- __Registration__-->	
	<div id="reg" class="menu"  style="display:none">
		<h2>Registration</h2>
		<form action="test.php" target="regiframe" method="POST">
			<input type="text" name="nid" placeholder="Your NID number here">
			<input type="text" name="name" placeholder="Name please">
			<input type="text" name="phone" placeholder="Phone number">
			<input type="text" name="district" placeholder="District Name">
			<input type="text" name="subdistrict" placeholder="Sub-district Name">
			<input type="submit" value="register">

		</form>
	  	<iframe name= "regiframe"  style= "border:none"></iframe>
	</div>

	<! --Check __appointment__ date -->
	<div id="chkappnt" class="menu"  style="display:none" >
		<h2>Check Your Appointment Dates:</h2>
		<form action="test.php" target="chkappntframe" method="GET">
			<input type="text" name="nidcheck" placeholder="NID number please.">
			<input type="text" name="namecheck" placeholder="Or just the name.">
			<input type="submit" name="Check Dosage Date">
		</form>
		<iframe name= "chkappntframe" width="auto" style= "border:none"></iframe>
	</div>

	<! -- __update__ info -->
	<div id="update" class="menu"  style="display:none">
		<h2>Update Vaccine Information</h2>
		<form action="test.php" method="POST" target="updateframe">
			<input type="text" name="nidupdate" placeholder="Citizen's NID">
			<input type="submit" value="Generate 2nd Dosage Date">
			<input type="submit" name ="submit" value="Status Vaccinated">
		</form>

		<iframe name= "updateframe" style= "border:none"></iframe>
	</div>

						<! --  STATS -->
	<div id="stats" class="menu"  style="display:none" align="center">
		<h2> Search District-wise. </h2>
		<form action="stats.php" target="statframe" method="GET" display: inline>
			<select name="dis">
				<option value='All' selected>All</option>
		<!-- ALl the district names from database as options -->
		<?php

			$connec = mysqli_connect("localhost", "root", "","project") or die ("Could not connect: " . mysql_error()); 

			$sql = "select distinct district from `person`";

			$result = mysqli_query($connec, $sql) or die("registration failed, try again later". mysqli_error($connec));


			$rowcount = mysqli_num_rows($result);



			for ($i=1; $i < $rowcount; $i++) {

			 	$row = mysqli_fetch_array($result);

				echo "<option value=" . $row["district"] . ">" . $row["district"]. "</option>";
			}


			mysqli_close($connec);				
		?>

			</select>
			<input type="submit" name="submit" value="Get Stats">
		</form>

		<iframe name= "statframe" style= "border:none"></iframe>

	</div>


<script> // code to hide other menu descriptions. nothing to see here.
function hideothers(name) {
  var i;
  var x = document.getElementsByClassName("menu");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  document.getElementById(name).style.display = "block"; 
  
}
</script>

</body>
</html>