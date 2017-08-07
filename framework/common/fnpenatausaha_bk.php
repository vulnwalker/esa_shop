<?php


/************************************************************
* parameter $Pg $SPg $Opt
* - tanpa nav atas &notnavatas=1 --> lihat pages/navatas.php
* - $tampilCbxKeranjang
* - $tampilBidang

**************************************************************/
class PenatausahaObj {
	var $prefix = 'penatausaha';
	var $elContentHal ='penatausaha_cont_hal';
	var $elContentDaftar ='penatausaha_cont_list';
	//var $tampilCbxKeranjang = TRUE;
	var $tampilBidang = FALSE;
				
	function genEntryScriptJS(){
		global $Main, $SPg, $Pg,$jns;
		if ($SPg == '') {
			$SPg = '03';
		}	
		
		return "
	<script type=\"text/javascript\" src=\"js/wilayah.js\" language=\"JavaScript\"></script>
		<script language='javascript'>
		
		function prosesBaru(){
			//alert('Tes');
			//fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmIDREKENING,fmKET,fmTAHUNANGGARAN
			//adminForm.action = '?Pg=$Pg&SPg=$SPg';
			//adminForm.action='?Pg=$Pg&SPg=barangProses';
			adminForm.action='index.php?Pg=05&KIB=$SPg&SPg=barangProses&jns=$jns';
			adminForm.Baru.value = '1';
			adminForm.Act.value = 'Baru';			
			adminForm.target = '_blank';
			adminForm.submit();
			adminForm.Baru.value = '';
			adminForm.Act.value='';
			adminForm.target = '';
		}
		function prosesEdit_(id){
			//alert(id);
			adminForm.Act.value='Edit';
							document.getElementById('fmIDLama').value=id;//box.value;
							//adminForm.action='index.php?Pg=05&SPg=barangProses&byId='+id;				
							adminForm.action='index.php?Pg=05&KIB=$SPg&SPg=barangProses';
							adminForm.target = '_blank';
							adminForm.submit();
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
							
							//--- open blank di chrome ga bisa, nunggu looping beres 
							adminForm.Act.value='Edit';
							document.getElementById('fmIDLama').value=box.value;
							//adminForm.action='index.php?Pg=05&SPg=barangProses&byId='+box.value;				
							adminForm.action='index.php?Pg=05&KIB=$SPg&SPg=barangProses&jns=$jns';
							adminForm.target = '_blank';
							adminForm.submit();
							
							//setTimeout(  'prosesEdit_('+box.value+')', 1000);
							//alert(i+' ' +box.value);
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
			/*
			var spg_ = get_url_param('SPg');
			if (spg_ =='04' || spg_=='06'){
				var spg= 'brg_cetak2';
			}else{
				var spg= 'brg_cetak';
			}
			*/
			var spg= 'brg_cetak2';
			
			if(errmsg ==''){				
				//adminForm.action='?Pg=PR&SPg=brg_cetak';
				if (document.getElementById('cbxDlmRibu').checked ){
					//adminForm.action = 'index.php?Pg=PR&SPg=brg_cetak&cbxDlmRibu=1';
					adminForm.action = 'index.php?Pg=PR&SPg='+spg+'&cbxDlmRibu=1';
				}else{
					//adminForm.action = 'index.php?Pg=PR&SPg=brg_cetak';
					adminForm.action = 'index.php?Pg=PR&SPg='+spg;
				}
				
				//adminForm.Act.value='Edit';
				adminForm.target = '_blank';
				adminForm.submit();
				
				
				}else{
				alert(errmsg);
			}
		}
		</script>";
		
	}
	function genHidden(){
		$Pg = $_GET['Pg'];
		$SPg = $_GET['SPg']; if ($SPg =='') $SPg = '03';
		$Act2 = $_POST['Act2'];
		$fmIDLama = $_POST['fmIDLama'];
		$ViewList = $_POST['ViewList'];
		$ViewEntry = $_POST['ViewEntry'];
		//$tipebi	= $_REQUEST['tipebi'];
		
		return		
		"<input type=hidden id='PrevPageParam' name='PrevPageParam' value='index.php?Pg=$Pg&SPg=$SPg'>
		<input type=hidden id='Act' name='Act' value=''>
		<input type=hidden id='Act2' name='Act2' value=$Act2>
		<input type=hidden id='Baru' name='Baru' value='$Baru'>
		<input type=hidden id='boxchecked' name='boxchecked' value=\"0\" />
		<input type=hidden name='fmIDLama' id='fmIDLama' value='$fmIDLama'>
		<input type=hidden id='ViewList' name='ViewList' value='$ViewList' >
		<input type=hidden id='ViewEntry' name='ViewEntry' value='$ViewEntry' >
		<input type=hidden id='GetSPg' name='GetSPg' value='$SPg' >";
		//<input type=hidden id='tipebi' name='tipebi' value='$tipebi' >";
	}
	
	function genTitleCetak($SPg='listbi_cetak', $fmKONDBRG=3, $tipe=''){
		global $Main;
		//$fmKONDBRG = $_POST['fmKONDBRG'];//
		$tipe = $_REQUEST['tipe'];
		switch ($SPg) {    		
			case "kib_a_cetak": 
				if($tipe=='kertaskerja'){
					$titleCaption = "KERTAS KERJA SENSUS BMD <br> KARTU INVENTARIS BARANG (KIB) A";		
				}else{
					$titleCaption = $fmKONDBRG==3?
						'ASET LAINNYA - KIB A':
						'KARTU INVENTARIS BARANG (KIB) A <br> TANAH';					
				}
				
				
			break;
			case "kib_b_cetak": 
				if($tipe=='kertaskerja'){
					$titleCaption = "KERTAS KERJA SENSUS BMD <br> KARTU INVENTARIS BARANG (KIB) B";		
				}else{
					$titleCaption = $fmKONDBRG==3?
					'ASET LAINNYA  - KIB B':
					'KARTU INVENTARIS BARANG (KIB) B <br> PERALATAN DAN MESIN';		 
				}
			break;
			case "kib_c_cetak": 
				if($tipe=='kertaskerja'){
					$titleCaption = "KERTAS KERJA SENSUS BMD <br> KARTU INVENTARIS BARANG (KIB) C";		
				}else{
					$titleCaption = $fmKONDBRG==3?
					'ASET LAINNYA  - KIB C':
					'KARTU INVENTARIS BARANG (KIB) C <BR> GEDUNG DAN BANGUNAN';		 				 
				}
			break;
			case "kib_d_cetak": 
				if($tipe=='kertaskerja'){
					$titleCaption = "KERTAS KERJA SENSUS BMD <br> KARTU INVENTARIS BARANG (KIB) D";		
				}else{
					$titleCaption = $fmKONDBRG==3?
					'ASET LAINNYA  - KIB D':
					'KARTU INVENTARIS BARANG (KIB) D <BR>JALAN, IRIGASI, DAN JARINGAN';		 
				}
			break;
			case "kib_e_cetak": 
				if($tipe=='kertaskerja'){
					$titleCaption = "KERTAS KERJA SENSUS BMD <br> KARTU INVENTARIS BARANG (KIB) E";		
				}else{
					$titleCaption = $fmKONDBRG==3?
					'ASET LAINNYA  - KIB E':
					'KARTU INVENTARIS BARANG (KIB) E <BR>ASET TETAP LAINNYA';		
				}
			break;
			case "kib_f_cetak": $titleCaption = $fmKONDBRG==3?
			'ASET LAINNYA  - KIB F':
			'KARTU INVENTARIS BARANG (KIB) F <BR>KONSTRUKSI DALAM PENGERJAAN';		
			break;
			case "kib_g_cetak": 
				if($tipe=='kertaskerja'){
					$titleCaption = "KERTAS KERJA SENSUS BMD <br> KARTU INVENTARIS BARANG ASET TAK BERWUJUD";		
				}else{
					$titleCaption = $fmKONDBRG==3?
						'ASET TAK BERWUJUD':
						'KARTU INVENTARIS BARANG <br> ASET TAK BERWUJUD';					
				}
				
				
			break;			  
			case "belumsensus": 
				
				if($tipe=='kertaskerja'){
					//require_once('daftarobj.php');
					//require_once('fnsensus.php');
					
					$thnskr = date('Y');		
					$tahun_sensus = $Main->thnsensus_default;
					while ( ($tahun_sensus+ $Main->periode_sensus) <= $thnskr  ){
						$tahun_sensus+= $Main->periode_sensus;
					}
					//$thnsensus = $SensusTmp->getTahunSensus();
					$titleCaption = "KERTAS KERJA <br> SENSUS BARANG MILIK PEMERINTAH <br>PROVINSI JAWA BARAT TAHUN $tahun_sensus";		
					//$titleCaption = "KERTAS KERJA SENSUS BMD <br> KARTU INVENTARIS BARANG ";		
				}else{
					$titleCaption = 'Sensus - Belum Cek';			
				}
				
			break; 
			case "KIR": 
				$titleCaption = 'Kartu Inventaris Ruang';			
			break; 
			case "KIP": 
				$titleCaption = 'Kartu Inventaris Pegawai';			
			break; 
			default: $titleCaption = $fmKONDBRG==3?
			'ASET LAINNYA ':
			'Buku Inventaris Barang';					        
			break;
		}
		return $titleCaption;
	}
	
	function genTitleCaption($SPg='03', $tipebi=''){
		$fmKONDBRG = $_POST['fmKONDBRG'];
		$jns = $_REQUEST['jns'];
		//$tipebi = $_REQUEST['tipebi'];
		switch ($SPg) {   
			case 'KIP':
				$titleCaption = 'Kartu Inventaris Pegawai';						
			break;
			case 'KIR':
				$titleCaption = "Kartu Inventaris Ruang";						
			break;
			case "belumsensus": 
				$titleCaption = "Sensus - Belum Cek (<span id='titleBelumSensus' name='titleBelumSensus'></span>)";						
			break;
			case "04": 
				$titleCaption = $fmKONDBRG==3?	'Aset Lainnya - KIB A':	'KIB A Tanah';		
				//$titleCaption = $tipe =='pilih'? 'Pilihan - '.$titleCaption : $titleCaption ;		
			break;
			case "05": 
				$titleCaption = $fmKONDBRG==3?	'Aset Lainnya - KIB B':	'KIB B Peralatan dan Mesin'; 
				
			break;
			case "06": $titleCaption = $fmKONDBRG==3?
			'Aset Lainnya - KIB C':
			'KIB C Gedung dan Bangunan'; 				 
			break;
			case "07": $titleCaption = $fmKONDBRG==3?
			'Aset Lainnya - KIB D':
			'KIB D JALAN, IRIGASI, DAN JARINGAN'; 
			break;
			case "08": $titleCaption = $fmKONDBRG==3?
			'Aset Lainnya - KIB E':
			'KIB E ASET TETAP LAINNYA'; 
			break;
			case "09": $titleCaption = $fmKONDBRG==3?
			'Aset Lainnya - KIB F':
			'KIB F KONSTRUKSI DALAM PENGERJAAN'; 
			break;   
			case "kibg": $titleCaption = $fmKONDBRG==3?
			'Aset Tidak Berwujud':
			'ASET TAK BERWUJUD'; 
			break;   
			default: 
				$titleCaption = $fmKONDBRG==3?	'Aset Lainnya':	'Buku Inventaris Barang';					        
				//$titleCaption .= $_REQUEST['tipe'];
				//$titleCaption = $tipe =='pilih'? 'Pilihan - '.$titleCaption: $titleCaption ;		
			break;
		}
		//$titleCaption = $tipebi =='pilih'? 'Pilihan - '.$titleCaption : $titleCaption ;		
		if ($SPg=='03')
		{
			if ($jns=='intra')
			{
				$titleCaption = $titleCaption.' (Intrakomptabel) ';
			} else if ($jns=='ekstra')
			{
				$titleCaption = $titleCaption.' (Ekstrakomptabel) ';
			}  else if ($jns=='lain')
			{
				$titleCaption = "Aset Lain - lain";
			} 	
		}
		return $titleCaption;
	}
	
