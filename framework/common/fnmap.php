<?php
//embeded google map
//echo '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q=35.128061,-106.535561&amp;ie=UTF8&amp;t=m&amp;z=14&amp;vpsrc=0&amp;ll=35.126517,-106.535131&amp;output=embed"></iframe><br /><small><a href="http://maps.google.com/maps?q=35.128061,-106.535561&amp;ie=UTF8&amp;t=m&amp;z=14&amp;vpsrc=0&amp;ll=35.126517,-106.535131&amp;source=embed" style="color:#0000FF;text-align:left">View Larger Map</a></small>';
function map_showjs( $idMAPCanvas='mapContent', $MAP_width='400',	$MAP_height='400', $ZoomMAP=16, $embed=0 ){
	
	//$KeyAPI = 'ABQIAAAA0v9TR7DzktUixI7HudY_RRRDHi7C3D_AuatlzcvXqKhcM7CorhQ2C9Sr3ywKCMrx4DzEKQP-ogrbjA';
	$KeyAPI = 'AIzaSyBRBG1GZGKjIASlvJ9K9_KWYxm-TY-oN_I';
	$AddOverview = $embed? 'map.addControl(new GOverviewMapControl());' : '';
	
	$linecolor = '#0000FF';//'#0000FF';//rgb	
	$linewidth = 3;
	$lineopacity= 0.5;
	$strokecolor= '#FF0000';
	$strokewidth = 3;
	$fillColor = '#FFFFFF';//'#6D84B4';
	$fillOpacity = 0.1;//$lineopacity;
	/*
	koorbid='-6.901437, 107.617050; -6.901384, 107.619644; -6.901642, 107.619713; 
			-6.901887, 107.619858; -6.902175, 107.620216; -6.902218, 107.620399;
			-6.902356, 107.620598; -6.902654, 107.620941; -6.903279, 107.620285;
			-6.903229, 107.620163; -6.903546, 107.619652; -6.903578, 107.617699;
			-6.903410, 107.617371; -6.903098, 107.617111; -6.901437, 107.617050';
	*/
	$script = "
			
		<script type='text/javascript' src='http://www.google.com/jsapi?key=$KeyAPI'></script>
		<script>
			//map ---------
			google.load('maps', '2'); 	
			var map;
			var earth;
			var markers = new Array();
			var bidangs = new Array();
			var marker_info = new Array();
			var geocoder;			
			var mapCenterLat;
			var mapCnterLng;

			
			
			
			function initialize(map_content){
				if (!document.getElementById(map_content)) {
					map_content = 'mapContent';
				}
				//alert(map_content);
				//set center
				var skoord = document.getElementById('koordinat_gps').value;
				var akoord = skoord.split(',');
				var koord_lat = akoord[0];
				var koord_lng = akoord[1];
				mapCenterLat = koord_lat;
				mapCenterLng = koord_lng;
				
				var mapcanvas = document.getElementById(map_content);
				mapcanvas.style.width='$MAP_width';
				mapcanvas.style.height='$MAP_height';
				
				// control ----------------------------------------
    			map = new GMap2(document.getElementById(map_content));    			
    			map.addControl(new GMapTypeControl());				
				map.addControl(new GLargeMapControl3D());
				$AddOverview	
    			map.setCenter(new GLatLng(mapCenterLat, mapCenterLng), $ZoomMAP);
	
				map.addMapType(G_SATELLITE_3D_MAP );
				//map.addMapType(G_PHYSICAL_MAP);
				var mapControl = new GMapTypeControl();
				map.addControl(mapControl);		
				//map.setUIToDefault();
				//map.enableRotation();
				
				//svOverlay = new GStreetviewOverlay();	map.addOverlay(svOverlay);											
			}
			
			function showmap2(map_content) { 
				if (map_content =='') map_content = 'mapContent';
			//google.load('maps', '2');		  			
  			if (GBrowserIsCompatible()) {
				
							
				
				var skoord = removeCR(removeSpaces(document.getElementById('koordinat_gps').value));
				var sbidang = removeCR(removeSpaces(document.getElementById('koord_bidang').value));
				
				if(skoord!='' ){					
				
					initialize(map_content);
				
					//create point -----------------------------------				
					if (skoord!=''){
						var pointKoord = skoord.split(',');
						//var koord_lat = akoord[0];	var koord_lng = akoord[1];				
						var point = new GLatLng(pointKoord[0], pointKoord[1]);
						var marker = new GMarker(point);
						marker[0] = marker
    					map.addOverlay(marker[0]);
						//get place address
						geocoder = new GClientGeocoder();
						geocoder.getLocations(new GLatLng(pointKoord[0], pointKoord[1]), function(response){
							//map.clearOverlays();
  							if (!response || response.Status.code != 200) {
    							alert('Status Code:' + response.Status.code);
  							} else {
    							place = response.Placemark[0];    					
								marker_info[0] = '<br><b>Alamat:</b> ' + place.address ; 
  							}	
						});	
						//event klik marker 
						/*GEvent.addListener(marker[0], 'click', function() {
            				marker[0].openInfoWindowHtml(marker_info[0]);
          				});	*/
					}
					
					//create bidang ---------------------------------
					if (sbidang!=''){						
						var abidang = sbidang.split(';');				
											
						//get array poly koord 
						var aPolyKoord = new Array();
						//for(i=0 ;i<abidang.length;i++){
						var i=0;
						while (i<abidang.length){					
				
							if(removeCR(removeSpaces(abidang[i]))== '' ){
								abidang.splice(i,1);
							}else{
								var abidangpoint= abidang[i].split(',');
								if (abidangpoint.length == 2 ){	//alert(abidangpoint[1]+', '+abidangpoint[0]);
									aPolyKoord.push(new GLatLng(abidangpoint[0], abidangpoint[1] )) 		
								}						
								i++;
							}										
						}
				
						//cek polygon/polyline 
						var aw = abidang[0];
						aw = removeCR(removeSpaces(aw));
						var ak = abidang[abidang.length-1];
						ak= removeCR(removeSpaces(ak));
						isPolygon= aw == ak;	
						//alert(isPolygon+' \"'+aw+'\" \"'+ak+'\"');
						//create polygon/line						
				
						/*isPolygon=false;
						if (isPolygon){										
							var bidang = new GPolygon(
								aPolyKoord, 
								'$strokecolor', 
								$strokewidth, $lineopacity, '$fillColor', $fillOpacity);
						}else{
							var bidang = new GPolyline(
								aPolyKoord, 
								'$linecolor', 
								$linewidth, $lineopacity);
							}
						*/
						if (isPolygon){
				 			lcolor='$strokecolor' ;
							lwidth=$strokewidth ;
						} else{
							lcolor = '$linecolor'; 
							lwidth= $linewidth ;
						}
						var bidang = new GPolyline(
							aPolyKoord, 
							lcolor, 
							lwidth, $lineopacity);				
						bidangs[0] = bidang;
						map.addOverlay(bidangs[0]);	
						//event klik poly 
						/*GEvent.addListener(bidangs[0], 'click', function() {					
            				marker[0].openInfoWindowHtml(marker_info[0]);
          				});	
						*/
					}
				
					//event ----------------------------------------
					//change map type
					GEvent.addListener(map, 'maptypechanged', function() { 
						//alert(map.getCurrentMapType().getName(true));
						var nm = map.getCurrentMapType().getName(true);
						switch (nm){
							case 'Map': break;
							case 'Sat': break;
							case 'Hyb': break;
							case 'Earth': {
								setTimeout('setLayer()',1000);							
								break;
							}
						}
					});
				
					//create menu view large
					if (document.getElementById('mapviewlarge')){
						var onclick = 'map_viewlarge()';
						document.getElementById('mapviewlarge').innerHTML =	
							'<small><a target=\"blank\"  onclick=\"'+onclick+'\" style=\"cursor:pointer;color:#6D84B4;text-align:left\">View Larger Map</a></small><br>';
						}
					}
				
					
  				}else{
					alert('Koordinat Lokasi belum diisi!');
				}
			}
			function showmap3(map_content, maptype) { 
				//maptype : G_NORMAL_MAP,G_SATELLITE_MAP,G_HYBRID_MAP
				if (map_content =='') map_content = 'mapContent';
			//google.load('maps', '2');		  			
  			if (GBrowserIsCompatible()) {
				
							
				
				var skoord = removeCR(removeSpaces(document.getElementById('koordinat_gps').value));
				var sbidang = removeCR(removeSpaces(document.getElementById('koord_bidang').value));
				
				if(skoord!='' ){					
					
					initialize(map_content);
					map.setMapType(maptype);
					map.setZoom(".($ZoomMAP+2).");
					
					//create point -----------------------------------				
					if (skoord!=''){
						var pointKoord = skoord.split(',');
						//var koord_lat = akoord[0];	var koord_lng = akoord[1];				
						var point = new GLatLng(pointKoord[0], pointKoord[1]);
						var marker = new GMarker(point);
						marker[0] = marker
    					map.addOverlay(marker[0]);
						//get place address
						geocoder = new GClientGeocoder();
						geocoder.getLocations(new GLatLng(pointKoord[0], pointKoord[1]), function(response){
							//map.clearOverlays();
  							if (!response || response.Status.code != 200) {
    							alert('Status Code:' + response.Status.code);
  							} else {
    							place = response.Placemark[0];    					
								marker_info[0] = '<br><b>Alamat:</b> ' + place.address ; 
  							}	
						});	
						//event klik marker 
						/*GEvent.addListener(marker[0], 'click', function() {
            				marker[0].openInfoWindowHtml(marker_info[0]);
          				});	*/
					}
					
					//create bidang ---------------------------------
					if (sbidang!=''){						
						var abidang = sbidang.split(';');				
											
						//get array poly koord 
						var aPolyKoord = new Array();
						//for(i=0 ;i<abidang.length;i++){
						var i=0;
						while (i<abidang.length){					
				
							if(removeCR(removeSpaces(abidang[i]))== '' ){
								abidang.splice(i,1);
							}else{
								var abidangpoint= abidang[i].split(',');
								if (abidangpoint.length == 2 ){	//alert(abidangpoint[1]+', '+abidangpoint[0]);
									aPolyKoord.push(new GLatLng(abidangpoint[0], abidangpoint[1] )) 		
								}						
								i++;
							}										
						}
				
						//cek polygon/polyline 
						var aw = abidang[0];
						aw = removeCR(removeSpaces(aw));
						var ak = abidang[abidang.length-1];
						ak= removeCR(removeSpaces(ak));
						isPolygon= aw == ak;	
						//alert(isPolygon+' \"'+aw+'\" \"'+ak+'\"');
						//create polygon/line						
				
						/*isPolygon=false;
						if (isPolygon){										
							var bidang = new GPolygon(
								aPolyKoord, 
								'$strokecolor', 
								$strokewidth, $lineopacity, '$fillColor', $fillOpacity);
						}else{
							var bidang = new GPolyline(
								aPolyKoord, 
								'$linecolor', 
								$linewidth, $lineopacity);
							}
						*/
						if (isPolygon){
				 			lcolor='$strokecolor' ;
							lwidth=$strokewidth ;
						} else{
							lcolor = '$linecolor'; 
							lwidth= $linewidth ;
						}
						var bidang = new GPolyline(
							aPolyKoord, 
							lcolor, 
							lwidth, $lineopacity);				
						bidangs[0] = bidang;
						map.addOverlay(bidangs[0]);	
						//event klik poly 
						/*GEvent.addListener(bidangs[0], 'click', function() {					
            				marker[0].openInfoWindowHtml(marker_info[0]);
          				});	
						*/
					}
				
					//event ----------------------------------------
					//change map type
					GEvent.addListener(map, 'maptypechanged', function() { 
						//alert(map.getCurrentMapType().getName(true));
						var nm = map.getCurrentMapType().getName(true);
						switch (nm){
							case 'Map': break;
							case 'Sat': break;
							case 'Hyb': break;
							case 'Earth': {
								setTimeout('setLayer()',1000);							
								break;
							}
						}
					});
				
					//create menu view large
					if (document.getElementById('mapviewlarge')){
						var onclick = 'map_viewlarge()';
						document.getElementById('mapviewlarge').innerHTML =	
							'<small><a target=\"blank\"  onclick=\"'+onclick+'\" style=\"cursor:pointer;color:#6D84B4;text-align:left\">View Larger Map</a></small><br>';
						}
					}
				
					
  				}else{
					alert('Koordinat Lokasi belum diisi!');
				}
			}
			function setAddress(response) {
  				//map.clearOverlays();
  				if (!response || response.Status.code != 200) {
    				alert('Status Code:' + response.Status.code);
  				} else {
    				place = response.Placemark[0];
    				//point = new GLatLng(place.Point.coordinates[1],place.Point.coordinates[0]);
    				//marker = new GMarker(point);
    				//map.addOverlay(marker);
					marker_info = 
						//'<b>Nama :</b> '+place.name+'<br>'+
						'<b>Alamat:</b> ' + place.address ; 
    				/*marker.openInfoWindowHtml(
        				'<b>orig latlng:</b>'+ response.name+'<br/>'+ 
        				'<b>latlng:</b>' + place.Point.coordinates[1] + ',' + place.Point.coordinates[0] + '<br>' +
        				'<b>Status Code:</b>' + response.Status.code + '<br>' +
        				'<b>Status Request:</b>' + response.Status.request + '<br>' +
        				'<b>Address:</b>' + place.address + '<br>' +
        				'<b>Accuracy:</b>' + place.AddressDetails.Accuracy + '<br>' +
        				'<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode
					);*/
  				}
			}
			function setLayer(){			
				map.getEarthInstance(function(ge) {					 
    				ge.getLayerRoot().enableLayerById(ge.LAYER_ROADS , true);
					ge.getLayerRoot().enableLayerById(ge.LAYER_TERRAIN , true);
					ge.getLayerRoot().enableLayerById(ge.LAYER_TREES , true);
					ge.getLayerRoot().enableLayerById(ge.LAYER_BORDERS , true);					
					//alert('tes');					
  				});			
			}
			function map_viewlarge(){
				adminForm.target = '_blank';
				adminForm.action='pages.php?Pg=map&SPg=01'
				
				adminForm.submit();
				adminForm.target = '';
			}
		</script>
	
		";
		return $script;
}


