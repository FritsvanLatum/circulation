<?php
require_once __DIR__.'/../vendor/autoload.php';

$printer = 'magazijn';
$html_file = $_GET['file'];

$parts = explode('.',$html_file);
$last = array_pop($parts);
$pdf_file = implode('.',$parts).'.pdf';

$html_file = __DIR__.'/tickets/printed/'.$html_file;
$pdf_file =  __DIR__.'/tickets/temp_printer/'.$pdf_file;

if (file_exists($html_file)) {
  //generate pdf
  $html = file_get_contents($html_file);
  if (file_exists($pdf_file)) {
    //nothing todo?
  }
  else {
    //generate pdf from html
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->WriteHTML($html);
    //save pdf to $printer_as_dir
    $mpdf->Output($pdf_file);
  }


  //try to print when pdf_file exists
  $cmd = "lp -d $printer $pdf_file";
  $output = array();
  $retval = 1;
  $last_line = exec($cmd, $output, $retval);

  if ($retval == 0) {
    //print succeeded now delete the file
    $deleted = false;
    $deleted = unlink($pdf_file);
    if ($deleted) {
      echo "Print command '$cmd' succeeded, file deleted.";
    }
    else {
      echo "Print command '$cmd' succeeded, file NOT deleted.";
    }
    exit(0);
  }
  else {
    header('HTTP/1.1 500 Internal Server Error');
    exit("Print command '$cmd' failed: ($retval) ".implode(' - ',$output));
  }
}
else {
  header('HTTP/1.1 500 Internal Server Error');
  exit("File $html_file not found.(From: ".__DIR__.")");
}

?>