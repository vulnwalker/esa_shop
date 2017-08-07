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
				PanelIcon($Link="pages.php?Pg=settingPerencanaan_v3",$Image="module.png",$Isi="PENGATURAN").
				PanelIcon($Link="pages.php?Pg=tandaTanganKuasaPenggunaBarang_v3",$Image="module.png",$Isi="TANDA TANGAN").
				"</td>
				<td width=5% valign=top>
				".
				
				"</td>
		<td width=10% valign=top>
		".PanelIcon($Link="pages.php?Pg=renja_v3",$Image="module.png",$Isi="RENJA").
				
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
		
		PanelIcon($Link="pages.php?Pg=rkbmdPengadaan_v3",$Image="module.png",$Isi="RKBMD").
		
		
		
		"</td>
		<td width=10% valign=top>
		
		".PanelIcon($Link="pages.php?Pg=koreksiPenggunaPengadaan",$Image="module.png",$Isi="KOREKSI PENGGUNA")."

		</td>
		<td width=10% valign=top>
		".PanelIcon($Link="pages.php?Pg=koreksiPengelolaPengadaan",$Image="module.png",$Isi="KOREKSI PENGELOLA")."
		</td>
		<td width=10% valign=top>
		".PanelIcon($Link="pages.php?Pg=ref_std_butuh_v3",$Image="module.png",$Isi="Standar Kebutuhan Barang Maksimal")."
		</td>
		</tr>
		</table>
	</td>
	</tr>
</table>


		";
?>