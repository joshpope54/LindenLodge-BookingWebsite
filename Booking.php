<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Booking</title>
    </head>
    <body>
      <?php
        if (!empty($_GET['id'])) {
          echo "Insert booking for " . $_GET["id"];
        }
      ?>
    </body>
</html>
