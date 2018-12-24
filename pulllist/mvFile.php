<?php

$html_file = $_GET['file'];

$html_tobeprinted_dir = __DIR__.'/tickets/tobeprinted';
$html_printed_dir = __DIR__.'/tickets/printed';

$moved = rename( $html_printed_dir.'/'.$html_file, $html_tobeprinted_dir.'/'.$html_file);

if ($moved) {
  echo "Move of $html_file ok";
  exit(0);
}
else {
  header('HTTP/1.1 500 Internal Server Error');
  exit("Could not move file $html_file.");
}

?>