	function genTitle($SPg = '03', $ToolbarAtas=''){
		//$tipebi= $_REQUEST['tipebi'];
		//$ToolbarAtas =  $tipebi==''? $ToolbarAtas: '';
		$titleCaption= $this->genTitleCaption($SPg);
		
		return 
		"<table class=\"adminheading\">
		<tr>
		<th height=\"47\" class=\"user\">" . $titleCaption . "</th>
		<th>" . 
		$ToolbarAtas . 
		" </th>
		</tr>
		</table>";
		
	}
	function genToolbarAtas(){
		global $ridModul05,$Main ;
		$jns = $_REQUEST['jns'];
		switch ($_GET['SPg']) {
			case "KIP": $spg = 'KIP'; break;
			case "KIR": $spg = 'KIR'; break;
			case "belumsensus": $spg = 'belumsensus'; break;
			case "03": $spg = 'listbi_cetak'; break;
			case "04": $spg = 'kib_a_cetak'; $cetak_kk = "<td>" . PanelIcon1("javascript:Penatausahaan_CetakKertasKerja()", "print_f2.png", "<br>K. Kerja", '', '', '', '', 'Cetak Kertas Kerja KIB A ') . "</td>";break;
			case "05": $spg = 'kib_b_cetak'; $cetak_kk = "<td>" . PanelIcon1("javascript:Penatausahaan_CetakKertasKerja()", "print_f2.png", "<br>K. Kerja", '', '', '', '', 'Cetak Kertas Kerja KIB B ') . "</td>";break;
			case "06":$spg = 'kib_c_cetak'; $cetak_kk = "<td>" . PanelIcon1("javascript:Penatausahaan_CetakKertasKerja()", "print_f2.png", "<br>K. Kerja", '', '', '', '', 'Cetak Kertas Kerja KIB C ') . "</td>";break;
			case "07":$spg = 'kib_d_cetak'; $cetak_kk = "<td>" . PanelIcon1("javascript:Penatausahaan_CetakKertasKerja()", "print_f2.png", "<br>K. Kerja", '', '', '', '', 'Cetak Kertas Kerja KIB D ') . "</td>";break;
			case "08":$spg = 'kib_e_cetak'; $cetak_kk = "<td>" . PanelIcon1("javascript:Penatausahaan_CetakKertasKerja()", "print_f2.png", "<br>K. Kerja", '', '', '', '', 'Cetak Kertas Kerja KIB E ') . "</td>";break;
			case "09":$spg = 'kib_f_cetak'; break;    		
			case "kibg":$spg = 'kib_g_cetak'; break;    
			case "11":$spg = 'rekap_bi_cetak'; break;
			default : $spg = 'listbi_cetak'; break;
		}
		$cetak_kk ='';
		
		//--- set toolbar atas edit
		$PnlBarcode= $Main->BARCODE_ENABLE ? "<td>" . PanelIcon1("javascript:barcode.cetak()", "barcode.png", "Barcode") . "</td>":"";
		$PnlMutasi = $Main->MODUL_MUTASI ? "<td>" . PanelIcon1("?Pg=05&SPg=setmutasi", "mutasi.png", "Mutasi",'','','','','Mutasi Bertambah') ."</td>":"";
		if (empty($ridModul05)) {
			switch ($_GET['SPg']) {
				case 'belumsensus':{
					$PnlSensusManual = $Main->MODUL_SENSUS_MANUAL ? "<td>" . PanelIcon1("javascript:Sensus.CekBarang()", "sections.png", "Sensus", '', '', '', '', 'Sensus Barang') . "</td>" :"<td>" . PanelIcon1("javascript:Sensus.Baru()", "new_f2.png", "Baru", '', '', '', '', 'Sensus Baru') . "</td>";
					$ToolbarAtas_edit = $PnlSensusManual.$PnlBarcode 
						.						
						
						"<td>" . PanelIcon1("javascript:Sensus.formCetakKKShow('1')", "print_f2.png", "<br>K. K KIB A", '', '', '', '', 'Cetak Kertas Kerja KIB A Semua') . "</td>".
						"<td>" . PanelIcon1("javascript:Sensus.formCetakKKShow('2')", "print_f2.png", "<br>K. K KIB B", '', '', '', '', 'Cetak Kertas Kerja KIB B Semua') . "</td>".
						"<td>" . PanelIcon1("javascript:Sensus.formCetakKKShow('3')", "print_f2.png", "<br>K. K KIB C", '', '', '', '', 'Cetak Kertas Kerja KIB C Semua') . "</td>".
						"<td>" . PanelIcon1("javascript:Sensus.formCetakKKShow('4')", "print_f2.png", "<br>K. K KIB D", '', '', '', '', 'Cetak Kertas Kerja KIB D Semua') . "</td>".						
						"<td>" . PanelIcon1("javascript:Sensus.formCetakKKShow('5')", "print_f2.png", "<br>K. K KIB E", '', '', '', '', 'Cetak Kertas Kerja KIB E Semua') . "</td>";
/*						
						"<td>" . PanelIcon1("javascript:Sensus.cetakKertasKerja(0)", "print_f2.png", "<br>K. K BI", '', '', '', '', 'Cetak Kertas Kerja BI per Halaman') . "</td>".
						"<td>" . PanelIcon1("javascript:Sensus.cetakKertasKerja(1)", "print_f2.png", "<br>K. K BI", '', '', '', '', 'Cetak Kertas Kerja BI Semua') . "</td>"
						//"<td>" . PanelIcon1("javascript:Penatausahaab_exportXls()", "export_xls.png", "Excel") . "</td>"
*/						
					break;
				}
				case 'KIR':{
					$ToolbarAtas_edit =
						$PnlBarcode.
						$PnlMutasi.
						"<td>" . PanelIcon1("javascript:prosesBaru()", "new_f2.png", "Baru") . "</td>".
						"<td>" . PanelIcon1("javascript:prosesEdit()", "edit_f2.png", "Ubah") . "</td>".
						"<td>" . PanelIcon1("javascript:prosesHapus()", "delete_f2.png", "Delete") . "</td>".
						"<td>" . PanelIcon1("javascript:cetakBrg()", "print_f2.png", "Barang") . "</td>".
						"<td>" . PanelIcon1("javascript:Penatausahaan_CetakHal()", "print_f2.png", "Halaman") . "</td>".
						"<td>" . PanelIcon1("javascript:Penatausahaan_CetakAll()", "print_f2.png", "Semua") . "</td>".					
									
						"<td>" . PanelIcon1("javascript:Penatausahaab_exportXls()", "export_xls.png", "Excel") . "</td>".
						$cetak_kk;	
					break;	
					
				}
				default:
					$PnlBaru="<td>" . PanelIcon1("javascript:prosesBaru()", "new_f2.png", "Baru") . "</td>";
					$PnlDelete="<td>" . PanelIcon1("javascript:prosesHapus()", "delete_f2.png", "Delete") . "</td>";
					if ($jns=='pindah' || $jns=='mitra' || $jns=='tgr')
					{
						$PnlBaru='';
						$PnlDelete='';
						$PnlMutasi='';
						
					}
					$ToolbarAtas_edit =
						$PnlBarcode.
						$PnlMutasi.
						$PnlBaru.
						"<td>" . PanelIcon1("javascript:prosesEdit()", "edit_f2.png", "Ubah") . "</td>".
						$PnlDelete.
						"<td>" . PanelIcon1("javascript:cetakBrg()", "print_f2.png", "Barang") . "</td>".
						"<td>" . PanelIcon1("javascript:Penatausahaan_CetakHal()", "print_f2.png", "Halaman") . "</td>".
						"<td>" . PanelIcon1("javascript:Penatausaha.formCetakKKShow('".$spg."')", "print_f2.png", "Semua") . "</td>".					
									
						"<td>" . PanelIcon1("javascript:Penatausahaab_exportXls()", "export_xls.png", "Excel") . "</td>".
						$cetak_kk;	
			
			}
			
		}
		
		//--- set toolbar atas 
		
		
		$applet_z= $Main->BARCODE_ENABLE ?"<applet id='qz' name='QZ Print Plugin' code='qz.PrintApplet.class' archive='./qz-print.jar' width='0px' height='0px'>
 	  <param name='jnlp_href' value='qz-print_jnlp.jnlp'>
          <param name='cache_option' value='plugin'>
  
				      <!-- Optional, searches for printer with 'zebra' in the name on load -->
				      <!-- Note:  It is recommended to use applet.findPrinter() instead for ajax heavy applications -->
				      <param name='printer' value='PRINTER BARCODE'>
				      <!-- Optional, these 'cache_' params enable faster loading 'caching' of the applet -->
				      <param name='cache_option' value='plugin'>
				      <!-- Change 'cache_archive' to point to relative URL of jzebra.jar -->
				      <param name='cache_archive' value='./qz-print.jar'>
				      <!-- Change 'cache_version' to reflect current jZebra version -->
				      <param name='cache_version' value='1.4.8.0'>
				   </applet>":"";		
				
				
		return
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
					function Penatausahaan_CetakKertasKerja(){
						adminForm.action='?Pg=PR&SPg=$spg&ctk=1&tipe=kertaskerja';
						adminForm.target='_blank';
						adminForm.submit();		
						adminForm.target='';
					}
					function Penatausahaab_exportXls(){
						adminForm.action='?Pg=PR&SPg=$spg&ctk=1&xls=1';
						adminForm.target='_blank';
						adminForm.submit();
						adminForm.target='';
					}
					</script>		

					<table width='125'><tr>".$applet_z
					.
					$ToolbarAtas_edit.
					
					"</tr></table>			
					</div>";
			
		
		
	}
	function genToolbarBawah(){
		global $ridModul09, $disModul09, $SPg, $Pg,$Main,$jns;
		
		$PnlPengaman = $Main->MODUL_PENGAMANPELIHARA ?"<table width=\"70\"><tr><td>" . 
						PanelIcon3("javascript:setPengamanan()", "PENGAMANAN", $ridModul07, $disModul07,'',2) . 
					"</td></tr></table>":"";
		$PnlPelihara = $Main->MODUL_PENGAMANPELIHARA ?"<table width=\"70\"><tr><td>" . 
						PanelIcon3("javascript:setPemeliharaan()", "PEMELIHARAAN", $ridModul07, $disModul07,'',2,90) . 
					"</td></tr></table>":"";
		$PnlPemanfaatan = $Main->MODUL_PEMANFAATAN ?"<table width=\"70\"><tr><td>" . 
						PanelIcon3("javascript:setPemanfaatan()", "PEMANFAATAN", $ridModul06, $disModul06,'',2,90) . 
					"</td></tr></table>":"";
		$PnlPenghapusan = $Main->MODUL_PENGAPUSAN ?"<table width=\"70\"><tr><td>" . 
						PanelIcon3("javascript:setPenghapusan()", "PENGHAPUSAN", $ridModul09, $disModul09,'',2) . 			"</td></tr></table>":"";
		$PnlPenghapusanSebagian = ($Main->MODUL_PENGAPUSAN_SEBAGIAN && $SPg=='06') ?"<table width=\"70\"><tr><td>" . 
						PanelIcon3("javascript:setHapusSebagian()", "PENGHAPUSAN<br>SEBAGIAN", $ridModul09, $disModul09,'',1) . 
					"</td></tr></table>":"";
						
/* 
"<table width=\"70\"><tr><td>" . 
						PanelIcon3("javascript:setPenghapusan()", "PENGHAPUSAN", $ridModul09, $disModul09,'',2) . 			"</td></tr></table>"
*/
		$PnlPemindahtangan = $Main->MODUL_PEMINDAHTANGAN ?"<table width=\"70\"><tr><td>" . 
						PanelIcon3("javascript:setPindahtangan()", "PEMINDAH<br>TANGANAN", $ridModul10, $disModul10,'',1) . 
					"</td></tr></table>":"";
		$PnlGantirugi = $Main->MODUL_GANTIRUGI ?"<table width=\"70\"><tr><td>" . 
		PanelIcon3("javascript:setGantiRugi()", "TUNTUTAN<br>GANTI RUGI", $ridModul12, $disModul12,'',1) ."</td></tr></table>":"";
		
		
		
		if ($jns=='pindah' || $jns=='mitra' || $jns=='tgr')
		{
			$PnlPelihara='';
			$PnlPengaman='';
			$PnlPenghapusanSebagian='';
			$PnlPemanfaatan='';
			$PnlGantirugi='';
			$PnlPemindahtangan='';
			
			
		}
		
		$ToolBarBawah="<input type=hidden name=idbi id=idbi value=''>
					<input type=hidden name=idbi_awal id=idbi_awal value=''>".
					//Pelihara_createScriptJs().
					"<table width=\"100%\" class=\"menudottedline\" ><tr>
					<td>
					
					</td><td>".$PnlPelihara."
					
					</td><td>".$PnlPengaman."
					
					</td><td>".$PnlPenghapusan."		

					</td><td>".$PnlPenghapusanSebagian."		
					
					</td><td>".$PnlPemindahtangan."
					
					</td><td>".$PnlPemanfaatan."

					</td><td>".$PnlGantirugi."
					
					
					</td><td width=\"80%\"></td></tr>
					</table>
					</td>
					<td align='right'>
						$tampilCbxKeranjangDaftar
					</td>
					</tr>
					</table> 
					
					<script language='javascript'>
					PeliharaRefresh= new AjxRefreshObj(
						'PeliharaList','Pelihara_cover', 'divPeliharaList', 
						new Array('idbi_awal') 
					);
					PeliharaSimpan= new AjxSimpanObj(
						'PeliharaSimpan','PeliharaSimpan_cover',
						new Array('fmTANGGALPEMELIHARAAN','fmJENISPEMELIHARAAN','fmPEMELIHARAINSTANSI',
							'fmPEMELIHARAALAMAT','fmSURATNOMOR','fmSURATTANGGAL','fmBIAYA',
							'fmKET','fmTAMBAHASET','idbi','idbi_awal','idplh','fmst'
						),
						\"PeliharaForm.Close();document.getElementById('boxchecked').value='';Penatausaha.refreshList(false);\"
					);
					PeliharaForm= new AjxFormObj('PeliharaForm','Pelihara_cover','Pelihara_checkbox','jmlTampilPLH', 
						'cbPLH', new Array('idbi','idbi_awal'), 'document.getElementById(\'fmTANGGALPEMELIHARAAN_tgl\').focus()');
					PeliharaHapus= new AjxHapusObj('PeliharaHapus',  'Pelihara_cover', 'Pelihara_checkbox', 'jmlTampilPLH', 
						'cbPLH', 'cidPLH', 'PeliharaRefresh.Refresh();');
					//------------	
					PengamanRefresh= new AjxRefreshObj('PengamanList','Pengaman_cover', 'divPengamanList', new Array('idbi_awal') );
					PengamanSimpan= new AjxSimpanObj(
						'PengamanSimpan','PengamanSimpan_cover',
						new Array('fmTANGGALPENGAMANAN','fmJENISPENGAMANAN','fmURAIANKEGIATAN',
							'fmPENGAMANINSTANSI','fmPENGAMANALAMAT', 'fmSURATNOMOR', 'fmSURATTANGGAL', 
							'fmBIAYA', 'fmKET','fmTAMBAHASET','idbi','idbi_awal','idplh','fmst'
						),
						\"PengamanForm.Close();document.getElementById('boxchecked').value='';Penatausaha.refreshList(false);\" 
					);
					PengamanForm= new AjxFormObj('PengamanForm','Pengaman_cover','Pengaman_checkbox','jmlTampilPGN', 
						'cbPGN', new Array('idbi','idbi_awal'), 'document.getElementById(\'fmTANGGALPENGAMANAN_tgl\').focus()');
					PengamanHapus= new AjxHapusObj('PengamanHapus',  'Pengaman_cover', 'Pengaman_checkbox', 'jmlTampilPGN', 
						'cbPGN', 'cidPGN','PengamanRefresh.Refresh();');
					//------------
					HapusSebagianRefresh= new AjxRefreshObj(
						'HapusSebagianList','HapusSebagian_cover', 'divHapusSebagianList', 
						new Array('idbi_awal') 
					);
					HapusSebagianSimpan= new AjxSimpanObj(
						'HapusSebagianSimpan','HapusSebagianSimpan_cover',
						new Array('fmTANGGALPENGHAPUSAN','fmURAIAN',
							'fmSURATNOMOR','fmSURATTANGGAL','fmHARGA_AWAL','fmHARGA_HAPUS','fmHARGA_SCRAP',
							'fmKET','idbi','idbi_awal','idplh','fmst'
						),
						\"HapusSebagianForm.Close();document.getElementById('boxchecked').value='';Penatausaha.refreshList(false);\"
					);
					HapusSebagianForm= new AjxFormObj('HapusSebagianForm','HapusSebagian_cover','HapusSebagian_checkbox','jmlTampilPLH', 
						'cbPLH', new Array('idbi','idbi_awal'), 'document.getElementById(\'fmTANGGALPENGHAPUSAN_tgl\').focus()');
					HapusSebagianHapus= new AjxHapusObj('HapusSebagianHapus',  'HapusSebagian_cover', 'HapusSebagian_checkbox', 'jmlTampilPLH', 
						'cbPLH', 'cidPLH', 'HapusSebagianRefresh.Refresh();');					
									
					//------------
					PemanfaatRefresh= new AjxRefreshObj('PemanfaatList','Pemanfaat_cover', 'divPemanfaatList', new Array('idbi_awal') );
					PemanfaatSimpan= new AjxSimpanObj(
						'PemanfaatSimpan','PemanfaatSimpan_cover',
						new Array('fmTANGGALPEMANFAATAN', 'fmBENTUKPEMANFAATAN', 'fmKEPADAINSTANSI',
							'fmKEPADAALAMAT', 'fmKEPADANAMA', 'fmKEPADAJABATAN', 'fmSURATNOMOR',
							'fmSURATTANGGAL', 'fmJANGKAWAKTU', 'fmBIAYA', 'fmKET', 'fmTANGGALPEMANFAATAN_akhir',
							'fmURAIAN',	'idbi', 'idbi_awal', 'idplh', 'fmst'
						),
						\"PemanfaatForm.Close();document.getElementById('boxchecked').value='';Penatausaha.refreshList(false);\" 
					);
					PemanfaatForm= new AjxFormObj('PemanfaatForm','Pemanfaat_cover','Pemanfaat_checkbox','jmlTampilPMF', 
						'cbPMF', new Array('idbi','idbi_awal'), 'document.getElementById(\'fmTANGGALPEMANFAATAN_tgl\').focus()');
					PemanfaatHapus= new AjxHapusObj('PemanfaatHapus',  'Pemanfaat_cover', 'Pemanfaat_checkbox', 'jmlTampilPMF', 'cbPMF', 'cidPMF','PemanfaatRefresh.Refresh();');		
					
						
					function setPemeliharaan(){
						errmsg = '';
						if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';}
						if((errmsg=='') && (adminForm.boxchecked.value == 0 )){	errmsg= 'Data belum dipilih!';}
						if(errmsg ==''){
							
							document.getElementById('idbi').value = getCbxCheckedValue('cidBI[]');		
							PeliharaForm.Baru();								
						}else{
							alert(errmsg);
						}			
					}

					function setHapusSebagian(){
						errmsg = '';
						if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';}
						if((errmsg=='') && (adminForm.boxchecked.value == 0 )){	errmsg= 'Data belum dipilih!';}
						if(errmsg ==''){
							
							document.getElementById('idbi').value = getCbxCheckedValue('cidBI[]');		
							HapusSebagianForm.Baru();								
						}else{
							alert(errmsg);
						}			
					}
										
					function setGantiRugi(){
						document.getElementById('idbi').value = getCbxCheckedValue('cidBI[]');		
						if(document.getElementById('idbi').value!='')
							Gantirugi.Baru2();								
					}
					function setPengamanan(){
						document.getElementById('idbi').value = getCbxCheckedValue('cidBI[]');		
						if(document.getElementById('idbi').value!='')
							PengamanForm.Baru();				
					}
					function setPindahtangan(){
						document.getElementById('idbi').value = getCbxCheckedValue('cidBI[]');		
						if(document.getElementById('idbi').value!='')
							Pindahtangan.Baru2();				
							//Pindahtangan.formbaru.Baru();
							//alert('tes');
					}
					function setPemanfaatan(){
						document.getElementById('idbi').value = getCbxCheckedValue('cidBI[]');		
						if(document.getElementById('idbi').value!='')
							PemanfaatForm.Baru();				
					}
					function setPenghapusan(){
						errmsg = '';
					if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';}
					if((errmsg=='') && (adminForm.boxchecked.value == 0 )){	errmsg= 'Data belum dipilih!';}
						if(errmsg ==''){						
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
					/*function setPemanfaatan(){
						errmsg = '';
					if((errmsg=='') && (adminForm.boxchecked.value >1 )){errmsg= 'Pilih Hanya Satu Data!';}
					if((errmsg=='') && (adminForm.boxchecked.value == 0 )){	errmsg= 'Data belum dipilih!';}
						if(errmsg ==''){
							adminForm.action='?Pg=$Pg&SPg=$SPg'; adminForm.target='';adminForm.Act.value='Pemanfaatan_TambahEdit';				
							adminForm.Baru.value='1'; adminForm.submit();
						}else{
							alert(errmsg);
						}
					}*/
					</script>";

		//tampil link daftar pilihan
		if($SPg=='belumsensus'){
			return  '';
		}else{
		
			
				return	$ToolBarBawah;	
					
			//	break;
		//	}
		//}
		
		}
	}
	
	function BIGetKib($f, $KondisiKIB){
		//get data detil kib untuk BI
		global $Main;//, $sort1;
		//global $ISI5, $ISI6, $ISI7, $ISI10, $ISI12, $ISI15;
		$ISI5=''; $ISI6=''; $ISI7=''; $ISI10=''; $ISI12=''; $ISI15='';
		//echo"<br>f=".$f;
		//*
		switch($f){
			case '01':{//KIB A			
				
				$sqryKIBA = "select sertifikat_no, luas, ket from kib_a  $KondisiKIB limit 0,1";
				//$sqryKIBA = "select * from view_kib_a  $KondisiKIB limit 0,1";
				//echo '<br> qrykibA = '.$sqryKIBA;
				$QryKIB_A = mysql_query($sqryKIBA);
				while($isiKIB_A = mysql_fetch_array($QryKIB_A))	{
					//$ISI5 = $isiKIB_A['alamat'].'<br>'.$isiKIB_A['alamat_kel'].'<br>'.$isiKIB_A['alamat_kec'].'<br>'.$isiKIB_A['alamat_kota'] ;
					$ISI6 = $isiKIB_A['sertifikat_no'];
					
					$ISI10 = number_format($isiKIB_A['luas'],2,',','.');//$cek .= '<br> luas A = '.$isiKIB_A['luas'];
					$ISI15 = "{$isiKIB_A['ket']}";
				}
				break;
			}
			case '02':{//KIB B;			
				//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'";
				$aqry="select ukuran, merk,no_pabrik,no_rangka,no_mesin,bahan,ket  from kib_b  $KondisiKIB limit 0,1";
				//echo"<br>qrkbb=".$aqry;
				
				$QryKIB_B = mysql_query($aqry);
				
				//echo "<br>qrkibb=".$aqry;
				while($isiKIB_B = mysql_fetch_array($QryKIB_B))	{
					
					$ISI5 = "{$isiKIB_B['merk']}";
					$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
					$ISI7 = "{$isiKIB_B['bahan']}";
					$ISI10 = "{$isiKIB_B['ukuran']}";
					$ISI15 = "{$isiKIB_B['ket']}";
				}
				break;
				}	
			case '03':{//KIB C;
				$QryKIB_C = mysql_query("select dokumen_no, kondisi_bangunan, ket from kib_c  $KondisiKIB limit 0,1");
				//$QryKIB_C = mysql_query("select dokumen_no, kondisi_bangunan, ket, alamat_kota, alamat_kec, alamat_kel, alamat from view_kib_c  $KondisiKIB limit 0,1");
				while($isiKIB_C = mysql_fetch_array($QryKIB_C))	{
					//$ISI5 = $isiKIB_C['alamat'].'<br>'.$isiKIB_C['alamat_kel'].'<br>'.$isiKIB_C['alamat_kec'].'<br>'.$isiKIB_C['alamat_kota'] ;
					$ISI6 = "{$isiKIB_C['dokumen_no']}";
					$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan']-1][1];
					$ISI15 = "{$isiKIB_C['ket']}";
				}
				break;
			}
			case '04':{//KIB D;
				//$QryKIB_D = mysql_query("select dokumen_no, ket, alamat_kota, alamat_kec, alamat_kel, alamat from view_kib_d  $KondisiKIB limit 0,1");
				$QryKIB_D = mysql_query("select dokumen_no, ket  from kib_d  $KondisiKIB limit 0,1");
				while($isiKIB_D = mysql_fetch_array($QryKIB_D))	{
					//$ISI5 = $isiKIB_D['alamat'].'<br>'.$isiKIB_D['alamat_kel'].'<br>'.$isiKIB_D['alamat_kec'].'<br>'.$isiKIB_D['alamat_kota'] ;
					$ISI6 = "{$isiKIB_D['dokumen_no']}";
					$ISI15 = "{$isiKIB_D['ket']}";
				}
				break;
			}
			case '05':{//KIB E;		
				$QryKIB_E = mysql_query("select seni_bahan, ket from kib_e  $KondisiKIB limit 0,1");
				while($isiKIB_E = mysql_fetch_array($QryKIB_E))	{
					$ISI7 = "{$isiKIB_E['seni_bahan']}";
					$ISI15 = "{$isiKIB_E['ket']}";
				}
				break;
			}
			case '06':{//KIB F;
				//$cek.='<br> F = '.$isi['f'];
				//$sqrykibF = "select dokumen_no, bangunan, ket, alamat_kota, alamat_kec, alamat_kel, alamat  from view_kib_f  $KondisiKIB limit 0,1";
				$sqrykibF = "select dokumen_no, bangunan, ket from kib_f  $KondisiKIB limit 0,1";
				$QryKIB_F = mysql_query($sqrykibF);
				$cek.='<br> qrykibF = '.$sqrykibF;
				while($isiKIB_F = mysql_fetch_array($QryKIB_F))	{
					//$ISI5 = $isiKIB_F['alamat'].'<br>'.$isiKIB_F['alamat_kel'].'<br>'.$isiKIB_F['alamat_kec'].'<br>'.$isiKIB_F['alamat_kota'] ;
					$ISI6 = "{$isiKIB_F['dokumen_no']}";
					$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan']-1][1];
					$ISI15 = "{$isiKIB_F['ket']}";
				}
				break;
			}
		}
		//*/	
				
		$ISI5 = !empty($ISI5) ? $ISI5 : "-";
		$ISI6 = !empty($ISI6) ? $ISI6 : "-";
		$ISI7 = !empty($ISI7) ? $ISI7 : "-";
		$ISI10 = !empty($ISI10) ? $ISI10 : "-";
		$ISI12 = !empty($ISI12) ? $ISI12 : "-";
		$ISI15 = !empty($ISI15) ? $ISI15 : "-";
		return array('ISI5'=>$ISI5, 'ISI6'=>$ISI6, 
		'ISI'=>$ISI7, 'ISI10'=>$ISI10, 'ISI15'=>$ISI15);
	}
	
	function getDaftarOpsi(){
		global $Main, $Pg, $SPg, $HTTP_COOKIE_VARS; 
		$cek = '';
		
		// urutan --------------------------------------------------------------------------
		$Urutkan='';
		switch ($SPg){			
			case '04': case '06': case '07': case '09' :{
				// urutan --------------------------------------------------------------------------
				$AcsDsc1 = cekPOST("AcsDsc1");
				$AcsDsc2 = cekPOST("AcsDsc2");
				$AcsDsc3 = cekPOST("AcsDsc3");
				$Asc1 = !empty($AcsDsc1)? " desc ":"";
				$Asc2 = !empty($AcsDsc2)? " desc ":"";
				$Asc3 = !empty($AcsDsc3)? " desc ":"";
				$odr1 = cekPOST("odr1");
				$odr2 = cekPOST("odr2");
				$odr3 = cekPOST("odr3");
				
				if(!empty($odr1) ){	//echo "<br>odr1='$odr1' ".strcmp($odr1,'alamat_kota, alamat_kec, alamat_kel' );
					if (strcmp($odr1,'alamat_kota, alamat_kec, alamat_kel, alamat' )==0 &&  !empty($AcsDsc1)){
						" $Urutkan alamat_kota desc, alamat_kec desc, alamat_kel desc, alamat desc,";
						}else{
						$Urutkan = " $Urutkan $odr1 $Asc1, ";
					}
				}
				if(!empty($odr2) ){
					if (strcmp($odr2,'alamat_kota, alamat_kec, alamat_kel, alamat' )==0 &&  !empty($AcsDsc2)){
						$Urutkan = " $Urutkan alamat_kota desc, alamat_kec desc, alamat_kel desc, alamat desc,";
						}else{
						$Urutkan = " $Urutkan $odr2 $Asc2, ";
						}	
				}
				if(!empty($odr3) ){
					if (strcmp($odr3,'alamat_kota, alamat_kec, alamat_kel, alamat' )==0 &&  !empty($AcsDsc3)){
						$Urutkan = " $Urutkan alamat_kota desc, alamat_kec desc, alamat_kel desc,  alamat desc,";
						}else{
						$Urutkan = " $Urutkan $odr3 $Asc3, ";
					}
				}
				//tampil
				$selTahun1 = $odr1 == "tahun" ? " selected " :  "";
				$selTahun2 = $odr2 == "tahun" ? " selected " :  "";
				$selTahun3 = $odr3 == "tahun" ? " selected " :  "";
				$selKondisi1 = $odr1 == "kondisi" ? " selected " :  "";
				$selKondisi2 = $odr2 == "kondisi" ? " selected " :  "";
				$selKondisi3 = $odr3 == "kondisi" ? " selected " :  "";
				$selWilayah1 = $odr1 == "alamat_kota, alamat_kec, alamat_kel, alamat" ? " selected " :  "";
				$selWilayah2 = $odr2 == "alamat_kota, alamat_kec, alamat_kel, alamat" ? " selected " :  "";
				$selWilayah3 = $odr3 == "alamat_kota, alamat_kec, alamat_kel, alamat" ? " selected " :  "";
				$Odr1  = "<option value=''>--</option>
				<option $selTahun1 value='tahun'>Tahun Perolehan</option>
				<option $selKondisi1 value='kondisi'>Keadaan Barang</option>
				<option $selWilayah1 value='alamat_kota, alamat_kec, alamat_kel, alamat'>Letak/Alamat</option>";
				$Odr2  = "<option value=''>--</option>
				<option $selTahun2 value='tahun'>Tahun Perolehan</option>
				<option $selKondisi2 value='kondisi'>Keadaan Barang</option>
				<option $selWilayah2 value='alamat_kota, alamat_kec, alamat_kel, alamat'>Letak/Alamat</option>";
				$Odr3  = "<option value=''>--</option>
				<option $selTahun3 value='tahun'>Tahun Perolehan</option>
				<option $selKondisi3 value='kondisi'>Keadaan Barang</option>
				<option $selWilayah3 value='alamat_kota, alamat_kec, alamat_kel, alamat'>Letak/Alamat</option>";
				$TampilOrder = "&nbsp Urutkan berdasar : 
				<select name=odr1>$Odr1</select><input $AcsDsc1 type=checkbox name=AcsDsc1 value='checked'>Desc. 
				<select name=odr2>$Odr2</select><input $AcsDsc2 type=checkbox name=AcsDsc2 value='checked'>Desc.
				<select name=odr3>$Odr3</select><input $AcsDsc3 type=checkbox name=AcsDsc3 value='checked'>Desc.";
				break;
			}
			default :{
				$sort1 = $_GET['sort1'];
				$AcsDsc1 = cekPOST("AcsDsc1"); //echo $AcsDsc1.'<br>';
				$AcsDsc2 = cekPOST("AcsDsc2");
				$AcsDsc3 = cekPOST("AcsDsc3");
				$odr1 = cekPOST("odr1"); //echo "odr1=$odr1";
				$odr2 = cekPOST("odr2");// echo "odr2=$odr2";
				$odr3 = cekPOST("odr3");		
				$Asc1 = !empty($AcsDsc1) ? " desc " : "";
				$Asc2 = !empty($AcsDsc2) ? " desc " : "";
				$Asc3 = !empty($AcsDsc3) ? " desc " : "";		
				//$Urutkan = "";
				if (!empty($odr1)) $Urutkan .= " $odr1 $Asc1, ";		
				if (!empty($odr2)) $Urutkan .= " $odr2 $Asc2, ";		
				if (!empty($odr3)) $Urutkan .= " $odr3 $Asc3, ";
				if ($sort1 == 1) {
					$Urutkan = ' id desc, ' . $Urutkan; //' tgl_update desc, '.$Urutkan;
					} else if ($sort1 == 2) {
					$Urutkan = ' tgl_update desc, ' . $Urutkan;
				}
				//tampil urut ----------------------------------------
				$selTahun1 = $odr1 == "tahun" ? " selected " : "";
				$selTahun2 = $odr2 == "tahun" ? " selected " : "";
				$selTahun3 = $odr3 == "tahun" ? " selected " : "";
				$selKondisi1 = $odr1 == "kondisi" ? " selected " : "";
				$selKondisi2 = $odr2 == "kondisi" ? " selected " : "";
				$selKondisi3 = $odr3 == "kondisi" ? " selected " : "";
				$selThnBuku1 = $odr1 == "year(tgl_buku)" ? " selected " : "";
				$selThnBuku2 = $odr2 == "year(tgl_buku)" ? " selected " : "";
				$selThnBuku3 = $odr3 == "year(tgl_buku)" ? " selected " : "";
				$Odr1 = "<option value=''>--</option><option $selTahun1 value='tahun'>Tahun Perolehan</option><option $selKondisi1 value='kondisi'>Keadaan Barang</option><option $selThnBuku1 value='year(tgl_buku)'>Tahun Buku</option>";
				$Odr2 = "<option value=''>--</option><option $selTahun2 value='tahun'>Tahun Perolehan</option><option $selKondisi2 value='kondisi'>Keadaan Barang</option><option $selThnBuku2 value='year(tgl_buku)'>Tahun Buku</option>";
				$Odr3 = "<option value=''>--</option><option $selTahun3 value='tahun'>Tahun Perolehan</option><option $selKondisi3 value='kondisi'>Keadaan Barang</option><option $selThnBuku3 value='year(tgl_buku)'>Tahun Buku</option>";		
				$TampilOrder = 
				" &nbsp&nbsp Urutkan berdasar : 
				<select name=odr1>$Odr1</select><input $AcsDsc1 type=checkbox name=AcsDsc1 value='checked'>Desc. 
				<select name=odr2>$Odr2</select><input $AcsDsc2 type=checkbox name=AcsDsc2 value='checked'>Desc.
				<select name=odr3>$Odr3</select><input $AcsDsc3 type=checkbox name=AcsDsc3 value='checked'>Desc. 
				";
				break;
			}
		}
		
		//create kondisi ------------------------------------------------------------------
		$fmKEPEMILIKAN = $Main->DEF_KEPEMILIKAN;
		$a = $Main->Provinsi[0];
		$b = $Main->DEF_WILAYAH;//'00';
		$fmSKPD = cekPOST('fmSKPD'); //echo  '<br> fmSKPD  = '.$fmSKPD;//? 
		$fmUNIT = cekPOST('fmUNIT'); //echo  '<br> fmUNIT  = '.$fmUNIT;//?
		$fmSUBUNIT = cekPOST('fmSUBUNIT');  //echo  '<br> fmSUBUNIT  = '.$fmSUBUNIT;//?		
		$fmSEKSI = cekPOST('fmSEKSI');  //echo  '<br> fmSEKSI  = '.$fmSEKSI;//?		
		
		$Kondisi = getKondisiSKPD2($fmKEPEMILIKAN, $a, $b, $fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI);
		$fmCariComboIsi = $_POST['fmCariComboIsi'];// cekPOST('fmCariComboIsi');
		$fmCariComboField = //$_POST['fmCariComboField'];//
		cekPOST('fmCariComboField');
		if (!empty($fmCariComboIsi) && !empty($fmCariComboField)) {
			if ($fmCariComboField != 'ket' && $fmCariComboField != 'Cari Data') {
				$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
			}
		}
		$fmTahunPerolehan = cekPOST('fmTahunPerolehan', '');
		$fmTahunPerolehan2 = cekPOST('fmTahunPerolehan2', '');
		if (!empty($fmTahunPerolehan) && !empty($fmTahunPerolehan2) ) {
			$Kondisi .= " and thn_perolehan >= '$fmTahunPerolehan'  and thn_perolehan <= '$fmTahunPerolehan2'  ";
		}else if (!empty($fmTahunPerolehan) && empty($fmTahunPerolehan2) ) {
			$Kondisi .= " and thn_perolehan >= '$fmTahunPerolehan'  ";
		}else if (empty($fmTahunPerolehan) && !empty($fmTahunPerolehan2) ) {
			$Kondisi .= " and thn_perolehan <= '$fmTahunPerolehan2'  ";
		}		
		
		$fmTglBuku = cekPOST('fmTglBuku');
		$fmFiltThnBuku = cekPOST('fmFiltThnBuku');
		$Kondisi .= empty($fmTglBuku) ? "" : " and tgl_buku ='$fmTglBuku' "; //echo $Kondisi;
		$Kondisi .= empty($fmFiltThnBuku) ? "" : " and Year(tgl_buku) ='$fmFiltThnBuku' ";
		$jns= $_REQUEST['jns'];
				
		//kondisi belum sensus -----------------------------
		if( $_GET['SPg']=='belumsensus'){
			$Kondisi .= " and id not in 
				(select id_bukuinduk FROM penghapusan where tgl_penghapusan <='2014-12-31')
				and id not in 
				(select id_bukuinduk from pemindahtanganan where tgl_pemindahtanganan <='2014-12-31' )
				and id not in 
				(select id_bukuinduk from gantirugi where tgl_gantirugi <='2014-12-31' )";
		} else if( $jns<>''){
			$Kondisi .= "  and status_barang <> 3 ";
		}else{
			$Kondisi .= ' and status_barang <> 3 and  status_barang <> 4 and status_barang <> 5';
		}		
				
		$fmkode = cekPOST('barcode_input');
		if (strlen($fmkode)>=28){			
			$Kondisi .= " and idall2 ='$fmkode' ";				
		}
		
		$filterBrg = $_GET['filterBrg'];
		$filterHrg = $_GET['filterHrg'];
		if (isset($filterBrg)) {
			$Kondisi .= " and concat(f,'.',g,'.',h,'.',i,'.',j,'.',tahun,'.',noreg) like '$filterBrg%' ";
		}
		if (isset($filterHrg)) {
			$Kondisi .= " and jml_harga = '$filterHrg%' ";
		}		
				
		$fmKONDBRG = cekPOST('fmKONDBRG');
		if ($fmKONDBRG>='80')
		{
			$Kondisi .= $fmKONDBRG =='80'? " and kondisi<3	":"";
			$Kondisi .= $fmKONDBRG =='81'? " and kondisi<4	":"";
		} else {
		$Kondisi .= $fmKONDBRG ==''? '' : " and kondisi='$fmKONDBRG' ";			
			
		}


		$fmFiltTglBtw_tgl1 = cekPOST('fmFiltTglBtw_tgl1');
		$fmFiltTglBtw_tgl2 = cekPOST('fmFiltTglBtw_tgl2');
		if( !empty($fmFiltTglBtw_tgl1)   ) $Kondisi .= " and tgl_buku>='$fmFiltTglBtw_tgl1'";
		if( !empty($fmFiltTglBtw_tgl2)   ) $Kondisi .= " and tgl_buku<='$fmFiltTglBtw_tgl2'";
		if( $_GET['SPg']=='belumsensus'){
			if( empty($fmFiltTglBtw_tgl1) && empty($fmFiltTglBtw_tgl2)   )  $fmFiltTglBtw_tgl2=$Main->defTglBukuBelumSensus;
		}
				
		//kondisi sertifikat -------------------------------
		switch ($_GET['SPg']){
			case '04':{
				$selSertifikat = $_POST['selSertifikat'];
				switch ($selSertifikat){
					case '1': $Kondisi .= ' and bersertifikat ="1" '; break;
					case '2': $Kondisi .= ' and bersertifikat ="2" '; break;
					case '3': $Kondisi .= ' and bersertifikat ="" '; break;
				}
				$TampilFilterSertifikat = 
					"<div style='float:left;margin: 0 4 0 0;border-left: 1px solid #E5E5E5;height:22'></div>".
					//"<div style='float:left;padding: 2 4 0 4'> Bersertifikat </div>".
					"<div style='float:left;padding: 0 4 0 0'>".
					cmb2D_v3('selSertifikat', $selSertifikat, $Main->bersertifikat, '','Status Sertifikat ').
					"</div>";
				$FiltID = $_POST['FiltID'];
				$FiltVal = $_POST['FiltVal'];
				switch ($FiltID ){
					case 1: {	$Kondisi .= " and luas = $FiltVal "; break;	}
				}
				break;
			}	
		}
		
		//Kondisi cari bi di usulan hapus
		$formcaribi = $_REQUEST['formcaribi'];
		if($formcaribi ==1){
			$Kondisi .= " and (kondisi=3 or kondisi=2 or kondisi=4) ";			
			//kondisi bukan usulan
			/*$aqry = "select id_bukuinduk from v1_penghapusan_usul_det_bi ".
				"where c='04' and d='01' ".
				"and e='01' and sesi ='' ".
				"and id>0 and tahun_sensus = '2013' ";*/
			//$qry = mysql_query($aqry);			
			/*
			$Kondisi .= " and tahun_sensus<>'' and tahun_sensus is not NULL and id NOT IN (".
				"select id_bukuinduk as id from v1_penghapusan_usul_det_bi ".
				"where c='$fmSKPD' and d='$fmUNIT' ".
				"and e='$fmSUBUNIT' and sesi ='' ".
				"and id>0 and tahun_sensus = '2013') ";
			*/
			$Kondisi .= //" and tahun_sensus<>'' and tahun_sensus is not NULL 
			 	" and id NOT IN (".
				"select id_bukuinduk as id from v1_penghapusan_usul_det_bi ".
				"where c='$fmSKPD' and d='$fmUNIT' ".
				"and e='$fmSUBUNIT' and e1='$fmSEKSI' and sesi ='' ".
				"and id>0 ) ";
		}
		
		//kondisi BarCode barcodeSensusBaru_input
		$barcodeCariBarang_input = $_REQUEST['barcodeCariBarang_input'];
		if (!empty($barcodeCariBarang_input)) $Kondisi .= " and idall2='$barcodeCariBarang_input' ";
				
		//kondisi skpd readonly pembukuan
		$f= $_REQUEST['f']=='00' || $_REQUEST['f']=='' ? '' : $_REQUEST['f'];
		$g= $_REQUEST['g']=='00' || $_REQUEST['g']=='' ? '' : $_REQUEST['g'];		
		
		if($f!='' || $g!='') {
			$kode_barang = "$f.$g" ;
		}else{
			$kode_barang = $_REQUEST['kode_barang'];	
		}



		switch ($jns){
			case 'lancar' :$Kondisi .= " and concat(f,g)='0519' and harga>=500000 and kondisi<>3 "; break;  
			case 'tetap' : $Kondisi .= " and kondisi<>3 and (staset=3 or staset=1) "; break;
			case 'pindah' : $Kondisi .= " and staset=5 "; break; //aset pemindahtanganan
			case 'tgr' : $Kondisi .= " and staset=6 "; break; //aset tgr
			case 'mitra' : $Kondisi .= " and staset=7 "; break; //aset kemitraan
			case 'atb' : $Kondisi .= " and staset=8 and status_barang<=1"; break; //aset tak berwujud
			case 'lain' : $Kondisi .= " and staset=9 and status_barang<=1 "; break; //aset lain lain
			case 'lainx' : $Kondisi .= " and (kondisi=3 or status_penguasaan=2) and harga>=500000 "; break;
			case 'intra' : $Kondisi .= " and staset<=9 ";
			
			break;
			case 'bawahkap' : $Kondisi .= " and harga<500000 "; break;
			case 'ekstra' : $Kondisi .= " and staset=10 "; break;
			
		}
		
				
		//kondisi 2 --------------
		//if(!empty($barcodeSensus_input)) $Kondisi .= " and concat(f,'.',g,'.',h,'.',i,'.',j) like '$barcodeSensus_input%'";
		if(!empty($kode_barang)) $Kondisi .= " and concat(f,'.',g,'.',h,'.',i,'.',j) like '$kode_barang%'";
		$nama_barang = $_REQUEST['nama_barang'];
		if(!empty($nama_barang)) $Kondisi .= " and nm_barang like '%$nama_barang%'";
		$jml_harga1 = $_REQUEST['jml_harga1'];
		$jml_harga2 = $_REQUEST['jml_harga2'];
		if(!empty($jml_harga1)) $Kondisi .= " and jml_harga >= '$jml_harga1' ";
		if(!empty($jml_harga2)) $Kondisi .= " and jml_harga <= '$jml_harga2' ";
		$alamat = $_REQUEST['alamat'];
		if(!empty($alamat)) $Kondisi .= " and alamat like '%$alamat%'  ";
//		$selKabKota = $_REQUEST['selKabKota'];
//		if(!empty($selKabKota)) $Kondisi .= " and alamat_b='$selKabKota'  ";
		$noSert = $_REQUEST['noSert'];
		if(!empty($noSert)) $Kondisi .= " and sertifikat_no like '%$noSert%'  ";
		$selHakPakai = $_REQUEST['selHakPakai'];
		if(!empty($selHakPakai)) $Kondisi .= " and status_hak='$selHakPakai' ";
		
		$konsTingkat = $_REQUEST['konsTingkat'];
		if(!empty($konsTingkat)) $Kondisi .= " and konstruksi_tingkat = '$konsTingkat'  ";
		$konsBeton = $_REQUEST['konsBeton'];
		if(!empty($konsBeton)) $Kondisi .= " and konstruksi_beton = '$konsBeton'  ";
		$status_tanah = $_REQUEST['status_tanah'];
		if(!empty($status_tanah)) $Kondisi .= " and status_tanah = '$status_tanah'  ";
								
		$merk = $_REQUEST['merk'];
		if(!empty($merk)) $Kondisi .= " and merk like '%$merk%' ";				
		$bahan = $_REQUEST['bahan'];
		if(!empty($bahan)) $Kondisi .= " and bahan like '%$bahan%' ";
		$nopabrik = $_REQUEST['nopabrik'];
		if(!empty($nopabrik)) $Kondisi .= " and no_pabrik like '%$nopabrik%' ";		
		$norangka = $_REQUEST['norangka'];
		if(!empty($norangka)) $Kondisi .= " and no_rangka like '%$norangka%' ";		
		$nomesin = $_REQUEST['nomesin'];
		if(!empty($nomesin)) $Kondisi .= " and no_mesin like '%$nomesin%' ";		
		$nopolisi = $_REQUEST['nopolisi'];
		if(!empty($nopolisi)) $Kondisi .= " and no_polisi like '%$nopolisi%' ";		
		$nobpkb = $_REQUEST['nobpkb'];
		if(!empty($nobpkb)) $Kondisi .= " and no_bpkb like '%$nobpkb%' ";
		
		$dokumen_no = $_REQUEST['dokumen_no'];
		if(!empty($dokumen_no)) $Kondisi .= " and dokumen_no like '%$dokumen_no%' ";
		$kode_tanah = $_REQUEST['kode_tanah'];
		if(!empty($kode_tanah)) $Kondisi .= " and kode_tanah like '%$kode_tanah%' ";		
		$konstruksi = $_REQUEST['konstruksi'];
		if(!empty($konstruksi)) $Kondisi .= " and konstruksi like '%$konstruksi%' ";
		
		$judul = $_REQUEST['judul'];
		if(!empty($judul)) $Kondisi .= " and buku_judul like '%$judul%' ";
		$spesifikasi = $_REQUEST['spesifikasi'];
		if(!empty($spesifikasi)) $Kondisi .= " and buku_spesifikasi like '%$spesifikasi%' ";
		$seni_asal_daerah = $_REQUEST['seni_asal_daerah'];
		if(!empty($seni_asal_daerah)) $Kondisi .= " and seni_asal_daerah like '%$seni_asal_daerah%' ";
		$seni_pencipta = $_REQUEST['seni_pencipta'];
		if(!empty($seni_pencipta)) $Kondisi .= " and seni_pencipta like '%$seni_pencipta%' ";
		$seni_bahan = $_REQUEST['seni_bahan'];
		if(!empty($seni_bahan)) $Kondisi .= " and seni_bahan like '%$seni_bahan%' ";
		$hewan_jenis = $_REQUEST['hewan_jenis'];
		if(!empty($hewan_jenis)) $Kondisi .= " and hewan_jenis like '%$hewan_jenis%' ";
		$hewan_ukuran = $_REQUEST['hewan_ukuran'];
		if(!empty($hewan_ukuran)) $Kondisi .= " and hewan_ukuran like '%$hewan_ukuran%' ";		
		$bangunan = $_REQUEST['bangunan'];
		if(!empty($bangunan)) $Kondisi .= " and bangunan = '$bangunan' ";
		
		$uraian= $_REQUEST['uraian'];
		if(!empty($uraian)) $Kondisi .= " and uraian like '%$uraian%' ";
		$luas1 = $_REQUEST['luas1'];
		$luas2 = $_REQUEST['luas2'];
		if(!empty($luas1)) $Kondisi .= " and luas >= '$luas1' ";
		if(!empty($luas2)) $Kondisi .= " and luas <= '$luas2' ";
		$luas_lantai1 = $_REQUEST['luas_lantai1'];
		$luas_lantai2 = $_REQUEST['luas_lantai2'];
		if(!empty($luas_lantai1)) $Kondisi .= " and luas_lantai >= '$luas_lantai1' ";
		if(!empty($luas_lantai2)) $Kondisi .= " and luas_lantai <= '$luas_lantai2' ";
		
		$tipe = $_REQUEST['tipe'];
		$tahun_sensus = $_REQUEST['tahun_sensus'];
		if(!empty($tahun_sensus)) {
		//if($tahun_sensus != '') {
			if ($tahun_sensus=='belum_sensus') {
				//$Kondisi .= " and (tahun_sensus ='' or tahun_sensus is null)";
				require_once('daftarobj.php');
				require_once('fnsensus.php');
				//global $SensusTmp;
				if($tipe!='kertaskerja'){
					$thnsensusskr = $SensusTmp->getTahunSensus();
					$Kondisi .= " and (tahun_sensus <> $thnsensusskr or tahun_sensus='' or tahun_sensus is null)";	
				}
				
				
			}else{
				$Kondisi .= " and tahun_sensus ='$tahun_sensus'";
			}			
		}
						
		
		//kondisi lain -----------------------		
		$jmPerHal = cekPOST("jmPerHal"); 
		$Main->PagePerHal = !empty($jmPerHal) ? $jmPerHal : $Main->PagePerHal;
		$cbxDlmRibu = $_POST["cbxDlmRibu"];		
		
		
//		$alamat_kec = $_REQUEST['alamat_kec'];
		$alamat_b=$_REQUEST['WilayahfmxKotaKab'];
		$alamat_c=$_REQUEST['WilayahfmxKecamatan'];
		
		$alamat_kota=$_REQUEST['WilayahfmxKotaKabtxt'];
		$alamat_kec=$_REQUEST['WilayahfmxKecamatantxt'];
		if(!empty($alamat_b)){
		if ($alamat_b != '0' )
		{
//			$alamat_kota='';
			if(!empty($alamat_b)) $Kondisi .= " and alamat_b='$alamat_b' ";
		}
		}
		if(!empty($alamat_c)){
		if ($alamat_c != '0' )
		{
			$alamat_kec='';
			 $Kondisi .= " and alamat_c='$alamat_c' ";

		}
		}
		
		if(!empty($alamat_kec)) $Kondisi .= " and alamat_kec like '%$alamat_kec%' ";
		$alamat_kel = $_REQUEST['alamat_kel'];
		if(!empty($alamat_kel)) $Kondisi .= " and alamat_kel like '%$alamat_kel%' ";
		
		

		/*//kondisi pilih --------------------- teu kapake?
		$tipebi = $_REQUEST['tipebi'];
		if($tipebi=='pilih'){
			$idpilihan = $HTTP_COOKIE_VARS['Keranjang'];
			if($idpilihan != ''){
				$Kondisi .= $Kondisi ==''? '':' and ';	//$arridpilih = explode(',',$idpilihan);
				
				$Kondisi .= $SPg=='03' ? " id in ($idpilihan) ": " idbi in ($idpilihan) ";
			}
			
			
		}
		*/
		//kondisi pilihan banyak ----------------
		$cbxpilihfilter = $_REQUEST['cbxpilihfilter'];
		if($cbxpilihfilter==1){
			$idpilihan = $HTTP_COOKIE_VARS['penatausaha_DaftarPilih'];
			if($idpilihan != ''){
				$Kondisi .= $Kondisi ==''? '':' and ';	//$arridpilih = explode(',',$idpilihan);
				
				$Kondisi .= $SPg=='03' ? " id in ($idpilihan) ": " idbi in ($idpilihan) ";
			}
			
			
		}
		
		
		
		//tampil cari ----------------------------------------------------------------	
		//$FilterStatus = ''; //cmb2D_v3('selStatusBrg', $selStatusBrg, $Main->StatusBarang, $disStatusBrg,'Semua Status ');
		switch($_GET['SPg']){			
			case'04': case'06': case'07': case'09' :{
				$ArFieldCari = array(
				array('nm_barang','Nama Barang'),
				array('thn_perolehan','Tahun Perolehan'),
				array('alamat','Letak/Alamat'),
				array('ket','Keterangan')
					);				
				break;
			}
			default:{
				$ArFieldCari = array(
				array('nm_barang', 'Nama Barang'),
				array('thn_perolehan', 'Tahun Perolehan'),
				//array('alamat', 'Letak/Alamat'),
				//array('ket', 'Keterangan')
					);					
				break;			
			}
			
		}
		
		if($formcaribi ==1){
			$barcodeCari = $Main->BARCODE_ENABLE?

				"<span style='color:red'>BARCODE</span><br>
				<input type='TEXT' value='' id='barcodeCariBarang_input' name='barcodeCariBarang_input' 
				style='font-size:24;width: 369px;' size='32' maxlength='32' ".
				//onchange='barcodeCariBarangExec()'
				">".
				"<span id='barcodeCariBarang_msg' name='barcodeCariBarang_msg'>
					<a style='color:red;' href='javascript:barcodeCariBarang.setInputReady()'>Not Ready! (click for ready)</a>".
				"</span>":"";
		}else{
			$barcodeCari = '';
		}
		
		$OptCari =  //$Main->ListData->OptCari =
			"<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin: 4 0 0 0;'>
			<tr > 
			<td align='Left'> &nbsp;&nbsp;".
			"<div style='float:left'>".
				CariCombo4($ArFieldCari, $fmCariComboField, $fmCariComboIsi,"Penatausaha.refreshList(true)" ).
			"</div>".
			"<div id='".$this->prefix."_pilihan_msg' style='float:right;padding: 4 4 4 8;'></div>".
			"</td>".
			"<td width='375'>".
				/*"<span style='color:red'>BARCODE</span><br>			
				<input type='TEXT' value='' 
					id='barcodeSensus_input' name='barcodeSensus_input'
					style='font-size:24;width: 379px;' 
					size='28' maxlength='28'>
				<span id='barcodeSensus_msg' name='barcodeSensus_msg' ></span>". 
				*/
				$barcodeCari.
				
					
				//<input type='TEXT' value='' 	style='	font-weight:bold' 	size='50'	>-->
			"</td>
			</tr>
		</table>";
		
		//tampil filter ---------------------------------------------
		switch($SPg){
			case "03" : case "listbi_cetak" :{ $tblname = ' buku_induk'; break; }
			case "04" : case "kib_a_cetak" :{ $tblname = " buku_induk where f='01' ";	break; }
			case "05" : case "kib_b_cetak" :{ $tblname = " buku_induk where f='02' ";	break; }
			case "06" : case "kib_c_cetak" :{ $tblname = " buku_induk where f='03' ";	break; }
			case "07" : case "kib_d_cetak": { $tblname = " buku_induk where f='04' ";	break; }
			case "08" : case "kib_e_cetak": { $tblname = " buku_induk where f='05' ";	break; }
			case "09" : case "kib_f_cetak": { $tblname = " buku_induk where f='06' "; break; }
			case "kibg" : case "kib_g_cetak": { $tblname = " buku_induk where f='07' "; break; }
			default : $tblname = ' buku_induk'; break;
		}	
		
		$filtTgl = $SPg == '03'? 
			"<div style='float:left;padding: 0 4 0 0'> ".
			genComboBoxQry('fmTglBuku',$fmTglBuku,
				"select tgl_buku from $tblname group by tgl_buku order by tgl_buku desc",
				'tgl_buku', 'tgl_buku','Semua Tgl. Buku').
			"</div>"
			: '';		
		$BarisPerHalaman = 			
			" Baris per halaman <input type=text name='jmPerHal' id='jmPerHal' size=4 value='$Main->PagePerHal'>
			<input type=button onClick=\"Penatausaha.refreshList(true)\" value='Tampilkan'>";
		$dalamRibuan = " <input $cbxDlmRibu id='cbxDlmRibu' type='checkbox' value='checked' name='cbxDlmRibu' > Dalam Ribuan ";				
		
		$batas = $Main->batas;//"<div style='float:left;margin: 0 8 0 4;border-left: 1px solid #E5E5E5;height:22'></div>";
		$baris = $Main->baris;//"<div style='border-top: 1px solid #E5E5E5;height:1'></div>";
		
		$vtgl_buku = 
			"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tgl. Buku </div>".				
			createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1);
		$vthn_buku = 
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				genComboBoxQry('fmFiltThnBuku',$fmFiltThnBuku,
					"select year(tgl_buku)as thnbuku from $tblname group by thnbuku desc",
					'thnbuku', 'thnbuku','Tahun Buku'). 
			"</div>";
		$fmThnSensus = $_REQUEST['fmThnSensus'];
		if ($fmThnSensus=='1')
		{
			 $Kondisi .=" and tahun_sensus <>'".$Main->thnsensus_default."' ";
		} else {
			
		
			
		$aqry = "select tahun_sensus from view_buku_induk where tahun_sensus<>'' group by tahun_sensus desc ";
		$qry = mysql_query($aqry);
		$Input = "<option value=''>Tahun Sensus</option><option value='belum_sensus'>Belum Sensus</option>"; 
		while ($Hasil=mysql_fetch_array($qry)) { 
			//$Sel = $Hasil['tahun_sensus']==$value?"selected":""; 
			$Input .= "<option $Sel value='{$Hasil['tahun_sensus']}'>{$Hasil['tahun_sensus']}</option>"; 
		} 
		$Input  = "<select $param name='tahun_sensus' id='tahun_sensus'>$Input</select>"; 

		$vthn_sensus = 
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				$Input.
			"</div>";
		}
		$vthn_perolehan =
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				genComboBoxQry('fmTahunPerolehan',$fmTahunPerolehan,
					"select thn_perolehan from $tblname group by thn_perolehan order by thn_perolehan desc",
					'thn_perolehan', 'thn_perolehan','Dari Tahun'). 
			"</div>" .
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> s/d </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				genComboBoxQry('fmTahunPerolehan2',$fmTahunPerolehan2,
					"select thn_perolehan from $tblname group by thn_perolehan order by thn_perolehan desc",
					'thn_perolehan', 'thn_perolehan','Tahun'). 
			"</div>" ;
		$formcaribi = $_REQUEST['formcaribi'];
		if($formcaribi ==1){
			$vkondisi_barang = '';// "<input type='hidden' id='fmKONDBRG' name='fmKONDBRG' value='3'>";
		}else{
			// $ArBarang=$Main->KondisiBarang;
			$ArBarang=array_merge($Main->KondisiBarang,$Main->KondisiBarangLainnya);
			$vkondisi_barang = 
				"<div style='float:left;padding: 0 4 0 4;height:22;'>".
					cmb2D_v2('fmKONDBRG',$fmKONDBRG, $ArBarang,'','Kondisi Barang','').
				"</div>";
				
		}
		$vkode_barang =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0 ' > Kode Barang </div>".				
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				"<input id='kode_barang' name='kode_barang' value='$kode_barang' title='Cari Kode Barang (ex: 01.02.01.01.01)'>".
				"<input type='hidden' id='jns' name='jns' value='$jns' >".
			"</div>";
		$vnm_barang = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0 '> Nama Barang </div>".				
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				"<input id='nama_barang' name='nama_barang' value='' title='Cari Nama Barang (ex: Meja Kayu)'>".
			"</div>" ;
		$vhrg_perolehan=
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Harga Perolehan Rp </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input type='text' name='jml_harga1' id='jml_harga1' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'
				title = 'Cari Barang dengan harga perolehan lebih dari atau sama dengan (ex: 1000000)'
				>".
			"</div>" .	
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> s/d </div>".			
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input type='text' name='jml_harga2' id='jml_harga2' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'
				title = 'Cari Barang dengan harga perolehan kurang dari atau sama dengan (ex: 1000000)'
				>".
			"</div>";
		$vstatus_hakpakai =
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				cmb2D_v2('selHakPakai', $selHakPakai, $Main->StatusHakPakai, '','Status Tanah').
			"</div>" ;
		$valamat =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Alamat </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input id='alamat' name='alamat' type='text' value='$alamat' style='width:200'>".
			"</div>" ;
		$vkota =selKabKota_txt_div($alamat_b,$alamat_kota,'',1,'Wilayah').selKecamatan_txt_div($alamat_c,$alamat_kec,'',$alamat_b,1,'Wilayah');
/*
			"
			<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				selKabKota(selKabKota, $selKabKota).
			"</div>".
*/					
		$vnosertifikat = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> No. Sertifikat </div>".			
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				'<input name="noSert" type="text" value="'.$noSert.'">'.
			"</div>" ;
		$vmerk =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0 '> Merk </div>".				
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				"<input id='merk' name='merk' type='text' value='$merk' style=''>".
			"</div>";
		$vbahan = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Bahan </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input id='bahan' name='bahan' type='text' value='$bahan' style=''>".
			"</div>" ;
		$vnopabrik=
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> No. Pabrik </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input id='nopabrik' name='nopabrik' type='text' value='$nopabrik' style=''>".
			"</div>" ;
		$vnorangka =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> No. Rangka </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input id='norangka' name='norangka' type='text' value='$norangka' style=''>".
			"</div>" ;
		$vnomesin =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> No. Mesin </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input id='nomesin' name='nomesin' type='text' value='$nomesin' style=''>".
			"</div>" ;
		$vnopolisi = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> No. Polisi </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input id='nopolisi' name='nopolisi' type='text' value='$nopolisi' style=''>".
			"</div>" ;
		$vnobpkb = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> No. BPKB </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input id='nobpkb' name='nobpkb' type='text' value='$nobpkb' style=''>".
			"</div>" ;
		$vkonst_bertingkat = 
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				cmb2D_v2('konsTingkat', $konsTingkat, $Main->Tingkat, '','-- Bertingkat/Tidak --').
			"</div>";
		$vkonst_beton=
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				cmb2D_v2('konsBeton', $konsBeton, $Main->Beton, '','-- Beton/Tidak --').
			"</div>";
		$vnodokumen =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> No. Dokumen </div>".			
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				'<input name="dokumen_no" type="text" value="'.$dokumen_no.'"> '.
			"</div>";
		$vstatus_tanah =
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				cmb2D_v2('status_tanah', $status_tanah, $Main->StatusTanah, '','-- Status Tanah --').
			"</div>" ;
		$vkode_tanah =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> No. Kode Tanah </div>".			
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				'<input name="kode_tanah" type="text" value="'.$kode_tanah.'" style="width: 214px;"> '.
			"</div>" ;
		$vkonstruksi =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Konstruksi </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input name="konstruksi" type="text" value="'.$konstruksi.'"> '.
			"</div>" ;
		$vjudul = 														
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>Judul Buku</div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="judul" name="judul" type="text" value="'.$judul.'" title="Judul Buku Perpustakaan"> '.
			"</div>" ;
		$vspesifikasi = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Spesifikasi </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="spesifikasi" name="spesifikasi" type="text" value="'.$spesifikasi.'" title="Spesifikasi Buku Perpustakaan"> '.
			"</div>" ;
		$vseni_asal_daerah = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>Kesenian Asal Daerah </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="seni_asal_daerah" name="seni_asal_daerah" type="text" value="'.$seni_asal_daerah.'" title="Asal Daerah Kesenian/Kebudayaan"> '.
			"</div>" ;
		$vseni_pencipta = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Pencipta </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="seni_pencipta" name="seni_pencipta" type="text" value="'.$seni_pencipta.'" title="Pencipta Kesenian/Kebudayaan"> '.
			"</div>" ;			
		$vseni_bahan = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Bahan </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="seni_bahan" name="seni_bahan" type="text" value="'.$seni_bahan.'" title="Bahan Kesenian/Kebudayaan"> '.
			"</div>" ;
		$vhewan_jenis = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>Hewan Ternak Jenis</div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="hewan_jenis" name="hewan_jenis" type="text" value="'.$hewan_jenis.'" title="Jenis Hewan Ternak"> '.
			"</div>" ;
		$vhewan_ukuran = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>Ukuran</div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="hewan_ukuran" name="hewan_ukuran" type="text" value="'.$hewan_ukuran.'" title="Ukuran Hewan Ternak"> '.
			"</div>" ;
			
		$vbangunan =			
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				cmb2D_v2('bangunan', $bangunan, $Main->Bangunan, '','Tipe Bangunan').	
			"</div>" ;
			
		$vuraian_kibg =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>Uraian</div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="uraian" name="uraian" type="text" value="'.$uraian.'" title=""> '.
			"</div>" ;
		$vluas =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Luas Tanah (m2) </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input type='text' name='luas1' id='luas1' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'				
				title = 'Cari Barang dengan luas tanah lebih dari atau sama dengan (ex: 1000000)'
				
				>".
			"</div>" .	
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> s/d </div>".			
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input type='text' name='luas2' id='luas2' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'				
				title = 'Cari Barang dengan luas tanah kurang dari atau sama dengan (ex: 1000000)'
				
				>".
			"</div>";	
		$vluas_lantai =
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Luas Lantai (m2) </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input type='text' name='luas_lantai1' id='luas_lantai1' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'				
				title = 'Cari Barang dengan luas lantai lebih dari atau sama dengan (ex: 1000000)'
				
				>".
			"</div>" .	
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> s/d </div>".			
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input type='text' name='luas_lantai2' id='luas_lantai2' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'				
				title = 'Cari Barang dengan luas lantai kurang dari atau sama dengan (ex: 1000000)'
				
				>".
			"</div>";	
			
		$valamat_kec = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>Kecamatan</div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="alamat_kec" name="alamat_kec" type="text" value="'.$alamat_kec.'" title=""> '.
			"</div>" ;
		$valamat_kel = 
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'>Kelurahan</div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				'<input id="alamat_kel" name="alamat_kel" type="text" value="'.$alamat_kel.'" title=""> '.
			"</div>" ;
		
		
			
		//detail filter		
		$opsi_height=100;
		switch($SPg){						
			case 'belumsensus':{				
				$opsi_height = 64;
				$OptDetail = '';
					/*"<table width=100%><tr><td>".
						//$vthn_perolehan. $batas.					
						//$vkondisi_barang. $batas.
						//$vthn_sensus.						
						//$TampilFilterSertifikat. 
						//$batas. $vstatus_hakpakai.				
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".						
						$vkode_barang. $batas.	
						$vnm_barang. $batas.	
						$vhrg_perolehan.
					"</td></tr></table>".*/
					
				break;
			}			 
			case '04':{
				$opsi_height =130;
				$OptDetail =
					"<table width=100%><tr><td>".
						$vthn_perolehan. $batas.					
						$vkondisi_barang.
						$TampilFilterSertifikat. 
						$batas. $vstatus_hakpakai.				
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vkode_barang. $batas.	
						$vnm_barang. $batas.	
						$vhrg_perolehan. $batas. 
						// $valamat_kec. $batas. $valamat_kel.
					"</td></tr></table>".
					$baris. 
					"<table width=100%><tr><td>".						 
						$valamat. $batas.
						$vkota.	$batas.
					"</td></tr></table>".
					$baris. 
					"<table width=100%><tr><td>".						 
						$vnosertifikat. $batas.
						$vluas.
					"</td></tr></table>";
				break;
			}
			case '05':{
				$opsi_height =164;
				$OptDetail =
					"<table width=100%><tr><td>".
						$vthn_perolehan. $batas.					
						$vkondisi_barang.
						$TampilFilterSertifikat.				
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vkode_barang. $batas.	
						$vnm_barang. $batas.	
						$vhrg_perolehan.
					"</td></tr></table>".					
					$baris. 
					"<table width=100%><tr><td>".
						$vmerk.	$batas.
						$vbahan. $batas.
						$vnopabrik.	$batas.
						$vnorangka.
					"</td></tr></table>".	
					$baris.
					"<table width=100%><tr><td>".
						$vnomesin. $batas.
						$vnopolisi.	$batas.
						$vnobpkb.
					"</td></tr></table>";
				break;
			}			
			case '06':{ //c
				$opsi_height =164;
				$OptDetail =
					"<table width=100%><tr><td>".
						$vthn_perolehan. $batas.					
						$vkondisi_barang.
						$TampilFilterSertifikat.				
						$batas. $vkonst_bertingkat.	
						$batas. $vkonst_beton. 						
						$batas. $vstatus_tanah.	
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vkode_barang. $batas.	
						$vnm_barang. $batas.	
						$vhrg_perolehan. 
					"</td></tr></table>".					
					$baris. 
					"<table width=100%><tr><td>".
						$valamat. $batas.
						$vkota. $batas.	
					"</td></tr></table>".
					$baris. 
					"<table width=100%><tr><td>".
						$vluas. $batas.
						$vluas_lantai.$batas.					
						$vnodokumen. $batas.
						$vkode_tanah.					
					"</td></tr></table>";					
				break;
			}
			case '07':{
				$opsi_height =130;
				$OptDetail =
					"<table width=100%><tr><td>".
						$vthn_perolehan. $batas.					
						$vkondisi_barang.
						$TampilFilterSertifikat.				
						$batas.	$vstatus_tanah.
						$batas.	$vkode_tanah.
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vkode_barang. $batas.	
						$vnm_barang. $batas.	
						$vhrg_perolehan.  
					"</td></tr></table>".					
					$baris. 
					"<table width=100%><tr><td>".
						$valamat.
						$batas.	$vkota.
					"</td></tr></table>".
					$baris. 
					"<table width=100%><tr><td>".
						$vkonstruksi.
						$batas. $vnodokumen.						
					"</td></tr></table>";
					
				break;
			}
			case '08':{
				$opsi_height =164;
				$OptDetail =
					"<table width=100%><tr><td>".
						$vthn_perolehan. $batas.					
						$vkondisi_barang.
						$TampilFilterSertifikat.				
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vkode_barang. $batas.	
						$vnm_barang. $batas.	
						$vhrg_perolehan. 
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vjudul. $batas.					
						$vspesifikasi. $batas.
						$vhewan_jenis. $batas.
						$vhewan_ukuran. 
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vseni_asal_daerah. $batas.
						$vseni_pencipta. $batas.
						$vseni_bahan. $batas.						
					"</td></tr></table>";
				break;
			}
			case '09':{
				$opsi_height =130;
				$OptDetail =
					"<table width=100%><tr><td>".
						$vthn_perolehan. $batas.					
						$vkondisi_barang.
						$TampilFilterSertifikat.
						$batas. $vbangunan.	
						$batas. $vkonst_bertingkat.	
						$batas. $vkonst_beton. 						
						$batas. $vstatus_tanah.					
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vkode_barang. $batas.	
						$vnm_barang. $batas.	
						$vhrg_perolehan.  $batas. $valamat_kec. $batas. $valamat_kel.
					"</td></tr></table>".
					$baris. 
					"<table width=100%><tr><td>".
						$valamat. $batas.
						$vkota. $batas.	
					"</td></tr></table>".
					$baris. 
					"<table width=100%><tr><td>".
						$vnodokumen. $batas.
						$vkode_tanah.					
					"</td></tr></table>";					;
				break;
			}
			case 'kibg':{
				$opsi_height =130;
				$OptDetail =
					"<table width=100%><tr><td>".
						$vthn_perolehan. $batas.					
						$vkondisi_barang.
						$TampilFilterSertifikat.				
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vkode_barang. $batas.	
						$vnm_barang. $batas.	
						$vhrg_perolehan.
					"</td></tr></table>".					
					$baris. 
					"<table width=100%><tr><td>".
						$vuraian_kibg.	
					"</td></tr></table>"	
					;
				break;
			}			
			default:{//03
				$opsi_height = 94;
				$OptDetail =
					"<table width=100%><tr><td>".
						$vthn_perolehan. $batas.					
						$vkondisi_barang. $batas.
						$vthn_sensus.
						$TampilFilterSertifikat. 
						//$batas. $vstatus_hakpakai.				
					"</td></tr></table>".
					$baris.
					"<table width=100%><tr><td>".
						$vkode_barang. $batas.	
						$vnm_barang. $batas.	
						$vhrg_perolehan.
					"</td></tr></table>";
				break;
			}
		}	
		
		//--- KIR
		if($SPg == 'KIR'){
			$cek = '';
			$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
			$fmPILRUANG = $_REQUEST['fmPILRUANG'];
			if (empty($fmSKPD)) {
		        if (isset($HTTP_COOKIE_VARS['cofmSKPD'])) { $fmSKPD = $HTTP_COOKIE_VARS['cofmSKPD']; }
		    }		   
		    if (empty($fmUNIT)) {
		        if (isset($HTTP_COOKIE_VARS['cofmUNIT'])) { $fmUNIT = $HTTP_COOKIE_VARS['cofmUNIT']; }
		    }
		    if (empty($fmSUBUNIT)) {
		        if (isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])) { $fmSUBUNIT = $HTTP_COOKIE_VARS['cofmSUBUNIT']; }
		    }		   	    
		    if (empty($fmSEKSI)) {
		        if (isset($HTTP_COOKIE_VARS['cofmSEKSI'])) { $fmSEKSI = $HTTP_COOKIE_VARS['cofmSEKSI']; }
		    }		   	    
			$c= $fmSKPD; $d= $fmUNIT; $e= $fmSUBUNIT;$e1= $fmSEKSI;
			
			//-- kondisi gedung
			$arrkondgdg = array();			
			if(!($c=='' || $c =='00')) $arrkondgdg[] = " c = '$c' ";
			if(!($d=='' || $d =='00')) $arrkondgdg[] = " d = '$d' ";
			if(!($e=='' || $e =='00')) $arrkondgdg[] = " e = '$e' ";						
			if(!($e1=='' || $e1 =='00' || $e1 =='000')) $arrkondgdg[] = " e1 = '$e1' ";						
			$arrkondgdg[] =  "q='0000'";
			$Kondisigdg = join(' and ',$arrkondgdg);
			if($Kondisigdg != '') $Kondisigdg = ' where '.$Kondisigdg;
			
			//-- kondisi ruang
			if(!($fmPILGEDUNG=='' )) {
				$arrkondgdg = array();
				//$arrkondgdg[] =  "q<>'0000'";	
				$arrkondgdg = explode(' ',$fmPILGEDUNG);	
				$c = $arrkondgdg[0]; $d = $arrkondgdg[1]; 
				$e = $arrkondgdg[2]; $e1 = $arrkondgdg[3];$p = $arrkondgdg[4];
				
				$arrkondgdg = array();
				if(!($c=='' || $c =='00')) $arrkondgdg[] = " c = '$c' ";
				if(!($d=='' || $d =='00')) $arrkondgdg[] = " d = '$d' ";
				if(!($e=='' || $e =='00')) $arrkondgdg[] = " e = '$e' ";
				if(!($e1=='' || $e1 =='00' || $e1 =='000')) $arrkondgdg[] = " e1 = '$e1' ";
			
				$arrkondgdg[] = " p = '$p' ";
				$KondisiRuang = join(' and ',$arrkondgdg);
				if($KondisiRuang != '') $KondisiRuang = ' where '.$KondisiRuang;
			}else{
				$KondisiRuang = ' where 1= 0 ';
			}
			
			
			//-- kondisi KIR
			if(!($fmPILGEDUNG=='' )) {
				
				$arrkondgdg = array();							
				if(!($fmPILRUANG=='' )) {
					$arrkondgdg = explode(' ',$fmPILRUANG);	
					$c = $arrkondgdg[0]; $d = $arrkondgdg[1]; 
					$e = $arrkondgdg[2]; $e1 = $arrkondgdg[3]; $p = $arrkondgdg[4];
					$q = $arrkondgdg[5];
					$arrkondgdg = array();
					$arrkondgdg[] =  "q<>'0000'";
					$arrkondgdg[] = " c = '$c' ";
					$arrkondgdg[] = " d = '$d' ";
					$arrkondgdg[] = " e = '$e' ";
					$arrkondgdg[] = " e1 = '$e1' ";
					$arrkondgdg[] = " p = '$p' ";	
					$arrkondgdg[] = " q = '$q' ";	
				}else{
					$arrkondgdg = array();
					$arrkondgdg[] =  "q<>'0000'";
					$arrkondgdg[] = " c = '$c' ";
					$arrkondgdg[] = " d = '$d' ";
					$arrkondgdg[] = " e = '$e' ";
					$arrkondgdg[] = " e1 = '$e1' ";
					$arrkondgdg[] = " p = '$p' ";	
				}
				$KondisiRuang = join(' and ',$arrkondgdg);
				if($KondisiRuang != '') $KondisiRuang = ' where '.$KondisiRuang;
				
				$arruang = array();
				$aqry = "select * from ref_ruang $KondisiRuang"; $cek .= $aqry;
				$qry = mysql_query($aqry);
				while($isi=mysql_fetch_array($qry)){
					$arruang[] = $isi['id'];
				}
				$kondkir = join(',',$arruang);
				
				if($kondkir != '') {
					$kondkir = ' ref_idruang in('.$kondkir.')';	
				}else{ //tidak ada data
					$kondkir = ' 1=0 ';				
				}
				$Kondisi = $Kondisi == '' ? ' where '.$kondkir : $Kondisi. ' and '. $kondkir;
			}
			
			//-- tampil filter KIR
			$OptKIR = //'tes' ;	
				genFilterBarfn(
					array( 						
						' Gedung &nbsp; '.						
						"<span id='cbxGedung'>".
						genComboBoxQry2( 'fmPILGEDUNG', $fmPILGEDUNG, 
							"select * from ref_ruang $Kondisigdg order by c,d,e,e1,p,q ",
							array('c','d','e','e1','p'), 'nm_ruang', '-- Semua Gedung --',"style='width:140' onChange=\"Penatausaha.pilihGedungOnchange()\"" ).
						"</span>".//.$Kondisigdg.
						'&nbsp;&nbsp;Ruang &nbsp;'.
						"<span id='cbxRuang'>".
						genComboBoxQry( 'fmPILRUANG', $fmPILRUANG, 
							"select * from ref_ruang  $KondisiRuang  order by c,d,e,e1,p,q",
							'q', 'nm_ruang', '-- Semua Ruang --',"style='' onChange=\"Penatausaha.refreshList(true);Penatausaha.tampilPJRuang();\" "  ).
						"</span>"//.$aqry//.$KondisiRuang
						."&nbsp;&nbsp;<b>Penanggung Jawab Ruangan: </b><span id='pjruang' name='pjruang'></span>"
					)				
					,'',FALSE, ''
				);
			
			
		}
		
		
		//--- KIP
		if($SPg == 'KIP'){
			$arrJnsPegawai = array(				
				array('1','Penanggung Jawab Barang'),	
				array('2','Pengurus Barang'),		
				array('3','Pengguna/Kuasa Pengguna Barang')
			);
			$arrNIPNama = array(				
				array('1','NIP'),	
				array('2','Nama')
			);
			$KondKIP = '';
			
			//kondisi kip --------------------------------------------------			
			$fmPILNIPNAMA = $_REQUEST['fmPILNIPNAMA'];
			$fmPILJNSPEGAWAI = $_REQUEST['fmPILJNSPEGAWAI'];
			$fmEntryNIPNAMA = $_REQUEST['fmEntryNIPNAMA'];
			if($fmEntryNIPNAMA !='' && $fmPILNIPNAMA != ''){
				switch($fmPILNIPNAMA){
					case '1': 
						switch($fmPILJNSPEGAWAI){
							case '1': $Kond = " nip_pemegang like '%$fmEntryNIPNAMA%' "; break;
							case '2': $Kond = " nip_pengurus like '%$fmEntryNIPNAMA%' "; break;
							case '3': $Kond = " nip_pengguna like '%$fmEntryNIPNAMA%' "; break;
							default : $Kond = " (nip_pemegang like '%$fmEntryNIPNAMA%' or nip_pengurus like '%$fmEntryNIPNAMA%' or nip_pengguna like '%$fmEntryNIPNAMA%' )"; 
						}
						//$aqry = "select * from v1_buku_induk_pegawai left join ref_pegawai  where nip ='$fmEntryNIPNAMA'";
						$aqry = "select * from v1_buku_induk_pegawai where $Kond";	
					break;
					case 2:
						switch($fmPILJNSPEGAWAI){
							case '1': $Kond = " nm_pemegang like '%$fmEntryNIPNAMA%' "; break;
							case '2': $Kond = " nm_pengurus like '%$fmEntryNIPNAMA%' "; break;
							case '3': $Kond = " nm_pengguna like '%$fmEntryNIPNAMA%' "; break;
							default : $Kond = " (nm_pemegang like '%$fmEntryNIPNAMA%' or nm_pengurus like '%$fmEntryNIPNAMA%' or nm_pengguna like '%$fmEntryNIPNAMA%' )"; 
						}
						$aqry = "select * from v1_buku_induk_pegawai where $Kond";												
					break;
					
				}
				$cek .= ' qry kir='.$aqry;
				$pgw=mysql_query($aqry);
				$arrkondKIP = array();
				while($isipgw = mysql_fetch_array($pgw) ){
					$arrkondKIP[] = $isipgw['id'];
				}
				$kondKIP = join(',',$arrkondKIP);
				if($kondKIP != '') {
					$kondKIP = ' id in('.$kondKIP.')';
				}else{ //tidak ada data
					$kondKIP = ' 1=0 ';
				}
				$Kondisi = $Kondisi == '' ? ' where '.$kondKIP : $Kondisi. ' and '. $kondKIP;
				
			}
			
				
			//tampil kip --------------------------------------------------------			
			$OptKIP = //'tes' ;	
				genFilterBarfn(
					array( 						
						' Jenis Pegawai &nbsp; '.						
						"<span id='cbxJnsPegawai'>".						
							cmb2D_v2('fmPILJNSPEGAWAI',$fmPILJNSPEGAWAI,$arrJnsPegawai,'','-- Semua --','').
						"</span>".
						'&nbsp;&nbsp;Cari &nbsp;'.
						"<span id='cbxPilNipNama'>".
							cmb2D_v2('fmPILNIPNAMA',$fmPILNIPNAMA,$arrNIPNama,'', '-- Pilih --','').
							"&nbsp;&nbsp;<input type='text' id='fmEntryNIPNAMA' name='fmEntryNIPNAMA' value='' style='width:300'>".
							
						"</span>".
						"<input type='button' onclick='Sensus.pilihPemegang()' value='Pilih' title='Pilih Pegawai'>"
						//"<input type='button' onclick=\"document.getElementById('fmEntryNIPNAMA').value='';\" value='Clear' title='Reset Pegawai'>"
					)				
					,'',FALSE, ''
				);
				
			
		}
				
