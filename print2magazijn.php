<?php

$html_tobeprinted_dir = __DIR__.'/pulllist/tickets/tobeprinted';
$html_files = scandir($html_tobeprinted_dir);
$printed_dir = __DIR__.'/pulllist/tickets/printed';

$PDF_tobeprinted_dir = __DIR__.'/pulllist/tickets/temp_printer';


//check each file in $tobeprinted_dir
foreach ($html_files as $html_file) {
  if(strpos($html_file,"html")>0){
  $parts = explode('.', $html_file);
  $last = array_pop($parts);
  $pdf_file = $PDF_tobeprinted_dir.'/'.implode('.',$parts).'.pdf';
  if (file_exists($pdf_file)) {
    echo "printing!\n\n";
    
  }
}
}
?>
