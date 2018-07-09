<?php
include 'auth.php';
include 'kepala.php';
include 'menu.php';
include_once ('config.php');
include('include/function.php');

$task=$_GET["task"];
?>
<td valign="top" class="rightColumn" height="1000">
<iframe style="overflow:hidden;height:100%;width:90%" frameborder="0"  src="https://emisonline.moe.gov.my/UtilitiSAPS/<?php echo $task;?>.php"></iframe>
</td>
<?php include 'kaki.php';?> 