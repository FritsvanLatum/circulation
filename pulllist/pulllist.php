<?php
require_once './OCLC/Auth/WSKey.php';
require_once './OCLC/User.php';
require_once __DIR__.'/../patron/patron.php';
require_once __DIR__.'/../vendor/autoload.php';

/**
* A class that represents a pullist
*/
class Pulllist {

  private $errors = [];

  //must be provided as parameters in $pulllist = new Pulllist($wskey,$secret,$ppid), see __construct
  private $wskey = null;
  private $secret = null;
  private $ppid = null;

  private $institution = "57439";
  private $defaultBranch = "262638";

  //$ppid_namespace is extended in __construct
  private $ppid_namespace = "urn:oclc:platform:";

  private $auth_url = 'http://www.worldcat.org/wskey/v2/hmac/v1';
  private $auth_method = 'GET';
  private $auth_headers = ['Accept: application/json'];

  //$pulllist_url is extended in __construct
  private $pulllist_url = "share.worldcat.org/circ/pulllist";

  //pulllist
  public $list = null;
  public $no_of_items = null;

  private $pullist_dir = null;
  private $tickets_dir = 'tickets';
  private $tobeprinted_dir = 'tobeprinted';
  private $pulllist_filename = 'actual_pulllist.json';
  private $previous_pulllist_filename = 'previous_pulllist.json';

  private $patron = null;

  //twig
  private $twig = null;
  private $template = 'ticket_template.html';

  public function __construct($wskey,$secret,$ppid,$idm_wskey,$idm_secret,$idm_ppid) {
    //oclc business
    $this->wskey = $wskey;
    $this->secret = $secret;
    $this->ppid = $ppid;
    $this->ppid_namespace = $this->ppid_namespace.$this->institution;
    $this->pulllist_url = 'https://'.$this->institution.'.'.$this->pulllist_url.'/'.$this->defaultBranch;

    //directory structure
    $this->pullist_dir = __DIR__;  //might be another directory, the web server has to have write access

    $this->tickets_dir = $this->pullist_dir.'/'.$this->tickets_dir;
    $this->pulllist_filename = $this->tickets_dir.'/'.$this->pulllist_filename;
    $this->previous_pulllist_filename = $this->tickets_dir.'/'.$this->previous_pulllist_filename;
    $this->tobeprinted_dir = $this->tickets_dir.'/'.$this->tobeprinted_dir;

    //$temp = new Patron($idm_wskey,$idm_secret,$idm_ppid);
    //echo $temp;
    $this->patron = new Patron($idm_wskey,$idm_secret,$idm_ppid);
    
    //Twig
    $loader = new Twig_Loader_Filesystem(__DIR__);
    $this->twig = new Twig_Environment($loader, array(
    //specify a cache directory only in a production setting
    //'cache' => './compilation_cache',
    ));
  }

  public function __toString(){
    //create an array and return json_encoded string
    $json = [
    'wskey' =>$this->wskey,
    'secret' => $this->secret,
    'ppid' => $this->ppid,

    'institution' => $this->institution,
    'defaultBranch' => $this->defaultBranch,

    'ppid_namespace' => $this->ppid_namespace,

    'auth_url' => $this->auth_url,
    'auth_method' => $this->auth_method,
    'auth_headers' => $this->auth_headers,

    'pulllist_url' => $this->pulllist_url,

    'list' => $this->list,
    'no_of_items' => $this->no_of_items,

    'pullist_dir' => $this->pullist_dir,
    'tickets_dir' => $this->tickets_dir,
    'tobeprinted_dir' => $this->tobeprinted_dir,
    'pulllist_filename' => $this->pulllist_filename,
    'previous_pulllist_filename' => $this->previous_pulllist_filename,
    'patron' => get_object_vars($this->patron),
    'twig' => ($this->twig === null) ? $this->twig : 'is initiated',
    'template' => $this->template,
    ];
    return json_encode($json, JSON_PRETTY_PRINT);
  }