function balikKoord($Koord){
	$arr = explode(';',$Koord);
	$hsl='';
	foreach($arr  as &$value ){
		$value = trim($value);
		if ($value!=''){
			$ps = explode(',', $value);
			$value = trim($ps[1]).','.trim($ps[0]).',0';			
		}
		
	}
	return join("\n",$arr);
}


//xtoolpro
function genKML($aqry='', $fieldName='', $fieldDesk='', $fieldKoord='', $isPoint=TRUE ){
	$no=0;
	$qry = mysql_query($aqry);
	
	$KMLPlacemark='';
	while($isi=mysql_fetch_array($qry)){
	
		$no++;
		//ambil data ---------------------------------------------
		$Name = $isi[$fieldName];//'nama';//htmlentities($row['name']) ;
		$Desk = $isi[$fieldDesk];//<![CDATA[<b>1</b> 3 345 6]]>';//htmlentities($row['address']) ;		
		$Koords= balikKoord( $isi['koord']);
		if($isPoint){
			//$Koords= $isi['koord'];//'107.618332,-6.90207,0';
			$StyleUrl = '#pointStyle1';
			$KMLPlacemark .= 	
				"<Placemark >\n".
					"<name>$Name</name>\n ".
					"<description>$Desk</description>\n".
					"<styleUrl>$StyleUrl</styleUrl>\n".
    				"<Point>\n".
					"<coordinates>\n$Koords\n</coordinates>\n".
					"</Point>\n".
  				"</Placemark>\n";
		}else{
			/*$Koords = ' 107.617050,-6.901437,0.000000 107.619644,-6.901384,0.000000 107.619713,-6.901642,0.000000 107.619858,-6.901887,0.000000 
					107.620216,-6.902175,0.000000 107.620399,-6.902218,0.000000 107.620598,-6.902356,0.000000 107.620941,-6.902654,0.000000
					107.620285,-6.903279,0.000000 107.620163,-6.903229,0.000000 107.619652,-6.903546,0.000000 107.617699,-6.903578,0.000000
    				107.617371,-6.903410,0.000000 107.617111,-6.903098,0.000000 107.617050,-6.901437,0.000000';*/
			$StyleUrl = '#normalState';
			$KMLPlacemark.=
				"<Placemark >\n".
					"<name>$Name</name>\n".
					"<description>$Desk</description>\n".
					"<styleUrl>$StyleUrl</styleUrl>\n".
					"<Polygon>\n".
      					"<outerBoundaryIs>\n".
        					"<LinearRing>\n".
          						"<tessellate>1</tessellate>\n".
          						"<coordinates>\n$Koords\n</coordinates>\n".
        					"</LinearRing>\n".
      					"</outerBoundaryIs>\n".
    				"</Polygon>\n".
				"</Placemark>\n";
		}
		
	}

	//style  -----------------------------------------------
	$KMLStyle= 
		"<Style id='normalState'>\n".
		"<LineStyle> <color>73FF0000</color>\n".
		"<width>2</width>\n".
		"</LineStyle>\n".
    	"<PolyStyle>\n".
		"<color>73FF0000</color>\n".
		"<fill>0</fill>\n".
		"<outline>1</outline>\n".
		"</PolyStyle>\n".
		"</Style>\n".
		"<Style id='pointStyle1'>\n".
		"<IconStyle>\n".
		"<scale>0.5</scale>\n".
		"<Icon>\n".
          "<href>http://maps.google.com/mapfiles/kml/pal4/icon28.png</href>\n".
        "</Icon>\n".
      	"</IconStyle>\n".
		"</Style>\n";
	/*
	<Style id="highlightState"> 		
		<LineStyle>
      		<color>73FF0000</color>
      		<width>2</width>
    	</LineStyle>
    	<PolyStyle>
      		<color>73FF0000</color>
      		<fill>1</fill>
      		<outline>1</outline>
    	</PolyStyle>
	</Style>	
  	<StyleMap id="styleMap">
    	<Pair>
      		<key>normal</key>
      		<styleUrl>#normalState</styleUrl>
    	</Pair>
    	<Pair>
      		<key>highlight</key>
      		<styleUrl>#highlightState</styleUrl>
    	</Pair>
	</StyleMap>';
	*/
	
	return $KMLStyle.$KMLPlacemark;
	
}


