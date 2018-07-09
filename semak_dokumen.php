<?php
$base = __DIR__ ."/";
$p=$_GET["p"];
if ($p=="")
  $p="$base";
$filename=$_GET["f"];
$dir=$_GET["d"];
$download=0;
if ($filename<>""){
    $download=1;
    if ($pos=strrpos($filename,"|"))
	  $fname=substr($filename,$pos+1);
	else
      $fname=$filename;	
    $filename=str_ireplace("|","/",$filename);
	//$filename=str_ireplace("../","",$filename);
	//$fullpath="doc/dokumen/".$filename;  
	$fullpath=$base.$filename;  
    //die($fullpath);
	session_cache_limiter("public, post-check=50");
	header("Cache-Control: private");
	header("Content-Type: application/zip");
	header("Content-Length: ".filesize($fullpath));
	header("Content-Disposition: attachment; filename=$fname");
	readfile($fullpath);
}
else if ($dir<>""){
    $dir=str_ireplace("|","/",$dir);
	$fullpath=$base.$dir;  
	$p=$fullpath."/";
}
if ($download==0){
$blocksdir = scandir($p);

	
?>
<?
$hlcolor="#EAEAEA";
$ncolor="#FFF";
$opentable.="<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
$opentable.="<tr><td bgcolor=\"#FFFFFF\">No</td>";
$opentable.="<td bgcolor=\"#FFFFFF\">Nama File</td>";
$opentable.="<td bgcolor=\"#FFFFFF\">Capai</td></tr>\n"; 	
$closetable.="</table>";		    

$foldersaja=$opentable;
$filesaja=$opentable;

$fileNames = array();
$folderNames= array();

foreach ($blocksdir as $func) {
	    if($func!= ".." and $func!=".") {
			$cnt++;
	          $fname=$func;
			  	if (is_dir($p.$fname)) {
					 $folderNames[]=$fname;
			   	}
			   	else {
					  $fileNames[]=$fname;
			   	}
			  
	    }
	 }


echo "BASEDIR:$p<br><br>";

echo "<table border=\"0\" width=\"100%\" >";
echo "<tr>";
echo "<td valign=\"top\">";
//MULA FOLDER
echo "<table border=\"0\" bgcolor=\"#000000\"  cellspacing=\"0\" cellpadding=\"0\"><tr><td>";

echo $opentable;
sort($folderNames);
$cntfolder=0;

foreach($folderNames as $file)
{
   $fname=$file;
   $cntfolder++;
   if ($dir=="")
	  $pass=$file;
    else
	  $pass="$dir|$file";		   
   echo "<tr bgcolor=\"$ncolor\" onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$ncolor'\">"; 
   echo "<td>$cntfolder.</td>";
   echo "<td>$file</td>";
   if (is_dir($p.$fname)) {
	  echo "<td align=\"center\"><a href='semak_dokumen.php?d=$pass'><img src=\"../../images/folder.gif\"></a></td>"; 
   }
   else {
	  echo "<td  align=\"center\"><a href='semak_dokumen.php?f=$pass'><img src=\"../../images/floppy.gif\"></a></td>"; 
   }
   echo "</tr>";
}
echo $closetable;
//TAMAT FOLDER
echo "</td></tr></table>";
echo "</td>";
echo "<td valign=\"top\">";
//MULA FILES
echo "<table border=\"0\" bgcolor=\"#000000\"  cellspacing=\"0\" cellpadding=\"0\"><tr><td>";
echo $opentable;
sort($fileNames);
$cntfolder=0;

foreach($fileNames as $file)
{
   $fname=$file;
   if ($dir=="")
	  $pass=$file;
    else
	  $pass="$dir|$file";		   
   $cntfolder++;
   echo "<tr bgcolor=\"$ncolor\" onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$ncolor'\">"; 
   echo "<td>$cntfolder.</td>";
   echo "<td>$file</td>";
   if (is_dir($p.$fname)) {
	  echo "<td align=\"center\"><a href='semak_dokumen.php?d=$pass'><img src=\"../../images/folder.gif\"></a></td>"; 
   }
   else {
	  echo "<td  align=\"center\"><a href='semak_dokumen.php?f=$pass'><img src=\"../../images/floppy.gif\"></a></td>"; 
   }
   echo "</tr>";
}
echo $closetable;
//TAMAT FILES
echo "</td></tr></table>";

echo "</td>";
echo "</tr>";
echo "</table>";

		

		
}
?>
