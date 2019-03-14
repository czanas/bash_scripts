<?php
/*
#Overview:
#This script checks the likelihood that a password has been leaked.
#It does so by by submitting the 
#first 5 characters of password SHA-1's has to the haveibeenpwned API
#
#Disclaimer: 
#This tool is not a password generator. It is simply used as a 'leak check'
#A stronger password should be longer.
#See: https://xkcd.com/936/
#
#Credit:
#API: https://haveibeenpwned.com/API/v2
#Based of the Computerphile episode: https://www.youtube.com/watch?v=hhUb5iknVJs

This is a PHP version. The password should be sent to this page in $_POST or 
could be used in the command line. 
CLI: php check_pass_pwnd.php PASSWORD

Required Library:
cURL plugin needs to be enabled. 
Install with: apt install libapache2-mod-php php-curl
*/


/*Auxillary function for making POST or GET requests
  using cURL
  public get function
    params: needs to be an associated array of parameters
    url: is the url where the request is posted
*/
function genericCurlGet($params, $url)
{
    $postData = '';
    //create name value pairs seperated by &
    foreach($params as $k => $v) 
    { 
        $postData .= $k . '='.$v.'&'; 
    }
    //$postData = rtrim($postData, '&');

    //Initiate cURL.
    $ch = curl_init();  

    $header = array();
    $header[] = "Accept: application/json";
    
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); 
    //cut the output of the header of the request
    curl_setopt($ch, CURLOPT_HEADER, false);
    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  
    //set the url of the conent
    curl_setopt($ch,CURLOPT_URL,$url."?".$postData);
    
    //make curl return the data instead of outputting it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    //make sure that curl doesn't print everything to screen
    curl_setopt($ch, CURLOPT_VERBOSE, 0);   

    $output=curl_exec($ch);

    curl_close($ch);
    return $output;  
}


/*this function submits the first 5 SHA1 chars to pwnedpasswords
  it returns the number of time the password has been pwned
  */
function subPwnPass($hashpassw, $PRINT=false, $lb='\n'){
    $hashpassw=strtoupper($hashpassw);
    $url = "https://api.pwnedpasswords.com/range/";
    $resp = genericCurlGet(array(), $url."/".substr($hashpassw,0,5)); 

    if($PRINT)
    {
        echo substr($hashpassw, 0, 5);
        echo substr($hashpassw,5);
    }
    $retVal = 0;

    $matchObj = preg_match("/(".substr($hashpassw,5).")\:(\d+)/i", $resp, $matches); //re.findall(r'('+hashpassw[5:]+r')\:(\d+)', res)
    if($matchObj)
    {
        if($PRINT){
            echo var_dump(matchObj); 
            echo "${lb}Password found ". $matches[2]." times.";
        }
        $retVal = $matches[2]; 
    }
    else{
        echo "${lb}Password has not been pwned."; 
    }

    if($PRINT){
        echo $resp; 
    }
    
    return $retVal; 
}

/* main php work */
if(isset($_POST) || isset($argv))
{
    $uPass = null;  
    $cli = false; 
    if(isset($_POST) && isset($_POST['password']))
    {
        $uPass = $_POST['password'];
        $cli = false; 
        $lb = "<br>"; //line break 
    }elseif(isset($argv) && count($argv) == 2)
    {
        $cli = true; 
        $lb = "\n";
        $uPass = $argv[1];
    }

    if($uPass != null)
    {
        $sha1PassHex = sha1($uPass); 
        echo "${lb}Password: ". $uPass;
        echo "${lb}HA1: ". $sha1PassHex;
        echo "${lb}Checking first 5 SHA1 characters ".substr($sha1PassHex, 0,5)." vs haveibeenpwnd database";
        $pwnedVal = subPwnPass($sha1PassHex, False, $lb);
        echo "${lb}Password has been pwned ".$pwnedVal." times${lb}";        
    }else
    {
        if($cli)
        {
            $this_file_name = basename(__FILE__, '.php'); 
            echo "${lb}Error: Usage -- php $this_file_name PASSWORD${lb}";
        }else
        {
            echo "${lb}Error! POST data not sent$lb";
        }

    }

}else
{
    echo "Error! POST data not sent\n OR CLI usage: php check_pass_pwn.php PASSWORD";
}

?>