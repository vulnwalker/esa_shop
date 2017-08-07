<?php

class CBPBObj  extends DaftarObj2{	
	var $Prefix = 'CBPB';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_hargabarang_persediaan'; //daftar
	var $TblName_Hapus = 'ref_hargabarang_persediaan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f','g','h','i','j','tahun_anggaran','satuan',);
	var $FieldSum = array('harga');//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array(6);//berdasar mode
	var $FieldSum_Cp2 = array(0);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'FARMASI';
	var $PageIcon = 'images/administrator/images/payment.png';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = '';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'DPBP_form';
 		
	function genDaftarInitial(){
		$vOpsi = $this->genDaftarOpsi();
		$fmORDER18 = $_GET['lokasi'];
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				//$vOpsi['TampilOpt'].
			//"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}											
			

	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $hrg_supplier=$_REQUEST['hrg_supplier'];	
	 $qty_supplier=$_REQUEST['jml_obat_suplier'];	 
	 
			if($fmST == 0){
				if($err==''){ 
				$aqry = "UPDATE penerimaan_obat_d
				         set "." hrg='$hrg_supplier',
						 qty='$qty_supplier'". 
						 "WHERE Id='".$idplh."'";	$cek .= $aqry;
				mysql_query($aqry);			
				
				}
			}elseif($fmST == 1){						
				if($err==''){
				
					}
			}else{
			if($err==''){ 
					
				}
			} //end else
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){	
				
		case 'formCari':{				
			$fm = $this->setFormCari();				
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
		
		case 'formBaru2':{				
			$fm = $this->setFormBaru2();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formSetuju':{				
			$fm = $this->setFormSetuju();				
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
	   
	   	case 'pilihobat':{
		//simpan data pemeriksaan detail

			$idp = $_REQUEST['idp']; 
			//$penjamin = $_REQUEST['penjamin']; 
			//$qtyr = $_REQUEST['qtyr'];
			//$dosis = $_REQUEST['dosis'];
			//$cara_pakai = $_REQUEST['cara_pakai'];
			$qty = $_REQUEST['qty'];
			$harga_obat = $_REQUEST['harga'];
			$ref_pilihobat = $_REQUEST['FarmasiPenerimaanDetail_daftarpilih'];
			$arrpo = explode(',',$ref_pilihobat);
			$valuearr = array();

			for($i = 0; $i<count($arrpo); $i++){ 

				$get = "select * from ref_obat where b = '".$arrpo[$i]."' ";$cek.='pilih obat = '.$get;
				//$p = mysql_fetch_array(mysql_query("SELECT * from ref_penjamin where Id='".$penjamin."'"));$cek .= $p;
			
				$rss = mysql_query($get);

				while($row=mysql_fetch_array($rss)){
					
					//$harga_obat = $row['hna_baru'];//-($row['harga_jual']*$p['indexobat']/100); $cek.= $harga_obatdis;
					//$$qty_sedia = $p['jml'];
					//$ref_idsat = $row['ref_idsatuan'];
					$konv_kemasan=$row['konv_kemasan'];
					$hna_baru=$row['hna_baru'];
			
				}
					//} //end switch

					$valuearr[]= "('$idp','".$arrpo[$i]."','$qty','$harga_obat','$konv_kemasan','$hna_baru')";
			}//end for	
			$valuestr = join(',',$valuearr);
			//if( $err=='' && $harga_obat =='' ){$err= 'Harga belum diisi !!';} 
			//else
			if( $err=='' && $qty =='' ){$err= 'Qty belum diisi !!';}
			else {
			$querypr = "INSERT INTO penerimaan_obat_d(ref_idterima,kd_obat,qty,hrg,konv_kemasan,hna_baru_prev) VALUES".$valuestr;$cek.='Query prr='.$querypr;
			}
			mysql_query($querypr);
		break;
	   }
	   	
		case 'disetujui':{
			//simpan data pemeriksaan detail
	 		$uid = $HTTP_COOKIE_VARS['coID'];
	 		//$cek = ''; $err=''; $content=''; $json=TRUE;
			$idr = $_REQUEST['idr'];
			$jml = $_REQUEST['jml'];
			$aqry1 = "SELECT * FROM perencanaan_obat_d WHERE Id=$idr "; $cek .= $aqry1;
			$dt=mysql_fetch_array(mysql_query($aqry1));
			if($err=='' && $jml>$dt['qty_rencana'] ) 
			{
				$err= 'Jumlah yang disetujui tidak boleh lebih dari Qty Rencana !!';
			}
			else
			{
				$aqry = "UPDATE perencanaan_obat_d
				         set "." qty_disetujui='$jml'". 
						 "WHERE Id='".$idr."'";	$cek .= $aqry;
				mysql_query($aqry);	
			}
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
			//"<script type='text/javascript' src='js/pengadaanpersediaan/cbpb.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/pengadaanbarangpersediaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	//form ==================================
	function setFormCari(){
		$dt=array();
		$this->form_fmST = 0;
		$dt['tgl_kunjungan'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		//get data 
		$bulan=date('Y-m-')."1";
		$aqry = "select * from v1_penerimaandetail where Id='".$this->form_idplh."'"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm1($dt);			
		
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
 	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 $idp=$_REQUEST['idp'];	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 750;
	 $this->form_height = 400;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'PILIH BARANG';
	  }else{}
	

	   //items ----------------------
	  $this->form_fields = array(	
	  	 						 					 
			'daftarfarmasipilihbarang' => array( 

						'label'=>'',

						 'value'=>"<div id='daftarpilihbarang' style='height:5px'></div>".
						 "<input type='hidden' value='' id='".$this->Prefix."_daftarpilih' name='".$this->Prefix."_daftarpilih'>", 

						 'type'=>'merge'

					 )										 
						 					 
			);
		//tombol
		$this->form_menubawah =
			"Harga Barang: <input type='text' name='harga' value='' id='harga' size='10px'>&nbsp&nbsp".
			"Jumlah Barang: <input type='text' name='qty' value='1' id='qty' size='5px'>&nbsp&nbsp".			
			"<input type='hidden' name='idp' id='idp' value='$idp'>".
			//"<input type='hidden' name='penjamin' id='penjamin' value='$penjamin'>".
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".Pilih_obat()' title='Pilih obat' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setForm1($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
		
	 $json = TRUE;	//$ErrMsg = 'tes';
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 450;
	 $this->form_height = 225;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'JUMLAH YANG DIEDIT';
		$id=$dt['id'];
	  }else{}
	

	   //items ----------------------
	  $this->form_fields = array(	
	  	 	'namabarang' => array( 
								'label'=>'Nama Barang',
								'labelWidth'=>125, 
								'value'=>$dt['uraian'], 
								'type'=>'text',
								'id'=>'barang',
								'param'=>"style='width:250ppx' size=34px readonly"
									 ),	
			'hrg_obat' => array( 
								'label'=>'Harga obat sekarang',
								'labelWidth'=>125, 
								'value'=>$dt['hrg'], 
								'type'=>'text',
								'id'=>'terima',
								'param'=>"size=10px readonly"
									 ),				 
			'hrg_supplier' => array( 
								'label'=>'Harga Dari supplier',
								'labelWidth'=>125, 
								'value'=>$dt['hrg'], 
								'type'=>'text',
								'id'=>'edit',
								'param'=>"size=10px"
									 ),	
			'jml_obat' => array( 
								'label'=>'Jumlah obat sekarang',
								'labelWidth'=>125, 
								'value'=>$dt['qty'], 
								'type'=>'text',
								'id'=>'qty_terima',
								'param'=>"size=3px readonly"
									 ),				 
			'jml_obat_suplier' => array( 
								'label'=>'Jumlah Dari supplier',
								'labelWidth'=>125, 
								'value'=>$dt['qty'], 
								'type'=>'text',
								'id'=>'qty_edit',
								'param'=>"size=3px"
									 ),
			'discount' => array( 
								'label'=>'Discount',
								'labelWidth'=>125, 
								'value'=>$dt['discount'], 
								'type'=>'text',
								'id'=>'discount',
								'param'=>"size=3px"
								),
			'expire' => array( 
						 'label'=>'Expire',
						 'labelWidth'=>125, 
						 'value'=>createEntryTgl3($dt['expire'],'expire', false,'')
						 ),
			'batch' => array( 
								'label'=>'Batch',
								'labelWidth'=>125, 
								'value'=>$dt['batch'], 
								'type'=>'text',
								'id'=>'batch',
								'param'=>"size=20px"
								),			 											 								 						 
						 					 
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='idr' id='idr' value='$id'>".
			//"<input type='hidden' name='penjamin' id='penjamin' value='$penjamin'>".
			"<input type='button' value='edit' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
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
  	   <th class='th01' width='20' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='100'>Tahun Anggaran</th>
	   <th class='th01' width='200'>Nama Barang</th>
	   <th class='th01' width='200'>Merk/Type/Spesifikasi</th>
	   <th class='th01' width='100'>Satuan</th>
	   <th class='th01' width='100'>Harga</th>
	   </tr>
	   </thead>";
	
		return $headerTable;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	//Qty disetujui		
	if($isi['qty_disetujui'] !='0'){
	 	$qty_disetujui=$isi['qty_disetujui'];
	 }else{
	 	$qty_disetujui=$isi['qty_rencana'];
	 }
	 $hrg_beli=$isi['hrg']*$isi['konv_kemasan'];
	 $fg=mysql_fetch_array(mysql_query("select f, g, nama_barang from ref_barang_persediaan where f =".$isi['f']." and g=".$isi['g'].""));
	 $h=mysql_fetch_array(mysql_query("select h, nama_merk from ref_merk_persediaan where h=".$isi['h'].""));	 
	 $i=mysql_fetch_array(mysql_query("select i, nama_type from ref_type_persediaan where i=".$isi['i'].""));
	 $j=mysql_fetch_array(mysql_query("select j, nama_spec from ref_spec_persediaan where j=".$isi['j'].""));	 	 	 
	 $s=mysql_fetch_array(mysql_query("select Id, nama_satuan from ref_satuan_persediaan where Id=".$isi['satuan'].""));	 	 	 
	$spesifikasi=$h['nama_merk'].' / '.$i['nama_type'].' / '.$j['nama_spec'];
	 
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="center" width="100"',$isi['tahun_anggaran']);
	 $Koloms[] = array('align="left" width="200"',$fg['nama_barang']);
	 $Koloms[] = array('align="left" width="400"',$spesifikasi);
	 $Koloms[] = array('align="left" width="200"',$s['nama_satuan']);
	 $Koloms[] = array('align="right" width="100"',number_format($isi['harga'],2,',','.'));

	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	$idp = $_REQUEST['idp'];	
	$penjamin = $_REQUEST['penjamin'];	
				
	$TampilOpt = 
			"<input type='hidden' value='".$idp."' name='idp' id='idp'>";
			//;
				/*genFilterBar(
				''
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);*/
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		
		$idp = $_REQUEST['idp'];			
		if(!empty($ref_idkunjp)) $arrKondisi[]= " ref_idkunjp='$ref_idkunjp'";
		if(!empty($idp)) $arrKondisi[] = " ref_idterima = '$idp'"; 		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '1': $arrOrders[] = " tgl_kunjungan $Asc1 " ;break;
			case '2': $arrOrders[] = " no_rm $Asc1 " ;break;
			case '3': $arrOrders[] = " nama_pasien $Asc1 " ;break;
		}
		
	$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//$Order ="";
		//limit --------------------------------------
		/**$HalDefault=cekPOST($this->Prefix.'_hal',1);	//Cat:Settingan Lama				
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		**/
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$CBPB = new CBPBObj();

?>