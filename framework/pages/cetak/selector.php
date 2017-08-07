<?php
//selector01.php
$SPg = isset($HTTP_GET_VARS["SPg"])?$HTTP_GET_VARS["SPg"]:"";
$SDest = isset($HTTP_GET_VARS["SDest"])?$HTTP_GET_VARS["SDest"]:"";

switch($SPg){
	case "kib_a_cetak":
		if ($SDest == "XLS") {
		include("kib_a_cetak_xls.php");	
		} else {
		include("listbi_cetak.php");
		}
		break;
	case "listbi_cetak":
		if ($SDest == "XLS") {
		include("listbi_cetak_xls.php");
		} else {
		include("listbi_cetak.php");
		}
		break;
	case "kib_b_cetak":
		if ($SDest == "XLS") {
		include("kib_b_cetak_xls.php");
		} else {
			include("listbi_cetak.php");//include("kib_b_cetak.php");
		}
		break;
	case "kib_c_cetak":
		if ($SDest == "XLS") {
		include("kib_c_cetak_xls.php");	
		} else {
		include("listbi_cetak.php");
		}
		break;

	case "kib_d_cetak":
		if ($SDest == "XLS") {
		include("kib_d_cetak_xls.php");	
		} else {
		include("listbi_cetak.php");
		}
		break;
	case "kib_e_cetak":
		if ($SDest == "XLS") {
		include("kib_e_cetak_xls.php");	
		} else {
		include("listbi_cetak.php");
		}
		break;
	case "kib_f_cetak":
		if ($SDest == "XLS") {
		include("kib_f_cetak_xls.php");	
		} else {
		include("listbi_cetak.php");
		}
		break;
	case "kib_g_cetak":
		if ($SDest == "XLS") {
			include("kib_g_cetak_xls.php");	
		} else {
			include("listbi_cetak.php");
		}
	break;
	case "rkb_cetak":include("rkb_cetak.php");break;
	case "dkb_cetak":include("dkb_cetak.php");break;
	case "rkpb_cetak":include("rkpb_cetak.php");break;
	case "dkpb_cetak":include("dkpb_cetak.php");break;
	case "dpb_cetak":include("dpb_cetak.php");break;
	case "dppb_cetak":include("dppb_cetak.php");break;
	case "penerimaan_cetak":include("penerimaan_cetak.php");break;
	case "pengeluaran_cetak":include("pengeluaran_cetak.php");break;
	case "penetapan_cetak":include("penetapan_cetak.php");break;
	case "pengamanan_cetak":include("pengamanan_cetak.php");break;
	case "pelihara_cetak":include("pelihara_cetak.php");break;
	case "penilaian_cetak":include("penilaian_cetak.php");break;
	case "penghapusan_cetak":include("penghapusan_cetak.php");break;
	case "hapussebagian_cetak":include("hapussebagian_cetak.php");break;
	case "pindahtangan_cetak":include("pindahtangan_cetak.php");break;
	case "pembiayaan_cetak":include("pembiayaan_cetak.php");break;
	case "gantirugi_cetak":include("gantirugi_cetak.php");break;
	case "manfaat_cetak":include("manfaat_cetak.php");break;
	case "rekap_bi_cetak":

		if ($SDest == "XLS") {
		include("rekap_bi_cetak_xls.php");
		} else {
		include("rekap_bi_cetak.php");
		}


		
		break;
	case "rekap_bi_cetak_keu":

		if ($SDest == "XLS") {
		include("rekap_bi_cetak_keu_xls.php");
		} else {
		include("rekap_bi_cetak_keu.php");
		}


		
		break;
	case "KIR":
		include("listbi_cetak.php");
		break;		
	case "listkir_cetak":include("listkir_cetak.php");break;
	case "brg_cetak":include("brg_cetak.php");break;
	case "brg_cetak2":include("brg_cetak2.php");break;
	case "listmutasi_cetak":
/*
		if ($SDest == "XLS") {
		include("listmutasi_cetak_xls.php");
		} else {
		include("listMutasi_cetak.php");
		}
*/
		include("listMutasi_cetak.php");

		break;
	case "rekap_mutasi_cetak":
		if ($SDest == "XLS") {
		include("rekap_mutasi_cetak_xls.php");
		} else {
		include("rekap_mutasi_cetak.php");
		}
		break;
	case "rekap_mutasi_cetak2":
		if ($SDest == "XLS") {
		include("rekap_mutasi_cetak_xls2.php");
		} else {
		include("rekap_mutasi_cetak2.php");
		}
		break;
	case "ref_skpd_cetak":include("ref_skpd_cetak.php");break;
	case "ref_barang_cetak":include("ref_barang_cetak.php");break;
		
}

echo $Main->Isi;
exit;

?>