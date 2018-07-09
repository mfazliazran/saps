<?php
  include 'auth.php';
  include 'config.php';
  include 'kepala.php';
  include 'menu.php';
  include 'fungsi2.php';
  include 'fungsikira.php';
  include "input_validation.php";
?>
<td valign="top" class="rightColumn">

<script type="text/javascript">
  function semak(){
    var mula = document.frmCarian.txtTkhMula.value;
    var akhir = document.frmCarian.txtTkhAkhir.value;
    var today = document.frmCarian.txtTkhToday.value;
    
    if(mula == "" && akhir == ""){
      alert("TIada maklumat carian yang diisi.");
      document.frmCarian.txtTkhMula.focus();
      return false;
    }

    if(mula > akhir || mula > today){
      alert("Maklumat tidak sah. Sila semak tarikh pilihan.");
      document.frmCarian.txtTkhMula.focus();
      return false;
    }

    return confirm("Adakah anda pasti?");
  }
</script>

<?php

  if(validate($_POST['Cari'])){
    $tkhmula = validate($_POST['txtTkhMula']);
    $tkhakhir = validate($_POST['txtTkhAkhir']);
  }
?>

<form method="post" action="" name="frmCarian">
  <table>
    <tr>
      <td colspan='2'>Carian</td>
    </tr>
    <tr>
      <td>Tarikh Mula :</td>
      <td>
        <input name="txtTkhMula" type="text" class="short" id="txtTkhMula" value="<?php echo $tkhmula; ?>" size="12" maxlength="10" placeholder="cth: <?php echo date('d/m/Y'); ?>" />
      </td>
    </tr>
    <tr>
      <td>Tarikh Akhir :</td>
      <td>
        <input name="txtTkhAkhir" type="text" class="short" id="txtTkhAkhir" value="<?php echo $tkhakhir; ?>" size="12" maxlength="10" placeholder="cth: <?php echo date('d/m/Y'); ?>" />
      </td>
    </tr>
    <tr>
      <td colspan='2'>
        <input type="hidden" name="txtTkhToday" id="txtTkhToday" value="<?php echo date("d/m/Y"); ?>" />
        <input name="Cari" type="submit" value="Cari" onClick="return semak();" />
      </td>
    </tr>
  </table>
</form>

<table>
  <?php
    $tkhtoday = oradate(date("d/m/Y"));

    for($i=1; $i<3; $i++){
      if($i == 1)
        $label = "Guru";
      else
        $label = "Ibu Bapa";

      $cpelawat = 0;

      // Hit Home
      $chkhit = "SELECT * FROM HIT_PELAWAT WHERE HIT_JENIS = '$i' ";
      if($tkhmula != "")
        $chkhit .= "AND HIT_DATE >= :tmula ";
      if($tkhakhir != "")
        $chkhit .= "AND HIT_DATE <= :takhir ";
      if($tkhmula == "" && $tkhakhir == "")
        $chkhit .= "AND HIT_DATE = '$tkhtoday'";

      $reshit = oci_parse($conn_sispa, $chkhit);
      oci_bind_by_name($reshit, ':tmula', oradate($tkhmula));
      oci_bind_by_name($reshit, ':takhir', oradate($tkhakhir));
      oci_execute($reshit);
      while($dathit = oci_fetch_array($reshit)){
        for($h=0; $h<24; $h++){
          $hit = (int) $dathit["HIT_H".sprintf("%02d", $h)];
          $cpelawat = $cpelawat + $hit;
          }
      }

      echo "<tr>
              <td>$label</td>
              <td>&nbsp;&nbsp;:</td>
              <td>&nbsp; $cpelawat</td>
            </tr>";
    }
  ?>
</table>
</td>
<?php include 'kaki.php';?>
