#!/usr/bin/php
<?php
//on windows ZipArchive doesn't like driveletters and backward slashes
//comment the following 2 lines before pushing to github
//$html_printed_dir = '/xampp/htdocs/oclcAPIs/circulation/pulllist/tickets/printed';
//$html_archive_dir = '/xampp/htdocs/oclcAPIs/circulation/pulllist/tickets/archive';

//on linux use __DIR__
//uncomment the following 2 lines before pushing to github
$html_printed_dir = __DIR__.'/pulllist/tickets/printed';
$html_archive_dir = __DIR__.'/pulllist/tickets/archive';

//a zipfile for eacht month: tickets201901.zip, tickets201902.zip,etc.
$zip_name = $html_archive_dir.'/tickets'.date('Ym').'.zip';

$num_of_days_before_archiving = 2;

$zip = new ZipArchive;

$opened = FALSE;
$created = FALSE;
if (file_exists($zip_name)) {
  $opened = $zip->open($zip_name);
}
else {
  $created = $zip->open($zip_name, ZipArchive::CREATE);
}

if ($opened || $created) {
  $html_files = scandir($html_printed_dir);
  $tel = 0;

  //check each file in $tobeprinted_dir
  foreach ($html_files as $html_file) {
    //only html files
    if(strpos($html_file,"html")>0){
      $html_file = $html_printed_dir.'/'.$html_file;

      $dt_this_date = new DateTime();
      $dt_this_date->setTimeStamp(time());
      $dt_file_date = new DateTime();
      $dt_file_date->setTimeStamp(filectime($html_file));

      $difference = intval($dt_this_date->diff($dt_file_date)->format("%a"));

      /*echo "Now: ".$dt_this_date->format('Y-m-d')."<br/>\n";
      echo "File: ".$dt_file_date->format('Y-m-d')."<br/>\n";
      echo "Diff: ".$difference."<br/>\n";*/

      if ($difference > $num_of_days_before_archiving) {
        $tel++;
        $zipped = $zip->addFile($html_file,basename($html_file));
        $deleted = unlink($html_file);
      }
    }

  }
}

if (file_exists($zip_name)) $zip->close();

?>
