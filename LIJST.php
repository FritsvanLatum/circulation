<!DOCTYPE html>
<?PHP
require_once './vendor/autoload.php';
//$mpdf = new \Mpdf\Mpdf();

?>

<html>
  <head>
    <title>Loopbonnen</title>
    <meta charset="utf-8" />
  </head>
  <body>
    <h2>Nog te printen loopbonnen in HTML</h2>
    <table>
      <?php
      $tobeprinted_dir = __DIR__.'/pulllist/tickets/tobeprinted';
      $html_dir = 'pulllist/tickets/tobeprinted';
      $tobeprinted = scandir($tobeprinted_dir);
      foreach ($tobeprinted as $file) {
	$mpdf = new \Mpdf\Mpdf();
        if (($file != '.') && ($file != '..')){
	$htmlfile=file_get_contents($html_dir.'/'.$file);
	if(strpos($file,"html")>0){
		$naam=substr($file,0,-5).".pdf";
		$pdf_filename=$html_dir.'_pdf/'.$naam;
		$mpdf->WriteHTML($htmlfile);
		$mpdf->Output($pdf_filename);
	}
	echo '<tr><td><a href="'.$html_dir.'/'.$file.'">'.$file.'</a></td><td>PRINT BUTTON</td></tr>';
        }
      }
      ?>
    </table>
   <h2>Nog te printen loopbonnen in PDF</h2>
<table>
<?php
$tobeprinted_dir = __DIR__.'/pulllist/tickets/tobeprinted_pdf';
$pdf_dir = 'pulllist/tickets/tobeprinted_pdf';
$tobeprinted = scandir($tobeprinted_dir);
foreach ($tobeprinted as $file) {
	if (($file != '.') && ($file != '..')){
		echo '<tr><td><a href="'.$pdf_dir.'/'.$file.'">'.$file.'</a></td><td>PRINT BUTTON</td></tr>';
	}
}
?>
</table>
    <h2>Al afgedrukte loopbonnen</h2>
    <table>
      <?php
      $printed_dir = __DIR__.'/pulllist/tickets/printed';
      $html_dir = 'pulllist/tickets/printed';
      $printed = scandir($printed_dir);
      foreach ($printed as $file) {
        if (($file != '.') && ($file != '..')) {
          echo '<tr><td><a href="'.$html_dir.'/'.$file.'">'.$file.'</a></td><td>PRINT BUTTON</td></tr>';
        }
      }
      ?>
    </table>
  </body>

</html>