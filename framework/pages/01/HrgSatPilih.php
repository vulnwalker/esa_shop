<?php

class HrgSatPilihObj  extends DaftarObj2{	
	var $Prefix = 'HrgSatPilih';
	var $elCurrPage="HalDefault";
	var $TblName = 'ref_std_harga'; //daftar
	var $TblName_Hapus = 'ref_std_harga';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='HrgSatPilih.xls';
	var $Cetak_Judul = 'DAFTAR PEGAWAI';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'HrgSatPilihForm';
	
	
	//function setPage_TitleDaftar(){	return 'Daftar Pegawai'; }	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Daftar Harga Satuan';
	}
	
	function setMenuEdit(){		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Standar Harga Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit Standar Harga')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus Standar Harga')."</td>";
	}
	
	function setMenuView(){		
		return 			
			"";
		
	}
	
	function setFormBaru(){
		global $Main;
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$dt['kd_barang']=$_REQUEST['fmIDBARANG'];
		$dt['nm_barang']=$_REQUEST['fmNMBARANG'];
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormEdit(){
		global $HTTP_COOKIE_VARS;
		global $Main;
	 	$uid = $HTTP_COOKIE_VARS['coID'];
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh =$cbid[0];
		
		$aqry = "select * from ref_std_harga where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		$this->form_fmST = 1;
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 450;
		$this->form_height = 150;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$tahun=$_COOKIE['coThnAnggaran'];
		}else{
			$this->form_caption = 'Edit';			
			$tahun=$dt['tahun'];
		}
		
		$nm_barang = "<input type=text name='kd_barang' id='kd_barang' value='".$dt['kd_barang']."' size='11' readonly>
					 <input type=text name='nm_barang' id='nm_barang' value='".$dt['nm_barang']."' size='30' readonly>
					 <input type='button' value='CariDKB' onclick ='".$this->Prefix.".CariDKB()' >
					 <input type='button' value='CariMR' onclick ='".$this->Prefix.".CariPemanfaatRencana()' >
					 <input type='button' value='CariPR' onclick ='".$this->Prefix.".CariPemeliharaRencana()' >
					 <input type='button' value='CariPR2' onclick ='".$this->Prefix.".CariPemindahtanganRencana()' >";
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		
		$this->form_fields = array(	
			
			
			/*'nm_barang' => array(  'label'=>'Nama Barang', 
							'value'=> $nm_barang,
							'labelWidth'=>100, 
							'row_params'=>"", 
							'type'=>'' 
						),*/
			
			'merk' => array(  'label'=>'Merk/Spesifikasi',
							   'value'=> "<textarea name='merk' id='merk' style='margin: 0px; height: 50px; width: 270px;'>".$dt['merk']."</textarea>",  
							   'type'=>'' ,
							   'row_params'=> "valign=top",
							 ),
							 
			'harga' => array(  'label'=>'Harga Satuan',
							   'value'=> "<input type=text name='harga' id='harga' value='".$dt['harga']."' size='20' style='text-align:right;' onkeypress='return isNumberKey(event)'",  
							   'type'=>'' ,
							   'param'=> "",
							 ),

			'tahun' => array(  'label'=>'Tahun',
							   'value'=> "<input type=text name='tahun' id='tahun' value='$tahun' size=4 readonly>",  
							   'type'=>'' ,
							   'param'=> "",
							 ),
			
			'ket' => array(  'label'=>'Keterangan',
							   'value'=> "<input type=text name='ket' id='ket' value='".$dt['ket']."' size='50'>",  
							   'type'=>'' ,
							   'param'=> "",
							   )

		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden name='kd_barang' id='kd_barang' value='".$dt['kd_barang']."'>".
			"<input type=hidden name='nm_barang' id='nm_barang' value='".$dt['nm_barang']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $kd_barang = $_REQUEST['kd_barang'];
	 $kdbrg = explode(".", $kd_barang);
	 $f = $kdbrg[0];
	 $g = $kdbrg[1];
	 $h = $kdbrg[2];
	 $i = $kdbrg[3];
	 $j = $kdbrg[4];
	 $nm_barang = $_REQUEST['nm_barang'];
	 $merk = $_REQUEST['merk'];
	 $harga = $_REQUEST['harga'];
	 $tahun = $_REQUEST['tahun'];
	 $ket = $_REQUEST['ket'];
	 
	 //cek validasi
	 if( $err=='' && $merk =='' ) $err= 'Merk/Spesifikasi belum diisi !!';
	 if( $err=='' && $harga =='' ) $err= 'Harga Satuan belum diisi !!';
	 if( $err=='' && $tahun =='' ) $err= 'Tahun belum diisi !!';
 	 	 
	 
			if($fmST == 0){ //input penggunaan
				if($err==''){ 
					$aqry = "INSERT INTO ref_std_harga (f,g,h,i,j,nm_barang,harga,ket,tahun,merk)
							VALUES ('$f','$g','$h','$i','$j','$nm_barang','$harga','$ket','$tahun','$merk')";	$cek .= $aqry;	
					$qry = mysql_query($aqry);
					
				}	
			}elseif($fmST == 1){
			
			$old = mysql_fetch_array(mysql_query("select * from t_kip where Id='".$idplh."' "));
			$old_idbi=$old['idbi'];
									
				if($err==''){
					$aqry2 = "UPDATE ref_std_harga
			        		 set "."
							 harga = '$harga',
							 ket='$ket',
							 tahun = '$tahun',
							 merk = '$merk'".
					 		 "WHERE Id='".$idplh."'";	$cek .= $aqry2;	
					$qry2 = mysql_query($aqry2);
					
				}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
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
			
			case 'formBaru':{				
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'formEdit':{				
				$fm = $this->setFormEdit();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'simpan':{
				
				$get= $this->simpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'getdata':{
				$id = $_REQUEST['id'];
				$aqry = "select * from ref_pegawai where id='$id' "; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query($aqry));
				if($get==FALSE) $err= "Gagal ambil data!"; 
				$content = array('nip'=>$get['nip'],'nama'=>$get['nama'],'jabatan'=>$get['jabatan']);
				break;
			}
			
			case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
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
			/*default:{
				$err = 'tipe tidak ada!';
				break;
			}*/
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40'>No.</th>
				$Checkbox
				<th class='th01' >Merk/Type/Ukuran/Spek </th>
				<th class='th01' >Harga Satuan (Rp) </th>								
				
				</tr>
				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		$Koloms = array();
		$Koloms[] = array('align=center', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $isi['merk'] );
		$Koloms[] = array('align=right', number_format($isi['harga'],2,',','.'));				
		return $Koloms;
	}
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$fmNMBARANG = $_REQUEST['fmNMBARANG'];	
		$fmMERK = $_REQUEST['fmMERK'];	
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">
			<tr>
			<td width=20%>Kode Barang</td>
			<td width=1%>:</td> 
			<td width=89%><input type=text id='fmIDBARANG' name='fmIDBARANG' value='$fmIDBARANG' size=15 readonly></td>
			</tr>
			<tr>
			<td width=20%>Nama Barang</td>
			<td width=1%>:</td> 
			<td width=89%><input type=text id='fmNMBARANG' name='fmNMBARANG' value='$fmNMBARANG' size=40 readonly></td>
			</tr>
			<tr>
			<td width=20%>Merk</td>
			<td width=1%>:</td> 
			<td width=89%>
				<input type=text id=fmMERK name=fmMERK value='$fmMERK' size=40>&nbsp;&nbsp;
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'></td>
			</tr>			
			</table>";
			
		return array('TampilOpt'=>$TampilOpt);
	}			
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		
		$arrKondisi = array();		
		//$arrKondisi[] = 'a='.$Main->Provinsi[0];
		//$arrKondisi[] = 'b='.$Main->DEF_WILAYAH;
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		/*if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = 'c='.$fmSKPD;
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = 'd='.$fmUNIT;
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = 'e='.$fmSUBUNIT;
		if(!($fmSEKSI=='' || $fmSEKSI=='00' || $fmSEKSI=='000') ) $arrKondisi[] = 'e1='.$fmSEKSI;
		
		*/
		 	
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$fmNMBARANG = $_REQUEST['fmNMBARANG'];
		$fmMERK = $_REQUEST['fmMERK'];
		if (!empty($fmIDBARANG)) $arrKondisi[] = "concat(f,'.',g,'.',h,'.',i,'.',j) ='$fmIDBARANG'";
		if (!empty($fmNMBARANG)) $arrKondisi[] = "nm_barang ='$fmNMBARANG'";
		if (!empty($fmMERK)) $arrKondisi[] = "merk ='$fmMERK'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,e1,nip ";
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}*/		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		
		$fmIDBARANG = $_REQUEST['fmIDBARANG'];
		$fmNMBARANG = $_REQUEST['fmNMBARANG'];
		
		//if($err==''){
			$FormContent = $this->genDaftarInitial($fmIDBARANG, $fmNMBARANG);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						600,
						300,
						'Informasi Standar Harga',
						'',
						//"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
						"<input type='button' value='Tutup' onclick ='".$this->Prefix.".windowClose()' >".
						//"<input type='hidden' id='CariBarang_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						//"<input type='hidden' id='CariBarang_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
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
	function genDaftarInitial($fmIDBARANG='', $fmNMBARANG=''){
		$vOpsi = $this->genDaftarOpsi();
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' id='fmIDBARANG' name='fmIDBARANG' value='$fmIDBARANG'>".
				"<input type='hidden' id='fmNMBARANG' name='fmNMBARANG' value='$fmNMBARANG'>".
				//"<input type='hidden' id='".$this->Prefix."SkpdfmSUBUNIT' name='".$this->Prefix."SkpdfmSUBUNIT' value='$fmSUBUNIT'>".
				//"<input type='hidden' id='".$this->Prefix."tahun_anggaran' name='".$this->Prefix."tahun_anggaran' value='$tahun_anggaran'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='HrgSatPilih_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			/*"<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>".			*/
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/dkb.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/pemanfaatan/pemanfaat_rencana.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/pemeliharaan/pemelihara_rencana.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/pemindahtangan/pemindahtangan_rencana.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/HrgSatPilih.js' language='JavaScript' ></script>".

			$scriptload;
	}

}
$HrgSatPilih = new HrgSatPilihObj();
?>