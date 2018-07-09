<?php
set_time_limit(0);
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
if($_SESSION['level']<>'7'){ 
	die("Anda bukan SUPERADMIN");
}

$database = "(DESCRIPTION =
                        (ADDRESS =
                                                (PROTOCOL = TCP)
                                                (HOST = prod-scan.moe.gov.my)
                                                (PORT = 1521)
                        )
                        (CONNECT_DATA = (SERVICE_NAME=KPMPROD))
                        )";

if ($conn_smm=oci_connect("smmsapd","S@PD02i8#",$database)){
  $success=1;
  //echo $success;
 }
 else {
   $err=oci_error();
  echo "Fatal: ".$err["message"]."<br>";
   echo "<center>Harap Maaf !!!<br><br>
      Gangguan Dalam Laluan Ke Database EMIS<br><br>Pihak Kami Memohon Ribuan Kemaafan Di Atas Kesulitan Yang Dialami.<br><br>Terima Kasih !!</center>";
   die();
 }


?> 
<td valign="top" class="rightColumn">
<p class="subHeader">Import Sekolah EMIS dan Guru SMM ..<font color="#FFFFFF">(Tarikh Kemaskini Program : 24/2/2015 12:11PM)</font></p>
<?php
//echo ("1.post:". $_POST['post']." kodsek:".$_GET["kodsek"]);
if(!($_POST['post']=="1" or $_GET["kodsek"]<>"")){
?>
<form id="form1" name="form1" method="post" action="">
<table width="578" border="1">
  <tr>
    <td colspan="2" bgcolor="#ff9900"><div align="center">IMPORT SEKOLAH EMIS</div></td>
  </tr>
  <tr>
    <td colspan="2">SILA MASUKKAN KOD SEKOLAH</td>
  </tr>
  <tr>
    <td>Kod Sekolah</td>
    <td><label>
      <input type="text" name="txtKodSek" id="txtKodSek" />
    </label></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="button" id="button" value="Hantar" />
      <input name="post" type="hidden" id="post" value="1" /></td>
    </tr>
</table>
</form>
<?php 

} //if($_POST['post']=='' and $_GET["kodsek"]==""){
 function GetDesc($tbl,$ktrgn,$kod,$v)
 {
  $rekod[]=NULL;
  $sql2="select " . $ktrgn . " from " . $tbl ." where " . $kod . "='" . $v . "'";
  //echo $sql2."<br><br>";
  $qid2=mysql_query($sql2);
  $rekod=mysql_fetch_row($qid2);		
  return $rekod[0];
 
 }

