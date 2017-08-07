var RekonSkpd = new SkpdCls({
	prefix : 'RekonSkpd', formName:'RekonForm'
});


var Rekon = new DaftarObj2({
	prefix : 'Rekon',
	url : 'pages.php?Pg=rekon', 
	formName : 'RekonForm',// 'ruang_form',
	
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='000') ) err='SUBUNIT belum dipilih!';
		
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaru',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},	
	
	cetakba: function (){	
		//var aForm = document.getElementById('PerjalananDinas_form');		
		var aForm = document.getElementById('BaRekonForm');		
		var err='';
		if(err==''){
			aForm.action= this.url+'&tipe=cetakba';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
			aForm.target='_blank';
			aForm.submit();	
			aForm.target='';
		}else{
			alert(err);
		}		
	},
	
	TambahBarang:function(){		
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();						
		var idref = document.getElementById(this.prefix+'_idplh').value;
		//var sesiCari = document.getElementById('sesi').value;
		var c = document.getElementById('c').value;
		var d = document.getElementById('d').value;
		var e = document.getElementById('e').value;	
		var e1 = document.getElementById('e1').value;	
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
					document.getElementById('div_detailcaribarang').innerHTML = 
					'<input type=\"hidden\" id=\"formcaribi\" name=\"formcaribi\" value=\"8\">'+
					'<input type=\"hidden\" id=\"multiSelectNo\" name=\"multiSelectNo\" value=\"1\">'+
					'<input type=\"hidden\" id=\"fmSKPD\" name=\"fmSKPD\" value=\"'+c+'\">'+
					'<input type=\"hidden\" id=\"fmUNIT\" name=\"fmUNIT\" value=\"'+d+'\">'+
					'<input type=\"hidden\" id=\"fmSUBUNIT\" name=\"fmSUBUNIT\" value=\"'+e+'\">'+
					'<input type=\"hidden\" id=\"fmSEKSI\" name=\"fmSEKSI\" value=\"'+e1+'\">'+
					'<input type=\"hidden\" id=\"idref\" name=\"idref\" value=\"'+idref+'\">'+
					//'<input type=\"hidden\" id=\"sesicari\" name=\"sesicari\" value=\"'+sesiCari+'\">'+					 
					'<input type=\"hidden\" id=\"boxchecked\" name=\"boxchecked\" value=\"2\">'+
					//'<input type=\"hidden\" id=\"GetSPg\" name=\"GetSPg\" value=\"03\">'+
					//'<input type=\"hidden\" id=\"SPg\" name=\"SPg\" value=\"03\">'+					
					'<div id=\"penatausaha_cont_opt\"></div>'+
					'<div id=\"penatausaha_cont_list\"></div>'+
					'<div id=\"penatausaha_cont_hal\"><input type=\"hidden\" value=\"1\" id=HalDefault></div>'+
					'';
					//generate data
					Penatausaha.getDaftarOpsi();
					Penatausaha.resetPilih();
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
	
	CloseCariBarang:function(){//alert(this.elCover);
		Penatausaha.resetPilih();		
		var cover = this.prefix+'_formcovercari';
		if(document.getElementById(cover)) delElem(cover);								
	},
	
	
	PilihBarang:function(){ //pilih cari
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
				url: this.url +'&tipe=getdata',
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.CloseCariBarang();
						//Penggunaan_Det.refreshList(true);
						document.getElementById('idbi').value = resp.content.idbi;
						document.getElementById('idbi_awal').value = resp.content.idbi_awal;
						document.getElementById('kd_barang').value = resp.content.kd_barang;
						document.getElementById('nm_barang').value = resp.content.nm_barang;
						document.getElementById('thn_perolehan').value = resp.content.thn_perolehan;
						document.getElementById('noreg').value = resp.content.noreg;					
						document.getElementById('harga').value = resp.content.jml_harga;					
						document.getElementById('a1').value = resp.content.a1;		
						document.getElementById('a').value = resp.content.a;		
						document.getElementById('b').value = resp.content.b;		
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
	
	Simpan: function(){
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
					alert('Data berhasil disimpan');
					me.Close();
					me.refreshList(true);
					me.AfterSimpan();	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	},	
	
	Edit : function(){
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
	
	
	formatRupiah:function(objek, separator) {
	  a = objek.value;
	  b = a.replace(/[^\d]/g,"");
	  c = "";
	  panjang = b.length;
	  j = 0;
	  for (i = panjang; i > 0; i--) {
	    j = j + 1;
	    if (((j % 3) == 1) && (j != 1)) {
	      c = b.substr(i-1,1) + separator + c;
	    } else {
	      c = b.substr(i-1,1) + c;
	    }
	  }
	  objek.value = c;
   },	
   
   Surat : function(){
   		var me =this;
		var err='';
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		//if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		//if(err=='' && (seksi=='' || seksi=='00' || seksi=='000' ) ) err='SUB UNIT belum dipilih!';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: 'pages.php?Pg=barekon&tipe=setSurat',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;
					me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	Lampiran2: function(){		
		var aForm = document.getElementById(this.formName);		
		aForm.action= 'pages.php?Pg=rekonlampiran2&tipe=cetaklampiran';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	},
	Lampiran1: function(){		
		var aForm = document.getElementById(this.formName);		
		aForm.action= 'pages.php?Pg=rekonlampiran1&tipe=cetaklampiran';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	},
	Perhitungan: function(){		
		var aForm = document.getElementById(this.formName);		
		aForm.action= 'pages.php?Pg=rekonhitung&tipe=cetakhitung';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
	

});
