<?php
//$Main->REF_URUSAN = 1;


if($Main->bypassJadwal == TRUE){
	$tergantungBypass =	PanelIcon($Link="pages.php?Pg=bypassTahap_v2",$Image="module.png",$Isi="Jadwal");
}else{
 	$tergantungBypass =	PanelIcon($Link="pages.php?Pg=ref_tahap_anggaran",$Image="module.png",$Isi="Jadwal");
}

$Main->Isi="
<table border=0 cellspacing=4 width=60%>
	<tr>
	<td valign=top>
		<table border=0 cellspacing=0 width=100% class=\"adminform\">
			<tr><th colspan=8>PERENCANAAN</th></tr>
			<tr><td width=10% valign=top>
				".
				$tergantungBypass.
				PanelIcon($Link="pages.php?Pg=rka-ppkd-1_v2",$Image="module.png",$Isi="RKA-PPKD").
				PanelIcon($Link="pages.php?Pg=tandaTanganKuasaPenggunaBarang_v3",$Image="module.png",$Isi="TANDA TANGAN").
				"</td>
				<td width=5% valign=top>
				".
				
				"</td>
		<td width=10% valign=top>
		".PanelIcon($Link="pages.php?Pg=plafon_v2",$Image="module.png",$Isi="Plafon").
				PanelIcon($Link="pages.php?Pg=r-apbd_v2",$Image="module.png",$Isi="R-APBD").
				
		PanelIcon($Link="pages.php?Pg=settingPerencanaan",$Image="module.png",$Isi="Pengaturan Perencanaan").
			"</td>
				<td width=5% valign=top>
				".	
		
		"
		".
		$Vref_urusan.
		$Vrefskpd_urusan.
        
		
		"</td>
		<td width=10% valign=top>
		".
		
		PanelIcon($Link="pages.php?Pg=renja_v2",$Image="module.png",$Isi="Renja").
		PanelIcon($Link="pages.php?Pg=apbd_v2",$Image="module.png",$Isi="APBD").
		
		PanelIcon($Link="pages.php?Pg=spd_v2",$Image="module.png",$Isi="SPD").
		
		"</td>
		<td width=10% valign=top>
		

		</td>
		<td width=10% valign=top>
		".
		
		PanelIcon($Link="pages.php?Pg=rkbmdPengadaan_v2",$Image="module.png",$Isi="RKBMD").
		PanelIcon($Link="pages.php?Pg=dpa-skpd-2.2.1_v2",$Image="module.png",$Isi="DPA-SKPD").
				

		"</td>
		<td width=10% valign=top>
		".
		PanelIcon($Link="pages.php?Pg=rka-skpd-2.2.1_v2",$Image="module.png",$Isi="RKA-SKPD").
		PanelIcon($Link="pages.php?Pg=dpa-ppkd-1_v2",$Image="module.png",$Isi="DPA-PPKD").
		"
		</td>
		</tr>
		</table>
	</td>
	</tr>
</table>


		";
?>