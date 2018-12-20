<?php

$pdf_file = $_GET['file'];

if (file_exists($pdf_file)) {
  //try to print when pdf_file exists
  $retval = 1;
  $last_line = exec('lp '.$pdf_file.' -d balie',$output, $retval);
  //$retval = 0;
  //move html file and delete pdf file if command succeeded
  if ($retval == 0) {
    $deleted = unlink($pdf_file);
  }
}


?>