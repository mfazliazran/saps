<?php 
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';
?>
<SCRIPT LANGUAGE="JavaScript"> 
function openWin (fileName,windowName) 
{ 
	mywindow = window.open(fileName,windowName,'width=850,height=800,directories=no,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,resizable=no'); 
	mywindow.moveTo(screen.width/2-400,screen.height/2-400);
}

</script>
<td valign="top" class="rightColumn">
<p class="subHeader">Pindah Guru</p>

<?php
$m=$_GET['data'];
list ($nokp, $kodsek)=split('[|]', $m);

$query = "SELECT * FROM login WHERE nokp= :nokp"; 
$qic = oci_parse($conn_sispa,$query);
oci_bind_by_name($qic, ':nokp', $nokp);
oci_execute($qic);
while($row = oci_fetch_array($qic))
{
	$nokp = $row["NOKP"];
	$nama = $row["NAMA"];
	$jantina = $row["JAN"];
	$levelguru = $row["LEVEL1"];
	$negeri = $row["NEGERI"];
	$daerah = $row["DAERAH"];
	$namasek = $row["NAMASEK"];
	$statussek = $row["STATUSSEK"];
	$kodsek = $row["KODSEK"];
	$ting = $row["TING"];
	$kelas = $row["KELAS"];
	
	if ($levelguru==1){
		$statusguru="GURU BIASA";
		}
	else if ($levelguru==2){
		$statusguru="GURU KELAS";
		}
	else {
		$statusguru="ADMIN";
		}
?>
<br><blockquote>
                          <form id="loginForm" name="loginForm" method="post" action="pindah_guru.php">
                            <table width="700" align="center" cellpadding="0" cellspacing="5">
                              <tr>
                                <td width="120" ><div align="left">NOKP</div></td>
                                <td width="580" >
                                  <div align="left">
                                    <input name="nokp" type="text" id="nokp" value="<?php echo"$nokp"; ?>" readonly size="50" maxlength="12">
                                    <input name="nokplama" type="hidden" id="nokplama" value="<?php echo"$nokp"; ?>" size="50" maxlength="12">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">NAMA</div></td>
                                <td>
                                  <div align="left">
                                    <input name="nama" type="text" id="nama" value="<?php echo"$nama"; ?>" readonly onBlur="this.value=this.value.toUpperCase()" size="50">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">JANTINA</div></td>
                                <td>
                                  <div align="left">
                                    <input name="jan" type="text" id="jan" value="<?php echo"$jantina"; ?>" readonly onBlur="this.value=this.value.toUpperCase()" size="50">
						          </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">KOD SEKOLAH </div></td>
                                <td><div align="left">
                                  <input name="kodseklama" type="text" id="kodseklama" value="<?php echo"$kodsek"; ?>" readonly onBlur="this.value=this.value.toUpperCase()" size="50">
                                </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">DAERAH</div></td>
                                <td>
                                  <div align="left">
                                    <input name="daerah" type="text" id="daerah" value="<?php echo"$daerah"; ?>" readonly onBlur="this.value=this.value.toUpperCase()" size="50">
							      </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">NEGERI</div></td>
                                <td>
                                  <div align="left">
                                    <input name="user" type="text" id="user2" value="<?php echo"$negeri"; ?>" readonly size="50">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">NAMA SEKOLAH  </div></td>
                                <td><div align="left">
                                  <input name="namasek" type="text" id="namasek" value="<?php echo"$namasek"; ?>" readonly onBlur="this.value=this.value.toUpperCase()" size="50">
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
                                  <input name="level" type="text" id="level" value="<?php echo"$statusguru"; ?>" readonly size="50">
                                </div></td>
                              </tr>
                              <tr>
                                <td colspan="2"><hr noshade></td>
                              </tr>
                              <tr>
                                <td><div align="left">PINDAH KE SEKOLAH </div></td>
                                <td>
                                <input name="kodsek"  id="kodsek" type="text"  readonly size="10">
                                <input name="sek"  id="sek" type="text"  readonly size="50">
                                <input type="hidden" name="data1" id="data1" />
                                <input type="button" name="pilih" value="PILIH" onClick="openWin('pilih_sekolah.php','Maklumat_Sekolah');">
<div align="left"></div></td>
                              </tr>
                              <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left">								</div></td>
                              </tr>
                              <tr>
                                <td colspan="2"><div align="left"></div>
                                <div align="left">
                                  <hr noshade>
                                </div></td>
                              </tr>
                               <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left">
                                  <input type="submit" name="Submit" value="PINDAH GURU">
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