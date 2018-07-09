<?php
session_start();
//include 'menu.php';
include 'config.php';
//echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../tulisexam.css\">";
$tahun = $_POST["tahun"];
$kodsek = $_POST["kodsek"];
$nokp = $_POST["nokp"];
$markah = $_POST["markah"];
$ting = $_POST["ting"];
$kelas = $_POST["kelas"];
$mp = $_POST["mp"];
$jpep = $_POST["jpep"];
$bil = $_POST["bilpel"];
$tg=strtolower($ting); //utk capai dalam database utk SQL

if ($_SESSION['statussek']=="SM"){
	$tmarkah="markah_pelajar";
	$theadcount="headcount";
	$tmurid="tmurid";
	//$tmp="mpsmkc";
	$tahap="TING";
}

if ($_SESSION['statussek']=="SR"){
	$tmarkah="markah_pelajarsr";
	$theadcount="headcountsr";
	$tmurid="tmuridsr";
	//$tmp="mpsr";
	$tahap="DARJAH";
}

switch ($ting)
{
	case "D1": case "D2" : case "D3": case "D4" :case "D5" : case "D6" :
		$level="SR";
		break;
	case "P" : case "T1": case "T2": case "T3":
		$level="MR";
		break;
	case "T4": case "T5":
		$level="MA";
		break;

}
$key=0;
if (is_array($nokp))
{
    $buff='';
	$bilpelajar=$bil;
	while ($bil>0)
	{
		$item_qty = $nokp[$key];
		$marks = $markah[$key];
		$bil--; $key++;
		$buff=$buff.$item_qty."|".$marks."|";
		
	}
  //die($buff);
  //if($kodsek=='BBC0042')
  	//die($level);
    if ($level=="SR" or $level=="SN"){
	   $sql="begin insert_markah_sr('$level','$kodsek','$tahun','$jpep','$ting','$kelas','$mp',$bilpelajar,'$buff'); end;";
	}
	else
	   $sql="begin insert_markah('$kodsek','$tahun','$jpep','$ting','$kelas','$mp',$bilpelajar,'$buff'); end;";
	  // if($kodsek=='AEA2055' and $kelas=='AMANAH')
	   		//die($sql);
	//die($sql);
	$stmt=oci_parse($conn_sispa,$sql);
	oci_execute($stmt);
	?>
<script>alert('MARKAH TELAH DISIMPAN')
location.href='papar_subjek.php';
</script>
<?php
	
	
}

?>