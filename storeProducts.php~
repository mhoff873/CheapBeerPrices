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
	//LOCATION VARS
	//$town=$_POST['location'];
	//$state=$_POST['state'];
	
	$place_id=(isset($_GET['place_id'])&&!isset($_GET['ID']))?$_GET['place_id']:null;
	$id=(isset($_GET['ID'])&&!isset($_GET['place_id']))?$_GET['ID']:null;

	//only 1 condition is needed to decide which vendor to pull from for pulling
	$getData=(isset($_GET['ID'])&&!isset($_GET['place_id']))?
	"SELECT Name,ID,Location,State
	FROM Vendors
	WHERE ID = ".$id:null; 
	
	$fill = (!is_null($getData))?mysql_query($getData):null;
/* 	if (!$fill) {
	    die('Could not query:' . mysql_error());
	} */
	$callGoogle=(isset($_GET['place_id'])&&!isset($_GET['ID']))?
	"SELECT Name,ID,Location,State 
	FROM Vendors 
	WHERE place_id = '".$place_id."'":null;
/* 	if (!$fill) {
	    die('Could not query:' . mysql_error());
	} */
	$lookup =(!is_null($callGoogle))? mysql_query($callGoogle):null;
	
	
	
	//$query = "SELECT Name,ID,Location,State FROM Vendors WHERE place_id = '".$place_id."'";
	//$nameID = mysql_query($query);
	//$storeName=((!is_null($lookup)&&is_null($fill))? mysql_result($lookup,0,"Name"):(is_null($lookup)&&!is_null($fill)))?mysql_result($fill,0,"Name"):null;
	//$town=((!is_null($lookup)&&is_null($fill))? mysql_result($lookup,0,"Location"):(is_null($lookup)&&!is_null($fill)))?mysql_result($fill,0,"Location"):null;
	//$state=((!is_null($lookup)&&is_null($fill))? mysql_result($lookup,0,"State"):(is_null($lookup)&&!is_null($fill)))?mysql_result($fill,0,"State"):null;
	//mysql_result($nameID,0,"Name");
	//$town= mysql_result($nameID,0,"Location");
	//$state=mysql_result($nameID,0,"State");
	$id=0;
	$storeName=0;
	$town=0;
	$state=0;
	$id=(!is_null($lookup)&&is_null($fill))?mysql_result($lookup,0,"ID"):$id;
	$storeName=(!is_null($lookup)&&is_null($fill))?mysql_result($lookup,0,"Name"):$storeName;
	$town=(!is_null($lookup)&&is_null($fill))?mysql_result($lookup,0,"Location"):$town;
	$state=(!is_null($lookup)&&is_null($fill))?mysql_result($lookup,0,"State"):$state;
	$id=(!is_null($fill)&&is_null($lookup))?mysql_result($fill,0,"ID"):$id;
	$storeName=(!is_null($fill)&&is_null($lookup))?mysql_result($fill,0,"Name"):$storeName;
	$town=(!is_null($fill)&&is_null($lookup))?mysql_result($fill,0,"Location"):$town;
	$state=(!is_null($fill)&&is_null($lookup))?mysql_result($fill,0,"State"):$state;
/* 	if(!is_null($lookup)&&is_null($fill)){
		$storeName=mysql_result($lookup,0,"Name");
		
	} */
	
	//if(is_null($lookup)&&!is_null($fill)){
	/* 	$storeName=mysql_result($fill,0,"Name");
		$town=mysql_result($fill,0,"Location");
		$state=mysql_result($fill,0,"State"); */
	//}
	//$query = "SELECT Name FROM Products WHERE ID = '".mysql_result($prices,0,"ProductID")."'";
	//$productQuery = mysql_query($query);
	//$productName= json_encode(mysql_result($productQuery,0,"Name"));
	
	//echo"<script> alert(' ".$store." ".$productName." ".$price." ".$timestamp." ');</script>";
	//$storename=$_POST['storeName'];
	//$townTimezone="America/New_York";
	//$time_zone_name = ( 
	//IF THERE ARE COOKIES, SET CORRECT TIMEZONE, OTHERWISE TIMEZONE IS "America/New_York"
	//cookies()?
	//timezone_name_from_abbr('', -$_COOKIE['time_zone_offset']*60, $_COOKIE['time_zone_dst']) : $townTimezone);
	//$dateer = date_create("now", timezone_open($time_zone_name));
	//$hour24 = date_format($dateer, 'H'); //24 hour clock
	//$today = date_format($dateer, 'D'); //
	//$minute = date_format($dateer, 'i');
	//IF THE HOUR IS 12am IT IS NOT 0am
	//$hour12=(!($hour24%12))?12:$hour24%12; //12 hour clock conversion
	//AM OR PM
	//$dayTime = date_format($dateer, 'a');//(
	//used for simply indexing days of week
	//$days=array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
	//
	/*function isOpen($storeQueryRow){
		$today=$GLOBALS['today'];
		$hour = $GLOBALS['hour24']; //private $hour is based off 24 hour clock of client
		$minute = $GLOBALS['minute'];
		$query = "SELECT * FROM Vendors WHERE Town = '$_POST[location]'";
		$townVendors = mysql_query($query);
		//OPENING TIME
		$H24Open = mysql_result($townVendors,$storeQueryRow,"H24Open".$today);
		$MOpen=mysql_result($townVendors,$storeQueryRow,"MOpen".$today);
		//CLOSING TIME
		$H24Close = mysql_result($townVendors,$storeQueryRow,"H24Close".$today);
		$MClose=mysql_result($townVendors,$storeQueryRow,"MClose".$today);
		//Checkmark and X for open and close
		$open=json_decode('"\u2713"');
		$closed=json_decode('"\u2718"');
		
		if($H24Open<= $hour && $hour <= $H24Close ){
			if($H24Open== $hour || $hour == $H24Close ){
				if($MOpen<= $minute || $minute < $MClose )
					echo"<p style='color:lightgreen;font-size:1.5em;font-weight: bold'>".$open."</p>";
				else
					echo"<p style='color:red;font-size:1.5em;'>".$closed."</p>";
			}else{
				echo"<p style='color:lightgreen;font-size:1.5em;font-weight: bold'>".$open."</p>";
			}
		}else{
			echo"<p style='color:red;font-size:1.5em;'>".$closed."</p>";
		}	
	} END OF isOpen() */
	////INDENTATION REDUCED 
