var gantirugiSkpd = new SkpdCls({
	prefix : 'gantirugiSkpd', 
	formName: 'gantirugiForm',
});

var gantirugiCariSkpd = new SkpdCls({
	prefix : 'gantirugiCariSkpd', 
	formName: 'adminForm',
});

var gantirugi = new DaftarObj2({
	prefix : 'gantirugi',
	url : 'pages.php?Pg=gantirugi', 
	formName: 'gantirugiForm',// 'adminForm'
	daftarPilih:new Array(),
	withPilih:true,
	elaktiv:'', //id elemen filter yang aktivformName : 'PerencanaanBarang_form',// 'adminForm',// 'ruang_form',
			
	
	Baru:function(){
		var me = this;
		var err='';
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		
		//if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		//if(err=='' && (unit=='' || unit=='00') ) err='ASISTEN/OPD belum dipilih!';
		//if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		//if(err=='' && (subunit=='' || subunit=='00') ) err='BIRO/ UPTD/B belum dipilih!';
		//if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		
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
	Pilih:function(){ //pilih cari
		var me = this;
		errmsg = '';		
		if((errmsg=='') && (Penatausaha.daftarPilih.length == 0 )){
			errmsg= 'Data belum dipilih!';
		}
		if((errmsg=='') && (Penatausaha.daftarPilih.length > 1 )){
			errmsg= 'Pilih 1 data!';
		}
		if(errmsg ==''){	
			//alert('simpan');
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: this.url +'&tipe=simpanPilih',
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.Closecari();
						//Pemanfaatan.refreshList(true);						
						//Pemanfaatan.resetPilih();
						//fmIDBARANG, fmNMBARANG, noreg, thn_perolehan
						document.getElementById('fmIDBARANG').value = resp.content.fmIDBARANG;
						document.getElementById('fmNMBARANG').value = resp.content.fmNMBARANG;
						document.getElementById('noreg').value = resp.content.noreg;
						document.getElementById('tahun').value = resp.content.tahun;
						document.getElementById('harga_perolehan').value = resp.content.harga_perolehan;
						//document.getElementById('tgl_gantirugi').value = resp.content.tgl_gantirugi;
						document.getElementById('idbi').value = resp.content.idbi;
						document.getElementById('c').value = resp.content.c;
						document.getElementById('d').value = resp.content.d;
						document.getElementById('e').value = resp.content.e;
						document.getElementById('e1').value = resp.content.e1;
						
						
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
	},
	
	KetetapanTanpaUsulan:function(){
		var me = this;
		var err='';
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		
		if (err =='' ){
		var cover = this.prefix+'_formcover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=formKetetapanTanpaUsulan',
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
	
	BatalKetetapan:function(){
		var me =this;
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else if(jmlcek >1){
			alert('Pilih Satu Data!');
		}else{
			if(confirm('Batalkan ketetapan dari '+jmlcek+' Data ?')){
				//document.body.style.overflow='hidden'; 
				var cover = this.prefix+'_hapuscover';
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=BatalKetetapan',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');		
						delElem(cover);		
						if(resp.err==''){
							alert('Batal Ketetapan Sukses!')							
							me.AfterSimpan();	
						}else{
							alert(resp.err);
						}							
						
				  	}
				});
				
			}	
		}
	},
	
	Closecari:function(){//alert(this.elCover);
		Penatausaha.resetPilih();		
		var cover = this.prefix+'_formcovercari';
		if(document.getElementById(cover)) delElem(cover);								
	},
	
	getDaftarOpsi:function(){
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: 'pages.php?Pg=gantirugi',
		  	success: function(data) {
				var resp = eval('(' + data + ')');	
				if (resp.ErrMsg =='')	{	
					document.getElementById('gantirugi_cont_opt').innerHTML = resp.content;
				}else{
					alert(resp.ErrMsg);					
					
				}
			}
		});
	},
	filterRender:function(){
		var me=this;
		//render filter
		$.ajax({
		  url: this.url+'&tipe=filter',
		  type:'POST', 
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_opsi').innerHTML = resp.content;
			me.filterRenderAfter()
		  }
		});
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
				if(me.withPilih) me.cbTampil();				
				me.sumHalRender();
		  	}
		});
	},
	caribarang1:function(jns_usul){		
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();						
		var idref = document.getElementById(this.prefix+'_idplh').value;
		//var sesiCari = document.getElementById('sesi').value;
		/*var c = document.getElementById('c').value;
		var d = document.getElementById('d').value;
		var e = document.getElementById('e').value;
		*/
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		if(jns_usul==1){//usulan baru/edit
			var tgl = document.getElementById('tgl_usul').value;
			
		}else{
			var tgl = document.getElementById('tgl_sk').value;
			
		}
		var cover = this.prefix+'_formcovercari';
		addCoverPage2(cover,999,true,false);
		$.ajax({
			type:'POST', 
			//data:$('#'+this.formName).serialize(),
			data:$('#adminform').serialize(),
		  	url: this.url+'&tipe=formCari&sw='+sw+'&sh='+sh+'&tgl='+tgl,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if (resp.err ==''){		
					document.getElementById(cover).innerHTML = resp.content;
					document.getElementById('div_detailcari').innerHTML = 
					"<input type='hidden' id='formcaribi' name='formcaribi' value='gantirugi_cari'>"+
					"<input type='hidden' id='fmSKPD' name='fmSKPD' value='"+skpd+"'>"+
					"<input type='hidden' id='fmUNIT' name='fmUNIT' value='"+unit+"'>"+
					"<input type='hidden' id='fmSUBUNIT' name='fmSUBUNIT' value='"+subunit+"'>"+
					 
					"<input type='hidden' id='idref' name='idref' value='"+idref+"'>"+
					//"<input type='hidden' id='sesicari' name='sesicari' value='"+sesiCari+"'>"+					 
					"<input type='hidden' id='boxchecked' name='boxchecked' value='2'>"+
					"<input type='hidden' id='GetSPg' name='GetSPg' value='03'>"+
					"<div id='penatausaha_cont_opt'></div>"+
					"<div id='penatausaha_cont_list'></div>"+
					"<div id='penatausaha_cont_hal'><input type='hidden' value='1' id=HalDefault></div>"+
					"";
					//generate data
					Penatausaha.getDaftarOpsi();
					Penatausaha.refreshList(true);
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
	
	Ketetapan:function(){
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
				url: this.url+'&tipe=formKetetapan',
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
	
	simpan1:function(){
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
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					alert('Data Berhasil disimpan');
					me.Close();
					me.AfterSimpan();
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	simpan2:function(){
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
			url: this.url+'&tipe=simpanSK',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					alert('Data SK Berhasil disimpan');
					me.Close();
					me.AfterSimpan();
				}else{
					alert(resp.err);
				}
		  	}
		});
	},	
	
	simpan3:function(){
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
			url: this.url+'&tipe=simpanSKTU',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					alert('Data Ketetapan Tanpa Usulan Berhasil disimpan');
					me.Close();
					me.AfterSimpan();
				}else{
					alert(resp.err);
				}
		  	}
		});
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
			if(confirm('Batalkan usulan '+jmlcek+' Data ?')){
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
	
	AfterHapus : function(){
		var me =this;
		alert('Sukses Batalkan Usulan');
		me.refreshList(true);
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
