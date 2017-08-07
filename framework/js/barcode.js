//penggunaan barcode  -----------------------------------
//sensus.filterRenderAfter : function(){
//		barcodeSensus.loading();
//	},
// declare jzebra di penatausahaan.php genToolbarAtas()

var BarcodeCls = function(params_){
	this.params=params_;
	this.onscan = 0;
	this.name = '';//barcode';
	this.kode = '';
	//this.elinputname = this.name+'_input';	
	//this.elreadymsg = this.name+'_msg';
	
	
	
	this.setInputReady = function(){
		if(document.getElementById(this.elinputname)){
			document.getElementById(this.elinputname).readOnly=false;
			document.getElementById(this.elinputname).value='';
			document.getElementById(this.elinputname).focus();
		}
	}
	this.cekBarcode = function(){ 
		var me = this;
		//console.info('cek '+ me.onscan+' name:'+me.name);
		//console.info('cek el'+ document.getElementById(this.elinputname));
		if (this.onscan==0){
			if (document.getElementById(this.elinputname)){
				//cek focus				
				$("#"+this.elinputname).blur(function () {
    				document.getElementById(me.elreadymsg).innerHTML = //'Not Ready!';
						"<a style='color:red;' href=\"javascript:"+me.name+".setInputReady()\">Not Ready! (click for ready)</a>";
				});
				$("#"+this.elinputname).focus(function () {
    				document.getElementById(me.elreadymsg).innerHTML = 'Ready';
				});
				
				//cek str length
				var e = document.getElementById(this.elinputname);
				var str = e.value;
				//console.info('str.length '+ str.length);
				if (str.length>=32){
					//trigger input barcode to filter
					//alert(str);
					me.onscan = 1; 
					e.readOnly = true;
					//console.info('request'+this.onscan);
					this.kode = str;
					//this.setInputReady();
					//console.info('cek '+ me.onscan+' name:'+me.name);
					this.execBarcode(); //Penatausaha.refreshList(true);
				}
				//console.info('cek '+ this.onscan);
			}
		}
	}
	
	this.execBarcode=function(){
		//Penatausaha.refreshList(true);
	}
	
	this.loading = function(){
		var me= this;
		this.reset();
		//cekinput barcode
		/*setInterval(
			function(){				
				if (this.onscan==0){
					if (document.getElementById(this.elinputname)){
						barcode.inputBarcode();	
					}
				}		
			},1000
		);
		*/
		//setInterval(function(){barcode.cekBarcode()},1000);	
		setInterval(function(){
			eval(me.name+'.cekBarcode()')
			},
			1000
		);	
	} 
	
	this.reset = function(){
		
		if(document.getElementById(this.elinputname)){
			
		
		this.onscan = 0; 
		document.getElementById(this.elinputname).readOnly=false;
		document.getElementById(this.elinputname).focus();
		document.getElementById(this.elreadymsg).innerHTML = 'Ready';
		//console.info('succses'+this.onscan); 
		if (document.getElementById(this.elinputname)) {
			document.getElementById(this.elinputname).value='';
		}
		}
	}
	
	this.printlabel = function(jmlcetak,jnslabel,nmbidang,nmopd,nmbiro,nmbarang,kode) {
	
    	//console.log('masuk');
      	var applet = document.jzebra;
        //console.log(applet);
      	if (applet != null) {
			//kode = '11 10 00 04 04 08 04     02 03 01 01 01 0001';
			//        12 28 01 01 01 00 01 001 01 01 11 01 004 0002
			// 		  12 28 01 01 01 00 01 001 03 11 01 01 001 0001
			
			// KIB C 12280101010001001031101010010001
			// kib A
			
			
			kode1 = kode.substr(0,2)+'.'+ kode.substr(2,2)+'.'+ kode.substr(4,2)+'.'+
				kode.substr(6,2)+'.'+kode.substr(8,2)+'.'+kode.substr(10,2)+'.'+
				kode.substr(12,2)+'.'+
				kode.substr(14,3);//'11.10.00.04.04.08.04';
	    	kode2 = 
				' '+kode.substr(17,2)+'.'+ kode.substr(19,2)+'.'+ kode.substr(21,2)+'.'+
				kode.substr(23,2)+'.'+kode.substr(25,3)+'.'+kode.substr(28,4);//' 02.03.01.01.01.0001';
			//alert(jnslabel+' - '+kode1+' '+kode2);
            
			for(var i=0;i<jmlcetak;i++){
				
			
			switch(jnslabel){
            case '1':

                applet.append("^XA");
                applet.append("^MMT");
                applet.append("^PW799");
                applet.append("^LL0400");
                applet.append("^LS0");
                applet.append("^FT20,170^A0N,28,26^FH\^FD"+nmopd+"^FS");
                applet.append("^FT20,205^A0N,20,16^FH\^FD"+nmbiro+"^FS");
                applet.append("^FT510,170^A0N,30,32^FH\^FD"+kode1+"^FS");
                applet.append("^FT510,207^A0N,30,32^FH\^FD"+kode2+"^FS");
                applet.append("^BY4,3,85^FT20,328^BCN,,N,N");
                applet.append("^FD>;"+kode+"^FS");
                applet.append("^FT20,369^A0N,26,20^FH\^FD"+nmbarang+"^FS");
                applet.append("^PQ1,0,1,Y^XZ");
                //applet.print();
                break;
             case '2':
                applet.append("^XA");
                applet.append("^MMT");
                applet.append("^PR5");
                applet.append("^PW718");
                applet.append("^LL1200");
                applet.append("^LS0");
                applet.append("^FT410,60^A0R,56,55^FH\^FD"+nmopd+"^FS");
                applet.append("^FT340,60^A0R,34,33^FH\^FD"+nmbiro+"^FS");
                applet.append("^FT410,740^A0R,56,55^FH\^FD"+kode1+"^FS");
                applet.append("^FT330,740^A0R,56,55^FH\^FD"+kode2+"^FS");
                applet.append("^BY6,3,160^FT260,1200^BCB,,N,N");
                applet.append("^FD>;"+kode+"^FS");
                applet.append("^FT40,60^A0R,42,40^FH\^FD"+nmbarang+"^FS");
                applet.append("^PQ1,0,1,Y^XZ");
                //applet.print();
                break;
                
            
            case '3':
                applet.append("^XA");
                applet.append("^MMT");
                applet.append("^PW719");
                applet.append("^LL1600");
                applet.append("^LS0");
                applet.append("^FT350,30^A0R,68,67^FH\^FD"+nmopd+"^FS");
                applet.append("^FT280,30^A0R,45,45^FH\^FD"+nmbiro+"^FS");
                applet.append("^FT350,1092^A0R,56,55^FH\^FD"+kode1+"^FS");
                applet.append("^FT280,1092^A0R,56,55^FH\^FD"+kode2+"^FS");
                applet.append("^BY8,3,160^FT260,1545^BCB,,N,N");
                applet.append("^FD>;"+kode+"^FS");
                applet.append("^FT40,30^A0R,51,50^FH\^FD"+nmbarang+"^FS");
                applet.append("^PQ1,0,1,Y^XZ");
                //applet.print();
                break;
            case '4':
                applet.append("^XA");
                applet.append("^MMT");
                applet.append("^PW480");
                applet.append("^LL0300");
                applet.append("^LS0");
                applet.append("^FT14,118^A0N,20,19^FH\^FD"+nmopd+"^FS");
                applet.append("^FT14,135^A0N,11,12^FH\^FD"+nmbiro+"^FS");
                applet.append("^FT260,115^A0N,17,16^FH\^FD"+kode1+"^FS"); //21-320
                applet.append("^FT260,135^A0N,17,16^FH\^FD"+kode2+"^FS"); //21-320
                applet.append("^BY2,3,40^FT17,180^BCN,,N,N");
                applet.append("^FD>;"+kode+"^FS");
                applet.append("^FT14,195^A0N,14,14^FH\^FD"+nmbarang+"^FS");
                applet.append("^PQ1,0,1,Y^XZ");
                //applet.print();
                break;
            case '5':
                applet.append("^XA");
                applet.append("^MMT");
                applet.append("^PW480");
                applet.append("^LL0300");
                applet.append("^LS0");
                applet.append("^FT87,135^A0N,17,16^FH\^FD"+kode1+ kode2+"^FS");
                applet.append("^BY2,3,40^FT92,180^BCN,,N,N");
                applet.append("^FD>;"+kode+"^FS");
                applet.append("^FT87,195^A0N,14,14^FH\^FD"+nmbiro+"^FS");
                applet.append("^FT290,195^A0N,14,14^FH\^FD"+nmbarang+"^FS");
                applet.append("^PQ1,0,1,Y^XZ");
                //applet.print();
                break;
            }
	   		}
			applet.print();
	    //*/
		}else{
			alert('Java Aplet Gagal!');
		}
	}
	
	/*
	this.cetak_ = function(){ //tombol cetak
		var me= this;
		var jnslabel = document.getElementById('jnslabel').value;
		var jmlcetak = document.getElementById('jmlcetak').value;
		var err = '';
		
		if(err=='' && jnslabel =='' ) err = 'Jenis Label Belum Dipilih'
		
		if(err == ''){
			
		
			document.body.style.overflow='hidden';
					
					addCoverPage('coverpage',100);
					
					$.ajax({
						type:'POST', 		
						url: 'pages.php?Pg=genxmlbar', 
						data:$('#adminForm').serialize()+'&jnslabel='+jnslabel+'&jmlcetak='+jmlcetak, 
						success: function(response) {
							
							var resp = eval('(' + response + ')');
							document.body.style.overflow='auto';
							delElem('coverpage');
							if(resp.err != ''){
								alert(resp.err);
							}else{
								me.Close();
							}
							
						}
					});
		}else{
			alert('Jenis Label Belum Dipilih!');
		}
		
	}
	*/
	
	this.cetaklabelcbx_ = function(cbno){
		var cb = document.getElementById('cb'+cbno.toString());
		if(cb.checked){
			var kode = cb.getAttribute('kode');
			var nmbarang = cb.getAttribute('nmbarang');
			var nmbidang = cb.getAttribute('bidang');
			var nmopd = cb.getAttribute('opd');
			var nmseksi = cb.getAttribute('seksi');
			//console.log//alert('kode='+kode+' barang='+nmbarang+' bidang='+nmbidang+' opd='+nmopd);
			this.printlabel(jmlcetak,jnslabel,nmbidang,nmopd,nmbarang,kode)
		}
	}
	
	this.cetak_ = function(){ //tombol cetak
		var me= this;
		var jnslabel = document.getElementById('jnslabel').value;
		var jmlcetak = document.getElementById('jmlcetak').value;
		var err = '';
		var applet = document.qz;
//		applet.findPrinter("printer barcode");
		if(err=='' && jnslabel =='' ) err = 'Jenis Label Belum Dipilih!';
		if(err=='' && applet == null) err = 'Java Applet Gagal!';
		if(err == ''){
			if(document.getElementById('jmPerHal')){
				var jmPerHal = document.getElementById('jmPerHal').value;				
			}else{
				var jmPerHal = 25;
			}
			
			for (var i=0;i<jmPerHal;i++){
				//this.cetaklabelcbx_(i);
				//*
				var cb = document.getElementById('cb'+i.toString());
				if (cb){					
				
				if(cb.checked){
					var kode = cb.getAttribute('kode');
					var nmbarang = cb.getAttribute('nmbarang');
					var nmbidang = cb.getAttribute('bidang');
					var nmopd = cb.getAttribute('opd');
					var nmbiro = cb.getAttribute('biro');
					var nmseksi = cb.getAttribute('seksi');
					var nm_gedung = cb.getAttribute('nm_gedung');
					var nm_ruang = cb.getAttribute('nm_ruang');
					var thn_perolehan = cb.getAttribute('thn_perolehan');
					var str = "SKPD : "+nmopd+",UNIT : "+nmbiro+",SUBUNIT : "+nmseksi+",GEDUNG : "+nm_gedung+",RUANG : "+nm_ruang+",KODE : "+kode+",NAMA BARANG : "+nmbarang+",THN PEROLEHAN : "+thn_perolehan;
			  		var jml_str = str.length;
					var sisa_str = 480-jml_str;
					var i=0;
					var space ='';
					for (i = 0; i < sisa_str; i++) {
						space+=' ';						
					}
						
					//alert('text='+str_);
					//console.log
					// alert('kode='+kode+' barang='+nmbarang+' bidang='+nmbidang+' opd='+nmopd);
					//this.printlabel(jmlcetak,jnslabel,nmbidang,nmopd,nmbarang,kode)
					//--------------------------------
					
			        //console.log(applet);
			      	//if (applet != null) {
						//kode = '1110000404080402030101010001';
						// 12 28 01 01 01 00 01 001 03 11 01 01 001 0001
						
			kode1 = kode.substr(0,2)+'.'+ kode.substr(2,2)+'.'+ kode.substr(4,2)+'.'+
				kode.substr(6,2)+'.'+kode.substr(8,2)+'.'+kode.substr(10,2)+'.'+
				kode.substr(12,2)+'.'+
				kode.substr(14,3);//'11.10.00.04.04.08.004';
	    	kode2 = 
				' '+kode.substr(17,2)+'.'+ kode.substr(19,2)+'.'+ kode.substr(21,2)+'.'+
				kode.substr(23,2)+'.'+kode.substr(25,3)+'.'+kode.substr(28,4);//' 02.03.01.01.01.0001';
			//alert(jnslabel+' - '+kode1+' '+kode2);
						//alert(jnslabel+' - '+kode1+' '+kode2);
			    nmgedang=nm_gedung+' - '+nm_ruang;      
						for(var j=0;j<jmlcetak;j++){							
						
						switch(jnslabel){
			            case '1': //9*5

/*			                applet.append("^XA");
			                applet.append("^MMT");
			                applet.append("^PW799");
			                applet.append("^LL0400");
			                applet.append("^LS0");
			                applet.append("^FT20,170^A0N,28,26^FH\^FD"+nmopd+"^FS");
			                applet.append("^FT20,205^A0N,20,16^FH\^FD"+nmseksi+"^FS");
			                applet.append("^FT510,170^A0N,30,32^FH\^FD"+kode1+"^FS");
			                applet.append("^FT510,207^A0N,30,32^FH\^FD"+kode2+"^FS");
			                applet.append("^BY4,3,85^FT20,328^BCN,,N,N");
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT20,369^A0N,26,20^FH\^FD"+nmbarang+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");
*/			

			                applet.append("^XA");
			                applet.append("^MMT");
			                applet.append("^PW799");
			                applet.append("^LL0400");
			                applet.append("^LS0");
			                applet.append("^FT15,190^A0N,28,26^FH\^FD"+nmopd+"^FS");
			                applet.append("^FT15,215^A0N,20,16^FH\^FD"+nmseksi+"^FS");
			                applet.append("^FT455,210^A0N,30,32^FH\^FD"+kode1+"^FS");
			                applet.append("^FT495,240^A0N,30,32^FH\^FD"+kode2+"^FS");
							applet.append("^FT15,235^A0N,20,16^FH\^FD"+nmgedang+"^FS");
			                applet.append("^BY3,3,80^FT15,338^BCN,,N,N");
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT15,375^A0N,26,20^FH\^FD"+nmbarang+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");
			                //applet.print();
			               
							
							 break;
			             case '2':
                			applet.append("^XA");
                			applet.append("^MMT");
                			applet.append("^PR5");
                			applet.append("^PW718");
                			applet.append("^LL1200");
                			applet.append("^LS0");
                			applet.append("^FT390,40^A0R,56,55^FH\^FD"+nmopd+"^FS");
                			applet.append("^FT330,40^A0R,34,33^FH\^FD"+nmseksi+"^FS");
                			applet.append("^FT380,840^A0R,34,33^FH\^FD"+kode1+"^FS");
                			applet.append("^FT320,880^A0R,34,33^FH\^FD"+kode2+"^FS");
							applet.append("^FT290,40^A0R,34,33^FH\^FD"+nmgedang+"^FS");
                			applet.append("^BY4,3,160^FT260,890^BCB,,N,N");
                			applet.append("^FD>;"+kode+"^FS");
                			applet.append("^FT40,40^A0R,42,40^FH\^FD"+nmbarang+"^FS");
                			applet.append("^PQ1,0,1,Y^XZ");
			                //applet.print();
			                break;
			                
			            
			            case '3':
/*			                applet.append("^XA");
			                applet.append("^MMT");
			                applet.append("^PW719");
			                applet.append("^LL1600");
			                applet.append("^LS0");
			                applet.append("^FT350,30^A0R,68,67^FH\^FD"+nmopd+"^FS");
			                applet.append("^FT280,30^A0R,45,45^FH\^FD"+nmseksi+"^FS");
			                applet.append("^FT350,1092^A0R,56,55^FH\^FD"+kode1+"^FS");
			                applet.append("^FT280,1092^A0R,56,55^FH\^FD"+kode2+"^FS");
			                applet.append("^BY8,3,160^FT260,1545^BCB,,N,N"); 1485
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT40,30^A0R,51,50^FH\^FD"+nmbarang+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");
*/
			                applet.append("^XA");
			                applet.append("^MMT");
			                applet.append("^PW719");
			                applet.append("^LL1600");
			                applet.append("^LS0");
			                applet.append("^FT380,80^A0R,55,54^FH\^FD"+nmopd+"^FS");
			                applet.append("^FT330,80^A0R,35,35^FH\^FD"+nmseksi+"^FS");
			                applet.append("^FT380,1150^A0R,35,35^FH\^FD"+kode1+"^FS");
			                applet.append("^FT330,1190^A0R,35,35^FH\^FD"+kode2+"^FS");							
							applet.append("^FT280,80^A0R,35,35^FH\^FD"+nmgedang+"^FS");
			                applet.append("^BY4,3,160^FT260,925^BCB,,N,N"); 
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT40,80^A0R,51,50^FH\^FD"+nmbarang+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");
			                //applet.print();
			                break;
			            case '4':
			                applet.append("^XA");
			                applet.append("^MMT");
			                applet.append("^PW480");
			                applet.append("^LL0300");
			                applet.append("^LS0");
			                applet.append("^FT14,118^A0N,20,19^FH\^FD"+nmopd+"^FS");
			                applet.append("^FT14,135^A0N,11,12^FH\^FD"+nmseksi+"^FS");
			                applet.append("^FT290,115^A0N,17,16^FH\^FD"+kode1+"^FS");
			                applet.append("^FT310,135^A0N,17,16^FH\^FD"+kode2+"^FS");
			                applet.append("^BY2,3,40^FT17,180^BCN,,N,N");
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT14,195^A0N,14,14^FH\^FD"+nmbarang+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");
			                //applet.print();
			                break;
			            case '5':
			                applet.append("^XA");
			                applet.append("^MMT");
			                applet.append("^PW480");
			                applet.append("^LL0300");
			                applet.append("^LS0");
			                applet.append("^FT87,135^A0N,17,16^FH\^FD"+kode1+ kode2+"^FS");
			                applet.append("^BY2,3,40^FT92,180^BCN,,N,N");
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT87,195^A0N,14,14^FH\^FD"+nmseksi+"^FS");
			                applet.append("^FT290,195^A0N,14,14^FH\^FD"+nmbarang+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");
			                //applet.print();
			                break;
			            
						
						case '6': //8*4
			               	/*applet.append("^XA");
			                applet.append("^MMT");
			                applet.append("^PW799");
			                applet.append("^LL0400");
			                applet.append("^LS0");
			                applet.append("^FT50,165^A0N,28,26^FH\^FD"+nmopd+"^FS");  //FT kiri, atas
			                applet.append("^FT50,200^A0N,20,16^FH\^FD"+nmseksi+"^FS");
			                applet.append("^FT400,165^A0N,30,32^FH\^FD"+kode1+"^FS");
			                applet.append("^FT435,200^A0N,30,32^FH\^FD"+kode2+"^FS");
			                applet.append("^BY3,3,85^FT20,328^BCN,,N,N");
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT20,369^A0N,26,20^FH\^FD"+nmbarang+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");*/
							
							applet.append("^XA"); //start new label format
			                applet.append("^MMT"); //print mode T (tear-off))
			                //applet.append("^PW480");
							applet.append("^PW580"); //print width 
			                applet.append("^LL0300"); //label length?
			                applet.append("^LS0"); //label shift
			                //applet.append("^FT14,118^A0N,20,19^FH\^FD"+nmopd+"^FS");//FT kiri, atas
			                //applet.append("^FT14,135^A0N,11,12^FH\^FD"+nmseksi+"^FS");
			                //applet.append("^FT290,115^A0N,17,16^FH\^FD"+kode1+"^FS");
			                //applet.append("^FT310,135^A0N,17,16^FH\^FD"+kode2+"^FS");
							applet.append("^FT-5,160^A0N,20,19^FH\^FD"+nmopd+"^FS");//FT kiri, atas , 
							applet.append("^FT5,180^A0N,15,14^FH\^FD"+nmseksi+"^FS");
			                applet.append("^FT405,160^A0N,17,16^FH\^FD"+kode1+"^FS");
			                applet.append("^FT425,180^A0N,17,16^FH\^FD"+kode2+"^FS");							
			                applet.append("^FT5,200^A0N,15,14^FH\^FD"+nmgedang+"^FS");
			                //applet.append("^BY2,3,70^FT25,250^BCN,,N,N"); //BY width, ratio, height barcode
							//applet.append("^BY2,3,40^FT92,180^BCN,,N,N");
							applet.append("^BY2,3,40^FT5,250^BCN,,N,N");
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT5,280^A0N,14,14^FH\^FD"+nmbarang+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");
			                break;
							
						/*case '7' :
							applet.append("^XA");
							applet.append("^MMT");
							applet.append("^PW799"); //print width 
			                applet.append("^LL0900"); //label length?
							applet.append("^FO170,47");
							applet.append("^GFA,3173,3173,19,S0QFE,P03VF8,O03XFC,N0gGFE,L01MFC001981DOF,K03KFC06C42D9DBDC3DMF,K0JFC4692563C9D7CE9C937KF,J01IFB926D2772C9CF8E9CD36CJF,J01FE3B92692770C1CF8E98DBI67FF,K0F17B92685372E5C78E38CB67643F,K0FAF18268C372F1C7863A48C7359F,K0FAF18269D37371D3263860C735DF,J01F9F19269BB7319D9769260C311FF8,J01F9E59A6998309D8070976493419F8,J01F8EC9218BOFC224B3459F8,J01F86C07UFD13659F8,J01FB0C3XF0649F8,J01F11gHFE1F8,J01MFEB8O07BMF8,J01LFV07KF8,J01JF8X01JF8,J01IF8J0EO018K0IF8,J01FFK03EK06I074L07F8,J01FK06FCK0EI0463L0F8,J01FJ01EF8J01EI0CFF8K0F8,J01FJ01DEK03EI05E1L0F8,J01FJ03D8K07CI04E188J0F8,J01FJ03818J07CI07DFFEJ0F8,J01FJ03878J07CI018F82J0F8,J01FJ038FK0FCJ0CF02J0F8,J01FI0219EK0FCJ07F36J0F8,J01FI03198K0FCK0F3DEI0F8,J01FI0398L0FCK02783I0F8,J01FI03884K0FCK01F83I0F8,J01FI03C1CK0FCL0732I0F8,J01FI01C38K0FCL023EI0F8,J01F0019E38K0FCL033F800F8,J01F001CC2L0FCL01E7600F8,J01F001EC6CK0FCM0E0600F8,J01F001E458K0FCM0C8C00F8,J01F001F838K0FCL010FC00F8,J01FI0F878K0FCM08FC00F8,J01FI07CF8K0FCM0CFC00F8,J01F00C397L0FCM07B800F8,J01F00E194L0F8L01F1800F8,J01F0070918K0F8L0301C00F8,J01F0078B3808I0F8L0218600F8,J01F007C27808I0F8L0330600F8,J01F003E0F808I0F8L01FCC00F8,J01F001E4F008I0F8L01E7800F8,J01F00424E608I0F8L03C3I0F8,J01F007845C08I0F8L0C01I0F8,J01F003C21C08I0F8L0C01C00F8,J01F001F23C08I0F8L0610600F8,J01F001F83C0CI0F8L06F0600F8,J01FI0793808I0F8K01F10400F8,J01F001983088I0F8K0330E80078,J01F021E423CCI0F8K0200FI078,J01F010F0278CI0FI04006007I078,J01F008F82F84I0FI0E00200180078,J01F004790F34I0F0071001FC180078,J01FI07E8E76007FE001809383I078,J01F0023E8CF200FFE02083E186I078,J01F0030340F3I0F80208601FCI078,J01F0013C41F1I0F00318401F8I078,J01FI0FF21CD800FI0E06001J078,J01FI0DF819C800FI09F7F81J078,J01FI04F81BC800F00491CF83J078,J01FI027E93CC00F006900786J078,J01FI020047C800F00A1007B8J078,J01FI031FA78800FC41B007FK078,J01FI011FE71800FE00BFC7EK078,J01FI010FFE1I0FF003F81CK078,J01FI0183EE1I0FE20BF81L078,J01FJ081FE1I0781F3F8601J078,J01FJ0C00F9L0A3F8C3FJ078,J01FJ071IFL081FF801J078,J01FJ03FC03K0103FF002J078,J01FN01CJ010E7808K078,J01FO07J027808003J078,J01FO03CI03C0FC06K078,J01FP0F803F158429CJ078,J01FP03F3F802078L078,J01FO07JF00303M078,J01FO03JFEI04M078,J01FO03F81FE00F8M078,J01FO02J04Q078,J01FgL078,J01F03F87F03F83F83F81FC1FC1FC078,J01F07F87F03F83F83F81FC1FC1FC078,:J01F3gJF878,::::J01FgL078,J01FI0EC00EC007C003E003E007F078,J01F1FFCFFECOF7FFDJF078,J01FI0FC00FC0278003C001A001C078,J01F1FF03FE81FF87FF83FFE7FFE3078,J01FI0FC007C003C003E183C081C078,J01F0FFCF7ECFFDIFDFF67FFE7FF078,J01F0387C387C383838387E007EI078,K0F1FFC7FEC7FFEIFE7FFE7FFE7878,K0F078007800380038003E003EI07,K0FgL07,K0F030040040900C0402004098030F8,K0F9A02C06C0D06C243206C0D01A0F,K0F8E018038060381C1C0380700C0F,K0F9E0380780E0783C1C0780F01C0F,I07FF8gK0F,007IFCgK0F,01JFC0201200204C2212K04I01IF8,03JFC36012016068161A02406C0D81IFE,03JFC1C00E00C0301C0E03C0380781JF,07FFBFE1C01C00C0781C1C0180380303JF8,07FF9FE1C01C01CI01C1C03C0380F03FBFFC,07FF1FFgJ03F3FFC,1FD84FFS01P03F0FFC,3FC0E7FK0804C0C80900903301207FE3FC,7FE647F84C0480280780F00F01E00E07FF8FF8FFE70FF8280300180700600700E00E0FC007F8MF8380700300700E00201E01C0F81IF8MFC3gG01FA3IF8IF800FEP0198O03FFC3FF8IFD91FEJ013006C0D01900100883IF3FF8JFD3FF02600A00280F00F00900D07C077FF87IFC7BF01C00E00380E00600E00707E00IF87IF0F9F81C01E00300E00E00E0070FF24IF87FBF9F8FC3CT08I01FF8JF83C1F9F0FEg01FFCJF83C0FFC9FET0D801303E363F9F83C07E19DF00C8013J0D807800A07CE67F0781C07F398FC07001A02407007I0E07DC0FE0781C03FBB3FE07I0E02C0700F001E0F9F9FE0780C01FF87FF07I0C0180FM01F9FDFC07,0400FF180F8L038O03ECFBF807,J0FF31F7EI08P0100FCE33F002,J07FF3F3F0078P0B01FA707E002,J03FF7F3FC038P0607FF3FFC,J01FF3D6FF0700481301B00E1FC7BFFC,K0FFBCE7FC500780E00E00A3F800FF8,K07FC861FFI0300E006I0FFE407F,K03FE38EFF800701E01E007IF7FFE,K01IF803FFP01FF1F9FFC,L0IF0FBFFCO0IF87C7F8,L07FC6FFDFFN07FF3A3CFF,L03FE6FF9FFCL0BFFC780FFE,M0FFCFE1IFEJ0IFC738C7F8,M07FC7CBE1NFC0FBBDFF,M03FF309F0NF0CF99FFE,N0FF821F7LF87CE7D9FF8,N07FF79E7KFC23CE3DIF,N01IF1EFFC040773EF31FFC,O07FF9CFFEE67737E787FF8,O03IF0FFEE66F07B7JF,P0IFC27C0E0FB3C7IFC,P03IF8FC7E7F984JF,Q0KFCFE7B8KFC,Q01JF87C037JFE,R07SF,S0RFC,S01PFC,U0NFE,V05KFC,,^FS");
			                applet.append("^FT115,247^A0N,25,25^FH\^FDBARANG MILIK DAERAH^FS");
			                applet.append("^FT175,277^A0N,25,25^FH\^FDPEMERINTAH^FS");
			                applet.append("^FT115,307^A0N,25,25^FH\^FDKABUPATEN KARAWANG^FS");
			                applet.append("^FO475,53");
							applet.append("^BQN,2,4");
							//applet.append("^FH_^FDMM,Aabcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi ^FS");
							applet.append("^FH_^FDMM,ASKPD : "+nmopd+",B0002_0d_0a,AUNIT : "+nmbiro+",B0002_0d_0a,ASUBUNIT : "+nmseksi+",B0002_0d_0a,AGEDUNG : "+nm_gedung+",B0002_0d_0a,ARUANG : "+nm_ruang+",B0002_0d_0a,AKODE : "+kode+",B0002_0d_0a,ANAMA BARANG : "+nmbarang+",B0002_0d_0a,ATHN PEROLEHAN : "+thn_perolehan+""+space+"^FS");
			               	applet.append("^BY2,3,40^FT30,357^BCN,,N,N");
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT30,377^A0N,15,15^FH\^FD"+nmbarang+"^FS");
			                //applet.append("^FT235,350^A0N,15,15^FH\^FD"+kode+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");	
							applet.append("^XZ");							
							break;						
						*/
						case '7' :
							applet.append("^XA");
			                applet.append("^MMT");
							//applet.append("^PW580"); //print width 
			                //applet.append("^LL0300"); //label length?
			                //applet.append("^LS0"); //label shift
			                applet.append("^FO450,50");
							applet.append("^BQN,2,5");
							applet.append("^FH_^FDMM,ASKPD : "+nmopd+",B0002_0d_0a,AUNIT : "+nmbiro+",B0002_0d_0a,ASUBUNIT : "+nmseksi+",B0002_0d_0a,AGEDUNG : "+nm_gedung+",B0002_0d_0a,ARUANG : "+nm_ruang+",B0002_0d_0a,AKODE : "+kode+",B0002_0d_0a,ANAMA BARANG : "+nmbarang+",B0002_0d_0a,ATHN PEROLEHAN : "+thn_perolehan+""+space+"^FS");
			               	applet.append("^XZ");							
						break;
			            }
						/*case '8' :
							applet.append("^XA");
							applet.append("^MMT");
							applet.append("^PW799"); //print width 
			                applet.append("^LL0900"); //label length?							
			                applet.append("^FO470,53");
							applet.append("^BQN,2,4");
							//applet.append("^FH_^FDMM,Aabcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi ^FS");
							applet.append("^FH_^FDMM,ASKPD : "+nmopd+",B0002_0d_0a,AUNIT : "+nmbiro+",B0002_0d_0a,ASUBUNIT : "+nmseksi+",B0002_0d_0a,AGEDUNG : "+nm_gedung+",B0002_0d_0a,ARUANG : "+nm_ruang+",B0002_0d_0a,AKODE : "+kode+",B0002_0d_0a,ANAMA BARANG : "+nmbarang+",B0002_0d_0a,ATHN PEROLEHAN : "+thn_perolehan+""+space+"^FS");
			               	applet.append("^BY2,3,40^FT25,357^BCN,,N,N");
			                applet.append("^FD>;"+kode+"^FS");
			                applet.append("^FT25,377^A0N,15,15^FH\^FD"+nmbarang+"^FS");
			                //applet.append("^FT235,350^A0N,15,15^FH\^FD"+kode+"^FS");
			                applet.append("^PQ1,0,1,Y^XZ");	
							applet.append("^XZ");							
							break;						
						}*/
						case '8' :
							applet.append("^XA");
			                applet.append("^MMT");
							//applet.append("^PW580"); //print width 
			                //applet.append("^LL0300"); //label length?
			                //applet.append("^LS0"); //label shift
			                applet.append("^FO450,50");
							applet.append("^BQN,2,5");
							applet.append("^FH_^FDMM,ASKPD : "+nmopd+",B0002_0d_0a,AUNIT : "+nmbiro+",B0002_0d_0a,ASUBUNIT : "+nmseksi+",B0002_0d_0a,AGEDUNG : "+nm_gedung+",B0002_0d_0a,ARUANG : "+nm_ruang+",B0002_0d_0a,AKODE : "+kode+",B0002_0d_0a,ANAMA BARANG : "+nmbarang+",B0002_0d_0a,ATHN PEROLEHAN : "+thn_perolehan+""+space+"^FS");
			               	applet.append("^XZ");							
						break;
			            }
						
						
				   	}
						//applet.print();
				    //*/
					//}else{
					//	alert('Java Aplet Gagal!');
					//}
					
					//--------------------------------------
				}
				//*/
				}
			}
			//alert(nmbiro);
			applet.print();
			this.Close();							
		}else{
			//alert('Jenis Label Belum Dipilih!');
			alert(err);
		}
		
	}
	this.cetak = function(){
		//this.setFormCetak();
			if (adminForm.boxchecked.value >0 ){
				if(confirm('Yakin '+adminForm.boxchecked.value+' barcode akan di cetak?')){
					this.setFormCetak();
				}
			}
	}
	this.Close= function(){
		delElem('barcode_formCetakCover');
	}
	this.setFormCetak= function(){
		var form_judul = 'Cetak Label Barcode';
		var form_width = '400';
		var form_height = '150';
		var cover ='barcode_formCetakCover';
		//document.body.style.overflow='hidden';
		addCoverPage2(cover,999,true,false);
		
		var form_menu =
			"<div style='padding: 0 8 9 8;height:22; '>"+
			"<div style='float:right;'>"+
				"<input type='button' value='Cetak' onclick='barcode.cetak_()'>"+
				"<input type='button' value='Batal' onclick='barcode.Close()'>"+
				"<input type='hidden' id='Sensus_idplh' name='Sensus_idplh' value='109'><input type='hidden' id='Sensus_fmST' name='Sensus_fmST' value='1'>"+
				"<input type='hidden' id='sesi' name='sesi' value=''>"+
			"</div>"+
			"</div>";
		
		var content = 
			"<div id='Sensus_form_div' style='margin:9 8 8 8; overflow:auto; border:1px solid #E5E5E5;width:"+(form_width-20)+";height:"+(form_height-80)+";'>"+
				"<table style='width:100%' class='tblform'><tr><td style='padding:4'>"+
					"<table style='width:100%:height:100%'>"+
						"<tr>"+
							"<td style='width:100'>Jenis Label</td>"+
							"<td style='width:10'>:</td>"+
							"<td>"+
								"<select name='jnslabel' id='jnslabel'>"+
								"<option value=''>Pilih</option>"+
								"<option selected value='1'>Label 1 (9 x 5 cm)</option>"+
								"<option value='2'>Label 2 (16 x 9 cm)</option>"+
								"<option value='3'>Label 3 (20 x 9 cm)</option>"+
								"<option value='4'>Label 4 (6 x 2,6 cm)</option>"+
								"<option value='5'>Label 5 (6 x 1,38 cm)</option>"+
								"<option value='6'>Label 6 (8 x 4 cm)</option>"+
								"<option value='7'>Label 7 (QR code)</option>"+
								"<option value='8'>Label 8 (QR code)No Logo</option>"+
								"</select>"+
							"</td>"+
						"</tr>"+
						"<tr>"+
							"<td style='width:100'>Jumlah Cetak</td>"+
							"<td style='width:10'>:</td>"+
							"<td><input type='text' id='jmlcetak' name='jmlcetak' style='width:100;' value=1></td>"+
						"</tr>"+						
					"</table>"+
				"</td></tr></table>"+
			"</div>";
		
		
		document.getElementById(cover).innerHTML= 
			"<table width='100%' height='100%'><tbody><tr><td align='center'>"+
			//"rtera"+
			"<div id='div_border' style='width:"+form_width+";height:"+form_height+"; background-color:white; border-color: rgba(0, 0, 0, 0.3);   border-style: solid;  border-width:1; box-shadow: 6px 6px 5px rgba(0, 0, 0, 0.3);'>"+
			"<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tbody><tr><td style='padding:0'>"+
				"<div class='menuBar2' style='height:20'>"+			
				"<span style='cursor:default;position:relative;left:6;top:2;color:White;font-size:12;font-weight:bold'>"+form_judul+"</span>"+
				"</div>"+
			"</td></tr></tbody></table>"+			
			content+
			form_menu+		
			"</div>"+
				
			"</td></tr>"+
			"</table>";
		
			
	}
	this.initial = function(){	
		//change param default	
		for (var name in this.params) {
			eval( 'if(this.params.'+name+' != null) this.'+name+'= this.params.'+name+'; ');
		}
		this.elinputname = this.name+'_input';	
		this.elreadymsg = this.name+'_msg';
	}
	this.initial();
	
} 
var barcode = new BarcodeCls({
	name:'barcode',
	execBarcode : function(){
		Penatausaha.refreshList(true);
		
	}
});
var barcodeSensus = new BarcodeCls({
	name:'barcodeSensus',
	execBarcode : function(){
		//alert('tes');
		Sensus.refreshList(true);
		//this.reset();
		/*var me = this;
		var kode = this.kode;
		var cover = 'coverinsertbarcode';	
		addCoverPage(cover,100);
					
		$.ajax({		
			type:'POST', 		
			url: 'pages.php?Pg=sensus&tipe=insertsensus&kode='+kode, 
			data:$('#Sensus_form').serialize(), 
			success: function(response) {
				var resp = eval('(' + response + ')');
				delElem('coverinsertbarcode');
				if(resp.err != ''){
				}else{
				}
				me.reset();
				Sensus.refreshList(true);
			}
		});*/
		
	}
});
var barcodeSensusBaru = new BarcodeCls({
	name : 'barcodeSensusBaru',
	execBarcode : function(){
		var me = this;
		var kode = this.kode;
		var cover = 'coverinsertbarcode';	
		addCoverPage(cover,100);
					
		$.ajax({		
			type:'POST', 		
			url: 'pages.php?Pg=sensustmp&tipe=insertsensus&kode='+kode, 
			data:$('#Sensus_form').serialize(), 
			success: function(response) {
				var resp = eval('(' + response + ')');
				delElem('coverinsertbarcode');
				if(resp.err != ''){
				}else{
				}
				me.reset();
				SensusTmp.refreshList(true);
			}
		});
		
	}
});

var barcodeCariBarang = new BarcodeCls({
	name:'barcodeCariBarang',
	execBarcode : function(){
		Penatausaha.refreshList(true);
		
	}
});
barcodeCariBarang.loading();

var barcodeUsulanHapusBA = new BarcodeCls({
	name:'barcodeUsulanHapusBA',
	execBarcode : function(){
		UsulanHapusbadetail.refreshList(true);
		
	}
});
barcodeUsulanHapusBA.loading();

