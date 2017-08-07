<?php

$HalDPSB = cekPOST("HalDPSB",1);
$HalIDI = cekPOST("HalIDI",1);
$LimitHalDPSB = " limit ".(($HalDPSB*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;
$LimitHalIDI = " limit ".(($HalIDI*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$cDPSBDI = cekPOST("cDPSBDI");
$cidIDI = cekPOST("cidIDI");


$fmKEPEMILIKAN = cekPOST("fmKEPEMILIKAN");
setWilSKPD();

$fmIDBARANG = cekPOST("fmIDBARANG");
$fmNMBARANG = cekPOST("fmNMBARANG");
$fmREGISTER = cekPOST("fmREGISTER");
$fmTAHUNPEROLEHAN = cekPOST("fmTAHUNPEROLEHAN");
$fmJUMLAH = cekPOST("fmJUMLAH");
$fmSATUAN = cekPOST("fmSATUAN");
$fmHARGABARANG = cekPOST("fmHARGABARANG");
$fmJUMLAHHARGA = cekPOST("fmJUMLAHHARGA");
$fmASALUSUL = cekPOST("fmASALUSUL");
$fmSTATUSBARANG = cekPOST("fmSTATUSBARANG");
$fmKONDISIBARANG = cekPOST("fmKONDISIBARANG");

$fmTGLUPDATE = cekPOST("fmTGLUPDATE",date("d-m-Y"));

$InfoIDBARANG = explode(".",$fmIDBARANG);
$IDKIB = cekPOST("IDKIB");
$InfoIDBARANG[0] = $IDKIB;
$MyFieldKIB="";
$InfoKIB = "";
//KIB A
if($InfoIDBARANG[0]=="01")
{
	$fmLUAS_KIB_A = cekPOST("fmLUAS_KIB_A");
	$fmLETAK_KIB_A = cekPOST("fmLETAK_KIB_A");
	$fmHAKPAKAI_KIB_A = cekPOST("fmHAKPAKAI_KIB_A");
	$fmTGLSERTIFIKAT_KIB_A = cekPOST("fmTGLSERTIFIKAT_KIB_A");
	$fmNOSERTIFIKAT_KIB_A = cekPOST("fmNOSERTIFIKAT_KIB_A");
	$fmPENGGUNAAN_KIB_A = cekPOST("fmPENGGUNAAN_KIB_A");
	$fmKET_KIB_A = cekPOST("fmKET_KIB_A");
	$MyFieldKIB = "fmLUAS_KIB_A,fmLETAK_KIB_A,fmHAKPAKAI_KIB_A,fmTGLSERTIFIKAT_KIB_A,fmNOSERTIFIKAT_KIB_A,fmPENGGUNAAN_KIB_A,fmKET_KIB_A";
	$InfoKIB = "KIB A";
}
//KIB B
if($InfoIDBARANG[0]=="02")
{
$fmMERK_KIB_B = cekPOST("fmMERK_KIB_B");
$fmUKURAN_KIB_B = cekPOST("fmUKURAN_KIB_B");
$fmBAHAN_KIB_B = cekPOST("fmBAHAN_KIB_B");
$fmPABRIK_KIB_B = cekPOST("fmPABRIK_KIB_B");
$fmRANGKA_KIB_B = cekPOST("fmRANGKA_KIB_B");
$fmMESIN_KIB_B = cekPOST("fmMESIN_KIB_B");
$fmPOLISI_KIB_B = cekPOST("fmPOLISI_KIB_B");
$fmBPKB_KIB_B = cekPOST("fmBPKB_KIB_B");
$fmKET_KIB_B = cekPOST("fmKET_KIB_B");
$MyFieldKIB = "fmMERK_KIB_B,fmUKURAN_KIB_B,fmBAHAN_KIB_B,fmPABRIK_KIB_B,fmRANGKA_KIB_B,fmMESIN_KIB_B,fmPOLISI_KIB_B,fmBPKB_KIB_B,fmKET_KIB_B";
$InfoKIB = "KIB B";
}




$Cari = cekPOST("Cari");
$CariBarang = cekGET("CariBarang");
$CariRekening = cekGET("CariRekening");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));

$Act = cekPOST("Act");
$Baru = cekPOST("Baru","1");
$Info = "";

//Field UTAMA : fmIDBARANG,fmNMBARANG,fmREGISTER,fmTAHUNPEROLEHAN,fmJUMLAH,fmSATUAN,fmHARGABARANG,fmJUMLAHHARGA,fmASALUSUL,fmSTATUSBARANG,fmTGLUPDATE
//ProsesCekField
$MyField ="fmKEPEMILIKAN,fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmREGISTER,fmTAHUNPEROLEHAN,fmJUMLAH,fmSATUAN,fmHARGABARANG,fmASALUSUL,fmSTATUSBARANG,fmTGLUPDATE,fmTAHUNANGGARAN";
if($Act=="Simpan")
{
	if(ProsesCekField($MyField) && ProsesCekField($MyFieldKIB))
		{
		$ArBarang = explode(".",$fmIDBARANG);
		$fmJUMLAHHARGA = $fmJUMLAH * $fmHARGABARANG;
		$Simpan = false;
		$Kriteria = "concat(a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg)='$fmKEPEMILIKAN{$Main->Provinsi[0]}$fmWIL$fmSKPD$fmUNIT$fmSUBUNIT{$ArBarang[0]}{$ArBarang[1]}{$ArBarang[2]}{$ArBarang[3]}{$ArBarang[4]}$fmTAHUNANGGARAN$fmREGISTER'";
		$CekBaru = mysql_num_rows(mysql_query("select * from buku_induk where $Kriteria"));
		if($Baru=="1")
			{
			if(!$CekBaru)
				{
				//Simpan Baru
				$Qry = "insert into buku_induk (a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg,tgl_perolehan,jml_barang,satuan,harga,jml_harga,asal_usul,status_barang,tgl_update,kondisi)
				values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmTAHUNANGGARAN','$fmREGISTER','".TglSQL($fmTAHUNPEROLEHAN)."','$fmJUMLAH','$fmSATUAN','$fmHARGABARANG','$fmJUMLAHHARGA','$fmASALUSUL','$fmSTATUSBARANG','".TglSQL($fmTGLUPDATE)."','$fmKONDISIBARANG')";
				$Simpan = mysql_query($Qry);
				$InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,f,g,h,i,j,noreg,tahun,tgl_update,kejadian,kondisi,status_barang)values('{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmTAHUNANGGARAN','$fmREGISTER','".TglSQL($fmTGLUPDATE)."','Entry Inventaris','$fmKONDISIBARANG','$fmSTATUSBARANG')");
				

				//SIMPAN KIB A
				if($InfoIDBARANG[0]=="01")
				{
					/*
						fmLUAS_KIB_A
						fmLETAK_KIB_A
						fmHAKPAKAI_KIB_A
						fmTGLSERTIFIKAT_KIB_A
						fmNOSERTIFIKAT_KIB_A
						fmPENGGUNAAN_KIB_A
						fmKET_KIB_A
					*/
					$Qry = "insert into kib_a (a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg,luas,alamat,status_hak,sertifikat_tgl,sertifikat_no,penggunaan,ket)
					values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmTAHUNANGGARAN','$fmREGISTER','$fmLUAS_KIB_A','$fmLETAK_KIB_A','$fmHAKPAKAI_KIB_A','".TglSQL($fmTGLSERTIFIKAT_KIB_A)."','$fmNOSERTIFIKAT_KIB_A','$fmPENGGUNAAN_KIB_A','$fmKET_KIB_A')";
					$Simpan = mysql_query($Qry);
				}

				//SIMPAN KIB B
				if($InfoIDBARANG[0]=="02")
				{
					/*
						fmMERK_KIB_B
						fmUKURAN_KIB_B
						fmBAHAN_KIB_B
						fmPABRIK_KIB_B
						fmRANGKA_KIB_B
						fmMESIN_KIB_B
						fmPOLISI_KIB_B
						fmBPKB_KIB_B
						fmKET_KIB_B
					*/
					$Qry = "insert into kib_b (a1,a,b,c,d,e,f,g,h,i,j,tahun,noreg,merk,ukuran,bahan,no_pabrik,no_rangka,no_mesin,no_polisi,no_bpkb,ket)
					values ('$fmKEPEMILIKAN','{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmTAHUNANGGARAN','$fmREGISTER','$fmMERK_KIB_B','$fmUKURAN_KIB_B','$fmBAHAN_KIB_B','$fmPABRIK_KIB_B','$fmRANGKA_KIB_B','$fmMESIN_KIB_B','$fmPOLISI_KIB_B','$fmBPKB_KIB_B','$fmKET_KIB_B')";
					$Simpan = mysql_query($Qry);
				}


				}
			else
				{
				$Info = "<script>alert('Data TIDAK dapat disimpan\\nNomor Register fmREGISTER sudah ada!!!')</script>";
				}
			}
			if($Baru=="0")
			{
				$Qry = "
				update buku_induk set thn_perolehan='$fmTAHUNPEROLEHAN', jml_barang='$fmJUMLAH',kondisi='$fmKONDISI',satuan='$fmSATUAN',harga='$fmHARGABARANG',jml_harga='$fmJUMLAHHARGA',asal_usul='$fmASALUSUL',status_barang='$fmSTATUSBARANG',tgl_update='$fmTGLUPDATE'
				where $Kriteria ";
				$InsertHistory = mysql_query("insert into history_barang (a,b,c,d,e,f,g,h,i,j,tahun,noreg,tgl_update,kejadian,kondisi,status_barang)values('{$Main->Provinsi[0]}','$fmWIL','$fmSKPD','$fmUNIT','$fmSUBUNIT','{$ArBarang[0]}','{$ArBarang[1]}','{$ArBarang[2]}','{$ArBarang[3]}','{$ArBarang[4]}','$fmTAHUNANGGARAN','$fmREGISTER','".TglSQL($fmTGLUPDATE)."','Update Inventaris','$fmKONDISIBARANG','$fmSTATUSBARANG')");
				$Simpan = mysql_query($Qry);
			}
			if($Simpan)
			{
				$Info = "<script>alert('Data telah di simpan')</script>";
				$Baru="0";
			}
			else
			{
				$Info = "<script>alert('Data TIDAK dapat disimpan')</script>";
			}
		
	}
	else
	{
		$Info = "<script>alert('Data TIDAK Lengkap\\nLengkapi untuk dapat di simpan')</script>";
	}
	
}




//Proses EDIT

$cidDPSB = CekPOST("cidDPSB");
$cidNya = $cidDPSB;
if($Act == "Edit")
{
	$cidBI = CekPOST("cidBI");
	$cidNya = $cidBI;
}




if($Act=="Edit"|| $Act == "TambahEdit")
{
	if(count($cidNya) != 1)
	{
		$Info = "<script>alert('Pilih hanya satu ID yang dapat di Ubah')</script>";
	}
	else
	{
		if($Act=="Edit")
		{
			$Qry = mysql_query("select * from penetapan where id='{$cidNya[0]}'");
		}
		else
		{
			$Qry = mysql_query("select penetapan.* from penetapan inner join pengeluaran  using(id) where penetapan.id='{$cidNya[0]}'");
			//echo "select penetapan.* from penetapan inner join pengeluaran on penetapan.id_pengeluaran=pengeluaran.id where penetapan.id='{$cidNya[0]}'";
		}
		$isi = mysql_fetch_array($Qry);

		$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
//		$kdRekening = $isi['k'].$isi['l'].$isi['m'].$isi['n'].$isi['o'];
//		$nmRekening = mysql_fetch_array(mysql_query("select * from ref_rekening where concat(k,l,m,n,o)='$kdRekening'"));
		$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
		
		$fmIDBARANG = $isi['f'].".".$isi['g'].".".$isi['h'].".".$isi['i'].".".$isi['j'];
		$fmIDBARANG = $fmIDBARANG == "...." ? "":$fmIDBARANG;
		$fmNMBARANG = "{$nmBarang['nm_barang']}";
		$fmMEREK = "{$isi['merk_barang']}";
		$fmJUMLAH = "{$isi['jml_barang']}";
		$fmSATUAN = "{$isi['satuan']}";
		$fmHARGABARANG = "{$isi['harga']}";
		$fmKET = "{$isi['ket']}";
		$fmTAHUNPEROLEHAN= TglInd("{$isi['tgl_beli']}");

		if($Act=="Edit")
		{
			$fmIDPENETAPAN=$isi['id_pengeluaran'];
		}
		else
		{
			$fmIDPENETAPAN=$isi['id'];
		}
		$fmID = "{$isi['id']}";

		if($Act == "TambahEdit")
		{
			$Baru=1;
		}
		else
		{

		$fmNOSKGUBERNUR=$isi['skgub_no'];
		$fmTANGGALSKGUBERNUR=TglInd($isi['skgub_tgl']);
		$fmKONDISIBAIK=$isi['jml_baik'];
		$fmKONDISIKURANGBAIK=$isi['jml_kbaik'];
		$fmTANGGALBELI=TglInd($isi['tgl_beli']);

			$Baru=0;
		}
	}
}




//List Kepemilikan
$Qry = mysql_query("select * from ref_pemilik order by nm_pemilik");
$Ops = "";
while($isi=mysql_fetch_array($Qry))
{
	$sel = $fmKEPEMILIKAN == $isi['a1'] ? "selected":"";
	$Ops .= "<option $sel value='{$isi['a1']}'>{$isi['nm_pemilik']}</option>\n";
}
$ListKepemilikan = "<select name='fmKEPEMILIKAN'  onChange=\"adminForm.submit()\"><option value=''>--- Pilih Kepemilikan ---</option>$Ops</select>";





$DetilKIB = "";
//KIB A
if($InfoIDBARANG[0]=="01")
{
	$InfoKIB = "KIB A";
	$DetilKIB = "
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
			<tr valign=\"top\">   
				<td width='245'>Luas</td>
				<td width='25'>:</td>
				<td><input type=text name='fmLUAS_KIB_A' value='$fmLUAS_KIB_A' size='15' >&nbsp;&nbsp;M<sup>2</sup>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Letak</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=2 name='fmLETAK_KIB_A'>$fmLETAK_KIB_A</textarea>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245' colspan=3>Status Tanah :</td>
			</tr>
			<tr valign=\"top\">   
				<td>&nbsp;&nbsp;&nbsp;&nbsp;Hak </td>
				<td>:</td>
				<td>".cmb2D('fmHAKPAKAI_KIB_A',$fmHAKPAKAI_KIB_A,$Main->StatusHakPakai,'')."</td>
			</tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal Sertifikat  </td><td>:</td><td>".InputKalender($NAMA="fmTGLSERTIFIKAT_KIB_A")."</td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor Sertifikat  </td><td>:</td><td>".txtField('fmNOSERTIFIKAT_KIB_A',$fmNOSERTIFIKAT_KIB_A,'100','20','text','')."</td></tr>
			<tr valign=\"top\">   
				<td width='245'>Penggunaan</td>
				<td width='25'>:</td>
				<td>
					".txtField('fmPENGGUNAAN_KIB_A',$fmPENGGUNAAN_KIB_A,'100','20','text','')."
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Keterangan</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=2 name='fmKET_KIB_A'>$fmKET_KIB_A</textarea>
				</td>
			</tr>

		</table>		
	";
}
if($InfoIDBARANG[0]=="02")
{
	$InfoKIB = "KIB B";
	$DetilKIB = "		<table width=\"100%\" height=\"100%\" class=\"adminform\">
			<tr valign=\"top\">   
				<td width='245'>Merk/Type</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=2 name='fmMERK_KIB_B'>$fmMERK_KIB_B</textarea>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Ukuran/CC</td>
				<td width='25'>:</td>
				<td>
					".txtField('fmUKURAN_KIB_B',$fmUKURAN_KIB_B,'100','20','text','')."
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Bahan</td>
				<td width='25'>:</td>
				<td>
					".txtField('fmBAHAN_KIB_B',$fmBAHAN_KIB_B,'100','20','text','')."
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245' colspan=3>Nomor :</td>
			</tr>
			
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Pabrik </td><td>:</td><td>".txtField('fmPABRIK_KIB_B',$fmPABRIK_KIB_B,'100','20','text','')."</td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Rangka </td><td>:</td><td>".txtField('fmRANGKA_KIB_B',$fmRANGKA_KIB_B,'100','20','text','')."</td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Mesin </td><td>:</td><td>".txtField('fmMESIN_KIB_B',$fmMESIN_KIB_B,'100','20','text','')."</td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Polisi </td><td>:</td><td>".txtField('fmPOLISI_KIB_B',$fmPOLISI_KIB_B,'100','20','text','')."</td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;BPKB </td><td>:</td><td>".txtField('fmBPKB_KIB_B',$fmBPKB_KIB_B,'100','20','text','')."</td></tr>
			<tr valign=\"top\">   
				<td width='245'>Keterangan</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=2 name='fmKET_KIB_B'>$fmKET_KIB_B</textarea>
				</td>
			</tr>
			
		</table>		
	";
}
if($InfoIDBARANG[0]=="03")
{
	$InfoKIB = "KIB C";
	$DetilKIB = "		<table width=\"100%\" height=\"100%\" class=\"adminform\">
			<tr valign=\"top\">   
				<td width='245'>Kondisi Bangunan</td>
				<td width='25'>:</td>
				<td>
					<select>
						<option>Baik</option>
						<option>Kurang Baik</option>
						<option>Rusak Berat</option>
					</select>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245' colspan=3>Kontruksi Bangunan</td>
			</tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Bersetifikat/Tidak </td><td>:</td><td><select><option>Bersertifikat</option><option>Tidak</option></select></td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Beton/Tidak </td><td>:</td><td><select><option>Beton</option><option>Tidak</option></select></td></tr>
			
			<tr valign=\"top\">   
				<td width='245'>Luas Lantai</td>
				<td width='25'>:</td>
				<td>
					<input> &nbsp;M<sup>2</sup>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Letak/Alamat</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=3></textarea>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245' colspan=3>Dokumen Gudang :</td>
			</tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td><td>:</td><td><input></td></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td><td>:</td><td><input></td></tr>
			
			<tr valign=\"top\">   
				<td width='245'>Luas</td>
				<td width='25'>:</td>
				<td>
					<input> &nbsp;M<sup>2</sup>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Status Tanah</td>
				<td width='25'>:</td>
				<td>
					<select><option>Tanah Milik...</option><option>Tanah Milik...</option></select>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Nomor Kode Tanah</td>
				<td width='25'>:</td>
				<td>
					<input>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Keterangan</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=3></textarea>
				</td>
			</tr>
		</table>		
	";
}
if($InfoIDBARANG[0]=="04")
{
	$InfoKIB = "KIB D";
	$DetilKIB = "		
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
			<tr valign=\"top\">   
				<td width='245'>Konstruksi</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Panjang</td>
				<td width='25'>:</td>
				<td><input> KM</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Lebar</td>
				<td width='25'>:</td>
				<td><input> M</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Luas</td>
				<td width='25'>:</td>
				<td><input> M<sup>2</sup></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Letak/Alamat</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=3></textarea>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Dokumen :</td>
			</tr>

			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>

			<tr valign=\"top\">   
				<td width='245'>Status Tanah</td>
				<td width='25'>:</td>
				<td>
					<select><option>Tanah Milik...</option><option>Tanah Milik...</option></select>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Nomor Kode Tanah</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Kondisi</td>
				<td width='25'>:</td>
				<td>
					<select>
						<option>Baik</option>
						<option>Kurang Baik</option>
						<option>Rusak Berat</option>
					</select>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Keterangan</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=3></textarea>
				</td>
			</tr>
		</table>
	";
}

if($InfoIDBARANG[0]=="05")
{
	$InfoKIB = "KIB E";
	$DetilKIB = "		
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
			<tr valign=\"top\">   
				<td width='245'>Buku Perpustakaan :</td>
			</tr>

			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Judul/Pencipta</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Spesifikasi</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>

			<tr valign=\"top\">   
				<td width='245'>Barang bercorak Kesenian/Kebudayaan :</td>
			</tr>

			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Asal Daerah</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Pencipta</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>			
			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Bahan</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>


			<tr valign=\"top\">   
				<td width='245'>Hewan Ternak :</td>
			</tr>

			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Jenis</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Ukuran</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>			
			<tr valign=\"top\">   
				<td width='245'>Keterangan</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=3></textarea>
				</td>
			</tr>
		</table>
	";
}


if($InfoIDBARANG[0]=="06")
{
	$InfoKIB = "KIB F";
	$DetilKIB = "		
		<table width=\"100%\" height=\"100%\" class=\"adminform\">
			<tr valign=\"top\">   
				<td width='245'>Bangunan</td>
				<td width='25'>:</td>
				<td><select><option>Permanen</option><option>Semi Permanen</option><option>Darurat</option></select></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Konstruksi Bangunan :</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Bertingkat/Tidak</td>
				<td width='25'>:</td>
				<td><select><option>Bertingkat</option><option>Tidak</option></select></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Beton/Tidak</td>
				<td width='25'>:</td>
				<td><select><option>Beton</option><option>Tidak</option></select></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Luas</td>
				<td width='25'>:</td>
				<td><input> M<sup>2</sup></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Letak/Alamat</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=3></textarea>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Dokumen :</td>
			</tr>

			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>&nbsp;&nbsp;&nbsp;&nbsp;Nomor</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>

			<tr valign=\"top\">   
				<td width='245'>Tanggal/Bln./Thn. mulai</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>	

			<tr valign=\"top\">   
				<td width='245'>Status Tanah</td>
				<td width='25'>:</td>
				<td>
					<select><option>Tanah Milik...</option><option>Tanah Milik...</option></select>
				</td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Nomor Kode Tanah</td>
				<td width='25'>:</td>
				<td><input></td>
			</tr>
			<tr valign=\"top\">   
				<td width='245'>Keterangan</td>
				<td width='25'>:</td>
				<td>
					<textarea cols=60 rows=3></textarea>
				</td>
			</tr>					
		</table>
	";
}


//LIST DPSB
$KondisiD = $fmUNIT == "00" ? "":" and penetapan.d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and penetapan.e='$fmSUBUNIT' ";
$Kondisi = "penetapan.a='{$Main->Provinsi[0]}' and penetapan.b='$fmWIL' and penetapan.c='$fmSKPD' $KondisiD $KondisiE and penetapan.tahun='$fmTAHUNANGGARAN' and penetapan.f='$IDKIB'";
if(!empty($fmBARANGCARIDPSB))
{
	$Kondisi .= " and ref_barang.nm_barang like '%$fmBARANGCARIDPSB%' ";
}

//$jmlTotalHarga = mysql_query("select sum(jml_harga) as total from penetapan where $Kondisi");
$jmlTotalHarga = mysql_query("select sum(penetapan.jml_harga) as total from penetapan inner join ref_barang  using(f,g,h,i,j) where $Kondisi ");


if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}

