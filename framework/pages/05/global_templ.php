<?php

$Act2 = cekPOST('Act2','');
$ViewList = cekPOST('ViewList',1);
$ViewEntry = cekPOST('ViewEntry',0);

//$cek .= '<br> $baru 1='.$Baru;
//proses -> get main->entry --------------------------------------------------------------------
/*
if ($Act == 'Penghapusan_TambahEdit' || $Act == 'Penghapusan_Simpan' || $Act2 == 'Penghapusan'){	
//if ( $Act2 == 'Penghapusan' ){
	include('pages/'.$Pg.'/set_penghapusan.php');
}else if ($Act == 'Pemanfaatan_TambahEdit' || $Act == 'Pemanfaatan_Simpan'){
//}else if ($Act2 == 'Pemanfaatan'){
	include('pages/'.$Pg.'/set_pemanfaatan.php');		
}else{	
	include('pages/'.$Pg.'/entryidi_lama.php');
}
*/
if ($Act == 'Hapus'){
	Penatausahaan_Proses();
}
	
switch($SPg){
	case "03":$spg ='listbi_cetak'; $titleCaption = 'Buku Inventaris Barang'; break;	
	case "04":$spg ='kib_a_cetak'; $titleCaption = 'KIB A Tanah'; break;	
	case "05":$spg ='kib_b_cetak'; $titleCaption = 'KIB B Peralatan dan Mesin'; break;	
	case "06":$spg ='kib_c_cetak'; $titleCaption = 'KIB C Gedung dan Bangunan'; break;	
	case "07":$spg ='kib_d_cetak'; $titleCaption = 'KIB D JALAN, IRIGASI, DAN JARINGAN'; break;	
	case "08":$spg ='kib_e_cetak'; $titleCaption = 'KIB E ASET TETAP LAINNYA'; break;	
	case "09":$spg ='kib_f_cetak'; $titleCaption = 'KIB F KONSTRUKSI DALAM PENGERJAAN'; break;	
	//case "10":$spg ='kib_f_cetak';break;	
	case "11":$spg ='rekap_bi_cetak';break;
}
/*$Main->ListData->ToolbarBawah =
	"<table width=\"100%\" class=\"menudottedline\">
	<tr><td>
		<table width=\"50\"><tr>
		<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=$spg';adminForm.target='_blank';adminForm.submit();adminForm.target=''","print_f2.png","Halaman")."</td>
		<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=$spg&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();adminForm.target=''","print_f2.png","Semua")."</td>
		</tr></table>
	</td></tr>
	</table> ";
	*/
if(empty($ridModul05)){
	$ToolbarAtas_edit = 
			"<td>".PanelIcon1("?Pg=$Pg&SPg=setmutasi","mutasi.png","Mutasi")."</td>
			<td>".PanelIcon1("javascript:prosesBaru()","new_f2.png","Baru")."</td>
			<td>".PanelIcon1("javascript:prosesEdit()","edit_f2.png","Ubah")."</td>
			<td>".PanelIcon1("javascript:prosesHapus()","delete_f2.png","Delete")."</td>";
}
$Main->ListData->ToolbarATas=
	"<!-- toolbar atas -->
			<div style='float:right;'>
				<script>
					function Penatausahaan_CetakHal(){
						adminForm.action='?Pg=PR&SPg=$spg';
						adminForm.target='_blank';
						adminForm.submit();		
						adminForm.target='';
					}
					function Penatausahaan_CetakAll(){
						adminForm.action='?Pg=PR&SPg=$spg&ctk=1';
						adminForm.target='_blank';
						adminForm.submit();
						adminForm.target='';
					}
				</script>			
				<table width='125'><tr>
					$ToolbarAtas_edit
					<td>".PanelIcon1("javascript:cetakBrg()","print_f2.png","Barang")."</td>
					<td>".PanelIcon1("javascript:Penatausahaan_CetakHal()","print_f2.png","Halaman")."</td>
					<td>".PanelIcon1("javascript:Penatausahaan_CetakAll()","print_f2.png","Semua")."</td>						
					<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SDest=XLS&SPg=$spg&ctk=<!--jmlData-->';adminForm.target='_blank';adminForm.submit();","export_xls.png","Excel")."</td>						

				</tr></table>			
			</div>
	";
	
$Main->ListData->Title = 
	"<table class=\"adminheading\">
	<tr>
	  <th height=\"47\" class=\"user\">".$titleCaption."</th>
	  <th>
	  	".$Main->ListData->ToolbarATas."
	  </th>
	</tr>
	</table>
	";
	
