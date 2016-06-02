<?php 
	include 'header.php';
	//RETURNS TRUE WHEN COOKIES ARE SET
	//function cookies(){
	//	return (isset($_COOKIE['time_zone_offset'],$_COOKIE['time_zone_dst']));
	//}
	function db_connect1(){
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

	echo"	

	
	<main id='center' class='column'>
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
			echo"<input style='width:98%;height:2em;font-size:1.2em;font-weight:bold;margin-top:0.5em;margin-left:2%;text-indent: 1em;' type='text' name='search' value='".$_GET['search']."' id='searchProductsBar'>";
		else
			echo"<input style='width:98%;height:2em;font-size:1.2em;font-weight:bold;margin-top:0.5em;margin-left:2%;text-indent: 1em;' type='text' name='search' id='searchProductsBar'>";
		echo"</td>
		<td style='width:16%;text-align:right'>
			<button type='submit' style='width:100%;height:2em;font-size:1.3em;font-weight:bold;margin-top:0.5em;padding:0;border:0;'>Search</button>
		</td>
		
		</tr>
	</table><hr>";
/* 	echo"<table style='width:96%;margin-top:0.5em;'>";
	
		
		$catQuery = "SELECT Category FROM Products GROUP BY Category";
		$categories = (!is_null($catQuery))?mysql_query($catQuery):null;
		
		
		$categories2 = array();
		$allOption = array('Category' => 'All');
		$categories2[] = $allOption;
		if(!is_null($catQuery))
			while ($row = mysql_fetch_array($categories))
				$categories2[] = $row;
		else
			$categories2 = null; //categories2 is actually an assoc array instead of a mysql resource


		//actually a very important variabble. figure out where it goes later		
		$setCat = (isset($_GET['Category']))?$_GET['Category']:NULL;
		
		echo"<tr><td style='text-align:right;width:30%'>
		<span style='height:2em;'>
			Category: </span></td>
			<td style='width:60%;'>
			<select name='Category' onchange='this.form.submit()' id='Category' style='width:100%;text-align:center;box-sizing:border-box;-moz-box-sizing:border-box;height:2em;'>";
		if(isset($_GET['Category']))// if the category is previously sent to server, then set it as top of dropdown
			echo'<option value="'.$_GET['Category'].'">'.$_GET['Category'].'</option>';
		foreach($categories2 as $uniqueEntry){
			if(!(isset($_GET['Category']) && $uniqueEntry['Category']==$_GET['Category']))
				echo'<option value="'.$uniqueEntry['Category'].'">'.$uniqueEntry['Category'].'</option>';
		}
		echo"</select>
		        </td><td style='width:30%'></td>
		      </tr></table>"; */
	
/* 	echo"<table style='width:96%'>";
		//$searchMethod = array("Basic Price Results","ABV Price Comparison");
		//$numSearchMethod = 2; 
		echo"<tr>
		<td><center><div style='padding-bottom:1em;width:60%;'>";
		//display method radiobuttons are set to previously GET-requested method
		//changing them resubits the form with the updated information
/* 		if(!isset($_GET['searchMethod'])){
			for($s=0;$s<$numSearchMethod;++$s) { 
				if($s==0)
					echo'<input type="radio" onchange="this.form.submit()" name="searchMethod" value="'.$searchMethod[0].'" checked>'.$searchMethod[0].'<br>';
				else
					echo'<input type="radio" onchange="this.form.submit()" name="searchMethod" value="'.$searchMethod[$s].'" >'.$searchMethod[$s].'<br>';
			}
		}else{
			for($s=0;$s<$numSearchMethod;++$s) { 
				if($_GET['searchMethod']==$searchMethod[$s])
					echo'<input type="radio" onchange="this.form.submit()" name="searchMethod" value="'.$searchMethod[$s].'" checked>'.$searchMethod[$s].'<br>';
				else
					echo'<input type="radio" onchange="this.form.submit()" name="searchMethod" value="'.$searchMethod[$s].'" >'.$searchMethod[$s].'<br>';
			}
		} 
	echo"</div></center></td></tr>";	
echo"</table>"; */
echo"<center>
<table style='background-color:white;width:98%;border-style:solid;border-color:black;border-width:3px'>";
$productCounter = 0; //counts number of search results or just price results for the town. Allows coloring of rows and "0 results" alert message
//if the searchMethod has been previously set, build that table of results
/* /* if(!isset($_GET['searchMethod']) || $_GET['searchMethod'] == $searchMethod[0]){ //if the searchMethod is not set, set the table to basic search
	echo"<tr style='vertical-align:top;text-align:center;height:1em;margin-right:auto;margin-left:auto;background-color:silver;color'>
			<th style='width:6em;' onclick='sort_table(priceTable, 0, asc1); asc1 *= -1; asc2 = 1; asc3 = 1; asc4 = 1; asc5 = 1;asc6 = 1;'>Date</th>
			<th onclick='sort_table(priceTable, 1, asc2); asc2 *= -1; asc3 = 1; asc1 = 1; asc4 = 1; asc5 = 1;asc6 = 1;'>Name</th>
			<th onclick='sort_table(priceTable, 2, asc3); asc3 *= -1; asc1 = 1; asc2 = 1; asc4 = 1; asc5 = 1;asc6 = 1;'>Style</th>
			<th onclick='sort_table(priceTable, 3, asc4); asc4 *= -1; asc1 = 1; asc2 = 1; asc3 = 1; asc5 = 1;asc6 = 1;'>Pack</th>
			<th style='width:3em;' onclick='sort_table(priceTable, 4, asc5); asc5 *= -1; asc1 = 1; asc2 = 1; asc4 = 1; asc3 = 1;asc6 = 1;'>ABV</th>
			<th style='width:4.2em;' onclick='sort_table(priceTable, 5, asc6); asc6 *= -1; asc1 = 1; asc2 = 1; asc4 = 1; asc5 = 1;asc3 = 1;'>Price</th>
			</tr><tbody id='priceTable'>";
		$query = "SELECT productID FROM `$town` WHERE vendorID = '$id' GROUP BY productID"; //1 for each productID


		$prices = (!is_null($query))?mysql_query($query):null;
	/*  	if (!$prices) {
		    die('Could not query:' . mysql_error());
		}  */
		/*
		*
		* Here it bugs out when the town does not have a table.
		* Insert price for that vendor or another vendor in that location
		*
		*/
/* 		$numProducts = (!is_null($prices))?mysql_numrows($prices):0;
		$productArray = array();
		for($i = 0; $i<=$numProducts-1; $i++)//this array_push only works for numeric arrays
			array_push($productArray,mysql_result($prices,$i,"productID"));
			  
		foreach ($productArray as $pro){
			
			//query to return price results from town table
			$pQuery = "SELECT timestamp,price FROM $town 
			WHERE vendorID = $id 
			AND productID = $pro ORDER BY timestamp DESC LIMIT 1";
			$proQuery = mysql_query($pQuery);
			//query from the town database
			$price=mysql_result($proQuery,0,"price"); 
			$timestamp=mysql_result($proQuery,0,"timestamp");
			
			//query to return data about the product from the product table
			$nameQuery = (isset($_GET['search']) && $_GET['search']!="")?
				"SELECT Name,volume,quantity,cans,ABV,Style,Category FROM Products WHERE ID = $pro AND Name LIKE '%$_GET[search]%'"
				:"SELECT Name,volume,quantity,cans,ABV,Style,Category FROM Products WHERE ID = $pro";
			
			
			$proNameQuery = mysql_query($nameQuery);
			if(mysql_num_rows($proNameQuery)==1){//a search result has been found
				$productCounter+=1; //Keeps track of number of search results
				//queries from the product database
				$productName= mysql_result($proNameQuery,0,"Name"); //there is only 1 result for this product
				$quantity=mysql_result($proNameQuery,0,"Quantity");
				$volume=mysql_result($proNameQuery,0,"Volume"); 
				$abv=mysql_result($proNameQuery,0,"ABV");
				$style=mysql_result($proNameQuery,0,"Style");
				$bc=(mysql_result($proNameQuery,0,"Cans")==1)?"Cans":"Bottles";

				//the stuff to format the timestamp
				$dateOfInsertion = new dateTime;
				$dateOfInsertion->setTimestamp(strtotime($timestamp));
				//draws the data's row into the table
				echo "<tr style='vertical-align:top;text-align:center;height:1em;margin-right:auto;margin-left:auto;background-color:";
				if($productCounter%2)
					echo"#b3d9ff";
				else echo"white";
				echo"'>
				<td>".$dateOfInsertion->format('d M Y')."</td>
				<td>".$productName."</td>
				<td>".$style."</td>
				<td>".$quantity." ".$bc."(".$volume."oz)</td>
				<td>".$abv."%</td>
				<td>$".$price."</td>
				</tr>";
			}
		} */
	//non-default display method
	//}elseif (isset($_GET['searchMethod']) && $_GET['searchMethod'] == 'ABV Price Comparison'){ 
			
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
			$numProducts = (!is_null($prices))?mysql_numrows($prices):0;
			$productArray = array();
			for($i = 0; $i<=$numProducts-1; $i++)//this array_push only works because productArray is a numerical array
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
				"SELECT Name,volume,quantity,cans,ABV,Style,Category FROM Products WHERE ID = $pro AND Name LIKE '%$_GET[search]%'"
				:"SELECT Name,volume,quantity,cans,ABV,Style,Category FROM Products WHERE ID = $pro";
			
			
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
					$bc=(mysql_result($proNameQuery,0,"Cans")==1)?"Cans":"Bottles";
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
		//} end of if statement where abv method is displayed
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