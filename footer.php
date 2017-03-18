
<?php
	 function db_connect(){
		$db_host = ' ';
		$db_user= ' ';
		$db_pass= ' ';
		//$db_pass='';
		//$db_name= ' ';
		$db_name= ' ';
		global $connection;
		$connection = mysql_connect($db_host,$db_user,$db_pass) 
			or die ("cannot connect to $db_host as $db_user".mysql_error());
		mysql_select_db($db_name) 
			or die ("Cannot open $db_name:".mysql_error());
		return $connection;
	}
	function db_close(){
		global $connection;
		mysql_close($connection);
	}  
	db_connect(); //CONNECT TO DATABASE RIGHT AWAY
 	echo"<nav id='left' class='column'><u><h3 style='text-align:center'> Locations</h3></u>";
			$towns = "SELECT Location FROM vendors GROUP BY Location";
			$states = "SELECT State FROM vendors GROUP BY State";
			$resultTowns = mysql_query($towns);
			$resultStates = mysql_query($states);
			$numOfRowsTowns = mysql_numrows($resultTowns);
			$numOfRowsStates = mysql_numrows($resultStates); //number of different states
			echo"<ul>";
			for ($s=0;$s<$numOfRowsStates;$s++)
			{	$state = mysql_result($resultStates,$s,"State");
				$stateLocQuery = "SELECT Location FROM vendors WHERE State='$state' GROUP BY Location";
				$stateLocations =  mysql_query($stateLocQuery);
				$numOfStateLoc = mysql_numrows($stateLocations);
				
				$validLocationInState = false;
				for ($l=0;$l<$numOfStateLoc;$l++)
						{	$location = mysql_result($stateLocations,$l,"Location");
							$validateLocation="select 1 from `$location` LIMIT 1"; //checks if there are prices at the location
							 $locWithPriceQuery=mysql_query($validateLocation);
							 
							 if($locWithPriceQuery!==false){
								 $validLocationInState = true;
								 break;
							 }
						}
						if($validLocationInState){ //if the state has confirmed to have valid locations, print state name and proceed to check locations
							echo"<li style='width:100%;background-color:white;padding-top:0.5em;padding-bottom:0.5em;padding-left:0.25em;'> ";
							echo "<span style='font-size:1.42em;margin-left:2%;margin-top:0.5em;background-color:white;'>$state</span> 
							<ol style='list-style-type: none;'>";
							for ($l=0;$l<$numOfStateLoc;$l++)  //checks all locations in state for validity
							{	$location = mysql_result($stateLocations,$l,"Location");
								$validateLocation="select 1 from `$location` LIMIT 1"; //checks if there are prices at the location
								 $locWithPriceQuery=mysql_query($validateLocation);
								 if($locWithPriceQuery!==false){
									echo"<li>
										<form action='location.php' method='get'>
										
										<input type='submit' name='loc' value='$location' style='border:solid;border-width:1px;height:2em;margin-top:2px;margin-bottom:2px;font-size:1.2em;width:96%;background:#b3d9ff;'/>
										<input type='hidden' name='state' value='$state'/>
										</form>
									    </li>";
								}
							} 
							echo"</ol></li>";
							}
			}
			echo"</ul>"; //end of entire unordered list of states and locations
			
			echo"</nav>"; 
	?>
	
	
		
		<a href="http://www.fratbench.com">
			<div id="right" class="column">
				<h3>Thank You</h3>
				<p> to our supporters</p>
				<img src="images/fratbenchLogo.png">
				<h4>Fratbench.com</h4>
				<p> Serving all your fraternity bench needs </p>
				<p> Benches and Blue Prints </p>
				<p> Click to check out our website: <br> fratbench.com</p>
			</div>
		</a>

</div>

	<div id="footer-wrapper">
		<footer id="footer">
		<a href="login.php">Login</a> <a href = "Logout.php">Logout</a>
		<p> cheapbeerprices.com - Copyright &copy;  <?php echo date("Y"); ?></p>
		</footer>
	</div>

</body>

</html>
