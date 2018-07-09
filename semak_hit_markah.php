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

  $orasysdate = oci_parse($conn_sispa, "SELECT sysdate AS ODATE FROM DUAL");
  oci_execute($orasysdate);
  $datsysdate = oci_fetch_array($orasysdate);
  $tkhtoday = $datsysdate["ODATE"];

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
    echo "<tr>
            <td colspan='4'>Semakan Slip Peperiksaan</td>
            </tr>";

    for($i=1; $i<3; $i++){
      if($i == 1)
        $label = "Rendah";
      else
        $label = "Menengah";

      echo "<tr>
              <td rowspan='4' valign='top'>$label</td>
            </tr>";

      for($js=1; $js<4; $js++){
        if($js == 1)
          $jpep = "U1";
        
        if($js == 2)
          $jpep = "PPT";

        if($js == 3)
          $jpep = "PAT";
        
        // Hit Slip
        $chkhit = "SELECT SLIP_BIL FROM HIT_SEMAK_SLIP WHERE SLIP_JPEP = '$jpep' AND SLIP_JENISSEK = '".substr($label, 0, 1)."' ";
        if($tkhmula != "")
          $chkhit .= "AND SLIP_TARIKH >= :tmula ";
        if($tkhakhir != "")
          $chkhit .= "AND SLIP_TARIKH <= :takhir ";
        if($tkhmula == "" && $tkhakhir == "")
          $chkhit .= "AND SLIP_TARIKH = '$tkhtoday'";

        $reshit = oci_parse($conn_sispa, $chkhit);
        oci_bind_by_name($reshit, ':tmula', oradate($tkhmula));
        oci_bind_by_name($reshit, ':takhir', oradate($tkhakhir));
        oci_execute($reshit);
        $dathit = oci_fetch_array($reshit);
        $cnthit = $dathit["SLIP_BIL"];
        if($cnthit == "")
          $cnthit = 0;

        echo "<tr>
                <td>&nbsp;&nbsp;$jpep</td>
                <td>&nbsp;&nbsp;:</td>
                <td>$cnthit</td>
                </tr>";
      }

    }

    echo "<tr>
            <td>&nbsp;</td>
            </tr>";

    echo "<tr>
            <td colspan='3'>Semakan Markah Peperiksaan</td>
            </tr>";

    for($i=1; $i<3; $i++){
      if($i == 1)
        $label = "Rendah";
      else
        $label = "Menengah";

      // Hit Home
      $chkhit = "SELECT MARK_BIL FROM HIT_SEMAK_MARK WHERE MARK_JENISSEK = '".substr($label, 0, 1)."' ";
      if($tkhmula != "")
        $chkhit .= "AND MARK_TARIKH >= :tmula ";
      if($tkhakhir != "")
        $chkhit .= "AND MARK_TARIKH <= :takhir ";
      if($tkhmula == "" && $tkhakhir == "")
        $chkhit .= "AND MARK_TARIKH = '$tkhtoday'";

      $reshit = oci_parse($conn_sispa, $chkhit);
      oci_bind_by_name($reshit, ':tmula', oradate($tkhmula));
      oci_bind_by_name($reshit, ':takhir', oradate($tkhakhir));
      oci_execute($reshit);
      $dathit = oci_fetch_array($reshit);
      $cnthit = $dathit["MARK_BIL"];
      if($cnthit == "")
        $cnthit = 0;

      echo "<tr>
              <td>$label</td>
              <td>&nbsp;&nbsp;:</td>
              <td>&nbsp; $cnthit</td>
            </tr>";
    }
  ?>
</table>
</td>
<?php include 'kaki.php';?>
