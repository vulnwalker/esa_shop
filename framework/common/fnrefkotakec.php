<?php

class KotaKecObj  extends DaftarObj2{	
	var $Prefix = 'refKotaKec';
	var $elCurrPage="HalDefault";
	var $TblName = 'ref_kotakec'; //daftar
	var $TblName_Hapus = 'ref_kotakec';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('kd_kota','kd_kec');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='kotakecamatan.xls';
	var $Cetak_Judul = 'DAFTAR KOTA/KECAMATAN';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	
	//function setPage_TitleDaftar(){	return 'Daftar Pegawai'; }	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Daftar Kota/Kecamatan';
	}
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
/*		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
*/		
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>	
			<br>";
	}
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	function simpan(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
				$fmST = $_REQUEST[$this->Prefix.'_fmST'];
				$kdkotaplh = $_REQUEST[$this->Prefix.'_kdkotaplh'];
				$kdkecplh = $_REQUEST[$this->Prefix.'_kdkecplh'];
				
				$kd_kota=$_REQUEST['kd_kota'];
				$kd_kec=$_REQUEST['kd_kec'];
				$nm_wilayah= $_REQUEST['nm_wilayah'];
				$koord_gps= $_REQUEST['koordinat_gps'];
				$koord_bidang= $_REQUEST['koord_bidang'];
				$zoom= $_REQUEST['zoom'];
				
				
				if( $err=='' && $kd_kota =='' ) $err= 'Kode Kota belum diisi!';
				if( $err=='' && $kd_kec =='' ) $err= 'Kode Kecamatan belum diisi!';
				if( $err=='' && $nm_wilayah =='' ) $err= 'Nama Wilayah belum diisi!';
				
					
				
				if($fmST == 0){
					//cek 
					if( $err=='' ){
						$get = mysql_fetch_array(mysql_query(
							"select count(*) as cnt from $this->TblName where kd_kota='$kd_kota' and kd_kec='$kd_kec' "
						));
						if($get['cnt']>0 ) $err="Kode Kota/Kecamatan Sudah Ada!  ";
					}
					if($err==''){
						$aqry = "insert into $this->TblName (kd_kota,kd_kec,nm_wilayah,koordinat_gps,koord_bidang,zoom)"."values('$kd_kota','$kd_kec','$nm_wilayah','$koord_gps','$koord_bidang','$zoom')";	$cek .= $aqry;	
						$qry = mysql_query($aqry);
					}
					
				}else{
					$old = mysql_fetch_array(mysql_query("select * from $this->TblName where kd_kota='$kdkotaplh' and kd_kec='$kdkecplh'"));
					if( $err=='' ){
						if($kd_kota!=$old['kd_kota'] && $kd_kec!=$old['kd_kec'] ){
							$get = mysql_fetch_array(mysql_query(
								"select count(*) as cnt from $this->TblName where kd_kota='$kd_kota' and kd_kec='$kd_kec' "
							));
							if($get['cnt']>0 ) $err='Kode Kota/Kecamatan Sudah Ada!';
						}
					}
					if($err==''){
						/*$aqry = "update ref_ruang set ".
							"a1='$a1', a='$a', b='$b', c='$c',d='$d',e='$e',
							p='$p',q='$q',nm_ruang='$nm_ruang'".
							"where a1='$a1' and a='$a' and b='$b' and c='$c' and d='$d' and e='$e' 
							and p='$oldp' and q='$oldq' ";	$cek .= $aqry;
						*/
						$aqry = "update $this->TblName set ".
							" kd_kota='$kd_kota', kd_kec='$kd_kec', nm_wilayah='$nm_wilayah',".
							"koordinat_gps='$koord_gps',koord_bidang='$koord_bidang',zoom='$zoom'".
							"where kd_kota='".$old['kd_kota']."' and kd_kec='".$old['kd_kec']."' ";	$cek .= $aqry;
						$qry = mysql_query($aqry);
					}
				}
				
				//
				
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
			case 'cbxgedung':{
				break;
			}		
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
				$kd_kota = $_REQUEST['kd_kota'];
				$kd_kec = $_REQUEST['kd_kec'];
				$aqry = "select * from $TblName where kd_kota='$kd_kota' and kd_kec='$kd_kec'  "; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query($aqry));
				if($get==FALSE) $err= "Gagal ambil data!"; 
				$content = array('kd_kota'=>$get['kd_kota'],'kd_kec'=>$get['kd_kec'],
				'nm_wilayah'=>$get['nm_wilayah'],'koordinat_gps'=>$get['koordinat_gps'],
				'koordinat_gps'=>$get['koordinat_gps'],'koord_bidang'=>$get['koord_bidang'],
				'zoom'=>$get['zoom']);
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
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$dt['kd_kota'] = '';
		$dt['kd_kec'] = '';
		$dt['nm_wilayah'] = '';
		$dt['koordinat_gps'] = '';
		$dt['koord_bidang'] = '';
		$dt['zoom'] = '';
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	function setFormEdit(){
		$cek ='';

		$cbid = $_REQUEST[$this->Prefix.'_cb'];
/*
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
*/				
		$this->form_idplh = $cbid[0];

		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		//$aqry = "select * from ref_ruang where c='$c' and d='$d' and e='$e' and p ='".$kode[0]."' and q='".$kode[1]."' "; $cek.=$aqry;
		$aqry = "select * from $this->TblName where kd_kota ='".$kode[0]."' and kd_kec ='".$kode[1]."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
				//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setForm($dt){	
		global $SensusTmp,$Main;
		$cek = ''; 
		$err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 500;
		$this->form_height = 200;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';

		}else{
			$this->form_caption = 'Edit';			
			$kd_kota = $dt['kd_kota'];			
			$kd_kec = $dt['kd_kec'];			
			$nm_wilayah = $dt['nm_wilayah'];			
			$koordinat_gps = $dt['koordinat_gps'];			
			$koord_bidang = $dt['koord_bidang'];			
		}
		
		$combozoom="<select id='zoom' name='zoom' >";
		$x=0;
		while ($x<=14)
		{
		$x++;
			$combozoom .= $dt['zoom']==$x ? "<option value='$x' selected>$x</option>" :"<option value='$x' >$x</option>"; 	
		}
		$combozoom.="</select>";
		//items ----------------------
		
		
		$this->form_fields = array(				
			'kd_kota' => array(  'label'=>'Kode Kota', 'value'=> $kd_kota, 'labelWidth'=>120, 'type'=>'number' ),
			'kd_kec' => array(  'label'=>'Kode Kecamatan', 'value'=> $kd_kec, 'labelWidth'=>120, 'type'=>'number' ),
			'nm_wilayah' => array(  'label'=>'Nama Wilayah', 'value'=> $nm_wilayah, 'labelWidth'=>120, 'type'=>'text' ),
			'koordinat_gps' => array(  'label'=>'Koordinat GPS', 'value'=> $koordinat_gps, 'labelWidth'=>120, 'type'=>'text' ),
			'koord_bidang' => array( 'label'=>'Koordinat Bidang', 'value'=> $koord_bidang, 'labelWidth'=>120, 'type'=>'memo' ),
			'zoom' => array( 'label'=>'Zoom', 'value'=>$combozoom, 'type'=>'')	
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='".$this->Prefix."_kdkotaplh' name='".$this->Prefix."_kdkotaplh' value='".$dt['kd_kota']."'> ".
			"<input type=hidden id='".$this->Prefix."_kdkecplh' name='".$this->Prefix."_kdkecplh' value='".$dt['kd_kec']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40'>No.</th>
				$Checkbox		
				<th class='th01' width=40>Kode Kota</th>
				<th class='th01' width=40>Kode Kecamatan</th>
				<th class='th01' >Nama Kota/Kecamatan </th>
				</tr>
				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array(" align='center'  ", $isi['kd_kota']);		
		$Koloms[] = array(" align='center'  ", $isi['kd_kec']);		
		$Koloms[] = array('', $isi['nm_wilayah'] );
		return $Koloms;
	}
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmPILKOTA = $_REQUEST['fmPILKOTA'];
		$TampilOpt =
			
