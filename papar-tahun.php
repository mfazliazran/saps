<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
$tahun_pilih = $_GET["tahun"];
if($tahun_pilih==""){
	$tahun_pilih = date("Y");	
}
$tahun_semasa = date("Y");
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kawalan Kemasukkan Markah Peperiksaan</p>
<h3><center>Kawalan Kemasukkan Markah Peperiksaan</center></h3>
<table width="80%" border="0" align="center" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
  <tr style="background-color: rgb(153, 153, 153); height: 25px;">
    <td colspan="2" style="height: 25px;"><div style="text-align:center;"><select autocomplete="off" name="tahun_semasa" id="tahun_semasa" value="" onChange="location.href='papar-tahun.php?tahun='+this.value;">
          <option value="">-- Pilih Tahun --</option>
          <?php
            for($thn = 2010; $thn <= $tahun_semasa; $thn++ ){
                if($tahun_pilih == $thn){
                    echo "<option value='$thn' selected>$thn</option>";
                } else {
                    echo "<option value='$thn'>$thn</option>";
                }
            }			
            ?>
      </select></div></td>
  </tr>
</table>
<table width="80%" border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
  <tr height="30">
    <td colspan="6" align="right"><img src="images/add.gif">&nbsp;<a href="tahun.php"><b>Tambah Kawalan Kemasukan Markah Peperiksaan</b></a></td>
  </tr>
  <tr height="30" bgcolor="#FF9933">
    <td width="100" align="center">Tahun</td>
	<td >Jenis Peperiksaan</td>
    <td >Tarikh Mula Pengisian</td>
    <td >Tarikh Tamat Pengisian</td>
    <td align="center">Status</td>
    <td align="center">Tindakan</td>
  </tr>
  <tr>
  <?php
  $querydata="SELECT * FROM kawal_pep where tahun='$tahun_pilih' ORDER BY tahun, rank ASC";
  $resultdata= oci_parse($conn_sispa,$querydata);
  oci_execute($resultdata);
  $i=0;
  while($row = oci_fetch_array($resultdata))
	{
  	$tahun = $row["TAHUN"];
	$jpep = $row["JPEP"];
	$status = $row["STATUS"];
	$mulaisi = $row["MULA_PENGISIAN"];
	$tamatisi = $row["TAMAT_PENGISIAN"];
			
		if ($status == 1){
		$logo = "<img src=\"images/ok.png\" width=\"20\" height=\"20\">";
		}
		else {
		$logo = "<img src=\"images/ko.png\" width=\"20\" height=\"20\">";
		}
		
	echo " <td align=center>$tahun</td>\n";
	echo " <td>".jpep($jpep)."</td>\n";
	echo " <td>$mulaisi</td>\n";
	echo " <td>$tamatisi</td>\n";
	echo " <td align=center>$logo</td>\n";
	echo " <td align=center><a href='tahun.php?data=".$tahun."/".$jpep."/".$status."'><img src='images/btn_edit.gif' border='0' alt='Kemaskini'></a>&nbsp;&nbsp;<a href='hapus_kawal_jpep.php?data=".$tahun."/".$jpep."/".$status."'><img src='images/btn_delete.gif' border='0' alt='Hapus' onclick=\"return (confirm('Adakah anda pasti hapus data kawalan ".jpep($jpep)."'))\"></a></td>\n";
	echo " </tr>\n";
	
	$i++;
	}
?>
</table>
<br>
</td>
<?php include 'kaki.php';?>