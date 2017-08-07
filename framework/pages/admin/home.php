<?php
if($Main->MODUL_PEMBUKUAN){
	$menuLogImport = PanelIcon($Link="pages.php?Pg=DaftarImport",$Image="sections.png",$Isi="Daftar Import");	
}
$Main->Isi="
<table border=0 cellspacing=4 width=50%>
	<tr>
	<td valign=top>
		<table border=0 cellspacing=0 width=100% class=\"adminform\">
			<tr><th colspan=4>INPUT DATA</th></tr>
			<tr><td valign=top>
				".PanelIcon($Link="?Pg=$Pg&SPg=01#ISIAN",$Image="sections.png",$Isi="Administrasi User").
				PanelIcon($Link="pages.php?Pg=useraktivitas",$Image="sections.png",$Isi="User Online").
				PanelIcon($Link="pages.php?Pg=refmigrasi",$Image="sections.png",$Isi="Migrasi").				
				PanelIcon($Link="pages.php?Pg=ManagementSystem",$Image="sections.png",$Isi="Management System").	
				PanelIcon($Link="pages.php?Pg=ManagementPengguna",$Image="sections.png",$Isi="Management Pengguna")."	
				</td>
				<td valign=top>
				".PanelIcon($Link="pages.php?Pg=backup",$Image="sections.png",$Isi="Export/Backup Data").
				PanelIcon($Link="pages.php?Pg=refclosingdata",$Image="sections.png",$Isi="Closing Data").
				PanelIcon($Link="pages.php?Pg=refperbandinganhasil",$Image="sections.png",$Isi="Perbandingan Hasil").
				PanelIcon($Link="pages.php?Pg=ManagementModulSystem",$Image="sections.png",$Isi="Management Modul System")."
				
				<td>
				<td valign=top>
				".PanelIcon($Link="pages.php?Pg=refdatalra",$Image="sections.png",$Isi="Data LRA").
				PanelIcon($Link="pages.php?Pg=ManagementStrukturMenu",$Image="sections.png",$Isi="Management Struktur Menu").
				PanelIcon($Link="pages.php?Pg=ManagementShortcut",$Image="sections.png",$Isi="Management Shortcut").
				"
				$menuLogImport
				</td>
			</tr>
		</table>
	</td>
	</tr>
</table>

		";
// 				".PanelIcon($Link="?Pg=$Pg&SPg=03#ISIAN",$Image="sections.png",$Isi="Import/Restore Data")."

?>