<?php

require_once './config/key.php';
require_once './pulllist/pulllist.php';


$pulllist = new Pulllist($config['wskey'],$config['secret'],$config['ppid']); 

if ($pulllist->get_pulllist()) {
  $result = $pulllist->get_list();
  $pulllist->items2html();
}

?>

<html>
	<head>
	   
	</head>
	<body>
	  
		<p>Pulllist object:
			<pre><?php echo $pulllist;?></pre>
		</p>

	</body>
	
</html>