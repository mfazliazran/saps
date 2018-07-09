<?php
set_time_limit(0);
session_start();
include("config.php");
$tahun1 = $_SESSION['tahun'] ;
$jpep1 = $_SESSION['jpep'];
$kodsek1 = $_SESSION['kodsek'];
$m=$_GET['data'];
list ($ting1, $kelas, $kodmp, $tahun, $kodsek, $jpep)=split('[|]', $m);
$tg=strtolower($ting1);

if ($_SESSION['statussek']=="SM"){
	$tmarkah="markah_pelajar";
	$tmurid="tmurid";
	$tahap="TING";
	$ting="TING";
	$mp='mpsmkc';
}

if ($_SESSION['statussek']=="SR"){
	$tmarkah="markah_pelajarsr";
	$tmurid="tmuridsr";
	$tahap="DARJAH";
	$ting="DARJAH";
	$mp='mpsr';
}


  $stmt1=oci_parse($conn_sispa,"select * from $tmarkah where tahun='$tahun1' and kodsek='$kodsek1' and jpep='$jpep1' and $tahap='$ting1' and kelas='$kelas'");
  oci_execute($stmt1);
  //echo "select * from $tmarkah where tahun='$tahun1' and kodsek='$kodsek1' and jpep='$jpep1' and $tahap='$ting1' and kelas='$kelas'";
  $bil=0;
  while($dt=oci_fetch_array($stmt1)){
    $nokp=$dt["NOKP"];
    $kodsek=$dt["KODSEK"];
    $tahun=$dt["TAHUN"];
    $darjah=$dt["$tahap"];
    $kelas=$dt["KELAS"];
    $jpep=$dt["JPEP"];
	//echo "kodsek:$kodsek nokp:$nokp jpep:$jpep tahun:$tahun $ting:$darjah kelas:$kelas<br>";
	if($kodmp=='PIMA' or $kodmp=='PI'){
		$cnt_murid=count_row("select nokp from $tmurid where nokp='$nokp' and kodsek$darjah='$kodsek' and tahun$darjah='$tahun' and $darjah='$darjah' and kelas$darjah='$kelas' and agama='01'");
	}else if($kodmp=='PMMA' or $kodmp=='PM'){
		$cnt_murid=count_row("select nokp from $tmurid where nokp='$nokp' and kodsek$darjah='$kodsek' and tahun$darjah='$tahun' and $darjah='$darjah' and kelas$darjah='$kelas' and agama!='01'");
	}else{
		$cnt_murid=count_row("select nokp from $tmurid where nokp='$nokp' and kodsek$darjah='$kodsek' and tahun$darjah='$tahun' and $darjah='$darjah' and kelas$darjah='$kelas'");
	}
	//echo "select nokp from $tmurid where nokp='$nokp' and kodsek$darjah='$kodsek' and tahun$darjah='$tahun' and $darjah='$darjah' and kelas$darjah='$kelas' ";
	if ($cnt_murid==0){
	  $bil++;
	  if($kodmp=='PIMA' or $kodmp=='PI' or $kodmp=='PMMA' or $kodmp=='PM'){
		  	$sql = "update $tmarkah set $kodmp=null,G$kodmp=null where nokp='$nokp' and kodsek='$kodsek' and tahun='$tahun' and $ting='$darjah' and kelas='$kelas' and jpep='$jpep'";
	  }else{
	  //echo "$bil. nokp:$nokp kodsek:$kodsek tahun:$tahun $ting:$darjah kelas:$kelas jpep:$jpep<br>";
	  		$sql="delete from $tmarkah where nokp='$nokp' and kodsek='$kodsek' and tahun='$tahun' and $ting='$darjah' and kelas='$kelas' and jpep='$jpep'";
	  }
	  //$sql="update $tmarkah set $kodmp = null where nokp='$nokp' and kodsek='$kodsek' and tahun='$tahun' and $ting='$darjah' and kelas='$kelas' and jpep='$jpep'";
      $stmt=oci_parse($conn_sispa,$sql);
oci_execute($stmt);
	}
  }
  
  $stmt1=oci_parse($conn_sispa,"select kod from $mp");
  oci_execute($stmt1);
  
  $i=0;
  $sel="";
  while($dt=oci_fetch_array($stmt1)){
    $arr_kod[$i]=$dt["KOD"];
	$sub=$arr_kod[$i];
	$i++;
	if ($i>1)
	  $sel.=",";
	$sel.="$sub,G$sub";
  }
  $stmt=oci_parse($conn_sispa,"select kodsek,nokp,jpep,tahun,$tahap,kelas from $tmarkah where tahun='$tahun1' and kodsek='$kodsek1' and jpep='$jpep1' and $tahap='$ting1' and kelas='$kelas' group by kodsek,nokp,jpep,tahun,$tahap,kelas having count(*) > 1");
  oci_execute($stmt);		 					   
  while($data=oci_fetch_array($stmt)){
 	$nokp=$data["NOKP"];
    $kodsek=$data["KODSEK"];
    $tahun=$data["TAHUN"];
    $darjah=$data["$tahap"];
    $kelas=$data["KELAS"];
    $jpep=$data["JPEP"];
	//echo "kodsek:$kodsek nokp:$nokp jpep:$jpep tahun:$tahun $ting:$darjah kelas:$kelas<br>";
	$rowcnt=0;
   $subj=oci_parse($conn_sispa,"select rowidtochar(rowid) as r_id,$sel from $tmarkah
                               where kodsek='$kodsek' and nokp='$nokp' and jpep='$jpep' 
							   and tahun='$tahun' and $ting='$darjah' and kelas='$kelas'");
	oci_execute($subj);
    while($data_subj=oci_fetch_array($subj)){
	  $rowcnt++;
	  $rowid=$data_subj["R_ID"];
	  if ($rowcnt==1)
	    $rowid_first=$rowid;
      for($idx=0;$idx<count($arr_kod);$idx++){
		 $kodsubj=$arr_kod[$idx];
		 $markah=$data_subj["$kodsubj"];
		 $gred=$data_subj["G$kodsubj"];
		 if ($markah<>""){		 
		   //echo "row:$rowcnt $idx. rowid:$rowid kodsubjek=$kodsubj markah=$markah gred=$gred<br>";	   
		   if ($rowcnt>1){
			 $sql_upd=oci_parse($conn_sispa,"update $tmarkah set $kodsubj='$markah',G$kodsubj='$gred' where rowid='$rowid_first'");
			 //echo "update $tmarkah set $kodsubj='$markah',G$kodsubj='$gred' where rowid='$rowid_first'<br>";
			 oci_execute($sql_upd);
		   }
	     }//$markah<>""  
	   } //for($idx...
	 if ($rowcnt>1){  
		 $sql_del=oci_parse($conn_sispa,"delete from $tmarkah where rowid='$rowid'");
		  oci_execute($sql_del);
		 //echo "delete from $tmarkah where rowid='$rowid'<br>";
     }
	 }//while($data_subj..

  }
header('Location: papar_subjek.php');
?>

