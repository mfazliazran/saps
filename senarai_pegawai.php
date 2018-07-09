<?php 
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
//echo "..";
if ($_SESSION['level']<>"3" and $_SESSION['level']<>"4" and $_SESSION['level']<>"5" and $_SESSION['level']<>"6" and $_SESSION['level']<>"7")
	die('Anda bukan level SU Peperiksaan, PPD/JPN atau PUSAT');
?>
<script type="text/javascript" src="ajax/ajax_pegawai.js"></script>
<script language="javascript" type="text/javascript">
function ucase(e,obj) {
tecla = (document.all) ? e.keyCode : e.which;
//alert(tecla);
if (tecla!="8" && tecla!="0"){
obj.value += String.fromCharCode(tecla).toUpperCase();
return false;
}else{
return true;
}
}
</script>
<td valign="top" class="rightColumn">
  <p class="subHeader">Maklumat Pegawai Teknikal SAPS</p>
<?php 

	if ($_POST["post"]=="1"){
		$jpn_cari=$_POST["txtKodJPN"];
		$ppd_cari=$_POST["txtKodPPD"];
		
		$_SESSION["jpn_cari"]=$jpn_cari;
		$_SESSION["ppd_cari"]=$ppd_cari;
	}
	else {
		$jpn_cari=$_SESSION["jpn_cari"];
		$ppd_cari=$_SESSION["ppd_cari"];
	}
	
	if($_GET["hapus"]=="1"){
	  $sqldel="delete from pegawai_saps where id='".$_GET["id"]."'";
	  $resdelete = oci_parse($conn_sispa,$sqldel);
	  oci_execute($resdelete);
	  pageredirect("senarai_pegawai.php"); 
	}
	
	
 ?>
 <title>Sistem Analisis Peperiksaan Sekolah - KPM</title>
<form name="frm1" method="post" action="">
<TABLE>
<tr><td colspan="3">Carian Pegawai Teknikal SAPS</td></tr>
<tr><td>JPN</td><td>:</td><td>
<?php if ($_SESSION["level"]=="7"){ ?>
<select name="txtKodJPN" id="txtKodJPN" onChange="papar_ppd(this.value);">
<option value="">-Pilih-</option>
<?php
  $result_jpn = oci_parse($conn_sispa,"select kodnegeri,negeri from tknegeri order by negeri");
  oci_execute($result_jpn);
  while($data_jpn=oci_fetch_array($result_jpn)){
	  $tkodnegeri=$data_jpn["KODNEGERI"];
	  $tnegeri=$data_jpn["NEGERI"];
	  if($tkodnegeri==$jpn_cari)
	     echo "<option selected value=\"$tkodnegeri\">$tnegeri</option>";
      else
	     echo "<option value=\"$tkodnegeri\">$tnegeri</option>";
  }
  
?>
</select>
<?php } else { 
		$jpn_cari=$_SESSION["kodnegeri"];
		$kodnegeri=$jpn_cari;
		if($jpn_cari==""){
			$ressek=oci_parse($conn_sispa,"select kodnegerijpn from tsekolah where kodsek='".$_SESSION["kodsek"]."'");
			oci_execute($ressek);
			$datasek=oci_fetch_array($ressek);
			$jpn_cari=$datasek["KODNEGERIJPN"];
		}
		$resnegeri=oci_parse($conn_sispa,"select negeri from tknegeri where kodnegeri='$jpn_cari'");
		oci_execute($resnegeri);
		$datanegeri=oci_fetch_array($resnegeri);
		$namajpn=$datanegeri["NEGERI"];
        echo "<b>$namajpn</b>";
		echo "<input type=\"hidden\" name=\"txtKodJPN\" value=\"$jpn_cari\">";

}
?>


