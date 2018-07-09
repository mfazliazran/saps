<style type="text/css">
.titleruangdiv {
	background-color: #EEEEEE;
	border-top: solid 1px #CCCCCC;
	border-left: solid 1px #CCCCCC;
	border-right: solid 1px #CCCCCC;
	float:left;
	width:600px;
}
.ruangdiv {
	background-color: #EEEEEE;
	border-bottom: solid 1px #CCCCCC;
	border-left: solid 1px #CCCCCC;
	border-right: solid 1px #CCCCCC;
	float:left;
	width:600px;
	overflow-y: auto;
	height: 200px;
}
.ruangdiv ul{
		list-style-type: none;
		position: relative;
		padding: 5px;
		color: #000000;
}
	
ruangdiv.li:hover{
	background-color: #E0E0E0;
}
.close { float:right }
</style>

<?php
	include "../config.php";
	session_start();
	$kodnegeri=$_SESSION["negeri"];	
	//echo $kodnegeri;
	$namasek=strtoupper($_GET["namasek"]);
	if($kodnegeri=="")
		$kodnegeri="all";
	if($kodnegeri != ""){	
?>  
    <div class="ruangdiv">
	<ul>
		<?php
		    if($kod=="all" or $_SESSION["level"]==7)
			   $query = "SELECT KODSEK, NAMASEK, ALAMATSURAT, BANDARSURAT, POSKODSURAT, NEGERI, NOTELEFON FROM TSEKOLAH WHERE NAMASEK LIKE '%$namasek%' ORDER BY NAMASEK";
		    else
			   $query = "SELECT KODSEK, NAMASEK, ALAMATSURAT, BANDARSURAT, POSKODSURAT, NEGERI, NOTELEFON FROM TSEKOLAH WHERE KODNEGERIJPN='$kodnegeri' and NAMASEK LIKE '%$namasek%' ORDER BY NAMASEK";
			//echo $query;
			$result = oci_parse($conn_sispa,$query);
			oci_execute($result);
			$cnt=0;
			while($data=oci_fetch_array($result)){ 
			  $kodsek = $data["KODSEK"];
			  $_SESSION["kodsek_ib"] = $kodsek;
			  $name = str_replace("'","\'",$data["NAMASEK"]);
			  //$_SESSION["namasek"] = $name;
			  $alamat = $data["ALAMATSURAT"];
			  $bandar = $data["BANDARSURAT"];
			  $poskod = $data["POSKODSURAT"];
			  $negeri = $data["NEGERI"];
			  $notelefon = $data["NOTELEFON"];
			  $_SESSION["notelefon"] = $notelefon;
			  $alamat = str_replace("","\'","$alamat, $poskod $bandar, $negeri");
			  $cnt++;
			  echo "<li><a href=\"javascript:void(0)\" onClick=\"selectSekolah('$kodsek','$name');\">($kodsek) $name</a></li>";// \n$alamat
			}
			if ($cnt==0){
				echo "Tiada rekod.";
			}
		?>
	</ul>
	</div>
<?php
	}
?>