<!DOCTYPE html>
<html>
<head>
	<style>
	body {
		background: #737373;
	}
	/* Style the tab */
	#customerSelection {
		width: 50%;
	}

	pre {
		float: left;
	}
	</style>

<meta charset="ISO-8859-1">
<script type = "text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src = "https://code.jquery.com/jquery-1.12.4.js"></script>
<script src = "https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type = "text/javascript">

/***********************************    Data Exchange  Start   *****************/

var selectedArr = [];
var products;
var invPr;
var txt;
var total = 0;
var t = "123"
t = JSON.stringify(t);
$.post("http://localhost/POS_Proj/POS_DB_Invoice.php", t, function(r){
	alert("Searching for invoice no. " + t);
	console.log("    This is what's coming from the callback of POS_DB_Invoice:\n " + r);
	invPr = r;
	txt = "";

	for( var i in products ) if( products.hasOwnProperty(i) )  {
		var j = 0;

		//  ID, Price and Stock data sent as string need to convert to numbers to work with
		invPr[i].ID = Number(invPr[i].ID);
		invPr[i].Price = Number(invPr[i].Price);
		invPr[i].Stock = Number(invPr[i].Stock);

		txt += " \n\n		Invoice Info:\n		" + invPr[i].ID + ": " + invPr[i].Item + " \n" + "Price: " + invPr[i].Price + " \nStock availability: " + invPr[i].Stock + " \n\n";
	}
	//console.log(s);
} );


//console.log(r1);



$.get("http://localhost/POS_Proj/POS_DB.php", false, function(r){
	alert("Start JSON parse");
	products = JSON.parse(r);
	console.log(r);
	txt = "";
	for( var i in products ) if( products.hasOwnProperty(i) )  {
		var j = 0;

		//  ID, Price and Stock data sent as string need to convert to numbers to work with
		products[i].ID = Number(products[i].ID);
		products[i].Price = Number(products[i].Price);
		products[i].Stock = Number(products[i].Stock);
		txt += " \n\n" + products[i].ID + ": " + products[i].Item + " \n" + "Price: " + products[i].Price + " \nStock availability: " + products[i].Stock + " \n\n";

		$("#itemSelection").append($("<option></option>").text(products[i].Item  +"		" + products[i].Price).val( i ));		//  .text is for what is actually displayed and .val is the value assigned to the option
		console.log(products[i].ID);
		console.log( typeof( products[i].ID ) );
		$( "#Stock" ).html(txt +"\n");
		}
	alert("End JSON parse");
});




/***********       Add item button functions and event handlers        **************/
function selectItem(){
	var idx = $( "#itemSelection option:selected" ).val();
	var selected = products[idx].Item + "		" +products[idx].Price;

	$( document ).ready( function(){
		$("#customerSelection").append($("<option></option>").text(selected).val(idx));

		Number(total);
		total += products[idx].Price;

		$("#printPrice").text(total);

		// alert($("#itemSelection").prop('selectedIndex'));
		//alert(c);
	});
}

 $( document ).ready( function(){
	 $( "#addBtn" ).click(function(  ) {
		 selectItem();

		 //alert(" JQuery Action: addBtn pressed");

	 });
 });

 /********************				Delete button functions and event handlers			****************/

 $( document ).ready( function(){
	 $( "#deleteBtn" ).click(function(  ) {


		 alert(" JQuery Action: deleteBtn pressed");
	 });
 });

/*********************       Total button functions and event handlers          *************************/

