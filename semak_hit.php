<?php
  $orasysdate = oci_parse($conn_sispa, "SELECT sysdate AS ODATE FROM DUAL");
  oci_execute($orasysdate);
  $datsysdate = oci_fetch_array($orasysdate);
  $timestamp = $datsysdate["ODATE"];
?>
<table>
  <tr>
    <td>Guru</td>
    <td>&nbsp;&nbsp;:</td>
    <td>&nbsp;
      <?php
        $cpelawat = 0;

        // Hit Home
        $chkhit = "SELECT * FROM HIT_PELAWAT WHERE HIT_JENIS = '1' AND HIT_DATE = '$timestamp'";
        $reshit = oci_parse($conn_sispa, $chkhit);
        oci_execute($reshit);
        $dathit = oci_fetch_array($reshit);
          for($h=0; $h<24; $h++){
            $hit = (int) $dathit["HIT_H".sprintf("%02d", $h)];
            $cpelawat = $cpelawat + $hit;
          }

        echo $cpelawat;
      ?>
    </td>
  </tr>
  <tr>
    <td>Ibu Bapa</td>
    <td>&nbsp;&nbsp;:</td>
    <td>&nbsp;
      <?php
        $cibubapa = 0;

        // Hit Home
        $chkhit = "SELECT * FROM HIT_PELAWAT WHERE HIT_JENIS = '2' AND HIT_DATE = '$timestamp'";
        $reshit = oci_parse($conn_sispa, $chkhit);
        oci_execute($reshit);
        $dathit = oci_fetch_array($reshit);
          for($h=0; $h<24; $h++){
            $hit = (int) $dathit["HIT_H".sprintf("%02d", $h)];
            $cibubapa = $cibubapa + $hit;
          }

        echo $cibubapa;
      ?>
    </td>
  </tr>
</table>