genFilterBar(
				array( 
					//"<div id='cbxRuangGedung' style='flaot:left'>	".
					'Tampilkan : &nbsp; '.
					//"</div>".
					//"<div id='cbxRuangGedung' style='flaot:left'>	".
					"<span id='cbxKota'>".
					genComboBoxQry( 'fmPILKOTA', $fmPILKOTA, 
						"select * from ref_kotakec where kd_kec='0'  ",
						'kd_kota', 'nm_wilayah', '-- Semua Kota --',"style='width:140'" ).
					"</span"
				)				
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);
		return array('TampilOpt'=>$TampilOpt);
	}			
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		
		$arrKondisi = array();		
/*
		$arrKondisi[] = 'a='.$Main->Provinsi[0];
		$arrKondisi[] = 'b='.$Main->DEF_WILAYAH;
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = 'c='.$fmSKPD;
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = 'd='.$fmUNIT;
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = 'e='.$fmSUBUNIT;
		if(!($fmSEKSI=='' || $fmSEKSI=='00' || $fmSEKSI=='000') ) $arrKondisi[] = 'e1='.$fmSEKSI;
		
*/		
		 	
		$fmPILKOTA = $_REQUEST['fmPILKOTA'];
		if (!empty($fmPILKOTA)) $arrKondisi[] = "kd_kota='$fmPILKOTA'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " kd_kota,kd_kec ";
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
	
	

}
$refKotaKec = new KotaKecObj();


