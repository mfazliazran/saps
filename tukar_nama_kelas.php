<script type="text/javascript">



  function semak_inputs(){
    if(document.getElementById("slxTing").value==""){
      alert("Sila Pilih Tingkatan.");
      document.getElementById("slxTing").focus();
      return false;
    }

    if(document.getElementById("slxNamaKelas").value==""){
      alert("Sila Pilih Nama Kelas SAPS.");
      document.getElementById("slxNamaKelas").focus();
      return false;
    }

    if(document.getElementById("slxNamaKelasAPDM").value==""){
      alert("Sila Pilih Nama Kelas APDM.");
      document.getElementById("slxNamaKelasAPDM").focus();
      return false;
    }

    if(document.getElementById("slxTing").value!="" && document.getElementById("slxNamaKelas").value!="" && document.getElementById("slxNamaKelasAPDM").value!=""){
      var kompem = confirm("Tukar nama kelas "+ document.getElementById("slxNamaKelas").value +" kepada "+document.getElementById("slxNamaKelasAPDM").value+"?");
      if(kompem==true){
          return true;
      } else {
          return false;
      }
    }

  }
</script>
<?php
global $conn_sispa;

include_once('auth.php');
include_once('config.php');
include 'kepala.php';
include 'menu.php';

$database = "(DESCRIPTION =
                        (ADDRESS =
                                                (PROTOCOL = TCP)
                                                (HOST = nprod-scan.moe.gov.my)
                                                (PORT = 1521)
                        )
                        (CONNECT_DATA = (SERVICE_NAME=KPMSTG))
                        )";
if ($conn_smm=oci_connect("smmsapd","smmsapddev123",$database)){
  $success=1;
  //echo $success;
  // die($success);
 }
 else {
   $err=oci_error();
  echo "Fatal: ".$err["message"]."<br>";
   echo "<center>Harap Maaf !!!<br><br>
      Gangguan Dalam Laluan Ke Database EMIS<br><br>Pihak Kami Memohon Ribuan Kemaafan Di Atas Kesulitan Yang Dialami.<br><br>Terima Kasih !!</center>";
   die();
 }

if($_SESSION["level"]=="7"){
   $kodjpn_pilih = $_POST['slxKodJPN'];
   $kodppd_pilih = $_POST['slxKodPPD'];
   $kodsekolah_pilih = $_POST['slxKodSekolah'];
}
else if($_SESSION["level"]=="6"){
   $kodjpn_pilih = $_SESSION['kodsek'];
   $kodppd_pilih = $_POST['slxKodPPD'];
   $kodsekolah_pilih = $_POST['slxKodSekolah'];
}
else if($_SESSION["level"]=="5"){
   $kodppd_pilih = $_SESSION['kodsek'];
   $kodsekolah_pilih = $_POST['slxKodSekolah'];
//echo "kodppd_pilih:$kodppd_pilih<br>";
}
else 
  $kodsekolah_pilih = $_SESSION['kodsek'];

   

$ting_pilih = $_POST['slxTing'];
?>

<td valign="top" class="rightColumn">

<p class="subHeader">Tukar Nama Kelas<font color="#FFFFFF">(Tarikh Kemaskini Program : 18/10/2016 12:27PM)</font></p>
<?php


