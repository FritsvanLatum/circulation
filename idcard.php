<?php
require_once './patron/key_idm.php';
require_once './patron/patron.php';

$patron_barcode = null;
if (array_key_exists('patron',$_POST)) {


  $patron_barcode = $_POST['patron'];

  //get patron
  $patron = new Patron($config_idm['wskey'], $config_idm['secret'], $config_idm['ppid']);
  $search = '{"schemas": ["urn:ietf:params:scim:api:messages:2.0:SearchRequest"], '.
  '"filter": "External_ID eq \"'.$patron_barcode.'\""}';
  $patron->search_patron($search);

  //analyze result
  if ($patron->search['totalResults'] == 0) {
    //not found
    
  }
  else {
    if ($patron->search['totalResults'] > 1){
      //more patrons share the same barcode...

    }
    else {

    }
  }
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

      Patron barcode: <input type="text" name="patron" <?php if ($patron_barcode) echo 'value="'.$patron_barcode.'"' ; ?> /> <br />
      <input type="submit" value="Submit">
    </form>
    <div>
      Search:
      <pre>
        <?php if ($patron_barcode) echo $search;?>
      </pre>
    </div>
    <div>
      Patron:
      <pre>
        <?php if ($patron_barcode) echo $patron;?>
      </pre>
    </div>
  </body>

</html>