</td></tr>
<tr><td>PPD</td><td>:</td><td><div id="divPPD">
<?php if ($_SESSION["level"]=="6" or $_SESSION["level"]=="7"){ ?>

<select name="txtKodPPD" id="txtKodPPD">
<option value="">-Pilih-</option>
<?php
  $result_jpn = oci_parse($conn_sispa,"select kodppd,ppd from tkppd where kodnegeri='$jpn_cari'");
  oci_execute($result_jpn);
  while($data_jpn=oci_fetch_array($result_jpn)){
	  $kodppd=$data_jpn["KODPPD"];
	  $ppd=$data_jpn["PPD"];
	  if($kodppd==$ppd_cari)
	     echo "<option selected value=\"$kodppd\">$kodppd - $ppd</option>";
      else
	     echo "<option value=\"$kodppd\">$kodppd - $ppd</option>";
  }
  
?>
</select>
<?php } else { 
	if($_SESSION["level"]=="3" or $_SESSION["level"]=="4"){
		$ressek=oci_parse($conn_sispa,"select kodppd from tsekolah where kodsek='".$_SESSION["kodsek"]."'");
		oci_execute($ressek);
		$datasek=oci_fetch_array($ressek);
		$ppd_cari=$datasek["KODPPD"];
	}
	else {
	  $ppd_cari=$_SESSION["kodsek"];
	}
	//echo "select kodnegeri,ppd from tkppd where kodppd='$ppd_cari'";
	$resppd=oci_parse($conn_sispa,"select kodnegeri,ppd from tkppd where kodppd='$ppd_cari'");
	oci_execute($resppd);
	$datappd=oci_fetch_array($resppd);
	$kodnegeri=$datappd["KODNEGERI"];
	$namappd=$datappd["PPD"];
	echo "<b>$namappd</b>";
	echo "<input type=\"hidden\" name=\"txtKodJPN\" value=\"$ppd_cari\">";
}
//echo "kodndgeri:$kodnegeri ppd_cari:$ppd_cari<br>";
//echo "level:".$_SESSION["level"];
?>
</div></td></tr> 


  </select></td></tr>
<?php 
if ($_SESSION["level"]=="6" or $_SESSION["level"]=="7"){?>  
<tr><td colspan="3">
<input type="submit" value="Cari" name="submit" />
<input type="hidden" value="1" name="post" />

</td></tr>
<?php } ?>
</TABLE>
<?php
echo "<br>";
echo "<center><h3>SENARAI PEGAWAI TEKNIKAL SAPS</center></h3><br>";

//echo($_SESSION['kodsek']);

echo "<table width=\"90%\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
if($level=="7"){
   echo "<tr><td colspan=\"7\"><input type=\"button\" name=\"Tambah\" value=\"Tambah\" onclick=\"location.href='b_tambah_pegawai.php';\"></td></tr>";
}
//echo "  <tr><td colspan=\"9\"><img src=\"images/add.gif\">&nbsp;<a href=\"tambah-user.php\"><b>Tambah Pengguna</b></a></td>\n";
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "    <td><div align=\"center\">Bil</div></td>\n";
echo "    <td><div align=\"center\">JPN/Bahagian</div></td>\n";
echo "    <td><div align=\"center\">PPD</div></td>\n";
echo "    <td><div align=\"center\">Nama Pegawai</div></td>\n";
echo "    <td><div align=\"center\">Sektor/Unit</div></td>\n";
echo "    <td><div align=\"center\">Emel</div></td>\n";
echo "    <td><div align=\"center\">No. Telefon Meja</div></td>\n";
if($level=="7"){
   echo "    <td>Tindakan</td>\n";
}
echo "  </tr>\n";
$pg=(int) $_GET["pg"];
if ($pg==0)
 $pg=1;

$recordpage=30;
$startrec=($pg-1)*$recordpage+1;
$endrec=($startrec+$recordpage)-1;  
$rowstart=($pg-1)*30;
//echo $_SESSION["kodsek2"];
$count_query_sm = " select tknegeri.negeri,pegawai_saps.ppd as kodppd,nama_pegawai,nvl(tkppd.ppd,'JPN') as namappd,sektor_unit,emel,notelefon,pegawai_saps.id from pegawai_saps 
			left join tknegeri on pegawai_saps.jpn=tknegeri.kodnegeri
			left join tkppd on pegawai_saps.ppd=tkppd.kodppd "; 