		//--- FILTER 
		if($SPg == 'belumsensus'){
			$TampilOpt =
				"<input type='hidden' id='tahun_sensus' name='tahun_sensus' value='belum_sensus'>".
				"<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin: 4 0 0 0;'>
				<tr valign=\"top\">   		
				<td> ".
				
				
					"<table width=100%><tr><td>".
						"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tampilkan : </div>".					
						$vtgl_buku.	
						"<input type=button onClick=\"Penatausaha.refreshList(true)\" value='Tampilkan'>".
					"</td></tr></table>".
					
					
				"</td>
				</tr>
				</table>";
		}else{
			$TampilOpt =
				$OptKIR.
				$OptKIP.
				$OptCari.			
				"<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin: 4 0 0 0;'>
				<tr valign=\"top\">   		
				<td> ".
				"<div id='' style='float:right; padding: 2 0 0 0' >
					<img id='daftaropsi_slide_img' src='images/tumbs/down_2.png' onclick=\"daftaropsi_click($opsi_height)\" style='cursor:pointer'>
				</div>".
				"<div id='daftaropsi_div' style='height:60;overflow-y:hidden;'>".				
					"<table width=100%><tr><td>".
						"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tampilkan : </div>".					
						$vtgl_buku.
						$batas.
						$vthn_buku.
					"</td></tr></table>".
					$baris.				
					$OptDetail.
				"</div>".					
					
				"</td>
				</tr>
				</table>".
				"<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin: 4 0 0 0;'>
				<tr valign=\"top\"><td> ".
					"<div style='float:left'>".
					//$FilterStatus . "&nbsp;&nbsp" .
					$TampilOrder . "&nbsp;&nbsp;" .			
					$dalamRibuan . "&nbsp;&nbsp;" .
					$BarisPerHalaman . "&nbsp;&nbsp;" .
					"</div>".
				"</td></tr></table>"
				;//*/	
			}
		
		
		//limit --------------
		/*$limitdata = $_REQUEST['limitdata'];
		$limitstart = $_REQUEST['limitstart'];
		$limitbanyak = $_REQUEST['limitbanyak'];
		if ($limitdata==1){
			$LimitHal = " limit ".$limitstart.", ".$limitbanyak;
		}else{*/
			$HalDefault = isset($_REQUEST['HalDefault'])? $_REQUEST['HalDefault'] : 1 ; //cekPOST("HalDefault", 1); //echo "HalDefault=$HalDefault";
			$LimitHal = " limit " . (($HalDefault * 1) - 1) * $Main->PagePerHal . "," . $Main->PagePerHal;	
		//}
		
		
		//$cek ='';		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Urutkan, 'Limit'=>$LimitHal, 'NoAwal'=>$NoAwal, 
		'TampilOpt'=>$TampilOpt, 'OptWil'=>$OptWil, 'TampilOrder'=>'tbl='.$tblname.$TampilOrder, 'cek'=>$cek  );
	}
	function getTableName($spg){
		
		//get table ---------------------------
		switch($spg){
			case "KIP" :{ $tblNameList = 'view_buku_induk2'; break; }
			case "KIR" :{ $tblNameList = 'view_buku_induk2'; break; }
			case "belumsensus" :{ $tblNameList = 'view_buku_induk2'; break; }
			case "03" : case "listbi_cetak" :{ $tblNameList = 'view_buku_induk2'; break; }
			case "04" : case "kib_a_cetak" :{ $tblNameList = 'view_kib_a2';	break; }
			case "05" : case "kib_b_cetak" :{ $tblNameList = 'view_kib_b2';	break; }
			case "06" : case "kib_c_cetak" :{ $tblNameList = 'view_kib_c2';	break; }
			case "07" : case "kib_d_cetak": { $tblNameList = 'view_kib_d2';	break; }
			case "08" : case "kib_e_cetak": { $tblNameList = 'view_kib_e2';	break; }
			case "09" : case "kib_f_cetak": { $tblNameList = 'view_kib_f2'; break; }
			case "kibg" : case "kib_g_cetak": { $tblNameList = 'view_kib_g'; break; }


			default : $tblNameList = 'view_buku_induk2'; break;
		}	
		return $tblNameList;
	}
		
	function genList($Kondisi='', $Urutkan='',$LimitHal, $cetak = FALSE, $AllData=0,$SPg_='', $tipe=''){
		/*******************************
		* fungsi : untuk gen row buku_induk, kib_a ... f
		* Proses :
		* digunakan pada:
		* - listbi_cetak.php
		* - listbi_cetak_xls.php
		* - listbi.php
		****************************/
		global $Main ; 
		//$tipebi = $_REQUEST['tipebi'];
		$cek ='';
		
		$MaxFlush = 10;
		
		$cbxDlmRibu = $_POST['cbxDlmRibu'];
		$txls = $_REQUEST['xls']=="1"?"1":"";
		$SPg = $SPg_ ==''? $_GET['SPg'] : $SPg_; $cek .= "SPg = $SPg";
		
		//get table ---------------------------		
		//$tblNameList = $this->getTableName($SPg);		
		switch($SPg){
			case "belumsensus" :{ $tblNameList = 'view_buku_induk2'; break; }
			case "03" : case "listbi_cetak" :{ $tblNameList = 'view_buku_induk2'; break; }
			case "04" : case "kib_a_cetak" :{ $tblNameList = 'view_kib_a';	break; }
			case "05" : case "kib_b_cetak" :{ $tblNameList = 'view_kib_b2';	break; }
			case "06" : case "kib_c_cetak" :{ $tblNameList = 'view_kib_c';	break; }
			case "07" : case "kib_d_cetak": { $tblNameList = 'view_kib_d2';	break; }
			case "08" : case "kib_e_cetak": { $tblNameList = 'view_kib_e2';	break; }
			case "09" : case "kib_f_cetak": { $tblNameList = 'view_kib_f2'; break; }
			case "kibg" : case "kib_g_cetak": { $tblNameList = 'view_kib_g'; break; }
			case "03b" : case "listbi_cetak" :{ $tblNameList = 'view_buku_induk2'; break; }

			default : $tblNameList = 'view_buku_induk2'; break;
		}

	
		// $Kondisi .= $KondisiInEx;
		//noawal ----------------------------------
		$limitdata = $_REQUEST['limitdata'];
		$limitstart = $_REQUEST['limitstart']>0 ? $_REQUEST['limitstart']-1 : 0;
		$limitend = $_REQUEST['limitend']>0? $_REQUEST['limitend']: 1;
		if ($limitdata==1){
			$limitbanyak =  $limitend - $limitstart;
			$LimitHal = " limit ".$limitstart.", ".$limitbanyak;
			$no = $limitstart;
		}else{
			$HalDefault = cekPOST('HalDefault',1);
			if ($AllData ==1){
				$no = 0;
				$LimitHal = '';
			}else{
				$no=  $Main->PagePerHal * (($HalDefault*1) - 1);	
			}	
		}
		
		
		
		
		//--- listdata -----------------------------	
		$Kondisi = $Kondisi==''? '' : " where $Kondisi ";
		$clGaris = $cetak? "GarisCetak": "GarisDaftar";	
		$sqry= "select * from $tblNameList $Kondisi  order by $Urutkan a1,a,b,c,d,e,e1,f,g,h,i,j,tahun,noreg "; 
		$sqrysum= "select count(*) as jml,sum(jml_barang) as jml_barang,sum(jml_harga) as jml_harga from $tblNameList $Kondisi "; 

		// echo $sqry;
		
		$Qry = mysql_query($sqry ." $LimitHal");
		$cek .= "<br>sqry= $sqry $LimitHal";
		//echo "<br>sqry= $sqry $LimitHal";
		
		$cb=0; $jmlTotalHargaDisplay = 0; $totLuasTanahHal = 0;
		$ListData = "";
		
		while($isi=mysql_fetch_array($Qry)){ //20 detik
			//tampil pilihan			
			/*if($this->tampilCbxKeranjang && $tipebi==''){
				$id = $SPg=='03'   ?  $isi['id'] : $isi['idbi'];
				$tampilCbxKeranjang =  $cetak ? "" : 
					"<td class=\"$clGaris\" align=center>
						<div id='cbk".($cb+1)."' value='".$id."'></div>
					</td>";		
					
			}
			*/
			if($this->tampilBidang){
				
				$nmopdarr=array();		
				$get = mysql_fetch_array(mysql_query(
					"select * from v_bidang where c='".$isi['c']."' "
				));		
				if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
				$get = mysql_fetch_array(mysql_query(
					"select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' "
				));		
				if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
				$get = mysql_fetch_array(mysql_query(
					"select * from v_unit where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."'"
				));		
				if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
				$get = mysql_fetch_array(mysql_query(
					"select * from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"
				));		
				if($get['nm_skpd']<>'') $nmopdarr[] = $get['nm_skpd'];

				$isi['vBidang'] = "<td class=\"$clGaris\">".join('/<br>', $nmopdarr )."</td>";
				
			}
			
			//get info dinas
			$get = mysql_fetch_array(mysql_query(
				"select * from ref_skpd where c='".$isi['c']."'  and d='00' and e='00'"
			));		
			if($get['nm_barcode']<>'') $nm = $get['nm_barcode'];			
			$infobrg = "  bidang='$nm' ";			
			//get opd
			$get = mysql_fetch_array(mysql_query(
				"select * from ref_skpd where c='".$isi['c']."'  and d='".$isi['d']."' and e='00'"
			));		
			if($get['nm_barcode']<>'') $nm = $get['nm_barcode'];
			$infobrg .= "  opd='$nm' ";
			//get biro
			$get = mysql_fetch_array(mysql_query(
				"select * from ref_skpd where c='".$isi['c']."'  and d='".$isi['d']."' and e='".$isi['e']."'"
			));		
			if($get['nm_barcode']<>'') $nm = $get['nm_barcode'];
			$infobrg .= "  biro='$nm' ";
			//get biro
			$get = mysql_fetch_array(mysql_query(
				"select * from ref_skpd where c='".$isi['c']."'  and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"
			));		
			if($get['nm_barcode']<>'') $nm = $get['nm_barcode'];
			$infobrg .= "  seksi='$nm' ";


			//get kode
			$infobrg .= " kode='".$isi['idall2']."'";			
			$infobrg .= " nmbarang='".$isi['nm_barang']."'";
			$kondisi = $isi['kondisi'];
			
			//$tipe=$_REQUEST['tipe'];
			$kondisi_kk =
				"<img src='images/checkbox.png'> B<br>".
				"<img src='images/checkbox.png'> KB<br>".
				"<img src='images/checkbox.png'> RB<br>".
				"<img src='images/checkbox.png'> TD<br>".							
				"";
			$status_penguasaan = 
				"<img src='images/checkbox.png'> Digunakan<br>".
				"<img src='images/checkbox.png'> Dimanfaatkan<br>".
				"<img src='images/checkbox.png'> Iddle<br>".
				"<img src='images/checkbox.png'> Dikuasai Pihak Ketiga<br>".
				"<img src='images/checkbox.png'> Sengketa<br>".
				"";
	$ketskpd="";

					if ($isi['nmopd']<>'')  $ketskpd=$isi['nmopd'];
					if ($isi['nmunit']<>'' && $isi['nmunit']<>$ketskpd ) $ketskpd="$ketskpd/<br>".$isi['nmunit'];
					if ($isi['nmseksi']<>'' && $isi['nmseksi']<>$ketskpd ) $ketskpd="$ketskpd/<br>".$isi['nmseksi'];
					if ($ketskpd<>'')  $ketskpd="/<br>$ketskpd";
					 				
			switch($SPg){
				case "03" : case "listbi_cetak" : case 'belumsensus': case 'KIR' : case 'KIP' :{				
					
					$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];	//$kdKelBarang = $isi['f'].$isi['g']."00";
					$AsalUsul = $isi['asal_usul'];	
					$ISI5 = "";	$ISI6 = "";	$ISI7 = "";	$ISI10 = ""; $ISI15='';

					$check_ =  "<input type=\"checkbox\" $Checked $infobrg id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);"."Penatausaha.cbxPilih(this)\" />"; //<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);\" />";
					$tampilCheckbox = $cetak ? "":
						"<td class=\"$clGaris\" align=center>$check_</td>";
						
					
					//--- ambil data kib by noreg --------------------------------				
					if ($isi['f'] == "01" || $isi['f'] == "02" || $isi['f'] == "03" || $isi['f'] == "04" || $isi['f'] == "05" || $isi['f'] == "06" || $isi['f'] == "07") {
						$KondisiKIB = "
						where 
						a1= '{$isi['a1']}' and 
						a = '{$isi['a']}' and 
						b = '{$isi['b']}' and 
						c = '{$isi['c']}' and 
						d = '{$isi['d']}' and 
						e = '{$isi['e']}' and 
						e1 = '{$isi['e1']}' and 
						f = '{$isi['f']}' and 
						g = '{$isi['g']}' and 
						h = '{$isi['h']}' and 
						i = '{$isi['i']}' and 
						j = '{$isi['j']}' and 
						noreg = '{$isi['noreg']}' and 
						tahun = '{$isi['tahun']}' ";
					}
					if ($isi['f'] == "01") {//KIB A
						//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'
						$QryKIB_A = mysql_query("select * from kib_a  $KondisiKIB  limit 0,1");
						while ($isiKIB_A = mysql_fetch_array($QryKIB_A)) {
							if($SPg == 'belumsensus'){
								$alm = '';
								$alm .= ifempty($isiKIB_A['alamat'],'-');		
								$alm .= $isiKIB_A['alamat_kel'] != ''? '<br>Kel. '.$isiKIB_A['alamat_kel'] : '';
								$alm .= $isiKIB_A['alamat_kec'] != ''? '<br>Kec. '.$isiKIB_A['alamat_kec'] : '';
								$alm .= $isiKIB_A['alamat_kota'] != ''? '<br>'.$isiKIB_A['alamat_kota'] : '';
								$ISI5 = $alm;
							}else{
								$ISI5 = '';
							}
							$ISI6 = "{$isiKIB_A['sertifikat_no']}";  //$ISI10 = "{$isiKIB_A['luas']}";
							$ISI15 = "{$isiKIB_A['ket']}";
							$ISI10 = number_format($isiKIB_A['luas'],2,',','.');
						}
					}
					if ($isi['f'] == "02") {//KIB B;
						//"concat(a1,a,b,c,d,e,f,g,h,i,j,noreg,tahun)='{$isi['a1']}{$isi['a']}{$isi['b']}{$isi['c']}{$isi['d']}{$isi['e']}{$isi['f']}{$isi['g']}{$isi['h']}{$isi['i']}{$isi['j']}{$isi['noreg']}{$isi['tahun']}'";
						$QryKIB_B = mysql_query("select * from kib_b  $KondisiKIB limit 0,1");
						while ($isiKIB_B = mysql_fetch_array($QryKIB_B)) {
							$ISI5 = "{$isiKIB_B['merk']}";
							$ISI6 = "{$isiKIB_B['no_pabrik']} / {$isiKIB_B['no_rangka']} / {$isiKIB_B['no_mesin']}";
							$ISI7 = "{$isiKIB_B['bahan']}";							
							$ISI15 = "{$isiKIB_B['ket']}";
						}
					}
					if ($isi['f'] == "03") {//KIB C;
						$QryKIB_C = mysql_query("select * from kib_c  $KondisiKIB limit 0,1");
						while ($isiKIB_C = mysql_fetch_array($QryKIB_C)) {
							if($SPg == 'belumsensus'){
								$alm = '';
								$alm .= ifempty($isiKIB_C['alamat'],'-');		
								$alm .= $isiKIB_C['alamat_kel'] != ''? '<br>Kel. '.$isiKIB_C['alamat_kel'] : '';
								$alm .= $isiKIB_C['alamat_kec'] != ''? '<br>Kec. '.$isiKIB_C['alamat_kec'] : '';
								$alm .= $isiKIB_C['alamat_kota'] != ''? '<br>'.$isiKIB_C['alamat_kota'] : '';
								$ISI5 = $alm;
							}else{
								$ISI5 = '';
							}
							$ISI6 = "{$isiKIB_C['dokumen_no']}";
							$ISI10 = $Main->Bangunan[$isiKIB_C['kondisi_bangunan'] - 1][1];
							$ISI15 = "{$isiKIB_C['ket']}";
						}
					}
					if ($isi['f'] == "04") {//KIB D;
						$QryKIB_D = mysql_query("select * from kib_d  $KondisiKIB limit 0,1");
						while ($isiKIB_D = mysql_fetch_array($QryKIB_D)) {
							if($SPg == 'belumsensus'){
								$alm = '';
								$alm .= ifempty($isiKIB_D['alamat'],'-');		
								$alm .= $isiKIB_D['alamat_kel'] != ''? '<br>Kel. '.$isiKIB_D['alamat_kel'] : '';
								$alm .= $isiKIB_D['alamat_kec'] != ''? '<br>Kec. '.$isiKIB_D['alamat_kec'] : '';
								$alm .= $isiKIB_D['alamat_kota'] != ''? '<br>'.$isiKIB_D['alamat_kota'] : '';
								$ISI5 = $alm;
							}else{
								$ISI5 = '';
							}
							$ISI6 = "{$isiKIB_D['dokumen_no']}";
							$ISI15 = "{$isiKIB_D['ket']}";
						}
					}
					if ($isi['f'] == "05") {//KIB E;
						$QryKIB_E = mysql_query("select * from kib_e  $KondisiKIB limit 0,1");
						while ($isiKIB_E = mysql_fetch_array($QryKIB_E)) {
							$ISI7 = "{$isiKIB_E['seni_bahan']}";
							$ISI15 = "{$isiKIB_E['ket']}";
						}
					}
					if ($isi['f'] == "06") {//KIB F;
						$sQryKIB_F = "select * from kib_f  $KondisiKIB limit 0,1";
						$QryKIB_F = mysql_query($sQryKIB_F);
						//echo "<br>qrykibf= $sQryKIB_F";
						while ($isiKIB_F = mysql_fetch_array($QryKIB_F)) {
							if($SPg == 'belumsensus'){
								$alm = '';
								$alm .= ifempty($isiKIB_F['alamat'],'-');		
								$alm .= $isiKIB_F['alamat_kel'] != ''? '<br>Kel. '.$isiKIB_F['alamat_kel'] : '';
								$alm .= $isiKIB_F['alamat_kec'] != ''? '<br>Kec. '.$isiKIB_F['alamat_kec'] : '';
								$alm .= $isiKIB_F['alamat_kota'] != ''? '<br>'.$isiKIB_F['alamat_kota'] : '';
								$ISI5 = $alm;
							}else{
								$ISI5 = '';
							}
							$ISI6 = "{$isiKIB_F['dokumen_no']}";
							$ISI10 = $Main->Bangunan[$isiKIB_F['bangunan'] - 1][1];
							$ISI15 = "{$isiKIB_F['ket']}";
						}
					}
					if ($isi['f'] == "07") {//KIB E;
						$QryKIB_E = mysql_query("select * from kib_g  $KondisiKIB limit 0,1");
						while ($isiKIB_E = mysql_fetch_array($QryKIB_E)) {
							$ISI7 = "{$isiKIB_E['pencipta']}";
//							$ISI7 = "{$isiKIB_E['jenis']}";
							$ISI15 = "{$isiKIB_E['ket']}";
						}
					}					
					
					//*******************************************************
					
					$ISI5 = !empty($ISI5) ? $ISI5 : "-";
					$ISI6 = !empty($ISI6) ? $ISI6 : "-";
					$ISI7 = !empty($ISI7) ? $ISI7 : "-";
					$ISI10 = !empty($ISI10) ? $ISI10 : "-";
					$ISI12 = !empty($ISI12) ? $ISI12 : "-";
					$ISI15 = !empty($ISI15) ? $ISI15 : "-";
					if (($fmCariComboField != 'ket')||($fmCariComboField == 'ket' && stripos( $ISI15, $fmCariComboIsi) !== false  )){						
						if ($sort1 >= 1){			
							$ISI15 	= $ISI15.' /<br>'.TglInd($isi['tgl_buku']).' /<br>'.$isi['tgl_update'].' /<br>'.$isi['tahun_sensus'].$ketskpd ;	
						}else{
							$ISI15 	= $ISI15.' /<br>'.TglInd($isi['tgl_buku']).' /<br>'.$isi['tahun_sensus'].$ketskpd;			
						}		
						//$ISI15 .= $isi['nmbidang'].' - '.$isi['nmopd'].' - '.$isi['nmunit'];	//$ISI15 .= tampilNmSubUnit($isi);//echo"<br>$ISI15";	
						$no++;
						$jmlTotalHargaDisplay += $isi['jml_harga'];
						$clRow = $no % 2 == 0 ?"row1":"row0";
						if ($txls=='1'){
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');					
						
							
						} else 
						{
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');					
						
							
						}
						
						if($tipe=='kertaskerja'){ //cetak kertas kerja sensus
							$ListData .= "
								<tr class=\"$clRow\" valign='top'>
								<td class=\"$clGaris\" align=center>$no.</td>
								$tampilCheckbox
								<td class=\"$clGaris\" align=center>{$isi['a1']}.{$isi['a']}.{$isi['b']}.{$isi['c']}.{$isi['d']}.".substr($isi['thn_perolehan'],2,2).".{$isi['e']}.{$isi['e1']}<br>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}.{$isi['noreg']}<br>{$isi['nm_barang']}</td>								
								
								<td class=\"$clGaris\">$ISI5</td>
								<td class=\"$clGaris\">$ISI6</td>	
								<td class=\"$clGaris\" align=right>{$isi['thn_perolehan']}/<br>$tampilHarga</td>".							
								//"<td class=\"$clGaris\" align=center>".$Main->KondisiBarang[$isi['kondisi']-1][1]."</td>".
								"<td class=\"$clGaris\" align=center>Ada /<br> Tidak</td>".
								"<td class=\"$clGaris\" align=center><br>B / KB / RB</td>
								<td class=\"$clGaris\">&nbsp;</td>
								<td class=\"$clGaris\">&nbsp;</td>
								<td class=\"$clGaris\">Digunakan / Dimanfaatkan/<br>Iddle / Dikuasai Pihak Ketiga /<br>Sengketa</td>
								<td class=\"$clGaris\">&nbsp;</td>
								<td class=\"$clGaris\">&nbsp;</td>".
								
								
								"</tr>";
						}else{					
							$ListData .= "
								<tr class=\"$clRow\" valign='top'>
								<td class=\"$clGaris\" align=center>$no.</td>
								$tampilCheckbox
								<td class=\"$clGaris\" align=center>{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}</td>
								<td class=\"$clGaris\" align=center><div class='nfmt3'> {$isi['noreg']}</div></td>
								<td class=\"$clGaris\">{$isi['nm_barang']}</td>						
								<td class=\"$clGaris\">$ISI5</td>
								<td class=\"$clGaris\">$ISI6</td>
								<td class=\"$clGaris\">$ISI7</td>
								<td class=\"$clGaris\">".$Main->AsalUsul[$AsalUsul-1][1]."<br>/".$Main->StatusBarang[$isi['status_barang']-1][1]."</td>
								<td class=\"$clGaris\" align=center>{$isi['thn_perolehan']}</td>
								<td class=\"$clGaris\">$ISI10</td>
								<td class=\"$clGaris\" align=center>".$Main->KondisiBarang[$isi['kondisi']-1][1]."</td>
								<td class=\"$clGaris\" align=right>{$isi['jml_barang']} {$isi['satuan']}</td>
								<td class=\"$clGaris\" align=right>".$tampilHarga."</td>
								<td class=\"$clGaris\">$ISI15</td>".
								$isi['vBidang'].
								$tampilDok.
								$tampilCbxKeranjang.
								"</tr>	";
						}
					}
					
					//--- ambil data pemeliharaan & pengamanan -----------------------------
					if($tipe!='kertaskerja'){
						$det = Penatausahaan_GetListDet2($isi['idawal'], '', $isi['jml_harga'],$cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);
						$ListData .= $det['ListData'];
						//--- total ------------------------------------------------------------
						$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];
					} 
					
					
					//*/
					break;
				}
				case "04" : case "kib_a_cetak" :{
					
					
					$idBI = $isi['idbi']; //?
					
					$no++;
					$clRow = ""; if ($cetak == FALSE){$clRow = $no % 2 == 0 ?"row1":"row0"; }							
					$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked  $infobrg id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>";
					
						
					//}else{
						$ListData .= Penatausahaan_genTableRowKibA($clRow, $clGaris, $tampilCheckbox, $isi, $no, $tampilCbxKeranjang,$tipe);					
					//}
					
					
					//$SPg=$_REQUEST['SPg'];
					//SPg=belumsensus&tipe=kertaskerja
					//$tipw = $_REQUEST['tipe'];
		
					$det = Penatausahaan_GetListDet($isi['idawal'], '01',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
					$ListData .= $det['ListData'];
					
					$jmlTampilKIB_A++;
					$jmlTotalHargaDisplay += $isi['jml_harga'];
					$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];	
					$totLuasTanahHal += $isi['luas'];
						
					break;
				}
				case "05" : case "kib_b_cetak" :{		
					//while ($isi = mysql_fetch_array($Qry)){
						$idBI = $isi['idbi'];//getIDByKodeBrg('buku_induk', $isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']);
						//$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
						$ISI1 	= !empty($isi['merk'])?$isi['merk']:"-";
						$ISI2 	= !empty($isi['ukuran'])? number_format($isi['ukuran'],0,',','.'):"-";
						$ISI3 	= !empty($isi['bahan'])?$isi['bahan']:"-";
						$ISI4 	= !empty($isi['thn_perolehan'])?$isi['thn_perolehan']:"-";
						$ISI5 	= !empty($isi['no_pabrik'])?$isi['no_pabrik']:"-";
						$ISI6 	= !empty($isi['no_rangka'])?$isi['no_rangka']:"-";
						$ISI7 	= !empty($isi['no_mesin'])?$isi['no_mesin']:"-";
						$ISI8 	= !empty($isi['no_polisi'])?$isi['no_polisi']:"-";
						$ISI9 	= !empty($isi['no_bpkb'])?$isi['no_bpkb']:"-";
						$ISI10 = !empty($Main->AsalUsul[$isi['asal_usul']-1][1])?$Main->AsalUsul[$isi['asal_usul']-1][1]:"-";
						$ISI11 = !empty($Main->StatusBarang[$isi['status_barang']-1][1])?$Main->StatusBarang[$isi['status_barang']-1][1]:"-";
						$ISI11a=!empty($Main->KondisiBarang[$kondisi-1][1])?$Main->KondisiBarang [$kondisi-1][1]:"-";
						$ISI12 = !empty($isi['ket'])?$isi['ket']:"-";
						//$ISI12 .= '<br>'.$isi['nmbidang'].' - '.$isi['nmopd'].' - '.$isi['nmunit'] ;
//						$ISI12 .= tampilNmSubUnit($isi);
						$jmlTampilKIB_B++;
						$jmlTotalHargaDisplay += $isi['jml_harga'];
						$no++;
						$clRow = ""; if ($cetak == FALSE){$clRow = $no % 2 == 0 ?"row1":"row0"; }		
						if ($txls=='1'){
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');
							
						} else {
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');
							
						}
						//$dok = $isi['dokumen_file']=="" ? "" : dokumenlink($isi['dokumen_file'], $isi['dokumen']);
						//$tampilDok = '';//$cetak? "" : "<td class=\"$clGaris\">$dok</td>"; 
						$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $Checked $infobrg id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>";
						
						
						
						if($tipe=='kertaskerja'){
							$ListData .= 
							"<tr class='$clRow'  valign='top' >
							<td class=\"$clGaris\" align=center>$no</td>	
							$tampilCheckbox
							<td class=\"$clGaris\" align=center>{$isi['id_barang']}</td>
							<td class=\"$clGaris\" align=center><div class='nfmt3'>{$isi['noreg']}</div></td>
							<td class=\"$clGaris\" align=left>{$isi['nm_barang']}</td>
							<td class=\"$clGaris\" align=left>{$ISI1}</td>
							<td class=\"$clGaris\">{$ISI2}</td>
							<td class=\"$clGaris\" align=left>{$ISI3}</td>
							<td class=\"$clGaris\" align=center>{$ISI4}</td>
							<td class=\"$clGaris\">{$ISI5}</td>
							<td class=\"$clGaris\">{$ISI6}</td>
							<td class=\"$clGaris\">{$ISI7}</td>
							<td class=\"$clGaris\">{$ISI8}</td>
							<td class=\"$clGaris\">{$ISI9}</td>
							<td class=\"$clGaris\" style='width:100'>".$ISI10."<br>/".$ISI11."</td>
							<td class=\"$clGaris\" align=right>".$tampilHarga."</td>
							<td class=\"$clGaris\">$kondisi_kk</td>".	
							"<td class=\"$clGaris\">$nm_no_ruang</td>".	
							"<td class=\"$clGaris\">$ket_kk</td>".	
							$isi['vBidang'].
							$tampilCbxKeranjang.				
							"</tr>";
						}else{
							$ListData .= 
							"<tr class='$clRow'  valign='top' >
							<td class=\"$clGaris\" align=center>$no</td>	
							$tampilCheckbox
							<td class=\"$clGaris\" align=center>{$isi['id_barang']}</td>
							<td class=\"$clGaris\" align=center><div class='nfmt3'>{$isi['noreg']}</div></td>
							<td class=\"$clGaris\" align=left>{$isi['nm_barang']}</td>
							<td class=\"$clGaris\" align=left>{$ISI1}</td>
							<td class=\"$clGaris\">{$ISI2}</td>
							<td class=\"$clGaris\" align=left>{$ISI3}</td>
							<td class=\"$clGaris\" align=center>{$ISI4}</td>
							<td class=\"$clGaris\">{$ISI5}</td>
							<td class=\"$clGaris\">{$ISI6}</td>
							<td class=\"$clGaris\">{$ISI7}</td>
							<td class=\"$clGaris\">{$ISI8}</td>
							<td class=\"$clGaris\">{$ISI9}</td>
							<td class=\"$clGaris\" style='width:100'>".$ISI10."<br>/".$ISI11."<br>/".$ISI11a."</td>
							<td class=\"$clGaris\" align=right>".$tampilHarga."</td>
							<td class=\"$clGaris\">{$ISI12}$ketskpd </td>".	
							$isi['vBidang'].
							$tampilCbxKeranjang.				
							"</tr>";
						}
						
						
						//$det =Penatausahaan_GetListDet($isi['idbi'], '02',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
						$det =Penatausahaan_GetListDet($isi['idawal'], '02',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox,3);			
						$ListData .= $det['ListData'];
						$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];
						$totLuasTanahHal += $isi['luas'];			
					//}
					break;
				}
				case "06" : case "kib_c_cetak" :{					
					
					$idBI = $isi['idbi'];//getIDByKodeBrg('buku_induk', $isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']);
					//$kota = '<br>'.$isi['alamat_kota'];//getNmWlayah($isi['alamat_a'], $isi['alamat_b']);
					//$Kec = $isi['alamat_kec'];//table_get_value('select alamat_kec from kib_c where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) = "'.$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg'].'"','alamat_kec');				
					//$Kel = $isi['alamat_kel'];//table_get_value('select alamat_kel from kib_c where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) = "'.$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg'].'"','alamat_kel');
					//if($Kec != ''){ $Kec='<br>Kec. '.$Kec;}
					//if($Kel != ''){ $Kel='<br>Kel. '.$Kel;}	
					
					
					
					$jmlTampilKIB_C++;
					$jmlTotalHargaDisplay += $isi['jml_harga'];
					$no++;
					$clRow = ""; if ($cetak == FALSE){$clRow = $no % 2 == 0 ?"row1":"row0"; }		
					$ISI15 = ifempty($isi['ket'],'-');					
					$ISI15 .= tampilNmSubUnit($isi);			
						if ($txls=='1'){
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');
							
						} else {
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');
							
						}
					$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $infobrg $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>";
					
					$tampilKodeTanah = 
						substr($isi['kode_tanah'],0,12)."<BR>".
						substr($isi['kode_tanah'],12,12)."<BR>".
						substr($isi['kode_tanah'],24,12)."<BR>".
						substr($isi['kode_tanah'],36,4);
					$tampilIdBrg =
						substr($isi['id_barang'],0,9)."<BR>".
						substr($isi['id_barang'],9,5);
						
					
					$ListData .= 
					Penatausahaan_genTableRowKibC($clRow,$clGaris,$tampilCheckbox,
					/*array(	$no,
						$tampilIdBrg,
						$isi['noreg'],
						$isi['nm_barang'],
						$isi['tahun'],
						ifempty($Main->KondisiBarang[$isi['kondisi']-1][1],'-'),//ifempty($Main->KondisiBarang[$isi['kondisi_bi']-1][1],'-'),
						ifempty($Main->Tingkat[$isi['konstruksi_tingkat']-1][1],'-'),
						ifempty($Main->Beton [$isi['konstruksi_beton']-1][1],'-'),
						( empty($isi['luas_lantai']) ? "-": number_format($isi['luas_lantai'],2,',','.') ),
						ifempty($isi['alamat'],'-').$Kel.$Kec.$kota,
						ifemptyTgl( TglInd($isi['dokumen_tgl']),'-').'<br>'.ifempty($isi['dokumen_no'],'-'),
						( empty($isi['luas']) ? "-": number_format($isi['luas'],2, ',', '.' ) ),
						ifempty($Main->StatusTanah[$isi['status_tanah']-1][1],'-'),
						$tampilKodeTanah,
						ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-')."<br>/".ifempty($Main->StatusBarang[$isi['status_barang']-1][1],'-'),
						$tampilHarga,
						$ISI15	)*/
						$isi,$no,$tampilCbxKeranjang, $tipe);	
						
					//$det =Penatausahaan_GetListDet($isi['idbi'], '03',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
					$det =Penatausahaan_GetListDet2($isi['idawal'], '03',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox,3);			
					$ListData .= $det['ListData'];
					$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];
					$totLuasTanahHal += $isi['luas'];
					//}
					break;
				}
				case "07" : case "kib_d_cetak" :{									
					
					//$Qry = mysql_query($sqry);
					//$jmlDataKIB_D = table_get_value("select count(*) as cnt from view_kib_d where $Kondisi ",'cnt');// mysql_num_rows($Qry);			
					//$Qry = mysql_query($sqry." $LimitHalKIB_D"); 			
					//$Qry = mysql_query($sqry);			
					//$no=$Main->PagePerHal * (($HalKIB_D*1) - 1);	
					//$cb=0;	$jmlTampilKIB_D = 0;	$jmlTotalHargaDisplay = 0;	$ListData = "";
					//*
					//while ($isi = mysql_fetch_array($Qry)){
					$idBI = $isi['idbi'];// getIDByKodeBrg('buku_induk', $isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']);// table_get_value( $sidBi, 'id' );
					$kota = '<br>'.$isi['alamat_kota'];//getNmWlayah($isi['alamat_a'], $isi['alamat_b']);
					// $Kec = $isi['alamat_kec'];
					//table_get_value('select alamat_kec from kib_d where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) = "'.$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg'].'"','alamat_kec');
					// $Kel = $isi['alamat_kel'];//table_get_value('select alamat_kel from kib_d where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) = "'.$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg'].'"','alamat_kel');
					/*
					if($Kec != ''){ $Kec='<br>Kec. '.$Kec;}
					if($Kel != ''){ $Kel='<br>Kel. '.$Kel;}	
					*/
					$Kec = getalamat($isi['alamat_b'],$isi['alamat_c'],'',$isi['kota'],$isi['alamat_kec'],$isi['alamat_kel']);
					
					$Kel='';
					$kota='';
					$jmlTampilKIB_D++;
					$jmlTotalHargaDisplay += $isi['jml_harga'];
					$no++;
					$clRow =""; 
					if($cetak == FALSE){$clRow = $no % 2 == 0 ?"row1":"row0";}
					$ISI15 = ifempty($isi['ket'],'-');
					//$ISI15 .= '<br>'.$isi['nmbidang'].' - '.$isi['nmopd'].' - '.$isi['nmunit'] ;//tampilNmSubUnit($isi);	
					$ISI15 .= tampilNmSubUnit($isi);	
						if ($txls=='1'){
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');
							
						} else {
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');
							
						}
					$dok = $isi['dokumen_file']=="" ? "" : dokumenlink($isi['dokumen_file'], $isi['dokumen']);
					$tampilDok = '';//$cetak? "" : "<td class=\"$clGaris\">$dok</td>"; 
					$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $infobrg $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>";
					$tampilKodeTanah = $isi['kode_tanah']."<BR>".$isi['kode_loktanah'];//$tampilKodeTanah = ifempty($isi['kode_tanah'],'-');	
					if($tipe=='kertaskerja'){	
						$ListData .= "	
							<tr class='$clRow'  valign='top' >
							<td class=\"$clGaris\" align=center>$no</td>
							$tampilCheckbox
							<td class=\"$clGaris\" align=center>{$isi['id_barang']}</td>
							<td class=\"$clGaris\" align=center><div class='nfmt3'>{$isi['noreg']}</div></td>
							<td class=\"$clGaris\" >{$isi['nm_barang']}</td>
							<td class=\"$clGaris\" >{$isi['tahun']}</td>
							<td class=\"$clGaris\" >".ifempty($isi['konstruksi'],'-')."</td>
							<td class=\"$clGaris\" align=center>". (empty($isi['panjang'])? '-': number_format($isi['panjang'],2,',','.'))."</td>
							<td class=\"$clGaris\" align=center>".(empty($isi['lebar'])?'-':number_format($isi['lebar'],2,',','.'))."</td>
							<td class=\"$clGaris\" align=center>".(empty($isi['luas'])?'-':number_format($isi['luas'],2,',','.'))."</td>
							<td class=\"$clGaris\" >".ifempty($isi['alamat'],'-').$Kel.$Kec.$kota."</td>
							<td class=\"$clGaris\" align=center >".ifemptyTgl(TglInd($isi['dokumen_tgl']),'-')."</td>
							<td class=\"$clGaris\">".ifempty($isi['dokumen_no'],'-')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($Main->StatusTanah[$isi['status_tanah']-1][1],'-')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($isi['kode_tanah'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-')."<br>/".
							ifempty($Main->StatusBarang[$isi['status_barang']-1][1],'-')."</td>
							
							<td class=\"$clGaris\" align=right >".$tampilHarga."</td>
							
							<td class=\"$clGaris\">$kondisi_kk</td>".	
							"<td class=\"$clGaris\"  style='min-width:120'>$status_penguasaan</td>".	
							"<td class=\"$clGaris\" style=''>$ket_kk</td>".	
							$tampilDok.
							$isi['vBidang'].
							$tampilCbxKeranjang.
							"</tr>
							";
					}else{
						$ListData .= "	
							<tr class='$clRow'  valign='top' >
							<td class=\"$clGaris\" align=center>$no</td>
							$tampilCheckbox
							<td class=\"$clGaris\" align=center>{$isi['id_barang']}</td>
							<td class=\"$clGaris\" align=center><div class='nfmt3'>{$isi['noreg']}</div></td>
							<td class=\"$clGaris\" >{$isi['nm_barang']}</td>
							<td class=\"$clGaris\" >{$isi['tahun']}</td>
							<td class=\"$clGaris\" >".ifempty($isi['konstruksi'],'-')."</td>
							<td class=\"$clGaris\" align=center>". (empty($isi['panjang'])? '-': number_format($isi['panjang'],2,',','.'))."</td>
							<td class=\"$clGaris\" align=center>".(empty($isi['lebar'])?'-':number_format($isi['lebar'],2,',','.'))."</td>
							<td class=\"$clGaris\" align=center>".(empty($isi['luas'])?'-':number_format($isi['luas'],2,',','.'))."</td>
							<td class=\"$clGaris\" >".ifempty($isi['alamat'],'-').$Kel.$Kec.$kota."</td>
							<td class=\"$clGaris\" align=center >".ifemptyTgl(TglInd($isi['dokumen_tgl']),'-')."</td>
							<td class=\"$clGaris\">".ifempty($isi['dokumen_no'],'-')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($Main->StatusTanah[$isi['status_tanah']-1][1],'-')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($isi['kode_tanah'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-')."<br>/".
							ifempty($Main->StatusBarang[$isi['status_barang']-1][1],'-')."<br>/".ifempty( $Main->KondisiBarang[$isi['kondisi']-1][1], '-')."</td>
							<td class=\"$clGaris\" >".ifempty($Main->KondisiBarang[$isi['kondisi_bi']-1][1],'-')."</td>
							<td class=\"$clGaris\" align=right >".$tampilHarga."</td>
							
							<td class=\"$clGaris\">".$ISI15."</td>".
							$tampilDok.
							$isi['vBidang'].
							$tampilCbxKeranjang.
							"</tr>
							";
					}
							
					//$det =Penatausahaan_GetListDet($isi['idbi'], '04',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
					$det =Penatausahaan_GetListDet($isi['idawal'], '04',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox,3);			
					$ListData .= $det['ListData'];
					$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];
					$totLuasTanahHal += $isi['luas'];
						//	$cb++;
					//}
					//*/
					break;
				}
				case "08" : case "kib_e_cetak" :{			
					//$Qry = mysql_query($sqry);
					//$jmlDataKIB_E = table_get_value("select count(*) as cnt from view_kib_e where $Kondisi ",'cnt');//mysql_num_rows($Qry);
					//$Qry = mysql_query($sqry." $LimitHalKIB_E");
					//$no=$Main->PagePerHal * (($HalKIB_E*1) - 1);
					//$cb=0;	$jmlTampilKIB_E = 0;	$jmlTotalHargaDisplay = 0;		$ListData = "";
					//while ($isi = mysql_fetch_array($Qry)){
						$idBI = $isi['idbi'];//getIDByKodeBrg('buku_induk', $isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']);// table_get_value( $sidBi, 'id' );
						$jmlTampilKIB_E++;
						$jmlTotalHargaDisplay += $isi['jml_harga'];
						$no++;
					$clRow =""; if($cetak == FALSE){$clRow = $no % 2 == 0 ?"row1":"row0";}
						$ISI15 = ifempty($isi['ket'],'-');
						//$ISI15 .= '<br>'.$isi['nmbidang'].' - '.$isi['nmopd'].' - '.$isi['nmunit'] ;//tampilNmSubUnit($isi);			
						$ISI15 .= tampilNmSubUnit($isi);	
						if ($txls=='1'){
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');
							
						} else {
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');
							
						}						
						if($tipe=='kertaskerja'){			
							//$dok = $isi['dokumen_file']=="" ? "" : dokumenlink($isi['dokumen_file'], $isi['dokumen']);
							//$tampilDok = '';//$cetak? "" : "<td class=\"$clGaris\">$dok</td>"; 
							$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $infobrg $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>";
							$ListData .= "	
							<tr class='$clRow'  valign='top'>
							<td class=\"$clGaris\" align=center>$no</td>
							$tampilCheckbox
							<td class=\"$clGaris\" align=center>{$isi['id_barang']}</td>
							<td class=\"$clGaris\" align=center><div class='nfmt3'>{$isi['noreg']}</div></td>
							<td class=\"$clGaris\" >{$isi['nm_barang']}</td>
							<!--<td class=\"$clGaris\" style=''>{$isi['tahun']}</td>-->
							<td class=\"$clGaris\" >".ifempty($isi['buku_judul'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['buku_spesifikasi'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['seni_asal_daerah'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['seni_pencipta'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['seni_bahan'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['hewan_jenis'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['hewan_ukuran'],'-')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($isi['jml_barang'],'0')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($isi['thn_perolehan'],'-')."</td>
							<td class=\"$clGaris\">".ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-')."<br>/".
							ifempty($Main->StatusBarang[$isi['status_barang']-1][1],'-')."<br>/".ifempty( $Main->KondisiBarang[$isi['kondisi']-1][1], '-')."</td>
							<td class=\"$clGaris\" align=right >".$tampilHarga."</td>
							<td class=\"$clGaris\">$kondisi_kk</td>".									
							"<td class=\"$clGaris\" style=''>$ket_kk</td>".	
							$tampilDok.
							$isi['vBidang'].
							$tampilCbxKeranjang.
							"</tr>
							";
							//$det =Penatausahaan_GetListDet($isi['idbi'], '05',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
							$det =Penatausahaan_GetListDet($isi['idawal'], '05',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
							$ListData .= $det['ListData'];
							$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];
							$totLuasTanahHal += $isi['luas'];
						}else{

							//$dok = $isi['dokumen_file']=="" ? "" : dokumenlink($isi['dokumen_file'], $isi['dokumen']);
							//$tampilDok = '';//$cetak? "" : "<td class=\"$clGaris\">$dok</td>"; 
							$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $infobrg $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>";
							$ListData .= "	
							<tr class='$clRow'  valign='top'>
							<td class=\"$clGaris\" align=center>$no</td>
							$tampilCheckbox
							<td class=\"$clGaris\" align=center>{$isi['id_barang']}</td>
							<td class=\"$clGaris\" align=center><div class='nfmt3'>{$isi['noreg']}</div></td>
							<td class=\"$clGaris\" >{$isi['nm_barang']}</td>
							<!--<td class=\"$clGaris\" style=''>{$isi['tahun']}</td>-->
							<td class=\"$clGaris\" >".ifempty($isi['buku_judul'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['buku_spesifikasi'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['seni_asal_daerah'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['seni_pencipta'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['seni_bahan'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['hewan_jenis'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['hewan_ukuran'],'-')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($isi['jml_barang'],'0')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($isi['thn_perolehan'],'-')."</td>
							<td class=\"$clGaris\">".ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-')."<br>/".
							ifempty($Main->StatusBarang[$isi['status_barang']-1][1],'-')."<br>/".ifempty( $Main->KondisiBarang[$isi['kondisi']-1][1], '-')."</td>
							<td class=\"$clGaris\" align=right >".$tampilHarga."</td>
							<td class=\"$clGaris\">".$ISI15."</td>".
							$tampilDok.
							$isi['vBidang'].
							$tampilCbxKeranjang.
							"</tr>
							";
							//$det =Penatausahaan_GetListDet($isi['idbi'], '05',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
							$det =Penatausahaan_GetListDet($isi['idawal'], '05',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
							$ListData .= $det['ListData'];
							$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];
							$totLuasTanahHal += $isi['luas'];
						}
					//}
					break;
				}
				case "09" : case "kib_f_cetak" :{
					//$Qry = mysql_query($sqry);
					//$jmlDataKIB_F = table_get_value("select count(*) as cnt from view_kib_f where $Kondisi ",'cnt');//mysql_num_rows($Qry);
					//$Qry = mysql_query($sqry." $LimitHalKIB_F");
					//$ISI15 = ifempty($isi['ket'],'-');
					//$ISI15 .= '<br>'.$isi['nmbidang'].' - '.$isi['nmopd'].' - '.$isi['nmunit'] ;
					//$no=$Main->PagePerHal * (($HalKIB_F*1) - 1);
					//$cb=0;			
					//$jmlTampilKIB_F = 0;
					//$jmlTotalHargaDisplay = 0;
					//$ListData = "";
					//while ($isi = mysql_fetch_array($Qry)){
						$idBI = $isi['idbi'];//getIDByKodeBrg('buku_induk', $isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']);
//						$kota =  '<br>'.$isi['alamat_kota'];//'<br>'.getNmWlayah($isi['alamat_a'], $isi['alamat_b']);
//						$Kec = $isi['alamat_kec'];//table_get_value('select alamat_kec from kib_f where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) = "'.$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg'].'"','alamat_kec');
//						$Kel = $isi['alamat_kel'];//table_get_value('select alamat_kel from kib_f where concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg) = "'.$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg'].'"','alamat_kel');
//					if($Kec != ''){ $Kec='<br>Kec. '.$Kec;}
//						if($Kel != ''){ $Kel='<br>Kel. '.$Kel;}	
					$Kec = getalamat($isi['alamat_b'],$isi['alamat_c'],'',$isi['kota'],$isi['alamat_kec'],$isi['alamat_kel']);
					
					$Kel='';
					$kota='';						
						$jmlTampilKIB_F++;
						$jmlTotalHargaDisplay += $isi['jml_harga'];
						$no++;
					$clRow =""; if($cetak == FALSE){$clRow = $no % 2 == 0 ?"row1":"row0";}
						$ISI15 = ifempty($isi['ket'],'-');
						//$ISI15 .='<br>'.$isi['nmbidang'].' - '.$isi['nmopd'].' - '.$isi['nmunit'] ;// tampilNmSubUnit($isi);	
						$ISI15 .= tampilNmSubUnit($isi);	
						if ($txls=='1'){
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');
							
						} else {
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');
							
						}
						//	$dok = $isi['dokumen_file']=="" ? "" : dokumenlink($isi['dokumen_file'], $isi['dokumen']);
						//	$tampilDok = '';//$cetak? "" : "<td class=\"$clGaris\">$dok</td>"; 				
						$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $infobrg $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>";
						$ListData .= "	
						<tr class='$clRow'  valign='top'>
						<td class=\"$clGaris\" align=center>$no</td>
						$tampilCheckbox
						<!--<td class=\"$clGaris\" align=center><input type=\"checkbox\" $infobrg $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>-->
						<td class=\"$clGaris\" align=center>{$isi['id_barang']}</td>
						<td class=\"$clGaris\" align=center><div class='nfmt3'>{$isi['noreg']}</div></td>
						<td class=\"$clGaris\" align=left>{$isi['nm_barang']}</td>
						<td class=\"$clGaris\" >{$isi['tahun']}</td>
						<td class=\"$clGaris\" align=left>".ifempty($Main->Bangunan[$isi['bangunan']-1][1],'-')."</td>
						<td class=\"$clGaris\" align=left>".ifempty($Main->Tingkat[$isi['konstruksi_tingkat']-1][1],'-')."</td>
						<td class=\"$clGaris\" align=left>".ifempty($Main->Beton[$isi['konstruksi_beton']-1][1],'-')."</td>
						<td class=\"$clGaris\" align=center>".(empty($isi['luas'])? '-': number_format($isi['luas'],2,',','.'))."</td>
						<td class=\"$clGaris\" >".ifempty($isi['alamat'],'-').$Kel.$Kec.$kota."</td>
						<td class=\"$clGaris\" align=center>".ifemptyTgl(TglInd($isi['dokumen_tgl']),'-')."</td>
						<td class=\"$clGaris\" >".ifempty($isi['dokumen_no'],'-')."</td>
						<td class=\"$clGaris\" align=center>".ifemptyTgl(TglInd($isi['tmt']),'-')."</td>
						<td class=\"$clGaris\" align=center>".ifempty($Main->StatusTanah[$isi['status_tanah']-1][1],'-')."</td>
						<td class=\"$clGaris\" align=center>".ifempty($isi['kode_tanah'],'-')."</td>
						<td class=\"$clGaris\" >".ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-')."<br>/".			
						ifempty($Main->StatusBarang[$isi['status_barang']-1][1],'-')."<br>/".ifempty( $Main->KondisiBarang[$isi['kondisi']-1][1], '-')."</td>
						<td class=\"$clGaris\" align=right>".$tampilHarga."</td>
						<td class=\"$clGaris\">".$ISI15."</td>
						<!--<td class=\"$clGaris\">$dok</td>-->
						$tampilDok
						$tampilCbxKeranjang
						</tr>";
						
						//$det =Penatausahaan_GetListDet($isi['idbi'], '06',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
						$det =Penatausahaan_GetListDet($isi['idawal'], '06',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
						$ListData .= $det['ListData'];
						$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];
						
						$totLuasTanahHal += $isi['luas'];
					//}
					break;
				}
				case "kibg" : case "kib_g_cetak" :{			
					//$Qry = mysql_query($sqry);
					//$jmlDataKIB_E = table_get_value("select count(*) as cnt from view_kib_e where $Kondisi ",'cnt');//mysql_num_rows($Qry);
					//$Qry = mysql_query($sqry." $LimitHalKIB_E");
					//$no=$Main->PagePerHal * (($HalKIB_E*1) - 1);
					//$cb=0;	$jmlTampilKIB_G = 0;	$jmlTotalHargaDisplay = 0;		$ListData = "";
					//while ($isi = mysql_fetch_array($Qry)){
						$idBI = $isi['idbi'];//getIDByKodeBrg('buku_induk', $isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'].$isi['tahun'].$isi['noreg']);// table_get_value( $sidBi, 'id' );
						$jmlTampilKIB_G++;
						$jmlTotalHargaDisplay += $isi['jml_harga'];
						$no++;
					$clRow =""; if($cetak == FALSE){$clRow = $no % 2 == 0 ?"row1":"row0";}
						$ISI15 = ifempty($isi['ket'],'-');
						//$ISI15 .= '<br>'.$isi['nmbidang'].' - '.$isi['nmopd'].' - '.$isi['nmunit'] ;//tampilNmSubUnit($isi);			
						// $ISI15 .= tampilNmSubUnit($isi);	
						if ($txls=='1'){
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, '.', '') : number_format($isi['jml_harga'], 2, '.', '');
							
						} else {
						$tampilHarga = !empty($cbxDlmRibu)? number_format($isi['jml_harga']/1000, 2, ',', '.') : number_format($isi['jml_harga'], 2, ',', '.');
							
						}						
						if($tipe=='kertaskerja'){			
							//$dok = $isi['dokumen_file']=="" ? "" : dokumenlink($isi['dokumen_file'], $isi['dokumen']);
							//$tampilDok = '';//$cetak? "" : "<td class=\"$clGaris\">$dok</td>"; 
							$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $infobrg $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>";
							$ListData .= "	
							<tr class='$clRow'  valign='top'>
							<td class=\"$clGaris\" align=center>$no</td>
							$tampilCheckbox
							<td class=\"$clGaris\" align=center>{$isi['id_barang']}</td>
							<td class=\"$clGaris\" align=center><div class='nfmt3'>{$isi['noreg']}</div></td>
							<td class=\"$clGaris\" >{$isi['nm_barang']}</td>
							<!--<td class=\"$clGaris\" style=''>{$isi['tahun']}</td>-->
							<td class=\"$clGaris\" >".ifempty($isi['uraian'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['pencipta'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['jenis'],'-')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($isi['jml_barang'],'0')."</td>
							<td class=\"$clGaris\" align=center>".ifempty($isi['thn_perolehan'],'-')."</td>
							<td class=\"$clGaris\">".ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-')."<br>/".
							ifempty($Main->StatusBarang[$isi['status_barang']-1][1],'-')."<br>/".ifempty( $Main->KondisiBarang[$isi['kondisi']-1][1], '-')."</td>
							<td class=\"$clGaris\" align=right >".$tampilHarga."</td>
							<td class=\"$clGaris\">$kondisi_kk</td>".									
							"<td class=\"$clGaris\" style=''>$ket_kk</td>".	
							$tampilDok.
							$isi['vBidang'].
							$tampilCbxKeranjang.
							"</tr>
							";
							//$det =Penatausahaan_GetListDet($isi['idbi'], '05',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
							$det =Penatausahaan_GetListDet($isi['idawal'], '07',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
							$ListData .= $det['ListData'];
							$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];
							$totLuasTanahHal += $isi['luas'];
						}else{

							//$dok = $isi['dokumen_file']=="" ? "" : dokumenlink($isi['dokumen_file'], $isi['dokumen']);
							//$tampilDok = '';//$cetak? "" : "<td class=\"$clGaris\">$dok</td>"; 
							$tampilCheckbox = $cetak ? "":"<td class=\"$clGaris\" align=center><input type=\"checkbox\" $infobrg $Checked  id=\"cb$cb\" name=\"cidBI[]\" value=\"".$idBI."\" onClick=\"isChecked(this.checked);\" /></td>";
							$ListData .= "	
							<tr class='$clRow'  valign='top'>
							<td class=\"$clGaris\" align=center>$no</td>
							$tampilCheckbox
							<td class=\"$clGaris\" align=center>{$isi['id_barang']}</td>
							<td class=\"$clGaris\" align=center><div class='nfmt3'>{$isi['noreg']}</div></td>
							<td class=\"$clGaris\" >{$isi['nm_barang']}</td>
							<td class=\"$clGaris\" style=''>{$isi['tahun']}</td>
							<td class=\"$clGaris\" >{$isi['uraian']}</td>
							<td class=\"$clGaris\" >".ifempty($isi['pencipta'],'-')."</td>
							<td class=\"$clGaris\" >".ifempty($isi['jenis'],'-')."</td>
							<!--<td class=\"$clGaris\" >".ifempty($isi['kerjasama_nama'],'-')."</td>-->
							<td class=\"$clGaris\" align=center>".ifempty($isi['jml_barang'],'0')."</td>
							<td class=\"$clGaris\">".ifempty($Main->AsalUsul[$isi['asal_usul']-1][1],'-')."<br>/".
							ifempty($Main->StatusBarang[$isi['status_barang']-1][1],'-')."<br>/".ifempty( $Main->KondisiBarang[$isi['kondisi']-1][1], '-')."</td>
							<td class=\"$clGaris\" align=right >".$tampilHarga."</td>
							<td class=\"$clGaris\">".$ISI15."</td>".
							$tampilDok.
							$isi['vBidang'].
							$tampilCbxKeranjang.
							"</tr>
							";
							//$det =Penatausahaan_GetListDet($isi['idbi'], '05',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
							$det =Penatausahaan_GetListDet($isi['idawal'], '07',$isi['jml_harga'], $cetak, $cbxDlmRibu,$clRow, $clGaris, $tampilCheckbox);			
							$ListData .= $det['ListData'];
							$jmlTotalHargaDisplay += $det['jmlTotalHargaDisplay'];
							$totLuasTanahHal += $isi['luas'];
						}
					//}
					break;
				}
				
			}
			
			if( ($cetak ) && ($cb % $MaxFlush==0) && $cb >0 ){				
				echo $ListData;
				ob_flush();
				flush();
				$ListData='';	//sleep(2); //tes
			}
			
			$cb++;
			
			
		}	
		//--- TOTAL ----------------------------------------
		$noSumhal = $_REQUEST['noSumhal'];
		if ($noSumhal=='1')
		{
			
		} else {
			
		$img = "<img src='images/wait.gif' height='16'>"; //loading tampil total
		// $jmlTotalHarga=0;		
		switch($SPg){
			case '04':case "kib_a_cetak" :{
				if($tipe=='kertaskerja'){ 
					$ListData .= $this->tampilTotalA_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 13, 4, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);				
				}else{
					$ListData .= $this->tampilTotalA_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 13, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
					
				}
				break;
			}
			case '05':case "kib_b_cetak" :{
				if($tipe=='kertaskerja'){ 
					$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 15, 4, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}else{
					$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 15, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}
				break;
			}
			case '06':case "kib_c_cetak" :{
				if($tipe=='kertaskerja'){ 
					$ListData .= $this->tampilTotalC_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 16, 4, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}else{
					$ListData .= $this->tampilTotalC_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 16, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}
				break;
			}
			case '07':case "kib_d_cetak" :{
				if($tipe=='kertaskerja'){ 
					$ListData .= $this->tampilTotalD_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 16, 4, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah, 6);
				}else{
					$ListData .= $this->tampilTotalD_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 17, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}
				break;
			}
			case '08':case "kib_e_cetak" :{
				$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 15, 3, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				break;
			}
			case '09':case "kib_f_cetak" :{
				$ListData .= $this->tampilTotalF_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 17, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				break;
			}
			case 'kibg':case "kib_g_cetak" :{
				$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu,11, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				break;
			}

			case 'belumsensus': {
				
				if($tipe=='kertaskerja'){
					
				}else{
					$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 13, 2, $clGaris, '', $img);	
				}
				
				break;
			}
			default: $ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 13, 2, $clGaris, '', $img);
		}
		
		}			
		$cek='';//
		
		$yesSum = $_REQUEST['yesSum'];
		if ($yesSum=='1')
		{
		$Qrysum = mysql_query($sqrysum);
		$cb=0;  $totLuasTanahHal = 0;
		$jmlTotalHargaDisplay = 0;
		 // $ListData = "";
		
		while($isisum=mysql_fetch_array($Qrysum)){
		
		if ($no==$isisum['jml']){
		$jmlTotalHarga=0;	

		$jmlTotalHargaDisplay =  !empty($cbxDlmRibu)? number_format($isisum['jml_harga']/1000, 2, '.', '') : number_format($isisum['jml_harga'], 2, '.', '');
		switch($SPg){
			case '04':case "kib_a_cetak" :{
				if($tipe=='kertaskerja'){ 
					$ListData .= $this->tampilTotalA_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 13, 4, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);				
				}else{
					$ListData .= $this->tampilTotalA_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 13, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
					
				}
				break;
			}
			case '05':case "kib_b_cetak" :{
				if($tipe=='kertaskerja'){ 
					$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 15, 4, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}else{
					$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 15, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}
				break;
			}
			case '06':case "kib_c_cetak" :{
				if($tipe=='kertaskerja'){ 
					$ListData .= $this->tampilTotalC_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 16, 4, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}else{
					$ListData .= $this->tampilTotalC_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 16, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}
				break;
			}
			case '07':case "kib_d_cetak" :{
				if($tipe=='kertaskerja'){ 
					$ListData .= $this->tampilTotalD_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 16, 4, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah, 6);
				}else{
					$ListData .= $this->tampilTotalD_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 17, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				}
				break;
			}
			case '08':case "kib_e_cetak" :{
				$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 15, 3, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				break;
			}
			case '09':case "kib_f_cetak" :{
				$ListData .= $this->tampilTotalF_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 17, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				break;
			}
			case 'kibg':case "kib_g_cetak" :{
				$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 11, 1, $clGaris, '', $img,$totLuasTanahHal, $totLuasTanah);
				break;
			}

			case 'belumsensus': {
				
				if($tipe=='kertaskerja'){
					
				}else{
					$ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 13, 2, $clGaris, '', $img);	
				}
				
				break;
			}
			default: $ListData .= $this->tampilTotal_($jmlTotalHargaDisplay, $jmlTotalHarga, $cetak, $cbxDlmRibu, 13, 2, $clGaris, '', $img);
		}
		}
		}		
		
		}
			
		
		
		return $ListData.$cek;
		//header -------------------------------
		//$headerTable
		
		
	}
		
	function genSumHal($SPg, $Kondisi){
		global $Main ; 
		$cek ='';
		//$HalDefault = $_POST['HalDefault'];		
		$cbxDlmRibu = $_POST["cbxDlmRibu"];
		
		$tblNameList = $this->getTableName($SPg);	
		
		
		$aqry="select sum(( coalesce(jml_harga,0)+coalesce(tot_pelihara,0) + coalesce(tot_pengaman,0)- coalesce(tot_hapussebagian,0) )) as total, count(*) as cnt from ".$tblNameList."_total Where $Kondisi ";
		//$cek .= $aqry;
		//$aqry="select sum(jml_harga) as total, count(*) as cnt from ".$tblNameList."_total Where $Kondisi ";
		$Sum = mysql_query($aqry);				
		$jmlTotalHarga=0;
		$jmlData=0;
		if($isi = mysql_fetch_array($Sum)){
			$jmlTotalHarga +=$isi['total'];
			$jmlData = $isi['cnt'];
		}
		
		$jmlTotalHarga = $cbxDlmRibu==''? '<b>'.number_format($isi['total'],2, ',', '.') : '<b>'.number_format($isi['total']/1000,2, ',', '.');		
		
		
		
		/*
		//halaman ------------------------------
		//echo ",spg=$SPg";
		$hal = Halaman($jmlData,$Main->PagePerHal,	"HalDefault",'',5,'Penatausaha.gotoHalaman');
		
		
		$listHalaman = "<tr><td align=center><div>".$hal."</div></td></tr>";
		
		
		$listHalaman = 
		//$aqry.
		"<table class='koptable' width='100%' border='1' style='margin:4 0 0 0'>".
		$listHalaman.
		"</table>";
		*/
		$listHalaman =
			//"<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>
			"<table class='koptable' border='1' width='100%' style='margin:4 0 0 0'>			
			<tr><td align=center style='padding:4'>".
				Halaman2b(
					$jmlData,
					$Main->PagePerHal,
					"HalDefault",
					cekPOST('HalDefault'),
					5, 
					'Penatausaha.gotoHalaman').
			" </td></tr></table>";
		
		
		//-- luas tanah u/ kib a,c,d,f
		$luas_tanah = 0;
		if($SPg=='04'||$SPg=='06'||$SPg=='07'||$SPg=='09'){
			
			$aqry = "select sum(luas)as totluas from $tblNameList Where $Kondisi";
			$get = mysql_fetch_array(mysql_query($aqry));
			$luas_tanah = $get['totluas'];
		
		}
		$vluas_tanah = '<b>'.number_format($luas_tanah,2,',','.');
		$vjmlData = '<b>'.number_format($jmlData,0,',','.');
		return array('luas_tanah'=>$luas_tanah,'vluas_tanah'=>$vluas_tanah, 'sum'=>$jmlTotalHarga, 'jmlData'=>$jmlData,  'vjmlData'=>$vjmlData, 'hal'=>$listHalaman, 'cek'=>$cek);
	}
	
		
	function genHeader($SPg='', $cetak=FALSE,$tipe=''){
			global $Main;
			
			$cbxDlmRibu = $_POST['cbxDlmRibu'];
			
			$tampilHeaderHarga = !empty($cbxDlmRibu)? 'Harga (Ribuan)': 'Harga';	
			$tampilDok ='';// $cetak? "" : "<th class=\"th01\" rowspan='2' width='24'></th>";
			$clGaris = $cetak? 'GarisCetak':'GarisDaftar';			
			if($this->tampilBidang){
				$vBidang =  
					"<th class='th01' rowspan=2>".
					"Bidang / SKPD / Unit / Sub Unit".
					"</th>";
				$vBidangA =  
					"<th class='th01' rowspan=3>".
					"Bidang / SKPD / Unit / Sub Unit".
					"</th>";
			}								
			//echo ",spg2=$SPg";
			//$tipe=$_REQUEST['tipe'];
			switch($SPg){
				case "03" : case "listbi_cetak" : case 'belumsensus' : case 'KIR': case 'KIP': {
					
					$tampilCheckbox =  $cetak ? "" : "	<th class='th01' ><input type='checkbox' name='toggle' id='toggle' value='' onClick=\"checkAll(".$Main->PagePerHal.");".
					"Penatausaha.checkAll($Main->PagePerHal,'cb','toggle','boxchecked')".
					"\" ></th>";			
					$cp = $cetak? 14:15;
					for ($i=0;$i<$cp;$i++){
						$tambahgaris .= "<th class='$clGaris' style='padding:0'></th>";
					}
					$tambahgaris .= '</tr>';
					
					if($tipe=='kertaskerja'){ //sensus.belumcek.kertaskerja
						$headerTable =
							"<tr>
							<th class=\"th01\" rowspan='2'>No.</th>		
							<th class=\"th01\" rowspan='2'>Kode Lokasi/<br> Kode Barang/<br> Nama Barang</th>						
							<th class=\"th02\" colspan='2'>Spesifikasi Barang</th>
							
							<th class=\"th01\" rowspan='2' width='60'>Tahun /<br> Harga Perolehan</th>
							<th class=\"th01\" rowspan='2' width='40'>Ada/<br>Tidak</th>
							
							<th class=\"th01\" rowspan=2 width='70'>  Kondisi Barang<br>(B/KB/RB) </th>
							<th class=\"th01\" rowspan='2' width='100'>Gedung/<br>Ruang/<br>Lokasi </th>
							<th class=\"th01\" rowspan='2' width='150'>Penanggung Jawab Barang</th>
							<th class=\"th01\" rowspan='2' width='150'>Status Penguasaan</th>
							<th class=\"th01\" rowspan='2' width='100'>Catatan</th>							
							<th class=\"th01\" rowspan='2' width='100'>Tgl. Cek/<br>Petugas Sensus</th>
							
							</tr>
							<tr>
							
							<th class=\"th01\"  width=\"100\">Merk / Tipe/ Alamat</th>
							<th class=\"th01\" width='100'>No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
							<!--<th class=\"th01\" width='70'>Barang</th>
							<th class=\"th01\"> $tampilHeaderHarga </th>-->
							
							</tr>";
							//$tambahgaris";
					}else{
						$kolomMerk = 'Merk / Tipe';
						if($SPg=='belumsensus') $kolomMerk = 'Merk / Tipe / Alamat';
						$headerTable =
							"<tr>
							<th class=\"th02\" colspan='". ($cetak? "3": "4") ."'>Nomor</th>
							<th class=\"th02\" colspan='3'>Spesifikasi Barang</th>
							<th class=\"th01\" rowspan='2'>Bahan</th>
							<th class=\"th01\" rowspan='2'>Cara Perolehan / Status Barang</th>
							<th class=\"th01\" rowspan='2'>Tahun Perolehan</th>
							<th class=\"th01\" rowspan='2'>Ukuran Barang / Konstruksi (P,SP,D)</th>
							<th class=\"th01\" rowspan='2'>Keadaan Barang (B,KB,RB)</th>
							<th class=\"th02\" colspan='2'>Jumlah</th>
							<th class=\"th01\" rowspan='2' style='min-width:100;'>Keterangan/<br>Tgl. Buku/<br>Tahun Sensus</th>
							$tampilDok
							$vBidang
							$tampilCbxKeranjangHead
							</tr>
							<tr>
							<th class=\"th01\">No.</th>				
							$tampilCheckbox
							<th class=\"th01\">Kode <br>Barang</th>
							<th class=\"th01\">Reg.</th>
							<th class=\"th01\"  width=\"100\">Nama / Jenis Barang</th>
							<th class=\"th01\"  width=\"100\">$kolomMerk</th>
							<th class=\"th01\">No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>
							<th class=\"th01\" width='70'>Barang</th>
							<th class=\"th01\"> $tampilHeaderHarga </th>
							
							</tr>
							$tambahgaris";
					}
					break;
				} 
				case "04" : case "kib_a_cetak" :{
					//$tampilDok = '';//$cetak? "" : "<th class=\"th01\" rowspan='3' width='24'></th>";
					//$tampilCheckbox =  $cetak ? "" : "<th class=\"th01\" rowspan=\"3\"><input type=\"checkbox\" name=\"toggle\" value=\"\" onClick=\"checkAll($jmlDataKIB_A);\"</th>";
					$tampilCheckbox =  $cetak ? "" : "<th class='th01' rowspan='2'><input type='checkbox' name='toggle' value='' onClick='checkAll(".$Main->PagePerHal.")'></th>";
					$width_alm = $cetak ? 'min-width:150;' :'min-width:150;width:250;';
					$width_ket = $cetak ? '' :'min-width:100;';
					$cp = $cetak? 14:15;
					for ($i=0;$i<$cp;$i++){
						$tambahgaris .= "<th class='$clGaris' style='padding:0'></th>";
					}
					$tambahgaris .= '</tr>';
					if($tipe=='kertaskerja'){
						$headerTable=
							"<tr>
							<th class=\"th01\"  rowspan=\"3\">No.</th>
							$tampilCheckbox
							<th class=\"th02\" colspan=\"2\">N o m o r</th>
							<th class=\"th01\" rowspan=\"3\" style=''>Nama Barang</th>
							<th class=\"th01\" rowspan=\"3\">Luas (M2)</th>
							<th class=\"th01\" rowspan=\"3\" style='width:50'>Tahun Peroleh an</th>
							<th class=\"th01\" rowspan=\"3\" style='$width_alm'>Letak / Alamat</th>
							<th class=\"th02\" colspan=\"3\">Status Tanah</th>
							<th class=\"th01\" rowspan=\"3\">Penggunaan</th>
							<th class=\"th01\" rowspan=\"3\" style='width:50'>Cara Perolehan / Status Barang / Kondisi</th>
							<th class=\"th01\" rowspan=\"3\" style=''>$tampilHeaderHarga</th>
							<th class=\"th01\" rowspan=\"3\" style='width:130px'>Status Penguasaan</th>
							<th class=\"th01\" rowspan=\"3\" style=''>DT/TD</th>
							<th class=\"th01\" rowspan=\"3\" style=''>Keterangan</th>
							$tampilDok	
							$vBidangA
							$tampilCbxKeranjangHeadA						
							</tr>
							<tr class=\"koptable\">
							<th class=\"th01\" rowspan=\"2\" style='width:70'>Kode Barang</th>
							<th class=\"th01\" rowspan=\"2\" style='width:25'>Reg</th>
							<th class=\"th01\" rowspan=\"2\">Hak</th>
							<th class=\"th02\" colspan=\"2\">Sertifikat</th>
							</tr>
							<tr>
							<th class=\"th01\" style=''>Tanggal</th>
							<th class=\"th01\" style=''>Nomor</th>
							</tr>
							
							$tambahgaris
							";
					}else{
						
					
						$headerTable=
							"<tr>
							<th class=\"th02\" colspan=\"". ($cetak? "3": "4") ."\">N o m o r</th>
							<th class=\"th01\" rowspan=\"3\" style=''>Nama Barang</th>
							<th class=\"th01\" rowspan=\"3\">Luas (M2)</th>
							<th class=\"th01\" rowspan=\"3\" style='width:50'>Tahun Peroleh an</th>
							<th class=\"th01\" rowspan=\"3\" style='$width_alm'>Letak / Alamat</th>
							<th class=\"th02\" colspan=\"3\">Status Tanah</th>
							<th class=\"th01\" rowspan=\"3\">Penggunaan</th>
							<th class=\"th01\" rowspan=\"3\" style='width:50'>Cara&nbsp;Perolehan&nbsp;/ Status&nbsp;Barang&nbsp;/ Kondisi</th>
							<th class=\"th01\" rowspan=\"3\" style=''>$tampilHeaderHarga</th>
							<th class=\"th01\" rowspan=\"3\" style='$width_ket'>Keterangan</th>
							$tampilDok	
							$vBidangA
							$tampilCbxKeranjangHeadA						
							</tr>
							<tr class=\"koptable\">
							<th class=\"th01\" rowspan=\"2\"  >No.</th>
							$tampilCheckbox
							<th class=\"th01\" rowspan=\"2\" style='width:70'>Kode Barang</th>
							<th class=\"th01\" rowspan=\"2\" style='width:25'>Reg.</th>
							<th class=\"th01\" rowspan=\"2\">Hak</th>
							<th class=\"th02\" colspan=\"2\">Sertifikat</th>
							</tr>
							<tr>
							<th class=\"th01\" style=''>Tanggal</th>
							<th class=\"th01\" style=''>Nomor</th>
							</tr>
							
							$tambahgaris
							";
					}
					break;
				}
				case "05" : case "kib_b_cetak" :{
					$tampilCheckbox =  $cetak ? "" : "<th class='th01' rowspan=''><input type='checkbox' name='toggle' value='' onClick='checkAll(".$Main->PagePerHal.")'></th>";
					$cp = $cetak? 16:17;
					for ($i=0;$i<$cp;$i++){
						$tambahgaris .= "<th class='$clGaris' style='padding:0'></th>";
					}
					$tambahgaris .= '</tr>';
					
					if($tipe=='kertaskerja'){
						$headerTable=
							"<tr>
							<th class=\"th01\" rowspan=\"2\">No.</th>
							$tampilCheckbox
							<th class=\"th01\" rowspan=\"2\" style='width:40'>Kode Barang</th>
							<th class=\"th01\" rowspan=\"2\" style='width:50'>Nomor Reg</th>
							<th class=\"th01\" rowspan=\"2\" style='min-width:100'>Nama Barang</th>
							<th class=\"th01\" rowspan=\"2\" style='min-width:50'>Merk/Type</th>
							<th class=\"th01\" rowspan=\"2\">Ukuran<br>/CC</th>
							<th class=\"th01\" rowspan=\"2\"  style='width:50'>Bahan</th>
							<th class=\"th01\" rowspan=\"2\" style='width:50'>Tahun Perolehan</th>
							<th class=\"th02\" colspan=\"5\">N o m o r</th>
							<th class=\"th01\" rowspan=\"2\" width='62'>Cara Perolehan / Status Barang</th>
							<th class=\"th01\" rowspan=\"2\" style='width:80'>$tampilHeaderHarga</th>							
							<th class=\"th01\" rowspan=\"2\" style=''>Kondisi</th>
							<th class=\"th01\" rowspan=\"2\"  style='min-width:150'>Nama/No Ruang</th>
							<th class=\"th01\" rowspan=\"2\" style=''>Keterangan</th>
							$tampilDok
							$vBidang
							$tampilCbxKeranjangHead	
							</tr>
							<tr>
							<th class=\"th01\">Pabrik</th>
							<th class=\"th01\">Rangka</th>
							<th class=\"th01\">Mesin</th>
							<th class=\"th01\">Polisi</th>
							<th class=\"th01\">BPKB</th>
							</tr>
							$tambahgaris
							";
					}else{
						$headerTable=
							"<tr>
							<th class=\"th02\" colspan=\"". ($cetak? "3": "4") ."\">N o m o r</th>
							
							<th class=\"th01\" rowspan=\"2\" style='min-width:100'>Nama Barang</th>
							<th class=\"th01\" rowspan=\"2\" style='width:200'>Merk/Type</th>
							<th class=\"th01\" rowspan=\"2\">Ukuran/CC</th>
							<th class=\"th01\" rowspan=\"2\">Bahan</th>
							<th class=\"th01\" rowspan=\"2\" style='width:50'>Tahun Perolehan</th>
							<th class=\"th02\" colspan=\"5\">N o m o r</th>
							<th class=\"th01\" rowspan=\"2\" width='62'>Cara&nbsp;Perolehan&nbsp;/ Status&nbsp;Barang&nbsp;/ Kondisi</th>
							<th class=\"th01\" rowspan=\"2\" style='width:80'>$tampilHeaderHarga</th>
							<th class=\"th01\" rowspan=\"2\" style=''>Keterangan</th>
							$tampilDok
							$vBidang
							$tampilCbxKeranjangHead	
							</tr>
							<tr>
							<th class=\"th01\" >No.</th>
							$tampilCheckbox
							<th class=\"th01\" style='width:40'>Kode Barang</th>
							<th class=\"th01\" style='width:50'>Reg.</th>
							<th class=\"th01\">Pabrik</th>
							<th class=\"th01\">Rangka</th>
							<th class=\"th01\">Mesin</th>
							<th class=\"th01\">Polisi</th>
							<th class=\"th01\">BPKB</th>
							</tr>
							$tambahgaris
							";
						
					}
					
					break;
				}
				case "06" : case "kib_c_cetak" :{			
					$tampilCheckbox = $cetak ? "" : "<th class='th01' rowspan=''><input type='checkbox' name='toggle' value='' onClick='checkAll(".$Main->PagePerHal.")'></th>";
					$width_alm = $cetak ? 'min-width:120;' :'min-width:150;';
					$width_ket = $cetak ? 'min-width:180;' :'min-width:100;';
					$tambahgaris = '<tr>';
					$cp = $cetak? 17:18;
					for ($i=0;$i<$cp;$i++){
						$tambahgaris .= "<th class='$clGaris' style='padding:0'></th> ";
					}
					$tambahgaris .= '</tr>';
					if($tipe=='kertaskerja'){
						$headerTable=
						"<tr>
						<th class=\"th01\" rowspan=\"2\">No.</th>
						$tampilCheckbox
						<th class=\"th01\" rowspan=\"2\" style='width:40'>Kode Barang</th>
						<th class=\"th01\" rowspan=\"2\" style='width:35'>Nomor Reg</th>
						<th class=\"th01\" rowspan=\"2\" style=''>Nama Barang</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Tahun Peroleh an</th>				
						<th class=\"th01\" rowspan=\"2\">Kondisi Bangunan (B, KB, RB)</th>
						<th class=\"th02\" colspan=\"2\">Konstruksi Bangunan</th>
						<th class=\"th01\" rowspan=\"2\">Luas Lantai (M2)</th>
						<th class=\"th01\" rowspan=\"2\" style='$width_alm'>Letak / Alamat</th>
						<th class=\"th02\" colspan=\"1\">Dokumen Gedung</th>
						<th class=\"th01\" rowspan=\"2\">Luas Tanah (M2)</th>
						<th class=\"th01\" rowspan=\"2\">Status Tanah</th>
						<th class=\"th01\" rowspan=\"2\">Nomor Kode Tanah</th>
						<th class=\"th01\" rowspan=\"2\" style='width:62'>Cara <br>Perolehan / Status Barang </th>
						<th class=\"th01\" rowspan=\"2\">$tampilHeaderHarga</th>
						<th class=\"th01\" rowspan=\"2\" style=''>Kondisi</th>
						<th class=\"th01\" rowspan=\"2\" style='width:130'>Status Penguasaan</th>
						<th class=\"th01\" rowspan=\"2\" style=''>Ket</th>
						$tampilDok
						$vBidang
						$tampilCbxKeranjangHead	
						</tr>
						<tr>
						<th class=\"th01\">Bertingkat/ Tidak</th>
						<th class=\"th01\">Beton/ Tidak</th>
						<!--<th class=\"th01\" style='width:50'>Tanggal</th>-->
						<th class=\"th01\">Tgl/Nomor</th>				
						</tr>".					
						$tambahgaris
						
						
						;
					}else{
						$headerTable=
						"<tr>
						<th class=\"th02\" colspan=\"". ($cetak? "3": "4") ."\">N o m o r</th>
						<th class=\"th01\" rowspan=\"2\" style=''>Nama Barang</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Tahun Peroleh an</th>				
						<th class=\"th01\" rowspan=\"2\">Kondisi Bangunan (B, KB, RB)</th>
						<th class=\"th02\" colspan=\"2\">Konstruksi Bangunan</th>
						<th class=\"th01\" rowspan=\"2\">Luas Lantai (M2)</th>
						<th class=\"th01\" rowspan=\"2\" style='$width_alm'>Letak / Alamat</th>
						<th class=\"th02\" colspan=\"1\">Dokumen Gedung</th>
						<th class=\"th01\" rowspan=\"2\">Luas Tanah (M2)</th>
						<th class=\"th01\" rowspan=\"2\">Status Tanah</th>
						<th class=\"th01\" rowspan=\"2\">Nomor Kode Tanah</th>
						<th class=\"th01\" rowspan=\"2\" style='width:62'>Cara Perolehan&nbsp;/ Status&nbsp;Barang&nbsp;/<br> Kondisi</th>
						<th class=\"th01\" rowspan=\"2\">$tampilHeaderHarga</th>
						<th class=\"th01\" rowspan=\"2\" style='$width_ket'>Ket</th>
						$tampilDok
						$vBidang
						$tampilCbxKeranjangHead	
						</tr>
						<tr>
						<th class=\"th01\" >No.</th>
						$tampilCheckbox
						<th class=\"th01\" style='width:40'>Kode Barang</th>
						<th class=\"th01\" style='width:35'>Reg.</th>
						
						<th class=\"th01\">Bertingkat/ Tidak</th>
						<th class=\"th01\">Beton/ Tidak</th>
						<!--<th class=\"th01\" style='width:50'>Tanggal</th>-->
						<th class=\"th01\">Tgl/Nomor</th>				
						</tr>".					
						$tambahgaris
						;
					}
					break;
				}
				case "07" : case "kib_d_cetak" :{			
					$tampilCheckbox = $cetak ? "" : "<th class='th01' rowspan=''><input type='checkbox' name='toggle' value='' onClick='checkAll(".$Main->PagePerHal.")'></th>";
					$width_alm = $cetak ? '' :'min-width:150;';
					$width_ket = $cetak ? '' :'min-width:100;';
					$cp = $cetak? 18:19;
					for ($i=0;$i<$cp;$i++){
						$tambahgaris .= "<th class='$clGaris' style='padding:0'></th>";
					}
					$tambahgaris .= '</tr>';
					
					if($tipe=='kertaskerja'){
						$headerTable="
						<tr>
						<th class=\"th01\" rowspan=\"2\" >No.</th>
						$tampilCheckbox
						<th class=\"th01\" rowspan=\"2\" style='width:60'>Kode Barang</th>
						<th class=\"th01\" rowspan=\"2\" style='width:40'>Nomor Reg</th>
						<th class=\"th01\" rowspan=\"2\" style='width:200'>Nama Barang</th>
						<th class=\"th01\" rowspan=\"2\" style=''>Tahun Perolehan</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Konstruksi</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Panjang (km)</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Lebar (M)</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Luas  (M2)</th>
						<th class=\"th01\" rowspan=\"2\" style='$width_alm'>Letak / Alamat</th>
						<th class=\"th02\" colspan=\"2\">Dokumen</th>
						<th class=\"th01\" rowspan=\"2\">Status Tanah</th>
						<th class=\"th01\" rowspan=\"2\">Nomor Kode Tanah</th>
						<th class=\"th01\" rowspan=\"2\">Cara Perolehan / Status Barang</th>
						
						<th class=\"th01\" rowspan=\"2\" style='width:75'>$tampilHeaderHarga</th>				
						<th class=\"th01\" rowspan=\"2\" style='width:40'>Kondisi</th>
						<th class=\"th01\" rowspan=\"2\" style='width:130'>Status Penguasaan</th>
						<th class=\"th01\" rowspan=\"2\" style='width:90'>Ket</th>
						$tampilDok
						$vBidang
						$tampilCbxKeranjangHead	
						</tr>
						<tr class=\"koptabel\">
						<th class=\"th01\" style='width:70'>Tanggal</th>
						<th class=\"th01\">Nomor</th>				
						</tr>
						$tambahgaris";
					}else{
						$headerTable="
						<tr>
						<th class=\"th02\" colspan=\"". ($cetak? "3": "4") ."\">N o m o r</th>
						<th class=\"th01\" rowspan=\"2\" style='width:200'>Nama Barang</th>
						<th class=\"th01\" rowspan=\"2\" style=''>Tahun Perolehan</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Konstruksi</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Panjang (km)</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Lebar (M)</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Luas  (M2)</th>
						<th class=\"th01\" rowspan=\"2\" style='$width_alm'>Letak / Alamat</th>
						<th class=\"th02\" colspan=\"2\">Dokumen</th>
						<th class=\"th01\" rowspan=\"2\">Status Tanah</th>
						<th class=\"th01\" rowspan=\"2\">Nomor Kode Tanah</th>
						<th class=\"th01\" rowspan=\"2\">Cara&nbsp;Perolehan&nbsp;/ Status&nbsp;Barang&nbsp;/<br> Kondisi</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Kondisi (B,KB,RB)</th>
						<th class=\"th01\" rowspan=\"2\" style='width:75'>$tampilHeaderHarga</th>				
						<th class=\"th01\" rowspan=\"2\" style='$width_ket'>Ket</th>
						$tampilDok
						$vBidang
						$tampilCbxKeranjangHead	
						</tr>
						<tr class=\"koptabel\">
						<th class=\"th01\" rowspan=\"\" >No.</th>
						$tampilCheckbox
						<th class=\"th01\" rowspan=\"\" style='width:60'>Kode Barang</th>
						<th class=\"th01\" rowspan=\"\" style='width:40'>Reg.</th>
						<th class=\"th01\" style='width:70'>Tanggal</th>
						<th class=\"th01\">Nomor</th>				
						</tr>
						$tambahgaris";
					}
					break;
				}
				case "08" : case "kib_e_cetak" :{					
					$tampilCheckbox =  $cetak ? "" : "<th class='th01' rowspan=''><input type='checkbox' name='toggle' value='' onClick='checkAll(".$Main->PagePerHal.")'></th>";
					$cp = $cetak? 16:17;
					for ($i=0;$i<$cp;$i++){
						$tambahgaris .= "<th class='$clGaris' style='padding:0'></th>";
					}
					$tambahgaris .= '</tr>';
					
					if($tipe=='kertaskerja'){
					
						$headerTable="
						<tr>
						<th class=\"th01\" rowspan=\"2\">No.</th>				
						$tampilCheckbox
						<th class=\"th01\" rowspan=\"2\" style='width:60'>Kode Barang</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Nomor Reg</th>
						<th class=\"th01\" rowspan=\"2\" style='width:150'>Nama Barang</th>
						<!--<th class=\"th01\" rowspan=\"2\" style=''>Tahun Perolehan</th>-->
						<th class=\"th02\" colspan=\"2\">Buku Perpustakaan</th>
						<th class=\"th02\" colspan=\"3\">Barang Bercorak Kesenian / Kebudayaan</th>
						<th class=\"th02\" colspan=\"2\">Hewan Ternak <br> Dan Tumbuhan</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Jumlah</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Tahun Cetak / Beli</th>
						<th class=\"th01\" rowspan=\"2\">Cara Perolehan / Status Barang</th>
						<th class=\"th01\" rowspan=\"2\" style='width:70'>$tampilHeaderHarga</th>
						<th class=\"th01\" rowspan=\"2\" style=''>Kondisi</th>
						<th class=\"th01\" rowspan=\"2\" style='min-width:100;'>Ket.</th>
						$tampilDok
						$vBidang
						$tampilCbxKeranjangHead	
						</tr>
						<tr>
						<th class=\"th01\" style='width:100'>Judul / Pencipta</th>
						<th class=\"th01\" style='width:50'>Spesifikasi</th>
						<th class=\"th01\" style='width:50'>Asal Daerah</th>
						<th class=\"th01\" style='width:50'>Pencipta</th>
						<th class=\"th01\" style='width:50'>Bahan</th>
						<th class=\"th01\" style='width:50'>Jenis</th>
						<th class=\"th01\" style='width:50'>Ukuran</th>
						</tr>
						$tambahgaris";
					}else{
						$headerTable="
						<tr>
						<th class=\"th02\" colspan=\"". ($cetak? "3": "4") ."\">N o m o r</th>
						
						<th class=\"th01\" rowspan=\"2\" style='width:150'>Nama Barang</th>
						<!--<th class=\"th01\" rowspan=\"2\" style=''>Tahun Perolehan</th>-->
						<th class=\"th02\" colspan=\"2\">Buku Perpustakaan</th>
						<th class=\"th02\" colspan=\"3\">Barang Bercorak Kesenian / Kebudayaan</th>
						<th class=\"th02\" colspan=\"2\">Hewan Ternak</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Jumlah</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Tahun Cetak / Beli</th>
						<th class=\"th01\" rowspan=\"2\">Cara&nbsp;Perolehan&nbsp;/ Status&nbsp;Barang&nbsp;/<br> Kondisi</th>
						<th class=\"th01\" rowspan=\"2\" style='width:70'>$tampilHeaderHarga</th>
						<th class=\"th01\" rowspan=\"2\" style='min-width:150;'>Ket.</th>
						$tampilDok
						$vBidang
						$tampilCbxKeranjangHead	
						</tr>
						<tr>
						<th class=\"th01\" rowspan=\"\">No.</th>				
						$tampilCheckbox
						<th class=\"th01\" rowspan=\"\" style='width:60'>Kode Barang</th>
						<th class=\"th01\" rowspan=\"\" style='width:50'>Reg.</th>
						
						<th class=\"th01\" style='width:100'>Judul / Pencipta</th>
						<th class=\"th01\" style='width:50'>Spesifikasi</th>
						<th class=\"th01\" style='width:50'>Asal Daerah</th>
						<th class=\"th01\" style='width:50'>Pencipta</th>
						<th class=\"th01\" style='width:50'>Bahan</th>
						<th class=\"th01\" style='width:50'>Jenis</th>
						<th class=\"th01\" style='width:50'>Ukuran</th>
						</tr>
						$tambahgaris";
					}
					break;
				}
				case "09" : case "kib_f_cetak" :{
					$tampilCheckbox =  $cetak ? "" : "<th class='th01' rowspan=''><input type='checkbox' name='toggle' value='' onClick='checkAll(".$Main->PagePerHal.")'></th>";
					$width_alm = $cetak ? '' :'min-width:150;';
					$width_ket = $cetak ? '' :'min-width:100;';
					$cp = $cetak? 18:19;
					for ($i=0;$i<$cp;$i++){
						$tambahgaris .= "<th class='$clGaris' style='padding:0'></th>";
					}
					$tambahgaris .= '</tr>';
					$headerTable="
					<tr>
					<th class=\"th02\" colspan=\"". ($cetak? "3": "4") ."\">N o m o r</th>
					
					<th class=\"th01\" rowspan=\"2\" style='width:200'>Nama Barang</th>
					<th class=\"th01\" rowspan=\"2\" style=''>Tahun Perolehan</th>
					<th class=\"th01\" rowspan=\"2\" style='width:50'>Bangunan(P,SP,D)</th>
					<th class=\"th02\" colspan=\"2\">Konstruksi Bangunan</th>
					<th class=\"th01\" rowspan=\"2\" style='width:50'>Luas (m2)</th>
					<th class=\"th01\" rowspan=\"2\" style='$width_alm'>Letak / Alamat</th>
					<th class=\"th02\" colspan=\"2\">Dokumen</th>
					<th class=\"th01\" rowspan=\"2\" style='width:80'>Tanggal Mulai</th>
					<th class=\"th01\" rowspan=\"2\" style='width:50'>Status Tanah</th>
					<th class=\"th01\" rowspan=\"2\" style='width:50'>Kode Tanah</th>
					<th class=\"th01\" rowspan=\"2\" style='width:50'>Cara&nbsp;Perolehan&nbsp;/ Status&nbsp;Barang&nbsp;/<br> Kondisi</th>
					<th class=\"th01\" rowspan=\"2\" style='width:75'>Harga Kontrak (Ribuan)</th>
					<th class=\"th01\" rowspan=\"2\" style='$width_ket'>Ket</th>
					$tampilDok
					$vBidang
					$tampilCbxKeranjangHead	
					</tr>
					<tr>
					<th class=\"th01\" rowspan=\"\">No.</th>
					$tampilCheckbox
					<th class=\"th01\" rowspan=\"\" style='width:60'>Kode Barang</th>
					<th class=\"th01\" rowspan=\"\" style='width:50'>Reg.</th>
					
					<th class=\"th01\" style='width:50'>Bertingkat/ Tidak</th>
					<th class=\"th01\" style='width:50'>Beton/ Tidak</th>
					<th class=\"th01\" style='width:80'>Tanggal</th>
					<th class=\"th01\" style='width:50'>Nomor</th>				
					</tr>
					$tambahgaris";
					break;
				}
				case "kibg" : case "kib_g_cetak" :{					
					$tampilCheckbox =  $cetak ? "" : "<th class='th01' rowspan=''><input type='checkbox' name='toggle' value='' onClick='checkAll(".$Main->PagePerHal.")'></th>";
					$cp = $cetak? 12:13;
					for ($i=0;$i<$cp;$i++){
						$tambahgaris .= "<th class='$clGaris' style='padding:0'></th>";
					}
					$tambahgaris .= '</tr>';
					
					if($tipe=='kertaskerja'){
					
						$headerTable="
						<tr>
						<th class=\"th01\" rowspan=\"2\">No.</th>				
						$tampilCheckbox
						<th class=\"th01\" rowspan=\"2\" style='width:60'>Kode Barang</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Nomor Reg</th>
						<th class=\"th01\" rowspan=\"2\" style='width:150'>Nama Barang</th>
						<!--<th class=\"th01\" rowspan=\"2\" style=''>Tahun Perolehan</th>-->
						<th class=\"th02\" colspan=\"2\">Buku Perpustakaan</th>
						<th class=\"th02\" colspan=\"3\">Barang Bercorak Kesenian / Kebudayaan</th>
						<th class=\"th02\" colspan=\"2\">Hewan Ternak <br> Dan Tumbuhan</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Jumlah</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Tahun Cetak / Beli</th>
						<th class=\"th01\" rowspan=\"2\">Cara Perolehan / Status Barang</th>
						<th class=\"th01\" rowspan=\"2\" style='width:70'>$tampilHeaderHarga</th>
						<th class=\"th01\" rowspan=\"2\" style=''>Kondisi</th>
						<th class=\"th01\" rowspan=\"2\" style='min-width:100;'>Ket.</th>
						$tampilDok
						$vBidang
						$tampilCbxKeranjangHead	
						</tr>
						<tr>
						<th class=\"th01\" style='width:100'>Judul / Pencipta</th>
						<th class=\"th01\" style='width:50'>Spesifikasi</th>
						<th class=\"th01\" style='width:50'>Asal Daerah</th>
						<th class=\"th01\" style='width:50'>Pencipta</th>
						<th class=\"th01\" style='width:50'>Bahan</th>
						<th class=\"th01\" style='width:50'>Jenis</th>
						<th class=\"th01\" style='width:50'>Ukuran</th>
						</tr>
						$tambahgaris";
					}else{
						$headerTable="
						<tr>
						<th class=\"th02\" colspan=\"". ($cetak? "3": "4") ."\">N o m o r</th>
						
						<th class=\"th01\" rowspan=\"2\" style='width:250'>Nama Barang</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Tahun Perolehan</th>
						<th class=\"th01\" rowspan=\"2\" style='width:200'>Judul</th>
						<th class=\"th01\" rowspan=\"2\" style='width:150'>Pencipta</th>
						<th class=\"th01\" rowspan=\"2\" style='width:150'>Jenis</th>
						<th class=\"th01\" rowspan=\"2\" style='width:50'>Jumlah</th>
						<th class=\"th01\" rowspan=\"2\">Cara&nbsp;Perolehan&nbsp;/ Status&nbsp;Barang&nbsp;/<br> Kondisi</th>
						<th class=\"th01\" rowspan=\"2\" style='width:70'>$tampilHeaderHarga</th>
						<th class=\"th01\" rowspan=\"2\" style='min-width:150;'>Ket.</th>
						$tampilDok
						$vBidang
						$tampilCbxKeranjangHead	
						</tr>
						<tr>
						<th class=\"th01\" rowspan=\"\">No.</th>				
						$tampilCheckbox
						<th class=\"th01\" rowspan=\"\" style='width:60'>Kode Barang</th>
						<th class=\"th01\" rowspan=\"\" style='width:50'>Reg.</th>
						</tr>
						$tambahgaris";
					}
					break;
				}
				
				
			}
			return $headerTable;
		}
		
	function genSubtitle($titleCaption=''){
			$ToolbarATas=
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
			function Penatausahaan_Cetakxls(){
				adminForm.action='?Pg=PR&SPg=$spg&ctk=1&xls=1';
				adminForm.target='_blank';
				adminForm.submit();
				adminForm.target='';
			}			
			function Penatausahaan_CetakKertasKerja(){
				adminForm.action='?Pg=PR&SPg=$spg&ctk=1&tipe=kertaskerja';
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
			<td>".PanelIcon1("javascript:Penatausahaan_Cetakxls()","export_xls.png","Excel")."</td>						
			
			</tr></table>			
			</div>";
			$Title = 
			"<table class=\"adminheading\">
			<tr>
			<th height=\"47\" class=\"user\">".$titleCaption."</th>
			<th>
			".$ToolbarATas."
			</th>
			</tr>
			</table>";
			
			return $Title;
		}


	function tampilTotal_($jmlTotalHargaHal, $jmlTotalHarga, $cetak=FALSE, $cbxDlmRibu=FALSE, $clspn1, $clspn2, $clGaris='', $clRow='', $img='') {
	    
		$tampilTotalHal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHargaHal / 1000), 2, ',', '.') : number_format(($jmlTotalHargaHal), 2, ',', '.');
	    if ($jmlTotalHarga > 0){
			$tampilTotal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHarga / 1000), 2, ',', '.') : number_format(($jmlTotalHarga), 2, ',', '.');
		}
	    $ListTotal = $cetak ?
	            "<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=" . ($clspn1 - 1) . " >Jumlah Harga " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=" . ($clspn2 - 1) . " align=right>&nbsp;</td>
					</tr>
					" : "
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=$clspn1 >Jumlah Harga per Halaman " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=$clspn1 >Total Harga Seluruhnya " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right><div id='cntTotHarga' name='cntTotHarga' >$img<b>" . $tampilTotal . "</div></td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
				";
	    return $ListTotal;
	}

		
	function tampilTotalA_($jmlTotalHargaHal, $jmlTotalHarga, $cetak=FALSE, $cbxDlmRibu=FALSE, $clspn1, $clspn2, $clGaris='', $clRow='', $img='', $luas_tanah_hal=0, $luas_tanah=0) {
	    //u kib a
		$txls = $_REQUEST['xls']=="1"?"1":"";
		if ($txls=="1"){
		$tampilTotalHal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHargaHal / 1000), 2, '.', '') : number_format(($jmlTotalHargaHal), 2, '.', '');
	    if ($jmlTotalHarga > 0){
			$tampilTotal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHarga / 1000), 2, '.', '') : number_format(($jmlTotalHarga), 2, '.', '');

		} 
		$vluas_tanah_hal = number_format($luas_tanah_hal,2,'.','');
		$vluas_tanah = number_format($luas_tanah,2,'.','');

		} else 
		{
		$tampilTotalHal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHargaHal / 1000), 2, ',', '.') : number_format(($jmlTotalHargaHal), 2, ',', '.');
	    if ($jmlTotalHarga > 0){
			$tampilTotal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHarga / 1000), 2, ',', '.') : number_format(($jmlTotalHarga), 2, ',', '.');

		} 
		$vluas_tanah_hal = number_format($luas_tanah_hal,2,',','.');
		$vluas_tanah = number_format($luas_tanah,2,',','.');
			
		}
		//if($this->tamp) $clspn2 
	    $ListTotal = $cetak ?
	            "<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=4 >Jumlah Harga " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<b>" . $vluas_tanah_hal . "
					</td>
					<td class=\"$clGaris\" colspan=7 ></td>
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=" . ($clspn2 - 1) . " align=right>&nbsp;</td>
					</tr>" : 
					
					"<tr class=\"$clRow\">
						<td class=\"$clGaris\" colspan=5 >Jumlah Harga per Halaman " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
						<td class=\"$clGaris\" align=right><b>" . $vluas_tanah_hal . "</td>
						<td class=\"$clGaris\" colspan=7 ></td>
					
						<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
						<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
					<tr class=\"$clRow\">
						<td class=\"$clGaris\" colspan=5 >Total Harga Seluruhnya " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
						<td class=\"$clGaris\" align=right>
							<div id='cntTotLuas' name='cntTotHarga' >$img<b>" . $vluas_tanah . "</div>
						</td>
						<td class=\"$clGaris\" colspan=7 ></td>
					
						<td class=\"$clGaris\" align=right>
							<div id='cntTotHarga' name='cntTotHarga' >$img<b>" . $tampilTotal . "</div>
						</td>
						<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
				";
	    return $ListTotal;
	}
	
	function tampilTotalC_($jmlTotalHargaHal, $jmlTotalHarga, $cetak=FALSE, $cbxDlmRibu=FALSE, $clspn1, $clspn2, $clGaris='', $clRow='', $img='', $luas_tanah_hal=0, $luas_tanah=0) {
	    //u kib a
		$txls = $_REQUEST['xls']=="1"?"1":"";
		if ($txls=="1"){		
		$tampilTotalHal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHargaHal / 1000), 2, '.', '') : number_format(($jmlTotalHargaHal), 2, '.', '');
	    if ($jmlTotalHarga > 0){
			$tampilTotal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHarga / 1000), 2, '.', '') : number_format(($jmlTotalHarga), 2, '.', '');
		}
		$vluas_tanah_hal = number_format($luas_tanah_hal,2,'.','');
		$vluas_tanah = number_format($luas_tanah,2,'.','');
		} else {
		$tampilTotalHal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHargaHal / 1000), 2, ',', '.') : number_format(($jmlTotalHargaHal), 2, ',', '.');
	    if ($jmlTotalHarga > 0){
			$tampilTotal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHarga / 1000), 2, ',', '.') : number_format(($jmlTotalHarga), 2, ',', '.');
		}
		$vluas_tanah_hal = number_format($luas_tanah_hal,2,',','.');
		$vluas_tanah = number_format($luas_tanah,2,',','.');
			
		}
	    $ListTotal = $cetak ?
	            "<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=11 >Jumlah Harga " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<b>" . $vluas_tanah_hal . "
					</td>
					<td class=\"$clGaris\" colspan=3 ></td>
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=" . ($clspn2 - 1) . " align=right>&nbsp;</td>
					</tr>
					" : "
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=12 >Jumlah Harga per Halaman " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<b>" . $vluas_tanah_hal . "
					</td>
					<td class=\"$clGaris\" colspan=3 ></td>
					
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
					
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=12 >Total Harga Seluruhnya " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<div id='cntTotLuas' name='cntTotHarga' >$img<b>" . $vluas_tanah . "</div>
					</td>
					<td class=\"$clGaris\" colspan=3 ></td>
					
					<td class=\"$clGaris\" align=right>
					<div id='cntTotHarga' name='cntTotHarga' >$img<b>" . $tampilTotal . "</div></td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
				";
	    return $ListTotal;
	}
	
	function tampilTotalD_($jmlTotalHargaHal, $jmlTotalHarga, $cetak=FALSE, $cbxDlmRibu=FALSE, $clspn1, $clspn2, $clGaris='', $clRow='', $img='', $luas_tanah_hal=0, $luas_tanah=0, $cp3=7) {
	    //u kib a
		$tampilTotalHal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHargaHal / 1000), 2, ',', '.') : number_format(($jmlTotalHargaHal), 2, ',', '.');
	    if ($jmlTotalHarga > 0){
			$tampilTotal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHarga / 1000), 2, ',', '.') : number_format(($jmlTotalHarga), 2, ',', '.');
		}
		$vluas_tanah_hal = number_format($luas_tanah_hal,2,',','.');
		$vluas_tanah = number_format($luas_tanah,2,',','.');
	    $ListTotal = $cetak ?
	            "<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=8 >Jumlah Harga " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<b>" . $vluas_tanah_hal . "
					</td>
					<td class=\"$clGaris\" colspan=$cp3 ></td>
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=" . ($clspn2 - 1) . " align=right>&nbsp;</td>
					</tr>
					" : "
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=9 >Jumlah Harga per Halaman " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<b>" . $vluas_tanah_hal . "
					</td>
					<td class=\"$clGaris\" colspan=$cp3 ></td>
					
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
					
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=9 >Total Harga Seluruhnya " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<div id='cntTotLuas' name='cntTotHarga' >$img<b>" . $vluas_tanah . "</div>
					</td>
					<td class=\"$clGaris\" colspan=$cp3 ></td>
					
					<td class=\"$clGaris\" align=right>
					<div id='cntTotHarga' name='cntTotHarga' >$img<b>" . $tampilTotal . "</div></td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
				";
	    return $ListTotal;
	}
	
	function tampilTotalF_($jmlTotalHargaHal, $jmlTotalHarga, $cetak=FALSE, $cbxDlmRibu=FALSE, $clspn1, $clspn2, $clGaris='', $clRow='', $img='', $luas_tanah_hal=0, $luas_tanah=0) {
	    //u kib a
		$tampilTotalHal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHargaHal / 1000), 2, ',', '.') : number_format(($jmlTotalHargaHal), 2, ',', '.');
	    if ($jmlTotalHarga > 0){
			$tampilTotal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHarga / 1000), 2, ',', '.') : number_format(($jmlTotalHarga), 2, ',', '.');
		}
		$vluas_tanah_hal = number_format($luas_tanah_hal,2,',','.');
		$vluas_tanah = number_format($luas_tanah,2,',','.');
	    $ListTotal = $cetak ?
	            "<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=8 >Jumlah Harga " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<b>" . $vluas_tanah_hal . "
					</td>
					<td class=\"$clGaris\" colspan=7 ></td>
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=" . ($clspn2 - 1) . " align=right>&nbsp;</td>
					</tr>
					" : "
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=9 >Jumlah Harga per Halaman " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<b>" . $vluas_tanah_hal . "
					</td>
					<td class=\"$clGaris\" colspan=7 ></td>
					
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
					
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=9 >Total Harga Seluruhnya " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<div id='cntTotLuas' name='cntTotHarga' >$img<b>" . $vluas_tanah . "</div>
					</td>
					<td class=\"$clGaris\" colspan=7 ></td>
					
					<td class=\"$clGaris\" align=right>
					<div id='cntTotHarga' name='cntTotHarga' >$img<b>" . $tampilTotal . "</div></td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
				";
	    return $ListTotal;
	}
	function tampilTotalG_($jmlTotalHargaHal, $jmlTotalHarga, $cetak=FALSE, $cbxDlmRibu=FALSE, $clspn1, $clspn2, $clGaris='', $clRow='', $img='', $luas_tanah_hal=0, $luas_tanah=0) {
	    //u kib a
		$tampilTotalHal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHargaHal / 1000), 2, ',', '.') : number_format(($jmlTotalHargaHal), 2, ',', '.');
	    if ($jmlTotalHarga > 0){
			$tampilTotal = !empty($cbxDlmRibu) ? number_format(($jmlTotalHarga / 1000), 2, ',', '.') : number_format(($jmlTotalHarga), 2, ',', '.');
		}
		$vluas_tanah_hal = number_format($luas_tanah_hal,2,',','.');
		$vluas_tanah = number_format($luas_tanah,2,',','.');
	    $ListTotal = $cetak ?
	            "<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=8 >Jumlah Harga " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<b>" . $vluas_tanah_hal . "
					</td>
					<td class=\"$clGaris\" colspan=7 ></td>
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=" . ($clspn2 - 1) . " align=right>&nbsp;</td>
					</tr>
					" : "
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=9 >Jumlah Harga per Halaman " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<b>" . $vluas_tanah_hal . "
					</td>
					<td class=\"$clGaris\" colspan=7 ></td>
					
					<td class=\"$clGaris\" align=right><b>" . $tampilTotalHal . "</td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
					
					<tr class=\"$clRow\">
					<td class=\"$clGaris\" colspan=9 >Total Harga Seluruhnya " . ( empty($cbxDlmRibu) ? "" : ("(Ribuan)") ) . "</td>
					<td class=\"$clGaris\" align=right>
					<div id='cntTotLuas' name='cntTotHarga' >$img<b>" . $vluas_tanah . "</div>
					</td>
					<td class=\"$clGaris\" colspan=7 ></td>
					
					<td class=\"$clGaris\" align=right>
					<div id='cntTotHarga' name='cntTotHarga' >$img<b>" . $tampilTotal . "</div></td>
					<td class=\"$clGaris\" colspan=$clspn2 align=right>&nbsp;</td>
					</tr>
				";
	    return $ListTotal;
	}
	
			
}
$Penatausaha = new PenatausahaObj();

			
?>