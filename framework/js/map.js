var MapSkpd = new SkpdCls({
	prefix : 'MapSkpd', formName:'adminForm',
	genUrl: function(){
		return 'pages.php?Pg=skpd&nm='+this.prefix+'&width=318';
	}
});


var MapObj = function(params_){	
	this.params = params_;
	this.map_content = 'map_content';
	this.MAP_width = '100%';
	this.MAP_height = '100%';
//	this.MAP_center_point = '-6.895160 107.633580'; -6.115333 106.152317
	this.MAP_center_point = '-6.115333 106.152317';

	// -0.789275, 113.921327
	// this.MAP_center_point = '-0.789275, 113.921327';

	this.MAP_zoom = 10;
	this.map=null;
	this.formName='adminForm';
		
	this.poly_strokecolor = '#FF0000';
	this.poly_strokewidth  = 3; 
	this.poly_linecolor = '#0000FF'; 
	this.poly_linewidth = 3;
	this.poly_lineopacity = 0.5;
	this.fillColor = '#FFFFFF';//'#6D84B4';
	this.fillOpacity = 0.1;
	
	//this.geocoder=null;
	this.markers = new Array(); //aray of point
	this.bidangs = new Array(); //array of bidang/area
	this.marker_info = new Array();
	this.area_info = new Array();
	//icon
	this.icon = null;		  
	
	this.MapIni = function(){
		
		//var map=this.map;
		
		var earth;
		
		
					
		var mapCenterLat;
		var mapCnterLng;
		
		
		//alert('map content='+this.map_content);
		//alert ( document.getElementById(this.map_content));
		//set center
		var szoom = this.MAP_zoom;
		var skoord = this.MAP_center_point;//document.getElementById('koordinat_gps').value;
		var akoord = skoord.split(' ');
		var koord_lat = akoord[0];
		var koord_lng = akoord[1];
		mapCenterLat = koord_lat;
		mapCenterLng = koord_lng;
		
		var mapcanvas = document.getElementById(this.map_content);
		mapcanvas.style.width=this.MAP_width;
		mapcanvas.style.height=this.MAP_height;
		
		//icon
		this.icon = new GIcon();
		this.icon.image = "images/tumbs/marker_icon.png"  
		this.icon.size = new GSize(11,11)  
		this.icon.iconAnchor = new GPoint(5,5)  
		this.icon.infoWindowAnchor = new GPoint(5,5)  
		 
		  
		
		// control ----------------------------------------
		
		//var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));
		var bottomRight = new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,20));

		
		this.map = new GMap2(document.getElementById(this.map_content));    					
		this.map.addControl(new GMapTypeControl());				
		//this.map.addControl(new GLargeMapControl3D());
		//$AddOverview	
		this.map.setCenter(new GLatLng(mapCenterLat, mapCenterLng), szoom);

		this.map.addMapType(G_SATELLITE_3D_MAP );
		//map.addMapType(G_PHYSICAL_MAP);
		var mapControl = new GMapTypeControl();
		this.map.addControl(mapControl);	
		this.map.removeControl();	
		this.map.addControl(new GLargeMapControl3D(), bottomRight); 
		//map.setUIToDefault();
		//map.enableRotation();
		
		//svOverlay = new GStreetviewOverlay();	map.addOverlay(svOverlay);	


	}
	
	this.delPoint=function(skoord){
		skoord = removeCR(removeSpaces(skoord));
		if (skoord!=''){
			var pointKoord = skoord.split(','); //var pointKoord = skoord.split(' ');	//var koord_lat = akoord[0];	var koord_lng = akoord[1];				
			
			var point = new GLatLng(pointKoord[0], pointKoord[1]);
			var marker = new GMarker(point);
			
			if(marker){			
    			this.map.removeOverlay(marker);
        	}
			//this.markers[jmldata] = marker;
			//this.map.addOverlay(this.markers[jmldata]);
		}
	}
	
	this.clearMap = function(){
		//alert(this.markers.length);	
		for (i=0;i<this.markers.length;i++){
			this.map.removeOverlay(this.markers[i]);
		}
		for (i=0;i<this.bidangs.length;i++){
			this.map.removeOverlay(this.bidangs[i]);
		}
		this.markers = Array();
		this.bidangs = Array();
		
	}
	this.addPoint = function(skoord, info){
		var me =this;
		skoord = removeCR(removeSpaces(skoord));
		if (skoord!=''){
			var pointKoord = skoord.split(','); //var pointKoord = skoord.split(' ');	//var koord_lat = akoord[0];	var koord_lng = akoord[1];				
			
			var jmldata = this.markers.length;
			
			//this.opt  
			var opt = {}  
			opt.icon = this.icon  
			opt.draggable = false  
			opt.clickable = true  
			opt.dragCrossMove = true
			
			var point = new GLatLng(pointKoord[0], pointKoord[1]);
			var marker = new GMarker(point, opt);
			
			this.markers[jmldata] = marker;
			this.map.addOverlay(this.markers[jmldata]);
			
			/*
			//get place address
			var geocoder = new GClientGeocoder();
			geocoder.getLocations(new GLatLng(pointKoord[0], pointKoord[1]), function(response){
				//map.clearOverlays();
				if (!response || response.Status.code != 200) {
					alert('Status Code:' + response.Status.code);
				} else {
					place = response.Placemark[0];    					
					me.marker_info[0] = '<br><b>Alamat:</b> ' + place.address ; 
				}	
			});	
			*/
			
			this.marker_info[jmldata] = info;
			
			//event klik marker 
			GEvent.addListener(this.markers[jmldata], 'click', function() {
				//me.markers[jmldata].openInfoWindowHtml(me.marker_info[jmldata]);
				var cover = 'cover_getinfo';
				addCoverPage2(cover,1,true,false);	
		
				
				$.ajax({
					url: "viewer/view_cari_det.php?fid=dlgDetail&tipe=jso&id="+me.marker_info[jmldata],
				  	success: function(data) {		
						delElem(cover);						
						var resp = eval('(' + data + ')');	//alert (resp.content.rows[0].noreg);	
						//var dat = resp.content.rows[0];									
						me.markers[jmldata].openInfoWindowHtml(
							"<div id='info' style=' width:640; height:520;'>"+resp.content+"</div>"
						);
						//$(document).ready(function(){
						Gbr.idbi = info;
						Gbr.show();
							//alert('xxz');
							
						//});
				  	}
				});
				
				

				
			});	
		}
	}
	
	this.addArea = function(sbidang, info, color){
		/* '-6.895072,107.633535;-6.895113,107.633676;'+
			'-6.895234,107.633643;-6.895194,107.633496;'+
			'-6.895072,107.633535' 
		*/
		var me=this;
		
		var jmldata = this.bidangs.length;		
		var sbidang = removeCR(removeSpaces(sbidang	)) ;
		var abidang = sbidang.split(';');				
										
		//get array poly koord 
		var aPolyKoord = new Array();					
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
 			lcolor=this.poly_strokecolor ;
			lwidth=this.poly_strokewidth ;
		} else{
			if (color== null) {
				lcolor = this.poly_linecolor;
			} else {
				lcolor =  color;
			}
			//if( jnskonstruks<> 0  ) 
			//lcolor = color_jnskonstruksi;
			lwidth= this.poly_linewidth ;
		}
		var bidang = new GPolyline(
			aPolyKoord, 
			lcolor, 
			lwidth, 
			this.poly_lineopacity
		);				
		this.bidangs[jmldata] = bidang;
		this.map.addOverlay(this.bidangs[jmldata]);	
		
		this.area_info[jmldata] = info;
		
		//event klik poly 
		GEvent.addListener(this.bidangs[jmldata], 'click', 
		function() {		
			//alert('tes');			
			var latlin = (me.bidangs[jmldata].getBounds()).getCenter();
			//me.bidangs[jmldata].
			//me.map.openInfoWindowHtml(latlin, me.area_info[jmldata]);
			
			var cover = 'cover_getinfo';
			addCoverPage2(cover,1,true,false);	
		
				/*
				$.ajax({
					url: "viewer/view_cari_det.php?fid=dlgDetail&tipe=jso&id="+me.marker_info[jmldata],
				  	success: function(data) {		
						delElem(cover);						
						var resp = eval('(' + data + ')');	//alert (resp.content.rows[0].noreg);	
						//var dat = resp.content.rows[0];									
						me.map.openInfoWindowHtml(latlin,
							"<div style=' width:640; height:440;'>"+resp.content+"</div>"
						);
						
				  	}
				});
				*/
				
				$.ajax({
					url: "viewer/view_cari_det.php?fid=dlgDetail&tipe=jso&id="+me.area_info[jmldata],
				  	success: function(data) {		
						delElem(cover);						
						var resp = eval('(' + data + ')');	//alert (resp.content.rows[0].noreg);	
						/*me.markers[jmldata].openInfoWindowHtml(
							"<div style=' width:640; height:440;'>"+resp.content+"</div>"
						);*/
						me.map.openInfoWindowHtml(latlin,
							"<div style=' width:640; height:520;'>"+resp.content+"</div>"
						);
						Gbr.idbi = info;
						Gbr.show();						
				  	}
				});
			
			
		});	
		
	}
	
	this.showMap = function() {
	 
				//if (map_content =='') map_content = 'mapContent';
			//google.load('maps', '2');		  			
		//if (GBrowserIsCompatible()) {
//			var skoord =  '-6.895160 107.633580';//removeCR(removeSpaces(document.getElementById('koordinat_gps').value)); -6.115333 106.152317
			var skoord =  '-6.115333 106.152317';//removeCR(removeSpaces(document.getElementById('koordinat_gps').value)); -6.115333 106.152317
			
			var me = this;
			//if(skoord!='' ){					
			
				this.MapIni();
				
			/*
				//create point -----------------------------------				
				this.addPoint('-6.895160, 107.633580', 'point1');
				this.addPoint('-6.894160, 107.633580', 'point2');
				
				//create bidang ---------------------------------
				this.addArea('-6.895072,107.633535;-6.895113,107.633676;'+
					'-6.895234,107.633643;-6.895194,107.633496;'+
					'-6.895072,107.633535', 'area1');
				this.addArea('-6.894072,107.633535;-6.894113,107.633676;'+
					'-6.894234,107.633643;-6.894194,107.633496;'+
					'-6.894072,107.633535', 'area2');
			*/	
				//event ----------------------------------------
				//change map type
				GEvent.addListener(this.map, 'maptypechanged', function() { 
					//alert(map.getCurrentMapType().getName(true));
					var nm = me.map.getCurrentMapType().getName(true);
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
				
			
			//}else{
			//	alert('Koordinat Lokasi belum diisi!');	
			//}
		//}else{
		//	alert('Browser Tidak Compatible!');	
		//}
	}
	
	this.gambar_lokasi= function(rows){
		this.clearMap();
		var tampilbidang = document.getElementById('tampilbidang').checked;
		//alert(this.markers.length); //do { } while (this.markers.length==0 && this.bidangs.length==0);
		for(var i=0; i<rows.length; i++){
			if (rows[i].koord != null){
				skoord = removeCR(removeSpaces(rows[i].koord));				
				if(skoord!='')	this.addPoint(rows[i].koord,rows[i].info);
			}else{
				console.info(i+'. gagal point: '+rows[i].info);	//alert('Gagal ambil data koordinat id='+rows[i].id+' i='+i)
			}
			
			if(tampilbidang ){
				if (  rows[i].area != null){
					sarea = removeCR(removeSpaces(rows[i].area));
					if(sarea!='') this.addArea(rows[i].area,rows[i].info, rows[i].color);
				}else{
					console.info(i+'. gagal area: '+rows[i].info);	//alert('Gagal ambil data area id='+rows[i].id+' i='+i)
				}
			}
		}
		//alert(this.markers.length+' '+this.bidangs.length);
	}
	//*
	this.showx = function(){
		var me = this;
		var kotakoord=document.getElementById('Wilayahfmxkotakoorgps').value;
		var kotazoom=document.getElementById('Wilayahfmxkotazoom').value;
		var keckoord=document.getElementById('Wilayahfmxkeckoorgps').value;
		var keczoom=document.getElementById('Wilayahfmxkeczoom').value;

		var szoom=11;
//			var skoord =  '-6.476766 106.824293'; // -6.115333 106.152317
			var skoord =  '-6.115333 106.152317'; // -6.115333 106.152317
//			skoord =' -0.789275 113.921327'; // indonesia
			
		
			if (kotakoord!=null && kotakoord!='')
			{
				szoom=11;				

				skoord=kotakoord.replace(' ','');
				skoord=skoord.replace(',',' ');

				if (kotazoom!=null && kotazoom!='')
				{
					
					szoom=parseInt(kotazoom);
				}
			
			}

			if (keckoord!=null && keckoord!='')
			{
				szoom=13;				
				skoord=keckoord.replace(' ','');
				skoord=skoord.replace(',',' ');

				if (keczoom!=null && keczoom!='')
				{
					szoom=szoom=parseInt(keczoom);
				}
				

			}
		me.MAP_zoom=szoom;
		me.MAP_center_point=skoord;
		

//		alert(this.MAP_zoom +' --- '+this.MAP_center_point);	

		this.MapIni();



				GEvent.addListener(this.map, 'maptypechanged', function() { 
					//alert(map.getCurrentMapType().getName(true));
					var nm = me.map.getCurrentMapType().getName(true);
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
	this.refreshList = function(){
//		alert('tes');

		
		var me = this;
		var err = '';
		var kib=document.getElementById('kib').value; //alert('kib'+kib+'|')
//		this.MAP_center_point=pcenter;
		if(err=='' && kib=='' ) err="KIB Belum Dipilih!";
		if(err == '' ){
			
		this.showx();

		var cover = 'map_cover';
		addCoverPage2(cover,1,true,false);	
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: 'pages.php?Pg=map&SPg=getdata',
		  	success: function(data) {		
				delElem(cover);
				
				var resp = eval('(' + data + ')');							
				//document.getElementById(cover).innerHTML = resp.content;	//me.loading();						
				document.getElementById('div_msg').innerHTML = "Hasil Pencarian: "+resp.content.jmldata+" data";
				me.gambar_lokasi(resp.content.rows);
				
		  	}
		});
		
		}else{
			alert(err);
		}
	}//*/
	
	this.menuShowHide = function(){
		if (document.getElementById('menu_content').style.display=='none'){
			document.getElementById('menu_content').style.display='block';
			//document.getElementById('menu_content').style.visibility='visible';
			//document.getElementById('menu_content').style.width='335';
			//document.getElementById('menu_overlay').style.width='370';
			document.getElementById('btcolapse').src = 'images/tumbs/left.png';
		}else{
			document.getElementById('menu_content').style.display='none';
			//document.getElementById('menu_content').style.visibility='hidden';	
			//document.getElementById('menu_content').style.width='0';
			//document.getElementById('menu_overlay').style.width='35';
			document.getElementById('btcolapse').src = 'images/tumbs/right.png';
		}
		
	}
	this.pilihKib = function(){
		//alert('tes');
		
		var kib=document.getElementById('kib').value; //alert('kib'+kib+'|')
		
		var cont = document.getElementById('detail_cont');
		//cont.innerHTML = kib;		
		var me = this;		
		var cover = 'map_cover';
		addCoverPage2(cover,1,true,false);	
		$.ajax({//type:'POST', //data:$('#'+this.formName).serialize(),
			url: 'pages.php?Pg=map&SPg=getmenukib&kib='+kib,
		  	success: function(data) {		
				delElem(cover);				
				var resp = eval('(' + data + ')');																	
				cont.innerHTML = resp.content;				
		  	}
		});
		
		
	}
	
	this.initial = function(){		
		for (var name in this.params) {
			//console.info ('name='+name);
			eval( 'if(this.params.'+name+' != null) this.'+name+'= this.params.'+name+'; ');
		}
	};	
	this.initial();
};

var Map = new MapObj({
	prefix  : 'Map',	
	MAP_center_point : '-6.476766 106.824293', // -0.789275, 113.921327  -3.800738 114.785254 -6.115333,106.152317 -6.115333 106.152317
	MAP_zoom : 8
});


