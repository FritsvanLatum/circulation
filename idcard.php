<?php
require_once './patron/key_idm.php';
require_once './patron/patron.php';
require_once './vendor/autoload.php';

$debug = FALSE;
if (array_key_exists('debug',$_GET)) $debug = TRUE;


$template_file = './patron/idcard_template.html';
$patron_barcode = null;
$patron = new Patron($config_idm['wskey'], $config_idm['secret'], $config_idm['ppid']);

if (array_key_exists('patronBarcode',$_GET)) {
  $patron_barcode = $_GET['patronBarcode'];

  //get patron
  $search = '{"schemas": ["urn:ietf:params:scim:api:messages:2.0:SearchRequest"], '.
  '"filter": "External_ID eq \"'.$patron_barcode.'\""}';
  $patron->search_patron($search);
  
  $msg = '';
  //analyze result
  if ($patron->search['totalResults'] == 0) {
    //not found
    $msg = "No patron found with barcode: $patron_barcode.";
  }
  else {
    if ($patron->search['totalResults'] > 1){
      //more patrons share the same barcode...
    $msg = "Please note: more then one patron found with barcode: $patron_barcode.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>ID cards</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" id="theme_stylesheet" href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css">
    <link rel="stylesheet" id="icon_stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="css/idcard.css">

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jsoneditor.min.js"></script>
    <script type="text/javascript" src="schema/idcardSchema.js"></script>
  </head>

  <body>
    <div id="editor"></div>
    <div id="buttons">
      <button id='submit'>Search patron</button>
      <button id='empty'>Empty form</button>
    </div>
    <div id="res" class="alert">
    <?php if ($patron->search['totalResults'] > 0) {
      if ($patron->search['totalResults'] > 1) echo "Please note: more then one patron found with barcode: $patron_barcode.";
      $tel = 0;
      foreach ($patron->search['Resources'] as $resource) {
        $tel++;
        //
        $resource['teller'] = $tel;

        $loader = new Twig_Loader_Filesystem(__DIR__);
        $twig = new Twig_Environment($loader, array(
        //specify a cache directory only in a production setting
        //'cache' => './compilation_cache',
        ));

        echo $twig->render($template_file, $resource);
      }
    }
    else echo "No patron found with barcode: $patron_barcode.";
    ?>
    </div>
    <?php if ($debug) { ?>
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
    <?php } ?>

    <script type="text/javascript" src="js/idcardForm.js"></script>
  </body>

</html>