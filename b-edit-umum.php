<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';

$id=$_GET['id'];
$flg = "add";
$xx = "Tambah Pengumuman";
$q_umum=oci_parse($conn_sispa,"SELECT * FROM umum WHERE id='$id'");
oci_execute($q_umum);
while($rowu=oci_fetch_array($q_umum)){
	$umum=$rowu['UMUM'];
	$statusumum=$rowu['PENTING'];
	$rsusun=$rowu['SUSUNAN'];
	$flg = "edit";
	$xx = "Kemaskini Pengumuman";
}
?>
<td valign="top" class="rightColumn">
<p class="subHeader"><?php echo $xx;?> (<font size="2" color="#FF0000">*</font>Setiap tulisan mestilah di akhiri dengan noktah (<font size="4" color="#FF0000">.</font>))</p>
<script src="ckeditor/ckeditor.js"></script>
  <form name="form1" method="post" action="edit_pengumuman.php">
    <table width="689" height="161" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="689" height="161"><table width="688" border="0">
          <tr>
            <td width="90" height="21"><strong>Pengumuman</strong></td>
            <td width="3">:</td>
            <td width="581"><textarea name="umum" cols="50" rows="15" id="umum"><?php echo "$umum"; ?></textarea>
            <script type="text/javascript">
				// Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'umum');</script>
              &nbsp;</td>
          </tr>
          <tr>
            <td><strong>Status</strong></td>
            <td>:</td>
            <td>
            <select name="status" id="status">
              <?php 
			  echo "<option value=\"\">Pilih Status</option>";
			  echo "<option value=\"1\" "; if($statusumum=="1"){ echo  "selected";} echo ">Aktif</option>";
			  echo "<option value=\"0\" "; if($statusumum=="0"){ echo  "selected";} echo ">Tidak Aktif</option>";
              
			  ?>
            </select>
             </td>
          </tr>
          <?php
		  if($flg=="edit"){
		  ?>
          <tr>
            <td><strong>Susunan</strong></td>
            <td><strong>:</strong></td>
            <td><?php
				$query = oci_parse($conn_sispa,"SELECT susunan FROM umum where penting=1 order by susunan");
				oci_execute($query);
				echo "<select name=\"txt_ordering\">";
				while($row_s=oci_fetch_array($query)){
						$susun=$row_s["SUSUNAN"];
						if ($susun==$rsusun)
						  echo "<option selected value=\"$susun\">$susun</option>";
						else
						  echo "<option value=\"$susun\">$susun</option>";
				}
				echo "</select>";
				?></td>
          </tr>
          <?php }?>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><!--<input name="Submit" type="image" height="35" width="35"value="Hantar" src="images/send.png" align="right">-->
        <input type="submit" name="Submit" value="Hantar">&nbsp;<input type="button" class="button" name="Kembali" value="Kembali" onclick="window.location.href='b_umum.php'" /></td>
          </tr>
        </table></td>
      </tr>
    </table>
    <p>
      <input name="id" type="hidden" id="id" value="<?php echo "$id"; ?>">
      <input name="flg" type="hidden" id="flg" value="<?php echo "$flg"; ?>">
      <input type="hidden" name="txt_old_ordering" id="txt_old_ordering" value="<?php echo $rsusun ?>">
    </p>
    <p align="right">&nbsp;</p>
  </form></center> 
</td>
<td width="85" background="../gambar/isi_05.png">&nbsp;</td>
<?php include 'kaki.php';?>