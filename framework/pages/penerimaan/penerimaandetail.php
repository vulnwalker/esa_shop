<?php

class PenerimaanDetailObj  extends DaftarObj2{	
	var $Prefix = 'PenerimaanDetail';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'penerimaan'; //daftar
	var $TblName_Hapus = 'penerimaan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Detail Penerimaan Barang';
	var $PageIcon = 'images/administrator/images/forms.png';
	var $pagePerHal ='10';
	var $fileNameExcel='PenerimaanDetail.xls';
	var $namaModulCetak='Penerimaan Detail';
	var $Cetak_Judul = 'Penerimaan Detail';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'Penerimaan_form';
	var $total_pagu = 0;
	var $total_pagu_mtg = 0;
	var $Sisa = 0;
	
		
	function setTitle(){
		return '';
	}
			
	function setMenuView(){
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar per Halaman")."</td>";			
	}
	
	function setMenuEdit(){
		
		return '';
	}
	
	function setTopBar(){
	   	return '';
	}
	
	function setPage_HeaderOther(){
		return '';
	}
				
	function set_selector_other($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  $cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
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
		
		case 'formDistribusi':{				
			$fm = $this->setFormDist();				
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
		
		case 'simpan2':{
			$get= $this->simpan2();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
				
		case 'hapus':{ //untuk ref_kota
			$idplh= $_REQUEST['Id'];		
			$get= $this->Hapus();
			$err= $get['err']; 
			$cek = $get['cek'];
			$content = $get['content'];
			$json=TRUE;	
			break;
		}
		
		case 'formCari':{				
			$fm = $this->setFormCari();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'autocomplete_barang_getdata':{
				$json = FALSE;
				$fm = $this->autocomplete_barang_getdata();
				break;
		}
		
		case 'getdata':{
				$ids = $_REQUEST['cidBI'];//735477
		
				//if($err=='' && $ids[0] == '') $err = 'Barang belum dipilih!';
				$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$ids[0]."'")) ;
				$kdbrg = $bi['f'].'.'.$bi['g'].'.'.$bi['h'].'.'.$bi['i'].'.'.$bi['j'];
								
				//Kode barang
				$kd_barang = str_replace('.','',$kdbrg);
				$br = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j) = '$kd_barang'"));
				
				//Kode Akun
				$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
				$kueri1="select max(thn_akun) as thn_akun from ref_jurnal where thn_akun <= '$fmThnAnggaran'";
				$tmax = mysql_fetch_array(mysql_query($kueri1));
				$kueri="select * from ref_jurnal 
						where thn_akun = '".$tmax['thn_akun']."' 
						and ka='".$br['ka']."' and kb='".$br['kb']."' 
						and kc='".$br['kc']."' and kd='".$br['kd']."'
						and ke='".$br['ke']."' and kf='".$br['kf']."'"; //echo "$kueri";
				$row=mysql_fetch_array(mysql_query($kueri));
				$kdAkun =$row['ka'].".".$row['kb'].".".$row['kc'].".".$row['kd'].".".$row['ke'].".".$row['kf'];
				
				//spesifikasi&alamat
				if($bi['f']=='01'){
					$aqry = "select * from kib_a where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				if($bi['f']=='02'){
					$aqry = "select * from kib_b where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['merk'];
				}
				if($bi['f']=='03'){
					$aqry = "select * from kib_c where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				if($bi['f']=='04'){
					$aqry = "select * from kib_d where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				if($bi['f']=='05'){
					$aqry = "select * from kib_e where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['buku_judul']."/".$arrdet['buku_spesifikasi'];
				}
				if($bi['f']=='06'){
					$aqry = "select * from kib_f where 
						idbi='".$bi['id']."' ";
						$qry=mysql_query($aqry);			
						$arrdet=mysql_fetch_array($qry);
					$merk=$arrdet['alamat'];
				}
				
				//harga buku
				//$hb = mysql_fetch_array(mysql_query("select get_nilai_buku('".$bi['id']."',now(),'0') as harga_buku"));
				$hb = getNilaiBuku($bi['id'],date('Y-m-d'),0);
				
				$content = array('idbi'=>$bi['id'],
								 'idbi_awal'=>$bi['idawal'],
								 'kd_barang'=>$kdbrg,
								 'nm_barang'=>$br['nm_barang'],
								 'a1'=>$bi['a1'],
								 'a'=>$bi['a'],
								 'b'=>$bi['b'],
								 'c'=>$bi['c'],
								 'd'=>$bi['d'],
								 'e'=>$bi['e'],
								 'e1'=>$bi['e1']);	
		break;
	    }
		
		case 'UnitRefresh':{
				$fmc = cekPOST('c');
				$fmd = cekPOST('d');
				$fme = cekPOST('fmSKPDUnit_form');
				
				
				$content=$this->cmbQueryUnit('fmSKPDUnit_form',$fme,'','onchange=.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',TRUE,$editunit);	
				
				
			break;
		}
			
		case 'UnitAfter':{
			//$fmSKPDUnit = cekPOST('fmSKPDUnit_form');
			//setcookie('cofmSUBUNIT',$fmSKPDUnit);
			$ref_iddkb = cekPOST('ref_iddkb');
			$fme1 = cekPOST('fmSKPDSubUnit_form');
			
			$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','','--- Pilih Sub Unit ---','000',FALSE,'');	
				
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
   
   function cmbQueryUnit($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE,$edit='') {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 if($edit==""){
	 	$fmSKPDBidang = cekPOST('c')!=$vAtas? cekPOST('c'): $_REQUEST['c'];
		$fmSKPDSkpd = cekPOST('d')!=$vAtas? cekPOST('d'): $_REQUEST['d'];
	 }else{
	 	$xplode=explode('.',$edit);
		$fmSKPDBidang=$xplode[0];
		$fmSKPDSkpd=$xplode[1];
	 }
		
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d='$fmSKPDSkpd' and e!='00' and e1='000' GROUP by e";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDUnit='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['e'] ==  $value ? "selected" : "";
				if ($nmSKPDUnit=='' ) $nmSKPDUnit =  $value == $Hasil['e'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[e]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDUnit <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySubUnit($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE,$edit='') {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 if($edit==""){
	 	$fmSKPDBidang = cekPOST('c')!=$vAtas? cekPOST('c'): $HTTP_COOKIE_VARS['cofmSKPD'];
		$fmSKPDSkpd = cekPOST('d')!=$vAtas? cekPOST('d'): $HTTP_COOKIE_VARS['cofmUNIT'];
		$fmSKPDUnit = cekPOST('fmSKPDUnit_form')!=$vAtas? cekPOST('fmSKPDUnit_form'): $HTTP_COOKIE_VARS['cofmSUBUNIT'];
	}else{
	 	$xplode=explode('.',$edit);
		$fmSKPDBidang=$xplode[0];
		$fmSKPDSkpd=$xplode[1];
		$fmSKPDUnit=$xplode[2];
	 }
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d='$fmSKPDSkpd' and e='$fmSKPDUnit' and e1!='000' GROUP by e1";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDUnit='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['e1'] ==  $value ? "selected" : "";
				if ($nmSKPDUnit=='' ) $nmSKPDUnit =  $value == $Hasil['e1'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[e1]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDUnit <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function Hapus($ids){ //validasi hapus data mak
		$err=''; $cek=''; $content='';
		for($i = 0; $i<count($ids); $i++){
				
				//ambil jumlah data detail
				$row = mysql_fetch_array(mysql_query("select * from t_penerimaan_d where Id='".$ids[$i]."' "));
				
				$aqry2 = "DELETE FROM t_penerimaan_d WHERE Id='".$ids[$i]."' ";$cek.=$aqry2;
				$qry2 = mysql_query($aqry2);
				
				$aqry3 = "DELETE FROM jurnal WHERE ref_id='".$ids[$i]."' ";$cek.=$aqry3;
				$qry3 = mysql_query($aqry3);
				
				$kueri="select count(*) as cnt from t_penerimaan_d where ref_idpenerimaan='".$row['ref_idpenerimaan']."'";
				$get = mysql_fetch_array(mysql_query($kueri)); $cek.=$kueri;
				$content->jml_data = $get['cnt'];

		}
		return array('err'=>$err,'cek'=>$cek,'content'=>$content);
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
		 "<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penatausaha.js' language='JavaScript' ></script>".	
		 "<script type='text/javascript' src='js/master/ref_aset/refjurnal.js' language='JavaScript' ></script>".	
		 "<script type='text/javascript' src='js/master/ref_aset/refbarang.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/pemindahtangan/pemindahtangan_rencana.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/distribusidetail.js' language='JavaScript' ></script>".
		 "<script type='text/javascript' src='js/penerimaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			
			 
			
			$scriptload;
	}
		
	//form ===================================
	function setFormBaru(){
		$dt=array();
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 1;		
		
		
			//get data
			$err=''; 
			$aqry = "SELECT * FROM  t_penerimaan_d WHERE Id='".$this->form_idplh."' "; $cek.=$aqry;
			$dt = mysql_fetch_array(mysql_query($aqry));
			$row= mysql_fetch_array(mysql_query("select nama from ref_barang where Id='".$dt['ref_idbarang']."' "));
			$dt['nm_barang'] = $row['nama'];		
			$fm = $this->setForm($dt);

		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormDist(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$this->form_fmST = 1;	
		
		$aqry = "SELECT * FROM  penerimaan WHERE Id='".$this->form_idplh."' "; $cek.=$aqry;
		$row = mysql_fetch_array(mysql_query($aqry));	
		$dt['ref_idterimaba'] = $row['ref_idba'];
		$dt['a'] = $row['a'];
		$dt['b'] = $row['b'];
		$dt['c'] = $row['c'];
		$dt['d'] = $row['d'];
		
		$fm = $this->setFormDistribusi($dt);

		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}
		
	
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';
	 $this->form_width = 650;
	 $this->form_height = 220;
	 $ref_jenis=$_REQUEST['ref_jenis'];
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Tambah Barang';
		$idp = $_REQUEST['idp'];
		$c = $_REQUEST['c'];
		$d = $_REQUEST['d'];
	  }else{
		$this->form_caption = 'Edit Barang';			
		$idp= $dt['ref_idba'];
		$c = $dt['c'];
		$d = $dt['d'];			
	  }
	  
	  $kode_brg = $dt['f'].'.'.$dt['g'].'.'.$dt['h'].'.'.$dt['i'].'.'.$dt['j'];
	 //items ----------------------
	  $this->form_fields = array(
	  		
			'nm_barang' => array( 
						'label'=>'Nama Barang',
						'labelWidth'=>150, 
						'value'=>"<input type='text' name='fmIDBARANG' id='fmIDBARANG' size='15' value='$kode_brg' readonly=''>".
								 "&nbsp;<input type='text' name='fmNMBARANG' id='fmNMBARANG' size='50' value='".$dt['nm_barang']."' readonly=''>".
								 "&nbsp;<input type='button' value='Cari' onclick=\"".$this->Prefix.".caribarang1()\" title='Cari Barang' >"			 
						),
	  		
			'jml' => array( 
						'label'=>'Jumlah',
						'labelWidth'=>100, 
						'value'=>"<input type='text' name='jml' id='jml' value='".$dt['jml_barang']."' size='15' onkeypress='return isNumberKey(event)' >",
						 ),
						 
			'harga_satuan' => array( 
								'label'=>'Harga Satuan',
								'labelWidth'=>150, 
								'value'=>"<input type='text' name='harga_satuan' id='harga_satuan' size='15' value='".$dt['harga']."' style='text-align:right;'>"
									 ),
			
			'merk' => array( 
						'label'=>'Merk/Type',
						'labelWidth'=>100, 
						'value'=>"<textarea name='merk' id='merk' style='margin: 0px; width: 200px; height: 55px;'>".$dt['merk_barang']."</textarea>",
						 ),
						 
			'ket' => array( 
						'label'=>'Keterangan',
						'labelWidth'=>100, 
						'value'=>"<textarea name='ket' id='ket' style='margin: 0px; width: 200px; height: 55px;'>{$dt['ket']}</textarea>"			 
						 ),	

			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='idp' id='idp' value='".$idp."' >".
			/*"<input type='text' name='a1' id='a1' value='".$dt['a1']."' >".
			"<input type='text' name='a' id='a' value='".$dt['a']."' >".
			"<input type='text' name='b' id='b' value='".$dt['b']."' >".*/
			"<input type='hidden' name='c' id='c' value='".$c."' >".
			"<input type='hidden' name='d' id='d' value='".$d."' >".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormDistribusi($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';
	 $this->form_width = 400;
	 $this->form_height = 250;
	 $ref_jenis=$_REQUEST['ref_jenis'];
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Distribusi';
		//$idp= $_REQUEST['idp'];
	  }else{
		$this->form_caption = 'Edit Distribusi';			
		//$Id = $dt['Id'];			
	  }
	  
	  //$editunit= $dt['e'] != '' ? $dt['c'].".".$dt['d']:'';
	 // $editsubunit=$dt['e1'] != '' ? $dt['c'].".".$dt['d'].".".$dt['e']:'';
	  
	  //unit & subunit
	  $pilihUnit=$this->cmbQueryUnit('fmSKPDUnit_form',$dt['e'],'','onchange=PenerimaanDetail.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',FALSE,$editunit);
	  $pilihSubUnit=$this->cmbQuerySubUnit('fmSKPDSubUnit_form',$dt['e1'],'',' '.$disabled1,'--- Pilih Sub Unit ---','000',FALSE,$editsubunit) ;
	  
	 //items ----------------------
	  $this->form_fields = array(
	  		
			'unit' => array( 'label'=>'UNIT', 
								'value'=>'<div id=Unit_formdiv>'.$pilihUnit.'</div>', 
								'type'=>'', 
								'row_params'=>"height='21' valign='top'"
							),
							
			'subunit' => array( 'label'=>'SUBUNIT', 
								'value'=>'<div id=SubUnit_formdiv>'.$pilihSubUnit.'</div>', 
								'type'=>'', 
								'row_params'=>"height='21' valign='top'"
							),
	  		
			'bast' => array(
							'label'=>'', 
							'value'=>'BAST', 
							'type'=>'merge',
							'row_params'=>" height='21'"
							),
			'ba_no' => array(
							'label'=>'&nbsp;&nbsp;Nomor', 
							'value'=>$dt['ba_no'], 
							'type'=>'text'
			),
			'ba_tgl2' => array(
							'label'=>'&nbsp;&nbsp;Tanggal', 
							'value'=> createEntryTgl3($dt['ba_tgl'],'ba_tgl2', false,''), 
							'type'=>''
			),	
			
			'jml' => array( 
						'label'=>'Jumlah',
						'labelWidth'=>50, 
						'value'=>"<input type='text' name='jml' id='jml' value='".$dt['jml_barang']."' size='15' onkeypress='return isNumberKey(event)' >",
						 ),
						 
			'harga_satuan' => array( 
								'label'=>'Harga Satuan',
								'labelWidth'=>50, 
								'value'=>"<input type='text' name='harga_satuan' id='harga_satuan' size='15' value='".$dt['harga_satuan']."' style='text-align:right;'>"
									 ),
			
			'ket' => array( 
						'label'=>'Keterangan',
						'labelWidth'=>50, 
						'value'=>"<textarea name='ket' id='ket' style='margin: 0px; width: 200px; height: 55px;'>{$dt['ket']}</textarea>"			 
						 ),	
			
				
						 

			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='ref_idterima' id='ref_idterima' value='".$this->form_idplh."' >".
			"<input type='hidden' name='ref_idterimaba' id='ref_idterimaba' value='".$dt['ref_idterimaba']."' >".
			"<input type='hidden' name='a' id='a' value='".$dt['a']."' >".
			"<input type='hidden' name='b' id='b' value='".$dt['b']."' >".
			"<input type='hidden' name='c' id='c' value='".$dt['c']."' >".
			"<input type='hidden' name='d' id='d' value='".$dt['d']."' >".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan2()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormCari(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$cek = 'tes';
				
		$dt=array();
		$this->form_fmST = 0;
		//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		//$fm = $this->setFormCr($dt);
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		//$id_penggunaan=$_REQUEST['id_penggunaan'];
		
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Barang'; //judul form
		$this->form_fields = array(	
			/*'skpd' => array( 
				'label'=>'',
				'value'=>
					"<table width=\"200\" class=\"adminform\">	<tr>		
					<td width=\"200\" valign=\"top\">" . 					
						WilSKPD_ajx3($this->Prefix.'CariSkpd') . 
						//WilSKPD_ajx('Skpd') . 						
					"</td>" . 
					"</tr></table>", 
				'type'=>'merge'
			),	*/		
			'div_detailcaribarang' => array( 
				'label'=>'',
				'value'=>"<div id='div_detailcaribarang' style='height:5px'></div>", 
				'type'=>'merge'
			)
		);
		
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".PilihBarang()' >".
			"<input type='button' value='Close' onclick ='".$this->Prefix.".CloseCariBarang()' >";
		
		//$form = //$this->genForm();		
		$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,
					$this->form_menu_bawah_height,
					'',1
					).
				"</form>";
				
		$content = $form;
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	//simpan penerimaan barang
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->DEF_PROPINSI;
	 $b = $Main->DEF_WILAYAH;
	 
	 $idp = $_REQUEST['idp'];
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $fmIDBARANG = explode('.',$_REQUEST['fmIDBARANG']);
		$f = $fmIDBARANG[0];
		$g = $fmIDBARANG[1];
		$h = $fmIDBARANG[2];
		$i = $fmIDBARANG[3];
		$j = $fmIDBARANG[4];
	 $fmNMBARANG = $_REQUEST['fmNMBARANG'];
     $jml = $_REQUEST['jml'];
     $harga_satuan = $_REQUEST['harga_satuan'];
	 $jml_harga = $jml * $harga_satuan;
     $merk = $_REQUEST['merk'];
     $ket = $_REQUEST['ket'];
	 
		if($err=='' && $fmIDBARANG=='')$err='Kode Barang belum diisi !';
		if($err=='' && $fmNMBARANG=='')$err='Nama Barang belum diisi !';
		if($err=='' && $jml=='')$err='Jumlah belum diisi !';
		if($err=='' && $harga_satuan=='')$err='Harga Satuan belum diisi !';
		if($err=='' && $merk=='')$err='Merk/Type belum diisi !';
	
			if($fmST == 0){
				
				if($err==''){ 
				$aqry="INSERT into penerimaan(a1,a,b,c,d,f,g,h,i,j,nm_barang,jml_barang,harga,jml_harga,merk_barang,ket,ref_idba,uid,tgl_update)".
				" values('$a1','$a','$b','$c','$d','$f','$g','$h','$i','$j','$fmNMBARANG','$jml','$harga_satuan','$jml_harga','$merk','$ket','$idp','$uid',now())"; $cek.=$aqry;
				$qry=mysql_query($aqry);	
				
				/*$id = mysql_insert_id();
				$aqry2 = "INSERT INTO jurnal (tgl,ref_id,ref_idbarang,jml,jns) VALUES (now(),'$id','$ref_idbarang','$jml','1')";
				$cek .= $aqry2;
				$qry1 = mysql_query($aqry2);*/			
				}
				
			}elseif($fmST == 1){
			
				if($err==''){ 
				$aqry2 = "UPDATE t_penerimaan_d set ref_idbarang='$ref_idbarang',spesifikasi='$spesifikasi',jml='$jml',ref_idsatuan='$satuan' WHERE Id='".$idplh."'";$cek.= $aqry;
				$qry2=mysql_query($aqry2);

				/*$aqry2 = "UPDATE jurnal set
							  tgl=now(),
							  ref_idbarang='$ref_idbarang',
							  jml='$jml'
							  WHERE ref_id='".$idplh."' and jns=1";
				$cek .= $aqry2;
				$qry2 = mysql_query($aqry2);*/	
				}
				
			}
								
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	//simpan distribusi
	function simpan2(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->DEF_PROPINSI;
	 $b = $Main->DEF_WILAYAH;
	 
	 $ref_idterima = $_REQUEST['ref_idterima'];
	 $ref_idterimaba = $_REQUEST['ref_idterimaba'];
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['fmSKPDUnit_form'];
	 $e1 = $_REQUEST['fmSKPDSubUnit_form'];
	 $ba_no = $_REQUEST['ba_no'];
	 $ba_tgl = $_REQUEST['ba_tgl2'];
     $jml = $_REQUEST['jml'];
     $harga_satuan = $_REQUEST['harga_satuan'];
     $jml_harga = $jml*$harga_satuan;
     $ket = $_REQUEST['ket'];
	 
		/*
		if($err=='' && $fmIDBARANG=='')$err='Kode Barang belum diisi !';
		if($err=='' && $fmNMBARANG=='')$err='Nama Barang belum diisi !';
		if($err=='' && $jml=='')$err='Jumlah belum diisi !';
		if($err=='' && $harga_satuan=='')$err='Harga Satuan belum diisi !';
		if($err=='' && $merk=='')$err='Merk/Type belum diisi !';
		*/
		$row = mysql_fetch_array(mysql_query("select jml_barang from penerimaan where id='$ref_idterima'"));
		if($err=='' && $jml > $row['jml_barang']) $err='Jumlah barang tidak mencukupi !';
	
			if($fmST == 0){
				
				if($err==''){ 
				$aqry="INSERT into pengeluaran(a,b,c,d,e,e1,ba_no,ba_tgl,jml_barang,harga,jml_harga,ket,ref_idterima,ref_idterimaba,uid,tgl_update,blm_posting)".
				" values('$a','$b','$c','$d','$e','$e1','$ba_no','$ba_tgl','$jml','$harga_satuan','$jml_harga','$ket','$ref_idterima','$ref_idterimaba','$uid',now(),'$jml')"; $cek.=$aqry;
				$qry=mysql_query($aqry);	$cek .= $aqry;
				
				/*$id = mysql_insert_id();
				$aqry2 = "INSERT INTO jurnal (tgl,ref_id,ref_idbarang,jml,jns) VALUES (now(),'$id','$ref_idbarang','$jml','1')";
				$cek .= $aqry2;
				$qry1 = mysql_query($aqry2);*/			
				}
				
			}elseif($fmST == 1){
			
				if($err==''){ 
				$aqry="INSERT into pengeluaran(a,b,c,d,e,e1,ba_no,ba_tgl,jml_barang,harga,jml_harga,ket,ref_idterima,ref_idterimaba,uid,tgl_update,blm_posting)".
				" values('$a','$b','$c','$d','$e','$e1','$ba_no','$ba_tgl','$jml','$harga_satuan','$jml_harga','$ket','$ref_idterima','$ref_idterimaba','$uid',now(),'$jml')"; $cek.=$aqry;
				$qry=mysql_query($aqry);	$cek .= $aqry;

				/*$aqry2 = "UPDATE jurnal set
							  tgl=now(),
							  ref_idbarang='$ref_idbarang',
							  jml='$jml'
							  WHERE ref_id='".$idplh."' and jns=1";
				$cek .= $aqry2;
				$qry2 = mysql_query($aqry2);*/	
				}
				
			}
								
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 
	 $headerTable =
	 "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox		
	   <th class='th01'>Kode Barang/ Nama Barang</th>
	   <th class='th01'>Merk/Type/Spek/Alamat</th>
	   <th class='th01'>Jumlah Barang</th>
	   <th class='th01'>Harga Satuan</th>
	   <th class='th01'>Jumlah Harga</th>
	   <th class='th01'>Ket</th>
	   </tr>
	  </thead>";
		return $headerTable;
	}
	
	function setCekBox($cb, $KeyValueStr, $isi){
		$hsl = '';
		if($KeyValueStr!=''){
			
		
			$hsl = "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');".
					"$this->Prefix.cbxPilih(this);PenerimaanDetail.AfterPilihCbx(this) \" />";					
		}
		return $hsl;
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 
	 $kdbarang = $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
	 $nmbarang = $isi['nm_barang'];
	 
	 $jml_harga = $isi['jml_barang']*$isi['harga']; 
	 
	 $Koloms = array();
	 $Koloms[] = array('align="right"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align=left',$kdbarang.'/<br>'.$nmbarang);
	 $Koloms[] = array('align=left',$isi['merk_barang']);
	 $Koloms[] = array('align=right',$isi['jml_barang']);
	 $Koloms[] = array('align=right',number_format( $isi['harga'] ,2,',','.'));
	 $Koloms[] = array('align=right',number_format( $isi['jml_harga'] ,2,',','.'));
	 $Koloms[] = array('align=left',$isi['ket']);
	 
	 
	 return $Koloms ;
	 
	}
	
	function genCetak($xls= FALSE, $Mode=''){
		global $Main;
		$Bidang = $_REQUEST['bidang']; 
		$Bagian = $_REQUEST['bagian']; 
		$SubBagian = $_REQUEST['subbagian'];
		$idp = $_REQUEST['idp'];
		/*
		<style>
		.nfmt1 {mso-number-format:'\#\,\#\#0_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt2 {mso-number-format:'0\.00_';}
		.nfmt3 {mso-number-format:'00000';}
		.nfmt4 {mso-number-format:'\#\,\#\#0.00_\)\;\[Red\]\\(\#\,\#\#0\\)';}
		.nfmt5 {mso-number-format:'\@';} 
		table {mso-displayed-decimal-separator:'\.';
			mso-displayed-thousand-separator:'\,';}	
		br {mso-data-placement:same-cell;}	
		</style>*/ 	
		//if($this->cetak_xls){
		if($xls){
			header("Content-type: application/msexcel");
			header("Content-Disposition: attachment; filename=$this->fileNameExcel");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		
		//$css = $this->cetak_xls	? 
		$css = $xls	? 
			"<style>
			.nfmt5 {mso-number-format:'\@';}			
			</style>":
			"<link rel=\"stylesheet\" href=\"css/template_css.css\" type=\"text/css\" />";
		echo 
			"<html>".
				"<head>
					<title>$Main->Judul</title>
					$css					
					$this->Cetak_OtherHTMLHead
				</head>".
			"<body >
			<form name='adminForm' id='adminForm' method='post' action=''>
			<div style='width:$this->Cetak_WIDTH'>
			<table class=\"rangkacetak\" style='width:$this->Cetak_WIDTH'>
			<tr><td valign=\"top\">".
				//$this->cetak_xls.		
				$this->setCetak_Header($Mode).//$this->Cetak_Header.//
				"<div id='cntTerimaKondisi'>".
					//$TampilOpt['TampilOpt'].
				"</div>
				<div id='cntTerimaDaftar' >";			
		
		$Opsi = $this->getDaftarOpsi($this->Cetak_Mode);
			//echo ',Kondisi='.$Opsi['Kondisi'].',Order='.$Opsi['Order'].',hal='.$_POST['HalDefault'].
			//	',limit='.$Opsi['Limit'].',NoAwal='.$Opsi['NoAwal'].',';								
			//echo 'vkondisi='.$$Opsi[vKondisi; 
		$row = mysql_fetch_array(mysql_query("select nm_bagian from ref_bagian where kode like '$Bidang.00.00'"));
		$row2 = mysql_fetch_array(mysql_query("select nm_bagian from ref_bagian where kode like '$Bidang.$Bagian.00'"));
		$row3 = mysql_fetch_array(mysql_query("select nm_bagian from ref_bagian where kode like '$Bidang.$Bagian.$SubBagian'"));
		$brg = mysql_fetch_array(mysql_query("SELECT
											  `ref_barang`.`nama` AS `nm_barang`, `ref_satuan`.`nama` AS `satuan`,
											  `ref_jenis`.`nama` AS `jenis`
											FROM
											  `ref_barang` LEFT JOIN
											  `ref_satuan` ON `ref_barang`.`ref_idsatuan` = `ref_satuan`.`Id` LEFT JOIN
											  `ref_jenis` ON `ref_barang`.`ref_idjenis` = `ref_jenis`.`Id`  
											where `ref_barang`.`Id` = 2"));
		$bid = $row['nm_bagian'];
		$bag = $row2['nm_bagian'];
		$subag = $row3['nm_bagian'];
		$nm_barang = $brg['nm_barang'];
		$jns = $brg['jenis'];
		$sat = $brg['satuan'];
		echo "
		<table border=0>
		<tr>
			<td>Satuan Kerja</td>
			<td>:</td>
			<td>$bid</td>
		</tr>
		<tr>
			<td>Bagian</td>
			<td>:</td>
			<td>$bag</td>
		</tr>
		<tr>
			<td>Sub Bagian</td>
			<td>:</td>
			<td>$subag</td>
		</tr>
		<tr>
			<td>Jenis/ Nama Barang</td>
			<td>:</td>
			<td>$jns/$nm_barang</td>
		</tr>
		<tr>
			<td>Satuan</td>
			<td>:</td>
			<td>$sat</td>
		</tr>
		</table>
		";
		if($this->Cetak_Mode==3){//flush
			$this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
		}else{
			$daftar = $this->genDaftar(	$Opsi['Kondisi'], $Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal'], $this->Cetak_Mode, $Opsi['vKondisi_old']);
			echo $daftar['content'];
		}								
		echo	"</div>	".			
				$this->setCetak_footer($xls).
			"</td></tr>
			</table>
			</div>
			</form>		
			</body>	
			</html>";
	}

	
	function genDaftarOpsi(){
		global $Ref, $Main;
	 	$idp = $_REQUEST['idp'];
		$TampilOpt = 
			"<input type='hidden' value='".$idp."' name='idp' id='idp'>";
		return array('TampilOpt'=>$TampilOpt);
	}
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();		
		$idp = $_REQUEST['idp'];
		$arrKondisi[]= " ref_idba='$idp'";		
		//if(!empty($idp)) $arrKondisi[]= " Id='$idp'";		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------		
		$arrOrders = array();
			
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';
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
$PenerimaanDetail = new PenerimaanDetailObj();

?>