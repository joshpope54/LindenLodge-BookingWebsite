<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="./styles/dash2.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
		var cur;
		const todaysDate = new Date();
		todaysDate.setHours(0, 0, 0, 0);
		var sendableCur;

		$(document).ready(function() {
			cur = new Date();

			cur.setHours(0, 0, 0, 0);
			sendableCur = cur.getFullYear() + "-" + (cur.getMonth() + 1) + "-" + cur.getDate();
			generateInsTable();
			generateOutsTable();
			setInsOutsCounters();
		});

		function setInsOutsCounters() {
			$.ajax({
				url: "http://192.168.0.7:8081/api/v1/bookings/count/insouts/" + sendableCur,
				type: "GET",
				crossDomain: true,
				success: function(result) {
					var action = JSON.parse(result);
					$("#insCount").text(action["insCount"]);
					$("#outsCount").text(action["outsCount"]);
				}
			})
		}

		function clearTables() {
			$(".actions").find("tbody").empty();
		}

		function reduceDate() {
			cur.setDate(cur.getDate() - 1);
			sendableCur = cur.getFullYear() + "-" + (cur.getMonth() + 1) + "-" + cur.getDate();
			if (cur.getTime() === todaysDate.getTime()) {
				$('.visibleLabel').text("Today's Dashboard");
			} else {
				$('.visibleLabel').text(cur.toDateString());
			}
			clearTables();
			generateInsTable();
			generateOutsTable();
			setInsOutsCounters();
		}

		function increaseDate() {
			cur.setDate(cur.getDate() + 1);
			sendableCur = cur.getFullYear() + "-" + (cur.getMonth() + 1) + "-" + cur.getDate();
			if (cur.getTime() === todaysDate.getTime()) {
				$('.visibleLabel').text("Today's Dashboard");
			} else {
				$('.visibleLabel').text(cur.toDateString());
			}
			clearTables();
			generateInsTable();
			generateOutsTable();
			setInsOutsCounters();
		}

		function generateInsTable() {
			$.ajax({
				url: "http://192.168.0.7:8081/api/v1/bookings/ins/date/" + sendableCur,
				type: "GET",
				crossDomain: true,
				success: function(result) {
					//call function to place items in table
					createRow("insTable", result);
				}
			});
		}

		function generateOutsTable() {
			$.ajax({
				url: "http://192.168.0.7:8081/api/v1/bookings/outs/date/" + sendableCur,
				type: "GET",
				crossDomain: true,
				success: function(result) {
					//call function to place items in table
					createRow("outsTable", result);
				}
			});
		}

		function createRow(tableType, jsonData) {
			var body = $(".actions#" + tableType+" tbody");
			console.log(jsonData);
			console.log(jsonData.length)
			for (var i = 0; i < jsonData.length; i++) {
				var booking = jsonData[i];
				//create table row
				var row = "<tr><td><a href='Booking.php/?id="+booking.id+"'><img id='editIcon' src='./images/editIcon.svg' alt='EDIT'></a></td><td>" + booking.type + "</td><td>" + booking.customer.firstName + " " + booking.customer.lastName + " </td><td>";
				for (var k = 0; k < booking.animals.length; k++) {
					var animal = booking.animals[k];
					row += animal.name;
				}
				row += "</td><td>" + booking.room.name + "</td>";
				if(cur.getTime()===todaysDate.getTime()){
					if (tableType === "insTable") {
						if (booking.arrived === "WAITING ARRIVAL") {
							row += "<td><button onclick='updateType(" + booking.id + ",3,this)' id='3 in' class='checkin-btn' type='button'><span>Check in</span></button></td>";
						} else {
							row += "<td><button onclick='updateType(" + booking.id + ",1,this)' id='1' class='checked-btn' type='button'><span>Checked In</span></button></td>";
						}
					} else if (tableType === "outsTable") {
						if (booking.arrived === "CHECKED IN") {
							row += "<td><button onclick='updateType(" + booking.id + ",2,this)' id='2' class='checkout-btn' type='button'><span>Check out</span></button></td>";
						} else {
							row += "<td><button onclick='updateType(" + booking.id + ",3,this)' id='3 out' class='checked-btn' type='button'><span>Checked out</span></button></td>";
						}
					}
				}else{
					if (tableType === "insTable") {
						if (cur<todaysDate) {
							row += "<td><button class='nottodays-btn' type='button'><span>Checked in</span></button></td>";
						} else {
							row += "<td><button class='nottodays-btn' type='button'><span>Check In</span></button></td>";
						}
					} else if (tableType === "outsTable") {
						if (cur<todaysDate) {
							row += "<td><button class='nottodays-btn' type='button'><span>Checked out</span></button></td>";
						} else {
							row += "<td><button class='nottodays-btn' type='button'><span>Check out</span></button></td>";
						}
					}

				}
				body.append(row);
			}
		}

		function updateType(bookingID, type, clickedButton) {
			$.ajax({
				url: "http://192.168.0.7:8081/api/v1/bookings/" + bookingID + "/" + type,
				type: "POST",
				crossDomain: true,
				success: function(result) {
					var sentType = $(clickedButton).attr("id");
					var button = $(clickedButton);
					if(sentType === "3 in"){
						var onclickmethod = button.attr("onclick");
						var onclickArray = onclickmethod.split(",")
						onclickArray[1] = "1";
						var method = onclickArray[0]+","+onclickArray[1]+","+onclickArray[2];
						button.attr("onclick", method);
						button.attr("class", "checked-btn");
						button.attr("id", "1");
						button.find("span").text("Checked In")
					}if(sentType === "3 out"){
						var onclickmethod = button.attr("onclick");
						var onclickArray = onclickmethod.split(",")
						onclickArray[1] = "2";
						var method = onclickArray[0]+","+onclickArray[1]+","+onclickArray[2];
						button.attr("onclick", method);
						button.attr("class", "checkout-btn");
						button.attr("id", "2");
						button.find("span").text("Check out")
					}else if (sentType === "2"){
						var onclickmethod = button.attr("onclick");
						var onclickArray = onclickmethod.split(",")
						onclickArray[1] = "3";
						var method = onclickArray[0]+","+onclickArray[1]+","+onclickArray[2];
						button.attr("onclick", method);
						button.attr("class", "checked-btn");
						button.attr("id", "3 out");
						button.find("span").text("Checked out")
					}else if(sentType === "1"){	
						var onclickmethod = button.attr("onclick");
						var onclickArray = onclickmethod.split(",")
						onclickArray[1] = "3";
						var method = onclickArray[0]+","+onclickArray[1]+","+onclickArray[2];
						button.attr("onclick", method);
						button.attr("class", "checkin-btn");
						button.attr("id", "3 in");
						button.find("span").text("Check in")	
					}					
				}
			});
		}
	</script>
