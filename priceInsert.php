<?php 
session_start();

if ($_SESSION['user_id']){
	include 'header.php';
	//RETURNS TRUE WHEN COOKIES ARE SET
	function db_connect1(){
		$db_host = 'localhost';
		$db_user= 'root';
		$db_pass= 'Beer1234';
		//$db_pass='';
		//$db_name= 'CheapBeer';
		$db_name= 'cheapbeerprices';
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
	
	
	//create the price input page
		$query = "SELECT Name FROM Vendors";
		$vendors = mysql_query($query);
		$numVendors = mysql_numrows($vendors);
		
		$query = "SELECT Name FROM Products";
		$products = mysql_query($query);
		$numProducts = mysql_numrows($products);
		
		$query = "SELECT Quantity FROM Products GROUP BY Quantity";
		$quantity = mysql_query($query);
		$numQuantity = mysql_numrows($quantity); 
		
		$query = "SELECT Volume FROM Products GROUP BY Volume";
		$volume = mysql_query($query);
		$numVolume = mysql_numrows($volume);
		
	if(!isset($_POST['vendors']) 
	    && !isset($_POST['products']) 
	    && !isset($_POST['quantity']) 
	    && !isset($_POST['volume']) 
	    && !isset($_POST['cans']) 
	    && !isset($_POST['price'])){
		    
		echo"<form action='priceInsert.php' name='priceInsertData' method='post'>";
		echo"<select name='vendorss' id='vendorss'>
		<option selected='selected'>Choose one</option>";
		for($v=0;$v<$numVendors;++$v) { 
			$vendorName = mysql_result($vendors,$v,"Name");
			echo $vendorName;
			echo'<option value="'.$vendorName.'">'.$vendorName.'</option>';
		}echo"</select>";

		echo"<select name='products' id='products'>
		<option selected='selected'>Choose one</option>";
		for($p=0;$p<$numProducts;++$p) { 
			$productName = mysql_result($products,$p,"Name");
			echo'<option value="'.$productName.'">'.$productName.'</option>';
		}echo"</select>";

		echo"<select name='quantity' id='quantity'>";
		for($q=0;$q<$numQuantity;++$q) { 
			$quantityName = mysql_result($quantity,$q,"Quantity");
			echo"<option value='".$quantityName."'>".$quantityName."</option>";
		}echo"</select>";
		
		
		echo"<select name='volume' id='volume'>";
		for($v=0;$v<$numVolume;++$v) { 
			$volumeName = mysql_result($volume,$v,"Volume");
			echo"<option value='".$volumeName."'>".$volumeName."</option>";
		}echo"</select>
		      <select name='cans' id='cans'>
			<option value='Cans'>Cans</option>
			<option value='Bottles'>Bottles</option>
		      </select>
		<input type='text' name='price'>
		<input type='submit' name='submit'>";
				
		echo"</form>";
	}else{
		$vendorsss=$_POST['vendorss'];
		
		echo"<form action='priceInsert.php' name='priceInsertData' method='post'>";

		
		echo"<table style='border-style:solid;width:100%;'>
		
		<tr>
		<th>Vendor</th>
		<th>Beer</th>
		<th>Quantity</th>
		<th>fl.oz./ea</th>
		<th>B/C</th>
		<th>Price</th>
		<th></th>
		</tr>";
		
		echo"<tr><th>
		<select name='vendorss' id='vendorss'>
			<option selected='selected'>".$vendorsss."</option>";
			for($v=0;$v<$numVendors;++$v) { 
				$vendorName = mysql_result($vendors,$v,"Name");
				echo'<option value="'.$vendorName.'">'.$vendorName.'</option>';
			}
		echo"
		</select>
		</th>";

		echo"<th><select name='products' id='products'>
		<option selected='selected'>".$_POST['products']."</option>";
		for($p=0;$p<$numProducts;++$p) { 
			$productName = mysql_result($products,$p,"Name");
			echo'<option value="'.$productName.'">'.$productName.'</option>';
		}
		echo"</select></th>";

		echo"<th><select name='quantity' id='quantity'>
		<option selected='selected'>".$_POST['quantity']."</option>";
		for($q=0;$q<$numQuantity;++$q) { 
			$quantityName = mysql_result($quantity,$q,"Quantity");
			echo"<option value='".$quantityName."'>".$quantityName."</option>";
		} 
		echo"</select></th>";
		
		
		echo"<th><select name='volume' id='volume'>
		<option selected='selected'>".$_POST['volume']."</option>";
		for($v=0;$v<$numVolume;++$v) { 
			$volumeName = mysql_result($volume,$v,"Volume");
			echo"<option value='".$volumeName."'>".$volumeName."</option>";
		}
		echo"</select></th>
		
		<th><select name='cans' id='cans'>
		<option selected='selected'>".$_POST['cans']."</option>
		<option value='Cans'>Cans</option>
		<option value='Bottles'>Bottles</option>
		</select></th>
		<th>
		<input type='text' name='price'>
		</th>
		<th>
		<input type='submit' name='submit'>
		</th>
		</table>";
		$query = 'SELECT ID FROM vendors WHERE Name = "'.$_POST['vendorss'].'"';
		$vendorQuery = mysql_query($query);
		$vendorID = mysql_result($vendorQuery,0,"ID");
		
		$query = 'SELECT Location FROM vendors WHERE Name = "'.$_POST['vendorss'].'"';
		$locationQuery = mysql_query($query);
		$location = mysql_result($locationQuery,0,"Location");
		//echo$_POST['products'];

		if($_POST['cans']=="Cans")
			{
			$cans=true;	
			}
		else{
			$cans=false;
		}
		$query = 'SELECT ID FROM products WHERE Name = "'.$_POST['products'].'" AND Quantity = "'.$_POST['quantity'].'" AND Volume = "'.$_POST['volume'].'" AND Cans = "'.$cans.'"';
		$productQuery = mysql_query($query)
			or die ("cannot find product".$_POST['products'].mysql_error());
		if (mysql_num_rows($productQuery)==0) { 
			echo"Product does not exist in Product table like that";
			echo "<br />Price insertion denied";
		}else{
			$productID = mysql_result($productQuery,0,"ID");
			$createTable="CREATE TABLE IF NOT EXISTS `".$location."` (
				`VendorID` int(11) NOT NULL,
				`ProductID` int(11) NOT NULL,
				`Price` double NOT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
				) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_unicode_ci;";
				
			$insertPrice=" INSERT INTO `".$location."` (`VendorID`, `ProductID`, `Price`, `timestamp`) VALUES
			($vendorID, $productID,".$_POST['price'].",NULL)";
			
			echo"<br>";
			if (mysql_query($createTable)) 
			{
			 if (mysql_query($insertPrice)) {
				 echo "<p style='color:green;'>Price inserted successfully!</p>";
			 }else{
				echo "<p style='color:red;'>Price insertion failed!</p>"; 
			 }
			}else{
				echo "<p style='color:red;'>Error in validating table: ".$location."</p>". mysql_error ();
			}	
		}
		echo"</form>";
	}
	echo"</article>								
	</main>";
	db_close1(); 
	include 'footer.php';
}
else{
die("<div style='font-family: Verdana; font-size: 12px;>"."You must be logged in!"."</div>");
}
?>