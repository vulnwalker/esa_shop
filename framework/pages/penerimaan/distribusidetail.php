<?php

class DistribusiDetailObj  extends DaftarObj2{	
	var $Prefix = 'DistribusiDetail';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'pengeluaran'; //daftar
	var $TblName_Hapus = 'pengeluaran';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Detail Distribusi Barang';
	var $PageIcon = 'images/administrator/images/forms.png';
	var $pagePerHal ='25';
	var $fileNameExcel='DistribusiDetail.xls';
	var $namaModulCetak='Distribusi Detail';
	var $Cetak_Judul = 'Distribusi Detail';	
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
		
		case 'simpan':{
			$get= $this->simpan();
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
		
		case 'formCariBrg':{				
			$fm = $this->setFormCariBrg();				
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
	 	$fmSKPDBidang = cekPOST('fmSKPDBidang')!=$vAtas? cekPOST('fmSKPDBidang'): $HTTP_COOKIE_VARS['cofmSKPD'];
		$fmSKPDSkpd = cekPOST('fmSKPDskpd')!=$vAtas? cekPOST('fmSKPDskpd'): $HTTP_COOKIE_VARS['cofmUNIT'];
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
	
	function autocomplete_barang_getdata(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//echo $name_startsWith
		$sql = "select Id,nama,ref_idsatuan,merk from ref_barang where nama like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;		
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
			//$label =;
				$ns=mysql_fetch_array(mysql_query("select * from ref_satuan where Id='".$row['ref_idsatuan']."'"));
				$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama'].' - '.$row['merk'];
				$a_json_row["label2"] =  $row['ref_idsatuan'];
				$a_json_row["label3"] =  $ns['nama'];				
				$a_json_row["label4"] =  $row['merk'];				
				array_push($a_json, $a_json_row);
		}
		//$a_json = apply_highlight($a_json, $parts);
		$json = json_encode($a_json);
		echo $json;
		//$content = $json;
		//return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
		//echo $sql;
		//json_encode($a_json)
	}
	
	function simpanmak(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $thn_dipa = $_REQUEST['thn_dipa'];
	 $idp = $_REQUEST['idp'];
	 $kegiatan = $_REQUEST['nama_kegiatan'];
	 $mak_biasa = strtoupper($_REQUEST['mak_biasa']);
	 $pagu_biasa = str_replace(".","",$_REQUEST['pagu_biasa']);
	 $ket_biasa = $_REQUEST['ket_biasa'];
	 $mak_meeting = strtoupper($_REQUEST['mak_meeting']);
	 $pagu_meeting = str_replace(".","",$_REQUEST['pagu_meeting']);
	 $ket_meeting = $_REQUEST['ket_meeting'];
	 
	 					
			if($fmST == 0){	
			
				$old = mysql_fetch_array(mysql_query("SELECT
										  `aa`.`no_mak`, `bb`.`thn_dipa`, `aa`.`ref_id_dipa`
										FROM
										  `tbl_mak` `aa` LEFT JOIN
										  `tbl_dipa` `bb` ON `aa`.`ref_id_dipa` = `bb`.`Id`
										WHERE no_mak='$mak_biasa' and `bb`.`thn_dipa`='".$thn_dipa."'"));
				if($max==$old['no_mak']){
					       $get = mysql_fetch_array(mysql_query("SELECT count(*) as cnt FROM tbl_mak WHERE no_mak='$mak_biasa'"));
						   if($get['cnt']>0 ) $err='MAK "'.$mak_biasa.'" Tahun Anggaran "'.$thn_dipa.'" Sudah Ada !';
				}
				
				if($err=='' && $kegiatan=='')$err='Nama Kegiatan belum di isi !';	
				if($err=='' && $mak_biasa=='')$err='Nomor MAK biasa belum di isi !';
				if($err=='' && $pagu_biasa=='')$err='Pagu Anggaran biasa belum di isi !';
				if($err=='' && $mak_meeting=='')$err='Nomor MAK meeting belum di isi !';
				if($err=='' && $pagu_meeting=='')$err='Pagu Anggaran meeting belum di isi !';
				if($err==''){ 
					$aqry="INSERT into tbl_mak(ref_id_dipa,no_mak,nama_kegiatan,pagu,keterangan,no_mak_meeting,pagu_meeting,keterangan_meeting)"." values('$idp','$mak_biasa','$kegiatan','$pagu_biasa','$ket_biasa','$mak_meeting','$pagu_meeting','$ket_meeting')"; $cek.=$aqry;
					$qry=mysql_query($aqry);				
				}
			}elseif($fmST == 1){
				if($err==''){ 
				$aqry2 = "UPDATE tbl_mak set no_mak='$mak_biasa',nama_kegiatan='$kegiatan',pagu='$pagu_biasa',keterangan='$ket_biasa',no_mak_meeting='$mak_meeting',pagu_meeting='$pagu_meeting',keterangan_meeting='$ket_meeting' WHERE Id='".$idplh."'";$cek.= $aqry;
				$qry2=mysql_query($aqry2);	
				}
			}			
			else{
			}
					
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
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
		 "<script type='text/javascript' src='js/pemindahtangan/pemindahtangan_rencana.js' language='JavaScript' ></script>".
		 //"<script type='text/javascript' src='js/penerimaan/penerimaandetail.js' language='JavaScript' ></script>".
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
	
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_form';
	 $this->form_width = 400;
	 $this->form_height = 250;
	 $ref_jenis=$_REQUEST['ref_jenis'];
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Distribusi';
		$idp= $_REQUEST['idp'];
	  }else{
		$this->form_caption = 'Edit Distribusi';			
		//$Id = $dt['Id'];			
	  }
	  
	  //unit & subunit
	  $pilihUnit=$this->cmbQueryUnit('fmSKPDUnit_form',$dt['e'],'','onchange=DistribusiDetail.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',FALSE,$editunit);
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
			'ba_tgl' => array(
							'label'=>'&nbsp;&nbsp;Tanggal', 
							'value'=> createEntryTgl3($dt['ba_tgl'], 'ba_tgl', false,''), 
							'type'=>''
			),	
			
			'jml' => array( 
						'label'=>'Jumlah',
						'labelWidth'=>50, 
						'value'=>"<input type='text' name='jml' id='jml' value='".$dt['jml']."' size='15' onkeypress='return isNumberKey(event)' >",
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
			"<input type='hidden' name='idp' id='idp' value='".$idp."' >".
			"<input type='hidden' name='ref_jenis' id='ref_jenis' value='".$ref_jenis."' >".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
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
	 
	 $idp = $_REQUEST['idp'];
	 $ref_idbarang = $_REQUEST['id_barang'];
     $spesifikasi = $_REQUEST['spesifikasi'];
     $satuan = $_REQUEST['ref_idsatuan'];
     $jml = $_REQUEST['jml'];
	 
		if($err=='' && $ref_idbarang=='')$err='Barang belum di isi !';
		if($err=='' && $spesifikasi=='')$err='spesifikasi belum di isi !';
		if($err=='' && $satuan=='')$err='satuan belum di isi !';
		if($err=='' && $jml=='')$err='Jumlah Barang belum di isi !';
	
			if($fmST == 0){
				
				if($err==''){ 
				$aqry="INSERT into t_penerimaan_d(ref_idpenerimaan,ref_idbarang,spesifikasi,jml,ref_idsatuan)"." values('$idp','$ref_idbarang','$spesifikasi','$jml','$satuan')"; $cek.=$aqry;
				$qry=mysql_query($aqry);	
				
				$id = mysql_insert_id();
				$aqry2 = "INSERT INTO jurnal (tgl,ref_id,ref_idbarang,jml,jns) VALUES (now(),'$id','$ref_idbarang','$jml','1')";
				$cek .= $aqry2;
				$qry1 = mysql_query($aqry2);			
				}
				
			}elseif($fmST == 1){
			
				if($err==''){ 
				$aqry2 = "UPDATE t_penerimaan_d set ref_idbarang='$ref_idbarang',spesifikasi='$spesifikasi',jml='$jml',ref_idsatuan='$satuan' WHERE Id='".$idplh."'";$cek.= $aqry;
				$qry2=mysql_query($aqry2);

				$aqry2 = "UPDATE jurnal set
							  tgl=now(),
							  ref_idbarang='$ref_idbarang',
							  jml='$jml'
							  WHERE ref_id='".$idplh."' and jns=1";
				$cek .= $aqry2;
				$qry2 = mysql_query($aqry2);	
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
  	   <th class='th01' width='5' rowspan=2>No.</th>
  	   $Checkbox		
	   <th class='th02' colspan=2>BAST</th>
	   <th class='th01' rowspan=2>Unit Pengguna</th>
	   <th class='th01' rowspan=2>Jumlah</th>
	   <th class='th01' rowspan=2>Harga Satuan</th>
	   <th class='th01' rowspan=2>Jumlah Harga</th>
	   <th class='th01' rowspan=2>Ket</th>
	   </tr>
	   <tr>
	   <th class='th01'>Nomor</th>
	   <th class='th01'>Tanggal</th>
	   </tr>
	  </thead>";
		return $headerTable;
	}
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main;
	 
	 $jml_harga = $isi['jml_barang']*$isi['harga'];
	 
	 $e=mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='000'"));
	 $e1=mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"));
	 
	 $Koloms = array();
	 $Koloms[] = array('align="right"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align=center',$isi['ba_no']);
	 $Koloms[] = array('align=center',TglInd($isi['ba_tgl']));
	 $Koloms[] = array('align=left',$e['nm_skpd'].'/<br>'.$e1['nm_skpd']);
	 $Koloms[] = array('align=right',$isi['jml_barang']);
	 $Koloms[] = array('align=right',number_format($isi['harga'],2,',','.'));
	 $Koloms[] = array('align=right',number_format($jml_harga,2,',','.'));
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
	 	$idp2 = $_REQUEST['idp2'];
		$TampilOpt = 
			"<input type='hidden' value='".$idp."' name='idp' id='idp'>
			<input type='hidden' value='".$idp2."' name='idp2' id='idp2'>";
		return array('TampilOpt'=>$TampilOpt);
	}
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		$arrKondisi = array();		
		$idp = $_REQUEST['idp'];
		$idp2 = $_REQUEST['idp2'];
		$arrKondisi[]= " ref_idterimaba='$idp'";		
		if(!empty($idp2)) $arrKondisi[]= " ref_idterima='$idp2'";		
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
$DistribusiDetail = new DistribusiDetailObj();

?>