<?php 
session_start(); 
// make a string with all the characters that we  
// want to use as the verification code 

//---$alphanum  = "abcdefghijklmnopqrstuvwxyz0123456789"; 

// generate the verication code  
//---$rand = substr(str_shuffle($alphanum), 0, 5); 
$rand=generateCode(5);
// choose one of four background images 
$bgNum = rand(1, 2); 

// create an image object using the chosen background 

//$image = imagecreatefromjpeg("images/pwd$bgNum.jpg"); 
$image = imagecreatefrompng("images/pwd$bgNum.png"); 
//echo "images/pwd$bgNum.jpg";

$textColor = imagecolorallocate ($image, 50, 50, 50);  

// write the code on the background image 
imagestring ($image, 5, 5, 8,  $rand, $textColor);  
     

// create the hash for the verification code 
// and put it in the session 
$_SESSION["verification"] = $rand; 
     
// send several headers to make sure the image is not cached     
// taken directly from the PHP Manual 
     
// Date in the past  
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  

// always modified  
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  

// HTTP/1.1  
header("Cache-Control: no-store, no-cache, must-revalidate");  
header("Cache-Control: post-check=0, pre-check=0", false);  

// HTTP/1.0  
header("Pragma: no-cache");      


// send the content type header so the image is displayed properly 
header('Content-type: image/jpeg'); 

// send the image to the browser 
imagepng($image); 

// destroy the image to free up the memory 
imagedestroy($image); 

function generateCode($length = 10)
{   
   $password="";
   $chars = "123456789";
   srand((double)microtime()*1000000);
   for ($i=0; $i<$length; $i++){
         $password = $password . substr ($chars, rand() % strlen($chars), 1);   
  }   
  return $password;
} 

?> 