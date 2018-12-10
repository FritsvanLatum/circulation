<?php

if ($_POST && $_POST['patron']) {
  $patron_barcode = $_POST['patron'];
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>ID cards</title>
    <meta charset="utf-8" />
  </head>

  <body>

    <form action="" method="post">

      Patron barcode<br />
      <input type="text" name="patron" /> <br />
      <input type="submit" value="Submit">
    </form>
    <div>
      Barcode: <?php echo $patron_barcode;?><br/>
    </div>
  </body>

</html>