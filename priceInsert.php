<?php 
session_start();

if ($_SESSION['user_id']){
	include 'header.php';
	//RETURNS TRUE WHEN COOKIES ARE SET
	function db_connect1(){
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
	function db_close1(){
		global $connection;
		mysql_close($connection);
	}
	db_connect1(); //CONNECT TO DATABASE RIGHT AWAY
				
	echo"<main id='center' class='column'>
	<article>";
	
	//echo welcome "name of user"
	$sessionID = $_SESSION['user_id'];
	//echo "Welcome, "."$sessionID";
	$queryName = "SELECT phpro_username FROM phpro_users WHERE phpro_user_id = $sessionID";
	$result = mysql_query($queryName);
	$sessionName = mysql_fetch_array($result);
	echo "<p> Welcome ".$sessionName['phpro_username']."</br>
	Please enter the prices in the table below</p>";
	
	if(!isset($_GET['vendorName'])|| $_GET['vendorName']=="Choose one" ){ //it also needs to include when it is not set to choose one	
		//form to find vendor	
		echo"<form action='priceInsert.php' name='vendorSelectForm' method='get'>";
		
		$query = "SELECT State FROM vendors GROUP BY State";
		$state = mysql_query($query);
		$numStates = mysql_numrows($state);
		
		//state selection dropdown
		echo"<select name='vendorState' id='vendorStateForm' onchange='this.form.submit()'>";
		
		if(isset($_GET['vendorState']) && $_GET['vendorState']!="Choose one"){ //state has been previously set
			echo"<option selected='selected'>".$_GET['vendorState']."</option>";
			for($s=0;$s<$numStates;++$s) { 
				$stateVal = mysql_result($state,$s,"State");
				if($stateVal!=$_GET['vendorState']) //does not print the selected state twice
					echo'<option value="'.$stateVal.'">'.$stateVal.'</option>';
			}echo"</select>";
			//if the state has been selected then the next queries can continue
			$query = "SELECT County FROM vendors WHERE State = '".$_GET['vendorState']."' GROUP BY County";
			$county = mysql_query($query);
			$numCounties = mysql_numrows($county);
				if(isset($_GET['vendorCounty']) && $_GET['vendorCounty']!="Choose one"){ //state has been previously set
					echo"<br><select name='vendorCounty' id='vendorCountyForm' onchange='this.form.submit()'>";
					echo"<option selected='selected'>".$_GET['vendorCounty']."</option>";
					for($c=0;$c<$numCounties;++$c) { 
						$countyVal = mysql_result($county,$c,"County");
						if($countyVal!=$_GET['vendorCounty']) //does not print the selected county twice
							echo'<option value="'.$countyVal.'">'.$countyVal.'</option>';
					}echo"</select>";
					$query = "SELECT Location FROM vendors WHERE County = '".$_GET['vendorCounty']."' GROUP BY Location";
					$location = mysql_query($query);
					$numLocations = mysql_numrows($location);

						if(isset($_GET['vendorLocation'])&&$_GET['vendorLocation']!="Choose one"){ //state has been previously set
							echo"<br><select name='vendorLocation' id='vendorLocationForm' onchange='this.form.submit()'>";
							echo"<option selected='selected'>".$_GET['vendorLocation']."</option>";
							for($l=0;$l<$numLocations;++$l) { 
								$locationVal = mysql_result($location,$l,"Location");
								if($locationVal!=$_GET['vendorLocation']) //does not print the selected county twice
									echo'<option value="'.$locationVal.'">'.$locationVal.'</option>';
							}echo"</select>";
							$query = "SELECT Name FROM vendors WHERE Location = '".$_GET['vendorLocation']."' GROUP BY Name";
							$vendors = mysql_query($query);
							$numNames = mysql_numrows($vendors);

							if(!isset($_GET['vendorName'])){ //state has been previously set
								
								echo"<br><select name='vendorName' id='vendorNameForm' onchange='this.form.submit()'>";
								echo"<option selected='selected'>Choose one</option>";
								for($n=0;$n<$numNames;++$n) { 
									$nameVal = mysql_result($vendors,$n,"Name");
									echo'<option value="'.$nameVal.'">'.$nameVal.'</option>';
								}echo"</select>";
							}//end of vendor selection
								//*go to line 147 or wherever*
						}else{ //county has not been previously set but state has
						
							echo"<br><select name='vendorLocation' id='vendorLocationForm' onchange='this.form.submit()'>";
							echo"<option selected='selected'>Choose one</option>";
							for($l=0;$l<$numLocations;++$l) { 
								$locationVal = mysql_result($location,$l,"Location");
								echo'<option value="'.$locationVal.'">'.$locationVal.'</option>';
							}echo"</select>";
						}//end of location selection
						
				}else{ //county has not been previously set but state has	
					echo"<br><select name='vendorCounty' id='vendorCountyForm' onchange='this.form.submit()'>";
					echo"<option selected='selected'>Choose one</option>";
					for($c=0;$c<$numCounties;++$c) { 
						$countyVal = mysql_result($county,$c,"County");
						echo'<option value="'.$countyVal.'">'.$countyVal.'</option>';
					}echo"</select>";
				}//end of county selection
				
		}else{ //state has not been previously set
			echo"<option selected='selected'>Choose one</option>";
			for($s=0;$s<$numStates;++$s) { 
				$stateVal = mysql_result($state,$s,"State");
				echo'<option value="'.$stateVal.'">'.$stateVal.'</option>';
			}echo"</select>";
		} //end of state selection
		echo"</form><br>";
	}else{ //t
		//finds all product names that could happen
		$query = "SELECT Name FROM products GROUP BY Name";
		$products = mysql_query($query);
		$numproducts = mysql_numrows($products);
		echo'<form action="priceInsert.php" name="priceInsertData" method="get">';
		echo'<input type="hidden" name="vendorName" value="'.$_GET["vendorName"].'"><br>
		<h1>'.$_GET["vendorName"].'</h1>';
		//productName selection dropdown
		echo"<select name='productName' id='productNameForm' onchange='this.form.submit()'>";
		
		if(isset($_GET['productName']) && $_GET['productName']!="Choose one"){ //state has been previously set
			echo"<option selected='selected'>".$_GET['productName']."</option>";
			for($p=0;$p<$numproducts;++$p) { 
				$nameVal = mysql_result($products,$p,"Name");
				if($stateVal!=$_GET['productName']) //does not print the selected state twice
					echo'<option value="'.$nameVal.'">'.$nameVal.'</option>';
			}echo"</select>";
			//if the state has been selected then the next queries can continue
			$query = 'SELECT Quantity FROM products WHERE Name = "'.$_GET["productName"].'" GROUP BY Quantity';
			$quantity = mysql_query($query);
			$numQuantities = mysql_numrows($quantity);
				if(isset($_GET['productQuantity']) && $_GET['productQuantity']!="Choose one"){ //state has been previously set
					echo"<br><select name='productQuantity' id='vendorCountyForm' onchange='this.form.submit()'>";
					echo"<option selected='selected'>".$_GET['productQuantity']."</option>";
					for($q=0;$q<$numQuantities;++$q) { 
						$quantityVal = mysql_result($quantity,$q,"Quantity");
						if($quantityVal!=$_GET['productQuantity']) //does not print the selected county twice
							echo'<option value="'.$quantityVal.'">'.$quantityVal.'</option>';
					}echo"</select>";
					$query = 'SELECT Volume FROM products WHERE Quantity = "'.$_GET["productQuantity"].'" 
						AND Name = "'.$_GET["productName"].'" GROUP BY Volume';
					$volume = mysql_query($query);
					$numVolumes = mysql_numrows($volume);

						if(isset($_GET['productVolume'])&&$_GET['productVolume']!="Choose one"){ //state has been previously set
							echo"<br><select name='productVolume' id='vendorLocationForm' onchange='this.form.submit()'>";
							echo"<option selected='selected'>".$_GET['productVolume']."</option>";
							for($v=0;$v<$numVolumes;++$v) { 
								$volumeVal = mysql_result($volume,$v,"Volume");
								if($volumeVal!=$_GET['productVolume']) //does not print the selected county twice
									echo'<option value="'.$volumeVal.'">'.$volumeVal.'</option>';
							}echo"</select>";
							$query = 'SELECT Cans FROM products WHERE Volume = "'.$_GET["productVolume"].'" 
								AND Quantity = "'.$_GET["productQuantity"].'"
								AND Name = "'.$_GET["productName"].'" GROUP BY Cans';
							$cans = mysql_query($query);
							$numCans = mysql_numrows($cans);
							
							if(isset($_GET['productCans'])&&$_GET['productCans']!="Choose one"){ //state has been previously set
								echo"<br><select name='productCans' id='productCanForm' onchange='this.form.submit()'>";
								echo"<option selected='selected'>".$_GET['productCans']."</option>";
								for($c=0;$c<$numCans;++$c) { 
									$cansVal = (mysql_result($cans,$c,"Cans")==0)?"Bottles":"Cans";
									if($cansVal!=$_GET['productCans']) //does not print the selected county twice
										echo'<option value="'.$cansVal.'">'.$cansVal.'</option>';
								}echo"</select>";
								
								//you are permitted to use the price insert box
								if(isset($_GET['price'])){
									
									echo"<br><input type='text' name='price'><br>
									<input type='submit' name='submit'><br>
									<p>ok you have entered a price. i shall insert...</p>";
									//gets the vendor ID that we are inserting into
									$query = 'SELECT ID FROM vendors WHERE Name = "'.$_GET['vendorName'].'"';
									$vendorQuery = mysql_query($query);
									$vendorID = mysql_result($vendorQuery,0,"ID"); //only selects 1 row (only expects to find 1 name matching)
									//gets the location for the location price table
									$query = 'SELECT Location FROM vendors WHERE Name = "'.$_GET['vendorName'].'"';
									$locationQuery = mysql_query($query);
									$location = mysql_result($locationQuery,0,"Location"); //only selects 1 row (only expects to find 1 name matching)
									$cans=($_GET['productCans']=="Cans")?1:0;
									$query = 'SELECT ID FROM products WHERE Name = "'.$_GET["productName"].'" 
										AND Quantity = "'.$_GET["productQuantity"].'" 
										AND Volume = "'.$_GET["productVolume"].'" 
										AND Cans = "'.$cans.'"'; //finds productID for price insertion
									$productQuery = mysql_query($query) //attempts to find product
										or die ('cannot find product'.$_GET["productName"].mysql_error());
										
									if (mysql_num_rows($productQuery)==0){
										echo "<p style='color:red;'>Product does not exist like that in the product table</p>"; 
										echo "<p style='color:red;'>Price insertion failed!</p>"; 
									}else{
										$productID = mysql_result($productQuery,0,"ID");
										$createTable="CREATE TABLE IF NOT EXISTS `".$location."` (
										        `priceID` int(11) NOT NULL, 
											`VendorID` int(11) NOT NULL,
											`ProductID` int(11) NOT NULL,
											`Price` double NOT NULL,
											`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
											) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;";
											
										$insertPrice=" INSERT INTO `".$location."` (`VendorID`, `ProductID`, `Price`, `timestamp`) VALUES
										($vendorID, $productID,".$_GET['price'].",NULL)";
										
										echo"<br>";
										if (mysql_query($createTable)) 
										{
											if(mysql_query("ALTER TABLE `".$location."` ADD PRIMARY KEY(`priceID`);")){
												if(mysql_query("ALTER TABLE `".$location."`
														MODIFY `priceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;")){
													 if (mysql_query($insertPrice)) {
														 echo "<p style='color:green;'>Price inserted successfully!</p>";
													 }else{
														echo "<p style='color:red;'>Price insertion failed!</p>"; 
													 }
												}else{
													echo "<p style='color:red;'>Error auto_incrementing the priceID in table '".$location."'</p>". mysql_error ();
												}
											}else{
												echo "<p style='color:red;'>Error adding primary key to table '".$location."'</p>". mysql_error ();
											}
										}else{
											echo "<p style='color:red;'>Error in validating table: '".$location."'</p>". mysql_error ();
										}
									}
					
								}else{ //insertion will not happen untl you press submit
									echo"<br><input type='text' name='price'><br>
									<input type='submit' name='submit'><br>";
								} //end of price insertion
					
							}else{ //cans have not been previously set but volume has
								echo"<br><select name='productCans' id='vendorLocationForm' onchange='this.form.submit()'>";
								echo"<option selected='selected'>Choose one</option>";
								for($c=0;$c<$numCans;++$c) { 
									$cansVal = (mysql_result($cans,$c,"Cans")==0)?"Bottles":"Cans";
									echo'<option value="'.$cansVal.'">'.$cansVal.'</option>';
								}echo"</select>";
							}//end of cans selection

						}else{ //volume has not been previously set but quantity has
						
							echo"<br><select name='productVolume' id='vendorLocationForm' onchange='this.form.submit()'>";
							echo"<option selected='selected'>Choose one</option>";
							for($v=0;$v<$numVolumes;++$v) { 
								$volumeVal = mysql_result($volume,$v,"Volume");
								echo'<option value="'.$volumeVal.'">'.$volumeVal.'</option>';
							}echo"</select>";
						}//end of volume selection
						
				}else{ //quantity has not been previously set but the product name has	
					echo"<br><select name='productQuantity' id='vendorCountyForm' onchange='this.form.submit()'>";
					echo"<option selected='selected'>Choose one</option>";
					for($q=0;$q<$numQuantities;++$q) { 
						$quantityVal = mysql_result($quantity,$q,"Quantity");
						echo'<option value="'.$quantityVal.'">'.$quantityVal.'</option>';
					}echo"</select>";
				}//end of quantity selection
				
		}else{ //productname has not been previously set
			echo"<option selected='selected'>Choose one</option>";
			for($p=0;$p<$numproducts;++$p) { 
				$nameVal = mysql_result($products,$p,"Name");
				echo'<option value="'.$nameVal.'">'.$nameVal.'</option>';
			}echo"</select>";
		} //end of state selection
		echo"</form><br>";
	} //end of else
		
	echo"</article>								
	</main>";
	db_close1(); 
	include 'footer.php';

}else{
die("<div style='font-family: Verdana; font-size: 12px;>"."You must be logged in!"."</div>");
}
?>
