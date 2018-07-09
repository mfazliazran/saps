<?php 
function peratus($bil, $amb)
{
	if ($amb >= 1)
	{
		$peratus = number_format(($bil/$amb)*100,2,'.',',');
	}
	else {
			$peratus = 0.00 ;
		 }
	return $peratus ;
}

function gpmpmrsr($bilA, $bilB, $bilC, $bilD, $bilE, $amb)
{//echo "$bilA, $bilB, $bilC, $bilD, $bilE, $amb <br>";
	if ($amb >= 1)
	{
		$gpmp = number_format((($bilA*1) + ($bilB*2) + ($bilC*3) + ($bilD*4) + ($bilE*5))/$amb,2,'.',',');
	}
	else {
			$gpmp = 0.00 ;
		 }
	return $gpmp ;
}
     
function gpmpmrsr_baru($bilA, $bilB, $bilC, $bilD, $bilE, $bilF, $amb)
{//echo "$bilA, $bilB, $bilC, $bilD, $bilE, $amb <br>";
	if ($amb >= 1)
	{
		$gpmp = number_format((($bilA*1) + ($bilB*2) + ($bilC*3) + ($bilD*4) + ($bilE*5) + ($bilF*6))/$amb,2,'.',',');
	}
	else {
			$gpmp = 0.00 ;
		 }
	return $gpmp ;
}

function gpmpma($bilAp, $bilA, $bilAm, $bilBp, $bilB, $bilCp, $bilC, $bilD, $bilE, $bilG, $amb)
{
	if ($amb >= 1)
	{
		$gpmp = number_format((($bilAp*0) + ($bilA*1) + ($bilAm*2) + ($bilBp*3) + ($bilB*4) + ($bilCp*5) 
								+ ($bilC*6) + ($bilD*7) + ($bilE*8) + ($bilG*9))/$amb,2,'.',',');
	}
	else {
			$gpmp = 0.00 ;
		 }
	return $gpmp ;
}


?>