class MapObj{
	
	function getinfo(){
		$cek = ''; $content=''; $err='';
		$id= $_REQUEST['id'];
		$aqry = "select * from buku_induk where id='$id'";
		$qry = mysql_query($aqry);
		
		$rows= array();
		while ($isi = mysql_fetch_assoc($qry)){
			$rows[] = $isi;
		}
		$content->rows = $rows;
		
		return array('cek'=>$cek, 'err'=>$err, 'content'=>$content );
		//break;
	}
	function getdata(){
		$Main;
		$cek = ''; $rows = array(); $err='';
		$fmSKPD = $_REQUEST['MapSkpdfmSKPD'];
		$fmUNIT = $_REQUEST['MapSkpdfmUNIT'];
		$fmSUBUNIT = $_REQUEST['MapSkpdfmSUBUNIT'];
				
		$Limit = '';//" limit 0,10 ";
		//table -----------------------------------------------------------------------------------------
		$kib = $_REQUEST['kib'];
		switch ($kib){
			case '01':	$tbl = 'view_kib_a';	break;	
			case '03':	$tbl = 'view_kib_c';	break;	
			case '04':	$tbl = 'view_kib_d';	break;	
			case '06':	$tbl = 'view_kib_f';	break;
			default : 'view_buku_induk2';break;	
		}
		
		//kondisi ---------------------------------------------------------------------------------------		
		$arrkond = array();
		$arrkond[] = " koordinat_gps<>'' ";
		$arrkond[] = " koordinat_gps<>'-' ";
		$arrkond[] = " koordinat_gps is not null ";
		$arrkond[] = " koord_bidang<>'' ";
		$arrkond[] = " koord_bidang<>'-' ";
		$arrkond[] = " koord_bidang is not null ";
		$arrkond[] = " status_barang =1 ";
		
		//$arrkond[] = " koordinat_gps<>'' ";
		if(!empty($fmSKPD) && $fmSKPD!='00') $arrkond[] = " c='$fmSKPD' ";
		if(!empty($fmUNIT) && $fmUNIT!='00') $arrkond[] = " d='$fmUNIT' ";
		if(!empty($fmSUBUNIT) && $fmSUBUNIT!='00') $arrkond[] = " e='$fmSUBUNIT' ";
		
		$thn_perolehan1 = $_REQUEST['fmTahunPerolehan'];
		$thn_perolehan2 = $_REQUEST['fmTahunPerolehan2'];
		if(!empty($thn_perolehan1)) $arrkond[] = " thn_perolehan >= '$thn_perolehan1' ";
		if(!empty($thn_perolehan2)) $arrkond[] = " thn_perolehan <= '$thn_perolehan2' ";
		$kondisi_barang = $_REQUEST['kondisi_barang'];
		if(!empty($kondisi_barang)) $arrkond[] = " kondisi = '$kondisi_barang' ";
		
		$kode_barang = $_REQUEST['kode_barang'];
		if(!empty($kode_barang)) $arrkond[] = " concat(f,'.',g,'.',h,'.',i,'.',j) like '$kode_barang%'";
		$nama_barang = $_REQUEST['nama_barang'];
		if(!empty($nama_barang)) $arrkond[] = " nm_barang like '%$nama_barang%'";
		$jml_harga1 = $_REQUEST['jml_harga1'];
		$jml_harga2 = $_REQUEST['jml_harga2'];
		if(!empty($jml_harga1)) $arrkond[] = " jml_harga >= '$jml_harga1' ";
		if(!empty($jml_harga2)) $arrkond[] = " jml_harga <= '$jml_harga2' ";
		$alamat = $_REQUEST['alamat'];
		if(!empty($alamat)) $arrkond[] = " alamat like '%$alamat%'  ";
/*
		$selKabKota = $_REQUEST['selKabKota'];
		if(!empty($selKabKota)) $arrkond[] = " alamat_b='$selKabKota'  ";
*/
		$selKabKota = $_REQUEST['WilayahfmxKotaKab'];
		if(!empty($selKabKota)) $arrkond[] = " alamat_b='$selKabKota'  ";

		$selKecamatan = $_REQUEST['WilayahfmxKecamatan'];
		if(!empty($selKecamatan)) $arrkond[] = " alamat_c='$selKecamatan'  ";


		$noSert = $_REQUEST['noSert'];
		if(!empty($noSert)) $arrkond[] = " sertifikat_no like '%$noSert%'  ";
		$selHakPakai = $_REQUEST['selHakPakai'];
		if(!empty($selHakPakai)) $arrkond[] = " status_hak='$selHakPakai' ";
		
		$konsTingkat = $_REQUEST['konsTingkat'];
		if(!empty($konsTingkat)) $arrkond[] = " konstruksi_tingkat = '$konsTingkat'  ";
		$konsBeton = $_REQUEST['konsBeton'];
		if(!empty($konsBeton)) $arrkond[] = " konstruksi_beton = '$konsBeton'  ";
		$status_tanah = $_REQUEST['status_tanah'];
		if(!empty($status_tanah)) $arrkond[] = " status_tanah = '$status_tanah'  ";
		
						
		$merk = $_REQUEST['merk'];
		if(!empty($merk)) $arrkond[] = " merk like '%$merk%' ";				
		$bahan = $_REQUEST['bahan'];
		if(!empty($bahan)) $arrkond[] = " bahan like '%$bahan%' ";
		$nopabrik = $_REQUEST['nopabrik'];
		if(!empty($nopabrik)) $arrkond[] = " no_pabrik like '%$nopabrik%' ";		
		$norangka = $_REQUEST['norangka'];
		if(!empty($norangka)) $arrkond[] = " no_rangka like '%$norangka%' ";		
		$nomesin = $_REQUEST['nomesin'];
		if(!empty($nomesin)) $arrkond[] = " no_mesin like '%$nomesin%' ";		
		$nopolisi = $_REQUEST['nopolisi'];
		if(!empty($nopolisi)) $arrkond[] = " no_polisi like '%$nopolisi%' ";		
		$nobpkb = $_REQUEST['nobpkb'];
		if(!empty($nobpkb)) $arrkond[] = " no_bpkb like '%$nobpkb%' ";
		
		$dokumen_no = $_REQUEST['dokumen_no'];
		if(!empty($dokumen_no)) $arrkond[] = " dokumen_no like '%$dokumen_no%' ";
		$kode_tanah = $_REQUEST['kode_tanah'];
		if(!empty($kode_tanah)) $arrkond[] = " kode_tanah like '%$kode_tanah%' ";		
		$konstruksi = $_REQUEST['konstruksi'];
		if(!empty($konstruksi)) $arrkond[] = " konstruksi like '%$konstruksi%' ";
		
		$judul = $_REQUEST['judul'];
		if(!empty($judul)) $arrkond[] = " buku_judul like '%$judul%' ";
		$spesifikasi = $_REQUEST['spesifikasi'];
		if(!empty($spesifikasi)) $arrkond[] = " buku_spesifikasi like '%$spesifikasi%' ";
		$seni_asal_daerah = $_REQUEST['seni_asal_daerah'];
		if(!empty($seni_asal_daerah)) $arrkond[] = " seni_asal_daerah like '%$seni_asal_daerah%' ";
		$seni_pencipta = $_REQUEST['seni_pencipta'];
		if(!empty($seni_pencipta)) $arrkond[] = " seni_pencipta like '%$seni_pencipta%' ";
		$seni_bahan = $_REQUEST['seni_bahan'];
		if(!empty($seni_bahan)) $arrkond[] = " seni_bahan like '%$seni_bahan%' ";
		$hewan_jenis = $_REQUEST['hewan_jenis'];
		if(!empty($hewan_jenis)) $arrkond[] = " hewan_jenis like '%$hewan_jenis%' ";
		$hewan_ukuran = $_REQUEST['hewan_ukuran'];
		if(!empty($hewan_ukuran)) $arrkond[] = " hewan_ukuran like '%$hewan_ukuran%' ";		
		$bangunan = $_REQUEST['bangunan'];
		if(!empty($bangunan)) $arrkond[] = " bangunan = '$bangunan' ";
		
		$uraian= $_REQUEST['uraian'];
		if(!empty($uraian)) $arrkond[] = " uraian like '%$uraian%' ";
		$luas1 = $_REQUEST['luas1'];
		$luas2 = $_REQUEST['luas2'];
		if(!empty($luas1)) $arrkond[] = " luas >= '$luas1' ";
		if(!empty($luas2)) $arrkond[] = " luas <= '$luas2' ";
		$luas_lantai1 = $_REQUEST['luas_lantai1'];
		$luas_lantai2 = $_REQUEST['luas_lantai2'];
		if(!empty($luas_lantai1)) $arrkond[] = " luas_lantai >= '$luas_lantai1' ";
		if(!empty($luas_lantai2)) $arrkond[] = " luas_lantai <= '$luas_lantai2' ";
		
		$tahun_sensus = $_REQUEST['tahun_sensus'];
		if(!empty($tahun_sensus)) {
			if ($tahun_sensus=='belum_sensus') {
				$arrkond[] = " (tahun_sensus ='' or tahun_sensus is null)";
			}else{
				$arrkond[] = " tahun_sensus ='$tahun_sensus'";
			}			
		}
		
		$ket= $_REQUEST['ket'];
		if(!empty($ket)) $arrkond[] = " ket like '%$ket%' ";
		
				
		$Kondisi = join(' and ',$arrkond);
		if($Kondisi != '') $Kondisi = ' where '.$Kondisi;
		
		//daftar ----------------------------------------------------------------------------------
		
		$aqry = "select * from $tbl $Kondisi $Limit"; $cek.=$aqry;
		$qry=mysql_query($aqry);
//		$cek=$aqry;
		while($isi=mysql_fetch_array($qry)){
			$color=null;
			$jnskonstruksi = 0;	
			switch ( $isi['f']){
				case '04' : {//kib d
					
					if (strpos(strtolower($isi['konstruksi']), 'tanah') !== false) {
						$jnskonstruksi = 1;
						$color = '#C0C0C0';
					}else if (strpos(strtolower($isi['konstruksi']), 'aspal') !== false) {
						$jnskonstruksi = 2;
						$color = '#FF8040';
					}else if (strpos(strtolower($isi['konstruksi']), 'beton') !== false) {
						$jnskonstruksi = 3;						
						$color = '#806000';
					}else if (strpos(strtolower($isi['konstruksi']), 'baja') !== false) {
						$jnskonstruksi = 4;
						$color = '#00FF00';
					}
						
					
					
					break;
				}
			}
			
			$info = //$Main->SUB_UNIT ?
				$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e'].$isi['e1']
					.$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j']
					.$isi['tahun']
					.$isi['noreg'];/*:
				$isi['a1'].$isi['a'].$isi['b'].$isi['c'].$isi['d'].$isi['e']
					.$isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j']
					.$isi['tahun']
					.$isi['noreg'];*/
			$rows[] = array(
				'id'=>$isi['id'], 
				'koord'=>$isi['koordinat_gps'], 
				'area'=>$isi['koord_bidang'] ,
				//'info'=>$isi['idbi']
				'konstruksi' => $isi['konstruksi'],
				'jnskonstruksi' => $jnskonstruksi,
				'color'=> $color,
				'info'=>//$isi['idall']
					//a1,new.a,new.b,new.c,new.d,new.e,new.f,new.g,new.h,new.i,new.j,new.thn_perolehan,new.noreg);
					$info
			);
		}
		
		$content->rows = $rows;
		$get = mysql_fetch_array( mysql_query("select count(*) as cnt from $tbl $Kondisi") );
		$content->jmldata = $get['cnt'];
		
		return array('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	function getmenukib(){
		global $Main;
		$cek = ''; $content=''; $err='';
		$kib = $_REQUEST['kib'];
		
		$valamat =
			"<tr><td>Alamat</td><td>".						
				"<input id='alamat' name='alamat' type='text' value='$alamat' style='width:214'>".
			"</td></tr>" ;
/*			
		$vkota = 
			"<tr><td>Kota</td><td>".									
				selKabKota(selKabKota, $selKabKota, "style='width:214'").
			"</td></tr>" ;
*/			

		$vkota = 
			"<tr><td>Kota/Kab.</td><td>".									
				selKabKota_gps_div($selKabKota,'','',0,'Wilayah','style=" style="width:214"').
			"</td></tr>" ;
		$vkecamatan = 
			"<tr><td>Kecamatan</td><td>".									
				selKecamatan_gps_div($selKecamatan,'','',$selKabKota,0,'Wilayah','style=" style="width:214"').
			"</td></tr>" ;
			
		$vnosertifikat = 
			"<tr><td>No. Sertifikat</td><td>".									
				'<input name="noSert" type="text" value="'.$noSert.'" style="width:214">'.
			"</td></tr>" ;		
		$vstatus_sertifikat = 
			"<tr><td>Status Sertifikat</td><td>".
				cmb2D_v3('selSertifikat', $selSertifikat, $Main->bersertifikat, " style='width:214'",'-- Status Sertifikat --').
			"</td></tr>" ;
		$vket = 
			"<tr><td>Keterangan</td><td>".									
				'<input name="ket" id="ket" type="text" value="'.$ket.'" style="width:214">'.
			"</td></tr>" ;		
		$vthn_perolehan =
			"<tr><td>Tahun Perolehan</td><td>".	
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".
				genComboBoxQry('fmTahunPerolehan',$fmTahunPerolehan,
					"select thn_perolehan from buku_induk group by thn_perolehan order by thn_perolehan desc",
					'thn_perolehan', 'thn_perolehan','Dari Tahun',"style='width:95'"). 
			"</div>" .
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> s/d </div>".
			"<div style='float:left;height:22;'>".
				genComboBoxQry('fmTahunPerolehan2',$fmTahunPerolehan2,
					"select thn_perolehan from buku_induk group by thn_perolehan order by thn_perolehan desc",
					'thn_perolehan', 'thn_perolehan','Tahun',"style='width:95'"). 
			"</div>".
			"</td></tr>" ;
		$vkondisi_barang = 
			"<tr><td>Kondisi Barang</td><td>".	
				cmb2D_v2('kondisi_barang',$kondisi_barang, $Main->KondisiBarang,'style="width:214"','-- Kondisi Barang --','').
			"</td></tr>" ;
		$vkode_barang =
			"<tr><td>Kode Barang</td><td>".	
				"<input id='kode_barang' name='kode_barang' value='' title='Cari Kode Barang (ex: 01.02.01.01.01)' style='width:214'>".
			"</td></tr>" ;
		$vnm_barang = 
			"<tr><td>Nama Barang</td><td>".	
				"<input id='nama_barang' name='nama_barang' value='' title='Cari Nama Barang (ex: Meja Kayu)' style='width:214'>".
			"</td></tr>" ;
		$vhrg_perolehan=
			"<tr valign='top'><td>Harga Perolehan Rp</td><td>".				
			//"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input type='text' name='jml_harga1' id='jml_harga1' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'
				title = 'Cari Barang dengan harga perolehan lebih dari atau sama dengan (ex: 1000000)'
				style='width:95'
				>".
			"</div>" .	
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> s/d </div>".			
			"<div style='float:left;height:22;'>".						
				"<input type='text' name='jml_harga2' id='jml_harga2' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'
				title = 'Cari Barang dengan harga perolehan kurang dari atau sama dengan (ex: 1000000)'
				style='width:95'
				>".
			"</div>".
			"</td></tr>" ;
		$vluas =
			"<tr valign='top'><td>Luas Tanah (m2)</td><td>". //"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> Luas Tanah (m2) </div>".
			"<div style='float:left;padding: 0 4 0 0;height:22;'>".						
				"<input type='text' name='luas1' id='luas1' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'				
				title = 'Cari Barang dengan luas tanah lebih dari atau sama dengan (ex: 1000000)'
				style='width:95'
				>".
			"</div>" .	
			"<div  style='float:left;padding: 0 4 0 0;height:22;padding: 4 4 0 0'> s/d </div>".			
			"<div style='float:left;height:22;'>".						
				"<input type='text' name='luas2' id='luas2' value='' onkeydown='oldValue=this.value;' onkeypress='return isNumberKey(event); ' onkeyup='TampilUang('TampilfmJMLHARGA2',this.value);'				
				title = 'Cari Barang dengan luas tanah kurang dari atau sama dengan (ex: 1000000)'
				style='width:95'
				>".
			"</div>".
			"</td></tr>" ;
		$vkonst_bertingkat = 
			"<tr valign='top'><td>Bertingkat/Tidak</td><td>".							
				cmb2D_v2('konsTingkat', $konsTingkat, $Main->Tingkat, 'style="width:214"','-- Bertingkat/Tidak --').
			"</td></tr>" ;
		$vkonst_beton=
			"<tr valign='top'><td>Beton/Tidak</td><td>".							
				cmb2D_v2('konsBeton', $konsBeton, $Main->Beton, 'style="width:214"','-- Beton/Tidak --').
			"</td></tr>" ;
		$vstatus_tanah =
			"<tr valign='top'><td>Status Tanah</td><td>".							
				cmb2D_v2('status_tanah', $status_tanah, $Main->StatusTanah, 'style="width:214"','-- Status Tanah --').
			"</td></tr>" ;
		$vnodokumen =
			"<tr valign='top'><td>No. Dokumen</td><td>".												
				'<input name="dokumen_no" type="text" value="'.$dokumen_no.'" style="width:214"> '.
			"</td></tr>" ;
		$vkode_tanah =
			"<tr valign='top'><td>No. Kode Tanah </td><td>".							
				'<input name="kode_tanah" type="text" value="'.$kode_tanah.'" style="width: 214px;"> '.
			"</td></tr>" ;
		$vkonstruksi =
			"<tr valign='top'><td>Konstruksi </td><td>".							
				'<input name="konstruksi" type="text" value="'.$konstruksi.'" style="width: 214px;"> '.
			"</td></tr>" ;
		$vbangunan =			
			"<tr valign='top'><td>Bangunan </td><td>".							
				cmb2D_v2('bangunan', $bangunan, $Main->Bangunan, 'style="width:214"','-- Tipe Bangunan --').	
			"</td></tr>" ;
		$vstatus_hakpakai =
			"<tr valign='top'><td>Hak Pakai </td><td>".							
				cmb2D_v2('selHakPakai', $selHakPakai, $Main->StatusHakPakai, 'style="width:214"','-- Status Tanah --').
			"</td></tr>" ;
		$vheader =
			"<tr valign='top'><td width='100'></td><td>".				
			"</td></tr>" ;
			
		$content = "<table>";
		switch ($kib){
			case '01':{
				$content .= 
					$vheader.
					$vthn_perolehan.					
					$vkondisi_barang.
					$vkode_barang.
					$vnm_barang.
					$vhrg_perolehan.
					$vluas.
					$vstatus_hakpakai.
					$valamat.$vkota.$vkecamatan.
					$vstatus_sertifikat.
					$vnosertifikat.
					$vket;
				break;
			}
			case '03':{
				$content .= //'kibc';
					$vheader.					
					$vthn_perolehan.					
					$vkondisi_barang.
					$vkode_barang.
					$vnm_barang.
					$vhrg_perolehan.					
					$valamat.$vkota.$vkecamatan.
					$vstatus_sertifikat.
					$vnosertifikat.
					$vnodokumen.			
					$vkonst_bertingkat.	
					$vkonst_beton. 						
					$vstatus_tanah.
					$vkode_tanah.
					$vket;				
				break;
			}
			case '04':{
				$content .= //'kibd';
					$vheader.					
					$vthn_perolehan.					
					$vkondisi_barang.
					$vkode_barang.
					$vnm_barang.
					$vhrg_perolehan.
					$vkonstruksi.
					$valamat.$vkota.$vkecamatan.
					$vnodokumen.			
					$vstatus_tanah.
					$vkode_tanah.
					$vket;					
				break;
			}
			case '06':{
				$content .= //'kibc';
					$vheader.			
					$vthn_perolehan.					
					$vkondisi_barang.
					$vkode_barang.
					$vnm_barang.
					$vhrg_perolehan.	
					$vbangunan.
					$vkonst_bertingkat.	
					$vkonst_beton. 											
					$valamat.$vkota.$vkecamatan.
					$vnodokumen.			
					$vstatus_tanah.	//$vstatus_sertifikat.//$vnosertifikat.					
					$vket;	
				break;
			}
		}
		$content .= "</tablle>";
		return array('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
}
$Map = new MapObj();

?>