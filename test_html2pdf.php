<?php
require_once __DIR__ . '/vendor/autoload.php';

$tobeprinted_dir = __DIR__.'/pulllist/tickets/tobeprinted';
$tobeprinted_files = scandir($tobeprinted_dir);
$printed_dir = __DIR__.'/pulllist/tickets/printed';

//testing only
$printer_as_dir = __DIR__.'/pulllist/tickets/temp_printer';
?>

<html>
  <head>

  </head>
  <body>

    <?php
    //check each file in $tobeprinted_dir
    foreach ($tobeprinted_files as $file) {
      if(strpos($file,"html")>0){
        $html_str=file_get_contents($tobeprinted_dir.'/'.$file);

        if ($html_str !== FALSE) {
          $pdf_file=substr($file,0,-5).".pdf";
          
          //testing only
          echo "<hr/><br/>file: $file<br/>pdf file: $pdf_file<br/>";

          $mpdf = new \Mpdf\Mpdf();
          $mpdf->WriteHTML($html_str);

          //PRINT TO PRINTER
          //deze regel vervangen door rechtstreeks printen
          //of aanvullen met een print opdracht, bijv. met shell_exec()
          
          //save pdf to $printer_as_dir
          $mpdf->Output($printer_as_dir.'/'.$pdf_file);


          //IF printing was ok: copy html file naar $printed_dir
          $renamed = rename($tobeprinted_dir.'/'.$file,$printed_dir.'/'.$file);
          if ($renamed === FALSE) echo "rename did not succeed!<br/>"; 


        }
      }
    }

    ?>

  </body>

</html>