//die("post:". $_POST['post']." kodsek:".$_GET["kodsek"]);
if($_POST['post']=="1" or $_GET["kodsek"]<>""){
//die("post=1");
$kodsekolah=$_POST["txtKodSek"];
if ($kodsekolah=="")
 $kodsekolah=$_GET["kodsek"];
$tahun=date("Y");
$sql="select KodSekolah, NamaSekolah, AlamatSurat, BandarSurat, PoskodSurat, KodNegeriSurat, NoTelefon, NoFax, KodStatusSekolah, 
					KodLokasiSemasa,KodJenisSekolah, GredSekolah, KodJenisBantuan, KodPeringkat, KodJenisMurid,  KodSesi,Mukim,
					KodParlimen, KodDUN, KodPPD, KodPKG,KodNegeriJPN,KodDaerah,KodJenisSekolah
					from tssekolah where KodSekolah='$kodsekolah'";
$res=oci_parse($conn_smm,$sql); //KodNegeriJPN<>'08'
oci_execute($res);
	$cnt=0;
	while($data=oci_fetch_array($res)){
	  $kodsekolah=$data["KODSEKOLAH"];
	  $namasekolah=oci_escape_string($data["NAMASEKOLAH"]);
	  $alamatsurat=oci_escape_string($data["ALAMATSURAT"]);
	  $bandarsurat=oci_escape_string($data["BANDARSURAT"]);
	  $poskodsurat=$data["POSKODSURAT"];
	  $negeri=$data["KODNEGERISURAT"];
	  $negerijpn=$data["KODNEGERIJPN"];
	  $notelefon=$data["NOTELEFON"];
	  $nofax=$data["NOFAX"];
	  $kodlokasisemasa=$data["KODLOKASISEMASA"];
	  $kodjenissekolah=$data["KODJENISSEKOLAH"];
	  $gredsekolah=$data["GREDSEKOLAH"];
	  $kodjenisbantuan=$data["KODJENISBANTUAN"];
	  $kodperingkat=$data["KODPERINGKAT"];
	  $kodjenismurid=$data["KODJENISMURID"];
	  $kodsesi=$data["KODSESI"];
	  $mukim=oci_escape_string($data["MUKIM"]);
	  $kodparlimen=$data["KODPARLIMEN"];
	  $koddun=$data["KODDUN"];
	  $kodppd=$data["KODPPD"];
	  $kodpkg=$data["KODPKG"];
	  $status=$data["KODSTATUSSEKOLAH"];
	  $koddaerah=$data["KODDAERAH"];
	  $kodnegeri=$data["KODNEGERIJPN"];
	  $kodjenissekolah=$data["KODJENISSEKOLAH"];
	
	 if (substr($kodjenissekolah,0,1)=="1")
	   $status="SR";
	 else
	   $status="SM";
	  
		   
	  $cnt_sek=count_row("select KODSEK from TSEKOLAH where KODSEK='$kodsekolah'");
	  //echo "status:$status cnt_sek:$cntsek<br>";
	  if ($cnt_sek==0){
		  $resnegeri=oci_parse($conn_smm,"select negeri from tknegeri where kodnegeri='$negeri'");
		  oci_execute($resnegeri);
		  $datanegeri=oci_fetch_array($resnegeri);
		  $ktrgnnegeri=$datanegeri["NEGERI"];
	
		  $sql="insert into tsekolah(KODSEK,NAMASEK,ALAMATSURAT,BANDARSURAT,POSKODSURAT,NEGERI ,NOTELEFON,NOFAX,
				 KODLOKASISEMASA,KODJENISSEKOLAH,KODSTATUSSEKOLAH,GREDSEKOLAH,KODJENISBANTUAN,KODPERINGKAT,KODJENISMURID,KODSESI,
				 MUKIM,KODPARLIMEN,KODDUN,KODPPD,KODPKG,STATUS,KEYCODE,LENCANA,LABELSEK,KODNEGERIJPN) 		values('$kodsekolah','$namasekolah','$alamatsurat','$bandarsurat','$poskodsurat','$ktrgnnegeri','$notelefon','$nofax','$kodlokasisemasa','$kodjenissekolah','01','$gredsekolah','$kodjenisbantuan','$kodperingkat','$kodjenismurid','$kodsesi','$mukim','$kodparlimen','$koddun','$kodppd','$kodpkg','$status','$kodsekolah',' ',' ','$negerijpn')";
			//echo $sql."<br><br>";
		  	$stmt=oci_parse($conn_sispa,$sql);
		  	oci_execute($stmt);
			
			
		  	#################### IMPORT GURU KELAS SEKIRANYA SEKOLAH TIDAK WUJUD LAGI#############################
		  	$qrystaff_smm = oci_parse($conn_smm,"select KPUtama,Nama,Jantina from tgstaf where KodSekolah='$kodsekolah' and JenisStaf='G'");
			//echo "select KPUtama,Nama,Jantina from tgstaf where KodSekolah='$kodsekolah' and JenisStaf='G'<br>";
			oci_execute($qrystaff_smm);
			$cnt=0;
			$cnt_update=0;
			while ($rowstaf = oci_fetch_array($qrystaff_smm)) {
			  $cntguru++;
			  $kputama=oci_escape_string($rowstaf["KPUTAMA"]);
			  $nama=oci_escape_string($rowstaf["NAMA"]);
			  $jantina=oci_escape_string($rowstaf["JANTINA"]);
			  
			  $cnt_guru=count_row("select NOKP from LOGIN where NOKP='$kputama'");// and TAHUN='$tahun'");
			  $resdaerah=oci_parse($conn_smm,"select daerah from tkdaerah where koddaerah='$koddaerah'");
			  oci_execute($resdaerah);
			  $datadaerah=oci_fetch_array($resdaerah);
			  $daerah=$datadaerah["DAERAH"];

			  $resnegeri=oci_parse($conn_smm,"select negeri from tknegeri where kodnegeri='$kodnegeri'");
			  oci_execute($resnegeri);
			  $datanegeri=oci_fetch_array($resnegeri);
			  $daerah=$datanegeri["NEGERI"];
		
			  if ($cnt_guru==0){ // data belum ada
				  $cnt++;
				  $sqlinsert="insert into LOGIN(tahun, nokp, nama, jan, user1,pswd,negeri, daerah, namasek, kodsek, statussek,online1,level1 ) 
						 values('$tahun','$kputama','$nama','$jantina',null,null,'$negeri','$daerah','$namasekolah','$kodsekolah','$status','0','1')";
				  //echo "$cntguru. $sqlinsert<br><br>";		 
				  $stmtinsert=oci_parse($conn_sispa,$sqlinsert);
				  oci_execute($stmtinsert);
			  } else { // dah ada. kena tukar kod sekolah
				  $cnt_update++;
				  $sqlupdate="update LOGIN set kodsek='$kodsekolah',namasek='$namasekolah',negeri='$negeri',daerah='$daerah',statussek='$status',online1='0',level1='1' where nokp='$kputama'";		
				  $stmtupdate=oci_parse($conn_sispa,$sqlupdate);
				  oci_execute($stmtupdate);
				  //echo "$cntguru. $sqlupdate<br><br>";
			  }
		   } //while($data guru
			
		  
		echo "<script language=\"javascript\" type=\"text/javascript\">";
		echo "alert('Data $namasekolah dan guru sekolah berjaya diimport.');";
		//echo "window.close();";
		echo "</script>";
	  }	  //id cnt_sek
	  else
	    echo "Sekolah $namasekolah telah wujud !<br>";
}//while utama
echo "<br><br>Proses import data sekolah <b>$kodsekolah</b> telah selesai...<br>";
echo "Jumlah GURU BARU telah berjaya di import : $cnt<br> ";
echo "Jumlah GURU dikemasikini : $cnt_update<br> ";
} //$_POST...
//pageredirect("senarai_sekolah_emis.php");


?>
</td>
<?php include 'kaki.php';?> 