//echo "select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j";
$Qry = mysql_query("select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j ");
$jmlDataDPSB = mysql_num_rows($Qry);
$Qry = mysql_query("select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang  using(f,g,h,i,j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDPSB");
//echo "select penetapan.*,ref_barang.nm_barang from penetapan inner join ref_barang on concat(penetapan.f,penetapan.g,penetapan.h,penetapan.i,penetapan.j)=concat(ref_barang.f,ref_barang.g,ref_barang.h,ref_barang.i,ref_barang.j) where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHalDPSB";


$JmlTotalHargaListDPSB = 0;
$no=$Main->PagePerHal * (($HalDPSB*1) - 1);
$cb=0;
$jmlTampilDPSB = 0;

$ListBarangDPSB = "";
while ($isi = mysql_fetch_array($Qry))
{
	$jmlTampilDPSB++;
	$JmlTotalHargaListDPSB += $isi['jml_harga'];

	$no++;
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListBarangDPSB .= "
	
		<tr class='$clRow'>
			<td>$no</td>
			<td><input type=\"checkbox\" id=\"cb$cb\" name=\"cidDPSB[]\" value=\"{$isi['id']}\" onClick=\"isChecked(this.checked);checkAll2( $jmlTampilDPSB, 'cb', this);
			adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='TambahEdit';adminForm.Baru.value='1';adminForm.submit()\" /></td>
			<td>{$nmBarang['nm_barang']}</td>
			<td>{$isi['merk_barang']}</td>
			<td align=right>{$isi['jml_barang']}&nbsp{$isi['satuan']}</td>
			<td align=right>".number_format($isi['harga'], 2, ',', '.')."</td>
			<td align=right>".number_format($isi['jml_harga'], 2, ',', '.')."</td>
			<td align=left>{$isi['skgub_no']} / ".TglInd($isi['skgub_tgl'])."</td>
			<td align=left>".TglInd($isi['tgl_beli'])."</td>
			<td align=right>{$isi['jml_baik']}</td>
			<td align=right>{$isi['jml_kbaik']}</td>
			<td>{$isi['ket']}</td>
		</tr>

		";
	$cb++;
}
$ListBarangDPSB .= "<tr><td colspan=6>Total Harga (Rp)</td><td align=right><b>".number_format($JmlTotalHargaListDPSB, 2, ',', '.')."</td><td colspan=2 align=right>&nbsp;</td></tr>";
//ENDLIST DPSB


