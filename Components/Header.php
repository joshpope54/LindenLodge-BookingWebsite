<link href="https://fonts.googleapis.com/css?family=Lato|Roboto&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./styles/Header.css">
<div class="header">
    <?php
        $values = array("Dashboard","Bookings","Customers");
        foreach ($values as $key) {
            echo '<div class="headerButton">';
                echo '<p class="headerButtonText">'. $key .'</p>';
            echo '</div>';
        }
     ?>
     <div class="headerRight">
         <button class="login-btn" type="button"><span>New booking</span></button>
         <p class="headerRightText">Your profile</p>
     </div>
</div>
