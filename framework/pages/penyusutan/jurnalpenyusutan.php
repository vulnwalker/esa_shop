<?php

class JurnalPenyusutanObj extends DaftarObj2{
	var $Prefix = 'JurnalPenyusutan';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 't_jurnal_aset'; //daftar
	var $TblName_Hapus = 'v_jurnal_penyusutan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f');
	var $FieldSum = array('debet','kredit','total');//array('jml_harga');
	var $SumValue = array();
	var $fieldSum_lokasi = array(5);
	var $FieldSum_Cp1 = array( 11, 1, 1);//berdasar mode
	var $FieldSum_Cp2 = array( 11, 1, 1);	
	var $checkbox_rowspan = 2;
	var $PageTitle = 'Penatausahaan';
	var $PageIcon = 'images/masterData_01.gif';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='ref_barang.xls';
	var $Cetak_Judul = 'DAFTAR JURNAL PENYUSUTAN';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'adminForm'; 	
	
	function setTitle(){
		global $Main;
		return 'Jurnal Penyusutan';	

	}
	
	function setNavAtas(){
		return
		
		
			'<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a href="pages.php?Pg=RekapPenyusutan" title="Rekap Penyusutan">Rekap Penyusutan</a> 
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';	
	}
	

	function setMenuEdit(){		
		return "";

			/*
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus').
			"</td>";
			*/
	}
	
	function setMenuView(){		
		return 			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Cetak',"Cetak Daftar")."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".exportXls(\"$Op\")","export_xls.png",'Excel',"Export Excel")."</td>";					

	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		
		$arrDebet = array(
	  	          	array('1','Debet'),
			     	array('2','Kredit'),
					);
					
		
		$idawal = $_REQUEST['idawal'];
		$idbi= $_REQUEST['idbi'];
		$tgl1 	= $_REQUEST['tgl1'];
		$tgl2 	= $_REQUEST['tgl2'];
		$debet 	= $_REQUEST['debet'];
		$jnsTrans2 	= $_REQUEST['jns_trans2'];
		$kint 	= $_REQUEST['kint'];
		$ka 	= $_REQUEST['ka'];
		$kb 	= $_REQUEST['kb'];
		$f 	= $_REQUEST['f'];
		$g 	= $_REQUEST['g'];
		$h 	= $_REQUEST['h'];
		$i 	= $_REQUEST['i'];
		$kode_rekap 	= $_REQUEST['kode_rekap'];
		$kode_barang 	= $_REQUEST['kode_barang'];
		#$kode_brg = $f ==''? '' : $f.'.'.$g.'.'.$h.'.'.$i;
		/*$hidden	= 
			"<input type='hidden' id='kint' name='kint' value='$kint'  >".
			"<input type='hidden' id='ka' name='ka' value='$ka'  >".
			"<input type='hidden' id='kb' name='kb' value='$kb'  >".
			"<input type='hidden' id='f' name='f' value='$f'  >".
			"<input type='hidden' id='g' name='g' value='$g'  >".
			"<input type='hidden' id='h' name='h' value='$h'  >".
			"<input type='hidden' id='i' name='i' value='$i'  >".
			"";	*/
		
	
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td>
				$hidden
			</td></tr>			
			</table>".
			"<div class='FilterBar'>
				<table style='width:100%'>
					<tbody>
						<tr>
							<td align='left'>
								<table cellspacing='0' cellpadding='0' border='0' style='height:28'>
									<tr valign='middle'>
										<td align='left' style='padding:1 8 0 8;height:30; '>".
											$vtgl =
											"<div style='float:left;padding: 0 4 0 0'> Tanggal :</div>".
											"<div style='float:left;padding: 0 4 0 0'>".createEntryTgl3($tgl1, 'tgl1', false, '')."</div>".
											"<div style='float:left;padding: 0 4 0 0'>s/d &nbsp;</div>".
											"<div style='float:left;padding: 0 4 0 0'>".createEntryTgl3($tgl2, 'tgl2', false, '')."</div>".
										"</td>
									</tr>
									<tr>
										<td align='left' style='padding:1 8 0 8; '>
											ID Awal : <input type='text' name='idawal' id='idawal' value='".$idawal."'> &nbsp;&nbsp;&nbsp;
											ID Barang : <input type='text' name='idbi' id='idbi' value='".$idbi."'> &nbsp;&nbsp;&nbsp;
											Kode Rekap : <input type='text' name='kode_rekap' id='kode_rekap' value='".$kode_rekap."'> &nbsp;&nbsp;&nbsp;
											Kode Barang : <input type='text' name='kode_barang' id='kode_barang' value='".$kode_barang."'> &nbsp;&nbsp;&nbsp;".
											cmbArray('debet',$debet,$arrDebet,'--Semua--','')."&nbsp&nbsp;&nbsp;".
											cmbArray('jns_trans2',$jnsTrans2,$Main->jnsTrans2,'--Semua--','')."&nbsp&nbsp;&nbsp;".
											"<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
		    </div>";
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
				
		$arrKondisi = array();	//$arrKondisi[] = " idawal = '740330' ";	
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
		/*$arrKondisi[] = getKondisiSKPD2(
			12, 
			$Main->Provinsi[0], 
			'00', 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
		);*/
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = "c='$fmSKPD'";
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = "d='$fmUNIT'";
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = "e='$fmSUBUNIT'";
		if(!($fmSEKSI=='' || $fmSEKSI=='00') ) $arrKondisi[] = "e1='$fmSEKSI'";
		 	
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		//if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		
		
		$idawal = $_REQUEST['idawal'];
		if($idawal!='')$arrKondisi[] = " idawal = '$idawal' ";	
		$idbi = $_REQUEST['idbi'];
		if($idbi!='')$arrKondisi[] = " idbi = '$idbi' ";	
		//&kint=01&ka=01&kb=01&tgl1=2015-01-01&tgl2=2015-12-31&jns_trans=7&debet=2
		$kode_rekap=explode(".",$_REQUEST['kode_rekap']);
		$kode_barang=explode(".",$_REQUEST['kode_barang']);		
		$kint = $kode_rekap[0];
		if($kint!='') $arrKondisi[] = " kint ='$kint' ";
		$ka = $kode_rekap[1];
		if($ka!='' && $ka!='00') $arrKondisi[] = " ka ='$ka' ";
		$kb = $kode_rekap[2];
		if($kb!='' && $kb!='00') $arrKondisi[] = " kb ='$kb' ";
		$f = $kode_barang[0];
		if($f!='' && $f!='00') $arrKondisi[] = " f ='$f' ";
		$g = $kode_barang[1];
		if($g!='' && $g!='00') $arrKondisi[] = " g ='$g' ";
		$h = $kode_barang[2];
		if($h!='' && $h!='00') $arrKondisi[] = " h ='$h' ";
		$i = $kode_barang[3];
		if($i!='' && $i!='00') $arrKondisi[] = " i ='$i' ";

		$tgl1 = $_REQUEST['tgl1'];
		if($tgl1!='') $arrKondisi[] = " tgl_buku >='$tgl1' ";
		$tgl2 = $_REQUEST['tgl2'];
		if($tgl2!='') $arrKondisi[] = " tgl_buku <='$tgl2' ";
		$debet = $_REQUEST['debet'];
		if($debet!='') {
			switch ($debet){
				case '1': $arrKondisi[] = " debet >0 "; break;
				case '2': $arrKondisi[] = " kredit >0 "; break;				
			}
			
		}
		$jns_trans2 = $_REQUEST['jns_trans2'];
		if($jns_trans2!='') $arrKondisi[] = " jns_trans2 ='$jns_trans2' ";
		$arrKondisi[] = " jns_trans ='10' ";
		
		/*$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];			
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		$fmIdbiAwal = $_REQUEST['idbi_awal'];
		$fmIdbi = $_REQUEST['idbi'];
		$fmKdAkun = str_replace('.','',$_REQUEST['kd_akun']);
		$fmJnsTrans = $_REQUEST['fmJnsTrans'];
		$fmJnsTrans2 = $_REQUEST['fmJnsTrans2'];
		if(!empty($fmFiltTglBtw_tgl1)) $arrKondisi[] = " tgl_buku>='$fmFiltTglBtw_tgl1'";
		if(!empty($fmFiltTglBtw_tgl2)) $arrKondisi[] = " tgl_buku<='$fmFiltTglBtw_tgl2'";
		if(!empty($fmIdbiAwal)) $arrKondisi[] = " idawal = '$fmIdbiAwal'";
		if(!empty($fmIdbi)) $arrKondisi[] = " idbi = '$fmIdbi'";
		if(!empty($fmIdbi)) $arrKondisi[] = " idbi = '$fmIdbi'";
		if(!empty($fmKdAkun)) $arrKondisi[] = " concat(kint,ka,kb) like '%".$fmKdAkun."%'";
		if(!empty($fmJnsTrans)) $arrKondisi[] = " jns_trans = '$fmJnsTrans'";
		if(!empty($fmJnsTrans2)) $arrKondisi[] = " jns_trans2 = '$fmJnsTrans2'";*/
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}*/		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);	$Main->PagePerHal = 50;				
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							
						});
						
						
					</script>";
		return "<script src='js/skpd.js' type='text/javascript'></script>
				<script src='js/barcode.js' type='text/javascript'></script>
				<script type='text/javascript' src='js/penyusutan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
				".
						$scriptload;
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$cetak = $Mode==2 || $Mode==3 ;
		
			
		$headerTable =
				"<tr>
				<th class='th01' width='20' rowspan=2>No.</th>
  	  			$Checkbox 		
   	   			<th class='th01' rowspan=2>Tanggal</th>