$query_sm = " select tknegeri.negeri,pegawai_saps.ppd as kodppd,nama_pegawai,nvl(tkppd.ppd,'JPN') as namappd,sektor_unit,emel,notelefon,pegawai_saps.id from pegawai_saps 
			left join tknegeri on pegawai_saps.jpn=tknegeri.kodnegeri  
			left join tkppd on pegawai_saps.ppd=tkppd.kodppd  "; 


$c=" where ";
if ($jpn_cari<>""){
  $query_sm.=" $c pegawai_saps.jpn ='$jpn_cari' ";
  $count_query_sm.=" $c pegawai_saps.jpn ='$jpn_cari' ";
  $c=" and ";
}
//echo "level:".$_SESSION["level"]."<br>";
if($_SESSION["level"]!="7" and $_SESSION["level"]!="6"){
  $query_sm.=" $c pegawai_saps.jpn='$kodnegeri' ";
  $count_query_sm.=" $c pegawai_saps.jpn='$kodnegeri' ";
  $c=" and ";
}

if ($ppd_cari<>""){
  if($_SESSION["level"]=="3" or $_SESSION["level"]=="3"){
	  $query_sm.=" $c pegawai_saps.ppd in ('$ppd_cari') ";
	  $count_query_sm.=" $c pegawai_saps.ppd in ('$ppd_cari') ";
  }
  else {
	  $query_sm.=" $c pegawai_saps.ppd in ('$ppd_cari','JPN') ";
	  $count_query_sm.=" $c pegawai_saps.ppd in ('$ppd_cari','JPN') ";
  }
  $c=" and ";
}

$query_sm.=" order by 1,4,3 ";
//echo $query_sm;
  $totalrecord=count_row($count_query_sm);

  $qrystr2="select * from ( select a.*,rownum rnum from ($query_sm)a where rownum<=$endrec) where rnum>=$startrec";
  $result_sm = oci_parse($conn_sispa,$qrystr2);
$rowcnt=0;

oci_execute($result_sm);
	$bil=$rowstart;
	while($sm = oci_fetch_array($result_sm)){
		$id=$sm["ID"];
		$jpn=$sm["NEGERI"];
		$kodppd=trim($sm["KODPPD"]);
		//if($kodppd=="JPN"){
		//	$ppd="JPN";
		//}	
		//else {
		    $ppd=$sm["NAMAPPD"];
		//}	
		$nama_pegawai=$sm["NAMA_PEGAWAI"];
		$sektor_unit=$sm["SEKTOR_UNIT"];
		$emel=$sm["EMEL"];
		$notelefon=$sm["NOTELEFON"];
		$bil=$bil+1;
		$rowcnt++;
		echo "  <tr><td><center>$bil</center></td>\n";
		echo "  <td>$jpn</td>\n";
		echo "  <td>$ppd</td>\n";
		echo "  <td>$nama_pegawai</td>\n";
		echo "  <td>$sektor_unit</td>\n";
		echo "  <td>$emel</td>\n";
		echo "  <td>$notelefon</td>\n";
		if($level=="7"){
          echo "<td>&nbsp;<a href='b_tambah_pegawai.php?id=$id'><img src='images/edit.png' border='0' alt='Kemaskini'></a>";
          echo "&nbsp;&nbsp;<a href='senarai_pegawai.php?hapus=1&id=$id' onclick='return confirm(\"Hapuskan rekod ?\");'><img src='images/drop.png' border='0' alt='Hapus'></a></td>";
		}
		echo "  </tr>\n";
	}
if ($rowcnt==0){
  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"8\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";

}	
?>	
</form>
<tr bgcolor="#FFFFFF"><td colspan="8">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"senarai_pegawai.php?cari=1",$pg);

	
echo "</table>\n";
echo "<br>";
//}

?>