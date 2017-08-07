var gantirugibayarSkpd = new SkpdCls({
	prefix : 'gantirugibayarSkpd', 
	formName: 'gantirugibayarForm',
});

var gantirugiCariSkpd = new SkpdCls({
	prefix : 'gantirugiCariSkpd', 
	formName: 'gantirugiCariForm',
});

var gantirugibayar = new DaftarObj2({
	prefix : 'gantirugibayar',
	url : 'pages.php?Pg=gantirugibayar', 
	formName: 'gantirugibayarForm',// 'adminForm'
	withPilih:true,
	elaktiv:'', //id elemen filter yang aktivformName : 'PerencanaanBarang_form',// 'adminForm',// 'ruang_form',
			
		
	Baru:function(){
		var me = this;
		var err='';
		
		if (err =='' ){
		var cover = this.prefix+'_formcover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
		  	url: this.url+'&tipe=formBaru',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				if (resp.err ==''){
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru(resp);	
				}else{
					alert(resp.err);
					delElem(cover);
					document.body.style.overflow='auto';
				}			
				
		  	}
		});
		}else{
		 	alert(err);
		}
	},
	
	Edit:function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,999,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formEdit',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						me.AfterFormEdit(resp);
					}else{
						alert(resp.err);
						delElem(cover);
						document.body.style.overflow='auto';
					}
			  	}
			});
		}else{
			alert(errmsg);
		}
		
	},
	Hapus:function(){
		var me =this;
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Hapus '+jmlcek+' Data ?')){
				//document.body.style.overflow='hidden'; 
				var cover = this.prefix+'_hapuscover';
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=hapus',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');		
						delElem(cover);		
						if(resp.err==''){							
							me.AfterHapus();	
						}else{
							alert(resp.err);
						}							
						
				  	}
				});
				
			}	
		}
	},
	
	Closecari:function(){//alert(this.elCover);
		gantirugi_cari.resetPilih();		
		var cover = this.prefix+'_formcovercari';
		if(document.getElementById(cover)) delElem(cover);								
	},
	
	caritgr:function(){		
			var sw = getBrowserWidth();
		var sh = getBrowserHeight();						
		var idref = document.getElementById(this.prefix+'_idplh').value;
		//var sesiCari = document.getElementById('sesi').value;
		/*var c = document.getElementById('c').value;
		var d = document.getElementById('d').value;
		var e = document.getElementById('e').value;	*/
		var fmstat=1;
		var cover = this.prefix+'_formcovercari';
		addCoverPage2(cover,999,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=formCari&sw='+sw+'&sh='+sh,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if (resp.err ==''){		
					document.getElementById(cover).innerHTML = resp.content;
					//var c = ''; var d=''; var e='';
					document.getElementById('div_detailcari').innerHTML = 
					'<input type=\"hidden\" id=\"gantirugi_cariForm\" name=\"gantirugi_cariForm\" value=\"7\">'+
					'<input type=\"hidden\" id=\"multiSelectNo\" name=\"multiSelectNo\" value=\"1\">'+
					//'<input type=\"hidden\" id=\"fmSKPD\" name=\"fmSKPD\" value=\"'+c+'\">'+
					//'<input type=\"hidden\" id=\"fmUNIT\" name=\"fmUNIT\" value=\"'+d+'\">'+
					//'<input type=\"hidden\" id=\"fmSUBUNIT\" name=\"fmSUBUNIT\" value=\"'+e+'\">'+
					'<input type=\"hidden\" id=\"fmstat\" name=\"fmstat\" value=\"'+fmstat+'\">'+
					'<input type=\"hidden\" id=\"idref\" name=\"idref\" value=\"'+idref+'\">'+
					//'<input type=\"hidden\" id=\"sesicari\" name=\"sesicari\" value=\"'+sesiCari+'\">'+					 
					'<input type=\"hidden\" id=\"boxchecked\" name=\"boxchecked\" value=\"2\">'+
					//'<input type=\"hidden\" id=\"GetSPg\" name=\"GetSPg\" value=\"03\">'+
					'<div id=\"gantirugi_cari_cont_opt\"></div>'+
					'<div id=\"gantirugi_cari_cont_daftar\"></div>'+
					'<div id=\"gantirugi_cari_cont_hal\"><input type=\"hidden\" value=\"1\" id=HalDefault></div>'+
					'';
					//generate data
					gantirugi_cari.getDaftarOpsi();
					gantirugi_cari.refreshList(true);
					document.body.style.overflow='hidden';
					
					//barcodeCariBarang.loading();
				}else{
					alert(resp.err);
					//delElem(cover);
					document.body.style.overflow='auto';
				}
		  	}
		});						
	} ,
	cekbayar:function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		/*this.sendReq(
			this.url,
			{ idprs: 0, daftarProses: new Array('simpan')},
			this.formDialog);*/
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=cekbayar',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					
				}else{
					alert(resp.err);
					//document.getElementById('bayar').value = "";
					document.getElementById('bayar').focus();
				}
		  	}
		});
	}	,
	Pilih:function(){ //pilih cari
		var me = this;
		errmsg = '';		
		if((errmsg=='') && (gantirugi_cari.daftarPilih.length == 0 )){
			errmsg= 'Data belum dipilih!';
		}
		if((errmsg=='') && (gantirugi_cari.daftarPilih.length > 1 )){
			errmsg= 'Pilih 1 data!';
		}
		if(errmsg ==''){	
			//alert('simpan');
			$.ajax({
				type:'POST', 
				data:$('#gantirugiCariForm').serialize(),
				url: this.url +'&tipe=simpanPilih',
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.Closecari();
						//Pemanfaatan.refreshList(true);						
						//Pemanfaatan.resetPilih();
						//fmIDBARANG, fmNMBARANG, noreg, thn_perolehan
						document.getElementById('no_sk').value = resp.content.no_sk;
						//document.getElementById('tgl_sk').value = resp.content.tgl_sk;
						document.getElementById('tgl_sk_thn').value = resp.content.tgl_sk_thn;
						document.getElementById('tgl_sk_tgl').value = resp.content.tgl_sk_tgl;
						document.getElementById('tgl_sk_bln').value = resp.content.tgl_sk_bln;
						document.getElementById('fmIDBARANG').value = resp.content.fmIDBARANG;
						document.getElementById('fmNMBARANG').value = resp.content.fmNMBARANG;
						document.getElementById('noreg').value = resp.content.noreg;
						document.getElementById('tahun').value = resp.content.tahun;
						//document.getElementById('harga_perolehan').value = resp.content.harga_perolehan;
						document.getElementById('harga').value = resp.content.harga;
						document.getElementById('ref_idgantirugi').value = resp.content.ref_idgantirugi;
						document.getElementById('jml_bayar').value = resp.content.jml_bayar;
						document.getElementById('sisa_bayar').value = resp.content.sisa_bayar;
						document.getElementById('idbi').value = resp.content.idbi;
						document.getElementById('kd_skpd').value = resp.content.kd_skpd;
						
								
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
	},
	daftarRender:function(){
		var me =this; //render daftar 
		addCoverPage2(
			'daftar_cover',	1, 	true, true,	{renderTo: this.prefix+'_cont_daftar',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		  	url: this.url+'&tipe=daftar',
		 	type:'POST', 
			data:$('#'+this.formName).serialize(), 
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
				me.sumHalRender();
		  	}
		});
	},
		
	AfterFormEdit:function(){
			this.AfterFormBaru()
	},
		
	
	
});