$Main->Isi = "
<A Name=\"ISIAN\"></A>
$Info
<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg#ISIAN\">
<table class=\"adminheading\">
<tr>
  <th height=\"47\" class=\"user\">Input Data Inventaris ($InfoKIB)</th>
			<td lign=right>
			<table width=200 align=center border=1 cellpadding=0 cellspacing=0>
				<tr><td>
				<INPUT TYPE=TEXT value='$fmKEPEMILIKAN' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#0000EE' readonly>.
				<INPUT TYPE=TEXT value='{$Main->Provinsi[0]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#0000EE'  readonly>.
				<INPUT TYPE=TEXT value='$fmWIL' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#0000EE'  readonly>.
				<INPUT TYPE=TEXT value='$fmSKPD' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#0000EE'  readonly>.
				<INPUT TYPE=TEXT value='$fmUNIT' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#0000EE'  readonly>.
				<INPUT id='infofmTAHUNPEROLEHAN' TYPE=TEXT value='".substr($fmTAHUNPEROLEHAN,2,2)."' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#0000EE'  readonly>.
				<INPUT TYPE=TEXT value='$fmSUBUNIT' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#0000EE'  readonly>
				</td></tr>
			
				<tr><td>
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[0]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#EE0000' readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[1]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#EE0000'  readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[2]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#EE0000'  readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[3]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#EE0000'  readonly>.
				<INPUT TYPE=TEXT value='{$InfoIDBARANG[4]}' style='width:20px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#EE0000'  readonly>.
				<INPUT id='infofmREGISTER' TYPE=TEXT value='$fmREGISTER' style='width:55px;font-size:16px;border:none;background:#ffffff;font-weight:800;color:#990000'  readonly>
				</td></tr>
			</table>
		</td>
