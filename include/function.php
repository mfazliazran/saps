<?php

function paging($totalrecord,$recordpage,$url,$pg)
{
 $maxpage=10;
 $b=($pg % $maxpage);
 $p=($pg-$b)/$maxpage;
 if (!$b)
    $p--;

  $startpage=($p * $maxpage)+1;

 $r=$totalrecord % $recordpage;
 $totalpage=(int) ($totalrecord-$r)/$recordpage;
 if ($r>0)
   $totalpage++;
 $c=0;
 $p=$startpage;
 if ($p>1){
  $prev=$p-1;
  echo "<a href=\"$url&pg=".$prev."\"><<</a>";
 }
 while($p<=$totalpage and $c<$maxpage){
   $c++;
   if ($pg==$p)
     echo "$p&nbsp;";
   else
     echo "<a href=\"$url&pg=$p\">$p&nbsp;</a>";
   $p++;
 } 
 $c++;
 if ($p<$totalpage)
  echo "<a href=\"$url&pg=$p\">>></a>";

}

?>
