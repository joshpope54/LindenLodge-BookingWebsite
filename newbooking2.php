<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>New Booking</title>
	<link rel="stylesheet" type="text/css" href="./styles/newbooking4.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Lato|Roboto&display=swap" rel="stylesheet">

	<?php
	require("./Components/Header.php");
	$webAddress = 'http://192.168.0.7:8081/api/v1';
	//var_dump($_GET["petId"]);
	?>
	<script>
		var customer = -1;
		var pets = [];
		var startDate = "";
		var endDate = "";
		var petsJson;
		var totalCost = 0;
		var room;

		//// TODO:
		//update number of nights when changed.

		function firstDateCheck() {
			var userDate = $("#checkInDate").get(0).value;
			var todaysDate = new Date();
			$('#room').empty();
			if (new Date(userDate).setHours(0, 0, 0, 0) < todaysDate.setHours(0, 0, 0, 0)) {
				alert("Check In Date cannot be in the past.")
				$("#checkInDate").val("");
				return false;
			} else if (endDate != "") {
				if (new Date(userDate).setHours(0, 0, 0, 0) > new Date(endDate).setHours(0, 0, 0, 0)) {
					alert("Check In Date cannot be after the check out date");
					$("#checkInDate").val("");
				} else {
					startDate = userDate;
					updateRoomsAndDisplayErrorContainer();
				}
			} else {
				startDate = userDate;
				return true;
			}
		}

		function secondDateCheck() {
			var userDate = $("#checkOutDate").get(0).value;
			var firstDate = $("#checkInDate").get(0).value;
			$('#room').empty();
			if (new Date(userDate).setHours(0, 0, 0, 0) < new Date(firstDate).setHours(0, 0, 0, 0)) {
				alert("Check Out Date cannot be before check in date.")
				$("#checkOutDate").val("");
				return false;
			} else {
				endDate = userDate;
				updateRoomsAndDisplayErrorContainer();
				return true;
			}
		}

		function updateRoomsAndDisplayErrorContainer() {
			$.ajax({
				url: "http://192.168.0.7:8081/api/v1/rooms/available/" + startDate + "/" + endDate,
				type: "GET",
				crossDomain: true,
			}).done(function(data) {
				var counts = [0, 0, 0, 0];
				$('#room').empty();
				for (var i = 0; i < data.length; i++) {
					var counter = data[i];
					$('#room').append(new Option(counter.name + " (" + counter.size + ")", counter.id));
					switch (counter.size) {
						case "Small":
							counts[0] += 1;
							break;
						case "Normal":
							counts[1] += 1;
							break;
						case "Large":
							counts[2] += 1;
							break;
						case "Family":
							counts[3] += 1;
							break;
					}
				}
				if (data.length == 0) {
					//create red box and offer to look at calender for dates
					$(".itemRow#available").show();
					$(".availabilityContainer").attr("id", "availableFalse");
					$('#room').append(new Option("No rooms available"));
				} else {
					$(".itemRow#available").show();
					$(".availabilityContainer").attr("id", "availableTrue");
					//create green box and inform room sizes
				}
			});
		};

		function showResult(str) {
			if (str.length == 0) {
				$(".livesearch").empty();
				$('#pets').empty();
				return;
			}
			$.ajax({
				url: "http://192.168.0.7:8081/api/v1/customers/name/" + str,
				type: "GET",
				crossDomain: true,
				success: function(result) {
					$(".livesearch").empty();
					for (var i = 0; i < result.length; i++) {
						$(".livesearch").append("<p id=" + result[i].id + ">" + result[i].firstName + " " + result[i].lastName + "</a>");
					}
				}
			})
		}

		$(document).on("click", ".bookingButton", function() {
			room = $("select#room").children("option:selected").val();
			type = $('input[name="bookingType"]:checked').val();
			if (type == "kennels") {
				type = "Kennel";
			} else if (type == "cattery") {
				type = "Cattery";
			} else {
				type = "Grooming";
			}
			for (var i = 0; i < pets.length; i++) pets[i] = +pets[i];

			markers = {
				"customer": {
					"id": parseInt(customer)
				},
				"room": {
					"id": parseInt(room)
				},
				"startDate": startDate,
				"endDate": endDate,
				"status": 1,
				"paymentTotal": totalCost,
				"type": type,
				"arrived": 1,
				"animals": []
			};
			for (var i = 0; i < pets.length; i++) {
				markers['animals'].push({
					"id": pets[i]
				});
			}
			jsonStr = JSON.stringify(markers);
			$.ajax({
				type: "POST",
				url: "http://192.168.0.7:8081/api/v1/bookings",
				// The key needs to match your method's input parameter (case-sensitive).
				data: jsonStr,
				contentType: "application/json; charset=utf-8",
				dataType: "json",
				beforeSend: function() {
					// setting a timeout
					$(placeholder).addClass('loading');
				},
				success: function(data) {
					alert("Booking with ID " + data.id + " created!");
					window.location.reload();
				},
				failure: function(errMsg) {
					alert(errMsg);
				}
			});

			//alert("Type of booking: " + type + "Start Date: " + startDate + " End Date: " + endDate + " Customer ID: " + customer + " List Of Pet IDS:" + pets + " room ID: " + room + " total Cost: " + totalCost);
		});



		$(document).on("click", ".livesearch p", function() {
			$(this).parents(".itemRow").find('input[type="text"]').val($(this).text());
			$(this).parent(".livesearch").empty();
			//add hidden customer element
			customer = $(this).attr('id');
			$('<input>').attr({
				type: 'hidden',
				value: customer,
				name: 'customer'
			}).appendTo('form');
			$.ajax({
				url: "http://192.168.0.7:8081/api/v1/animals/customerid/" + customer,
				type: "GET",
				crossDomain: true,
				success: function(result) {
					$('#pets').empty();
					$('#pets').append(new Option("Select a pet from the list", "NONE"));
					petsJson = result;
					for (var i = 0; i < result.length; i++) {
						var pet = result[i];
						$('#pets').append(new Option(pet.name, pet.id));
					}
				}
			})
		});

		$(document).on("change", "select#pets", function() {
			var selectedPetID = $(this).val();
			var selectedPetName = $(this).find("option:selected").text();
			pets.push(selectedPetID);
			$(this).find("option[value='" + selectedPetID + "']").remove();
			var petsContainer = $(".addedPetsContainer");
			var addedAnimal = $("<div></div>").addClass("addedPet").appendTo(petsContainer);
			var animalName = $("<span>" + selectedPetName + "</span>").addClass("petName").attr('id', selectedPetID).appendTo(addedAnimal);
			addToCostBoard(selectedPetID, selectedPetName);
			$('<input>').attr({
				type: 'hidden',
				value: selectedPetID,
				name: 'petId[]'
			}).appendTo('form');
		});

		$(document).on("click", ".addedPet", function() {
			var petName = $(this).text();
			var petId = $(this).find("span").attr('id');
			var index = pets.indexOf(petId);
			if (index > -1) {
				pets.splice(index, 1);
			}
			var newOption = new Option(petName, petId);
			$('#pets').append(newOption);
			$(this).remove();
			$(".costDetailsRow#" + petId).remove();
			updateTotals();
		});


		function addToCostBoard(petID, petName) {
			if (startDate != "" && endDate != "") {
				var date1 = new Date(startDate);
				var date2 = new Date(endDate);
				var timeDiff = Math.abs(date2.getTime() - date1.getTime());
				var nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
			} else {
				nights = 0;
			}
			nights = parseFloat(nights);
			var correctAnimal;
			for (var i = 0; i < petsJson.length; i++) {
				var obj = petsJson[i];
				if (obj.id == petID) {
					correctAnimal = obj;
				}
			}
			var pet_type = correctAnimal.petType.typeName;
			var rate_cost;
			$.ajax({
				url: "http://192.168.0.7:8081/api/v1/rates/" + correctAnimal.petType.id,
				type: "GET",
				crossDomain: true,
				success: function(result) {
					rate_cost = parseFloat(result.cost);
					var total_cost = rate_cost * nights;
					var petCost = `${nights} Nights for ${petName} - £${rate_cost} (${pet_type})`;
					var detailsContainer = $(".individualAnimalCosts");
					var detailsRow = $("<div></div>").addClass("costDetailsRow").attr('id', petID).appendTo(detailsContainer);
					var detail = $("<span>" + petCost + "</span>").addClass("costDetailsDetail").appendTo(detailsRow);
					var detail1 = $("<span>£ " + total_cost + "</span>").addClass("costDetailsDetail").attr('id', "beRight").appendTo(detailsRow);
					updateTotals();
				}
			});



		}

		function updateTotals() {
			$('.costDetailsRow > .costDetailsDetail#beRight').each(function() {
				var beRightText = $(this).text();
				beRightText = beRightText.split(" ");
				var individualAsString = beRightText[1];
				var individualAsFloat = parseFloat(individualAsString);
				totalCost = totalCost + individualAsFloat;
			})
			$('.costDetailsRowAlways#first > .costDetailsDetail#beRight').text("Subtotal: " + totalCost);
			$('.costDetailsRowAlways#last > .costDetailsDetail#beRight').text("Total: " + totalCost);
		}
	</script>
