<?php 
//CA16111204
//session_start();
if(!isset($_SESSION)){session_start();}

include_once('config.php');

//$sql="SELECT * FROM login WHERE user1='".$_SESSION['SESS_MEMBER_ID']."'";
/*$sql="SELECT KODSEK,LEVEL1,STATUSSEK FROM login WHERE user1='".$_SESSION['SESS_MEMBER_ID']."' and pswd='".$_SESSION['SESS_PASSWORD']."'";

$stmt=OCIParse($conn_sispa,$sql);
OCIExecute($stmt);
//$bil=count_row($sql); 
if(OCIFetch($stmt)){
	$kodsek = OCIResult($stmt,"KODSEK");
	$level = OCIResult($stmt,"LEVEL1");
	$jsek = OCIResult($stmt,"STATUSSEK");
}
OCIFreeStatement($stmt);*/
$tahun=$_SESSION["tahun"];

if(($_SESSION['level']=="1") OR ($_SESSION['level']=="2") OR ($_SESSION['level']=="3") OR ($_SESSION['level']=="4") OR ($_SESSION['level']=="P")){
if ($jsek=="SR"){
  $sql="select * from tmuridsr where (KodSekD1='$kodsek' and TahunD1='$tahun') or (KodSekD2='$kodsek' and TahunD2='$tahun') or (KodSekD3='$kodsek' and TahunD3='$tahun')
     or (KodSekD4='$kodsek' and TahunD4='$tahun') or (KodSekD5='$kodsek' and TahunD5='$tahun') or (KodSekD6='$kodsek' and TahunD6='$tahun')";
  $cnt_murid=count_row($sql);
}
else {
  $sql="select * from tmurid where (KodSekP='$kodsek' and TahunP='$tahun') or (KodSekT1='$kodsek' and TahunT1='$tahun') 
      or (KodSekT2='$kodsek' and TahunT2='$tahun') or (KodSekT3='$kodsek' and TahunT3='$tahun')
     or (KodSekT4='$kodsek' and TahunT4='$tahun') or (KodSekT5='$kodsek' and TahunT5='$tahun') 
	 or (KodSekT6='$kodsek' and TahunT6='$tahun')";
  $cnt_murid=count_row($sql);
}
//echo $sql;
  //$cnt_guru=count_row("select * from login where KodSek='$kodsek'");
  
  ?>
<div id="newsHeader"><?php echo $kodsek; ?></div>
<div id="sideBarNewsContent">
<div align="center">
<?php 
  echo "<font color =\"#0000ff\"><b>MURID : ".$cnt_murid."</b></font><br><br>"; 
  //echo "<font color =\"#0000ff\"><b>GURU : ".$cnt_guru."</b></font>"; 
 ?>
</div> 
</div><br>

<?php  
}

?>