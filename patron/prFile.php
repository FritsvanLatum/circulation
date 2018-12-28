<?php
$printer = 'balie';
$pdf_file = $_GET['file'];

if (file_exists($pdf_file)) {
  //try to print when pdf_file exists
  $cmd = "lp -d $printer $pdf_file";
  $output = array();
  $retval = 1;
  $last_line = exec($cmd, $output, $retval);

  if ($retval == 0) {
    //print succeeded now delete the file
    $deleted = false;
    //$deleted = unlink($pdf_file);
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
  exit("File $pdf_file not found.(From: ".__DIR__.")");
}

?>