<!DOCTYPE html>
<html>
  <head>
    <title>Loopbonnen</title>
    <meta charset="utf-8" />
  </head>
  <body>
    <h2>Nog te printen loopbonnen</h2>
    <table>
      <?php
      $tobeprinted_dir = __DIR__.'/pulllist/tickets/tobeprinted';
      $html_dir = 'pulllist/tickets/tobeprinted';
      $tobeprinted = scandir($tobeprinted_dir);
      foreach ($tobeprinted as $file) {
        if (($file != '.') && ($file != '..')) {
          echo '<tr><td><a href="'.$html_dir.'/'.$file.'">'.$file.'</a></td><td>PRINT BUTTON</td></tr>';
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