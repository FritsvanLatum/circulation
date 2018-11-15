<?php

require_once './OCLC/Auth/WSKey.php';
require_once './OCLC/User.php';
require_once './vendor/autoload.php';



/**
* A class that represents a pullist
*/
class Pulllist {
  
  private $errors = [];

  //API
  private $wskey = null;
  private $secret = null;
  private $ppid = null;
  private $institution = null;
  private $defaultBranch = null;
  private $ppid_namespace = null;
  private $auth_url = 'http://www.worldcat.org/wskey/v2/hmac/v1';
  private $auth_method = 'GET';
  private $auth_headers = ['Accept: application/json'];
  private $pulllist_url = null;
  private $pulllist_filename = 'pulllist.json';
  private $previous_pulllist_filename = 'prev_pulllist.json';

  //pulllist
  private $list = null;
  private $no_of_items = null;
  
  //twig
  private $twig = null;
  private $template = 'ticket.html';
  private $tickets_dir = 'tickets/tobeprinted';  
  
  public function __construct($wskey,$secret,$ppid,$institution,$branch) {
    echo __DIR__;
    $this->wskey = $wskey;
    $this->secret = $secret;
    $this->ppid = $ppid;
    $this->institution = $institution;
    $this->ppid_namespace = 'urn:oclc:platform:'.$this->institution;
    $this->defaultBranch = $branch;
    $this->pulllist_url = 'https://'.$this->institution.'.share.worldcat.org/circ/pulllist/'.$this->defaultBranch;
    
    $loader = new Twig_Loader_Filesystem(__DIR__);
    $this->twig = new Twig_Environment($loader, array(
    //    'cache' => './compilation_cache',
    ));
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
    $authorizationHeader = $this->get_pulllist_auth_header($this->auth_url,$this->auth_method);
    array_push($this->auth_headers,$authorizationHeader);

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
      $result = '{"Curl_errno": "'.$error_number.'", "Curl_error": "'.curl_error($curl).'"}';
      $this->errors['curl'] = json_decode($result,TRUE);
      return false;
    }
    else {
      $this->list = json_decode($result,TRUE);
 
      if (file_exists($this->pulllist_filename)) {
       rename($this->pulllist_filename,$this->previous_pulllist_filename); 
      }
      file_put_contents($this->pulllist_filename,json_encode($this->list, JSON_PRETTY_PRINT));
       
      if (array_key_exists('entries',$this->list)) {
        $this->no_of_items = count($this->list['entries']);
      }
      return true;
    }
  }
  
  public function get_list() {
    return $this->list;
  }
 
  public function get_number_of_items() {
    return $this->no_of_items;
  } 
  
  public function get_item($i) {
    $result = null;
    if ($this->list && array_key_exists('entries',$this->list) && ($i < $this->no_of_items)) {
      $result = $this->list['entries'][$i];
    }
    return $result;
  }
  
  public function items2html() {
    if ($this->list && array_key_exists('entries',$this->list)) {
      foreach ($this->list['entries'] as $entry) {
        $html = $this->twig->render($this->template, $entry);
        $filename = 'test.html';
        file_put_contents(__DIR__.'/'.$this->tickets_dir.'/'.$filename,$html); 
      }
    }
  }
}

