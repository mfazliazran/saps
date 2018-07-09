<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php'; 
?>
<td valign="top" class="rightColumn">
<?php //include 'sistem.php';?> 
<script src="ckeditor/ckeditor.js"></script>
<p class="subHeader">Pengumuman</p>
<?php
echo "<br><table width=\"80%\" align=\"center\" border=\"0\" bordercolor=\"#999999\" cellpadding=\"5\" cellspacing=\"0\" >\n";
echo "<tr><td align=\"right\"><input type=\"button\" name=\"tambah\" id=\"tambah\" value=\"TAMBAH\" onclick=\"location.href='b-edit-umum.php';\"/></td></tr>";
echo "<tr><td><table width=\"100%\" align=\"center\" border=\"1\" bordercolor=\"#999999\" cellpadding=\"5\" cellspacing=\"0\" >\n";
echo "  <tr align=\"center\" height=\"30\" bgcolor=\"#FF9933\">\n";
echo "    <td width=\"70%\">Aktiviti</td>\n";
echo "    <td width=\"10%\">Status</td>\n";
echo "    <td width=\"5%\">Susunan</td>\n";
echo "    <td width=\"5%\">Tindakan</td>\n";
echo "  </tr>\n";
$q_umum=oci_parse($conn_sispa,"SELECT * FROM umum ORDER BY penting desc, susunan asc");
oci_execute($q_umum);
while($row=oci_fetch_array($q_umum)){
	$id=$row['ID'];
	$tarikh=$row['TARIKH'];
	$umum=$row['UMUM'];
	$susun = $row['SUSUNAN'];
	$status=$row['PENTING'];
	if($status=="1"){
		$statusumum = "AKTIF";	
	}else{
		$statusumum = "TIDAK AKTIF";
	}
	echo "<tr><td>$umum</td>";
	echo "<td>$statusumum</td>";
	echo "<td>$susun</td>";
	echo "<td><a href='b-edit-umum.php?id=$id'><img src=images/edit.png border=0></a>&nbsp;&nbsp; <a href='hapus-umum.php?id=$id' onclick=\"return (confirm('Adakah anda pasti hapus data?'))\"><img src=images/drop.png border=0></a></td></tr>";
}
echo "</table></td></tr></table>";
?>

</td></tr>
</td>
<?php include 'kaki.php'; ?>