<th class='th01' rowspan=2>Uraian</th>
				<th class='th01' rowspan=2>Debet</th>
				<th class='th01' rowspan=2>Kredit</th>
				<th class='th01' rowspan=2>Total</th>
				<th class='th02' colspan=7>Barang</th>
				</tr>
				
				<tr>
				<th class='th01'>ID Barang</th>
				<th class='th01'>ID Awal</th>
				<th class='th01'>Kode SKPD</th>
				<th class='th01'>Nama SKPD</th>
				<th class='th01'>Kode Barang</th>
				<th class='th01'>Tahun</th>
				<th class='th01'>Noreg</th>
				</tr>
				";
				//$tambahgaris";
		return $headerTable;
	}
	
	function setDaftar_query($Kondisi='', $Order='', $Limit=''){		
		$aqry =  "select " .
				//a1,a,b,
				" c,d,e,e1,f,g,h,i,j,idawal,idbi,tgl_buku,debet,kredit,(kredit-debet) as total, jns_trans2, kint ".
				" from ".
					" ( select tgl_buku, idawal,idbi, ".
					" c,d,e,e1,f,g,h,i,j, ".
					" jns_trans2, kint, ".
					" sum(debet) as debet, sum(kredit) as kredit  from $this->TblName ".
					//where idawal = 5931 
					$Kondisi.				
					" group by tgl_buku, idawal,idbi, ".
					" c,d,e,e1,f,g,h,i,j, ".
					" jns_trans2, kint ".
				 ") zz ".				
				" $Order $Limit ".				
				"";
				
		/*$aqry = "select a1,a,b,c,d,e,e1,f,g,h,i,j,idawal,idbi,tgl_buku,debet,kredit,(kredit-debet) as total ".
				"from v_jurnal_penyusutan ".
				"$Kondisi $Order $Limit ";*/
		return $aqry;		
	}
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main,$HTTP_COOKIE_VARS;
			$kdbrg = $isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'];
			$kdskpd = $isi['c'].'.'.$isi['d'].'.'.$isi['e'].'.'.$isi['e1'];
			$isi2=mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['idbi']."'"));
			$row=mysql_fetch_array(mysql_query("select nm_skpd from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='00' and e1='000'"));
			
			$Koloms[] = array('align="center" width="20"', $no.'.' );
 			if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 		$Koloms[] = array('align="center"',$isi['tgl_buku']);
			$Koloms[] = array('align="left"',$Main->jnsTrans2[$isi['jns_trans2']-1][1]);

			$Koloms[] = array('align="right"',number_format($isi['debet'],2,',','.'));
			$Koloms[] = array('align="right"',number_format($isi['kredit'],2,',','.'));
			$Koloms[] = array('align="right"',number_format($isi['total'],2,',','.'));
			$Koloms[] = array('align="center"',$isi['idbi']);
			$Koloms[] = array('align="center"',$isi['idawal']); 
			$Koloms[] = array('align="center"',$kdskpd);
			$Koloms[] = array('align="center"',$row['nm_skpd']);
			$Koloms[] = array('align="center"',$kdbrg);
			$Koloms[] = array('align="center"',$isi2['thn_perolehan']);
	 		$Koloms[] = array('align="center"',$isi2['noreg']); 

		return $Koloms;
	}
	
	function setSumHal_query($Kondisi, $fsum){
		//return "select $fsum from (select a1,a,b,c,d,e,e1,f,g,h,i,j,idawal,idbi,tgl_buku,debet,kredit,(kredit-debet) as total from v_jurnal_penyusutan $Kondisi)`aa`  "; //echo $aqry;
		return " select $fsum from ( ".
				" select " .
				//a1,a,b,
				" c,d,e,e1,f,g,h,i,j,idawal,idbi,tgl_buku,debet,kredit,(kredit-debet) as total, jns_trans2, kint ".
				" from ".
					" ( select tgl_buku, idawal,idbi, ".
					" c,d,e,e1,f,g,h,i,j, ".
					" jns_trans2, kint, ".
					" sum(debet) as debet, sum(kredit) as kredit  from $this->TblName ".
					//where idawal = 5931 
					$Kondisi.				
					" group by tgl_buku, idawal,idbi, ".
					" c,d,e,e1,f,g,h,i,j, ".
					" jns_trans2, kint ".
				 ") aa ".				
				") bb ".
				"";
	
	
	}
	
	function genDaftarInitial(){
		global $HTTP_COOKIE_VARS;
		$TampilOpt = $this->genDaftarOpsi();
		
		$arrkode_rekap = array();
		if(($_REQUEST['kint'] != '' ||$_REQUEST['kint']!='00') ) $arrkode_rekap[] = $_REQUEST['kint'];
		if(($_REQUEST['ka'] != '' || $_REQUEST['ka']!='00') ) $arrkode_rekap[] = $_REQUEST['ka'];
		if(($_REQUEST['kb'] != '' || $_REQUEST['kb']!='00') ) $arrkode_rekap[] = $_REQUEST['kb'];
		$kode_rekap = join('.', $arrkode_rekap);
		$kode_rekap = $_REQUEST['kint']==''? '' : $kode_rekap;

		$arrkode_barang = array();
		if(($_REQUEST['f'] != '' || $_REQUEST['f']!='00') ) $arrkode_barang[] = $_REQUEST['f'];
		if(($_REQUEST['g'] != '' || $_REQUEST['g']!='00') ) $arrkode_barang[] = $_REQUEST['g'];
		if(($_REQUEST['h'] != '' || $_REQUEST['h']!='00') ) $arrkode_barang[] = $_REQUEST['h'];
		if(($_REQUEST['i'] != '' || $_REQUEST['i']!='00') ) $arrkode_barang[] = $_REQUEST['i'];
		$kode_barang = join('.', $arrkode_barang);
		$kode_barang = $_REQUEST['f']==''? '' : $kode_barang;
		
		return		
			//$NavAtas.	
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
			//$vOpsi['TampilOpt'].
			"<input type='hidden' id='c' name='c' value='".$_REQUEST['c']."'>".
			"<input type='hidden' id='d' name='d' value='".$_REQUEST['d']."'>".
			"<input type='hidden' id='e' name='e' value='".$_REQUEST['e']."'>".
			"<input type='hidden' id='e1' name='e1' value='".$_REQUEST['e1']."'>".
			"<input type='hidden' id='tgl1' name='tgl1' value='".$_REQUEST['tgl1']."'>".
			"<input type='hidden' id='tgl2' name='tgl2' value='".$_REQUEST['tgl2']."'>".
			"<input type='hidden' id='idbi' name='idbi' value='".$_REQUEST['idbi']."'>".
			"<input type='hidden' id='idawal' name='idawal' value='".$_REQUEST['idawal']."'>".
			"<input type='hidden' id='debet' name='debet' value='".$_REQUEST['debet']."'>".
			"<input type='hidden' id='jns_trans2' name='jns_trans2' value='".$_REQUEST['jns_trans2']."'>".
			"<input type='hidden' id='kint' name='kint' value='".$_REQUEST['kint']."'>".
			"<input type='hidden' id='ka' name='ka' value='".$_REQUEST['ka']."'>".
			"<input type='hidden' id='kb' name='kb' value='".$_REQUEST['kb']."'>".
			"<input type='hidden' id='f' name='f' value='".$_REQUEST['f']."'>".
			"<input type='hidden' id='g' name='g' value='".$_REQUEST['g']."'>".
			"<input type='hidden' id='h' name='h' value='".$_REQUEST['h']."'>".
			"<input type='hidden' id='i' name='i' value='".$_REQUEST['i']."'>".
			"<input type='hidden' name='kode_rekap' id='kode_rekap' value='".$kode_rekap."'>".
			"<input type='hidden' name='kode_barang' id='kode_barang' value='".$kode_barang."'>".
			//"<input type='hidden' id='skpd_distribusifmBidang' name='skpd_distribusifmBidang' value='".$skpd."'>".
			//"<input type='hidden' id='skpd_distribusifmBagian' name='skpd_distribusifmBagian' value='".$unit."'>".
			//"<input type='hidden' id='skpd_distribusifmSubBagian' name='skpd_distribusifmSubBagian' value='".$subunit."'>".
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				//"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}

	
}
$JurnalPenyusutan = new JurnalPenyusutanObj();

?>