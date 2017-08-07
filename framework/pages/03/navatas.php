<?php
//NAVIGASI
$NavRight = "";
if(!empty($SPg))
{
$NavRight = "

	<td width=80% class=\"menudottedline\" >&nbsp</td>
	<td align=right  class=\"menudottedline\" >
	".PanelIcon1($Link="?Pg=$Pg",$Image="save_f2.png",$Isi="Simpan")."
	</td>
	<td align=right  class=\"menudottedline\" >
	".PanelIcon1($Link="?Pg=$Pg",$Image="cancel_f2.png",$Isi="Tutup")."
	</td>

";
}
$Main->NavAtas = "
<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
<tr>
	<td class=\"menudottedline\" width=\"40%\" height=\"52\"><B>
	&nbsp;{$HTTP_COOKIE_VARS['coNama']}&nbsp;&nbsp;>>&nbsp;&nbsp;{$HTTP_COOKIE_VARS['coSebagai']} | 
	<A href=\"?Pg=\">Menu Awal</a> |
	<A href=\"?Pg=LogOut\">Log Out</a> |
	</td>
	$NavRight
</tr>
</table>

";
?>
