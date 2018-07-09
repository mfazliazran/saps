<?php
ini_set('display_errors', '1');
session_start();
$key = $_GET["key"];
if($key=='1' or $key=='2')
	$path="export/"; //linux
else if($key=='3')
	$path="export_summ/"; //summary
else
	$path="export_linus/"; //linux
$file = $_GET["file"];
// Create file path
if($key=='1')//export markah pelajar
	$filepath = $path . $_SESSION["kodsek"] ."/". $file;
elseif($key=='2')//export data etr
	$filepath = $path . $_SESSION["kodsek"] ."/data_etr/". $file;
else
	$filepath = $path . $_SESSION["kodsek"] ."/". $file;
//die ("filename:".$filepath);

if (!file_exists($filepath)){
?>
<script language="JavaScript">
  alert("Fail tidak wujud !");
  history.go(-1);
</script>
<?php
 die;
} //if !file_exists
// Now check if there isn't any funny business going on
//if ($filepath != realpath($filepath)) {
//	die('Security error! Please go back and try again.');
//}

// Get file extension
$ext = explode('.', $file);
$extension = $ext[count($ext)-1];

// Try and find appropriate type
switch(strtolower($extension)) {
	case 'txt': $type = 'text/plain'; break;
	case "pdf": $type = 'application/pdf'; break;
	case "exe": $type = 'application/octet-stream'; break;
	case "zip": $type = 'application/zip'; break;
	case "doc": $type = 'application/msword'; break;
	case "xls": $type = 'application/vnd.ms-excel'; break;
	case "ppt": $type = 'application/vnd.ms-powerpoint'; break;
	case "gif": $type = 'image/gif'; break;
	case "png": $type = 'image/png'; break;
	case "jpg": $type = 'image/jpg'; break;
	case "jpeg": $type = 'image/jpg'; break;
	case "bmp": $type = 'image/bmp'; break;
	case "html": $type = 'text/html'; break;
	default: $type = 'application/force-download';
}

// General download headers:
header("Pragma: public"); // required
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); // required for certain browsers 
header("Content-Transfer-Encoding: binary");

// Filetype header
header("Content-Type: " . $type);

// Filesize header
header("Content-Length: " . filesize($filepath));

// Filename header
header("Content-Disposition: attachment; filename=\"" . $file . "\";" );

// Send file data
readfile($filepath);
//sql_query("update contents set counter=counter+1 where id=$id",$dbi);
?>