</tr>
</table>

<table width=\"100%\">
<tr>
<td width=\"60%\" valign=\"top\">

	".WilSKPD()."
	<table width=\"100%\" height=\"100%\" class=\"adminform\">
		<tr>
			<td width='200'>KEPEMILIKAN BARANG</td>
			<td width='30'>:</td>
			<td>$ListKepemilikan</td>
		</tr>
		<tr>
			<td width='200'>DATA KIB</td>
			<td width='30'>:</td>
			<td>".cmb2D_KIB("IDKIB",$IDKIB,$Main->KIB," onChange=\"adminForm.submit()\" ")."</td>

		</tr>

	</table>


<!-DPSB-->
<BR>
	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">
	<td class=\"contentheading\">
	<DIV ALIGN=CENTER>DAFTAR PENETAPAN BARANG</DIV>
	</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td width=10% >Nama Barang</td>
		<td width=1% >:</td>
		<td>
		<input type=text name='fmBARANGCARIDPSB' value='$fmBARANGCARIDPSB'>&nbsp<input type=button value='Cari' onclick=\"adminForm.submit()\">
		</td>
	</tr>
	</table>

	<table width=\"100%\" height=\"100%\" class=\"adminlist\" BORDER=1>
	<TR class='title'>
		<TH>No</TD>
		<TH><input type=\"checkbox\" name=\"toggle2\" value=\"\" onClick=\"checkAll1($jmlDataDPSB,'cbDPSB','toggle2');\" /></TD>
		<TH>Nama Barang</TH>
		<TH>Merk/Type/Ukuran/Spesifikasi</TH>
		<TH>Jumlah</TH>
		<TH>Harga Satuan (Rp)</TH>
		<TH>Jumlah Harga</TH>
		<TH>No/Tgl SK Gubernur</TH>
		<TH>Tgl Pembelian</TH>
		<TH>Jml Baik</TH>
		<TH>Jml Kurang Baik</TH>
		<TH>Keterangan</TH>
	</TR>
	$ListBarangDPSB
	<tr>
	<td colspan=12 align=center>
	".Halaman($jmlDataDPSB,$Main->PagePerHal,"HalDPSB")."
	</td>
	</tr>
	</table>
