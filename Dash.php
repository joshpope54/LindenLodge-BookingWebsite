<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="./styles/Dash.css">
    </head>
    <body>
        <?php
            require("./Components/Header.php");
         ?>
         <div class="greeting">
             <p class="greeting"><b class="dateSelector">< </b>Todays dashboard<b class="dateSelector"> ></b></p>
         </div>
        <div class="upperInfo">
            <div class="basicInfoHolder">
                <div class="basicInfo">
                    <p class="basicInfoHeader">Ins</p>
                    <p class="basicInfoValue">11</p>
                </div>
                <div class="basicInfo">
                    <p class="basicInfoHeader">Outs</p>
                    <p class="basicInfoValue">5</p>
                </div>
                <div class="basicInfoLong">
                    <p class="basicInfoHeader">Total</p>
                    <p class="basicInfoValue">21<b class="small">/40</b></p>
                </div>
            </div>
            <div class="todayActivityHolder">
                <div class="todayActivityHeader">
                    <p class="todaysActivityTitle">Todays actions</p>
                </div>
                <table class="actions">
                    <tr>
                        <th>Booking ID</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Pets</th>
                        <th>Room</th>
                        <th>From</th>
                        <th>Till</th>
                        <th class="blank"></th>
                    </tr>
                    <tr>
                        <td>0</td>
                        <td>boarding</td>
                        <td>Jordan</td>
                        <td>Bobby</td>
                        <td>A-1</td>
                        <td>21st Febuary</td>
                        <td>26st Febuary</td>
                        <td><button class="checkin-btn" type="button"><span>Check in</span></button></td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>grooming</td>
                        <td>Ben</td>
                        <td>Frank</td>
                        <td>C-2</td>
                        <td>21st Febuary</td>
                        <td>6th March</td>
                        <td class="blank"><button class="checkout-btn" type="button"><span>Check out</span></button></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="currentOccupants">
            <div class="currentOccupantsHeader">
                <p class="todaysActivityTitle">Current Occupants</p>
            </div>
            <table class="actions">
                <tr>
                    <th>Room</th>
                    <th>Name</th>
                    <th>Breed</th>
                    <th>Species</th>
                    <th>Customer</th>
                    <th>Days remaining</th>
                    <th>Booking</th>
                </tr>
                <tr>
                    <td>A-1</td>
                    <td>Bobby</td>
                    <td>Golden retriever</td>
                    <td>Dog</td>
                    <td>Jordan</td>
                    <td>5</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>C-2</td>
                    <td>Frank</td>
                    <td>Maine coon</td>
                    <td>Cat</td>
                    <td>Ben</td>
                    <td>14</td>
                    <td>1</td>
                </tr>
            </table>
        </div>
    </body>
</html>
