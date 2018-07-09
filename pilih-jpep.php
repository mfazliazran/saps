<?php
include 'auth.php'; 
include 'config.php';
include 'fungsi.php';
include 'kepala.php';
//die($_SESSION['SESS_MEMBER_ID']);
?>

<script type="text/javascript">

function pilih_tahun(tahun_semasa)
{
//alert(nama_bulan)
//alert(tahun)

//location.href="mpep-daerah.php?tahun=" + tahun_semasa
location.href="pilih-jpep.php?tahun=" + tahun_semasa


}

</script>
<?php
$tahun_semasa = $_GET['tahun'];

if($tahun_semasa <> "") {
	$tahun = $tahun_semasa;
} else {
	$tahun = date("Y");
}

$_SESSION['tahun'] = $tahun;

$tahun_sekarang = date("Y");

?>
<td valign="top" class="rightColumn">
<link rel="stylesheet" href="tab/example.css" TYPE="text/css" MEDIA="screen">
<p class="subHeader">Pilih Jenis Peperiksaan</p>
		<br><br><br>
		<form name="myform" method="POST" action="">
		<table width="750" border="1" align="center" cellpadding="10" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
		<tr>
			<td>PILIH TAHUN : &nbsp;&nbsp;
			<select name="tahun_semasa" id="tahun_semasa" onchange="pilih_tahun(this.value)">
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
	</select>
		</td>
		</tr>
	</table>
		</form>
		<table width="750" border="1" align="center" cellpadding="10" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
		  <tr bgcolor="#FF9933">
			<td bgcolor="#336699">Tahun</td>
			<td bgcolor="#336699">Jenis Peperiksaan</td>
			<td colspan="3" bgcolor="#336699"><center>Status</center></td>
            
			<?php if ($_SESSION['level']=="3" or $_SESSION['level']=="4"){?>
            <td colspan="1" bgcolor="#336699"><center>Semak Murid SAPS & APDM</center></td>
            <?php }?>
            
		  </tr>
		  <tr>
		  <?php
		  $sql="SELECT TAHUN,JPEP,STATUS FROM kawal_pep WHERE tahun='$tahun' ORDER BY rank";
		  $stmt=OCIParse($conn_sispa,$sql);
		  //$bil=count_row("SELECT TAHUN,RANK FROM kawal_pep WHERE tahun='$tahun' ORDER BY rank");
		  
		  if(!count_row($sql)) 
		      die('Tahun semasa belum dibuka');
		  OCIExecute($stmt);
			
		  $i=0;
		  while(OCIFetch($stmt))
			{
			$tahun = OCIResult($stmt,"TAHUN");
			$jpep = OCIResult($stmt,"JPEP");
			$status = OCIResult($stmt,"STATUS");
			$status_pep = OCIResult($stmt,"STATUS");
			$_SESSION['status_buka_tutup'] = $status;
			
				if ($status == 1){
				$status = "Buka";
				$logo = "<img src=\"images/ok.png\" width=\"20\" height=\"20\">";
				}
				else {
				$status = "Tutup";
				$logo = "<img src=\"images/ko.png\" width=\"20\" height=\"20\">";
				}
				
				
				
			echo " <td>$tahun</td>\n";
			echo " <td>".jpep($jpep)."</td>\n";
			echo " <td>$status</td>\n";
			echo " <td>$logo</td>\n";
			echo " <td><a href=session-jpep.php?datajpep=".$tahun."/".$jpep."/".$status_pep."/".$_SESSION['level'].">Masuk</a></td>\n";
			if ($_SESSION['level']=="3" or $_SESSION['level']=="4")
				echo " <td align=\"center\"><a href=session-apdm.php?datajpep=".$tahun."/".$jpep."/".$status_pep."/".$_SESSION['level'].">Semak</a></td>\n";
			echo " </tr>\n";
			
			$i++;
			}
				/////////////////////////whois//////////////////////////
			//$tm=date("Y-m-d H:i:s");
			//$timestamp=time();
			//$stmt=OCIParse($conn_sispa,"UPDATE login SET online1='1',tm='$timestamp' WHERE user1='".$_SESSION['SESS_MEMBER_ID']."'");
			//OCIExecute($stmt);
			/////////////////////////////end whois/////////////////
			?>
</tr>
</table><br><br>
<?php //include 'kaki.php'; ?>
