<?php
echo "<table align=\"center\" width=\"98%\"  border=\"1\" cellpadding=\"5\" cellspacing=\"0\" bordercolor=\"#999999\">\n";
echo "<tr bgcolor=\"#FFCC99\">\n";
echo "<td rowspan=\"2\"><div align=\"center\">&nbsp;</div></td>\n";
echo "<td rowspan=\"2\">HC</td>\n";
echo "<td rowspan=\"2\" align=\"center\">BIL MURID</td>\n";
echo "<td colspan=\"7\"><div align=\"center\">BIL DAPAT 'STRAIGHT' A</div></td>\n";
echo "<td rowspan=\"2\"><div align=\"center\">% DAPAT 'STRAIGHT' A</div></td>\n";
echo "</tr>\n";
echo "<tr bgcolor=\"#FFCC99\">\n";
echo "<td><div align=\"center\">12A</div></td>\n";
echo "<td><div align=\"center\">11A</div></td>\n";
echo "<td><div align=\"center\">10A</div></td>\n";
echo "<td><div align=\"center\">9A</div></td>\n";
echo "<td><div align=\"center\">8A</div></td>\n";
echo "<td><div align=\"center\">7A</div></td>\n";
echo "<td><div align=\"center\">JUM</div></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td rowspan=\"2\">MURID MENDAPAT SEMUA A DALAM SEMUA MATA PELAJARAN</td>";
echo "<td><div align=\"center\">TOV</div></td>\n";
echo "<td><div align=\"center\">$jumalltovA</div></td>\n";
echo "<td><div align=\"center\">$jumtov12A</div></td>\n";
echo "<td><div align=\"center\">$jumtov11A</div></td>\n";
echo "<td><div align=\"center\">$jumtov10A</div></td>\n";
echo "<td><div align=\"center\">$jumtov9A</div></td>\n";
echo "<td><div align=\"center\">$jumtov8A</div></td>\n";
echo "<td><div align=\"center\">$jumtov7A</div></td>\n";
echo "<td><div align=\"center\">$jumalltovA</div></td>\n";
echo "<td><div align=\"center\">".peratus($jumalltovA,$bilcalon)."</div></td></tr>\n";
echo "<td><div align=\"center\">ETR</div></td>\n";
echo "<td><div align=\"center\">$jumalletrA</div></td>\n";
echo "<td><div align=\"center\">$jumetr12A</div></td>\n";
echo "<td><div align=\"center\">$jumetr11A</div></td>\n";
echo "<td><div align=\"center\">$jumetr10A</div></td>\n";
echo "<td><div align=\"center\">$jumetr9A</div></td>\n";
echo "<td><div align=\"center\">$jumetr8A</div></td>\n";
echo "<td><div align=\"center\">$jumetr7A</div></td>\n";
echo "<td><div align=\"center\">$jumalletrA</div></td>\n";
echo "<td><div align=\"center\">".peratus($jumalletrA,$bilcalon)."</div></td></tr>\n";
echo "</table>\n";
?>