if(isset($_POST['button'])){

    $kodjpn_pilih = $_POST['slxKodJPN'];
    $kodppd_pilih = $_POST['slxKodPPD'];
    $kodsekolah = $_POST['slxKodSekolah'];
    $kodting = $_POST['slxTing'];
    $namakelasSAPS = $_POST['slxNamaKelas'];
    $namakelasAPDM = $_POST['slxNamaKelasAPDM'];
	$namakelasAPDM = str_replace(" ","_",$namakelasAPDM);
    $hdnKodJenisSekolah = $_POST['hdnKodJenisSekolah'];
    $tahun_semasa = date("Y");

	$sqljenissekolah = "SELECT kodjenissekolah FROM tsekolah WHERE kodsek='{$kodsek}'";
	$resjenissekolah = oci_parse($conn_sispa,$sqljenissekolah);
	oci_execute($resjenissekolah);
	$datajenissekolah = oci_fetch_array($resjenissekolah);
	$kodjenissekolah = $datajenissekolah['KODJENISSEKOLAH'];
	
	$sqlketerangan = "SELECT jenissekolah FROM tkjenissekolah WHERE kodjenissekolah='{$kodjenissekolah}'"; 
	$resketerangan = oci_parse($conn_smm,$sqlketerangan);
	oci_execute($resketerangan);
	$dataketerangan = oci_fetch_array($resketerangan);
	$kodketerangan = $dataketerangan['JENISSEKOLAH'];

    if($kodting=="D1" or $kodting=="D2" or $kodting=="D3" or $kodting=="D4" or $kodting=="D5" or $kodting=="D6"){
      $table1 = "TMURIDSR";
      $table2 = "MARKAH_PELAJARSR";
      $table3 = "SUB_GURU";
      $table4 = "TKELASSEK";
      $table5 = "TGURU_KELAS";
      $table6 = "ANALISIS_MPSR";
      $table7 = "HEADCOUNTSR";
      $table8 = "PENILAIAN_MURIDSR";
      $table9 = "TNILAI_SR";
    } elseif($kodting=="T1" or $kodting=="T2" or $kodting=="T3"){
      $table1 = "TMURID";
      $table2 = "MARKAH_PELAJAR";
      $table3 = "SUB_GURU";
      $table4 = "TKELASSEK";
      $table5 = "TGURU_KELAS";
      $table6 = "ANALISIS_MPMR";
      $table7 = "HEADCOUNT";
      $table8 = "PENILAIAN_MURIDSMR";
      $table9 = "TNILAI_MR";
    } elseif($kodting=="T4" or $kodting=="T5"){
      $table1 = "TMURID";
      $table2 = "MARKAH_PELAJAR";
      $table3 = "SUB_GURU";
      $table4 = "TKELASSEK";
      $table5 = "TGURU_KELAS";
      $table6 = "ANALISIS_MPMA";
      $table7 = "HEADCOUNT";
      $table8 = "PENILAIAN_MURIDSMA";
      $table9 = "TNILAI_MA";
    }

    // business rule : bila user pilih nama kelas dekat saps, dan pilih nama kelas dekat apdm, tukar nama kelas dalam saps berdasarkan nama kelas apdm
      // sekolah rendah
      // TMURIDSR, MARKAH_PELAJARSR - {KODSEK, DARJAH, KELAS, TAHUN}, SUB_GURU - {TING, KELAS, KODSEK}, TKELASSEK, TGURU_KELAS, ANALISIS_MPSR, HEADCOUNTSR, PENILAIAN_MURIDSR, TNILAI_SR

    
      $chk = "SELECT KELAS$kodting FROM $table1 WHERE KODSEK$kodting='$kodsekolah' and TAHUN$kodting='$tahun_semasa' and $kodting='$kodting' and KELAS$kodting='$namakelasAPDM'";
      $resultchk = oci_parse($conn_sispa,$chk); 
      oci_execute($resultchk);
      if($datachk=oci_fetch_array($resultchk)){
         die("Nama Kelas Sudah Digunakan !");
      }
    
      $update1 = "UPDATE $table1 SET KELAS$kodting='$namakelasAPDM' WHERE KODSEK$kodting='$kodsekolah' and TAHUN$kodting='$tahun_semasa' and $kodting='$kodting' and KELAS$kodting='$namakelasSAPS'";
      //die($update1);
    $result1 = oci_parse($conn_sispa,$update1);
    oci_execute($result1);

      $update2 = "UPDATE $table2 SET KELAS='$namakelasAPDM' WHERE KODSEK='$kodsekolah' and DARJAH='$kodting' and KELAS='$namakelasSAPS' and TAHUN='$tahun_semasa'";
    $result2 = oci_parse($conn_sispa,$update2);
    oci_execute($result2);

      $update3 = "UPDATE $table3 SET KELAS='$namakelasAPDM' WHERE TAHUN='$tahun_semasa' and KODSEK='$kodsekolah' and TING='$kodting' and KELAS='$namakelasSAPS'";
    $result3 = oci_parse($conn_sispa,$update3);
    oci_execute($result3);

      $update4 = "UPDATE $table4 SET KELAS='$namakelasAPDM' WHERE TAHUN='$tahun_semasa' and KODSEK='$kodsekolah' and TING='$kodting' and KELAS='$namakelasSAPS'";
    $result4 = oci_parse($conn_sispa,$update4);
    oci_execute($result4);

      $update5 = "UPDATE $table5 SET KELAS='$namakelasAPDM' WHERE TAHUN='$tahun_semasa' and KODSEK='$kodsekolah' and TING='$kodting' and KELAS='$namakelasSAPS'";
    $result5 = oci_parse($conn_sispa,$update5);
    oci_execute($result5);

      $update6 = "UPDATE $table6 SET KELAS='$namakelasAPDM' WHERE TAHUN='$tahun_semasa' and KODSEK='$kodsekolah' and DARJAH='$kodting' and KELAS='$namakelasSAPS'";
    $result6 = oci_parse($conn_sispa,$update6);
    oci_execute($result6);

      $update7 = "UPDATE $table7 SET KELAS='$namakelasAPDM' WHERE KODSEK='$kodsekolah' and TING='$kodting' and KELAS='$namakelasSAPS' and TAHUN='$tahun_semasa'";
    $result7 = oci_parse($conn_sispa,$update7);
    oci_execute($result7);

      $update8 = "UPDATE $table8 SET KELAS='$namakelasAPDM' WHERE TAHUN='$tahun_semasa' and KODSEK='$kodsekolah' and DARJAH='$kodting' and KELAS='$namakelasSAPS'";
    $result8 = oci_parse($conn_sispa,$update8);
    oci_execute($result8);

      $update9 = "UPDATE $table9 SET KELAS='$namakelasAPDM' WHERE TAHUN='$tahun_semasa' and KODSEK='$kodsekolah' and DARJAH='$kodting' and KELAS='$namakelasSAPS'";
    $result9 = oci_parse($conn_sispa,$update9);
    oci_execute($result9);
       
  die("Nama Kelas $namakelasSAPS telah ditukar menjadi $namakelasAPDM.<br>");
    // die($update1."<br />".$update2."<br />".$update3."<br />".$update4."<br />".$update5."<br />".$update6."<br />".$update7."<br />".$update8."<br />".$update9);
}


