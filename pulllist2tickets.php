<?php

require_once './pulllist/key.php';
require_once './pulllist/pulllist.php';
echo __DIR__;

$pulllist = new Pulllist($config['wskey'],$config['secret'],$config['ppid']); 

if ($pulllist->get_pulllist()) {
  $result = $pulllist->get_list();
  $pulllist->items2html();
}

?>
