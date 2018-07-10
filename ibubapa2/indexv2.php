<script type="text/javascript" src="../ajax/ajax_carianv2.js"></script>
<script type="text/javascript" src="../ajax/ajax_carisekolahv2.js"></script>
<script type="text/javascript" language="javascript">
function disabledform(){
	document.getElementById("txtNegeri").readOnly = true;
	document.getElementById("txtDaerah").readOnly = true;
	document.getElementById("txtSekolah").disabled = true;
	document.getElementById("txtKodSekolah").disabled = true;
	document.getElementById("txtCariSekolah").disabled = true;
}
</script>
<?php
session_start();
unset($_SESSION["nokp"]);
unset($_SESSION["kodsek_ib"]);
unset($_SESSION["namamurid"]);

include("../include/kirapelawatibubapa.php");

echo "<script> disabledform(); </script>"; 
?>
<body onload="document.frmlogin.reset();">
<title>Sistem Analisis Peperiksaan Sekolah</title>
<link rel="icon" href="//mail.google.com/favicon.ico">
<link href="../include/kpm.css" type="text/css" rel="stylesheet" />
  <table id="Table_01" style="margin-left: auto; margin-right: auto; width: 900px;" border="0" cellpadding="0" cellspacing="0">
      <tr style="height: 129px;">
        <td style="width: 661px; height: 129px;" bgcolor="#003333"><img src="banner_baru.gif" alt="" height="129" width="900" /></td>
      </tr>
      <tr style="background-color: rgb(153, 153, 153); height: 25px;">
        <td colspan="2" style="height: 25px;"><div style="text-align:center;"> <span id="lblWARTA" style="font-family:Verdana, Arial, Helvetica, sans-serif; color: #000000; font-size: 14px; font-weight: bold;">SEMAK SLIP PEPERIKSAAN PELAJAR</span>.</div></td>
      </tr>
      <tr style="background-color: rgb(204, 204, 204); height: 100%;">
        <td colspan="2"><table style="margin-left: auto; margin-right: auto;" id="TABLE2" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <td align="left"><div id="div_carianmurid" style="text-align:left;">
                <table width="100%" border="0" align="left" cellpadding="0" cellspacing="5">
                  <tr>
                    <td colspan="4" valign="top"><span style="text-align:Left;"><span id="Label4" style="color:#000000;">SILA HUBUNGI PIHAK SEKOLAH UNTUK MENGETAHUI TARIKH SEMAKAN KEPUTUSAN PEPERIKSAAN</span></span></td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="top"><u>LANGKAH 1 : CARIAN MURID</u></td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="top"><span id="Label1" style="color:#000000;">SILA MASUKKAN</span> <span id="Label2" style="color:#000066;">NOMBOR SIJIL LAHIR / KAD PENGENALAN PELAJAR </span> <span id="Label3" style="color:Red;">(TANPA &quot;-&quot; atau &quot;SPACE&quot;)</span></td>
                  </tr>
                  <tr>
                    <td valign="top" width="20%"><strong>No KP / Sijil Lahir</strong></td>
                    <td valign="top" width="1%"><strong>:</strong></td>
                    <td valign="top" width="15%"><input autocomplete=off name="txtIC" id="txtIC" maxlength="12" type="text" style="text-transform:uppercase" /></td>
                    <td valign="top" width="64%"><input name="Cari" value="Cari" id="Cari" type="submit" onClick="return semak(document.getElementById('txtIC').value);" /></td>
                  </tr>
                </table>
			  </div></td>
            </tr>
             <tr>
              <td><div id="error_msg" style="text-align:center; display:none"><font color="#FF0000">NO. KP / SIJIL LAHIR PELAJAR TIADA DALAM PANGKALAN DATA</font></div></td>
            </tr>
            <tr>
              <td align="left"><div id="div_cariansekolah" style="text-align:left; display:block;">
              <form name="frmlogin" method="post" action="semak.php">
                <table width="100%" border="0" align="left" cellpadding="0" cellspacing="5">
                  <tr>
                    <td colspan="4" valign="top"><u>LANGKAH 2 : CARIAN SEKOLAH</u></td>
                  </tr>
                  <tr>
                    <td colspan="4" valign="top"><span id="Label1" style="color:#000000;">SILA MASUKKAN MAKLUMAT SEKOLAH PELAJAR, SEKOLAH TERKINI ATAU SEBELUM INI</span></td>
                  </tr>
                  <tr>
                    <td width="20%" valign="top"><strong>Negeri</strong></td>
                    <td width="1%" valign="top"><strong>:</strong></td>
                    <td width="79%" valign="top">
                    <?php
					echo "<select name=\"txtNegeri\" id=\"txtNegeri\" disabled onChange=\"senarai_PPD(this.value);\">";	
					echo "<option value=''>-PILIH NEGERI-</option>";		
					$sql = "SELECT NEGERI, KODNEGERI FROM tknegeri order by Negeri";
					$qic = oci_parse($conn_sispa,$sql);
					oci_execute($qic);
					while($row = oci_fetch_array($qic)){
						$negeri = $row["NEGERI"];
						$kodnegeri = $row["KODNEGERI"];
						
						echo "<option value=\"$kodnegeri\">$kodnegeri - $negeri</option>";
					}
					echo "</select>";
					?>
                    </td>
                    <td valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>Daerah</strong></td>
                    <td valign="top"><strong>:</strong></td>
                    <td valign="top" colspan="2"><?php
	           		echo "<select name='txtDaerah' id='txtDaerah' disabled onChange=\"senarai_Sekolah(document.getElementById('txtNegeri').value, this.value);\"><option value=''>-PILIH DAERAH-</option>";
					?></td>
                  </tr>
                  <tr>
                    <td valign="top"><strong>Nama Sekolah</strong></td>
                    <td valign="top"><strong>:</strong></td>
                    <td valign="top" colspan="2"><div id="divSekolah"><?php
            		echo "<select name='txtSekolah' id='txtSekolah' disabled><option value=''>-PILIH SEKOLAH-</option>"; ?></div></td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td valign="top" align="left"><input disabled name="Semak" value="Semak" id="Semak" type="button" onClick="return semaksekolah(document.getElementById('txtIC').value, document.getElementById('txtSekolah').value);" /></td>
                    <td valign="top">&nbsp;</td>
                  </tr>
                  <tr>
              <td colspan="4"><div id="error_msg2" style="text-align:center; display:none"><font color="#FF0000">NO. KP / SIJIL LAHIR PELAJAR TIADA DALAM SEKOLAH YANG DIPILIH</font></div></td>
            </tr>                  
                </table></form>
			  </div></td>
            </tr>
        </table></td>
      </tr>
      <tr style="height: 50px; background-color: rgb(204, 204, 204);">
      
        <td colspan="2" style="height: 19px; text-align: center;"><hr />
          <hr /></td>
      </tr>
  </table>