</head>

<body>
	<div class="pageContent">
		<div class="successHeader">
						
		</div>
		<div class="container">
			<div class="column">
				<div class="bookingContainer">
					<div class="bookingType">
						<input type="radio" id="kennels" name="bookingType" value="kennels">
						<label class="drinkcard-cc kennels" for="kennels"></label>
						<input type="radio" id="cattery" name="bookingType" value="cattery">
						<label class="drinkcard-cc cattery" for="cattery"></label>
						<input type="radio" id="grooming" name="bookingType" value="grooming">
						<label class="drinkcard-cc grooming" for="grooming"></label>
					</div>
					<div class="itemRow">
						<div class="leftSideItem">
							<label for="startDate">Check In Date</label>
						</div>
						<div class="rightSideItem">
							<input type="date" id="checkInDate" name="startDate" onchange="firstDateCheck()">
						</div>
					</div>

					<div class="itemRow">
						<div class="leftSideItem">
							<label for="endDate">Check Out Date</label>
						</div>
						<div class="rightSideItem">
							<input type="date" id="checkOutDate" name="endDate" onchange="secondDateCheck()">
						</div>
					</div>

					<div hidden class="itemRow" id="available">
						<div class="leftSideItem">
						</div>
						<div class="rightSideItem">
							<div class="availabilityContainer">
								<span class="availableText">Good News</span>
							</div>
						</div>
					</div>

					<div class="itemRow">
						<div class="leftSideItem">
							<label for="customer">Customer</label>
						</div>
						<div class="rightSideItem" id="withLiveSearch">
							<input autocomplete="off" type="text" id="customer" placeholder="Customer Name" onkeyup="showResult(this.value)">
							<div class="livesearch"></div>
						</div>
					</div>


					<div class="itemRow">
						<div class="leftSideItem">
							<label for="pets">Pets</label>
						</div>
						<div class="rightSideItem">
							<select id="pets">
								<option value="NONE">Define a Customer First</option>
							</select>
							<div class="addedPetsContainer">
							</div>
						</div>
					</div>

					<div class="itemRow">
						<div class="leftSideItem">
							<label for="room">Room</label>
						</div>
						<div class="rightSideItem">
							<select id="room">
								<option value="NONE">Define the Check in and out dates first</option>
							</select>
						</div>
					</div>

					<div class="itemRow">
						<div class="leftSideItem">
							<label for="extras">Extras</label>
						</div>
						<div class="rightSideItem">
							<select id="extras" name="extras">
								<option value="0">No Extras</option>
							</select>
						</div>
					</div>

				</div>
			</div>
			<div class="column">
				<div class="bookingContainer">
					<span class="costsHeading">Costs</span>
					<div class="costDetailsContainer">
						<div class="individualAnimalCosts">

						</div>
						<div class="TotalContainer">
							<div class="costDetailsRowAlways" id="first">
								<span class="costDetailsDetail" id="beRight">Subtotal: 0</span>
							</div>
							<div class="costDetailsRowAlways" id="second">
								<span class="costDetailsDetail" id="beRight">Tax (0%): 0</span>
							</div>
							<div class="costDetailsRowAlways" id="last">
								<span class="costDetailsDetail" id="beRight">Total: 0</span>
							</div>
						</div>



					</div>
					<div class="addAdditionalCost">
						<span class="additionalCostDetails">+ Add an Additional Cost</span>
					</div>
					<div class="book">
						<div class="bookButtonContainer">
							<input class="bookingButton" type="submit" value="Book">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>