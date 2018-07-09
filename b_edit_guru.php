<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Kemaskini Guru</p>

<?php
$m=$_GET['data'];
list ($nokp, $kodsek)=split('[|]', $m);
$query = "SELECT * FROM login WHERE kodsek= :kodsek AND nokp= :nokp"; 
$qic = oci_parse($conn_sispa,$query);
oci_bind_by_name($qic, ':kodsek', $kodsek);
oci_bind_by_name($qic, ':nokp', $nokp);
oci_execute($qic);
$bil_murid=0;

while($row = oci_fetch_array($qic))
{
	$nokp = $row["NOKP"];
	$nokplama = $row["NOKPLAMA"];
	$nama = $row["NAMA"];
	$jantina = $row["JAN"];
	$level = $row["LEVEL1"];
	$negeri = $row["NEGERI"];
	$daerah = $row["DAERAH"];
	$namasek = $row["NAMASEK"];
	$statussek = $row["STATUSSEK"];
	$kodsek = $row["KODSEK"];
	$ting = $row["TING"];
	$kelas = $row["KELAS"];
	
	if ($level=='1'){
		$status="GURU BIASA";
		}
	else if ($level=='2'){
		$status="GURU KELAS";
		}
	else {
		$status="ADMIN";
		}
	
?>
<link rel="stylesheet" type="text/css" href="tulisexam.css">
          <br><br>
		  <blockquote>
                          <form id="loginForm" name="loginForm" method="post" action="kemaskini_guru.php">
                            <table width="500" align="center" cellpadding="0" cellspacing="5">
                              <tr>
                                <td width="167"><div align="left">NOKP</div></td>
                                <td width="316">
                                  <div align="left">
                                    <input name="nokp" type="text" id="nokp" value="<?php echo"$nokp"; ?>" size="50" maxlength="12">
                                    <input name="nokplama" type="hidden" id="nokplama" value="<?php echo"$nokp"; ?>" size="50" maxlength="12">
                                  </div></td></tr>
                              <tr>
                                <td><div align="left">NAMA</div></td>
                                <td>
                                  <div align="left">
                                    <input maxlength="80" name="nama" type="text" id="nama" value="<?php echo"$nama"; ?>" onBlur="this.value=this.value.toUpperCase()" size="50">
                                  </div></td></tr>
                              <tr>
                                <td><div align="left">JANTINA</div></td>
                                <td><div align="left">
                                  <select name="jan" id="jan">
                                    <option value="<?php echo"$jantina"; ?>"><?php echo"$jantina"; ?></option>
                                    <option value="L">LELAKI</option>
                                    <option value="P">PEREMPUAN</option>
                                  </select>
                                </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">KOD SEKOLAH </div></td>
                                <td>
                                  <div align="left">
                                    <input name="kodsek" type="text" id="kodsek" readonly onBlur="this.value=this.value.toUpperCase()" value="<?php echo"$kodsek"; ?>" size="50">
                                  </div></td></tr>
                              <tr>
                                <td><div align="left">DAERAH</div></td>
                                <td>
                                  <div align="left">
                                    <input name="daerah" type="text" id="daerah" readonly value="<?php echo"$daerah"; ?>" size="50">
								    </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">NEGERI</div></td>
                                <td>
                                  <div align="left">
                                    <input name="negeri" type="text" id="negeri" value="<?php echo"$negeri"; ?>" readonly size="50">
                                  </div></td></tr>
                              <tr>
                                <td><div align="left">NAMA SEKOLAH  </div></td>
                                <td><div align="left">
                                  <input name="namasek" type="text" id="namasek" value="<?php echo"$namasek"; ?>" readonly size="50">
                                </div></td>
                              </tr>
							  <tr>
                                <td><div align="left">STATUS SEKOLAH</div></td>
                                <td>
								  <div align="left">
								    <input name="ssek" type="text" id="ssek" value="<?php echo"$statussek"; ?>" readonly size="50">
								    </div></td>
							  </tr>
                              <tr>
                                <td><div align="left">STATUS PENGGUNA </div></td>
                                <td><div align="left">
                                  <input name="level" type="text" id="level" value="<?php echo"$status"; ?>" readonly size="50">
                                </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">GURU KELAS</div></td>
                                <td>                                  <div align="left">
                                    <input name="ting" type="ting" id="ting" value="<?php echo"$ting"; ?>" readonly size="50" maxlength="15">
                                    <input name="kelas" type="hidden" id="kelas" value="<?php echo"$kelas"; ?>" readonly size="50" maxlength="15">
                                  </div></td></tr>
                               <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left">
                                  <input type="submit" name="Submit" value="SIMPAN">
                                </div></td>
                              </tr>
                            </table>
                          </form>
						  </blockquote>
<?php
}

?>
</td>
<?php include 'kaki.php';?> 