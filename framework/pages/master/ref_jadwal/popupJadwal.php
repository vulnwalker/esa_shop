<?php

class popupJadwalObj  extends DaftarObj2{	
	var $Prefix = 'popupJadwal';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_jadwal'; //daftar
	var $TblName_Hapus = 'ref_jadwal';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='25';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'BARANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'popupJadwalForm'; 	
			
	function setTitle(){
		return 'Histori';
	}
	function setMenuEdit(){		
		return
			"";
	}
	
	function setCetak_Header($Mode=''){
		global $Main, $HTTP_COOKIE_VARS;
		
		//$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD'); //echo 'fmskpd='.$fmSKPD;
		//$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		//$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\">".$this->setCetakTitle()."</td>
			</tr>
			</table>";	
			/*"<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT)."</td>
				</tr>
			</table><br>";*/
	}	
	
	
		
	function set_selector_other2($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){
	  

		
		case 'windowshow':{
				$idHistori = $_REQUEST['idHistori'];
				$fm = $this->windowShow($idHistori);
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
			break;
		}

	   	case 'getdata':{
			foreach ($_REQUEST as $key => $value) { 
		 		 $$key = $value; 
	 		}
			
			if(empty($tahun)){
				$err = "Isi tahun";
			}elseif(empty($jenis_anggaran)){
				$err = "Pilih jenis anggaran";
			}else{
				mysql_query("delete from ref_tahap_anggaran where tahun = '$tahun' and anggaran = '$jenis_anggaran'");
				$getData = mysql_query("select * from ref_jadwal where status_aktif = 'AKTIF' order by id ");
				$nomorUrut = 1 ;
				while($rows = mysql_fetch_array($getData)){
					foreach ($rows as $key => $value) { 
		 		 		$$key = $value; 
	 				}
					$data = array(
									'nama_tahap' => $nama_tahap,
									'anggaran' => $jenis_anggaran,
									'tahun' => $tahun,
									'id_modul' => $id_modul,
									'no_urut' => $nomorUrut,
									'jenis_form_modul' => $jenis_form_modul,
									'status_penyusunan' => $status_penyusunan,
									'tgl_update' => date('Y-m-d'),
									'user' => $_COOKIE['coID']	,
									'status_penyusunan' => 'AKTIF'
								  
								  );
					$query = VulnWalkerInsert('ref_tahap_anggaran',$data);
					$content .= $query;
					$nomorUrut = $nomorUrut + 1;
					mysql_query($query);			  
				}
			}
			
			break;
	   }		
		
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	   }
	   case 'save':{
			foreach ($_REQUEST as $key => $value) { 
		 		 		$$key = $value; 
	 				}
			$data = array(
							'nama_tahap' => $namaTahap,
							'jenis_form_modul' => $jenisForm,
							'id_modul' => $idModul,
							'status_aktif' => $statusAktif
						
						 );
			$query = VulnWalkerUpdate('ref_jadwal', $data, " id ='$id'");
			if(empty($namaTahap)){
				$err = "Isi nama tahap";
			}elseif(empty($idModul)){
				$err = "Pilih modul";
			}elseif(empty($jenisForm)){
				$err = "Pilih jenis tahap";
			}elseif(empty($statusAktif)){
				$err = "Pilih status aktif";
			}else{
				mysql_query($query);
			}
		break;
	   }
	   	case 'newRow':{
			$getMaxNoUrut = mysql_fetch_array(mysql_query("select max(no_urut) from ref_jadwal"));
			$no_urut = $getMaxNoUrut['max(no_urut)'];
			mysql_query("insert into ref_jadwal (nama_tahap) values ('')");
		break;
	   }
	   	case 'cekDataAda':{
			foreach ($_REQUEST as $key => $value) { 
		 		 		$$key = $value; 
	 				}
			if(mysql_num_rows(mysql_query("select * from ref_tahap_anggaran where tahun ='$tahun' and anggaran ='$jenis_anggaran'")) > 0){
				$status = 'ya';
			}else{
				$status = 'tidak';
			}
			
			$content = array('ada' => $status );
		break;
	   }
	   
