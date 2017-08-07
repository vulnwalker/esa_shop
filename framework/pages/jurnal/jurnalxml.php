<?php

class JurnalxmlObj {
	var $Prefix = 'jurnalxml';	
	var $title = 'ATISISBADA XML Jurnal';
	var $description="Publish Jurnal";
	var $link = "#";
	var $generator="";		
	var $language="id - id";
	var $TblName = 't_jurnal_aset'; //daftar
			
	
	function selector($tipe){
	 global $Main;
	 $cek = ''; $err=''; $content=''; //$json=TRUE;
	 $fmtipe = $_REQUEST['tipe'];
	 $fmbidang = $_REQUEST['bidang'];
	 $fmskpd = $_REQUEST['skpd'];
	 $fmunit = $_REQUEST['unit'];
	 $fmsubunit = $_REQUEST['subunit'];
	 $fmtahun = $_REQUEST['tahun'];
	 $fmsemester = $_REQUEST['semester'];
	 $fmkd_akun = $_REQUEST['kdakun'];
	 $fmkd_barang = $_REQUEST['kdbarang'];
	 $fmurusan_keu = $_REQUEST['urusan_keu'];
	 $fmbidang_keu = $_REQUEST['bidang_keu'];
	 $fmdinas_keu = $_REQUEST['dinas_keu'];
	 $fmurl = $_REQUEST['url'];
	 
	 //---------------------------- Inisiasi C, D Dengan Mapping Ke ref_skpd_urusan -------------------------------------
	 	if($urusan_keu!=""){
				$mapping_keu=mysql_fetch_array(mysql_query("select * from ref_skpd_urusan where bk='$fmurusan_keu' and ck='$fmbidang_keu' and dk='$fmdinas_keu' "));
				$fmbidang = $mapping_keu['c'];
	 			$fmskpd = $mapping_keu['d'];
		}
	  switch($fmtipe){
	  
	  	case 'tes':{
			if($fmurl!=""){
			$getdaftar=$this->genContentURL($fmbidang,$fmskpd,$fmunit,$fmsubunit,$fmtahun,$fmsemester,$fmkd_akun,$fmkd_barang,$fmurl);	
			}else{
			$getdaftar=$this->genContentJurnal($fmbidang,$fmskpd,$fmunit,$fmsubunit,$fmtahun,$fmsemester,$fmkd_akun,$fmkd_barang,"tes");
			}
			echo $getdaftar;
			break;
		}
		
	  
		   	   
	   default:{
			$string=$this->genContentJurnal($fmbidang,$fmskpd,$fmunit,$fmsubunit,$fmtahun,$fmsemester,$fmkd_akun,$fmkd_barang);
			
			$xml = simplexml_load_string($string);
			echo header('Content-Type: text/xml')."".$Main->base.$string;
			
	 break;
	 }
	 }//end switch
		
		//return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   }
	
	
	
	function genContentJurnal($c="",$d="",$e="",$e1="",$tahun_="",$semester_="",$kd_akun_="",$kd_barang_="",$tipe_="xml"){	
		global $Main;		
		$buildDate=TglInd(date("Y-m-d"));	
		$kd_barang_exp = explode('.',$kd_barang_);
		 $f = $kd_barang_exp[0];
		 $g = $kd_barang_exp[1];
		 $h = $kd_barang_exp[2];
		 $i = $kd_barang_exp[3];
		 $j = $kd_barang_exp[4];
		
		$kode_akun_exp = explode('.',$kd_akun_);
		 $k = $kode_akun_exp[0];
		 $l = $kode_akun_exp[1];
		 $m = $kode_akun_exp[2];
		 $n = $kode_akun_exp[3];
		 $o = $kode_akun_exp[4];
		 $p = $kode_akun_exp[5];
		 $groupbyakun="";
		
		/*++++++++++++++++++++++++++++ Kondisi ++++++++++++++++++++++++++++++*/	
			$arrKondisi = array();
			if(!($c=='' || $c=='00') ) $arrKondisi[] = "c='$c'";
			if(!($d=='' || $d=='00') ) $arrKondisi[] = "d='$d'";
			if(!($kd_barang_=='') ) $arrKondisi[] = "concat(f,'.',g,'.',h,'.',i,'.',j)='$kd_barang_'";
			if(!($k=='') ) $arrKondisi[] = "k='$k'";
			if(!($l=='') ) $arrKondisi[] = "l='$l'";
			if(!($m=='') ) $arrKondisi[] = "m='$m'";
			if(!($n=='') ) $arrKondisi[] = "n='$n'";
			if(!($o=='') ) $arrKondisi[] = "o='$o'";
			if(!($p=='') ) $arrKondisi[] = "p='$p'";
			
			
			if(!($tahun_=='') ){
				$arrKondisi[] = "YEAR(tgl_buku)='$tahun_'";
				switch($semester_){			
				case '1': $arrKondisi[] = " tgl_buku>='".$tahun_."-01-01' and  cast(tgl_buku as DATE)<='".$tahun_."-06-30' "; break;
				case '2': $arrKondisi[] = " tgl_buku>='".$tahun_."-07-01' and  cast(tgl_buku as DATE)<='".$tahun_."-12-31' "; break;
				default :""; break;
				}
			}
			$Kondisi = join(' and ',$arrKondisi); $cek .=$Kondisi;
			$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
			
		/*--------------------------------- Mendapatkan group, order dan level kode akun --------------------------------*/	
			if($groupbyakun=="" && $kd_akun_==""){
				$groupbyakun=" group by k ";
				$rollup=" with ROLLUP ";
				$order=" order by k ";
				$lvlakun=1;	
			}
			if($groupbyakun=="" && $k!=""){
				$groupbyakun=" group by k,l ";
				$rollup=" with ROLLUP ";
				$order=" order by k,l ";
				$lvlakun=2;	
			}
			if($groupbyakun=="" && $l!=""){
				$groupbyakun=" group by k,l,m ";
				$rollup=" with ROLLUP ";	
				$order=" order by k,l,m ";
				$lvlakun=3;
			}
			if($groupbyakun=="" && $m!=""){
				$groupbyakun=" group by k,l,m,n ";	
				$rollup=" with ROLLUP ";
				$order=" order by k,l,m,n ";
				$lvlakun=4;	
			}
			if($groupbyakun=="" && $n!=""){
				$groupbyakun=" group by k,l,m,n,o ";
				$rollup=" with ROLLUP ";	
				$order=" order by k,l,m,n,o ";
				$lvlakun=5;	
			}
			if($groupbyakun=="" && $o!=""){
				$groupbyakun=" group by k,l,m,n,o,p ";
				$rollup=" with ROLLUP ";	
				$order=" order by k,l,m,n,o,p ";
				$lvlakun=6;	
			}
			if($groupbyakun=="" && $p!=""){
				$groupbyakun=" group by k,l,m,n,o,p ";
				$rollup=" with ROLLUP ";
				$order=" order by k,l,m,n,o,p ";
				$lvlakun=6;	
			}
		
		
		
		
		/*---------------------------------------- GET DATA SKPD --------------------------------------*/		
		if(!($c == '' || $c == '00')){
			$get = mysql_fetch_array(mysql_query(
				"select * from v_bidang where c='".$c."' "
			));		
			if($get['nmbidang']<>'') $nm_bidang = $get['c'].". ".$get['nmbidang'];
		}else{
			$nm_bidang="Semua Bidang";
		}
		
		if(!($d == '' || $d == '00')){
			$get = mysql_fetch_array(mysql_query(
				"select * from v_opd where c='".$c."' and d='".$d."' "
			));		
			if($get['nmopd']<>'') $nm_skpd = $get['d'].". ".$get['nmopd'];
		}else{
			$nm_skpd="Semua SKPD";
		}
		
		if(!($e == '' || $e == '00')){
			$get = mysql_fetch_array(mysql_query(
				"select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."'"
			));		
			if($get['nmunit']<>'') $nm_unit = $get['e'].". ".$get['nmunit'];
		}else{
			$nm_unit="Semua Unit";
		}
		
		if(!($e1 == '' || $e1 == '000')){
			$get = mysql_fetch_array(mysql_query(
				"select nm_skpd as nmseksi from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."' and e1='".$e1."'"
			));		
			if($get['nmseksi']<>'') $nm_sub_unit = $get['e1'].". ".$get['nmseksi'];
		}else{
			$nm_sub_unit="Semua Sub Unit";
		}
		
		/*-------------------------------------- INISIASI DATA KOSONG -------------------------------*/
		$kd_akun_=$kd_akun_==""?" ":$kd_akun_;
		$tahun_=$tahun_==""?"Semua Tahun":$tahun_;
		$semester_=$semester_==""?"-":$semester_;
		$kd_barang_=$kd_barang_==""?"Semua Kode Barang":$kd_barang_;
		
		/*---------------------------------------- GEN QUERY --------------------------------------*/
		$jd=mysql_fetch_array(mysql_query("select count(*) as jml_data from (select * from $this->TblName $Kondisi $groupbyakun $rollup ) aa $order"));
		//inisiasi jumlah Item
		$jml_item= $jd['jml_data'];	
		$aqry="select * from (select *,sum(kredit) as tot_kredit,sum(debet) as tot_debet from $this->TblName $Kondisi $groupbyakun $rollup ) aa $order";
		
		if($tipe_=="xml"){
		/*---------------------------------------- GET DATA ITEM --------------------------------------*/
			$Query = mysql_query($aqry);		
			while ($item=mysql_fetch_array($Query)) {
				if($item['k']=="" || $item['l']=="" || $item['m']=="" || $item['n']=="" || $item['o']=="" || $item['p']=="" ){
					if($item['k']==""){
					$tot_saldo_awal=$item['tot_debet']-$item['tot_kredit'];
					$tot_debet=$item['tot_debet'];
					$tot_kredit=$item['tot_kredit'];
					}
					$jml_item= $jml_item-1;	
				}else{
					$saldo_awal=$item['tot_debet']-$item['tot_kredit'];
					$debet=$item['tot_debet'];
					$kredit=$item['tot_kredit'];
					$kd_akun_item=$this->genKodeAccount($item['k'],$item['l'],$item['m'],$item['n'],$item['o'],$item['p'],$lvlakun);
					$items.="<item>".
							"<kdakun>".$kd_akun_item."</kdakun>".
							"<saldo_awal>Rp ".number_format($saldo_awal,2,',','.')."</saldo_awal>".
							"<debet>Rp ".number_format($debet,2,',','.')."</debet>".
							"<kredit>Rp ".number_format($kredit,2,',','.')."</kredit>".
						"</item>";		
				}
			}
			
			$data=" ".
				"<data>".
					"<title>$this->title</title>".
					"<description>$this->description</description>".
					"<link>$this->link</link>".
					"<lastBuildDate>$buildDate</lastBuildDate>".
					"<generator>$this->generator</generator>".
					"<language>$this->language</language>".
					"<bidang>$nm_bidang</bidang>".
					"<skpd>$nm_skpd</skpd>".
					"<unit>$nm_unit</unit>".
					"<subunit>$nm_sub_unit</subunit>".
					"<tahun>$tahun_</tahun>".
					"<semester>$semester_</semester>".
					"<kdakun>$kd_akun_</kdakun>".
					"<kdbarang>$kd_barang_</kdbarang>".
					"<jml_item>".$jml_item."</jml_item>".				
					"<total_saldo_awal>Rp ".number_format($tot_saldo_awal,2,',','.')."</total_saldo_awal>".
					"<total_debet>Rp ".number_format($tot_debet,2,',','.')."</total_debet>".
					"<total_kredit>Rp ".number_format($tot_kredit,2,',','.')."</total_kredit>".
					$items.
				"</data>";			
			
					
			
			//tampil --------------------------------------------------------------------
			$content = 
				"<xml version='1.0' encoding='UTF-8'>
					$data				
				</xml>";
						
		}else{
			
			$kolom_head="<thead>
						<tr>
						<th class='th01' >kode Akun</th>												
						<th class='th01' >Saldo Awal (Rp)</th>
						<th class='th01' >Debet (Rp)</th>
						<th class='th01' >Kredit (Rp)</th>						
						</tr>			
					</thead>";
			
			$Query = mysql_query($aqry);		
			while ($item=mysql_fetch_array($Query)) {
				if($item['k']=="" || $item['l']=="" || $item['m']=="" || $item['n']=="" || $item['o']=="" || $item['p']=="" ){
					if($item['k']==""){
					$tot_saldo_awal=$item['tot_debet']-$item['tot_kredit'];
					$tot_debet=$item['tot_debet'];
					$tot_kredit=$item['tot_kredit'];
					}
					$jml_item= $jml_item-1;	
				}else{
					$saldo_awal=$item['tot_debet']-$item['tot_kredit'];
					$debet=$item['tot_debet'];
					$kredit=$item['tot_kredit'];
					$kd_akun_item=$this->genKodeAccount($item['k'],$item['l'],$item['m'],$item['n'],$item['o'],$item['p'],$lvlakun);
					$kolom_data.="<tr>
						<td align='center'>$kd_akun_item</td>												
						<td align='right'>".number_format($saldo_awal,2,',','.')."</td>
						<td align='right'>".number_format($debet,2,',','.')."</td>
						<td align='right'>".number_format($kredit,2,',','.')."</td>						
						</tr>";
						
				}
			}
			$Header_data="<table>".
						"<tr><td >Bidang</th>	<td >:</th> <td colspan=2>$nm_bidang</th></tr>".
						"<tr><td >SKPD</th>	<td >:</th> <td colspan=2>$nm_skpd</th></tr>".
						"<tr><td >Unit</th>	<td >:</th> <td colspan=2>$nm_unit</th></tr>".
						"<tr><td >Sub Unit</th>	<td >:</th> <td colspan=2>$nm_sub_unit</th></tr>".
						"<tr><td >Tahun</th>	<td >:</th> <td colspan=2>$tahun_</th></tr>".
						"<tr><td >Semester</th>	<td >:</th> <td colspan=2>$semester_</th></tr>".
						"<tr><td >Kode Akun</th>	<td >:</th> <td colspan=2>$kd_akun_</th></tr>".
						"<tr><td >Kode Barang</th>	<td >:</th> <td colspan=2>$kd_barang_</th></tr>".
						"<tr><td >Jumlah Item</th>	<td >:</th> <td colspan=2>$jml_item</th></tr>".
						"<tr><td >Total Saldo Awal</th>	<td >:</th> <td >Rp</th> <td align='right'>".number_format($tot_saldo_awal,2,',','.')."</th></tr>".
						"<tr><td >Total Debet</th>	<td >:</th> <td >Rp</th> <td align='right'>".number_format($tot_debet,2,',','.')."</th></tr>".
						"<tr><td >Total Kredit</th>	<td >:</th> <td >Rp</th> <td align='right'>".number_format($tot_kredit,2,',','.')."</th></tr>".
						"</table>";
			$content = 
				$Header_data.
				"<table class='koptable' border='1' style='margin:4 0 0 0;width:100%'>
					$kolom_head
					$kolom_data				
				</table>";
		}		
		
		return $content;
		
	}
	
	function genContentURL($c="",$d="",$e="",$e1="",$tahun_="",$semester_="",$kd_akun_="",$kd_barang_="",$URL="#"){	
		global $Main;		
		$buildDate=TglInd(date("Y-m-d"));	
				
		/*++++++++++++++++++++++++++++ Kondisi ++++++++++++++++++++++++++++++*/	
			$arrURLVal = array();
			if(!($c=='' || $c=='00') ) $arrURLVal[] = "bidang=$c";
			if(!($d=='' || $d=='00') ) $arrURLVal[] = "skpd=$d";
			if(!($e=='' || $e=='00') ) $arrURLVal[] = "unit=$e";
			if(!($e1=='' || $e1=='000') ) $arrURLVal[] = "subunit=$e1";
			if(!($tahun_=='') ) $arrURLVal[] = "tahun=$tahun_";
			if(!($semester_=='') ) $arrURLVal[] = "semester=$semester_";
			if(!($kd_barang_=='') ) $arrURLVal[] = "kdbarang=$kd_barang_";
			if(!($kd_akun_=='') ) $arrURLVal[] = "kdakun=$kd_akun_";
						
			$URLVal = join('&',$arrURLVal); $cek .=$URLVal;
			$URLVal = $URLVal =='' ? '':'&'.$URLVal;
			
		$URL="http://".$URL.$URLVal;	
		$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
		$string = file_get_contents($URL,false,$context);
		$xml = simplexml_load_string($string);
				foreach($xml->data->item as $isi){
				$kolom_data.="<tr>
						<td align='center'>".$isi->kdakun."</td>												
						<td align='right'>".$isi->saldo_awal."</td>
						<td align='right'>".$isi->debet."</td>
						<td align='right'>".$isi->kredit."</td>						
						</tr>";
				}
		
		//---------------------------------- Generate Data Dari URL XML -----------------------------------------------------
		$kolom_head="<thead>
						<tr>
						<th class='th01' >kode Akun</th>												
						<th class='th01' >Saldo Awal (Rp)</th>
						<th class='th01' >Debet (Rp)</th>
						<th class='th01' >Kredit (Rp)</th>						
						</tr>			
					</thead>";
			
			$Header_data="<table>".
						"<tr><td >URL</th>	<td >:</th> <td >$URL</th></tr>".
						"<tr><td >Bidang</th>	<td >:</th> <td >".$xml->data->bidang."</th></tr>".
						"<tr><td >SKPD</th>	<td >:</th> <td >".$xml->data->skpd."</th></tr>".
						"<tr><td >Unit</th>	<td >:</th> <td >".$xml->data->unit."</th></tr>".
						"<tr><td >Sub Unit</th>	<td >:</th> <td >".$xml->data->subunit."</th></tr>".
						"<tr><td >Tahun</th>	<td >:</th> <td >".$xml->data->tahun."</th></tr>".
						"<tr><td >Semester</th>	<td >:</th> <td >".$xml->data->semester."</th></tr>".
						"<tr><td >Kode Akun</th>	<td >:</th> <td >".$xml->data->kdakun."</th></tr>".
						"<tr><td >Kode Barang</th>	<td >:</th> <td >".$xml->data->kdbarang."</th></tr>".
						"<tr><td >Jumlah Item</th>	<td >:</th> <td >".$xml->data->jml_item."</th></tr>".
						"<tr><td >Total Saldo Awal</th>	<td >:</th> <td align='right'>".$xml->data->total_saldo_awal."</th></tr>".
						"<tr><td >Total Debet</th>	<td >:</th> <td align='right'>".$xml->data->total_debet."</th></tr>".
						"<tr><td >Total Kredit</th>	<td >:</th> <td align='right'>".$xml->data->total_kredit."</th></tr>".
						"</table>";
			$content = 
				$Header_data.
				"<table class='koptable' border='1' style='margin:4 0 0 0;width:100%'>
					$kolom_head
					$kolom_data				
				</table>";
								
		return $content;
		
	}
	
	function genKodeAccount($k="",$l="",$m="",$n="",$o="",$p="",$lvlakun=""){	
		global $Main;
		$k_= $k=="" ? "0":$k;
		$l_= $l=="" ? "0":$l;
		$m_= $m=="" ? "0":$m;
		$n_= $n=="" ? "0":$n;
		$o_= $o=="" ? "0":$o;
		$p_= $p=="" ? "0":$p;
		switch($lvlakun){
			case 1:{
				$KodeAccount="$k_.";
				break;
			}
			case 2:{
				$KodeAccount="$k_.$l_.";
				break;
			}
			case 3:{
				$KodeAccount="$k_.$l_.$m_.";
				break;
			}
			case 4:{
				$KodeAccount="$k_.$l_.$m_.$n_.";	
				break;
			}
			case 5:{
				$KodeAccount="$k_.$l_.$m_.$n_.$o_.";
				break;
			}
			case 6:{
				$KodeAccount="$k_.$l_.$m_.$n_.$o_.$p_";
				break;
			}
		}
		
		return $KodeAccount;
		
	}
	
	
}
$Jurnalxml = new JurnalxmlObj();

?>