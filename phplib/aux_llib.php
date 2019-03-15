<?php
#This first set of functions are useful for RESTful API 
#These functions are based on cURL. the cURL module must be enabled in php
/*Auxillary function for making GET requests
  using cURL
  public get function
    params: needs to be an associated array of parameters
    url: is the url where the request is posted
    Example: genericCurlGet(array('param1'=>'val', 'param2'=>'val2'), 'https://example.com/v2/api/)
*/
function genericCurlGet($params, $url)
{
    $postData = '';
    //create name value pairs seperated by &
    foreach($params as $k => $v) 
    { 
        $postData .= $k . '='.$v.'&'; 
    }

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

/*Auxillary function for making POST requests
  using cURL
  public get function
    params: needs to be an associated array of parameters
    url: is the url where the request is posted
    Example: genericCurlPost(array('param1'=>'val', 'param2'=>'val2'), 'https://example.com/v2/api/)
*/
function genericCurlPost($params, $url)
{

    $postData = '';
    //create name value pairs seperated by &
    foreach($params as $k => $v) 
    { 
        $postData .= $k . '='.$v.'&'; 
    }
    $postData = rtrim($postData, '&');
    
    //Initiate cURL.
    $ch = curl_init();  

    $header = array();
    $header[] = "Authorization: Bot $this->botAuth";
    $header[] = "User-Agent: myBotThing (http://s, v0.1)";
    $header[] = "Content-Type: application/json";
    
    //Encode array into JSON
    $content = json_encode($params);
    
        
    //cut the output of the header of the request
    curl_setopt($ch, CURLOPT_HEADER, false);
    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  
    //set the url of the conent
    curl_setopt($ch,CURLOPT_URL,$url);
    
    //make curl return the data instead of outputting it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    //make sure that curl doesn't print everything to screen
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    
    
    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, count($postData));
    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);    
    
    $output=curl_exec($ch);
    
    curl_close($ch);
    return $output;        
        
}

/*Auxillary function for making DELETE requests*/
protected function httpDiscordDelete($params, $url)
{
    $postData = '';
    //create name value pairs seperated by &
    foreach($params as $k => $v) 
    { 
        $postData .= $k . '='.$v.'&'; 
    }
    $postData = rtrim($postData, '&');
    
    //Initiate cURL.
    $ch = curl_init();  

    $header = array();
    $header[] = "User-Agent: myBotThing (http://s, v0.1)";
    $header[] = "Content-Type: application/json";
    
    //Encode array into JSON
    $content = json_encode($params);
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
    //cut the output of the header of the request
    curl_setopt($ch, CURLOPT_HEADER, false);
    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  
    //set the url of the conent
    curl_setopt($ch,CURLOPT_URL,$url);
    
    //make curl return the data instead of outputting it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    //make sure that curl doesn't print everything to screen
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    
    
    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, count($postData));
    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);    
    
    $output=curl_exec($ch);
    
    curl_close($ch);
    return $output;
}

/*Auxillary function for making a PATCH request*/
protected function genericPatch($params, $url)
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
    $header[] = "User-Agent: myBotThing (http://s, v0.1)";
    $header[] = "Content-Type: application/json";
    
    //Encode array into JSON
    $content = json_encode($params);
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH'); 
    //cut the output of the header of the request
    //cut the output of the header of the request
    curl_setopt($ch, CURLOPT_HEADER, false);
    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  
    //set the url of the conent
    curl_setopt($ch,CURLOPT_URL,$url);
    
    //make curl return the data instead of outputting it
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    //make sure that curl doesn't print everything to screen
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    
    
    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, count($postData));
    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content); 
      
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return $output;
   
}

?>