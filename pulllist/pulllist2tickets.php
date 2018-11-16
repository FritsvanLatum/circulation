<?php

require_once 'key.php';
require_once 'pulllist.php';


$pulllist = new Pulllist($config['wskey'],$config['secret'],$config['ppid']); 

if ($pulllist->get_pulllist()) {
  $result = $pulllist->get_list();
  $pulllist->items2html();
}

?>
