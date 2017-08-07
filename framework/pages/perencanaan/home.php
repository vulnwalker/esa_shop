<?php
//$Main->REF_URUSAN = 1;

$Main->Isi="
<table border=0 cellspacing=4 width=60%>
	<tr>
	<td valign=top>
		<table border=0 cellspacing=0 width=100% class=\"adminform\">
			<tr><th colspan=8>PERENCANAAN</th></tr>
			<tr><td width=10% valign=top>
				".
				PanelIcon($Link="pages.php?Pg=ref_tahap_anggaran",$Image="module.png",$Isi="Jadwal").
				PanelIcon($Link="pages.php?Pg=rka-ppkd",$Image="module.png",$Isi="RKA-PPKD").
				"</td>
				<td width=5% valign=top>
				".
				
				"</td>
		<td width=10% valign=top>
		".PanelIcon($Link="pages.php?Pg=plafon",$Image="module.png",$Isi="Plafon").
				PanelIcon($Link="pages.php?Pg=r-apbd",$Image="module.png",$Isi="R-APBD").
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
		
		PanelIcon($Link="pages.php?Pg=renja",$Image="module.png",$Isi="Renja").
		PanelIcon($Link="pages.php?Pg=apbd",$Image="module.png",$Isi="APBD").
		
		
		
		"</td>
		<td width=10% valign=top>
		

		</td>
		<td width=10% valign=top>
		".
		
		PanelIcon($Link="pages.php?Pg=rkbmdPengadaan",$Image="module.png",$Isi="RKBMD").
		PanelIcon($Link="pages.php?Pg=apbd",$Image="module.png",$Isi="DPA").
				

		"</td>
		<td width=10% valign=top>
		".
		PanelIcon($Link="pages.php?Pg=rka-skpd",$Image="module.png",$Isi="RKA-SKPD").
		PanelIcon($Link="pages.php?Pg=spd",$Image="module.png",$Isi="SPD").
		"
		</td>
		</tr>
		</table>
	</td>
	</tr>
</table>


		";
?>