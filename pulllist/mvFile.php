<?php

$html_tobeprinted_dir = __DIR__.'/tickets/tobeprinted';
$html_printed_dir = __DIR__.'/tickets/printed';

$html_file = $_GET['file'];
$moved = rename( $html_printed_dir.'/'.$html_file, $html_tobeprinted_dir.'/'.$html_file);
if ($moved) {
  echo "Move ok";
}
else {
  echo "Error in moving!";
}

?>