<br>

";
if($Act=="Baru" || $Act=="Tambah" || $Act=="TambahEdit"|| $Act=="Add"|| ($Act=="Edit" && !empty($fmID)))
{
	$Main->Isi .= "

<br>
<A NAME='FORMENTRY'></A>

<!--FORM-->



<table width=\"100%\" height=\"100%\" class=\"adminform\">
	<tr valign=\"top\">   
	<td width='200'>Kode Barang</td>
	<td width='25'>:</td>
	<td>".cariInfo("adminForm","pages/01/caribarang1.php","pages/01/caribarang2.php","fmIDBARANG","fmNMBARANG","$ReadOnly","$DisAbled")."
	</td>
	</tr>

	<tr>
	<td>Nomor Register</td>
		<td>:</td>
		<td><b><INPUT type=text name='fmREGISTER' value='$fmREGISTER' size='6' maxlength='4' onKeyup='infofmREGISTER.value=this.value'></b></td>
	</tr>

	<tr>
	<td>Tanggal Perolehan</td>
		<td>:</td>
		<td>".InputKalender("fmTAHUNPEROLEHAN")."</td>
	</tr>
</table>



<table width=\"100%\" height=\"100%\" class=\"adminform\">


	<tr valign=\"top\">
	  <td>Jumlah Barang</td>
	  <td>:</td>
	  <td>
		".txtField('fmJUMLAH',$fmJUMLAH,'100','20','text','')."&nbsp; Satuan".txtField('fmSATUAN',$fmSATUAN,'100','20','text','')."
		</td>
	</tr>

	<tr valign=\"top\">
	  <td>Harga Satuan </td>
	  <td>:</td>
	  <td>Rp. 
		<input type=\"text\" name=\"fmHARGABARANG\" value=\"$fmHARGABARANG\" /></td>
	</tr>

	<tr valign=\"top\">
	  <td width='245'>Jumlah Harga</td>
	  <td width='25'>:</td>
	  <td>Rp. 
		<input type=\"text\" name=\"fmJUMLAHHARGA\" value=\"$fmJUMLAHHARGA\" /></td>
	</tr>

	<tr valign=\"top\">
	  <td>Asal Usul/Cara Perolehan</td>
	  <td>:</td>
	  <td>
		".cmb2D('fmASALUSUL',$fmASALUSUL,$Main->AsalUsul,'')."
	</td>
	</tr>
	<tr valign=\"top\">
	  <td>Kondisi Barang</td>
	  <td>:</td>
	  <td>
		".cmb2D('fmKONDISIBARANG',$fmKONDISIBARANG,$Main->KondisiBarang,'')."
	</td>
	</tr>
	<tr valign=\"top\">
		<td >
		Status barang 
		</TD>
		<TD>:</TD>
		<TD>".cmb2D('fmSTATUSBARANG',$fmSTATUSBARANG,$Main->StatusBarang,'')." 
		</td>
	</tr>
	<tr valign=\"top\">
		<td >
		Tanggal update
		</TD>
		<TD>:</TD>
		<TD> ".InputKalender("fmTGLUPDATE")." 
		</td>
	</tr>

</table>
<table width=\"100%\" height=\"100%\" >
	<tr>
		<td align=right width=90%>
			<input type=hidden name='klikDetil' onClick='adminForm.submit()' value='Detil'>
		</td>
	</tr>

</table>
$DetilKIB

";
}//END IF