// echo $kodsek."kodsek";

?>
<form id="form1" name="form1" method="post" action="">
<table width="576" border="1" align="center">
  <tr bgcolor="#ff9900">
    <td colspan="3"><div align="center">TUKAR NAMA KELAS</div></td>
  </tr>
<?php if ($_SESSION["level"]==7) {//PUSAT  ?>
  <tr>
    <td>JPN</td>
    <td>:</td>
    <td>
      <select name="slxKodJPN" id="slxKodJPN" onchange="document.form1.submit()">
        <option value="">- PILIH -</option>  
        <?php

		  $sql = "SELECT KODNEGERI,NEGERI FROM TKNEGERI WHERE KODNEGERI NOT IN ('98','99') ORDER BY NEGERI";
          
          $res = oci_parse($conn_sispa,$sql);
          oci_execute($res);
          while($data = oci_fetch_array($res)){
            $kodnegeri = $data['KODNEGERI'];
            $namanegeri = $data['NEGERI'];

            echo "<option";
            if($kodnegeri==$kodjpn_pilih){
                echo " SELECTED ";
            }
            echo " value='$kodnegeri'>$namanegeri</option>";
          }
        ?>
      </select>
	<?php //echo "$sql<br>"; ?>  
    </td>
  </tr>
<?php } ?>
<?php if ($_SESSION["level"]==6 or $_SESSION["level"]==7) {//JPN/PUSAT   ?>
  <tr>
    <td>PPD</td>
    <td>:</td>
    <td>
      <select name="slxKodPPD" id="slxKodPPD" onchange="document.form1.submit()">
        <option value="">- PILIH -</option>  
        <?php

		  $sql = "SELECT KODPPD,PPD FROM TKPPD WHERE KODNEGERI='$kodjpn_pilih' ORDER BY PPD";
          
          $res = oci_parse($conn_sispa,$sql);
          oci_execute($res);
          while($data = oci_fetch_array($res)){
            $kodppd = $data['KODPPD'];
            $namappd = $data['PPD'];

            echo "<option";
            if($kodppd==$kodppd_pilih){
                echo " SELECTED ";
            }
            echo " value='$kodppd'>$namappd</option>";
          }
        ?>
      </select>
	<?php //echo "$sql<br>"; ?>  
    </td>
  </tr>
<?php } ?>
<?php// if ($_SESSION["level"]==5) //PPD   ?>
  <tr>
    <td>KOD SEKOLAH</td>
    <td>:</td>
    <td>
