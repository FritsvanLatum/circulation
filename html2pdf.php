<?php
require_once __DIR__ . '/vendor/autoload.php';

$tobeprinted_dir = __DIR__.'/pulllist/tickets/tobeprinted';
$tobeprinted_files = scandir($tobeprinted_dir);
$printed_dir = __DIR__.'/pulllist/tickets/printed';


foreach ($tobeprinted_files as $file) {
  if(strpos($file,"html")>0){
    $html_str=file_get_contents($tobeprinted_dir.'/'.$file);
    $pdf_file=substr($file,0,-5).".pdf";
    echo "<hr/><br/>file: $file<br/>pdf file: $pdf_file<br/>";
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html_str);

    //deze regel vervangen door rechtstreeks printen
    //string Output ( [ string $filename [, string $dest ])
    //https://mpdf.github.io/reference/mpdf-functions/output.html

    //of aanvullen met een print opdracht, bijv. met shell_exec()
    $mpdf->Output($printed_dir.'/'.$pdf_file);


    //copy html file naar printed

  }
}

?>
