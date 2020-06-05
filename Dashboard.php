<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="./styles/Dash.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
          function updateType(bookingID, type, clickedButton){
            $.ajax({
                url: "http://localhost:8081/api/v1/bookings/"+bookingID+"/"+type,
                type : "POST",
                crossDomain: true,
                success: function(result){
                  window.location.reload();
                }
              })
          };
        </script>
    </head>

    <body>
        <?php
            require("./Components/Header.php");
            $webAddress = 'http://localhost:8081/api/v1';
            $date = date("Y-m-d");
            if(!empty($_GET["date"])){
              $givenDate = $_GET["date"];
              $tommorow = strtotime("+1 day", strtotime($givenDate));
              $yesterday = strtotime("-1 day", strtotime($givenDate));
            }else{
              $givenDate = $date;
              $tommorow = strtotime("+1 day", strtotime($date));
              $yesterday = strtotime("-1 day", strtotime($date));
            }

            function createTableRow($item, $webAddress){
              global $date, $givenDate;
              echo "<tr>";
              echo "<td><a class='viewBooking' href='Booking.php?id=".$item["id"]."'>View Booking</a></td>";
              echo "<td>". $item["type"] . "</td>";
              $customer = file_get_contents($webAddress.'/customers/'.$item["customerId"]);
              $customer = json_decode($customer, true);
              echo "<td>". $customer["firstName"] . " " . $customer["lastName"] . "</td>";
              echo "<td>";
              for ($i = 0; $i <= count($item["animals"])-1; $i++) {
                if($i == 0){
                  echo $item["animals"]["".$i.""]["name"];
                }else if($i==count($item["animals"])-1){
                  echo " and ".$item["animals"]["".$i.""]["name"];
                }else{
                  echo ", " . $item["animals"]["".$i.""]["name"];
                }
              }
              $booking_start_date = strtotime($item["startDate"]);
              $end_date = strtotime($item["endDate"]);
              echo " for ". ($end_date - $booking_start_date)/60/60/24 ." days</td>";

              if($givenDate == $date){
                if($item["startDate"] == $date){
                  if($item["arrived"]=="WAITING ARRIVAL"){
                    echo "<td align='center'><button onclick='updateType(".$item["id"].", 1, this)' class='checkin-btn' type='button'><span>Check in</span></button></td>";
                  }else{
                    echo "<td align='center'><button onclick='updateType(".$item["id"].", 3, this)' class='checked-btn' type='button'><span>Checked In</span></button></td>";
                  }
                }else if ($item["endDate"] = $date){
                  if($item["arrived"]=="CHECKED IN"){
                    echo "<td align='center'><button onclick='updateType(".$item["id"].", 2, this)' class='checkout-btn' type='button'><span>Check out</span></button></td>";
                  }else{
                    echo "<td align='center'><button onclick='updateType(".$item["id"].", 1, this)' class='checked-btn' type='button'><span>Checked out</span></button></td>";
                  }
                }
              }else{
                if(strtotime($item["startDate"]) == strtotime($givenDate)){
                  if (strtotime($item["startDate"]) < strtotime($date)){
                    echo "<td align='center'><button class='nottodays-btn' type='button'><span>Checked in</span></button></td>";
                  }else{
                    echo "<td align='center'><button class='nottodays-btn' type='button'><span>Check in</span></button></td>";
                  }
                }else if (strtotime($item["endDate"]) == strtotime($givenDate)){
                  if (strtotime($item["endDate"]) < strtotime($date)){
                    echo "<td align='center'><button class='nottodays-btn' type='button'><span>Checked out</span></button></td>";
                  }else{
                    echo "<td align='center'><button class='nottodays-btn' type='button'><span>Check out</span></button></td>";
                  }
                }
              }
              echo "</tr>";
            }
         ?>
         <div class="greeting">
             <p class="greeting"><?php
              if(empty($_GET["date"])){
                echo "Today's Dashboard";
              }else{
                if($givenDate == date("Y-m-d", strtotime("+1 day"))){
                  echo "Tommorow's Dashboard";
                }else if($givenDate > date("Y-m-d", strtotime("+1 day"))){
                  echo $givenDate . "'s Dashboard";
                }
                if($givenDate == date("Y-m-d", strtotime("-1 day"))){
                  echo "Yesterday's Dashboard";
                }else if($givenDate < date("Y-m-d", strtotime("-1 day"))){
                  echo $givenDate . "'s Dashboard";
                }
              }
             ?></p>
             <p class="dateSelector" float='right'><a class="dateSelectorLink"<?php
                if($yesterday==strtotime($date)){
                  echo "href = Dashboard.php";
                }else{
                  echo "href = Dashboard.php?date=". date("Y-m-d", $yesterday);
                }
              ?>><</a>

             <a class="dateSelectorLink" <?php
                if($tommorow==strtotime($date)){
                  echo "href = Dashboard.php";
                }else{
                  echo "href = Dashboard.php?date=". date("Y-m-d", $tommorow);
                }
             ?>>></a></p>
         </div>
        <div class="upperInfo">
            <div class="todayActivityHolderIns">
                <div class="todayActivityHeaderIns">
                    <p class="todaysActivityTitle">Ins</p>
                    <p class="insOutsCounter"><?php
                    if($givenDate == $date){
                      $insOutsCount = file_get_contents($webAddress.'/bookings/count/insouts');
                      $insOutsCount = json_decode($insOutsCount, true);
                    }else{
                      $insOutsCount = file_get_contents($webAddress.'/bookings/count/insouts/'.$givenDate);
                      $insOutsCount = json_decode($insOutsCount, true);
                    }
                    echo $insOutsCount["insCount"];
                    ?></p>
                </div>
                <div class="itemBodys">
                  <table class="actions">
                      <tr>
                          <th></th>
                          <th>Service</th>
                          <th>Customer</th>
                          <th>Details</th>
                          <th>Actions</th>
                      </tr>
                      <?php
                          if(!empty($_GET["date"])){
                            $response = file_get_contents($webAddress.'/bookings/date/'.$givenDate);
                            $response = json_decode($response, true);
                          }else{
                            $response = file_get_contents($webAddress.'/bookings/today');
                            $response = json_decode($response, true);
                          }
                          foreach($response as $item) { //foreach element in $arr
                            if($item["startDate"] == $givenDate){
                              createTableRow($item, $webAddress);
                            }
                          }
                       ?>

                  </table>
                </div>
            </div>
            <div class="todayActivityHolderOuts">
                <div class="todayActivityHeaderOuts">
                    <p class="todaysActivityTitle">Outs</p>
                    <p class="insOutsCounter"><?php
                    echo $insOutsCount["outsCount"];
                    ?></p>
                </div>
                <div class="itemBodys">
                  <table class="actions">
                      <tr>
                          <th></th>
                          <th>Service</th>
                          <th>Customer</th>
                          <th>Details</th>
                          <th>Actions</th>
                      </tr>
                      <?php
                          if(!empty($_GET["date"])){
                            $response = file_get_contents($webAddress.'/bookings/date/'.$givenDate);
                            $response = json_decode($response, true);
                          }else{
                            $response = file_get_contents($webAddress.'/bookings/today');
                            $response = json_decode($response, true);
                          }
                          //this is an array
                          foreach($response as $item) { //foreach element in $arr
                            if($item["endDate"] == $givenDate){
                              createTableRow($item, $webAddress);
                            }
                          }
                       ?>
                  </table>
                </div>
            </div>
        </div>
        <div class="lowerInfo">
          <div class="currentOccupants">
              <div class="currentOccupantsHeader">
                  <p class="todaysActivityTitle">Current Bookings</p>
              </div>
              <div class="itemBodys">
                <table class="actions">
                    <tr>
                        <th></th>
                        <th>Booking Type</th>
                        <th>Customer Name</th>
                        <th>Assigned Room</th>
                        <th>Booking Details</th>
                        <th>Days remaining</th>
                    </tr>
                    <?php
                        $currentBookings = file_get_contents($webAddress.'/bookings/current');
                        $currentBookings = json_decode($currentBookings, true);
                        //this is an array
                        foreach($currentBookings as $item) { //foreach element in $arr
                          echo "<tr>";
                          echo "<td><a class='viewBooking' href='Booking.php?id=".$item["id"]."'>View Booking</a></td>";
                          echo "<td>".$item["type"]."</td>";
                          $customer = file_get_contents($webAddress.'/customers/'.$item["customerId"]);
                          $customer = json_decode($customer, true);
                          echo "<td>". $customer["firstName"] . " " . $customer["lastName"] . "</td>";
                          echo "<td>". $item["room"]["name"] . "</td>";
                          $booking_start_date = strtotime($item["startDate"]);
                          $end_date = strtotime($item["endDate"]);
                          echo "<td>";
                          for ($i = 0; $i <= count($item["animals"])-1; $i++) {
                            if($i == 0){
                              echo $item["animals"]["".$i.""]["name"];
                            }else if($i==count($item["animals"])-1){
                              echo " and ".$item["animals"]["".$i.""]["name"];
                            }else{
                              echo ", " . $item["animals"]["".$i.""]["name"];
                            }
                          }

                          echo " for ". ($end_date - $booking_start_date)/60/60/24 ." days</td>";
                          $start_date = strtotime($date);
                          echo "<td>". ($end_date - $start_date)/60/60/24 ."</td>";
                          echo "</tr>";
                        }
                     ?>
                </table>
              </div>
          </div>
        </div>
    </body>
</html>
