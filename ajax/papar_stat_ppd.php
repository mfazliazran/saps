<?php
//$conn_sispa=oci_connect("portal_sispa","portal_sispa","//testappserver:1521/xe");
session_start();
set_time_limit(0);
include "../config.php";
include '../fungsikira.php';
$hlcolor= "#AACCF2";
$ncolor="#ffffff";	
$altcolor="#57C9DD";

$currdate=date("Y-m-d");

$flg=$_GET["flg"];
$kod=$_GET["kod"];//kodnegeri
$jpep=$_GET["jpep"];
$tahun=$_GET["year"];

if($flg=="JPN"){
echo "<table width=\"90%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" align='center'><tr><td>";
echo "<TABLE  style=\"border: solid 1px #CCC;\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
echo "<tr bgcolor=\"#FFCC66\">\n";
if($jpep=='UPSRC')
	$rowsp = 0;
elseif($jpep=='PMRC')
	$rowsp = 0;
elseif($jpep=='SPMC')
	$rowsp = 0;
elseif($jpep=='PAT')
	$rowsp = 2;
else
	$rowsp = 2;
echo "<td rowspan=\"$rowsp\" scope=\"col\">Bil.</td>\n";
echo "<td rowspan=\"$rowsp\" scope=\"col\">PPD</td>\n";
echo "<td rowspan=\"$rowsp\" scope=\"col\" align='center'>Jumlah Sekolah</td>\n";
echo "<td colspan='$rowsp' scope=\"col\" align='center'>Jumlah Tidak Lengkap</td>\n";
echo "<td colspan='$rowsp' scope=\"col\" align='center'>Jumlah Lengkap</td>\n";
echo "</tr>";
if($jpep<>'UPSRC' and $jpep<>'PMRC' and $jpep<>'SPMC'){
echo "<tr bgcolor=\"#FFCC66\">";
echo "<td align=\"center\">Menengah</td>";
echo "<td align=\"center\">Rendah</td>";
echo "<td align=\"center\">Menengah</td>";
echo "<td align=\"center\">Rendah</td>";
echo "</tr>";
}

$querysub = "select KODPPD,PPD from TKPPD where KODNEGERI='$kod' order by PPD";
$res = OCIParse($conn_sispa,$querysub);
OCIExecute($res);
while(OCIFetch($res)){
	$kodppd = OCIResult($res,"KODPPD");
	$ppd = OCIResult($res,"PPD");
	
	//DAPATKAN KODSEKOLAH BAGI NEGERI YANG BERKENAAN
	//$sql = "SELECT KODSEK,STATUS FROM TSEKOLAH WHERE KODNEGERIJPN='$kodnegeri'";//
	if($jpep=='UPSRC'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE KODNEGERIJPN='$kod'
		AND TSEKOLAH.KODPPD='$kodppd'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.KODSEK=TKELASSEK.KODSEK
		AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH NOT IN ('105','202')
		AND TSEKOLAH.STATUS ='SR'
		AND TKELASSEK.TING ='D6'
        group by TSEKOLAH.KODSEK,STATUS");		
		//AND TSEKOLAH.KODSEK NOT IN ('KBA3055','KBC9027','KBA5032','BBC0057','BBC5028','BBC6033','JBD2048','ABA2074','XCC6008','XBA4037','XCC6218','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316','YBB3225','XCA2223')
		//AND TSEKOLAH.KODSEK not in ('BBC0057','JBC1040','BBB8285','BEA8645','NBC2031','NBC4068','NBC2027','NBA4052','NBA4053','JBA8035','ABC6081','BBA8285','JBD2041','JBD2048','CBD4052','ABD4111','ABA0127','BBA1008','BBA3038','BBD5052','BBC0057','YCC3212')
	}else if($jpep=='PMRC'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE KODNEGERIJPN='$kod'
		AND TSEKOLAH.KODPPD='$kodppd'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.KODSEK=TKELASSEK.KODSEK
		AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH NOT IN ('105','202')
		AND TSEKOLAH.KODSEK not in ('KFT2002','KHA2001','KHA3004','KEA3119','KHA5002','BHA8002','BRA0001','BEA3087','JEA5027','JHA5001','XHA3102','XHA5102','XHA2001','XEA4303','XRA1001','BHA7001','BHA8002')
		AND TSEKOLAH.KODJENISSEKOLAH != '203'
		AND TSEKOLAH.STATUS ='SM'
		AND TKELASSEK.TING ='T3'
        group by TSEKOLAH.KODSEK,STATUS");		
		//AND TSEKOLAH.KODSEK not in ('BEA8612','KEA3118','KEA4069','KHA2001','KHA3003','KHA3004','KHA5002','KHA6002','KKE2156','JEA8014','BRA0001','BHA8002','BEA7622','CHA4001','JEA2046','JHA2001','BEA4643','BEA3086','BEA1074','BEA8612','BHA8004','BEA4634','YHA3101','NHA3001','RHA0002','RHA0003')
}else if ($jpep=='SPMC'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE KODNEGERIJPN='$kod'
		AND TSEKOLAH.KODPPD='$kodppd'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.KODSEK=TKELASSEK.KODSEK
		AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH NOT IN ('105','202')
		AND TSEKOLAH.KODSEK NOT IN ('CEA4099','CHA4002','BEA3086','BEA0113','BEA8668','BEA3088','BHA7001','BEB1070','NFT0001','NFT1001','NFT1002','NFT2001','NEA4114')
		AND TSEKOLAH.STATUS ='SM'
		AND TKELASSEK.TING ='T5'
        group by TSEKOLAH.KODSEK,STATUS");	
//AND TSEKOLAH.KODSEK not in ('BEA1074','BEA8666','BEA7622','BEA4633','BEA4634','BEA3086','BEA8612','JEA2046','AFT3002','KEA3118','DFT0001','DFT1002','DEA1152','DEA1153','DEA1154','DEA1151','DEA1150','DEA1149','DFT3003','DFT3002','DEA3431','DFT3004','DFT3005','DFT4003','DFT4001','DFT5001','DFT5004','DFT5002','DFT5003','DFT6001','DFT6002')		
	}else if ($jpep=='PAT'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE KODNEGERIJPN='$kod'
		AND TSEKOLAH.KODPPD='$kodppd'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSAH.KODSEK=TKELASSEK.KODSEK
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH NOT IN ('105','202')
		AND TKELASSEK.TING IN ('D5','T4')
        group by TSEKOLAH.KODSEK,STATUS");
		//		AND TSEKOLAH.KODSEK NOT IN ('BEA8612','RHA0002','RHA0003','AHA1002','BHA8004','ABD6107','PHA3001')
	}else if($jpep=='PPT'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE KODNEGERIJPN='$kod'
		AND TSEKOLAH.KODPPD='$kodppd'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSAH.KODSEK=TKELASSEK.KODSEK
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH NOT IN ('105','202')
		AND TKELASSEK.TING IN ('D5','D6','T4','T5')
        group by TSEKOLAH.KODSEK,STATUS");	
	}else{
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE KODNEGERIJPN='$kod'
		AND TSEKOLAH.KODPPD='$kodppd'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSAH.KODSEK=TKELASSEK.KODSEK
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH NOT IN ('105','202')
		AND TSEKOLAH.KODSEK NOT IN ('BEA0113','AEA6159','ABC6081','AKE6159')
		AND TKELASSEK.TING IN ('D5','D6','T4','T5')
        group by TSEKOLAH.KODSEK,STATUS");//AND TKELASSEK.TING!='D2'
		//AND TSEKOLAH.KODSEK NOT IN ('AHA2001','RHA0003','AKE6159','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316')
	}
	//$ressek = OCIParse($conn_sispa,$sql);
	OCIExecute($res4);
	$jumsek=0; $bilxsiapsr=0; $bilxsiapsm=0; $bilkelas=0;$bilsiapsm=0;$bilsiapsr=0;
	while(OCIFetch($res4)){
		$bilkelas = OCIResult($res4,"BILTING");
		$jumlahsah = OCIResult($res4,"BILSAH");
		$status = OCIResult($res4,"STATUS");//SM/SR
        $jumsek++;
		if($status=='SM'){
			if($jumlahsah < $bilkelas){
				$bilxsiapsm++;	
			}
			else
			    $bilsiapsm++;
		}else{
			if($jumlahsah < $bilkelas){
				$bilxsiapsr++;	
			}
			else
			    $bilsiapsr++;
		}
		//$bilkelas=0;
	}//while ressek
	if($jpep=='UPSRC'){
	$res5 = OCIParse($conn_sispa,"select status,count(*) as jum from tsekolah where kodnegerijpn='$kod' and kodppd='$kodppd' and kodstatussekolah='01' 
	AND KODJENISSEKOLAH NOT IN ('105','202') and TSEKOLAH.STATUS ='SR' and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");	
	// AND TSEKOLAH.KODSEK NOT IN ('KBA3055','KBC9027','KBA5032','BBC0057','BBC5028','BBC6033','JBD2048','ABA2074','XCC6008','XBA4037','XCC6218','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316','YBB3225','XCA2223')
	}else if($jpep=='PMRC'){
	$res5 = OCIParse($conn_sispa,"select status,count(*) as jum from tsekolah where kodnegerijpn='$kod' and kodppd='$kodppd' and kodstatussekolah='01' 
	AND KODJENISSEKOLAH NOT IN ('105','202') AND TSEKOLAH.KODJENISSEKOLAH != '203' and TSEKOLAH.STATUS ='SM' AND TSEKOLAH.KODSEK not in ('KFT2002','KHA2001','KHA3004','KEA3119','KHA5002','BHA8002','BRA0001','BEA3087','JEA5027','JHA5001','XHA3102','XHA5102','XHA2001','XEA4303','XRA1001','BHA7001','BHA8002') and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");
	//AND TSEKOLAH.KODSEK not in ('BEA8612','KEA3118','KEA4069','KHA2001','KHA3003','KHA3004','KHA5002','KHA6002','KKE2156','JEA8014','BRA0001','BHA8002','BEA7622','CHA4001','JEA2046','JHA2001','BEA4643','BEA3086','BEA1074','BEA8612','BHA8004','BEA4634','YHA3101','NHA3001','RHA0002','RHA0003')
	}else if ($jpep=='SPMC'){
	$res5 = OCIParse($conn_sispa,"select status,count(*) as jum from tsekolah where kodnegerijpn='$kod' and kodppd='$kodppd' and kodstatussekolah='01' 
	AND KODJENISSEKOLAH NOT IN ('105','202') AND TSEKOLAH.KODSEK NOT IN ('CEA4099','CHA4002','BEA3086','BEA0113','BEA8668','BEA3088','BHA7001','BEB1070','NFT0001','NFT1001','NFT1002','NFT2001','NEA4114') and TSEKOLAH.STATUS ='SM' and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");
// AND TSEKOLAH.KODSEK not in ('BEA1074','BEA8666','BEA7622','BEA4633','BEA4634','BEA3086','BEA8612','JEA2046','AFT3002','KEA3118','DFT0001','DFT1002','DEA1152','DEA1153','DEA1154','DEA1151','DEA1150','DEA1149','DFT3003','DFT3002','DEA3431','DFT3004','DFT3005','DFT4003','DFT4001','DFT5001','DFT5004','DFT5002','DFT5003','DFT6001','DFT6002')	
	}else if ($jpep=='PAT'){
	$res5 = OCIParse($conn_sispa,"select status,count(KODSEK) as jum from tsekolah where kodnegerijpn='$kod' and kodppd='$kodppd' and kodstatussekolah='01' AND KODJENISSEKOLAH NOT IN ('105','202') AND kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");
	//AND TSEKOLAH.KODSEK NOT IN ('BEA8612','RHA0002','RHA0003','AHA1002','BHA8004','ABD6107','PHA3001')
	}else if ($jpep=='PPT'){
	$res5 = OCIParse($conn_sispa,"select status,count(KODSEK) as jum from tsekolah where kodnegerijpn='$kod' and kodppd='$kodppd' and kodstatussekolah='01' AND KODJENISSEKOLAH NOT IN ('105','202') AND kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");
	//AND TSEKOLAH.KODSEK NOT IN ('BEA8612','RHA0002','RHA0003','AHA1002','BHA8004','ABD6107','PHA3001')
	}else{
	$res5 = OCIParse($conn_sispa,"select status,count(KODSEK) as jum from tsekolah where kodnegerijpn='$kod' and kodppd='$kodppd' and kodstatussekolah='01' AND KODJENISSEKOLAH NOT IN ('105','202') AND TSEKOLAH.KODSEK NOT IN ('BEA0113','AEA6159','ABC6081','AKE6159') AND kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");
	//AND TSEKOLAH.KODSEK NOT IN ('AHA2001','RHA0003','AKE6159','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316')
	}
	OCIExecute($res5);
	while(OCIFetch($res5)){
		$jumsek+=OCIResult($res5,"JUM");
		$biltaksahall = OCIResult($res5,"JUM");
		$statussekolah = OCIResult($res5,"STATUS");
		if($statussekolah=='SM')
			$bilxsiapsm+= $biltaksahall;
		if($statussekolah=='SR')
			$bilxsiapsr+= $biltaksahall;
	}
	$bil2++;
	echo "<tr bgcolor='#D8DFFC' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '#D8DFFC'\"><td>$bil2</td> \n";
	echo "<td><a href=\"javascript:void(0);\" onClick=\"papar_rekod('PPD','$kodppd','$bil2','$tahun','$jpep');\">$ppd</a></td>";
	echo "<td align='center'>$jumsek&nbsp;</td>";
	if($jpep=="UPSRC"){
		echo "<td align='center'><strong><font color=\"#ff0000\">$bilxsiapsr</font></strong>&nbsp;</td>";
		echo "<td align='center'>$bilsiapsr</td>";	
	}elseif($jpep=="SPMC" or $jpep=="PMRC"){
		echo "<td align='center'><strong><font color=\"#ff0000\">$bilxsiapsm</font></strong>&nbsp;</td>";
		echo "<td align='center'>$bilsiapsm</td>";		
	}else{
		echo "<td align='center'><strong><font color=\"#ff0000\">$bilxsiapsm</font></strong>&nbsp;</td>";
		echo "<td align='center'><strong><font color=\"#ff0000\">$bilxsiapsr</font></strong>&nbsp;</td>";
		echo "<td align='center'>$bilsiapsm</td>";
		echo "<td align='center'>$bilsiapsr</td>";	
	}
	echo "</tr>";
	echo "<tr><td colspan=\"7\"><div id=\"div_detail_sek_".$kodppd."_".$bil2."\" style=\"display:none\"></div></td></tr>";
	$jumsek=0; $bilxsiapsm=0; $bilxsiapsr=0; $bilkelas=0;
}
echo "</table>";
echo "</td></tr></table>";	
}//flg JPN
if($flg=="PPD"){
echo "<table width=\"80%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" align='center'><tr><td>";
echo "<TABLE  style=\"border: solid 1px #CCC;\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
echo "<tr bgcolor=\"#FFCC66\">\n";
echo "<td scope=\"col\">Bil.</td>\n";
echo "<td scope=\"col\">Sekolah</td>\n";
echo "<td scope=\"col\" align='center'>Belum Lengkap</td>\n";
echo "<td scope=\"col\" align='center'>Lengkap</td>\n";
echo "</tr>";

if($jpep=='UPSRC'){
$querysub = "SELECT KODSEK,NAMASEK,STATUS,KODJENISSEKOLAH FROM tsekolah where kodppd='$kod' and kodstatussekolah='01' AND KODJENISSEKOLAH!='105' AND STATUS='SR' order by NAMASEK";
// AND TSEKOLAH.KODSEK NOT IN ('KBA3055','KBC9027','KBA5032','BBC0057','BBC5028','BBC6033','JBD2048','ABA2074','XCC6008','XBA4037','XCC6218','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316','YBB3225','XCA2223')	
}else if($jpep=='PMRC'){
$querysub = "SELECT KODSEK,NAMASEK,STATUS,KODJENISSEKOLAH FROM tsekolah where kodppd='$kod' and kodstatussekolah='01' AND KODJENISSEKOLAH!='105' AND TSEKOLAH.KODJENISSEKOLAH != '203' AND STATUS='SM' AND TSEKOLAH.KODSEK not in ('KFT2002','KHA2001','KHA3004','KEA3119','KHA5002','BHA8002','BRA0001','BEA3087','JEA5027','JHA5001','XHA3102','XHA5102','XHA2001','XEA4303','XRA1001','BHA7001','BHA8002') order by NAMASEK";
//AND TSEKOLAH.KODSEK not in ('BEA8612','KEA3118','KEA4069','KHA2001','KHA3003','KHA3004','KHA5002','KHA6002','KKE2156','JEA8014','BRA0001','BHA8002','BEA7622','CHA4001','JEA2046','JHA2001','BEA4643','BEA3086','BEA1074','BEA8612','BHA8004','BEA4634','YHA3101','NHA3001','RHA0002','RHA0003')
}else if($jpep=='SPMC'){
$querysub = "SELECT KODSEK,NAMASEK,STATUS,KODJENISSEKOLAH FROM tsekolah where kodppd='$kod' and kodstatussekolah='01' AND KODJENISSEKOLAH NOT IN ('105','202') AND TSEKOLAH.KODSEK NOT IN ('CEA4099','CHA4002','BEA3086','BEA0113','BEA8668','BEA3088','BHA7001','BEB1070','NFT0001','NFT1001','NFT1002','NFT2001','NEA4114') AND STATUS='SM' order by NAMASEK";	
// AND TSEKOLAH.KODSEK not in ('BEA1074','BEA8666','BEA7622','BEA4633','BEA4634','BEA3086','BEA8612','JEA2046','AFT3002','KEA3118','DFT0001','DFT1002','DEA1152','DEA1153','DEA1154','DEA1151','DEA1150','DEA1149','DFT3003','DFT3002','DEA3431','DFT3004','DFT3005','DFT4003','DFT4001','DFT5001','DFT5004','DFT5002','DFT5003','DFT6001','DFT6002')
}else if($jpep=='PAT'){
$querysub = "SELECT KODSEK,NAMASEK,STATUS,KODJENISSEKOLAH FROM tsekolah where kodppd='$kod' and kodstatussekolah='01' AND KODJENISSEKOLAH NOT IN ('105','202') order by NAMASEK";
// AND TSEKOLAH.KODSEK NOT IN ('BEA8612','RHA0002','RHA0003','AHA1002','BHA8004','ABD6107','PHA3001')
}else if($jpep=='PPT'){
$querysub = "SELECT KODSEK,NAMASEK,STATUS,KODJENISSEKOLAH FROM tsekolah where kodppd='$kod' and kodstatussekolah='01' AND KODJENISSEKOLAH!='105' order by NAMASEK";
}else{
$querysub = "SELECT KODSEK,NAMASEK,STATUS,KODJENISSEKOLAH FROM tsekolah where kodppd='$kod' and kodstatussekolah='01' AND KODJENISSEKOLAH!='105' AND TSEKOLAH.KODSEK NOT IN ('BEA0113','AEA6159','ABC6081','AKE6159') order by NAMASEK";
// AND TSEKOLAH.KODSEK NOT IN ('AHA2001','RHA0003','AKE6159','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316')
}
$res = OCIParse($conn_sispa,$querysub);
OCIExecute($res);
while(OCIFetch($res)){
	$kodsek = OCIResult($res,"KODSEK");
	$namasek = OCIResult($res,"NAMASEK");
	
	//DAPATKAN KODSEKOLAH BAGI NEGERI YANG BERKENAAN
	if($jpep=='UPSRC'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE TSEKOLAH.KODPPD='$kod'
		AND TSEKOLAH.KODSEK='$kodsek'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSAH.KODSEK=TKELASSEK.KODSEK
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH !='105'
		AND TSEKOLAH.STATUS ='SR'
		AND TKELASSEK.TING='D6'
        group by TSEKOLAH.KODSEK,STATUS");	
		//AND TSEKOLAH.KODSEK NOT IN ('KBA3055','KBC9027','KBA5032','BBC0057','BBC5028','BBC6033','JBD2048','ABA2074','XCC6008','XBA4037','XCC6218','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316','YBB3225','XCA2223')
		//AND TSEKOLAH.KODSEK not in ('BBC0057','JBC1040','BBB8285','BEA8645','NBC2031','NBC4068','NBC2027','NBA4052','NBA4053','JBA8035','ABC6081','BBA8285','JBD2041','JBD2048','CBD4052','ABD4111','ABA0127','BBA1008','BBA3038','BBD5052','BBC0057','YCC3212')	
	} else if($jpep=='PMRC'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE TSEKOLAH.KODPPD='$kod'
		AND TSEKOLAH.KODSEK='$kodsek'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSAH.KODSEK=TKELASSEK.KODSEK
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH !='105'
		AND TSEKOLAH.KODJENISSEKOLAH != '203'
		AND TSEKOLAH.STATUS ='SM'
		AND TSEKOLAH.KODSEK not in ('KFT2002','KHA2001','KHA3004','KEA3119','KHA5002','BHA8002','BRA0001','BEA3087','JEA5027','JHA5001','XHA3102','XHA5102','XHA2001','XEA4303','XRA1001','BHA7001','BHA8002')
		AND TKELASSEK.TING='T3'
        group by TSEKOLAH.KODSEK,STATUS");		
		//AND TSEKOLAH.KODSEK not in ('BEA8612','KEA3118','KEA4069','KHA2001','KHA3003','KHA3004','KHA5002','KHA6002','KKE2156','JEA8014','BRA0001','BHA8002','BEA7622','CHA4001','JEA2046','JHA2001','BEA4643','BEA3086','BEA1074','BEA8612','BHA8004','BEA4634','YHA3101','NHA3001','RHA0002','RHA0003')
	}else if($jpep=='SPMC'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE TSEKOLAH.KODPPD='$kod'
		AND TSEKOLAH.KODSEK='$kodsek'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSAH.KODSEK=TKELASSEK.KODSEK
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH NOT IN ('105','202')
		AND TSEKOLAH.KODSEK NOT IN ('CEA4099','CHA4002','BEA3086','BEA0113','BEA8668','BEA3088','BHA7001','BEB1070','NFT0001','NFT1001','NFT1002','NFT2001','NEA4114')
		AND TSEKOLAH.STATUS ='SM'
		AND TKELASSEK.TING='T5'
        group by TSEKOLAH.KODSEK,STATUS");	
//AND TSEKOLAH.KODSEK not in ('BEA1074','BEA8666','BEA7622','BEA4633','BEA4634','BEA3086','BEA8612','JEA2046','AFT3002','KEA3118','DFT0001','DFT1002','DEA1152','DEA1153','DEA1154','DEA1151','DEA1150','DEA1149','DFT3003','DFT3002','DEA3431','DFT3004','DFT3005','DFT4003','DFT4001','DFT5001','DFT5004','DFT5002','DFT5003','DFT6001','DFT6002')		
	}else if($jpep=='PAT'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE TSEKOLAH.KODPPD='$kod'
		AND TSEKOLAH.KODSEK='$kodsek'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSAH.KODSEK=TKELASSEK.KODSEK
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH NOT IN ('105','202')
		AND TKELASSEK.TING IN ('D5','T4')
        group by TSEKOLAH.KODSEK,STATUS");
		//AND TSEKOLAH.KODSEK NOT IN ('BEA8612','RHA0002','RHA0003','AHA1002','BHA8004','ABD6107','PHA3001')
	}else if($jpep=='PPT'){
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE TSEKOLAH.KODPPD='$kod'
		AND TSEKOLAH.KODSEK='$kodsek'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSAH.KODSEK=TKELASSEK.KODSEK
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH !='105'
		AND TKELASSEK.TING IN ('D5','D6','T4','T5')
        group by TSEKOLAH.KODSEK,STATUS");	
	}else{
	$res4 = OCIParse($conn_sispa,"SELECT TSEKOLAH.KODSEK,STATUS,COUNT(DISTINCT tsah.TING) AS BILSAH,COUNT(DISTINCT tkelassek.TING) AS BILTING
        FROM TSEKOLAH,TSAH,TKELASSEK
        WHERE TSEKOLAH.KODPPD='$kod'
		AND TSEKOLAH.KODSEK='$kodsek'
        AND TSEKOLAH.KODSEK=TSAH.KODSEK
        AND TSAH.TAHUN='$tahun' and TSAH.JPEP='$jpep'
        AND TSAH.KODSEK=TKELASSEK.KODSEK
        AND TSEKOLAH.KODSEK=TKELASSEK.KODSEK
        AND TKELASSEK.TAHUN='$tahun'
		AND TSEKOLAH.KODSTATUSSEKOLAH='01'
		AND TSEKOLAH.KODJENISSEKOLAH !='105'
		AND TSEKOLAH.KODSEK NOT IN ('BEA0113','AEA6159','ABC6081','AKE6159')
		AND TKELASSEK.TING IN ('D5','D6','T4','T5')
        group by TSEKOLAH.KODSEK,STATUS");//AND TKELASSEK.TING!='D2'
		//AND TSEKOLAH.KODSEK NOT IN ('AHA2001','RHA0003','AKE6159','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316')
	}
	//$ressek = OCIParse($conn_sispa,$sql);
	OCIExecute($res4);
	$jumsek=0; $bilxsiapsr=0; $bilxsiapsm=0; $bilkelas=0;$bilsiapsm=0;$bilsiapsr=0;
	while(OCIFetch($res4)){
		$bilkelas = OCIResult($res4,"BILTING");
		$jumlahsah = OCIResult($res4,"BILSAH");
		$status = OCIResult($res4,"STATUS");//SM/SR
        $jumsek++;
		if($status=='SM'){
			//echo "SM $kodsek :- $jumlahsah / $bilkelas<br>";
			if($jumlahsah < $bilkelas){
				$taksiap = "<center><img src = images/button002.png width=20 height=20></center>";
				$bilxsiapsm++;	
			}
			else{
			 	$siap = "<center><img src = images/button000.png width=20 height=20></center>";
			 	$bilsiapsm++;
			}
		}else{
			if($jumlahsah < $bilkelas){
				//echo "SR $kodsek :- $jumlahsah / $bilkelas<br>";
				$taksiap = "<center><img src = images/button002.png width=20 height=20></center>";
				$bilxsiapsr++;	
			}
			else{
				$siap = "<center><img src = images/button000.png width=20 height=20></center>";
			    $bilsiapsr++;
			}
		}
		//$bilkelas=0;
		
	}//while ressek
	if($jpep=='UPSRC'){
	$res5 = OCIParse($conn_sispa,"select status,count(*) as jum from tsekolah where kodppd='$kod' and kodsek='$kodsek' and kodstatussekolah='01' AND TSEKOLAH.KODJENISSEKOLAH !='105' and TSEKOLAH.STATUS ='SR' and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");		
	//$res5 = OCIParse($conn_sispa,"select status,count(*) as jum from tsekolah where kodppd='$kod' and kodsek='$kodsek' AND KODJENISSEKOLAH!='105' TSEKOLAH.STATUS ='SR' and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");	
	// and AND TSEKOLAH.KODSEK NOT IN ('KBA3055','KBC9027','KBA5032','BBC0057','BBC5028','BBC6033','JBD2048','ABA2074','XCC6008','XBA4037','XCC6218','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316','YBB3225','XCA2223')
	// AND TSEKOLAH.KODSEK not in ('BBC0057','JBC1040','BBB8285','BEA8645','NBC2031','NBC4068','NBC2027','NBA4052','NBA4053','JBA8035','ABC6081','BBA8285','JBD2041','JBD2048','CBD4052','ABD4111','ABA0127','BBA1008','BBA3038','BBD5052','BBC0057','YCC3212')	
	}else if($jpep=='PMRC'){
	$res5 = OCIParse($conn_sispa,"select status,count(*) as jum from tsekolah where kodppd='$kod' and kodsek='$kodsek' AND KODJENISSEKOLAH!='105' AND TSEKOLAH.KODJENISSEKOLAH != '203' and TSEKOLAH.STATUS ='SM' AND TSEKOLAH.KODSEK not in ('KFT2002','KHA2001','KHA3004','KEA3119','KHA5002','BHA8002','BRA0001','BEA3087','JEA5027','JHA5001','XHA3102','XHA5102','XHA2001','XEA4303','XRA1001','BHA7001','BHA8002') and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");
	//AND TSEKOLAH.KODSEK not in ('BEA8612','KEA3118','KEA4069','KHA2001','KHA3003','KHA3004','KHA5002','KHA6002','KKE2156','JEA8014','BRA0001','BHA8002','BEA7622','CHA4001','JEA2046','JHA2001','BEA4643','BEA3086','BEA1074','BEA8612','BHA8004','BEA4634','YHA3101','NHA3001','RHA0002','RHA0003')
	}else if($jpep=='SPMC'){
	$res5 = OCIParse($conn_sispa,"select status,count(*) as jum from tsekolah where kodppd='$kod' and kodsek='$kodsek' AND KODJENISSEKOLAH NOT IN ('105','202') AND TSEKOLAH.KODSEK NOT IN ('CEA4099','CHA4002','BEA3086','BEA0113','BEA8668','BEA3088','BHA7001','BEB1070','NFT0001','NFT1001','NFT1002','NFT2001','NEA4114') and TSEKOLAH.STATUS ='SM' and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");	
// AND TSEKOLAH.KODSEK not in ('BEA1074','BEA8666','BEA7622','BEA4633','BEA4634','BEA3086','BEA8612','JEA2046','AFT3002','KEA3118','DFT0001','DFT1002','DEA1152','DEA1153','DEA1154','DEA1151','DEA1150','DEA1149','DFT3003','DFT3002','DEA3431','DFT3004','DFT3005','DFT4003','DFT4001','DFT5001','DFT5004','DFT5002','DFT5003','DFT6001','DFT6002')		
	}else if($jpep=='PAT'){
	$res5 = OCIParse($conn_sispa,"select status,count(KODSEK) as jum from tsekolah where kodppd='$kod' and kodsek='$kodsek' AND KODJENISSEKOLAH NOT IN ('105','202') and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");
	// AND TSEKOLAH.KODSEK NOT IN ('BEA8612','RHA0002','RHA0003','AHA1002','BHA8004','ABD6107','PHA3001')
	}else if($jpep=='PPT'){
	$res5 = OCIParse($conn_sispa,"select status,count(*) as jum from tsekolah where kodppd='$kod' and kodsek='$kodsek' AND KODJENISSEKOLAH!='105' and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");
	}else{
	$res5 = OCIParse($conn_sispa,"select status,count(*) as jum from tsekolah where kodppd='$kod' and kodsek='$kodsek' AND KODJENISSEKOLAH!='105' AND TSEKOLAH.KODSEK NOT IN ('BEA0113','AEA6159','ABC6081','AKE6159') and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status");
	// AND TSEKOLAH.KODSEK NOT IN ('AHA2001','RHA0003','AKE6159','XBA3309','XBA3327','XBA3307','XBA3315','XBA3316')
	}
	//$www = "select status,count(*) as jum from tsekolah where kodppd='$kod' and kodsek='$kodsek' AND KODJENISSEKOLAH!='105' and kodsek not in ( select kodsek from tsah where tahun='$tahun' and jpep='$jpep') group by status";
	OCIExecute($res5);
	while(OCIFetch($res5)){
		$jumsek+=OCIResult($res5,"JUM");
		$biltaksahall = OCIResult($res5,"JUM");
		$statussekolah = OCIResult($res5,"STATUS");
		//echo "$statussekolah :- $biltaksahall <br>";
		if($statussekolah=='SM'){
			$taksiap = "<center><img src = images/button002.png width=20 height=20></center>";
			$bilxsiapsm+= $biltaksahall;
		}
		if($statussekolah=='SR'){
			$taksiap = "<center><img src = images/button002.png width='20' height='20'></center>";
			$bilxsiapsr+= $biltaksahall;
		}
	}
	$bil3++;
	echo "<tr bgcolor='#D8DFFC' onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '#D8DFFC'\"><td>$bil3</td> \n";
	echo "<td>$namasek</td>";	
	echo "<td align='center'>$taksiap</td>";
	echo "<td align='center'>$siap</td></tr>";		
	$jumsek=0; $bilxsiapsm=0; $bilxsiapsr=0; $bilkelas=0; $siap=""; $taksiap="";
}
echo "</table>";
echo "</td></tr></table>";	
}

?>