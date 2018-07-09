<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
?>
<td valign="top" class="rightColumn">
<p class="subHeader">Muat Naik Lencana</p>

<script language="JavaScript">
<!--

//  The "refresh" function implementations are identical
//  to our regular "JavaScript-Refresh" example.  The only
//  difference from our JavaScript Refresh example is
//  we do not have a doLoad function that starts our
//  refresh timer (since we use a refresh button).

var sURL = unescape(window.location.pathname);

function refresh()
{
    window.location.href = sURL;
}
//-->
</script>

<script language="JavaScript1.1">
<!--
function refresh()
{
    window.location.replace( sURL );
}
//-->
</script>

<script language="JavaScript1.2">
<!--
function refresh()
{
    window.location.reload( false );
}
//-->
</script>

<?php
$sql="SELECT * FROM login WHERE user1='".$_SESSION['SESS_MEMBER_ID']."'";
$stmt=OCIParse($conn_sispa,$sql);
OCIExecute($stmt);
$bil=count_row($sql); 
if(OCIFetch($stmt)){
	$kodsek = OCIResult($stmt,"KODSEK");
}

if (isset($_POST['hantar']))
{
	$extfile = substr(strrchr($_FILES["file"]["name"],'.'),1);
	$lencana = "$kodsek.$extfile";
	//echo "lencana: $lencana<br>";
	
	$namafail = $_FILES["file"]["name"] ;
	$jfail = $_FILES["file"]["type"] ;
	$saiz = $_FILES["file"]["size"]/1024 ;
	
	echo "<br><br>\n";
	echo "<center><h4>MAKLUMAT FAIL YANG DIUPLOAD</h4></center>";
	echo "<br>";
	echo "<table width=\"255\"  border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#CCCCCC\">\n";
	echo "  <tr>\n";
	echo "<td width=\"6\">Nama</td><td width=\"70\">&nbsp;&nbsp;$namafail</td>";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "<td width=\"6\">Jenis</td><td width=\"70\">&nbsp;&nbsp;$jfail</td>";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "<td width=\"6\">Size</td><td width=\"70\">&nbsp;&nbsp;$saiz Kb</td>";
	echo "  </tr>\n";
	echo "</table>\n";
	echo "<br><br>\n";

	if ((($jfail == "image/jpeg")||($jfail == "image/gif")||($jfail == "image/jpg")||($jfail == "image/png"))&&($saiz < 200000))
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else
			{
				if (file_exists("images/lencana/$lencana"))
				{
					$a = oci_parse($conn_sispa,"UPDATE tsekolah SET lencana='$lencana' WHERE kodsek='$kodsek'");
					oci_execute($a);
					move_uploaded_file($_FILES["file"]["tmp_name"],"images/lencana/$lencana");

					$qlenc = oci_parse($conn_sispa,"SELECT lencana FROM tsekolah WHERE kodsek='$kodsek'");
					oci_execute($qlenc);
					$rowlenc = oci_fetch_array($qlenc);
					$lencana = $rowlenc['LENCANA'];
					//echo "lencana: $lencana<br>";
					?>
					<form method="GET" action="upload_lenc.php">
					  <p><center><input type="button" onclick="refresh()" value="Kemaskini Lencana Sekolah" name="button1"></center></p>
					</form>
					<?php
					echo "<br><center><img src=\"images/lencana/$lencana\" width=\"50\" height=\"53\" ><br></center>";
					echo "<center><br>Klik Butang Kemaskini Lencana Sekolah<br>Sekiranya Lencana Baru tidak Dipaparkan<br>Dan Klik Retry</center>";
	   		    }
				else
					  {
						$b = oci_parse($conn_sispa,"UPDATE tsekolah SET lencana='$lencana' WHERE kodsek='$kodsek'");
						oci_execute($b);
						move_uploaded_file($_FILES["file"]["tmp_name"],"images/lencana/$lencana");
						echo "<br>";
						?><script type='text/javascript'> alert("Upload Lencana Selesai" )</script> <?php
						echo "<center>Lencana Sekolah Telah Di Hantar</center>";
						echo "<br><br>";
						echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ><br><br></center>";
						echo "<center><br>Untuk Menukarkan Lencana Baru<br>Sila Ulang Proses Upload</center>";
					  } // tamat if (file_exists("images/lencana/$lencana"))
		}// if ($_FILES["file"]["error"] > 0)
	}
	else
		  {
			echo "<br>";
			echo "<center>Lencana Sekolah Tak Dapat Diupload<br>Jenis fail Salah</center>";
			echo "<br><br>";
			echo "<center><img src=\"images/lencana/$lencana\"  width=\"50\" height=\"53\" ><br><br></center>";
			echo "<center><br>Sila Pastikan<br>Fail Yang Digunakan Mengikut Spesifikasi</center>";
			?><script type='text/javascript'> alert("Upload Lencana Selesai" )</script> <?php
		  }//	if ((($_FILES["file"]["type"] == "image/jpeg")		
} //if (isset($_POST['hantar']))
else
	{
	$qlenc = oci_parse($conn_sispa,"SELECT lencana FROM tsekolah WHERE kodsek='$kodsek'");
	oci_execute($qlenc);
	$rowlenc = oci_fetch_array($qlenc);
	$lencana = $rowlenc['LENCANA'];
	//echo "lencana: $lencana<br>";
	if ($lencana=="" or $lencana==" " or $lencana==NULL)
		echo "<br><center>Tiada Image Lencana.</center>";
	else
		echo "<br><center><img src=\"images/lencana/$lencana\" width=\"50\" height=\"53\" ></br>Image Lencana ($lencana)</center>";
	
	?>
	<script type='text/javascript' src='/exam/dFilter.js'></script>
	<br><br>
	<h1 class="title"><center>UPLOAD LENCANA SEKOLAH</center></h1>
	<br>
	<table width="500" border="1" align="center" cellpadding="10" cellspacing="0" bordercolor="#666666">
	  <tr>
		<td><p align="left">Cara Upload Lencana Sekolah :-<br>
		  1. Anda mesti mempunyai fail Lencana Sekolah dalam bentuk fail Grafik<br>&nbsp;&nbsp;&nbsp;(gif, png atau jpeg sahaja).<br>
		  2. Pastikan Nama Fail Lencana Sekolah Satu Perkataan Sahaja.<br>
		  3. Saiz fail lencana yang dicadangkan 200kb dan kebawah sahaja.<br>&nbsp;&nbsp;&nbsp;( antara 10kb hingga 200kb ).<br>
		  4. Klik butang browse dan cari fail Lencana Sekolah dalam mana-mana folder.<br>
		  5. Klik butang hantar. </p>      </td>
	  </tr>
	</table>
	<br>
	<form method="post" enctype="multipart/form-data">
	<table width="500" border="2" align="center" cellpadding="10" cellspacing="0" bordercolor="#666666" bgcolor="#CCCCCC">
	  <tr>
		<td width="97">Nama File </td>
		<td width="224"><input type="file" name="file" id="file"></td>
		<td width="59"><input type="submit" name="hantar" value="Hantar"></td>
	  </tr>
	</table>
	</form>
	<?php
	} 

?>

</td>
<?php include 'kaki.php';?> 