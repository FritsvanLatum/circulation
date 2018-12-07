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
    <p>Keys etc. Circulation:
      <pre>
<?php echo json_encode($config,JSON_PRETTY_PRINT );?>
      </pre>
    </p>
    <p>Keys etc. IDM:
      <pre>
<?php echo json_encode($config_idm,JSON_PRETTY_PRINT );?>
      </pre>
    </p>

<?php    
if ($pulllist->get_pulllist()) {
?>  

    <p>Result of pulllist request:
      <pre>
<?php echo $pulllist;?>
      </pre>
    </p>

    <p>Generating tickets:
      <pre>
<?php
  //see function items2html in ./pullist/pullist.php
	$pulllist->items2html();
	echo 'Done!';
}
?>
      </pre>
    </p>
    
    
    
  </body>

</html>