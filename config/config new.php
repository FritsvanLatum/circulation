<?php
//worldcatsearch config

$config = [];

$config['name'] = "NLVRD";
$config['institution'] = "57439";
$config['defaultBranch'] = "262638";
$config['datacenter'] = "sd02";

$config['wskey'] = "tA2yibd4vmeqTt7TYEmYNOzLHUWEGGF07v3dlR8YnvQni9wI0DLpBbARZtOZXpDsCFELxwV202smYFx8";
$config['secret'] = "akpDcRzATqHZakmw3b76bvJcIDrNqGKY";

$config['ppid_namespace'] = "urn:oclc:platform:57439";

$config['auth_url'] = "http://www.worldcat.org/wskey/v2/hmac/v1";
$config['auth_headers'] = ["Accept: application/json"];
$config['auth_method'] = 'GET';

    
$config['url'] = "https://57439.share.worldcat.org/circ/pulllist/262638";
$config['method'] = 'GET';
$config['headers'] = ["Accept: application/json"];


?>