class KotaKecPilihObj  extends KotaKecObj{
	var $Prefix = 'refKotaKecPilih';
	var $elCurrPage="HalDefault";
	var $TblName = 'ref_kotakec'; //daftar
	var $TblName_Hapus = 'ref_kotakec';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='ref_kotakec.xls';
	var $Cetak_Judul = 'DAFTAR KOTA/KECAMATAN';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	/*function setPage_TitleDaftar(){
		return 'Daftar Pegawai';
	}*/	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Pilih Kota/Kecamatan';
	}
	
	function setMenuView(){
		return '';
	}		
	//*
	function genDaftarOpsi(){
		global $Ref, $Main;
		
		/*$fmSKPD = $_COOKIE['cofmSKPD'];			 
		$fmUNIT = $_COOKIE['cofmUNIT'];
		$fmSUBUNIT = $_COOKIE['cofmSUBUNIT'];*/
/*
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		
		$TampilOpt =			
			"<input type='hidden' id='PegawaiPilihSkpdfmSKPD' name='PegawaiPilihSkpdfmSKPD' value='$fmSKPD'>".
			"<input type='hidden' id='PegawaiPilihSkpdfmUNIT' name='PegawaiPilihSkpdfmUNIT'  value='$fmUNIT'>".
			"<input type='hidden' id='PegawaiPilihSkpdfmSUBUNIT' name='PegawaiPilihSkpdfmSUBUNIT'  value='$fmSUBUNIT'>".
			"<input type='hidden' id='PegawaiPilihSkpdfmSEKSI' name='PegawaiPilihSkpdfmSEKSI'  value='$fmSEKSI'>";
*/			
			$TampilOpt="";
			/*genFilterBar(
				array( 
					'Gedung &nbsp; '.
					"<span id='cbxRuangGedung' >".
					genComboBoxQry( 'fmPILGEDUNG', $fmPILGEDUNG, 
						"select * from ref_ruang where q='0000' and c='$fmSKPD' and d='$fmUNIT' and e='$fmSUBUNIT'  ",
						'p', 'nm_ruang', '-- Semua Gedung --',"style='width:140'" ).
					"</span>"
				)				
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);	*/		
		return array('TampilOpt'=>$TampilOpt);
	}		
	//*/	
	//*
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$cek='';
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
/*
		$arrKondisi = array();
		$arrKondisi[] = 'a='.$Main->Provinsi[0];
		$arrKondisi[] = 'b='.$Main->DEF_WILAYAH;
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
*/		
		/*$fmSKPD = $_COOKIE['cofmSKPD'];			 
		$fmUNIT = $_COOKIE['cofmUNIT'];
		$fmSUBUNIT = $_COOKIE['cofmSUBUNIT'];*/
/*
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00' || $fmSEKSI=='000') ) $arrKondisi[] = "e1='$fmSEKSI'";
		
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		//if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
*/
		$Kondisi="";
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " kd_kota,kd_kec ";		
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
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal, 'cek'=>$cek);		
	}
	//*/
	
	function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->Prefix.'_Form';
/*		
		$fmSKPD = $_REQUEST['fmSKPD'];
		$fmUNIT = $_REQUEST['fmUNIT'];
		$fmSUBUNIT = $_REQUEST['fmSUBUNIT'];
		$fmSEKSI = $_REQUEST['fmSEKSI'];
*/				
		$FormContent = $this->genDaftarInitial('', '', '','');
		$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						600,
						400,
						'Pilih Kota/Kecamatan',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='".$this->Prefix."_kdkotaplh' name='".$this->Prefix."_kdkotaplh' value='$this->form_kdkotaplh' >".
						"<input type='hidden' id='".$this->Prefix."_kdkecplh' name='".$this->Prefix."_kdkecplh' value='$this->form_kdkecplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
		);
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function set_selector_other2($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){				
			case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function genDaftarInitial($fmSKPD='', $fmUNIT='', $fmSUBUNIT='', $fmSEKSI=''){
		$vOpsi = $this->genDaftarOpsi();
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
	
}
$refKotaKecPilih = new KotaKecPilihObj();


?>