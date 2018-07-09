<?php
//$dbi=sql_connect("localhost", "root","","my_data_emisportal");
//$dbi=sql_connect("10.251.27.15", "bdasaredu","emis%5%5","my_data_emisportal");
$dbi=sql_connect("10.209.2.127", "bdasaredu","emis%5%5","my_data_emisportal");
function sql_connect($host, $user, $password, $db)
{

      $dbi=@mysql_connect($host, $user, $password);
	mysql_select_db($db);
      return $dbi;
}

function sql_logout($id)
{
        $dbi=@mysql_close($id);
        return $dbi;
}


function sql_query($query, $id)
{
     $res=mysql_query($query, $id);
     return $res;
}

function sql_num_rows($res)
{
     if ($res<>"")
       $rows=mysql_num_rows($res);
	 else
	  $rows=0;
     return $rows;
}


function sql_fetch_row(&$res)
{

    $row = mysql_fetch_row($res);
    return $row;
}

function sql_result($res,$nr,$fieldname)
{

    $row = mysql_result($res,$nr,$fieldname);
    return $row;
}

function sql_fetch_array(&$res, $nr=0)
{

        $row = array();
        $row = mysql_fetch_array($res);
        return $row;
}

function sql_fetch_object(&$res, $nr=0)
{

      $row = mysql_fetch_object($res);
	if($row) 
         return $row;
	else 
        return false;
}

function sql_free_result($res) {
        $row = mysql_free_result($res);
        return $row;
}

function fmtdate($date_in)
{
if (strlen($date_in) > 0){
  $s = substr($date_in,8,2);
  $s .= "/".substr($date_in,5,2);
  $s .= "/".substr($date_in,0,4);
}
else
  $s="";  
return($s);
}

function fmtdate_time($date_in)
{
if (strlen($date_in) > 0){
  $s = substr($date_in,8,2);
  $s .= "/".substr($date_in,5,2);
  $s .= "/".substr($date_in,0,4);
  $s .= " ".substr($date_in,11,8);
}
else
  $s="";  
return($s);
}

function mysqldate($date_in)
{
if (strlen($date_in) > 0){
  $s = substr($date_in,6,4);
  $s .= "-".substr($date_in,3,2);
  $s .= "-".substr($date_in,0,2);
}
else
  $s="";  
return($s);
}

function dateDiff($dformat, $endDate, $beginDate)
{
$date_parts1=explode($dformat, $beginDate);
$date_parts2=explode($dformat, $endDate);
$start_date=gregoriantojd($date_parts1[1], $date_parts1[0], $date_parts1[2]);
$end_date=gregoriantojd($date_parts2[1], $date_parts2[0], $date_parts2[2]);
return $end_date - $start_date;
}

function generate_ref_id($tbl,$fld,$val)
{
  global $dbi;
  $sql="select max(id) from $tbl where $fld='$val'";
  $res=sql_query($sql,$dbi);
  $data=sql_fetch_array($res);
  $max=(int) $data[0];
  $max++;
  return $max;
}

function check_privileges($role,$module,$task)
{
 global $dbi;
 global $privileges_flag;

 $res=sql_query("select view_allowed,add_allowed,edit_allowed,delete_allowed from role_privileges where role='$role' and module='$module'",$dbi);
 if ($data=sql_fetch_array($res)){
   $privileges_flag["view"]=$data["view_allowed"];
   $privileges_flag["add"]=$data["add_allowed"];
   $privileges_flag["edit"]=$data["edit_allowed"];
   $privileges_flag["delete"]=$data["delete_allowed"];
 }
 else {
   $privileges_flag["view"]=0;
   $privileges_flag["add"]=0;
   $privileges_flag["edit"]=0;
   $privileges_flag["delete"]=0;
 }

}

?>