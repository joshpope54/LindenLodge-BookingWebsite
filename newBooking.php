<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>New Booking</title>
        <link rel="stylesheet" type="text/css" href="./styles/NewBooking.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    </head>
    <body>
      <?php
          require("./Components/Header.php");
          $webAddress = 'http://localhost:8081/api/v1';
      ?>
      <div class="greeting">
          <p class="greeting">New Booking</p>
      </div>
      <div class="container">
          <div class="bookingBody">
            <div class="bookingSummary">
              <div class="bookingSummaryHeader">
                  <p class="bookingSummaryTitle">Summary</p>
              </div>
            </div>
              <div class="todayActivityHeader">
                  <p class="todaysActivityTitle">Check Availability</p>
              </div>
              <div class="bookingForm">
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
                      $('input[name="dates"]').daterangepicker();
                    </script>
                  </div>
                  <div class="row">
                    <div class="col-25">
                      <label for="petCount">Number of Pets</label>
                    </div>
                    <div class="col-75">
                      <select id="petCount" name="petCount">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="other">Other</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <input type="submit" value="Submit">
                  </div>
                </form>
              </div>

          </div>
    </body>
</html>
