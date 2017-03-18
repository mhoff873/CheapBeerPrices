<?php 
	include 'header.php';
	function db_connect1(){
		$db_host = ' ';
		$db_user= ' ';
		$db_pass= ' ';
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
	
	$id=(isset($_GET['ID']))?$_GET['ID']:null;

	//only 1 condition is needed to decide which vendor to pull from for pulling
	$getData=(isset($_GET['ID']))?
	"SELECT Name,ID,Location,State
	FROM vendors
	WHERE ID = ".$id:null; 
	
	$fill = (!is_null($getData))?mysql_query($getData):null;

	$id=(!is_null($fill))?mysql_result($fill,0,"ID"):$id;
	$storeName=(!is_null($fill))?mysql_result($fill,0,"Name"):$storeName;
	$town=(!is_null($fill))?mysql_result($fill,0,"Location"):$town;
	$state=(!is_null($fill))?mysql_result($fill,0,"State"):$state; 

	echo"<main id='center' class='column'>
	<article>
	<h1 style='text-align:center;font-size:1.65em;'><b>".$storeName." -  ".$town.", ".$state.".</b></h1>

	<div style='padding: 0;border: 0;margin: 0;float:none;background-color:#707070;padding-bottom:1.25em;'>
	<table style='width:100%;margin: 0;padding: 0;'> 
		<tr>
			<td class='tabLeft' style='background-color:silver;color:white;display:block;border-bottom:none;border-left:none;'>
				<a href=storeSummary2.php?";
				//setting the link to correct vendor's summary page
				if(isset($_GET['ID'])){
					echo"ID=".$_GET['ID'];
				} 
				echo" style='width: 50%;height: 100%;'>
					<h3>Store Summary</h3></a>
			</td>
			
			<td class='tabRight' style='background-color:#707070;'>
				<h1 style='color:white'>Store Products</h1>
			</td>
		</tr>
	</table>
	<br>
	<div style='background-color:silver;width:98%;margin-right:1%;margin-left:1%;padding-bottom:1em;'>

	<form method='GET'>
	<input type='hidden' name='ID' value='".$_GET['ID']."'>
	<table style='width:96%'>
		<tr>
		<td style='width:84%'>";
		if(isset($_GET['search']))
			echo"<input style='width:98%;height:2em;font-size:1.2em;font-weight:bold;margin-top:0.5em;margin-left:2%;text-indent: 1em;' type='text' name='search' value='".$_GET['search']."' id='searchproductsBar'>";
		else
			echo"<input style='width:98%;height:2em;font-size:1.2em;font-weight:bold;margin-top:0.5em;margin-left:2%;text-indent: 1em;' type='text' name='search' id='searchproductsBar'>";
		echo"</td>
		<td style='width:16%;text-align:right'>
			<button type='submit' style='width:100%;height:2em;font-size:1.3em;font-weight:bold;margin-top:0.5em;padding:0;border:0;'>Search</button>
		</td>
		
		</tr>
	</table><hr>";

echo"<center>
<table style='background-color:white;width:98%;border-style:solid;border-color:black;border-width:3px'>";
$productCounter = 0; //counts number of search results or just price results for the town. Allows coloring of rows and "0 results" alert message

			$query = "SELECT productID FROM `$town` WHERE vendorID = '$id' GROUP BY productID"; //1 for each productID

			//gets all productIDs from each item for sale in the town
			$prices = (!is_null($query))?mysql_query($query):null;
		/*  	if (!$prices) { 
			    die('Could not query:' . mysql_error());
			}  */
			/*
			*
			* Here it bugs out when the town does not have a table.
			* Insert price for that vendor or another vendor in that location
			* Many attempts have been made to resolve this code. 
			* $prices is not NULL, it is a boolean that is neither true or false.
			* perhaps a try/catch statement could be used if this exception has a name.
			*/
			$numproducts = (!is_null($prices))?mysql_numrows($prices):0;
			$productArray = array();
			for($i = 0; $i<=$numproducts-1; $i++)//this array_push only works because productArray is a numerical array
				array_push($productArray,mysql_result($prices,$i,"productID"));
			//table heading for ABV Comparison table	
			echo"<tr style='vertical-align:top;text-align:center;height:1em;margin-right:auto;margin-left:auto;background-color:silver;color'>
			<th style='width:6em;vertical-align:middle;' onclick='sort_table(priceTable, 0, asc1); asc1 *= -1; asc2 = 1; asc3 = 1;asc4 = 1; asc5=1; asc6=1; asc7=1; asc8=1;'><b>Date</b></th>
			<th style='vertical-align:middle;' onclick='sort_table(priceTable, 1, asc2); asc2 *= -1; asc1 = 1; asc3 = 1;asc4 = 1; asc5=1; asc6=1; asc7=1; asc8=1;'>Name</th>
			<th style='vertical-align:middle;' onclick='sort_table(priceTable, 2, asc3); asc3 *= -1; asc2 = 1; asc1 = 1;asc4 = 1; asc5=1; asc6=1; asc7=1; asc8=1;'>Pack</th>
			<th style='width:4.2em;vertical-align:middle;' onclick='sort_table(priceTable, 3, asc4); asc4 *= -1; asc2 = 1; asc3 = 1;asc1 = 1; asc5=1; asc6=1; asc7=1; asc8=1;'>Total Volume</th>
			<th style='width:3em;vertical-align:middle;' onclick='sort_table(priceTable, 4, asc5); asc5 *= -1; asc2 = 1; asc3 = 1;asc4 = 1; asc1=1; asc6=1; asc7=1; asc8=1;'>ABV</th>
			<th style='width:4.2em;vertical-align:middle;' onclick='sort_table(priceTable, 5, asc6); asc6 *= -1; asc2 = 1; asc3 = 1;asc4 = 1; asc5=1; asc1=1; asc7=1; asc8=1;'>Price</th>
			<th style='width:4.2em;vertical-align:middle;' onclick='sort_table(priceTable, 6, asc7); asc7 *= -1; asc2 = 1; asc3 = 1;asc4 = 1; asc5=1; asc6=1; asc1=1; asc8=1;'>$/oz Beer</th>
			<th style='width:4.2em;vertical-align:middle;' onclick='sort_table(priceTable, 7, asc8); asc8 *= -1; asc2 = 1; asc3 = 1;asc4 = 1; asc5=1; asc6=1; asc7=1; asc1=1;'>$/oz Alcohol</th>
			</tr><tbody id='priceTable'>";	  
			
			foreach ($productArray as $pro){ //a price for product in this town
				
				$pQuery = "SELECT timestamp,price FROM $town 
				WHERE vendorID = $id 
				AND productID = $pro ORDER BY timestamp DESC LIMIT 1"; //lifts the newest timestamp only
				$nameQuery = (isset($_GET['search']) && $_GET['search']!="")?
				"SELECT Name,Volume,Quantity,containerID,ABV,Style FROM products WHERE ID = $pro AND Name LIKE '%$_GET[search]%' ORDER BY Name ASC"
				:"SELECT Name,Volume,Quantity,containerID,ABV,Style FROM products WHERE ID = $pro ORDER BY Name ASC";
			
			
				$proNameQuery = mysql_query($nameQuery);
				if(mysql_num_rows($proNameQuery)==1){//a search result has been found with its lastest timestamp
					$productCounter+=1; //Keeps track of number of search results
					$proQuery = mysql_query($pQuery);
					$proNameQuery = mysql_query($nameQuery);
					$productName= mysql_result($proNameQuery,0,"Name"); //queries from the product database
					$quantity=mysql_result($proNameQuery,0,"Quantity");
					$volume=mysql_result($proNameQuery,0,"Volume"); 
					$abv=mysql_result($proNameQuery,0,"ABV");
					$price=mysql_result($proQuery,0,"price"); //query from the town database
					$timestamp=mysql_result($proQuery,0,"timestamp");

					$bc="shoe"; //default container is a shoe
					//retrieves container id from products
					$container = mysql_result($proNameQuery,0,"containerID");
					//queries the container name from the container table
					$query = "SELECT Name FROM containers WHERE ID = $container ";
					$contNames = (!is_null($query))?mysql_query($query):null;
					$containerName = mysql_result($contNames,0,"Name");

					if(!($quantity==1))
						$bc=$containerName."s"; //adds an s for plural quantities
					else
						$bc=$containerName;

					$dateOfInsertion = new dateTime;
					$dateOfInsertion->setTimestamp(strtotime($timestamp));
					echo "<tr style='vertical-align:top;text-align:center;height:1em;margin-right:auto;margin-left:auto;background-color:";
					if($productCounter%2)echo"#b3d9ff"; //every odd row is this color
					else echo"white"; //every even row is this color
					echo"'>
					<td>".$dateOfInsertion->format('d M Y')."</td>
					<td>".$productName."</td>
					<td>".$quantity." ".$bc."(".$volume."oz)</td>
					<td>".($quantity*$volume)." oz</td>
					<td>".$abv."%</td>
					<td>$".$price."</td>
					<td>$".number_format(($price/($quantity*$volume)),3)."</td>
					<td>$".number_format(($price/(($abv/100)*($quantity*$volume))),3)."</td>
					</tr>";
				}elseif(mysql_num_rows($proNameQuery)==0){
				}//a price in this town belongs to a product that does not match the search results. no big deal.
			}
		echo"</tbody></table>"; //insert the failed search result method after the price table
		if(isset($_GET['search'])&&$productCounter==0){//there were no search results returned
			if($_GET['search']=="")
				echo"<h1 style='color:blue'>No Prices for ".$storeName."</h1>";
			else
				echo"<h1 style='color:red'>No Result for Search: '".$_GET['search']."'</h1>";
		}
		echo"</center></form>
		</div>
		</div>
		</article>								
		</main>";db_close1(); 
		include 'footer.php';
	//CLOSES DATABASE CONNECTION AT END
?>
