<!DOCTYPE html>
<!-- Template by quackit.com -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="user-scalable=yes">
	<title>Cheap Beer Prices</title>
	<link rel="stylesheet" type="text/css" href="css/layout.css">
	<script src='js/jquery220compressed.js'></script>
	 <script type="text/javascript">
        var priceTable, asc1 = 1,
            asc2 = 1,
            asc3 = 1,
	    asc4 = 1,
	    asc5 = 1,
	    asc6 = 1,
	    asc7 = 1,
	    asc8 = 1;
        window.onload = function () {
            priceTable = document.getElementById("priceTable");
        }

        function sort_table(tbody, col, asc) {
            var rows = tbody.rows,
                rlen = rows.length,
                arr = new Array(),
                i, j, cells, clen;
            // fill the array with values from the table
            for (i = 0; i < rlen; i++) {
                cells = rows[i].cells;
                clen = cells.length;
                arr[i] = new Array();
                for (j = 0; j < clen; j++) {
                    arr[i][j] = cells[j].innerHTML;
                }
            }
            // sort the array by the specified column number (col) and order (asc)
            arr.sort(function (a, b) {
                return (a[col] == b[col]) ? 0 : ((a[col] > b[col]) ? asc : -1 * asc);
            });
            // replace existing rows with new rows created from the sorted array
            for (i = 0; i < rlen; i++) {
                rows[i].innerHTML = "<td>" + arr[i].join("</td><td>") + "</td>";
            }
        }
    </script>
</head>

<body>

	<header id="header">
		<h1>Cheap Beer Prices</h1>
	</header>

	<nav>
			<ul>
				<li id="top"><a href=index.php>Home<a></li>
				<li id="top"><a href=search.php>Search<a></li>
				<li id="top"><a href=aboutUs.php>About Us<a></li>
				<li id="top"><a href=contactUs.php>Contact Us<a></li>
				<li id="top"><a href=paLiquorLawExplained.php>PA Liquor Law Explained<a></li>
			</ul>
	</nav>
	
	<div id="container">