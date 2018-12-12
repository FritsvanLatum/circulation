<!DOCTYPE html>
<?php
require_once './vendor/autoload.php';

$tobeprinted_dir = 'pulllist/tickets/tobeprinted';
$printed_dir = 'pulllist/tickets/printed';

//$mpdf = new \Mpdf\Mpdf();


function generateRows($dir) {
  $tobeprinted_files = scandir($dir);
  $template_file = 'pulllist/tablerow_template.html';
  
  //default data
  $data = array(
  'date' => 'date',
  'callNumber' => 'callNumber',
  'title' => 'title',
  'barcode' => 'barcode',
  'patron' => 'patron',
  'file' => 'file',
  'print' => 'print'
  );

  foreach ($tobeprinted_files as $file) {
    if(strpos($file,"html")>0){


      //get data
      $doc = new DOMDocument();
      $doc->loadHTMLFile($dir.'/'.$file,LIBXML_NOWARNING | LIBXML_NOERROR);
      $data['date'] = $doc->getElementById('date')->textContent ;
      $data['callNumber'] = $doc->getElementById('callNumber')->textContent ;
      $data['title'] = $doc->getElementById('title')->textContent ;
      $data['barcode'] = $doc->getElementById('barcode')->textContent ;
      $data['patron'] = $doc->getElementById('patron')->textContent ;
      $data['file'] = $dir.'/'.$file;
      //make row
      $loader = new Twig_Loader_Filesystem(__DIR__);
      $twig = new Twig_Environment($loader, array(
      //specify a cache directory only in a production setting
      //'cache' => './compilation_cache',
      ));


      $row = $twig->render($template_file, $data);
      echo $row;
    }
  }
  return true;
}

?>

<html>
  <head>
    <title>Loopbonnen</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="css/lijst.css">

  </head>
  <body>
    <h3>Loopbonnen die nog afgedrukt moeten worden:</h3>
    <div class="divTable paleBlueRows">
      <div class="divTableHeading">
        <div class="divTableRow">
          <div class="divTableHead">Date</div>
          <div class="divTableHead">Call number</div>
          <div class="divTableHead">Title</div>
          <div class="divTableHead">Barcode</div>
          <div class="divTableHead">Patron</div>
          <div class="divTableHead">Loopbon</div>
          <div class="divTableHead">Print</div>
        </div>
      </div>
      <div class="divTableBody">
        <?php
        generateRows($tobeprinted_dir);
        ?>
      </div>
    </div>

    <h3>Loopbonnen die al afgedrukt zijn:</h3>
    <div class="divTable paleBlueRows">
      <div class="divTableHeading">
        <div class="divTableRow">
          <div class="divTableHead">Date</div>
          <div class="divTableHead">Call number</div>
          <div class="divTableHead">Title</div>
          <div class="divTableHead">Barcode</div>
          <div class="divTableHead">Patron</div>
          <div class="divTableHead">Loopbon</div>
          <div class="divTableHead">Print</div>
        </div>
      </div>
      <div class="divTableBody">
        <?php
        generateRows($printed_dir);
        ?>
      </div>
    </div>

  </body>
</html>