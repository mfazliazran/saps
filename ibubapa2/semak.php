<script type="text/javascript" src="../ajax/ajax_ibpelajar.js"></script>
<?php
session_start();
include "../input_validation.php";
$nokp = validate($_SESSION["nokp"]);
$kodsek = validate($_SESSION["kodsek_ib"]);
$namamurid = validate($_SESSION["namamurid"]);
$namasek = validate($_SESSION["namasek"]);
$notelefon = validate($_SESSION["notelefon"]);
$tahun_semasa = validate($_POST['tahun']);

if($tahun_semasa <> "") {
	$tahun = $tahun_semasa;
} else {
	$tahun = date("Y");
}

$_SESSION['tahun'] = $tahun;

$tahun_sekarang = date("Y");
?>

<script type="text/javascript" src="../ajax/ajax_carianv2.js"></script>
<script type="text/javascript" src="../ajax/ajax_carisekolahv2.js"></script>
<script type="text/javascript" language="javascript">
function disabledform(){
	document.getElementById("txtNegeri").readOnly = true;
	document.getElementById("txtSekolah").disabled = true;
	document.getElementById("txtCariSekolah").disabled = true;
}
function submitform(flg,url)
{
	if(flg=='analisis')
		document.form7.action=url;
	else{
		document.form7.action=url;		
	}
    document.form7.submit();
}
</script>
<?php
echo "<script> disabledform(); </script>"; 
?>
<title>Sistem Analisis Peperiksaan Sekolah</title>
<link rel="icon" href="//mail.google.com/favicon.ico">
<link href="../include/kpm.css" type="text/css" rel="stylesheet" />
  <table id="Table_01" style="margin-left: auto; margin-right: auto; width: 1000px;" border="0" cellpadding="0" cellspacing="0">
      <tr style="height: 129px;">
        <td style="width: 661px; height: 129px;" bgcolor="#003333"><img src="banner_baru.gif" alt="" height="129" width="900" /></td>
      </tr>
      <tr style="background-color: rgb(153, 153, 153); height: 25px;">
        <td colspan="2" style="height: 25px;"><div style="text-align:center;"> <span id="lblWARTA" style="font-family:Verdana, Arial, Helvetica, sans-serif; color: #000000; font-size: 14px; font-weight: bold;">SEMAK SLIP PEPERIKSAAN PELAJAR</span></div></td>
      </tr>
      <tr style="background-color: rgb(153, 153, 153);">
        <td colspan="2" style="height: 25px;"><div style="text-align:center;"> <span style="font-family:Verdana, Arial, Helvetica, sans-serif; color: #000000; font-size: 14px; font-weight: bold;"><a href="indexv2.php">MENU UTAMA</a></span></div></td>
    </tr>
      <tr style="background-color: rgb(204, 204, 204); height: 100%;">
        <td colspan="2"><table style="margin-left: auto; margin-right: auto;" id="TABLE2" border="0" cellpadding="0" cellspacing="0" width="100%">
             <tr>
              <td><div id="error_msg" style="text-align:center; display:none"><font color="#FF0000">NO. KP / SIJIL LAHIR PELAJAR TIADA DALAM PANGKALAN DATA</font></div></td>
            </tr>
            <tr>
              <td align="left"><div id="div_cariansekolah" style="text-align:left; display:block;">
                <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">**Senarai guru berdasarkan peperiksaan terakhir untuk tahun terkini</td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>NO. KP / SIJIL LAHIR</strong></td>
                    <td valign="top"><strong>:</strong></td>
                    <td valign="top"><?php echo $nokp;?></td>
                    <td valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>NAMA MURID</strong></td>
                    <td valign="top"><strong>:</strong></td>
                    <td valign="top"><?php echo $namamurid;?></td>
                    <td width="37%" rowspan="18" valign="top"><div id="gurump"></div></td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>TAHUN</strong></td>
                    <td valign="top"><strong>:</strong></td>
                    <td valign="top"><select autocomplete="off" name="tahun_semasa" id="tahun_semasa" value="" onChange="get_details(this.value);">
                      <option value="">-- Pilih Tahun --</option>
                      <?php
						for($thn = 2011; $thn <= $tahun_sekarang; $thn++ ){
							if($tahun_semasa == $thn){
								echo "<option value='$thn' selected>$thn</option>";
							} else {
								echo "<option value='$thn'>$thn</option>";
							}
						}			
						?>
                    </select></td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top" width="25%"><strong>NAMA SEKOLAH</strong></td>
                    <td valign="top" width="1%"><strong>:</strong></td>
                    <td width="37%" valign="top"><div id="kodseksemasa"></div></td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>TAHUN / TINGKATAN</strong></td>
                    <td valign="top"><strong>:</strong></td>
                    <td valign="top"><div id="tahunting"></div><label id="lbltahunting" name="lbltahunting"></label> </td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>KELAS</strong></td>
                    <td valign="top"><strong>:</strong></td>
                    <td valign="top" align="left"><div id="kelas"></div></td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>KEPUTUSAN PEPERIKSAAN</strong></td>
                    <td valign="top"><strong>:</strong></td>
                    <td valign="top" align="left"><div id="keputusan_pep"></div></td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>ANALISIS MARKAH</strong></td>
                    <td valign="top"><strong>:</strong></td>
                    <td valign="top" align="left"><div id="btnpapar" style="display:none"></div></td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left">&nbsp;</td>
                  </tr>                  
                </table>
			  </div></td>
            </tr>
        </table></td>
      </tr>
      <tr style="height: 50px; background-color: rgb(204, 204, 204);">
      
        <td colspan="2" style="height: 19px; text-align: center;"><hr />
          <hr /></td>
      </tr>
  </table>