</head>

<body>
	<?php
	require("./Components/Header.php");
	$webAddress = 'http://192.168.0.7:8081/api/v1';
	?>
	<div class="container">
		<div class="dateContainer">
			<span class="visibleLabel">Today's Dashboard</span>
			<div class="arrowContainer">
				<span class="arrows" onclick=reduceDate()>
					<</span> <span class="arrows" onclick=increaseDate()>>
				</span>
			</div>

		</div>
		<div class="column">
			<div class="smallContainer">
				<span class="smallHeadings">Ins (<span id="insCount"></span>)</span>
				<div class="tableContainer">
					<table class="actions" id="insTable">
						<thead>
							<tr>
								<th></th>
								<th>Service</th>
								<th>Customer</th>
								<th>Animals</th>
								<th>Room</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="column">
			<div class="smallContainer">
				<span class="smallHeadings">Outs (<span id="outsCount"></span>)</span>
				<div class="tableContainer">
					<table class="actions" id="outsTable">
						<thead>
							<tr>
								<th></th>
								<th>Service</th>
								<th>Customer</th>
								<th>Animals</th>
								<th>Room</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>

			</div>
		</div>

	</div>
	<div class="container">
		<div class="bottomColumn">
			<div class="bottomContainer">
				<span class="smallHeadings">Current Occupants</span>
				<div class="tableContainer">
					<table class="actions">
						<thead>
							<tr>
								<th>Service</th>
								<th>Customer</th>
								<th>Room</th>
								<th>Booking Details</th>
								<th>Days remaining</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>

</html>