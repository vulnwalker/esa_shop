<?php
//error_reporting();
$tgl_ba = $_REQUEST['tgl_ba'];
//$url = $_REQUEST['url']
/**$url = "http://localhost/atisisbada_srg/bastp.php";  
			$data = array("tg_ba" => $tgl_ba);   //{ tg_ba : "yyyy-MM-dd }
			$content = json_encode($data);
			
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER,
			        array("Content-type: application/json"));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
			
			$json_response = curl_exec($curl);
			
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			
			if ( $status != 201 ) {
			    die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
			}
			
			
			curl_close($curl);
			
			$response = json_decode($json_response, true);
	**/
	
	
	



//The JSON data.
/**
{ jml_ba : <diisi jumlah BAST per tanggal terbit BA>
	BAST : [
			{
				kd_unit : "<kode unit/skpd, contoh: 1.07.01>",
				nm_unit : "<nama unit/skpd, contoh: DINAS PERHUBUNGAN, KOMUNIKASI, INFORMASI DAN TELEMATIKA>",
				kd_urus: "<kode urusan, contoh: 1.07.>",
				nm_urus: "<nama urusan, contoh: PERHUBUNGAN>", 
				kd_prog : "<kode program, contoh: 18>",
				nm_prog : "<nama program, contoh: Program Pembangunan Sarana dan Prasarana Perhubungan>",
				kd_keg : "<kode kegiatan, contoh: 07>",
				nm_keg : "<nama kegiatan, contoh: Pembangunan prasarana pos dan telekomunikasi>",
				no_ba : "<nomor BA>",
				tg_ba : "<tanggal BA>",				
				no_kontrak : "<nomor kontrak>",
				urai_ba : "<uraian berita acara>",
				tg_valid : "<tanggal sah, format: yyyy-MM-dd>",
				jml_akun : <diisi jumlah akun rinci dari BA>,
				akun : [
						{ 
							kd_akun : "<kode akun belanja, contoh: 5.2.3.51.03. >",
							nm_akun : "<nama akun belanja, contoh: Belanja modal Pengadaan Bangunan Menara Telekomunikasi>",
							nilai : "<diisi nilai nominal utk akun tsb, contoh: 350000000.00>"
						},
						{
							<sda rinci akun, jika akun lebih dari 1 untuk no_ba tertentu>
						},...
					]
			},
			{
				<sda, data BA, jika BA lebih dari satu pada tanggal tertentu>
			},...
		]
}
**/

$jsonData = array( 'tgl_ba' => $tgl_ba);
/**
$jml_ba = 2;
$jsonData = array(
	'jml_ba' => $jml_ba,
	'BAST' => 
		array (		  
		  array(
				'kd_unit' => '1.07.01',
				'nm_unit' => 'DINAS PERHUBUNGAN, KOMUNIKASI, INFORMASI DAN TELEMATIKA',
				'kd_urus' => '1.07.',
				'nm_urus' => 'PERHUBUNGAN', 
				'kd_prog' => '18',
				'nm_prog' => 'Program Pembangunan Sarana dan Prasarana Perhubungan',
				'kd_keg' => '07',
				'nm_keg' => 'Pembangunan prasarana pos dan telekomunikasi',
				'no_ba' => 'ba1',
				'tg_ba' => '2016-02-01',				
				'no_kontrak' => 'k123',
				'urai_ba' => 'uraian berita acara',
				'tg_valid' => '2016-02-02',
				'jml_akun' => 2,
			
				'akun' => 
					array (
						array( 
							'kd_akun' => '5.2.3.51.03',
							'nm_akun' => 'Belanja modal Pengadaan Bangunan Menara Telekomunikasi',
							'nilai' => 350000000.00
						),
						array( 
							'kd_akun' => '5.2.3.51.02',
							'nm_akun' => 'Belanja modal Pengadaan Bangunan Menara Telekomunikasi',
							'nilai' => 350000000.00
						),
					)	
					
			),
			array(
				'kd_unit' => '1.07.02',
				'nm_unit' => 'DINAS PERHUBUNGAN, KOMUNIKASI, INFORMASI DAN TELEMATIKA',
				'kd_urus' => '1.07.',
				'nm_urus' => 'PERHUBUNGAN', 
				'kd_prog' => '18',
				'nm_prog' => 'Program Pembangunan Sarana dan Prasarana Perhubungan',
				'kd_keg' => '07',
				'nm_keg' => 'Pembangunan prasarana pos dan telekomunikasi',
				'no_ba' => 'ba1',
				'tg_ba' => '2016-02-01',				
				'no_kontrak' => 'k123',
				'urai_ba' => 'uraian berita acara',
				'tg_valid' => '2016-02-02',
				'jml_akun' => 3,
			
				'akun' => 
					array (
						array( 
							'kd_akun' => '5.2.3.51.04',
							'nm_akun' => 'Belanja modal Pengadaan Bangunan Menara Telekomunikasi',
							'nilai' => 350000000.00
						),
						array( 
							'kd_akun' => '5.2.3.51.05',
							'nm_akun' => 'Belanja modal Pengadaan Bangunan Menara Telekomunikasi',
							'nilai' => 350000000.00
						),
						array( 
							'kd_akun' => '5.2.3.51.06',
							'nm_akun' => 'Belanja modal Pengadaan Bangunan Menara Telekomunikasi',
							'nilai' => 350000000.00
						),
					)	
					
			)
		)
);
**/



//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);
//var_dump($jsonDataEncoded);
//**

//API Url
//$url = 'http://localhost/atisisbada_srg/api.php?Pg=bast';
//$url = 'http://123.231.253.26/api.php?Pg=bast';
$url = 'http://180.250.129.116/api.php?Pg=bast';
//$url = 'http://localhost/atisisbada_srg/api.php?Pg=bast&tes=3';
//Initiate cURL.
$ch = curl_init($url);

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

//Execute the request
$result = curl_exec($ch);
if(curl_errno($ch)){
    echo 'Request Error:' . curl_error($ch);
}

//curl_close($ch);
var_dump($result);
//**/

//$json_response = curl_exec($ch);
//curl_close($ch);
			
//$decoded = json_decode($jsonDataEncoded);
//echo $decoded;
//var_dump($response);
	
?>