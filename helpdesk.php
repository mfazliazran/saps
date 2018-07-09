<script type="text/javascript">
	function check_input(){
		var notelefon = document.getElementById("inpNoTelefon");
		if(notelefon.value==""){
			alert("Sila Masukkan no telefon");
			notelefon.focus();
			return false;
		}

		return true;
	}
</script>
<?php
session_start();
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';
// include 'fungsi.php';
//include("FCKEditor/fckeditor.php") ; 

$sqlhelpdesk = "SELECT no_telefon, no_telefon2, hari1, hari2, waktu1, waktu2, rehat_biasa1, rehat_biasa2, rehat_jumaat1, rehat_jumaat2 FROM helpdesk";
                // echo $sqlhelpdesk;
$reshelpdesk = oci_parse($conn_sispa,$sqlhelpdesk);
$exec = oci_execute($reshelpdesk);
// if (!$exec) {
//     $e = oci_error($reshelpdesk);  // For oci_execute errors pass the statement handle
//     print htmlentities($e['message']);
//     print "\n<pre>\n";
//     print htmlentities($e['sqltext']);
//     printf("\n%".($e['offset']+1)."s", "^");
//     print  "\n</pre>\n";
// } 

if($datahelpdesk = oci_fetch_array($reshelpdesk)){
    $notelefon1 = $datahelpdesk['NO_TELEFON'];
    // echo $notelefon1;
    $notelefon2 = $datahelpdesk['NO_TELEFON2'];
    $hari1 = $datahelpdesk['HARI1'];
    $hari2 = $datahelpdesk['HARI2'];
    $waktu1 = $datahelpdesk['WAKTU1'];
    $waktu2 = $datahelpdesk['WAKTU2'];
    $rehatbiasa1 = $datahelpdesk['REHAT_BIASA1'];
    $rehatbiasa2 = $datahelpdesk['REHAT_BIASA2'];
    $rehatjumaat1 = $datahelpdesk['REHAT_JUMAAT1'];
    $rehatjumaat2 = $datahelpdesk['REHAT_JUMAAT2'];
    // echo "masuk";
}

?>
<td valign="top" class="rightColumn">
<p class="subHeader">Maklumat Helpdesk</p>
<form action="data-helpdesk.php" method="POST">
	<table width="100%">
		<tr>
			<td width="632" valign="top">
				<table width="650">
					<tr>
						<td width="40">No Telefon <font color="red">*</font></td>
						<td>:</td>
						<td>
							<input type="text" name="inpNoTelefon" id="inpNoTelefon" value="<?php echo $notelefon1;?>">
						</td>
					</tr>
					<tr>
						<td width="90">No Telefon Lain </td>
						<td>:</td>
						<td>
							<input type="text" name="inpNoTelefon2" value="<?php echo $notelefon2;?>">
						</td>
					</tr>
					<tr>
						<td>Waktu Operasi</td>
						<td>:</td>
						<td><input type="text" name="inpHari1" value="Isnin" "> <br /> <input type="text" name="inpHari2" value="Jumaat"></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><input type="text" name="inpWaktu1" value="9:00"> Pagi <br /> <input type="text" name="inpWaktu2" value="5:30"> Petang</td>
					</tr>
					<tr>
						<td>Waktu Rehat (hari biasa)</td>
						<td>:</td>
						<td><input type="text" name="inpRehat1" value="1:00"> Tengah Hari <br /> <input type="text" name="inpRehat2" value="2:00"> Petang</td>
					</tr>
					<tr>
						<td>Waktu Rehat (hari Jumaat)</td>
						<td>:</td>
						<td><input type="text" name="inpRehatJumaat1" value="12:15"> Tengah Hari <br /> <input type="text" name="inpRehatJumaat2" value="2:45"> Petang</td>
					</tr>
					<tr>
						<td colspan="3">
							<input type="image" height="35" width="35" src="images/send.png" name="hantar_helpdesk" value="Hantar" onclick="return check_input();">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
</td>
<?php include 'kaki.php'; ?>