  private function get_pulllist_auth_header($url,$method) {
    //get an authorization header
    //  with wskey, secret and if necessary user data from $config
    //  for the $method and $url provided as parameters

    $authorizationHeader = '';
    if ($this->wskey && $this->secret) {
      $options = array();
      if ($this->institution && $this->ppid && $this->ppid_namespace) {
        //uses OCLC provided programming to get an autorization header
        $user = new User($this->institution, $this->ppid, $this->ppid_namespace);
        $options['user'] = $user;
      }
      //echo "Options: ".json_encode($options, JSON_PRETTY_PRINT);
      if (count($options) > 0) {
        $wskeyObj = new WSKey($this->wskey, $this->secret, $options);
        $authorizationHeader = $wskeyObj->getHMACSignature($method, $url, $options);
      }
      else {
        $wskeyObj = new WSKey($config['wskey'], $config['secret'],null);
        $authorizationHeader = $wskeyObj->getHMACSignature($method, $url, null);
      }
      //check??
      $authorizationHeader = 'Authorization: '.$authorizationHeader;
    }
    return $authorizationHeader;
  }

  public function get_pulllist() {
    //authorization
    $authorizationHeader = $this->get_pulllist_auth_header($this->auth_url,$this->auth_method);
    array_push($this->auth_headers,$authorizationHeader);

    //CURL
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $this->pulllist_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $this->auth_headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($curl, CURLOPT_, );
    //curl_setopt($curl, CURLOPT_, );

    $result = curl_exec($curl);
    $error_number = curl_errno($curl);
    curl_close($curl);


    if ($error_number) {
      //return info in json format
      $result = '{"Curl_errno": "'.$error_number.'", "Curl_error": "'.curl_error($curl).'"}';
      $this->errors['curl'] = json_decode($result,TRUE);
      return false;
    }
    else {
      //store result in this object as an array
      $this->list = json_decode($result,TRUE);

      //save but not after renaming the previous one
      if (file_exists($this->pulllist_filename)) {
        rename($this->pulllist_filename,$this->previous_pulllist_filename);
      }
      file_put_contents($this->pulllist_filename,json_encode($this->list, JSON_PRETTY_PRINT));

      //number of items
      if (array_key_exists('entries',$this->list)) {
        $this->no_of_items = count($this->list['entries']);
      }
      return true;
    }
  }

  public function get_item($i) {
    $result = null;
    if ($this->list && array_key_exists('entries',$this->list) && ($i < $this->no_of_items)) {
      $result = $this->list['entries'][$i];
    }
    return $result;
  }

  public function items2html() {
    //use Twig to make a html file for each entry

    if ($this->list && array_key_exists('entries',$this->list)) {
      $tel = 0;
      foreach ($this->list['entries'] as $entry) {
        $tel++;
        $patronIdentifier = $entry['content']['patronIdentifier']['ppid'];
        echo "<hr/><br>patron identifier from list [$tel]: ".$patronIdentifier."<br>";
        $this->patron->read_patron($patronIdentifier);
        $barcode = $this->patron->get_barcode();
        echo "<br>patron barcode[$tel]: ".$barcode."<br>";
        echo "<br>errors[$tel]: ".json_encode($this->patron->errors,JSON_PRETTY_PRINT)."<br>";
        
        $entry['content']['lenerbarcode']=$barcode;

        try {
          $html = $this->twig->render($this->template, $entry);
          
          //use request id as filename
          $filename = $this->tobeprinted_dir.'/'.$entry['content']['requestId'].'.html';
          if (file_exists($filename)) {
            //-------- for testing, in production: do nothing if file exists
            echo "File exists: ".$filename;
            file_put_contents($filename,$html);
          }
          else {
            file_put_contents($filename,$html);
          }
        }
        catch (Exception $e) {
          echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
      }
    }
  }
}

