<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once './pulllist/key.php';
require_once './pulllist/pulllist.php';
require_once './patron/key_idm.php';
require_once './patron/patron.php';

//echo __DIR__;

$pulllist = new Pulllist($config['wskey'],$config['secret'],$config['ppid'],$config_idm['wskey'],$config_idm['secret'],$config_idm['ppid']);

if ($pulllist->get_pulllist()) {
	$result = $pulllist->get_list();
	$pulllist->items2html();
}
?>