//<td>".( empty($ridModul09) && empty($disModul09) ? PanelIcon3("javascript:setPenghapusan()","PENGHAPUSAN") : PanelIcon3("javascript:alert('User tidak diijinkan melakukan Penghapusan Barang')","PENGHAPUSAN") )."</td>
//<td>".( empty($ridModul09) && empty($disModul09) ? PanelIcon3("javascript:setPenghapusan()","PENGHAPUSAN") : "" )."</td>
//<td>".( PanelIcon3("javascript:setPenghapusan()","PENGHAPUSAN")  )."</td>
//<td>".( PanelIcon3("javascript:setPenghapusan()","PENGHAPUSAN",  '',  $disModul09 )  )."</td>
//if(($Act =='')&&($Baru=='') ) {
	$Main->ListData->ToolbarBawah =
	"<table width=\"100%\" class=\"menudottedline\">
	<tr><td>
		<table width=\"70\"><tr>		
		
		<td>".( PanelIcon3("javascript:setPenghapusan()","PENGHAPUSAN",  $ridModul09,  $disModul09 )  )."</td>
		<!-- <td>".( PanelIcon3("javascript:setPemanfaatan()","PEMANFAATAN",  $ridModul06,  $disModul06 )  )."</td> -->
		
		</tr></table>
	</td></tr>
	</table> 
	<script language='javascript'>
		
		function setPenghapusan(){
			errmsg = '';
			if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';}
			if((errmsg=='') && (adminForm.boxchecked.value == 0 )){	errmsg= 'Data belum dipilih!';}
			if(errmsg ==''){
				//adminForm.action='?Pg=$Pg&SPg=$SPg';
				//adminForm.Act2.value='Penghapusan';
				//adminForm.Baru.value=1;
				
				
				//adminForm.ViewList.value=0;
				//adminForm.ViewEntry.value = 1;
				//adminForm.action='?Pg=09&SPg=01';
				adminForm.action='?Pg=09&SPg=03';
				adminForm.target='_blank';
				
				
				adminForm.Act.value='Penghapusan_TambahEdit';				
				adminForm.Penghapusan_Baru.value='1';
				
				adminForm.submit();
				adminForm.target='';
			}else{
				alert(errmsg);
			}
			
		}
		function setPemanfaatan(){
			errmsg = '';
			if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';}
			if((errmsg=='') && (adminForm.boxchecked.value == 0 )){	errmsg= 'Data belum dipilih!';}
			if(errmsg ==''){
				adminForm.action='?Pg=$Pg&SPg=$SPg';
				adminForm.target='';
				adminForm.Act.value='Pemanfaatan_TambahEdit';				
				adminForm.Baru.value='1';
				adminForm.submit();
			}else{
				alert(errmsg);
			}
			
		}
	</script>
	";

/*
}else{
	

	

$Main->ListData->labelbarang=
	"<table width=200 align=right style='border:1px solid #ddd;margin:0 8 0 0; ' cellpadding=0 cellspacing=0>
				<tr><td>
				<INPUT TYPE=TEXT value='$fmKEPEMILIKAN' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934' readonly>.
				<INPUT TYPE=TEXT value='{$Main->Provinsi[0]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				
				<INPUT TYPE=TEXT value='00' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='$fmSKPD' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='$fmUNIT' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT id='infofmTAHUNPEROLEHAN' TYPE=TEXT value='".substr($fmTAHUNPEROLEHAN,2,2)."' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='$fmSUBUNIT' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>
				</td></tr>
			
				<tr><td>
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[0]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934' readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[1]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[2]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[3]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[4]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#C64934'  readonly>.
				<INPUT id='infofmREGISTER' TYPE=TEXT value='$fmREGISTER' style='width:55px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#0000CC'  readonly>
				</td></tr>
			</table>";
}
*/
//$stim = time()-$tim; $tim =time(); echo "<br>before tampil skpd $stim";
$Main->ListData->OptWil =
	"<!--wil skpd-->
	<table width=\"100%\" class=\"adminform\">	<tr>		
		<td width=\"100%\" valign=\"top\">			
			".WilSKPD1()."
		</td>
		<td >
			".$Main->ListData->labelbarang."	
		</td>
	</tr></table>";
//$stim = time()-$tim; $tim =time(); echo "<br>after tampilskpd $stim";
	
$Hidden = "	
	<input type=hidden name='PrevPageParam' value='index.php?Pg=$Pg&SPg=$SPg'>
	<input type=hidden name='Act' value=''>
	<input type=hidden name='Act2' value=$Act2>
	<input type=hidden name='Baru' value='$Baru'>
	<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
	<input type=hidden name='fmIDLama' id='fmIDLama' value='$fmIDLama'>
	<input type=hidden name='ViewList' value='$ViewList' >
	<input type=hidden name='ViewEntry' value='$ViewEntry' >
	<!--<input type='hidden' id='fmKONDBRG' name='fmKONDBRG' value='".$_POST['fmKONDBRG']."'>-->
	";


