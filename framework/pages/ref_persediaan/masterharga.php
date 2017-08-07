<?php

class MasterHargaObj  extends DaftarObj2{	
	var $Prefix = 'MasterHarga';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v1_masterbarang_persediaan'; //daftar
	var $TblName_Hapus = 'ref_hargabarang_persediaan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f','g','h','i','j','tahun_anggaran','satuan');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='usulansk.xls';
	var $Cetak_Judul = 'BARANG';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'MasterHargaForm'; 	
			
	function setTitle(){
		return 'Persediaan';
	}
	function setMenuEdit(){
		
		return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".CopyData()","new_f2.png","Copy",'Copy')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
	}
	
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $a1 = $Main->DEF_KEPEMILIKAN;
	 $a = $Main->Provinsi[0];
	 $b = '00';
	 $c = $_REQUEST['c'];
	 $d = $_REQUEST['d'];
	 $e = $_REQUEST['e'];
	 $golongan = $_REQUEST['golongan'];
	 $sub_golongan = $_REQUEST['sub_golongan'];
	 $merk = $_REQUEST['merk'];
	 $type = $_REQUEST['type'];
	 $spec = $_REQUEST['spec'];
	 $satuan = $_REQUEST['satuan'];
	 $tahun_anggaran = $_REQUEST['tahun_anggaran'];
 	 $harga = str_replace(".", "",$_REQUEST['harga']);
	 $autocompleate = $_REQUEST['autocomplete'];
	 $autocompleate2 = $_REQUEST['autocomplete2'];
 	 $autocompleate3 = $_REQUEST['autocomplete3'];
 	 $autocompleate4 = $_REQUEST['autocomplete4'];
 	 $h=mysql_fetch_array(mysql_query("select * from ref_merk_persediaan where h =".$merk.""));
	 $i=mysql_fetch_array(mysql_query("select * from ref_type_persediaan where i =".$type.""));
	 $j=mysql_fetch_array(mysql_query("select * from ref_spec_persediaan where j =".$spec.""));
	 $s=mysql_fetch_array(mysql_query("select * from ref_satuan_persediaan where Id =".$satuan.""));

			if($fmST == 0){ //input ref_hargabarang_persediaan
				if( $err=='' && $golongan =='' ) $err= 'Golongan belum diisi';
				if( $err=='' && $sub_golongan =='' ) $err= 'Sub Golongan belum diisi';
				if( $err=='' && $merk =='' ) $err= 'Merk belum diisi';
				if( $err=='' && $type =='' ) $err= 'Type belum diisi';
				if( $err=='' && $spec =='' ) $err= 'Spesifikasi belum diisi';
				if( $err=='' && $satuan =='' ) $err= 'Satuan belum diisi';
				if( $err=='' && $tahun_anggaran =='' ) $err= 'Tahun Anggaran belum diisi';
				if( $err=='' && $harga =='' ) $err= 'Harga belum diisi';
				if($autocompleate != $h['nama_merk']) $err="Nama merk tidak sesuai";
				if($autocompleate2 != $i['nama_type']) $err="Nama Type tidak sesuai";
				if($autocompleate3 != $j['nama_spec']) $err="Nama Spesifikasi tidak sesuai";
				if($autocompleate4 != $s['nama_satuan']) $err="Nama Satuan tidak sesuai";			
				if($err==''){ 	 	  
					$aqry1 = "INSERT into ref_hargabarang_persediaan (f,g,h,i,j,tahun_anggaran,harga,satuan)
					"."values('$golongan','$sub_golongan','$merk','$type','$spec','$tahun_anggaran','$harga','$satuan')";	$cek .= $aqry1;	
					$qry = mysql_query($aqry1);
					if($qry==FALSE) $err="Gagal simpan Data barang";		
				}
			}elseif($fmST == 1){ //edit ref_hargabarang_persediaan					
				if($err==''){
					$kode_barang = explode(' ',$idplh);
					$f=$kode_barang[0];	
					$g=$kode_barang[1];
					$h=$kode_barang[2];	
					$i=$kode_barang[3];
					$j=$kode_barang[4];
					$tahun_anggaran_awal=$kode_barang[5];
					$id_satuan=$kode_barang[6]; 				 
					$aqry2 = "UPDATE ref_hargabarang_persediaan
					set "." f = '$golongan',
					 g = '$sub_golongan',
					 h = '$merk',
					 i = '$type',
					 j = '$spec',
					 tahun_anggaran = '$tahun_anggaran',
					 satuan = '$satuan',
					 harga = '$harga'".
					"WHERE concat(f,g,h,i,j)='".$f.$g.$h.$i.$j."' and satuan=$id_satuan and tahun_anggaran=$tahun_anggaran_awal";	$cek .= $aqry2;
					$qry = mysql_query($aqry2);
					if($qry==FALSE) $err="Gagal Edit Data barang";
				}
			}else{
			if($err==''){ //input copy ref_hargabarang_persediaan sesuai tahun anggaran
					$tahun_anggaran_asal=$_REQUEST['tahun_anggaran_asal'];	
					$tahun_anggaran_tujuan=$_REQUEST['tahun_anggaran_tujuan'];	
					$query="select * from ref_hargabarang_persediaan where tahun_anggaran=$tahun_anggaran_asal";
					$qry=mysql_query($query);
					while($row=mysql_fetch_array($qry))
					{
						$aqry1 = "INSERT into ref_hargabarang_persediaan (f,g,h,i,j,tahun_anggaran,harga,satuan)
						"."values('".$row['f']."','".$row['g']."','".$row['h']."','".$row['i']."','".$row['j']."','$tahun_anggaran_tujuan','".$row['harga']."','".$row['satuan']."')";	$cek .= $aqry1;	
						$qry1 = mysql_query($aqry1);
					}	
	 						 
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
		
		case 'formCopyData':{				
			$fm = $this->setFormCopyData();				
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
	   	   
	   case 'golongan':{
			$golongan = $_REQUEST['golongan'];
			
				$query2 = "SELECT * FROM ref_barang_persediaan WHERE f = '".$golongan."' and g!=0"; $cek .= $query2;
				$hasil2 = mysql_query($query2);
				$golongan = "<option value=''>--Pilih--</option>";
				while ($dt = mysql_fetch_array($hasil2))
				{
					$golongan.="<option value='".$dt['g']."'>".$dt['nama_barang']."</option>";
				}
		$content = "<select name='sub_golongan' id='sub_golongan' onChange=".$this->Prefix.".SubGolongan()>".$golongan."</select>";
				
		break;
	   }
	   	   
	   case 'autocomplete_getdata':{
				$json = FALSE;
				//echo 'test';
				$fm = $this->autocomplete_getdata();
				break;
		}
		
		case 'autocomplete_getdatatype':{
				$json = FALSE;
				$fm = $this->autocomplete_getdatatype();
				break;
		}
		
		case 'autocomplete_getdataspec':{
				$json = FALSE;
				$fm = $this->autocomplete_getdataspec();
				break;
		}
		
		case 'autocomplete_getdatasatuan':{
				$json = FALSE;
				$fm = $this->autocomplete_getdatasatuan();
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
					"
					<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			 "<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			 "<script src='js/jquery-ui.custom.js'></script>".		
			 "<script type='text/javascript' src='js/ref_persediaan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/ref_persediaan/mastermerk.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/ref_persediaan/mastertype.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/ref_persediaan/masterspec.js' language='JavaScript' ></script>".			 			 
			 "<script type='text/javascript' src='js/ref_persediaan/mastersatuan.js' language='JavaScript' ></script>".		
			 "<script type='text/javascript' src='js/rekammedis/pasien.js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/kasir/tagihan.js' language='JavaScript' ></script>".
			
			$scriptload;
	}
	
	function autocomplete_getdata(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//query ambil merk persediaan
		$sql = "SELECT * from ref_merk_persediaan
				WHERE
				 nama_merk like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
				$a_json_row["id"] = $row['h'];
				$a_json_row["value"] = $row['nama_merk'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama_merk'];
				array_push($a_json, $a_json_row);
		}
		$json = json_encode($a_json);
		echo $json;
	}
	
	function autocomplete_getdatatype(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//query ambil type persediaan		
		$sql = "SELECT * from ref_type_persediaan
				WHERE
				 nama_type like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
				$a_json_row["id"] = $row['i'];
				$a_json_row["value"] = $row['nama_type'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama_type'];
				array_push($a_json, $a_json_row);
		}
		$json = json_encode($a_json);
		echo $json;
	}
	
	function autocomplete_getdataspec(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//query ambil spesifikasi persediaan
		$sql = "SELECT * from ref_spec_persediaan
				WHERE
				 nama_spec like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
				$a_json_row["id"] = $row['j'];
				$a_json_row["value"] = $row['nama_spec'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama_spec'];
				array_push($a_json, $a_json_row);
		}
		$json = json_encode($a_json);
		echo $json;
	}
	
	function autocomplete_getdatasatuan(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$a_json = array();
		$a_json_row = array();
		$name_startsWith = $_REQUEST['name_startsWith'];
		$maxRows = $_REQUEST['maxRows'];
		//query ambil satuan persediaan
		$sql = "SELECT * from ref_satuan_persediaan
				WHERE
				 nama_satuan like '%".$name_startsWith."%' limit 0,$maxRows ";$cek.=$sql;
		$rs = mysql_query($sql);
		while($row = mysql_fetch_assoc($rs)) {
				$a_json_row["id"] = $row['Id'];
				$a_json_row["value"] = $row['nama_satuan'];//.' '.$row['uraian'];
				$a_json_row["label"] =  $row['nama_satuan'];
				array_push($a_json, $a_json_row);
		}
		$json = json_encode($a_json);
		echo $json;
	}			
	
	
	//form ==================================
	function setFormBaru(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 0;
		$dt['readonly']='';
		$dt['g']=$_REQUEST['golongan'];
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormCopyData(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];		
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 3;
		$dt['readonly']='';
		$dt['g']=$_REQUEST['golongan'];
		$fm = $this->setForm1($dt);
		
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	  
  	function setFormEdit(){
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
		$cek =$cbid[0];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		//get data
		$f=$kode[0];
		$g=$kode[1]; 
		$h=$kode[2]; 
		$i=$kode[3]; 
		$j=$kode[4];
		$tahun_anggaran=$kode[5];
		$satuan=$kode[6]; 
		$bulan=date('Y-m-')."1";
		//query ambil data harga barang persediaan
		$aqry = "SELECT
				`ref_hargabarang_persediaan`.*, `ref_barang_persediaan`.`nama_barang`,
				`ref_merk_persediaan`.`nama_merk`, `ref_type_persediaan`.`nama_type`,
				`ref_spec_persediaan`.`nama_spec`, `ref_satuan_persediaan`.`nama_satuan`,
				`ref_hargabarang_persediaan`.`tahun_anggaran`,
				`ref_hargabarang_persediaan`.`harga`, `ref_hargabarang_persediaan`.`satuan`
				FROM
				`ref_hargabarang_persediaan` INNER JOIN
				`ref_barang_persediaan` ON `ref_barang_persediaan`.`f` =
				`ref_hargabarang_persediaan`.`f` AND `ref_barang_persediaan`.`g` =
				`ref_hargabarang_persediaan`.`g` INNER JOIN
				`ref_merk_persediaan` ON `ref_merk_persediaan`.`h` =
				`ref_hargabarang_persediaan`.`h` INNER JOIN
				`ref_type_persediaan` ON `ref_type_persediaan`.`i` =
				`ref_hargabarang_persediaan`.`i` INNER JOIN
				`ref_spec_persediaan` ON `ref_spec_persediaan`.`j` =
				`ref_hargabarang_persediaan`.`j` INNER JOIN
				`ref_satuan_persediaan` ON `ref_satuan_persediaan`.`Id` =
				`ref_hargabarang_persediaan`.`satuan`
				WHERE
				Concat(`ref_hargabarang_persediaan`.`f`, `ref_hargabarang_persediaan`.`g`,
				`ref_hargabarang_persediaan`.`h`, `ref_hargabarang_persediaan`.`i`,
				`ref_hargabarang_persediaan`.`j`) = '".$f.$g.$h.$i.$j."' AND
				`ref_hargabarang_persediaan`.`tahun_anggaran` = $tahun_anggaran AND
				`ref_hargabarang_persediaan`.`satuan` = $satuan"; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		$dt['kode_barang']=$f.'.'.$g;//.'.'.$h.'.'.$i.'.'.$j; 
		$dt['readonly']='readonly';
		$fm = $this->setForm($dt);
		$content->f=$f;
		$content->g=$g;
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setForm($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 
	 $json = TRUE;	//$ErrMsg = 'tes'; 	
	 $form_name = $this->Prefix.'_form';				
	 $this->form_width = 600;
	 $this->form_height = 250;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
	  }else{
		$this->form_caption = 'EDIT';	  	
	  }
	  			
 	    $username=$_REQUEST['username'];
		//query ref_barang_persediaan
		$queryGolongan = "SELECT f,nama_barang FROM ref_barang_persediaan where f!=0 and g=0";
		$querySubGolongan = "SELECT g,nama_barang FROM ref_barang_persediaan where f!=0 and g!=0"; $cek .=$querySubGolongan;		
       //items ----------------------
		  $this->form_fields = array(
			'golongan' => array( 
								'label'=>'Golongan',
								'labelWidth'=>100, 
								'value'=>cmbQuery('golongan',$dt['f'],$queryGolongan,'id=golongan onChange=\''.$this->Prefix.'.Golongan()\'','--PILIH--','')			 
									 ),	

			'sub_golongan' => array( 
								'label'=>'Sub Golongan',
								'labelWidth'=>100, 
								'value'=>//cmbQuery('kecamatan',$dt['ref_idkecamatan'],$querykecamatan,'style="width:160px" id=kecamatan ','--Kecamatan--')
										"<div id='div_sub_golongan' style='float:left'>".
										cmbQuery('sub_golongan',$dt['g'],$querySubGolongan,'onChange=\''.$this->Prefix.'.SubGolongan()\'','--Pilih--').
										/*<select name='kecamatan' id='kecamatan'>".
										$kecamatan. 
										 "</select>*/"</div>"
									 ),
			
			'merk' => array( 
								'label'=>'Merk',
								'labelWidth'=>100, 
								'value'=>"<div>
											<input type='text' name='autocomplete' id='autocomplete'  size='40' value='".$dt['nama_merk']."'>
											<input type='hidden' id='merk' name='merk' value='".$dt['h']."'>
											&nbsp
											<input type='button' name='reset' value='Reset' onClick='document.getElementById(\"autocomplete\").value=\"\"'>
											<input type='button' id='BaruMerk' name='BaruMerk' value='Baru' onClick='MasterMerk.Baru()'></div>
											</div>"
									 ),	
									 
			'type' => array( 
								'label'=>'Type',
								'labelWidth'=>100, 
								'value'=>"<div>
											<input type='text' name='autocomplete2' id='autocomplete2'  size='40' value='".$dt['nama_type']."'>
											<input type='hidden' id='type' name='type' value='".$dt['i']."'>
											&nbsp
											<input type='button' name='reset' value='Reset' onClick='document.getElementById(\"autocomplete2\").value=\"\"'>
											<input type='button' id='BaruMerk' name='BaruMerk' value='Baru' onClick='MasterType.Baru()'></div>"
									 ),	
									 									 									 			
			'spec' => array( 
								'label'=>'Spesifikasi',
								'labelWidth'=>100, 
								'value'=>"<div>
											<input type='text' name='autocomplete3' id='autocomplete3'  size='40'value='".$dt['nama_spec']."'>
											<input type='hidden' id='spec' name='spec' value='".$dt['j']."'>
											&nbsp
											<input type='button' name='reset' value='Reset' onClick='document.getElementById(\"autocomplete3\").value=\"\"'>
											<input type='button' id='BaruMerk' name='BaruMerk' value='Baru' onClick='MasterSpec.Baru()'></div>"
									 ),

			'satuan' => array( 
								'label'=>'Satuan',
								'labelWidth'=>100, 
								'value'=>"<div>
											<input type='text' name='autocomplete4' id='autocomplete4'  size='40'value='".$dt['nama_satuan']."'>
											<input type='hidden' id='satuan' name='satuan' value='".$dt['satuan']."'>
											&nbsp
											<input type='button' name='reset' value='Reset' onClick='document.getElementById(\"autocomplete4\").value=\"\"'>
											<input type='button' id='BaruMerk' name='BaruMerk' value='Baru' onClick='MasterSatuan.Baru()'></div>"
									 ),	
									 

			'tahun_anggaran' => array( 
								'label'=>'Tahun Anggaran',
								'labelWidth'=>100, 
								'value'=>$dt['tahun_anggaran'], 
								'type'=>'text',
								'id'=>'tahun_anggaran',
								'param'=>"style='width:250ppx;text-transform: uppercase;' size=10px maxlength=4"
									 ),	

			'harga' => array( 
								'label'=>'Harga',
								'labelWidth'=>100, 
								'value'=>number_format($dt['harga'],0,',','.'), 
								'type'=>'text',
								'id'=>'harga',
								//'param'=>"style='width:250ppx;text-transform: uppercase;' size='20px'  "
								'param'=>"style='width:250ppx;text-transform: uppercase;' size='20px'  onkeyup=\"$this->Prefix.formatRupiah(this,'.')\" "
									 ),	
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Batal kunjungan' >".
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
	 $this->form_width = 500;
	 $this->form_height = 75;
	  if ($this->form_fmST==3) {
		$this->form_caption = 'Copy Data';
	  }else{
//		$this->form_caption = 'EDIT';	  	
	  }
	  			
 	    $username=$_REQUEST['username'];
		//query ref_batal
		$queryTahunAnggaran = "select tahun_anggaran, tahun_anggaran from ref_hargabarang_persediaan group by tahun_anggaran";
		//$querySubGolongan = "SELECT g,nama_barang FROM ref_barang_persediaan where f!=0 and g!=0"; $cek .=$querySubGolongan;		
       //items ----------------------
		  $this->form_fields = array(
			'tahun_anggaran_asal' => array( 
								'label'=>'Tahun Anggaran Asal',
								'labelWidth'=>150, 
								'value'=>cmbQuery('tahun_anggaran_asal',$dt['tahun_anggaran_asal'],$queryTahunAnggaran,'id=tahun_anggaran_asal onChange=\''.$this->Prefix.'.Golongan()\'','--PILIH--','')			 
									 ),	

			'tahun_anggaran_tujuan' => array( 
								'label'=>'Tahun Anggaran Tujuan',
								'labelWidth'=>150, 
								'value'=>$dt['tahun_anggaran_tujuan'], 
								'type'=>'text',
								'id'=>'tahun_anggaran_tujuan',
								'param'=>"style='width:250ppx;text-transform: uppercase;' size=10px"
									 ),	
			);
		//tombol
		$this->form_menubawah =	
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Batal kunjungan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $headerTable =
	 "<thead>
	 <tr>
  	   <th class='th01' width='20' >No.</th>
  	   $Checkbox		
   	   <th class='th01' width='100'>Kode Barang</th>
	   <th class='th01' width='200'>Nama Barang</th>
	   <th class='th01' width='200'>Merk</th>
	   <th class='th01' width='200'>Type</th>
	   <th class='th01' width='200'>Spesifikasi</th>
	   <th class='th01' width='200'>Tahun Anggaran</th>
	   <th class='th01' width='200'>Satuan</th>
	   <th class='th01' width='200'>Harga</th>
	   </tr>
	   </thead>";
	
		return $headerTable;
	}	
	
	function setPage_HeaderOther(){
		$Pg = $_REQUEST['Pg'];
		
		$barang = '';
		$merk = '';
		$type = '';
		$spec = '';
		$persediaan = '';
		switch ($Pg){
			case 'masterbarang': $barang ="style='color:blue;'"; break;
			case 'masterharga': $harga ="style='color:blue;'"; break;
		}
		return 
			"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 4 0'>
			<tr><td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
			<A href=\"pages.php?Pg=masterbarang\" title='Barang' $barang>Barang Persediaan</a> |
			<A href=\"pages.php?Pg=masterharga\" title='Persediaan'  $harga>Harga Barang Persediaan</a>    												
			&nbsp&nbsp&nbsp	
			</td></tr></table>";
	}

	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	 global $Main; 

	 $kb=mysql_fetch_array(mysql_query("select * from ref_barang_persediaan where f =".$isi['f']." and g=".$isi['g']."")); //query ref_barang_persediaan
	 $km=mysql_fetch_array(mysql_query("select * from ref_merk_persediaan where h=".$isi['h']."")); //query ref_merk_persediaan
	 $kt=mysql_fetch_array(mysql_query("select * from ref_type_persediaan where i=".$isi['i'].""));	 //query ref_type_persediaan
	 $ks=mysql_fetch_array(mysql_query("select * from ref_spec_persediaan where j=".$isi['j']."")); //query ref_spec_persediaan
	 $satuan=mysql_fetch_array(mysql_query("select * from ref_satuan_persediaan where Id =".$isi['satuan']."")); //query ref_satuan_persediaan	 	 	 
	 $kode_barang=$kb['f'].'.'.$kb['g'].'.'.$km['h'].'.'.$kt['i'].'.'.$ks['j'];
	 $Koloms = array();
	 $Koloms[] = array('align="center" width="20"', $no.'.' );
	 if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('align="center" width="100"',$kode_barang);
 	 $Koloms[] = array('align="left" width="300"',$kb['nama_barang']);	 	 	 	 
 	 $Koloms[] = array('align="left" width="300"',$km['nama_merk']);	 	 	 	 
 	 $Koloms[] = array('align="left" width="300"',$kt['nama_type']);	 	 	 	 
 	 $Koloms[] = array('align="left" width="300"',$ks['nama_spec']);	 	 	 	 
 	 $Koloms[] = array('align="center" width="100"',$isi['tahun_anggaran']);	 	 	 	 
 	 $Koloms[] = array('align="left" width="100"',$satuan['nama_satuan']);	 	 	 	 	 
 	 $Koloms[] = array('align="right" width="300"',number_format($isi['harga'],2,',','.'));	 	 	 	  
	 return $Koloms;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
	 
	//$fmGOLONGAN = cekPOST('fmGOLONGAN');
	//$fmSUBGOLONGAN = cekPOST('fmSUBGOLONGAN');
	//$fmMERK = cekPOST('fmMERK');
	//$fmTYPE = cekPOST('fmTYPE');
	$fmPILCARI = $_REQUEST['fmPILCARI'];	
	$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];		
	$fmORDER1 = cekPOST('fmORDER1');
	$fmDESC1 = cekPOST('fmDESC1');
	 $arr = array(
			//array('selectAll','Semua'),
			array('selectfg','Kode Barang'),
			array('selectbarang','Nama Barang'),	
			array('selecttahun','Tahun Anggaran'),				
			);
		
	//data order ------------------------------
	 $arrOrder = array(
	  	         array('1','Kode Barang'),
			     array('2','Nama Barang'),
			     array('3','Tahun Anggaran'),			 				 				 	
	 );	
				
	$TampilOpt = 
			"
			<tr><td>	
			<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			$cari =
			cmbArray('fmPILCARI',$fmPILCARI,$arr,'-- Cari Data --','').'&nbsp'. //generate checkbox
			"<input type='text' value='".$fmPILCARIvalue."' name='fmPILCARIvalue' id='fmPILCARIvalue'>
			<input type='button' id='btTampil' value='Cari' onclick='".$this->Prefix.".refreshList(true)'>
			</td>				
			</tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>".
			"<div class='FilterBar'>
			<table style='width:100%'><tbody><tr><td align='left'>
			<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
			<tbody><tr valign='middle'>   						
				<td align='left' style='padding:1 8 0 8; '>".
			"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'>Urutkan : </div>".
			//createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1).
			cmbArray('fmORDER1',$fmORDER1,$arrOrder,'--Urutkan--','')."&nbsp".					
			"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>menurun".
			"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'><br>".		
			"<input type='hidden' id='fmORDER18' name='fmORDER18' value='".$fmORDER18."'>".
					"<input type='hidden' id='fmORDER19' name='fmORDER19' value='".$fmORDER19."'>";			
		return array('TampilOpt'=>$TampilOpt);
	}	
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();		
		$fmPILCARI = $_REQUEST['fmPILCARI'];	
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];			
		//$fmGOLONGAN = $_REQUEST['fmGOLONGAN'];
		//$fmSUBGOLONGAN = $_REQUEST['fmSUBGOLONGAN'];
		//$fmMERK = $_REQUEST['fmMERK'];
		//$fmTYPE = $_REQUEST['fmTYPE'];		
		
		switch($fmPILCARI){
			case 'selectfg': $arrKondisi[] = " concat(f,g) like '%$fmPILCARIvalue%'"; break;		 	
			case 'selectbarang': $arrKondisi[] = " nama_barang like '%".$fmPILCARIvalue."%'"; break;
			case 'selecttahun': $arrKondisi[] = " tahun_anggaran like '%".$fmPILCARIvalue."%'"; break;								 	
		}
		
				
		/*if(empty($fmGOLONGAN) && empty($fmSUBGOLONGAN) && empty($fmMERK) && empty($fmTYPE))
		{
			$arrKondisi[]= "f !=00 and g=00 and h=00 and i=00 and j=00";
		}
		elseif(!empty($fmGOLONGAN) && empty($fmSUBGOLONGAN) && empty($fmMERK) && empty($fmTYPE))
		{
			$arrKondisi[]= "f =$fmGOLONGAN and g!=00 and h=00 and i=00 and j=00";
		}
		elseif(!empty($fmGOLONGAN) && !empty($fmSUBGOLONGAN) && empty($fmMERK) && empty($fmTYPE))
		{
			$arrKondisi[]= "f =$fmGOLONGAN and g=$fmSUBGOLONGAN and h!=00 and i=00 and j=00";
		}
		elseif(!empty($fmGOLONGAN) && !empty($fmSUBGOLONGAN) && !empty($fmMERK) && empty($fmTYPE))
		{
			$arrKondisi[]= "f =$fmGOLONGAN and g=$fmSUBGOLONGAN and h=$fmMERK and i!=00 and j=00";
		}
		elseif(!empty($fmGOLONGAN) && !empty($fmSUBGOLONGAN) && !empty($fmMERK) && !empty($fmTYPE))
		{
			$arrKondisi[]= "f =$fmGOLONGAN and g=$fmSUBGOLONGAN and h=$fmMERK and i=$fmTYPE and j!=00";
		}*/
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		switch($fmORDER1){
			case '': $arrOrders[] = " tahun_anggaran DESC, concat(f,g,h,i,j) ASC   " ;break;
			case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama_barang $Asc1 " ;break;
			case '3': $arrOrders[] = " tahun_anggaran $Asc1 " ;break;			
		
		}

			$Order= join(',',$arrOrders);	
			$OrderDefault = '';// Order By no_terima desc ';
			$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//}
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
$MasterHarga = new MasterHargaObj();

?>