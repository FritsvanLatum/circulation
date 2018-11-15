<?php

require_once './config/config.php';
require_once './pulllist/pulllist.php';


$pulllist = new Pulllist($config['wskey'],$config['secret'],$config['ppid'],$config['institution'],$config['defaultBranch']); 
if ($pulllist->get_pulllist()) {
  $result = $pulllist->get_list();
  $pulllist->items2html();
}

?>

<html>
	<head>
	   
	</head>
	<body>
	  
		<p>Config:
		  <pre><?php echo __DIR__;?></pre>
			<pre><?php echo json_encode($config, JSON_PRETTY_PRINT);?></pre>
		</p>
		<p>Result:
			<pre><?php echo json_encode($result, JSON_PRETTY_PRINT);?></pre>
		</p>
	</body>
	
</html>