echo"	<main id='center' class='column'>
<article>
<h1 style='text-align:center;'><u>".$storeName." -  ".$town.", ".$state.".</u></h1>
<ul style='width:100%;list-style-type: none;margin: 0;padding: 0;'>
	<a href=storeSummary2.php?";
	if(isset($_GET['ID'])&&!isset($_GET['place_id'])){
		echo"ID=".$_GET['ID'];
	}
	if(isset($_GET['place_id'])&&!isset($_GET['ID'])){
		echo"place_id=".$_GET['place_id'];
	}
	echo"><li class='tabLeft' style='background-color:#707070;color:white;height:2.38em;border-bottom:none;border-left:none;'><h3>Store Summary</h3></li></a>
	<li class='tabRight' style='border-bottom:none;background-color:silver;border-right:none;'><h3>Store Products</h3></li>
</ul>
<br><div style='float:none;background-color:#707070;padding-bottom:1.25em;'>
<p style='height:0.2em;'></p>
<div style='background-color:silver;width:98%;margin-right:1%;margin-left:1%;padding-bottom:1em;'><div>";


		$catQuery = "SELECT Category FROM Products GROUP BY Category";
		$categories = (!is_null($catQuery))?mysql_query($catQuery):null;
		$numCategories = (!is_null($categories))?mysql_numrows($categories):null; 
		
		echo"<span style='margin-left:1%;margin-top:0.5em;vertical-align:center;margin-bottom:0.5em;height:2em;'>Product Category: </span><select name='categories' id='categories' style='margin-left:1%;margin-top:0.5em;margin-bottom:0.5em;height:2em;'>";
		for($c=0;$c<$numCategories;++$c) { 
			$catName = (!is_null($categories))?mysql_result($categories,$c,"Category"):null;
			echo'<option value="'.$catName.'">'.$catName.'</option>';
		}echo"</select>";
		
		//$catQuery = "SELECT Category FROM Products GROUP BY Category";
		$searchMethod = array("Basic Search","Advanced ABV Search");
		$numSearchMethod = 2; 

		
		echo"<span style='margin-left:1%;vertical-align:center;margin-top:0.5em;margin-bottom:0.5em;height:2em;'>Search Type: </span>";
		echo"<select name='searchMethod' id='searchMethod' style='margin-left:1%;margin-top:0.5em;margin-bottom:0.5em;height:2em;'>";
		for($s=0;$s<$numSearchMethod;++$s) { 
			echo'<option value="'.$searchMethod[$s].'">'.$searchMethod[$s].'</option>';
		}echo"</select>";
echo"</div><center>
<table style='background-color:white;width:98%;border-style:solid;border-color:black;border-width:3px'><tbody>
<tr style='vertical-align:top;text-align:center;height:1em;margin-right:auto;margin-left:auto;background-color:silver;color'>
		<td><b>Name</b></td>
		<td><b>Pack</b></td>
		<td><b>Price</b></td>
		<td><b>Time</b></td></tr>";
	$query = "SELECT productID FROM `$town` WHERE vendorID = '$id' GROUP BY productID";


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
	$numProducts = (!is_null($prices))?mysql_numrows($prices):0;
	$productArray = array();
	for($i = 0; $i<=$numProducts-1; $i++)//{
		array_push($productArray,mysql_result($prices,$i,"productID"));
		  
	foreach ($productArray as $pro){
		$pQuery = "SELECT timestamp,price FROM $town 
		WHERE vendorID = $id 
		AND productID = $pro ORDER BY timestamp DESC LIMIT 1";
		$nameQuery = "SELECT Name,volume,quantity,cans FROM Products WHERE ID = $pro";
		$proQuery = mysql_query($pQuery);
		$proNameQuery = mysql_query($nameQuery);
		$productName= mysql_result($proNameQuery,0,"Name");
		 $quantity=mysql_result($proNameQuery,0,"Quantity");
		$volume=mysql_result($proNameQuery,0,"Volume"); 
		$price=mysql_result($proQuery,0,"price");
		$timestamp=mysql_result($proQuery,0,"timestamp");
		$bc=(mysql_result($proNameQuery,0,"Cans")==1)?"Cans":"Bottles";
		
		echo "<tr style='vertical-align:top;text-align:center;height:1em;margin-right:auto;margin-left:auto'>
		<td>".$productName."</td>
		<td>".$quantity." ".$bc."(".$volume."oz)</td>
		<td>$".$price."</td>
		<td>".$timestamp."</td>
		</tr>";
	}
echo"</tbody></table>
</center>
</div>
</div>
</article>								
	</main>";db_close1(); 
	include 'footer.php';
	//CLOSES DATABASE CONNECTION AT END
?>