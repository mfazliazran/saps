<?php
include 'auth.php';
include 'config.php';
include 'kepala.php';
include 'menu.php';

?>

<td valign="top" class="rightColumn">
<p class="subHeader">Masuk Markah Peperiksaan (Store Procedure)</p>

<script language="JavaScript">
function semak_markah(obj)
{
   markah=obj.value;
   if (markah=="")
     return false;
	 
   if (IsNumeric(markah)){
	 v=parseInt(markah);
	 if (v<0 || v > 100){
		alert("Sila masukkan markah 0 hingga 100 sahaja"); 
		obj.value="";
		obj.focus();
		return false;
	 }	  
   }
	   if (!IsNumeric(markah) && markah!="TH" && markah!=''){
	   alert("Sila masukkan nombor atau TH (Tidak Hadir) atau biarkan kosong !");
	   obj.value="";
	   obj.focus();
	   return false;
   }
}

function IsNumeric(strString)
   //  check for valid numeric strings	
   {
   var strValidChars = "0123456789";
   var strChar;
   var blnResult = true;

   if (strString.length == 0) return false;
   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && blnResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         blnResult = false;
         }
      }
   return blnResult;
   }

var bgcolor = "#FFCC66";
var change_color = "#ffffff"
function mover(aa) {
 aa.style.backgroundColor = change_color;
}
function mout(aa) {
 aa.style.backgroundColor = bgcolor;
}
</script>
<?php
$m=$_GET['data'];
list ($kelas, $ting, $kod, $tahun, $kodsek,$jpep)=split('[|]', $m);
//$jpep = $_SERVER["jpep"];
//$kelas = htmlentities($kelas);
$tg=strtolower($ting); //utk capai dalam database utk SQL
//if($kodsek=='JEA7040'){
	//die('MASUK');
//}
if ($_SESSION['statussek']=="SM"){
	$tmarkah="markah_pelajar";
	$tmurid="tmurid";
	$tmp="mpsmkc";
	$tahap="ting";
}

