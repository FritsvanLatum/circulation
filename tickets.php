<!DOCTYPE html>
<?php
require_once './vendor/autoload.php';

$tobeprinted_dir = 'pulllist/tickets/tobeprinted';
$printed_dir = 'pulllist/tickets/printed';
//$mpdf = new \Mpdf\Mpdf();
function cmp($a,$b) {
//  het is makkelijker als je wilt zoeken naar recent NIET afgedrukte bonnen als je op datum sorteert. [aad]
//  return strcmp(strtolower($a["patron"].' '.$a['title']), strtolower($b["patron"].' '.$b['title']));
  return strcmp(strtolower($a["date"]), strtolower($b["date"]));
}

function generateRows($dir,$template_file) {
  $files = scandir($dir);
  $aantal=0;
  $rows = array();
  foreach ($files as $file) {
    if(strpos($file,"html")>0){
      //get data
      $doc = new DOMDocument();
      $doc->loadHTMLFile($dir.'/'.$file,LIBXML_NOWARNING | LIBXML_NOERROR);
      $data = array();
      $data['date'] = $doc->getElementById('date')->textContent ;
      $data['shelf'] = $doc->getElementById('shelf') ? $doc->getElementById('shelf')->textContent : ' ';
      $data['callNumber'] = $doc->getElementById('callNumber')->textContent ;
      $data['title'] = $doc->getElementById('title')->textContent ;
      $data['barcode'] = $doc->getElementById('barcode')->textContent ;
      $data['patron'] = $doc->getElementById('patron')->textContent ;
      $data['dir'] = $dir;
      $data['file'] = $file;
      //make row
      $rows[] = $data;
    }
  } 
  usort($rows,"cmp");
  foreach ($rows as $row) {   
      $aantal++;
      $row = array_merge(array('no'=>$aantal),$row);
      $loader = new Twig_Loader_Filesystem(__DIR__);
      $twig = new Twig_Environment($loader, array(
      //specify a cache directory only in a production setting
      //'cache' => './compilation_cache',
      ));


      $html_row = $twig->render($template_file, $row);
      echo $html_row;
  
  }

  return true;
}
?>

<html>
  <head>
    <title>Loopbonnen</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="css/lijst.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript">
      //function to move files from printed naar tobeprinted

      function printFile(fileName) {
        var jqxhr = $.ajax({
          url: 'pulllist/prFile.php?file='+fileName,
          //async: false
        }).done(function(data, textStatus, jqXHR) {
          console.log(textStatus + ' - ' + data);
        })
        .fail(function(jqXHR, textStatus, errorThrown ) {
          console.log(textStatus + ' - ' + errorThrown+ ' - ' + jqXHR.responseText);
        });
      }

/*      function moveFile(fileName) {
        var jqxhr = $.ajax({
          url: 'pulllist/mvFile.php?file='+fileName,
          //async: false
        }).done(function(data, textStatus, jqXHR) {
          console.log(textStatus + ' - ' + data);
        })
        .fail(function(jqXHR, textStatus, errorThrown ) {
          console.log(textStatus + ' - ' + errorThrown+ ' - ' + jqXHR.responseText);
        });

        location.reload();
      }*/
    </script>
  </head>
  <body>
    <h3>Loopbonnen die nog afgedrukt moeten worden:</h3>
    <div class="divTable paleBlueRows">
      <div class="divTableHeading">
        <div class="divTableRow">
          <div class="divTableHead">#</div>
          <div class="divTableHead">Date</div>
          <div class="divTableHead">Shelving location</div>
          <div class="divTableHead">Call number</div>
          <div class="divTableHead">Title</div>
          <div class="divTableHead">Barcode</div>
          <div class="divTableHead">Patron</div>
          <div class="divTableHead">Loopbon</div>
        </div>
      </div>
      <div class="divTableBody">
        <?php
        generateRows($tobeprinted_dir,'pulllist/tablerow_template.html');
        ?>
      </div>
    </div>

    <h3>Loopbonnen die al afgedrukt zijn:</h3>
    <div class="divTable paleBlueRows">
      <div class="divTableHeading">
        <div class="divTableRow">
          <div class="divTableHead">#</div>
          <div class="divTableHead">Date</div>
          <div class="divTableHead">Shelving location</div>
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
        generateRows($printed_dir,'pulllist/tablerow_print_template.html');
        ?>
      </div>
    </div>


  </body>
</html>
