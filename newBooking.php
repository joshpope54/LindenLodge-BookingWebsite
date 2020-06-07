<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>New Booking</title>
        <link rel="stylesheet" type="text/css" href="./styles/NewBookingPage2.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

        <script>
          function showResult(str) {
            if (str.length==0) {
              document.getElementById("livesearch").innerHTML="";
              document.getElementById("livesearch").style.border="0px";
              return;
            }

            $.ajax({
                url: "http://localhost:8081/api/v1//customers/name/"+str,
                type : "GET",
                crossDomain: true,
                success: function(result){
                  console.log(result);
                  $("#livesearch").empty();
                  for (var i = 0; i < result.length; i++) {
                    $("#livesearch").append("<a>"+result[i].firstName +" "+ result[i].lastName +"</a>");
                  }

                  //$("#livesearch").html(result).show();
                }
              })
          }

          function firstDateCheck() {
            var userDate = $("#startDate").get(0).value;
            var todaysDate = new Date();
            $('#room').empty();
            if(new Date(userDate).setHours(0,0,0,0) < todaysDate.setHours(0,0,0,0)){
              alert("Check In Date cannot be in the past.")
              $("#startDate").val("");
              return false;
            }else{
              return true;
            }
          }

          function updateType(startDate, endDate){
            $.ajax({
                url: "http://localhost:8081/api/v1/rooms/available/"+startDate+"/"+endDate,
                type : "GET",
                crossDomain: true,
              }).done(function(data) {
                var counts = [0, 0, 0, 0];
                $('#room').empty();
                for (var i = 0; i < data.length; i++) {
                    var counter = data[i];
                    $('#room').append(new Option(counter.name +" ("+counter.size+")", counter.name));
                    switch(counter.size) {
                      case "Small":
                        counts[0]+=1;
                        break;
                      case "Normal":
                        counts[1]+=1;
                        break;
                      case "Large":
                        counts[2]+=1;
                        break;
                      case "Family":
                        counts[3]+=1;
                        break;
                    }
                }
                if(data.length==0){
                  //create red box and offer to look at calender for dates
                }else{
                  //create green box and inform room sizes
                }
              });
          };

          function secondDateCheck() {
            var userDate = $("#endDate").get(0).value;
            var firstDate = $("#startDate").get(0).value;
            $('#room').empty();
            if(new Date(userDate).setHours(0,0,0,0) < new Date(firstDate).setHours(0,0,0,0)){
              alert("Check Out Date cannot be before check in date.")
              $("#endDate").val("");
              return false;
            }else{
              updateType(firstDate, userDate);
              return true;
            }
          }
        </script>
    </head>
    <body>
      <?php
          require("./Components/Header.php");
          $webAddress = 'http://localhost:8081/api/v1';
      ?>
      <div class="container">
          <div class="bookingStepsContainer">
            <div class="bookingSteps">
              <div class="bookingStepsHeader">
                  <p class="bookingStepsTitle">Types</p><br>
                  <p class="bookingStepsTitle">Check Availability</p><br>
                  <p class="bookingStepsTitle">Customer and pet(s)</p><br>
                  <p class="bookingStepsTitle">Select Room</p><br>
                  <p class="bookingStepsTitle">Confirmation</p><br>
              </div>
            </div>
          </div>



          <div class="bookingFormContainer">
            <div class="bookingForm">
              <form action="/action_page.php">
                <div class="row">
                  <div class="col-25">
                    <label for="type">Type of visit</label>
                  </div>
                  <div class="col-75">
                    <input type="text" id="type" name="type" placeholder="Kennel">
                  </div>
                </div>
                <div class="row">
                  <div class="col-25">
                    <label for="startDate">Check In Date</label>
                  </div>
                  <div class="col-75">
                    <input type="date" id="startDate" name="startDate" onchange="firstDateCheck()">
                  </div>
                </div>
                <div class="row">
                  <div class="col-25">
                    <label for="endDate">Check Out Date</label>
                  </div>
                  <div class="col-75">
                    <input type="date" id="endDate" name="endDate" onchange="secondDateCheck()">
                  </div>
                </div>
                <div class="row" id="availability">
                  <div class="col-25">
                  </div>
                </div>
                <div class="row">

                  <div class="col-25">
                    <label for="room">Assign a room</label>
                  </div>
                  <div class="col-75">
                    <select id="room" name="room">
                      <option value="NONE">Define the Check in and out dates first</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-25">
                    <label for="customer">Customer</label>
                  </div>
                  <div class="col-75">
                    <input type="text" id="customer" name="customer" placeholder="Customer Name" onkeyup="showResult(this.value)">
                    <div id="livesearch" style="height:50px"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-25">
                    <label for="pet">Pets</label>
                  </div>
                  <div class="col-75">
                    <input type="text" id="pet" name="pet" placeholder="" onkeyup="showResult(this.value)">
                    <div id="livesearch"></div>
                  </div>
                </div>
                <div class="row">
                  <input type="submit" value="Submit">
                </div>
                </form>
            </div>
          </div>
      </div>
  </div>
