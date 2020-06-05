<link href="https://fonts.googleapis.com/css?family=Lato|Roboto&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./styles/Header.css">
<div class="header">
    <?php
        $values = array("Dashboard","Bookings","Customers");
        foreach ($values as $key) {
            echo '<div class="headerButton">';
                echo '<p class="headerButtonText"><a class="headerButtonLink" href="'.$key.'.php">'. $key .'</a></p>';
            echo '</div>';
        }
     ?>
     <div class="headerRight">
       <!-- <form class="headerForm" action="newBooking.php" method="post"> -->
          <a class="login-btn" type="button" href="newBooking.php"><span>New booking</span></a>
        <!-- </form> -->
        <p class="headerRightText">Your profile</p>
     </div>
</div>