<?php

          if($_SESSION["level"]==6 or $_SESSION["level"]==7){
              $sql = "SELECT KODSEK, NAMASEK FROM TSEKOLAH WHERE KODNEGERIJPN='$kodjpn_pilih' and  KODPPD='$kodppd_pilih' ORDER BY NAMASEK";
          } else if($_SESSION["level"]==5){
              $sql = "SELECT KODSEK, NAMASEK FROM TSEKOLAH WHERE KODPPD='$kodppd_pilih' ORDER BY NAMASEK";
		  }
          else {		  
              $sql = "SELECT KODSEK, NAMASEK FROM TSEKOLAH WHERE KODSEK='".$_SESSION['pilihkodsekolah']."' GROUP BY KODSEK, NAMASEK ORDER BY NAMASEK";
          }

?>
      <select name="slxKodSekolah" id="slxKodSekolah" onchange="document.form1.submit()">
        <option value="">- PILIH -</option>  
        <?php
          
          $res = oci_parse($conn_sispa,$sql);
          oci_execute($res);
          while($data = oci_fetch_array($res)){
            $kodsekolah = $data['KODSEK'];
            $namasekolah = $data['NAMASEK'];

            echo "<option";
            if($kodsekolah==$kodsekolah_pilih){
                echo " SELECTED ";
            }
            echo " value='$kodsekolah'>$namasekolah</option>";
          }
        ?>
      </select>
	<?php //echo "$sql<br>"; ?>  
    </td>
  </tr>
  <tr>
    <td>TING</td>
    <td>:</td>
    <td>
      <select name="slxTing" id="slxTing" onchange="document.form1.submit()">
        <option value="">- PILIH TING -</option>
        <?php

          $sqlTing = "SELECT TING FROM TKELASSEK WHERE KODSEK='$kodsekolah_pilih' GROUP BY TING ORDER BY TING";
          $resTing = oci_parse($conn_sispa,$sqlTing);
          oci_execute($resTing);
          while($dataTing = oci_fetch_array($resTing)){
              $kelas = $dataTing['KELAS'];
              $ting = $dataTing['TING'];

              echo "<option ";
              if($ting==$ting_pilih){
                  echo " SELECTED ";
              } 
              echo "value='$ting'>$ting</option>";
          }

        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td>NAMA KELAS (SAPS)</td>
    <td>:</td>
    <td>
<?php 
          $sqlKelas = "SELECT KELAS, TING FROM TKELASSEK WHERE KODSEK='$kodsekolah_pilih' and TING='$ting_pilih' and TAHUN='".date("Y")."' GROUP BY KELAS, TING ORDER BY TING";

             //echo $sqlKelas;
?>
      <select name="slxNamaKelas" id="slxNamaKelas" >
        <option value="">- PILIH KELAS -</option>
        <?php

          $resKelas = oci_parse($conn_sispa,$sqlKelas);
          oci_execute($resKelas);
          while($dataKelas = oci_fetch_array($resKelas)){
              $kelas = $dataKelas['KELAS'];
              $ting = $dataKelas['TING'];

              echo "<option ";
              if($pilihkelas==$kelas){
                  echo " SELECTED ";
              }
              echo "value='$kelas'>$kelas</option>";
          }

        ?>
      </select>
    </td>
  </tr>
  <tr>
    <td>NAMA KELAS (APDM)</td>
    <td>:</td>
    <td>
      <select name="slxNamaKelasAPDM" id="slxNamaKelasAPDM">
        <option value="">- PILIH KELAS -</option>
        <?php
          $query = "SELECT DISTINCT KODTINGKATANTAHUN,NAMAKELAS FROM DATA_SEMUA_MURID WHERE KODSEKOLAH='$kodsekolah_pilih' and trim(KODTINGKATANTAHUN)='$ting_pilih' ORDER BY TRIM(KODTINGKATANTAHUN),NAMAKELAS"; 
          $result = oci_parse($conn_sispa,$query);
          oci_execute($result);
          while($dataAPDM = oci_fetch_array($result)){
              $kodtingkatan = $dataAPDM[0];
              $namakelas = $dataAPDM['NAMAKELAS'];

              echo "<option value='$namakelas'>$namakelas</option>";
          }
        ?>
      </select>
<?php //echo "$query<br>";?>
    </td>
  </tr>
  <!-- <?php echo $sqlKelas;?> -->
  <tr>
    <td colspan="3"><input type="submit" name="button" id="button" value="Tukar" onclick="return semak_inputs();" />
      <input type="hidden" name="hdnKodJenisSekolah" value="<?php echo $kodjenissekolah;?>">
      <input name="post" type="hidden" id="post" value="1" /></td>
    </tr>
</table>
</form>