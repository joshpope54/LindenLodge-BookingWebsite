<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>New Booking</title>
        <link rel="stylesheet" type="text/css" href="./styles/NewBookingPage2.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <script>
          $(document).on("click", ".livesearch p", function(){
            $(this).parents(".containerRow").find('input[type="text"]').val($(this).text());
            $(this).parent(".livesearch").empty();
            var customerId = $(this).attr('id');
            $.ajax({
                url: "http://localhost:8081/api/v1/animals/customerid/"+customerId,
                type : "GET",
                crossDomain: true,
                success: function(result){
                  $('#pets').empty();
                  for (var i = 0; i < result.length; i++) {
                      var pet = result[i];
                      $('#pets').append(new Option(pet.name));
                  }


                  //$("#livesearch").html(result).show();
                }
              })
          });

          function showResult(str) {
            if (str.length==0) {
              $(".livesearch").empty();
              $('#pets').empty();
              return;
            }

            $.ajax({
                url: "http://localhost:8081/api/v1/customers/name/"+str,
                type : "GET",
                crossDomain: true,
                success: function(result){
                  $(".livesearch").empty();
                  for (var i = 0; i < result.length; i++) {
                    $(".livesearch").append("<p id="+result[i].id+">"+result[i].firstName +" "+ result[i].lastName +"</a>");
                  }
                  //$("#livesearch").html(result).show();
                }
              })
          }

          function firstDateCheck() {
            var userDate = $("#checkInDate").get(0).value;
            var todaysDate = new Date();
            $('#room').empty();
            if(new Date(userDate).setHours(0,0,0,0) < todaysDate.setHours(0,0,0,0)){
              alert("Check In Date cannot be in the past.")
              $("#checkInDate").val("");
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
                  $(".row#available").show();
                  $(".availabilityContainer").attr("id","availableFalse");
                }else{
                  $(".row#available").show();
                  $(".availabilityContainer").attr("id","availableTrue");
                  //create green box and inform room sizes
                }
              });
          };

          function secondDateCheck() {
            var userDate = $("#checkOutDate").get(0).value;
            var firstDate = $("#checkInDate").get(0).value;
            $('#room').empty();
            if(new Date(userDate).setHours(0,0,0,0) < new Date(firstDate).setHours(0,0,0,0)){
              alert("Check Out Date cannot be before check in date.")
              $("#checkOutDate").val("");
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
        <form>
          <div class="upperSectionContainer">
            <div class="upperBody">
              <div class="bookingStepsHeader">
                <input type="radio" id="kennels" name="bookingType" value="kennels">
                <label for="kennels">Kennels</label>
                <input type="radio" id="cattery" name="bookingType" value="cattery">
                <label for="cattery">Cattery</label>
                <input type="radio" id="grooming" name="bookingType" value="grooming">
                <label for="grooming">Grooming</label>
              </div>
            </div>
          </div>
          <div class="lowerSectionContainer">
            <div class="lowerBody">
              <div class="row" id="insouts">
                <div class="leftSide" id="insouts">
                  <div class="containerRow" id="startDate">
                    <div class="leftSideColumn" id="startDate">
                      <label for="startDate">Check In Date</label>
                    </div>
                    <div class="rightSideColumn" id="startDate">
                      <input type="date" id="checkInDate" name="startDate" onchange="firstDateCheck()">
                    </div>
                  </div>
                </div>
                <div class="rightSide" id="insouts">
                  <div class="containerRow" id="endDate">
                    <div class="leftSideColumn" id="endDate">
                      <label for="endDate">Check Out Date</label>
                    </div>
                    <div class="rightSideColumn" id="endDate">
                      <input type="date" id="checkOutDate" name="endDate" onchange="secondDateCheck()">
                    </div>
                  </div>
                </div>
              </div>
              <div hidden class="row" id="available"  style="background-color:white">
                <div class="availabilityContainer">
                </div>
              </div>
              <div class="row" id="bigRow">
                <div class="leftSide">
                  <div class="containerRow">
                    <div class="leftSideColumn">
                      <label for="customer">Customer</label>
                    </div>
                    <div class="rightSideColumn" id="withLiveSearch">
                      <input autocomplete="off" type="text" id="customer" name="customer" placeholder="Customer Name" onkeyup="showResult(this.value)">
                      <div class="livesearch"></div>
                    </div>
                  </div>
                  <div class="containerRow">
                    <div class="leftSideColumn">
                      <label for="pets">Pets</label>
                    </div>
                    <div class="rightSideColumn">
                      <select id="pets" name="pets">
                        <option value="NONE">Define a Customer First</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="rightSide">
                  <div class="containerRow">
                    <div class="leftSideColumn">
                      <label for="room">Assign a room</label>
                    </div>
                    <div class="rightSideColumn">
                      <select id="room" name="room">
                        <option value="NONE">Define the Check in and out dates first</option>
                      </select>
                    </div>
                  </div>
                  <div class="containerRow">
                    <div class="leftSideColumn">
                      <label for="extras">Extras</label>
                    </div>
                    <div class="rightSideColumn">
                      <select id="extras" name="extras">
                        <option value="none">No Extras</option>
                        <option value="taxi">Taxi Service</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row" id="buttons">
                <input type="submit" value="Book"></input>
              </div>
            </div>
          </div>
        </form>
      </div>
  </div>
</body>
</html>