if ($_SESSION['statussek']=="SR"){
	$tmarkah="markah_pelajarsr";
	$tmurid="tmuridsr";
	$tmp="mpsr";
	$tahap="darjah";
}

	$gmp="G$kod";
	switch ($kod) 
	{
		//Pend Islam, Pelajaran Jawi
		//Bahasa Arab  case "BA":
		case "PI": case "PW":
		{
			$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND tahun='$tahun' AND agama='01' AND $kod is not null ORDER BY nama";
			break;
		}
		////Pend Moral 
		//& Bahasa Iban SR  case "BISR": (disabled 30/10/2015) Tiket Number : SAPS0632
		case "PM":
		{
			$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND tahun='$tahun' AND agama='02' AND $kod is not null ORDER BY nama";
			break;
		}
			
		case "PIMA":
			$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND tahun='$tahun' AND agama='01' AND $kod is not null ORDER BY nama";
			break;

		case "PMMA":
			$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND tahun='$tahun' AND agama='02' AND $kod is not null ORDER BY nama";
			break;
/*
		case "BT":

			$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND kaum='03' AND $kod!=' ' ORDER BY nama";

			break;
*/
		default :
			$querymark="SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND tahun='$tahun' AND $kod is not null ORDER BY nama";
			break;
	}
	//if($kodsek=='JEA7040')
		//echo $querymark;
	$resultmark = oci_parse($conn_sispa,$querymark);
	oci_execute($resultmark);

	//$semakmark=count_row("SELECT nokp, nama, $kod, $gmp FROM $tmarkah WHERE tahun='$tahun' AND kodsek='$kodsek' AND $tahap='$ting' AND kelas='$kelas' AND jpep='$jpep' AND $kod!=' ' ORDER BY nama");

	
    $semakmark=0;
	if ($semakmark==0)
	{
		switch ($kod)
		{
			/*case "KH1":

				$query = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$ting='$kelas' AND jantina='L' ORDER BY namap";

				break;

			case "KH2":

				$query = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$ting='$kelas' AND jantina='P' ORDER BY namap";

				break;		*/
			//Pend Islam, Pelajaran Jawi
			//Bahasa Arab case "BA":
			case "PI": case "PW": 
			{
				$query = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND $tg='$ting' AND kelas$ting='$kelas' AND tahun$tg='$tahun' AND agama='01' and kodsek_semasa='$kodsek' ORDER BY namap";
				//if($kodsek=='TBA5003')
				//echo $query;
				break;
			}
			//Pend Moral & Bahasa Iban SR
			// case "BISR": (disabled 30/10/2015) Tiket Number : SAPS0632
			case "PM":
			{
				$query = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND $tg='$ting' AND kelas$ting='$kelas' AND tahun$tg='$tahun' AND agama!='01' and kodsek_semasa='$kodsek' ORDER BY namap";
				break;
			}
				
			case "PIMA":
				$query = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND $tg='$ting' AND kelas$ting='$kelas' AND tahun$tg='$tahun' AND agama='01' and kodsek_semasa='$kodsek' ORDER BY namap";
				break;

			case "PMMA":
				$query = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND $tg='$ting' AND kelas$ting='$kelas' AND tahun$tg='$tahun' AND agama!='01' and kodsek_semasa='$kodsek' ORDER BY namap";
				break;

		/*	case "BC":

				$query = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$ting='$kelas' AND kaum='02' ORDER BY namap";

				break;	

			case "BT":

				$query = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND tahun$tg='$tahun' AND $tg='$ting' AND kelas$ting='$kelas' AND kaum='03' ORDER BY namap";

				break;
*/
			default:
				$query = "SELECT * FROM $tmurid WHERE kodsek$tg='$kodsek' AND $tg='$ting' AND kelas$ting='$kelas' AND tahun$tg='$tahun' and kodsek_semasa='$kodsek' ORDER BY namap";
				break;
		}//SWITCH
		//if($kodsek=='JEA7040'){
			//echo $query;
		//}
		$result = oci_parse($conn_sispa,$query);
		oci_execute($result);

		borang($result, $ting, $kelas, $kod, $tahun, $kodsek, $jpep, $namapep, $tmp,$tmarkah,$tahap,$tmurid);

	}//if semakmark
	else
	{
	/////lihat markah start
	//echo "<th width=\"79%\" bgcolor=\"#FFFFFF\" align=\"center\" valign=\"top\" scope=\"col\">";
	$querykod = "SELECT * FROM $tmp WHERE kod='$kod'";
	$resultnamamp=oci_parse($conn_sispa,$querykod);
	oci_execute($resultnamamp);
	$resultkod = oci_fetch_array($resultnamamp);
	$namamp=$resultkod['MP'];	

		echo "<br><br>";
		echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"4\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"CCCCCC\">\n";
		echo "    <th scope=\"col\"><center>KELAS</center></th>\n";
		echo "    <th scope=\"col\"><center>MATA PELAJARAN </center></th>\n";
		echo "    <th scope=\"col\"><center>JENIS UJIAN </center></th>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td><center>$ting $kelas</center></td>\n";
		echo "    <td><center>$namamp</center></td>\n";
		echo "    <td><center>".jpep("".$_SESSION['jpep']."")."</center></td>\n";//
		echo "  </tr>\n";
		echo "</table>\n";
		echo "<br>";

		echo "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"5\" cellspacing=\"0\">\n";
		echo "  <tr bgcolor=\"CCCCCC\">\n";
		echo "    <th scope=\"col\">BIL</th>\n";
		echo "    <th scope=\"col\">NOKP</th>\n";
		echo "    <th scope=\"col\">NAMA</th>\n";
		echo "    <th scope=\"col\">MARKAH</th>\n";
		echo "    <th scope=\"col\">GRED</th>\n";
		echo "  </tr>\n";

		$bil=0;
		while ($row = oci_fetch_array($resultmark))
		{
			//echo "".$row['nokp']." ".$row['nama']." ".$row[7]." ".$row[8]."<br>";
			$nokp = $row["NOKP"];
			$nama = $row["NAMA"];
			$mp = $row["$kod"];
			$gred = $row[$gmp];
			$bil=$bil+1;
			
			echo "  <tr>\n";
			echo "    <td><center>$bil</center></td>\n";
			echo "    <td>$nokp</td>\n";
			echo "    <td>$nama</td>\n";
			echo "    <td><center>$mp</center></td>\n";
			echo "    <td><center>$gred</center></td>\n";
		}
	//////lihat markah end
}
echo "</th>\n";
echo "</tr>\n";
echo "</table></body>\n";