$Main->Isi .= "

<input type=hidden name='fmIDREKENING' value='$fmIDREKENING'>
<input type=hidden name='fmIDPENETAPAN' value='$fmIDPENETAPAN'>
<input type=hidden name='Act'>
<input type=hidden name='Baru' value='$Baru'>

<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
	<tr><td class=\"menudottedline\">
			<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=center>
			<tr>
			<td class=\"menudottedline\" height=\"52\" align=right>
			".PanelIcon1("javascript:prosesBaru()","new_f2.png","Baru")."
			</td>
			<td class=\"menudottedline\" height=\"52\">
			".PanelIcon1("javascript:adminForm.Act.value='Simpan';adminForm.submit()","save_f2.png","Simpan")."
			</td>
			<td align=right  class=\"menudottedline\" >
			".PanelIcon1("?Pg=$Pg","cancel_f2.png","Tutup")."
			</td>
			</tr>
			</table>
	</td></tr>
<script language='javascript'>
function prosesBaru()
{
	//fmWIL,fmSKPD,fmUNIT,fmSUBUNIT,fmIDBARANG,fmNMBARANG,fmMEREK,fmJUMLAH,fmSATUAN,fmHARGASATUAN,fmIDREKENING,fmKET,fmTAHUNANGGARAN
	adminForm.Baru.value = '1';
	
	//adminForm.Submit()
}
</script>
</table>
</td></tr></table>
		<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
		<input type=\"hidden\" name=\"option\" value=\"com_users\" />
		<input type=\"hidden\" name=\"task\" value=\"\" />
		<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
		<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />

</form>



";
?>