function sendData(){

	var i = 0;
	$("#customerSelection > option").each(function() {
		//		$("#customerSelection > option") selects all child elements from "option" of the parent elemnt "#customerSelection"
		//		So basically it gathers all of the customer's selected items ready for .each to cycle through all of them
	console.log(this.text + ' ' + this.value);
	console.log( " Customer selection item no. " + i + ":  " + products[this.value]);

	selectedArr[ i ] = products[this.value];
	i++;

});
selectedArr.push({"Total":total});
	var strSelectedArr = JSON.stringify(selectedArr);

	$.post("http://localhost/POS_Proj/POS_DB.php", strSelectedArr, function(r){

		alert("sending purchase data to php.");
		console.log("  strSelectedArr: "+strSelectedArr);
		console.log(r);

	});
}

 $( document ).ready( function(){
	 $( "#totalBtn" ).click(function(  ) {
		 sendData();
		 alert(" JQuery Action: totalBtn pressed");
		 selectedArr.length = 0;
		 $( "#customerSelection" ).empty();
		 total = 0;

	 });
 });

 function srchInv( s ){
	 s = JSON.stringify(s);
	 $.post("http://localhost/POS_Proj/POS_DB_Invoice.php", s, function(r){
		 alert("Searching for invoice no. " + s);
		 console.log(r);
		 $.get("http://localhost/POS_Proj/POS_DB_Invoice.php", false, function(r1){
			alert("POS DB invoice sending stuff back");
		 console.log(r1);
		 invPr = JSON.parse(r1);
		 //txt = "";

		 // for( var i in products ) if( products.hasOwnProperty(i) )  {
		 // 	var j = 0;
		 //
		 // 	//  ID, Price and Stock data sent as string need to convert to numbers to work with
		 // 	invPr[i].ID = Number(invPr[i].ID);
		 // 	invPr[i].Price = Number(invPr[i].Price);
		 // 	invPr[i].Stock = Number(invPr[i].Stock);
		 //
		 // 	txt += " \n\n		Invoice Info:\n		" + invPr[i].ID + ": " + invPr[i].Item + " \n" + "Price: " + invPr[i].Price + " \nStock availability: " + invPr[i].Stock + " \n\n";
		 // }
		});
		 //console.log(s);
	 } );

 }

$( document ).ready( function(){
	$("#invoiceSrchBtn").click( function() {

		srchInv( $("#invoiceNo").val() );
	});
});

/****************************************INVOICE TESTING *************************************/






/***********************************    Data Exchange  End   *****************/


/******************************************  Tab Functions   *********************/
$( function() {
    $( "#tabs" ).tabs();
  } );
/****************************************** End Tab Functions   *********************/

</script>
<title>Hello and Welcome to JoJo's World.</title>
</head>
<body>
	<h1>Hello and welcome to your new life</h1>

<div id="tabs">
	<ul>
		<li><a href="#Ordering"><span>Ordering</span></a></li>
    <li><a href="#Invoices"><span>Invoices</span></a></li>
    <li><a href="#Stats"><span>Stats</span></a></li>
		<li><a href="#Stock"><span>Stock</span></a></li>
	</ul>

	<!-- *********************** Ordering  *****************/-->

	<div id="Ordering">
		<h3>Ordering</h3>
		<select id = "itemSelection"></select>

		<form><select id = "customerSelection" size = "12"></select></form>

	  	<button id = "addBtn">Add Item</button>
	  	<button id = "deleteBtn">Delete Item</button>

	  	<button id = "totalBtn">Total</button>

			<p>Transaction Total: </p>
			<p id = "printPrice"></p>

	  	<p> Please enter the amount you would like to pay </p>

	  	<input type = "number" id = "payAmnt" value = "0">

	  	<p> How would you like to pay? </p>

	  	<button id = "cashBtn"> Cash </button>
	  	<button id = "cardBtn">Card</button>
	  	<button id = "giftCrdBtn">Gift Card</button>
	</div>

<!-- *********************** Invoices  *****************/-->

	<div id="Invoices">
		<div class="input-group">
		 <input type="text" class="form-control" id = "invoiceNo" placeholder="Search" name="search">
		 <div class="input-group-btn">
			 <button class="btn btn-default" id = "invoiceSrchBtn" type="submit"><i class="glyphicon glyphicon-search"></i></button>
		 </div>
	</div>
	<div id="Stats"></div>
	<div id="Stock"></div>
</div>

    <?php
    print "<pre>Record:\n</pre>\n\n\n";
    ?>
</body>
</html>
