<?php 
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');
include 'fungsi2.php';
include 'fungsikira.php';
$tahun=$_GET["tahun"];
$kodsek=$_GET["kodsek"];

?>
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
<p class="subHeader">Carian Guru Mata Pelajaran</p>
<?php 
//echo "level:".$_SESSION["level"]." ppd:".$_SESSION["kodppd"];
if ($_SESSION["level"]=="7"){
	if ($_POST["post"]=="1"){
		$kodsek_cari=$_POST["txtKodSekolah"];
		$namasek_cari=oci_escape_string($_POST["txtNamaSekolah"]);
		$nokp_cari=$_POST["txtNoKP"];
		$nama_cari=$_POST["txtNama"];
		$jpep=$_POST['jpep'];
		$tahun=$_POST['tahun'];
		//$kodsek=$_POST['kodsek'];
		
		$_SESSION["kodsek_cari"]=$kodsek_cari;
		$_SESSION["namasek_cari"]=$namasek_cari;
		$_SESSION["nokp_cari"]=$nokp_cari;
		$_SESSION["nama_cari"]=$nama_cari;
		//$_SESSION["kodsek"]=$kodsek;
	}
	else {
		$kodsek_cari=$_SESSION["kodsek_cari"];
		$namasek_cari=$_SESSION["namasek_cari"];
		$nokp_cari=$_SESSION["nokp_cari"];
		$nama_cari=$_SESSION["nama_cari"];
	}
 ?>
<form name="frm1" method="post" action="senarai-guru-mp.php?cari=1">
<TABLE>
<tr><td colspan="3">Carian</td></tr>
<tr><td>KOD SEKOLAH</td><td>:</td><td><input type="text" name="txtKodSekolah" size="10" maxlength="7" value="<?php echo $kodsek_cari; ?>" onkeypress="return ucase(event,this);" /></td></tr>
<tr><td>NAMA SEKOLAH</td><td>:</td><td><input type="text" name="txtNamaSekolah" size="50" maxlength="50" value="<?php echo $namasek_cari; ?>" onkeypress="return ucase(event,this);"/></td></tr>
<!-- //////////////////////////////////////////////////////////////////////////////////// java script/////////////////////////////////////////////////////////////////////////////////////-->
<tr><td>TAHUN</td><td>:</td><td> 
<?php $tahun_sekarang = date("Y");?>
        
			<select name="tahun" id="tahun">
			<option value="">-- Pilih Tahun --</option>
			<?php
			for($thn = 2011; $thn <= $tahun_sekarang; $thn++ ){
				if($tahun == $thn){
					echo "<option value='$thn' selected>$thn</option>";
				} else {
					echo "<option value='$thn'>$thn</option>";
				}
			}

?>
<tr><td>JENIS PEPERIKSAAN</td><td>:</td><td>
<?php
		echo "<select name='jpep'><option value=''>Pilih Peperiksaan</option>";
		$sqljpep = "SELECT DISTINCT kod, jenis,rank FROM jpep $kodjpep ORDER BY rank";
		$SQLpep = oci_parse($conn_sispa,$sqljpep);
		oci_execute($SQLpep);
		while($rowpep = oci_fetch_array($SQLpep)) {
			if($jpep == $rowpep["KOD"])
				echo  "<option selected value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
			else
				echo  "<option value='".$rowpep["KOD"]."'>".$rowpep["JENIS"]."</option>";
		}
		echo "</select>";
		
		?>
<!--<select name="txtLevel"> -->

<?php
//////////////////// level park //////////////////////////////////////////////////////////////////////////////////////////////////////
?>
</select>
</td>
</tr>
<!--<tr><td>LOGIN</td><td>:</td><td><input type="text" name="txtLogin" size="30" maxlength="30"  value="<?php echo $login_cari; ?>"/></td></tr> -->
<tr><td>NO K/P</td><td>:</td><td><input type="text" name="txtNoKP" size="14" maxlength="12"  value="<?php echo $nokp_cari; ?>"/></td></tr>
<tr><td>NAMA</td><td>:</td><td><input type="text" name="txtNama" size="50" maxlength="50"  value="<?php echo $nama_cari; ?>"/></td></tr>
<tr><td colspan="3">
<input type="submit" value="Cari" name="submit" /><input type="hidden" value="1" name="post" />

</td></tr>
<tr><td></td><td>

</TABLE>
</form>
<?php
if ($_GET["cari"]=="1"){ 
//echo "level cari: $level_cari<br>";
echo "<center><h3>".jpep($jpep)." ".tahap($ting)." <br>TAHUN $tahun </h3></center><br>";
//echo "<center><h3></center></h3><br>";
echo "<table width=\"900\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
if ($_SESSION["level"]=="7"){
	//echo "  <tr><td colspan=\"15\"><img src=\"images/add.gif\">&nbsp;<a href=\"tambah-user.php\"><b>Tambah Pengguna</b></a></td>\n";
//} else if ($_SESSION["level"]=="6"){ // jpn
	//echo "  <tr><td colspan=\"15\"><img src=\"images/add.gif\">&nbsp;<a href=\"tambah-user-jpn.php\"><b>Tambah Pengguna (level JPN SAHAJA)</b></a></td>\n";
//} else if ($_SESSION["level"]=="5"){ // ppd
	//echo "  <tr><td colspan=\"15\"><img src=\"images/add.gif\">&nbsp;<a href=\"tambah-user-ppd.php\"><b>Tambah Pengguna (level PPD SAHAJA)</b></a></td>\n";
}
 
echo "  <tr bgcolor=\"#CCCCCC\">\n";
echo "    <td><div align=\"center\"><b>Bil</b></div></td>\n";
echo "    <td><div align=\"center\"><b>Nama Sekolah</b></div></td>\n";
echo "    <td><div align=\"center\"><b>No KP</b></div></td>\n";
echo "    <td><div align=\"center\"><b>Nama</b></div></td>\n";
echo "    <td><div align=\"center\"><b>Kelas</b></div></td>\n";
echo "    <td><div align=\"center\"><b>Mata Pelajaran</b></div></td>\n";
echo "    <td><div align=\"center\"><b>GPMP Tingkatan</b></div></td>\n";
echo "    <td><div align=\"center\"><b>GPMP Kelas</b></div></td>\n";
echo "    <td><div align=\"center\"><b>Bil Ambil Mata Pelajaran</b></div></td>\n";
if ($_SESSION["level"]=="7" ){
//echo "    <td><b>Edit</b></td>\n";
}
//echo "    <td>Reset</td>\n";
echo "  </tr>\n";
$pg=(int) $_GET["pg"];
if ($pg==0)
 $pg=1;

$recordpage=30;
$startrec=($pg-1)*$recordpage+1;
$endrec=($startrec+$recordpage)-1;  
$rowstart=($pg-1)*30;
$query_sm = "SELECT Nokp,Login.KodSek,login.NamaSek,User1,pswd,nama,keycode,level1,Statussek FROM login,tsekolah 
             where login.kodsek=tsekolah.kodsek";
if ($_SESSION["level"]=="3" or $_SESSION["level"]=="4" or $_SESSION["level"]=="P") //SU PEPERIKSAAN dan Pengetua
  $query_sm.=" and login.KodSek='".$_SESSION["kodsek"]."' ";
else if ($_SESSION["level"]=="5") //PPD
  $query_sm.=" and KodPPD='".$_SESSION["kodsek"]."' ";
else if ($_SESSION["level"]=="6") {//jpn
  $query_sm.=" and tsekolah.KodNegeriJPN='".$_SESSION["kodsek"]."' ";
}

if ($level_cari=="3/4" ) //SU PEPERIKSAAN
  $query_sm.=" and Level1 in ('3','4') ";
else if ($level_cari=="5") {//PPD
 $query_sm = "SELECT Nokp,Login.KodSek,login.NamaSek,User1,pswd,nama,level1 FROM login,tkppd ";
 $query_sm.=" where  Level1='5' and login.kodsek=tkppd.kodppd ";
 if ($_SESSION["level"]=="5")			 
   $query_sm.=" and KodSek='".$_SESSION["kodsek"]."' ";
 else if ($_SESSION["level"]=="6") // JPN
   $query_sm.=" and login.KodNegeri='".$_SESSION["kodnegeri"]."' ";
 }
else if ($level_cari=="6") {//JPN
 $query_sm = "SELECT Nokp,Login.KodSek,login.NamaSek,User1,pswd,nama,level1 FROM login";
 $query_sm.=" where  Level1='6'";

 if ($_SESSION["level"]<>"7")			 
   $query_sm.=" and KodNegeri='".$_SESSION["kodsek"]."' ";
 }
else if ($level_cari<>"" )
  $query_sm.=" and Level1='$level_cari' ";

if ($kodsek_cari<>"")
  $query_sm.=" and login.Kodsek='$kodsek_cari' ";

if ($namasek_cari<>"")
  $query_sm.=" and login.NamaSek like '%$namasek_cari%' ";

if ($login_cari<>"")
  $query_sm.=" and User1 like '%$login_cari%' ";
if ($nokp_cari<>"")
  $query_sm.=" and nokp like '%$nokp_cari%' ";
if ($nama_cari<>"")
  $query_sm.=" and Nama like '%$nama_cari%' ";
$query_sm.=" order by nama";

  $totalrecord=count_row($query_sm);
  $qrystr2="select * from ( select a.*,rownum rnum from ($query_sm)a where rownum<=$endrec) where rnum>=$startrec";
  $result_sm = oci_parse($conn_sispa,$qrystr2);
$rowcnt=0;
	
oci_execute($result_sm);
	$bil=$rowstart;
	while($sm = oci_fetch_array($result_sm)){
		$bil=$bil+1;
		$rowcnt++;
		$nokp=$sm["NOKP"];
		echo "  <tr bgcolor=\"#EEDD82\"><td><center>$bil</center></td>\n";
		echo "  <td>(".$sm["KODSEK"].") ".$sm["NAMASEK"]."</td>\n";
		echo "  <td>$nokp</td>\n";
		echo "  <td>".$sm["NAMA"]."</td>";
		if($sm["LEVEL1"]=="2" or $sm["LEVEL1"]=="4"){
       $result_kelas = oci_parse($conn_sispa,"select ting,kelas from tguru_kelas where nokp='$nokp' and tahun='$tahun'");
	   oci_execute($result_kelas);
		   if($rowkelas = oci_fetch_array($result_kelas)){
			 $ting1=$rowkelas["TING"];
			 $namakelas1=$rowkelas["KELAS"];
		   } else {
			 $ting1='';
			 $namakelas1='';
		   }
		  echo "<td>$ting1 $namakelas1</td>";
		}
		else echo "<td></td>";
       $result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp from sub_guru where nokp='$nokp' and tahun='$tahun'");
       oci_execute($result_mp);
	   echo "<td>";
		while($rowmp = oci_fetch_array($result_mp)){
			 $mp1=$rowmp["KODMP"];
			 $ting1=$rowmp["TING"];
			 $kelas1=$rowmp["KELAS"];
		  echo "$ting1/$kelas1/<font color=\"#0033FF\">$mp1</font><br>";
		}  
		echo "</td>";  
///////////////////////////////////////////////////////////////////////////// switch////////////////////////////////////////////////////////////////////////////////////////////////////////////		
	
	switch ($sm["STATUSSEK"])
	{
		case "SM" : 
		$tmarkah = "markah_pelajar"; 
		$tahap = "ting"; 
		break;
		
		case "SR" : 
		$tmarkah = "markah_pelajarsr";
		 $tahap = "darjah"; 
		break;
    }
		
{		
	$ting = $_GET['ting'];
	
	$q_sek = OCIParse($conn_sispa,"SELECT * FROM tsekolah WHERE kodsek='".$sm["KODSEK"]."'");
	//echo "SELECT * FROM tsekolah WHERE kodsek='".$sm["KODSEK"]."' ";
} 

		
switch ($jpep)
{
	case "UPSRC" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='".$sm["KODSEK"]."' AND ting='D6' AND nokp='$nokp' AND tahun='$tahun' ORDER BY ting";
		break;

	case "PAT" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='".$sm["KODSEK"]."' AND ting!='T3' AND ting!='T5' AND tahun='$tahun' ORDER BY ting";
		break;
		
	case "SPMC" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='".$sm["KODSEK"]."' AND ting='T5' AND tahun='$tahun' ORDER BY kodmp";
		break;
		
	case  "PMRC" :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='".$sm["KODSEK"]."' AND ting='T3' AND tahun='$tahun' ORDER BY kodmp";
		break;
		
	default :
		$querysub = "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='".$sm["KODSEK"]."' AND tahun='$tahun' ORDER BY kodmp";
		//echo  "SELECT DISTINCT kodmp, ting, kelas, bilammp FROM sub_guru WHERE nokp='$nokp' AND kodsek='".$sm["KODSEK"]."' AND tahun='$tahun' ORDER BY kodmp"."<br>";
		break;
}
//echo $querysub."<br>";
$num = count_row($querysub);
if($num == 0)
{
}
else{
		//echo $querysub."<br>";
		$stmt = oci_parse($conn_sispa,$querysub);
		oci_execute($stmt);
		//echo $stmt;
		while ($data=oci_fetch_array($stmt))
		{
			$ting = trim($data["TING"]);//$row['ting'];
			$kelas = $data["KELAS"];//$row['kelas'];
			$kodmp = $data["KODMP"];//$row['kodmp'];
			$bil_ambil = $data["BILAMMP"];//$row['bilammp'];
			
		switch ($ting)
	{
		case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
			$analisis = "analisis_mpsr";
			$mpp = "mpsr";
			$gpmp = 1;
			$gp = 1;
			break;
			
		case "P": case "T1": case "T2": case "T3":
			$analisis = "analisis_mpmr";
			$mpp = "mpsmkc";
			$gpmp = 2;
			$gp = 2;
			break;
			
		case "T4": case "T5":
			$analisis = "analisis_mpma";
			$mpp = "mpsmkc";
			$gpmp = 3;
			$gp = 3;
			break;
			
	}	
	
		}

}
$qbilmark = "SELECT * FROM $tmarkah WHERE $tahap='$ting' AND kelas='$kelas' AND tahun='$tahun' AND kodsek='".$sm["KODSEK"]."' AND jpep='$jpep' AND $kodmp is not null";
//echo $qbilmark."<br>";
//////////////////////////////////////////////////////////////////////////// gpmp ting//////////////////////////////////////////////////////////////////////////////////////////////////////////		
if ($gpmp == '1')	
{//////////////////////////////////////////////////////////////////////////////////sekolah rendah//////////////////////////////////////////////////////////////////////////////////
$q_mp ="SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kodmp='$kodmp' ";

					 //echo("$q_mp")."<br>";
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
					 
$bilmp = count_row("SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kodmp='$kodmp' ");

       $result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp from sub_guru where nokp='$nokp' and tahun='$tahun'");
       oci_execute($result_mp);
	   //echo "<td>";
		while($rowmp = oci_fetch_array($result_mp)){
			
			 $ting1=$rowmp["TING"];
			 $kelas1=$rowmp["KELAS"];
		}
//echo " <td>$gps</td>\n";
		//echo "    <td><center>haha".gpmpmrsr($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
echo "<td><b>$ting1</b>/ <font color=\"#0033CC\"><b>".gpmpmrsr(BILA,BILB,BILC,BILD,BILE,AMBIL)."</b></font></td>";
}

elseif  ($gpmp == '2')//////////////////////////////////////////////////////////////menegah rendah//////////////////////////////////////////////////////////////////////////////
{
$q_mp = "SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kodmp='$kodmp' ";

					 //echo("$q_mp");
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
					 
$bilmp = count_row("SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND amp.ting='$ting' AND amp.kodmp='$kodmp' ");
					 
       $result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp from sub_guru where nokp='$nokp' and tahun='$tahun'");
       oci_execute($result_mp);
	   //echo "<td>";
		while($rowmp = oci_fetch_array($result_mp)){
			
			 $ting1=$rowmp["TING"];
			 $kelas1=$rowmp["KELAS"];
		}
//echo " <td>$gps</td>\n";
		//echo "    <td><center>haha".gpmpmrsr($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
echo "<td><b>$ting1</b>/ <font color=\"#0033CC\"><b>".gpmpmrsr($rowmp[BILA], $rowmp[BILB], $rowmp[BILC], $rowmp[BILD], $rowmp[BILE], $rowmp[AMBIL])."</b></font></td>";
}
else
{////////////////////////////////////////////////////////////////////////////////// menengah atas //////////////////////////////////////////////////////////////////////////////////
$q_mp ="SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kodmp='$kodmp' ";

					 //echo("$q_mp");
					 $qry = oci_parse($conn_sispa,$q_mp);
					 oci_execute($qry);
					 
$bilmp = count_row("SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kodmp='$kodmp' ");
					 
//echo " <td>$gps</td>\n";
		//echo "    <td><center>haha".gpmpmrsr($rowmp["BILA"], $rowmp["BILB"], $rowmp["BILC"], $rowmp["BILD"], $rowmp["BILE"], $rowmp["AMBIL"])."</center></td>\n";
echo "<td><b>$ting1</b>/ <font color=\"#0033CC\"><b>".gpmpma(BILAP,BILA,BILAM,BILBP,BILB,BILCP,BILC,BILD,BILE,BILG,AMBIL)."</b></font></td>";
} 
//echo "  <td>gpmp ting</td>\n";
	
//////////////////////////////////////////////////////////////////////////////////gpmp ting////////////////////////////////////////////////////////////////////////////////////////////////////	
       //$result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp from sub_guru where nokp='$nokp' and tahun='$tahun'");
       //oci_execute($result_mp);
	   //echo "<td>";
		//while($rowmp = oci_fetch_array($result_mp)){
			
			// $ting1=$rowmp["TING"];
			// $kelas1=$rowmp["KELAS"];
		    //echo "$ting1/$kelas1/<br>";
		 //echo "SELECT SUM(bcalon) bcalon, SUM(ambil) ambil, SUM(TH) th, SUM(A) bila, SUM(B) bilb, SUM(C) bilc, SUM(D) bild, SUM(E) bile FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.ting='$ting1' AND amp.kodmp='$kodmp' "."<br>";
		//}  
		//echo "</td>"; 
	
/////////////////////////////////////////////////////////////////////////gpmp kelas////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($gp == '1') 
{/////////////////////////////////////////////////////////////////////////sekolah rendah/////////////////////////////////////////////////////////////////////////
$q_mp = "SELECT * FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp'";
$qry = oci_parse($conn_sispa,$q_mp);
oci_execute($qry);
$bilmp = count_row("SELECT * FROM analisis_mpsr amp, mpsr mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.darjah='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp'");
//echo "$bilmp ";
 $result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp from sub_guru where nokp='$nokp' and tahun='$tahun' ");
       oci_execute($result_mp);
	   //echo "<td>";
		while($rowmp = oci_fetch_array($result_mp)){
			
			 $ting1=$rowmp["TING"];
			 $kelas1=$rowmp["KELAS"];
		 // echo "$ting1/$kelas1/<br>";
		}  
//echo $q_mp;
	{

}
echo "<td>$ting1/<b>$kelas1</b>/<font color=\"#0033CC\"><b>".gpmpmrsr(A,B,C,D,E,AMBIL)."</b></font></td>";
}

elseif  ($gp == '2'){///////////////////////////////////////////////////////////////////////// menengah rendah/////////////////////////////////////////////////////////////////////////
$q_mp = "SELECT * FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp' ";
$qry = oci_parse($conn_sispa,$q_mp);
oci_execute($qry);
$bilmp = count_row("SELECT * FROM analisis_mpmr amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp' ");
//echo "$bilmp ";
//echo $q_mp."<br>";
 $result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp from sub_guru where nokp='$nokp' and tahun='$tahun' ");
       oci_execute($result_mp);
	   //echo "<td>";
		while($rowmp = oci_fetch_array($result_mp)){
			
			 $ting1=$rowmp["TING"];
			 $kelas1=$rowmp["KELAS"];
		 // echo "$ting1/$kelas1/<br>";
		}  
	{
}
echo "<td>$ting1/<b>$kelas1</b>/<font color=\"#0033CC\"><b>".gpmpmrsr(A,B,C,D,E,AMBIL)."</b></font></td>";
}
else
{///////////////////////////////////////////////////////////////////////// menengah atas/////////////////////////////////////////////////////////////////////////
$q_mp = "SELECT * FROM analisis_mpma amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp' ";
$qry = oci_parse($conn_sispa,$q_mp);
oci_execute($qry);
$bilmp = count_row("SELECT * FROM analisis_mpma amp, mpsmkc mp WHERE amp.tahun='$tahun' AND amp.kodsek='".$sm["KODSEK"]."' AND amp.jpep='$jpep' AND  amp.ting='$ting' AND amp.kelas='$kelas' AND amp.kodmp='$kodmp' ");
//echo "$bilmp ";
//echo $q_mp;
 $result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp from sub_guru where nokp='$nokp' and tahun='$tahun' ");
       oci_execute($result_mp);
	   //echo "<td>";
		while($rowmp = oci_fetch_array($result_mp)){
			
			 $ting1=$rowmp["TING"];
			 $kelas1=$rowmp["KELAS"];
		 // echo "$ting1/$kelas1/<br>";
		}  
	{
}
echo "<td>$ting1/<b>$kelas1</b>/<font color=\"#0033CC\"><b>".gpmpma(AP,A,AM,BP,B,CP,C,D,E,G,AMBIL)."</b></font></td>";
}
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       $result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp from sub_guru where nokp='$nokp' and tahun='$tahun' ");
       oci_execute($result_mp);
	   //echo "<td>";
		while($rowmp = oci_fetch_array($result_mp)){
			
			 $ting1=$rowmp["TING"];
			 $kelas1=$rowmp["KELAS"];
		 // echo "$ting1/$kelas1/<br>";
		}  
		echo "</td>"; 

//////////////////////////////////////////////////////////////////////////bil murid/////////////////////////////////////////////////////////////////////////////////////////////////////////////

       $result_mp = oci_parse($conn_sispa,"select ting,kelas,kodmp,bilammp from sub_guru where nokp='$nokp' and tahun='$tahun'");
       oci_execute($result_mp);
	  //echo "select ting,kelas,kodmp,bilammp from sub_guru where nokp='$nokp' and tahun='".date("Y")."'"."<br>";
	   echo "<td>";
		while($rowmp = oci_fetch_array($result_mp)){
			 $am1=$rowmp["BILAMMP"];
			 $ting1=$rowmp["TING"];
			 $kelas1=$rowmp["KELAS"];
		  echo "$ting1/$kelas1/<font color=\"#0033FF\"><b>$am1</b></font><br>";
		}  
		echo "</td>"; 
		
		//if($_SESSION["level"]=="7")
			//echo "  <td><a href=\"papar-mp-guru.php?tahun=$tahun&jpep=$jpep&kodsek=".$sm["KODSEK"]."&namasek=".$sm["NAMASEK"]."&nokp=$nokp&statussek=".$sm["STATUSSEK"]."&nama=".$sm["NAMA"]."\"><center><strong>Edit</strong></center></a></td>\n";
			//echo "  <td><a href=\"papar-mp-guru.php?data=$tahun|$jpep|".$sm["KODSEK"]."|$nokp|".$sm["STATUSSEK"]."|".$sm["NAMA"]."\"><center><strong>Edit</strong></center></a></td>\n";
	}
if ($rowcnt==0){
  echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"8\"><b><font color=\"#FF0000\">Tiada rekod.</font></b></td></tr>";
}	

?>	
<tr bgcolor="#FFFFFF"><td colspan="15">Bilangan Rekod: <strong><?php echo $totalrecord; ?></strong>&nbsp;|&nbsp; Muka Surat:
<?php
paging($totalrecord,$recordpage,"senarai-guru-mp.php?cari=1",$pg);
	
echo "</table>\n";
echo "<br>";
}
}
?>

</td>
<?php include 'kaki.php';?>