<script type="text/javascript">
function semak(currentyear)
{
if (document.loginForm.nokp.value==""){
  alert("No KP Guru mesti diisi !");
  document.loginForm.nokp.focus();
  return false;
}  
if (document.loginForm.nama.value==""){
  alert("Nama Guru mesti diisi !");
  document.loginForm.nama.focus();
  return false;
}  
if (document.loginForm.jan.value==""){
  alert("Jantina Guru mesti diisi !");
  document.loginForm.jan.focus();
  return false;
}  


return confirm("Simpan maklumat guru?");
	
}
</script>

<?php 
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Tambah Guru</p>
<?php
$query = "SELECT NEGERI,NAMASEK,KODSEK,STATUS FROM tsekolah WHERE kodsek= :kodsek"; 
$qic = oci_parse($conn_sispa,$query);
oci_bind_by_name($qic, ':kodsek', $kodsek);
oci_execute($qic);
while($row = oci_fetch_array($qic)){
	$negeri = $row['NEGERI'];
	$namasek = $row['NAMASEK'];
	$kodsek = $row['KODSEK'];
	$status = $row['STATUS'];
}
?>	<br><br><blockquote>
                          <form id="loginForm" name="loginForm" method="post" action="data_tambah_guru.php">
                            <table width="500" align="center" cellpadding="0" cellspacing="5">
                              <tr>
                                <td width="167"><div align="left">NOKP</div></td>
                                <td width="316">
                                  <div align="left">
                                    <input name="nokp" type="text" id="nokp" size="15" maxlength="12">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">NAMA</div></td>
                                <td>
                                  <div align="left">
                                    <input maxlength="80" name="nama" type="text" id="nama" onBlur="this.value=this.value.toUpperCase()" size="50">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">JANTINA</div></td>
                                <td><div align="left">
                                  <select name="jan" id="jan">
                                    <option value=""></option>
                                    <option value="L">LELAKI</option>
                                    <option value="P">PEREMPUAN</option>
                                  </select>
                                </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">KOD SEKOLAH </div></td>
                                <td><div align="left">
                                  <div align="left">
                                    <input name="kodsek" type="text" id="kodsek" value="<?php echo"$kodsek"; ?>" readonly size="50">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">DAERAH</div></td>
                                <td>
                                  <div align="left">
                                    <input name="daerah" type="text" id="daerah" value="<?php echo"$daerah"; ?>" readonly size="50">
								    </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">NEGERI</div></td>
                                <td>
                                  <div align="left">
                                    <input name="user" type="text" id="user2" readonly value="<?php echo"$negeri"; ?>"size="50">
                                  </div></td>
                              </tr>
                              <tr>
                                <td><div align="left">NAMA SEKOLAH  </div></td>
                                <td><div align="left">
                                  <input name="namasek" type="text" id="namasek" readonly value="<?php echo"$namasek"; ?>" onBlur="this.value=this.value.toUpperCase()" size="50">
                                </div></td>
                              </tr>
                              <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left">
                                  <input name="level" type="hidden" id="level" value="1">                                
                                </div></td>
                              </tr>
                               <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left">
                                  <input type="submit" name="tambah" value="SIMPAN" onClick="return semak()">
                                </div></td>
                              </tr>
                            </table>
                          </form> </blockquote> 
			
<?php
?>
</td>
<?php include 'kaki.php';?> 