	   case 'modulChanged':{
		 	$id = $_REQUEST['id'];
			$namaModul = $_REQUEST['namaModul'];
			$getModul = mysql_fetch_array(mysql_query("select * from ref_modul where id_modul ='$namaModul'"));
			if($getModul['nama_modul'] == 'RKBMD'){
					$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
					  	    				array('VALIDASI' , 'VALIDASI'),
							 				array('KOREKSI PENGGUNA' , 'KOREKSI PENGGUNA'),
							 			    array('KOREKSI PENGELOLA' , 'KOREKSI PENGELOLA'),
							                array('READ' , 'READ'));
			}else{
				$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
					  	    				array('VALIDASI' , 'VALIDASI'),
							 				array('KOREKSI' , 'KOREKSI'),
							                array('READ' , 'READ'));
			}
			$content = array('jenisForm' => cmbArray('jenisForm',$jenisForm,$arrayJenisForm,'-- JENIS TAHAP --') );
		break;
	   }
	   	case 'remove':{
			$id =  $_REQUEST['id'];
			mysql_query("delete from ref_jadwal where id='$id'");
		break;
	   }
	    	case 'cancel':{
			$id =  $_REQUEST['id'];
			mysql_query("delete from ref_jadwal where nama_tahap =''");
		break;
	   }
	   
	   case 'fiturEdit':{
			foreach ($_REQUEST as $key => $value) { 
		 		 		$$key = $value; 
	 				}
			$getIdModul = mysql_fetch_array(mysql_query("select * from  ref_modul where nama_modul ='$namaModul'"));
			
			$codeAndNameModul = "select id_modul, nama_modul from ref_modul";
			$cmbModul = cmbQuery('namaModul'.$id, $getIdModul['id_modul'], $codeAndNameModul," onchange=$this->Prefix.modulChanged('$id'); ",'-- MODUL --');
			if($namaModul !='RKBMD'){
				$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
					  	    				array('VALIDASI' , 'VALIDASI'),
							 				array('KOREKSI' , 'KOREKSI'),
							                array('READ' , 'READ'));
			}else{
				$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
					  	    				array('VALIDASI' , 'VALIDASI'),
							 				array('KOREKSI PENGGUNA' , 'KOREKSI PENGGUNA'),
							 			    array('KOREKSI PENGELOLA' , 'KOREKSI PENGELOLA'),
							                array('READ' , 'READ'));
			}
			$arrayStatus = array(
							array('AKTIF' , 'AKTIF'),
							array('TIDAK AKTIF' , 'TIDAK AKTIF')
							);
			 $aksi = "
	 
	 <img id='save$id' src='datepicker/save.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=$this->Prefix.save('$id');>
	 <img id='cancel$id' src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=$this->Prefix.cancel('$id');>";
			$content = array('cmbModul' => $cmbModul, 'jenisForm' =>  cmbArray('jenisForm'.$id,$jenisForm,$arrayJenisForm,'-- JENIS TAHAP --')
			, 'statusAktif' => cmbArray('statusAktif'.$id,$statusAktif,$arrayStatus,'--  STATUS AKTIF --'),
			'aksi' => $aksi );
		break;
	   }
	   	   	   
	   default:{
			$other = $this->set_selector_other2($tipe);
			$cek = $other['cek'];
			$err = $other['err'];
			$content=$other['content'];
			$json=$other['json'];
	 break;
	 }
	 }//end switch
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   }
	
	function setPage_OtherScript(){
		$scriptload = 

					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			 "<script type='text/javascript' src='js/master/ref_jadwal/$this->Prefix.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	//form ==================================
	
	function genDaftarInitial($fmSKPD='', $fmUNIT='', $fmSUBUNIT='',$tahun_anggaran='', $height=''){
		$vOpsi = $this->genDaftarOpsi();
		return			
			//"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
	function windowShow($idHistori){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		
			$FormContent = $this->genDaftarInitial($fmSKPD, $fmUNIT, $fmSUBUNIT,$tahun_anggaran);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						1300,
						550,
						'TEMPLATE JADWAL',
						'',
						"<input type='hidden' name='idHistori' id='idHistori' value='$idHistori'>".
						"<input type='button' value='Ok' onclick ='".$this->Prefix.".windowSave()' > &nbsp &nbsp".
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
			);
			$content = $form;//$content = 'content';	
		//}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}	
		
	//daftar =================================	
function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
	   <th class='th01' width='900'>NAMA TAHAP</th>
	   <th class='th01' width='200'>AKTIFASI MODUL</th>
	   <th class='th01' width='200'>JENIS TAHAP</th>

	   <th class='th01' width='200'>STATUS</th>
	   <th class='th01' width='200'><span id='atasbutton'>
								<a href='javascript:popupJadwal.newRow();' id='linkAtasButton' /><img id='gambarAtasButton' src='datepicker/add-256.png' style='width:20px;height:20px;' /></a>
								</span></th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 foreach ($isi as $key => $value) { 
		  $$key = $value; 
	 }
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	 if(!empty($nama_tahap)){
	 	 $Koloms[] = array('align="left"',"<span id='spanNamaTahap$id'>". $nama_tahap ."</span>" );
	 	$getNamaModul = mysql_fetch_array(mysql_query("select * from ref_modul where id_modul  = '$id_modul'"));
		 $nama_modul = $getNamaModul['nama_modul'];
	 	$Koloms[] = array('align="left"',"<span id='spanNamaModul$id'>". $nama_modul ."</span>" );
		 $Koloms[] = array('align="left"',"<span id='spanJenisFormModul$id'>". $jenis_form_modul ."</span>" );
		 $Koloms[] = array('align="center"',"<span id='spanStatusAktif$id'>". $status_aktif ."</span>" );
		 //$aksi = "<img id='action$id' src='datepicker/save.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd_pemeliharaan.subSubSave('$id');>";
		 $aksi = "
	 
			 <img id='edit$id' src='images/administrator/images/edit_f2.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=$this->Prefix.edit('$id');>
			 <img id='delete$id' src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=$this->Prefix.remove('$id');>";
			 $Koloms[] = array('align="center"',"<span id='action$id'>".$aksi."</span>" );
	 }else{
	 	$getIdModul = mysql_fetch_array(mysql_query("select * from  ref_modul where nama_modul ='$namaModul'"));
			
			$codeAndNameModul = "select id_modul, nama_modul from ref_modul";
			$cmbModul = cmbQuery('namaModul'.$id, $getIdModul['id_modul'], $codeAndNameModul," onchange=$this->Prefix.modulChanged('$id'); ",'-- MODUL --');
			if($namaModul !='RKBMD'){
				$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
					  	    				array('VALIDASI' , 'VALIDASI'),
							 				array('KOREKSI' , 'KOREKSI'),
							                array('READ' , 'READ'));
			}else{
				$arrayJenisForm = array(array('PENYUSUNAN' , 'PENYUSUNAN'),
					  	    				array('VALIDASI' , 'VALIDASI'),
							 				array('KOREKSI PENGGUNA' , 'KOREKSI PENGGUNA'),
							 			    array('KOREKSI PENGELOLA' , 'KOREKSI PENGELOLA'),
							                array('READ' , 'READ'));
			}
			$arrayStatus = array(
							array('AKTIF' , 'AKTIF'),
							array('TIDAK AKTIF' , 'TIDAK AKTIF')
							);
			
	 	$Koloms[] = array('align="left"',"<span id='spanNamaTahap$id'>". "<input type='text' id='namaTahap$id'  style='width:100%;' >" ."</span>" );
	 	$Koloms[] = array('align="left"',"<span id='spanNamaModul$id'>". $cmbModul ."</span>" );
		 $Koloms[] = array('align="left"',"<span id='spanJenisFormModul$id'>". cmbArray('jenisForm'.$id,$jenisForm,$arrayJenisForm,'-- JENIS TAHAP --') ."</span>" );
		 $Koloms[] = array('align="center"',"<span id='spanStatusAktif$id'>". cmbArray('statusAktif'.$id,$statusAktif,$arrayStatus,'--  STATUS AKTIF --') ."</span>" );
		 $aksi = "
	 
	 <img id='save$id' src='datepicker/save.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=$this->Prefix.save('$id');>
	 <img id='cancel$id' src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=$this->Prefix.cancel('$id');>";
			 $Koloms[] = array('align="center"',"<span id='action$id'>".$aksi."</span>" );
	 }
	 
	 
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 	$tahun = $_REQUEST['tahun'];
		$jenis_anggaran = $_REQUEST['jenis_anggaran'];
		$arr = array(
						array('MURNI','MURNI'),		
						array('PERUBAHAN','PERUBAHAN')		
			);
	 
		$TampilOpt = 
			"<div class='FilterBar' style='margin-top:5px;'>".
			"<table style='width:100%'>
			<tr>
			<td style='width:150px;'> TAHUN </td><td><input type='text' value='$tahun'  name='tahun' id='tahun'>  </td>
			 </tr>
			 <tr>
			<td style='width:150px;'> JENIS ANGGARAN </td><td>".cmbArray('jenis_anggaran',$jenis_anggaran,$arr,'-- JENIS ANGGARAN --','')."   </td>
			 </tr>
			</table>".
			"</div>"
			
			;
			
		return array('TampilOpt'=>$TampilOpt);
	}					
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$idTahap = $_REQUEST['idHistori'];

		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

			
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		//cari tgl,bln,thn
		$fmLimit = $_REQUEST['baris'];
		$this->pagePerHal=$fmLimit;

		//Cari 
		switch($fmPILCARI){			
			case 'nama_Histori': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;						 
			case 'status': $arrKondisi[] = " $fmPILCARI like '%$fmPILCARIvalue%'"; break;	
		}

		/*$arrKondisi[] = " id_tahap = '$idTahap'";*/
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = "id";
		switch($fmORDER1){
			case 'nama_Histori': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
			case 'status': $arrOrders[] = " $fmORDER1 $Asc1 " ;break;
		}	
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;

		/*$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;

		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	*/
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$popupJadwal = new popupJadwalObj();

?>