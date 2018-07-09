<script type="text/javascript" src="ajax/ajax_pegawai.js"></script>
<?php
include 'auth.php';
include_once('config.php');
include 'kepala.php';
include 'menu.php';

?>
<td valign="top" class="rightColumn">
 <?php if ($_GET["id"]<>"") { ?>
<p class="subHeader">Kemaskini Pegawai Teknikal SAPS</p>
 <?php } else { ?>
<p class="subHeader">Tambah Pegawai Teknikal SAPS</p>
 <?php } ?>
<?php
 global $conn_sispa;

 if ($_POST["post"]=="1"){

  $id=$_REQUEST["id"];
  $dbtrans=$_REQUEST["dbtrans"];
  $jpn=$_REQUEST["txtKodJPN"];  
  $ppd=$_REQUEST["txtKodPPD"];  
  $namapegawai=oci_escape_string($_REQUEST["txtNamaPegawai"]);  
  $sektor=oci_escape_string($_REQUEST["txtSektor"]); 
  $emel=$_REQUEST["txtEmel"];  
  $notelefon=$_REQUEST["txtNoTelefon"];  

  if ($dbtrans=="0"){ //insert
     $qry="insert into pegawai_saps(jpn,ppd,nama_pegawai,sektor_unit,emel,notelefon)
	       values('$jpn','$ppd','$namapegawai','$sektor','$emel','$notelefon')"; 
  } 		   
  else if ($dbtrans=="1") { //update
     $qry="update pegawai_saps set jpn='$jpn',ppd='$ppd',nama_pegawai='$namapegawai',sektor_unit='$sektor',emel='$emel',
	       notelefon='$notelefon' where id='$id'";
	     
  }
  $res=oci_parse($conn_sispa,$qry);
  oci_execute($res);
  pageredirect("senarai_pegawai.php"); 
 
 
 } //$_POST

 $dbtrans="0";
 
 if ($_GET["id"]<>""){	 
	 $id=(int) $_GET["id"];
	 
	 $query = "SELECT JPN,PPD,NAMA_PEGAWAI,SEKTOR_UNIT,EMEL,NOTELEFON 
			  FROM PEGAWAI_SAPS where ID='$id'";
	//echo "$query<br>";		  
	 $result = oci_parse($conn_sispa,$query);
	 oci_execute($result);
	 if ($data=oci_fetch_array($result)){
	   $jpn=$data["JPN"];
	   $ppd=$data["PPD"];
	   $namapegawai=$data["NAMA_PEGAWAI"];
	   $sektor=$data["SEKTOR_UNIT"];
	   $emel=$data["EMEL"];   
	   $notelefon=$data["NOTELEFON"];   
	   $dbtrans="1";
	 }
 }	 
 ?>
<form name="frm1" method="post" action="b_tambah_pegawai.php">
<table id="form_table_outer" width="60%">
  <tr><td>
<table id="form_table_inner" width="100%" border="0" bgcolor="#EEF3FF" cellpadding="2" cellspacing="0">
          <tr> 
            <td width="20%">JPN</td>
            <td width="80%" >
<select name="txtKodJPN" id="txtKodJPN" onChange="papar_ppd(this.value);">
<option value="">-Pilih-</option>
<?php
  $result_jpn = oci_parse($conn_sispa,"select kodnegeri,negeri from tknegeri order by negeri");
  oci_execute($result_jpn);
  while($data_jpn=oci_fetch_array($result_jpn)){
	  $kodnegeri=$data_jpn["KODNEGERI"];
	  $negeri=$data_jpn["NEGERI"];
	  if($kodnegeri==$jpn)
	     echo "<option selected value=\"$kodnegeri\">$negeri</option>";
      else
	     echo "<option value=\"$kodnegeri\">$negeri</option>";
  }
  
?>
</select>			
			</td>
          </tr>
          <tr> 
            <td width="20%">PPD</td>
            <td width="80%" >
<div id="divPPD">			
<select name="txtKodPPD" id="txtKodPPD">
<option value="">-Pilih-</option>
<option 
<?php if ($ppd=="JPN")
	echo " selected ";
?>
value="JPN">JPN</option>
<?php
  $result_jpn = oci_parse($conn_sispa,"select kodppd,ppd from tkppd where kodnegeri='$jpn'");
  oci_execute($result_jpn);
  while($data_jpn=oci_fetch_array($result_jpn)){
	  $kodppd1=$data_jpn["KODPPD"];
	  $ppd1=$data_jpn["PPD"];
	  if($kodppd1==$ppd)
	     echo "<option selected value=\"$kodppd1\">$kodppd1 - $ppd1</option>";
      else
	     echo "<option value=\"$kodppd1\">$kodppd1 - $ppd1</option>";
  }
  
?>
</select>
</div>			
			</td>
          </tr>
          <tr> 
            <td width="20%">Nama Pegawai</td>
            <td width="80%" ><input name="txtNamaPegawai" id="txtNamaPegawai"  type="text" size="50" maxlength="200" value="<?php echo $namapegawai; ?>"></td>
          </tr>
          <tr> 
            <td width="20%">Sektor/Unit</td>
            <td width="80%" ><input name="txtSektor" id="txtSektor"  type="text" size="50" maxlength="200" value="<?php echo $sektor; ?>"></td>
          </tr>
          <tr> 
            <td width="20%">Emel</td>
            <td width="80%" ><input name="txtEmel" id="txtEmel"  type="text" size="50" maxlength="200" value="<?php echo $emel; ?>"></td>
          </tr>
          <tr> 
            <td width="20%">No. Telefon Meja</td>
            <td width="80%" ><input name="txtNoTelefon" id="txtNoTelefon"  type="text" size="50" maxlength="200" value="<?php echo $notelefon; ?>"></td>
          </tr>
          <tr> 
            <td colspan="2" >
              <input type="hidden" name="dbtrans" value="<?php echo $dbtrans;?>"> 
			  <input type="hidden" name="post" id="post" value="1">
			  <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>"> 
              <input name="Simpan" type="submit" value="Simpan"> 
			  <input name="Kembali" type="Button" value="Kembali" onclick="location.href='senarai_pegawai.php';"> 
            </td>
          </tr>
        </table></td></tr></table>
</form>

		</td>
	</tr>
</table>
</td>
<?php include 'kaki.php';?> 
