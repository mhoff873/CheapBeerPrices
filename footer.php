
<?php
	function db_connect(){
		$db_host = 'localhost';
		$db_user= 'root';
		$db_pass= 'Beer1234';
		//$db_pass='';
		$db_name= 'CheapBeer';
		//$db_name= 'cheapbeerprices';
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
	//db_connect(); 
	echo"<nav id='left' class='column'>
			<u><h3 style='text-align:center'> Locations</h3></u>";
			$towns = "SELECT Town FROM Vendors GROUP BY Town";
			$states = "SELECT State FROM Vendors GROUP BY State";
			$resultTowns = mysql_query($towns);
			$resultStates = mysql_query($states);
			$numOfRowsTowns = mysql_numrows($resultTowns);
			$numOfRowsStates = mysql_numrows($resultStates); //number of different states
			echo"<ul>";
			for ($s=0;$s<$numOfRowsStates;$s++)
			{	$state = mysql_result($resultStates,$s,"State");
				$stateLocQuery = "SELECT Town FROM Vendors WHERE State='$state' GROUP BY Town";
				$stateLocations =  mysql_query($stateLocQuery);
				$numOfStateLoc = mysql_numrows($stateLocations);
				echo"<li style='width:100%;background-color:white;padding-top:0.5em;padding-bottom:0.5em;padding-left:0.25em;'> 
					<span style='font-size:1.42em;margin-left:2%;margin-top:0.5em;background-color:white;'>$state</span> 
					<ol style='list-style-type: none;'>";
						for ($t=0;$t<$numOfStateLoc;$t++)
						{	$town = mysql_result($stateLocations,$t,"Town");
							echo"<li>
								<form action='location.php' method='post'>
								<input type='hidden' name='state' value='$state'/>
								<input type='submit' name='location' value='$town' style='border:solid;border-width:1px;height:2em;margin-top:2px;margin-bottom:2px;font-size:1.2em;width:96%;background:#BCCE98;'/>
								</form>
							    </li>";
						} 
				   echo"</ol></li>";
			}
			echo"</ul>";
			//db_close();
	?>
	
	
		</nav>
		<a href="http://www.fratbench.com">
			<div id="right" class="column">
				<center>
				<h3>Thank You</h3>
				<p> supporters</p>
				<img src="fratbenchLogo.png" style="width:100px;">
				<h3>Fratbench.com</h3>
				<p> Serving all your fraternity bench needs </p>
				<p> Benches and Blue Prints </p>
				<p> check out our website below: </p>
				</center>
			</div>
		</a>>
</div>

	<div id="footer-wrapper">
		<footer id="footer"><p> cheapbeerprices.com - Copyright &copy;  <?php echo date("Y"); ?></p></footer>
	</div>

</body>

</html>