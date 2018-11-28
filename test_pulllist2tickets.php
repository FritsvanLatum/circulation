<?php
require_once './pulllist/key.php';
require_once './pulllist/pulllist.php';
require_once './patron/key_idm.php';

$pulllist = new Pulllist($config['wskey'],$config['secret'],$config['ppid'],$config_idm['wskey'],$config_idm['secret'],$config_idm['ppid']);

?>

<html>
  <head>

  </head>
  <body>
    <p>Config:
      <pre><?php echo json_encode($config,JSON_PRETTY_PRINT );?></pre>
    </p>
    <p>Config IDM:
      <pre><?php echo json_encode($config_idm,JSON_PRETTY_PRINT );?></pre>
    </p>

<?php    
if ($pulllist->get_pulllist()) {
?>  
    <p>Pulllist after get_pulllist:
      <pre><?php echo $pulllist;?></pre>
    </p>

<?php
	$pulllist->items2html();
}
?>
    
    
    
  </body>

</html>