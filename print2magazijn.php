<?php

$html_tobeprinted_dir = __DIR__.'/pulllist/tickets/tobeprinted';
$html_printed_dir = __DIR__.'/pulllist/tickets/printed';
$pdf_tobeprinted_dir = __DIR__.'/pulllist/tickets/temp_printer';

$html_files = scandir($html_tobeprinted_dir);

//check each file in $tobeprinted_dir
foreach ($html_files as $html_file) {
  //only html files
  if(strpos($html_file,"html")>0){
    //echo "\n".$html_file."\n";
    //construct pdf file name from html file name 
    $parts = explode('.', $html_file);
    $last = array_pop($parts);
    $pdf_file = $pdf_tobeprinted_dir.'/'.implode('.',$parts).'.pdf';
    //echo $pdf_file."\n";
    if (file_exists($pdf_file)) {
      //try to print when pdf_file exists
      $retval = 1;
      //$last_line = exec('lp '.$pdf_file.' -d magazijn',$output, $retval);
      $retval = 0;
      //move html file and delete pdf file if command succeeded
      if ($retval == 0) {
        $moved = rename($html_tobeprinted_dir.'/'.$html_file, $html_printed_dir.'/'.$html_file);
        //echo json_encode($moved);
        $deleted = unlink($pdf_file);
      }
    }
  }
}
?>