</body>
</html>


              <!-- <div class="bookingForm">
                <form class="bookingForm">
                  <div class="row">
                    <div class="col-25">
                      <label for="service">Service</label>
                    </div>
                    <div class="col-75">
                      <select id="service" name="service">
                        <option value="kennel">Kennel Board</option>
                        <option value="cattery">Cattery Board</option>
                        <option value="other">Other</option>
                      </select>
                    </div>
                  </div>


                  <div class="row">
                    <div class="col-25">
                      <label for="startDate">Check In</label>
                    </div>
                    <div class="col-75">
                        <input type="text" name="dates">
                    </div>
                    <script>
                      $(function() {
                        $('input[name="dates"]').daterangepicker();
                        $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
                            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                            function updateType(startDate, endDate){
                              $.ajax({
                                  url: "http://localhost:8081/api/v1/rooms/available/"+startDate+"/"+endDate,
                                  type : "GET",
                                  crossDomain: true,
                                }).done(function(data) {
                                  var counts = [0, 0, 0, 0];
                                  for (var i = 0; i < data.length; i++) {
                                      var counter = data[i];
                                      switch(counter.size) {
                                        case "Small":
                                          counts[0]+=1;
                                          break;
                                        case "Normal":
                                          counts[1]+=1;
                                          break;
                                        case "Large":
                                          counts[2]+=1;
                                          break;
                                        case "Family":
                                          counts[3]+=1;
                                          break;
                                      }
                                  }
                                  alert(counts);

                                  if(data.length==0){
                                    //create red box and offer to look at calender for dates
                                  }else{
                                    //create green box and inform room sizes
                                    $("#availabilityRow").show();
                                  }
                                });
                            };
                            updateType(picker.startDate.format('YYYY-MM-DD'),picker.endDate.format('YYYY-MM-DD'));
                        });
                      });
                    </script>
                  </div>
                  <div class="row" id="availabilityRow">
                    <div class="availability">
                      <p> test</p>

                    </div>
                  </div> -->

                  <!-- <div class="row">
                    <input type="submit" value="Submit">
                  </div> -->



<!--<?php
  // if(isset($_GET["service"]) && isset($_GET["dates"]) && isset($_GET["petCount"])){
  //   $dates = explode(" ",$_GET["dates"]);
  //   $startDate = $dates[0];
  //   $endDate = $dates[2];
  //   $startDate = explode("/",$startDate);
  //   $endDate = explode("/",$endDate);
  //   $response = file_get_contents($webAddress.'/rooms/available/'.$startDate[2]."-".$startDate[0]."-".$startDate[1]."/".$endDate[2]."-".$endDate[0]."-".$endDate[1]);
  //   $response = json_decode($response, true);
  //   //Check available needs to take into consideration animal size and amount of animals.
  //   if(sizeof($response)!=0){
  //     echo "GREEN BOX";
  //     //display green box with number of rooms
  //   }else{
  //     echo "RED BOX";
  //     //display red box and offer ability to view calender with all bookings on it
  //     //can be a request for a given month - ie june
  //     //return number of available rooms on a given day.
  //     //select bookings where given date falls between a single day
  //   }
  ?> -->