$Entry_Script = "
	<script language='javascript'>
		function prosesBaru(){
			//alert('Tes');
			//fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmIDREKENING,fmKET,fmTAHUNANGGARAN
			//adminForm.action = '?Pg=$Pg&SPg=$SPg';
			//adminForm.action='?Pg=$Pg&SPg=barangProses';
			adminForm.action='index.php?Pg=05&KIB=$SPg&SPg=barangProses';
			adminForm.Baru.value = '1';
			adminForm.Act.value = 'Baru';			
			adminForm.target = '_blank';
			adminForm.submit();
			adminForm.Baru.value = '';
			adminForm.Act.value='';
			adminForm.target = '';
		}
		function prosesEdit(){
			//alert(adminForm.fmSUBUNIT.value);
			errmsg = '';			
			if((errmsg=='') && (adminForm.boxchecked.value >1 )){
				//errmsg= 'Pilih Hanya Satu Data!';
			}
			if((errmsg=='') && (adminForm.boxchecked.value == 0 )){
				errmsg= 'Data belum dipilih!';
			}
			
			if(errmsg ==''){	
				for(var i=0; i < ".$Main->PagePerHal."; i++){
					var str = 'document.adminForm.cb' + i; 					
					if (eval(str)){
						box = eval( str );	//alert( i+' '+ box.value);
					
						if( box.checked){			
							//total += box.value + ' ';	
					
							adminForm.Act.value='Edit';
							document.getElementById('fmIDLama').value=box.value;
							//adminForm.action='index.php?Pg=05&SPg=barangProses&byId='+box.value;				
							adminForm.action='index.php?Pg=05&KIB=$SPg&SPg=barangProses';
							adminForm.target = '_blank';
							adminForm.submit();
						}
					}
				}
				
				/*
				//var total='';
				for(var i=0; i < 25; i++){
					box = eval( 'document.adminForm.cb' + i );
					if( box.checked){						
						//total += box.value + ' ';	
						adminForm.Act.value='Edit';
						adminForm.target = '_blank';
						adminForm.submit();
					}
					
				}
				*/
				//alert( total ); 			
				
				/*adminForm.Act.value='Edit';				
				adminForm.action='?Pg=$Pg&SPg=$SPg';
				adminForm.target = '_blank';
				adminForm.submit();*/
				adminForm.Act.value='';
				adminForm.target = '';
				
				/*adminForm.action='?Pg=05&SPg=barangProses';				
				adminForm.target = '_blank';*/
				
				/*
				//post to iframe -> frameedit
				adminForm.action='?Pg=05&SPg=barangProses';
				adminForm.target='frameedit';*/
				
				
			}else{
				alert(errmsg);
			}
		}
		function prosesHapus(){
			
			if (adminForm.boxchecked.value >0 ){
			if(confirm('Yakin '+adminForm.boxchecked.value+' data akan di hapus??')){
				document.body.style.overflow='hidden';
				addCoverPage('coverpage',100);
				adminForm.action = '?Pg=$Pg&SPg=$SPg';
				adminForm.Act.value='Hapus';
				adminForm.target = '';
				adminForm.submit();
			}
			}
		}
		function cetakBrg(){
			//alert(adminForm.fmSUBUNIT.value);
			errmsg = '';
			
			if((errmsg=='') && (adminForm.boxchecked.value >1 )){
				errmsg= 'Pilih Hanya Satu Data!';
			}
			if((errmsg=='') && (adminForm.boxchecked.value == 0 )){
				errmsg= 'Data belum dipilih!';
			}
			
			if(errmsg ==''){				
				//adminForm.action='?Pg=PR&SPg=brg_cetak';
				if (document.getElementById('cbxDlmRibu').checked ){
							adminForm.action = 'index.php?Pg=PR&SPg=brg_cetak&cbxDlmRibu=1';
						}else{
							adminForm.action = 'index.php?Pg=PR&SPg=brg_cetak';
						}
				
				//adminForm.Act.value='Edit';
				adminForm.target = '_blank';
				adminForm.submit();
				
				
			}else{
				alert(errmsg);
			}
		}
	</script>";

	
$toolbar_bawah = $Main->ListData->ToolbarBawah;	
/*
if ($Act == 'Pemanfaatan_TambahEdit' || $Act == 'Pemanfaatan_Simpan' ){
	$opt_wil = 	'';
	$toolbar_atas = 
		"<table class=\"adminheading\"><tr>
	  		<th height=\"47\" class=\"user\">Input Pemanfaatan Barang</th>	  		
		</tr></table>
		";
}else if ($Act == 'Penghapusan_TambahEdit' || $Act == 'Penghapusan_Simpan' || $Act2 == 'Penghapusan' ){
	$opt_wil = 	'';
	$toolbar_atas = 
		"<table class=\"adminheading\"><tr>
	  		<th height=\"47\" class=\"user\">Entry Penghapusan</th>	  		
		</tr></table>
		";
	$toolbar_bawah = '';
}else{*/
	$opt_wil = 	$Main->ListData->OptWil;
	$toolbar_atas = $Main->ListData->Title;
//}



?>