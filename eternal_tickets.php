#!/usr/bin/php
<?php
require_once './pulllist/key.php';
require_once './pulllist/pulllist.php';
require_once './patron/key_idm.php';
$html_tobeprinted_dir = __DIR__.'/pulllist/tickets/tobeprinted';
$html_printed_dir = __DIR__.'/pulllist/tickets/printed';
$pdf_tobeprinted_dir = __DIR__.'/pulllist/tickets/temp_printer';

$debug = 'all';

while (TRUE) {
    //create Pulllist object, let it collect the actual pulllist from WMS and if it is collected create HTML files
    $pulllist = new Pulllist($config['wskey'],$config['secret'],$config['ppid'],$config_idm['wskey'],$config_idm['secret'],$config_idm['ppid']);
    if ($pulllist->get_pulllist()) {
    
        if ($debug == 'all') $pulllist->log_entry('Message','eternal_tickets','Downloaded pulllist from WMS.');
        
        $pulllist->items2html();
        if ($debug == 'all') $pulllist->log_entry('Message','eternal_tickets','Tickets created.');
    }
    else {
        $pulllist->log_entry('Error','eternal_tickets','No pulllist found in WMS.');
    }
    
    sleep(120); //2 minutes

    //print tickets
    $html_files = scandir($html_tobeprinted_dir);
    //check each file in $tobeprinted_dir
    foreach ($html_files as $html_file) {
        //only html files
        if(strpos($html_file,"html")>0) {

            //construct pdf file name from html file name
            $parts = explode('.', $html_file);
            $last = array_pop($parts);
            $pdf_file = $pdf_tobeprinted_dir.'/'.implode('.',$parts).'.pdf';
            if ($debug == 'all') $pulllist->log_entry('Message','eternal_tickets',"Trying to print: '$pdf_file'");
            if (file_exists($pdf_file)) {
                //try to print when pdf_file exists
                $retval = 1;
                $last_line = exec('lp '.$pdf_file.' -d magazijn',$output, $retval);

                //move html file and delete pdf file if command succeeded
                if ($retval == 0) {
                    //move html file
                    $moved = rename($html_tobeprinted_dir.'/'.$html_file, $html_printed_dir.'/'.$html_file);
                    //delete pdf file
                    $deleted = unlink($pdf_file);
                }
                else {
                    $pulllist->log_entry('Message','eternal_tickets',"Print command failed: ".'lp '.$pdf_file.' -d magazijn');
                }
            }
            else {
                $pulllist->log_entry('Message','eternal_tickets',"PDF file '$pdf_file' not found.");
            }
        }
    }
    sleep(180); //3 minutes

}
?>