//fungsi borang markah

function borang($result, $ting, $kelas, $kod, $year, $kodsek, $jenispep, $namapep, $tmp,$tmarkah,$tahap,$tmurid)

{	
global $conn_sispa;

	$order_form = ""; /* Will contain product form data */

	$i = 0;

	//include 'config.php';

	$querykod = "SELECT * FROM $tmp WHERE kod='$kod'";
	
	$resultnamamp=oci_parse($conn_sispa,$querykod);
	oci_execute($resultnamamp);

	$resultkod = oci_fetch_array($resultnamamp);

	$namamp=$resultkod['MP'];

	//$kod=$resultkod['kod'];


	//echo "<th width=\"80%\" bgcolor=\"#CCCCFF\" align=\"center\" valign=\"top\" scope=\"col\">";

	//session_start(); 

	echo "<form name=\"form1\" method=\"post\" action=\"masuk_markah.php\">\n";

	echo "<center><h3>MARKAH MURID TAHUN $year</h3></center><br>";

	echo "<input name=\"kodsek\" type=\"hidden\" id=\"kodsek\" value=\"$kodsek\">\n";

	echo "<input name=\"tahun\" type=\"hidden\" id=\"tahun\" value=\"$year\">\n";

	echo "<input name=\"ting\" type=\"hidden\" id=\"ting\" value=\"$ting\">\n";

	echo "<input name=\"kelas\" type=\"hidden\" id=\"kelas\" value=\"$kelas\">\n";

	echo "<input name=\"mp\" type=\"hidden\" id=\"mp\" value=\"$kod\">\n";

	echo "<input name=\"jpep\" type=\"hidden\" id=\"jpep\" value=\"$jenispep\">\n";                                                   

	echo "<table width=\"70%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"#cccccc\">\n";

	echo "<tr height=\"30\" bgcolor=\"#FFCC66\">\n";

	echo "    <th width=\"33%\" scope=\"col\">KELAS</th>\n";

	echo "    <th width=\"33%\" scope=\"col\">MATAPELAJARAN</th>\n";

	echo "    <th width=\"33%\" scope=\"col\">JENIS PEPERIKSAAN</th>\n";

	echo "  </tr>\n";

	echo "  <tr height=\"30\">\n";

	echo "    <th scope=\"col\">$ting $kelas</th>\n";

	echo "    <th scope=\"col\">$namamp</th>\n";

	echo "    <th scope=\"col\">";

	echo " ".jpep("".$_SESSION['jpep']."")."";

	echo " </th>\n";

	echo "  </tr>\n";

	echo " </table>\n";

$sqlsemak = "select distinct count(NOKP) as us,NOKP from $tmurid where kodsek$ting='".$_SESSION["kodsek"]."' and tahun$ting='".$_SESSION["tahun"]."' group by NOKP having count(NOKP) > 1";
$ressemak=oci_parse($conn_sispa,$sqlsemak);
oci_execute($ressemak);
while($rowsemak = oci_fetch_array($ressemak)){
	$nokpdouble=$rowsemak['NOKP'];
	echo "<center><b><font color='#FF0000'><br>NO KP/ NO SIJIL LAHIR $nokpdouble telah digunakan oleh lebih dari seorang pelajar. <br>Sila maklumkan kepada SUP untuk pengemaskinian data pelajar di APDM.<br></font></b></center>";
	$sqld = "Select nokp, namap, KELAS$ting from $tmurid where nokp='$nokpdouble'";
	$resd=oci_parse($conn_sispa,$sqld);
	oci_execute($resd);
	while($rowd = oci_fetch_array($resd)){
		$nokpd = $rowd["NOKP"];
		$nama = $rowd["NAMAP"];
		$kelasd = $rowd["KELAS$ting"];
		echo "<center><b><font color='#FF0000'><br>No. KP :- $nokpd Nama :- $nama [$kelasd]<br></font></b></center>";
	}
}
	print '<br>';

	print '<table width="70%" border="1" align="center" cellpadding="5" cellspacing="0" bordercolor="#ffffff">';

	print '<tr height=\"30\" bgcolor="#FFCC66">';

	print '<td width="4%"><div align="left" class="style1"><strong><center>BIL</center></strong></div></td>';

	print '<td width="10%"><div align="left" class="style1"><strong><center>NO KP</center></strong></div></td>';

	print '<td width="30%"><div align="left" class="style1"><strong><center>NAMA</center></strong></div></td>';

	print '<td width="5%"><div align="left" class="style1"><strong><center>MARKAH</center></strong></div></td>';

	print '</tr>';

	

	$bil=0; $tab=0;

	while($row = oci_fetch_array($result))

	{
		// Loop through the results from the MySQL query.
		$nokp = stripslashes($row["NOKP"]);
		$nama = oci_escape_string($row["NAMAP"]);
		$jantina = $row["JANTINA"];
		$kaum = $row["KAUM"];
		$agama = $row["AGAMA"];
       	$sqlmark="select $kod from $tmarkah where nokp='$nokp' and kodsek='$kodsek' and $tahap='$ting' and kelas='$kelas' and tahun='$year' and jpep='$jenispep'";
	   $stmark=oci_parse($conn_sispa,$sqlmark);	  
       oci_execute($stmark);
	   $datamark=oci_fetch_array($stmark);
	   $markah=$datamark["$kod"];

		$bil=$bil+1;

		print "<tr onmouseover=\"mover(this);\"  onmouseout=\"mout(this);\" bgcolor=\"#FFCC66\">";

		print "<td><center><strong>$bil</strong></center></td>";

		print "<td>$nokp<input name=\"nokp[$i]\" type=\"hidden\" readonly value=\"$nokp\" size=\"15\"></td>";
         
		print "<td>$nama</td>"; //<input name=\"nama[$i]\" type=\"hidden\" readonly value=\"$nama\" size=\"50\"><input name=\"jantina[$i]\" type=\"hidden\" readonly value=\"$jantina\" size=\"2\"><input name=\"kaum[$i]\" type=\"hidden\" readonly value=\"$kaum\" size=\"1\"><input name=\"agama[$i]\" type=\"hidden\" readonly value=\"$agama\" size=\"1\"></td>";

		print "<td><center>";
		print "<input name=\"markah[$i]\" type=\"text\"  value=\"$markah\" maxlength=\"3\" tabindex=\"4+$tab\" size=\"1\" onBlur=\"semak_markah(this);\" onkeyup=\"this.value=this.value.toUpperCase();\"
		><center></td>";

		print "</tr>";

		$i++; $tab=$tab+4;

	}
	print "<input name=\"bilpel\" type=\"hidden\" readonly value=\"$bil\" size=\"3\">";

	print "</table>";
	print "<br><br>";
	print "<center>";

	$tarikhsekarang = date("Y-m-d");
	$sqlbukasekolah = "select * from bukasekolah where tarikh_tutup >= '$tarikhsekarang'";
	$resque=oci_parse($conn_sispa,$sqlbukasekolah);
	oci_execute($resque);
	while($rowan = oci_fetch_array($resque)){
		if ($rowan["KODSEK"] == $_SESSION["kodsek"]){
		$kodsekbuka = "1";
		}
	}

	//if(($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1") or $_SESSION['kodsek']=="JBA5012"){
	if(($_SESSION['tahun'] == date("Y") AND $_SESSION['status_buka_tutup']=="1")){
		print '<input type="submit" name="add" value="SIMPAN REKOD">';
	} else if ($kodsekbuka == "1"){
		print '<input type="submit" name="add" value="SIMPAN REKOD">';
	}
	echo "</form>";

	print "</center>";

}
?>
</td>
<?php include 'kaki.php';?> 

