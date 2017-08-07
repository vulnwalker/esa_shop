var pangkatSkpd = new SkpdCls({
	prefix : 'pangkatSkpd', formName:'pangkatForm', kolomWidth:120
	
});

var pangkat = new DaftarObj2({
	prefix : 'pangkat',
	url : 'pages.php?Pg=pangkat', 
	formName : 'pangkatForm',
	
	pilihKelompok : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihKelompok',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_object').innerHTML = resp.content.unit;
			barang.daftarRender();
		  }
		});
		
	},
	
	pilihKelompok2 : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihKelompok2',
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_object2').innerHTML = resp.content.unit;
		  }
		});
	},
	
	pilihObjek : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihObjek',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_jenis').innerHTML = resp.content.jenis;
			barang.daftarRender();
		  }
		});
	},
	
	pilihObjek2 : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihObjek2',
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_jenis2').innerHTML = resp.content.unit;
		  }
		});
	},
	
	pilihJenis : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihJenis',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_NmBrg').innerHTML = resp.content.unit;
			barang.daftarRender();
		  }
		});
	},
	
	pilihNmBrg: function(){
	var me = this; 
		barang.daftarRender();
	},
	
	pilihJenis2 : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihJenis2',
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('j').value = resp.content.j;
		  }
		 
		});
	},
	
	pilihSatuanBesar : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihSatuanBesar',
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_satuan_besar').innerHTML = resp.content.unit;
		  }
		});
	},
	
	pilihSatuanBesar1 : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihSatuanBesar1',
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_satuan_besar').innerHTML = resp.content.unit;
		  }
		});
	},
	
	pilihSatuanKecil1 : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihSatuanKecil1',
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_satuan_kecil').innerHTML = resp.content.unit;
		  }
		});
	},
	
	pilihSatuanKecil : function(){
	var me = this; 
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=pilihSatuanKecil',
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_satuan_kecil').innerHTML = resp.content.unit;
		  }
		});
	},
	
	
	
	
	refreshJenis : function(id_jenisBaru){
	var me = this; //alert('tes');	//alert(this.prefix);
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=refreshJenis&id_jenisBaru='+id_jenisBaru,
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_jenis2').innerHTML = resp.content.unit;
			me.getKodeBarang2();
		  }
		});
	},
	
	refreshSatuanBesar : function(SatuanBesarBaru){
	var me = this; //alert('tes');	//alert(this.prefix);
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=refreshSatuanBesar&SatuanBesarBaru='+SatuanBesarBaru,
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_satuan_besar').innerHTML = resp.content.unit;
		  }
		});
	
	},
	
	refreshSatuanBesar1 : function(SatuanBesarBaru1){
	var me = this; //alert('tes');	//alert(this.prefix);
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=refreshSatuanBesar1&SatuanBesarBaru1='+SatuanBesarBaru1,
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_satuan_besar1').innerHTML = resp.content.unit;
		  }
		});
	},
	
	refreshSatuanKecil : function(SatuanKecilBaru){
	var me = this; //alert('tes');	//alert(this.prefix);
		
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=refreshSatuanKecil&SatuanKecilBaru='+SatuanKecilBaru,
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_satuan_kecil').innerHTML = resp.content.unit;
		  }
		});
	},
	
	refreshSatuanKecil1 : function(SatuanKecilBaru1){
	var me = this; //alert('tes');	//alert(this.prefix);
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=refreshSatuanKecil1&SatuanKecilBaru1='+SatuanKecilBaru1,
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('cont_satuan_kecil1').innerHTML = resp.content.unit;
		  }
		});
	},
	
	
	getKodeBarang : function(){
	var me = this; //alert('tes');	//alert(this.prefix);
		
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=getKodeBarang',
		  type : 'POST',
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('j').value = resp.content.j;
		  }
		});
	
	},
	
	getKodeBarang2 : function(){
	var me = this; //alert('tes');	//alert(this.prefix);
		
		$.ajax({
		  url: 'pages.php?Pg=barang&tipe=getKodeBarang2',
		  type : 'POST',
		  //data:$('#adminForm').serialize(),
		  data:$('#barang_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById('j').value = resp.content.j;
		  }
		});
	
	},
	
	loading: function(){
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		this.sumHalRender();
	},
	
	detail: function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();			
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
	
	Baru: function(){	
		var me = this;
		var err='';
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
	
	
	BaruJenis: function(){	
		var me = this;
		var err='';
		var kelompok =document.getElementById('fmKelompok2').value;
		var objek =document.getElementById('fmObjek2').value;
		if (kelompok=='' || objek==''){
			alert('kelompok / objek belum terpilih !');
		}else{
		if (err =='' ){		
			var cover = this.prefix+'_formcoverjenis';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#barang_form').serialize(),
			  	url: this.url+'&tipe=formBaruJenis',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					//me.AfterFormBaru();
			  	}
			});
		}else{
		 	alert(err);
		}	
		}
	},		
	
	
	BaruSatuanBesar: function(){	
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcoverSatuanBesar';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#barang_form').serialize(),
			  	url: this.url+'&tipe=formBaruSatuanBesar',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					
			  	}
			});
		}else{
		 	alert(err);
		}
	},	
	
	BaruSatuanBesar1: function(){	
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcoverSatuanBesar1';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#barang_form').serialize(),
			  	url: this.url+'&tipe=formBaruSatuanBesar1',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					
			  	}
			});
		}else{
		 	alert(err);
		}
	},		
	
	BaruSatuanKecil: function(){	
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcoverSatuanKecil';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#barang_form').serialize(),
			  	url: this.url+'&tipe=formBaruSatuanKecil',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					
			  	}
			});
		}else{
		 	alert(err);
		}
	},		
	
	BaruSatuanKecil1: function(){	
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcoverSatuanKecil1';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#barang_form').serialize(),
			  	url: this.url+'&tipe=formBaruSatuanKecil1',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					
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
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,1,true,false);	
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
		
	initial:function(){
		document.getElementById("tampil_barang").innerHTML ="<input type='button' value='Cari' onClick='barang.Cari()'>"+
		"<input type='text' name='el_id_barang_temp' id='el_id_barang_temp'>"+
		"<input type='text' name='el_nm_barang_temp' id='el_nm_barang_temp'>";
	},
	
	Cari: function(j){
		var me = this;
		
		me.el_id_barang_temp = 'el_id_barang_temp';
		me.el_nm_barang_temp = 'el_nm_barang_temp';
		me.windowSaveAfter= function(){};
		me.windowShow(j);	
	},
	
	windowShow: function(j){
		var me = this;
		var cover = this.prefix+'_cover';
		
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow&status_filter=1',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if(resp.err==''){
					document.getElementById(cover).innerHTML = resp.content;				
					me.loading();
				}else{
					alert(resp.err);
					delElem(cover);
					document.body.style.overflow='auto';
				}
		  	}
		});
	},
	
	windowClose: function(){
		document.body.style.overflow='auto';
		delElem(this.prefix+'_cover');
	},
	
	windowSave: function(){
		var me= this;
		var errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			this.idpilih = box.value;			
			
			var cover = 'barang_getdata';
			addCoverPage2(cover,1,true,false);				
			$.ajax({
				url: 'pages.php?Pg=barang&tipe=getdata&id='+this.idpilih+'&c='+c+'&d='+d+'&e='+e+'&e1='+e1,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
					document.getElementById('jml').value='';
						document.getElementById('harga_satuan').value='';
						document.getElementById('jumlah_harga').value='';
						document.getElementById('keterangan').value='';
						document.getElementById('jmlSatkkcl').value='';
						document.getElementById('jml_satuan').value='';
						if(document.getElementById('namabrg'))document.getElementById('namabrg').value=resp.content.nama;
						if(document.getElementById('kodebrg'))document.getElementById('kodebrg').value=resp.content.kodebarang;
						if(document.getElementById('f2'))document.getElementById('f2').value=resp.content.f;
						if(document.getElementById('g2'))document.getElementById('g2').value=resp.content.g;
						if(document.getElementById('h2'))document.getElementById('h2').value=resp.content.h;
						if(document.getElementById('i2'))document.getElementById('i2').value=resp.content.i;
						if(document.getElementById('j2'))document.getElementById('j2').value=resp.content.j;
						if(document.getElementById('k11'))document.getElementById('k11').value=resp.content.k1;
						if(document.getElementById('k21'))document.getElementById('k21').value=resp.content.k2;
						if(document.getElementById('k31'))document.getElementById('k31').value=resp.content.k3;
						if(document.getElementById('k41'))document.getElementById('k41').value=resp.content.k4;
						if(document.getElementById('k51'))document.getElementById('k51').value=resp.content.k5;
						if(document.getElementById('k61'))document.getElementById('k61').value=resp.content.k6;
						if(document.getElementById('kdbarbsr'))document.getElementById('kdbarbsr').value=resp.content.barcode_terbesar;
						if(document.getElementById('jumlahKonversi'))document.getElementById('jumlahKonversi').value=resp.content.jml_barang_konversi;
						if(document.getElementById('StKcl'))document.getElementById('StKcl').value=resp.content.satuan_terkecil;
						if(document.getElementById('StBsr'))document.getElementById('StBsr').value=resp.content.satuan_terbesar;
						satuan_terbesar=resp.content.satuan_terbesar;
						satuan_terkecil=resp.content.satuan_terkecil;
						document.getElementById('jml').value='';
						document.getElementById('harga_satuan').value='';
						document.getElementById('jumlah_harga').value='';
						document.getElementById('keterangan').value='';
						document.getElementById('jmlSatkkcl').value='';
						document.getElementById('jml_satuan').value='';
						DaftarBarangDetail.pilihSatuanBarang(satuan_terbesar,satuan_terkecil);
					
						me.windowClose();
						me.windowSaveAfter();
					}else{
						alert(resp.err)	
					}
			  	}
			});		
		}else{
			alert(errmsg);
		}
	},
	
	windowSaveAfter: function(){
		alert('tes2');	
	},
	
	refreshComboJenis:function(){
		var formName = document.getElementById('barang_form');
		var err='';
		if(err==''){
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=refreshComboJenis',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById('ref_idjenis').innerHTML = resp.content;
			  	}
			});			
		}else{
			alert(err);
		}	
	},
	
	Tambah_Id_barang:function(){
		var formName = document.getElementById('barang_form');
		var err='';
		if(err==''){
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=Tambah_Id_barang',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById('j').value = resp.content;
			  	}
			});			
		}else{
			alert(err);
		}	
	},
	
	refreshComboSatuan:function(){
		var formName = document.getElementById('barang_form');
		var err='';
		if(err==''){
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=refreshComboSatuan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById('ref_idsatuan').innerHTML = resp.content;
			  	}
			});			
		}else{
			alert(err);
		}		
	},
	
	/*Close:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcover';
		if(document.getElementById(cover)) delElem(cover);			
		if(tipe==null){
			document.body.style.overflow='auto';						
		}
	},
	*/
	Close2:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcoverjenis';
		if(document.getElementById(cover)) delElem(cover);			
		if(tipe==null){
			document.body.style.overflow='auto';						
		}
	},
	
	Close3:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcoverSatuanBesar';
		if(document.getElementById(cover)) delElem(cover);			
		if(tipe==null){
			document.body.style.overflow='auto';						
		}
	},
	
	Close4:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcoverSatuanKecil';
		if(document.getElementById(cover)) delElem(cover);			
		if(tipe==null){
			document.body.style.overflow='auto';						
		}
	},
	
	Close5:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcoverSatuanBesar1';
		if(document.getElementById(cover)) delElem(cover);			
		if(tipe==null){
			document.body.style.overflow='auto';						
		}
		
	},
	
	Close6:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcoverSatuanKecil1';
		if(document.getElementById(cover)) delElem(cover);			
		if(tipe==null){
			document.body.style.overflow='auto';						
		}
		
	},
	
	Simpan: function(){
		var me= this;
		
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				
				if(resp.err==''){
					me.Close();
					me.AfterSimpan();
				}else{
					alert(resp.err);
				}
		  	}
		});
		
	},	
	
	
	SimpanEdit: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan1';
		addCoverPage2(cover,1,true,false);	
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_formEdit').serialize(),
			url: this.url+'&tipe=simpanEdit',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				
				if(resp.err==''){
					me.Close();
					me.daftarRender();
				}else{
					alert(resp.err);
				}
		  	}
		});
	},	
	
	SimpanJenis: function(){
		var me= this;
		var err='';
		
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpanJenis';
		addCoverPage2(cover,1,true,false);	
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_jenisform').serialize(),
			url: this.url+'&tipe=simpanJenis',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				
				if(resp.err==''){
					me.refreshJenis(resp.content);
					me.Close2();
				}else{
					alert(resp.err);
				}
		  	}
		});
		
	},
	
	SimpanJenisLvl4: function(){
		var me= this;
		var err='';
		
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpanJenis';
		addCoverPage2(cover,1,true,false);	
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_jenisform').serialize(),
			url: this.url+'&tipe=simpanJenisLvl4',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				
				if(resp.err==''){
					me.Close();
					me.daftarRender();
				}else{
					alert(resp.err);
				}
		  	}
		});
		
	},
	
	SimpanSatuanBesar: function(){
		var me= this;
		var err='';
		var nama =document.getElementById('nama').value;
		
		/*if (nama=='' ){
			alert('Data Satuan Besar Belum terisi !');
		}else*/{
			
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpanSatuanBesar';
		addCoverPage2(cover,1,true,false);	
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_formSatuanBesar').serialize(),
			url: this.url+'&tipe=simpanSatuanBesar',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				
				if(resp.err==''){
					me.refreshSatuanBesar(resp.content);
					me.Close3();
				}else{
					alert(resp.err);
				}
		  	}
		});
		}
	},
	SimpanSatuanBesar1: function(){
		var me= this;
		var err='';
		var nama =document.getElementById('nama').value;
		
		if (nama=='' ){
			alert('Data belum terisi !');
		}else{
			
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpanSatuanBesar1';
		addCoverPage2(cover,1,true,false);	
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_formSatuanBesar1').serialize(),
			url: this.url+'&tipe=simpanSatuanBesar1',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				
				if(resp.err==''){
					me.refreshSatuanBesar1(resp.content);
					me.Close5();
				}else{
					alert(resp.err);
				}
		  	}
		});
		}
	},
	
	
	SimpanSatuanKecil: function(){
		var me= this;
		var err='';
		var nama =document.getElementById('nama').value;
		
		if (nama=='' ){
			alert('Maaf Data kelompok | Data Objek | Data Jenis | Data Satuan Besar Belum terisi !');
		}else{
			
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpanSatuanKecil';
		addCoverPage2(cover,1,true,false);	
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_formSatuanKecil').serialize(),
			url: this.url+'&tipe=simpanSatuanKecil',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
					me.refreshSatuanKecil(resp.content);
					me.Close4();
				}else{
					alert(resp.err);
				}
		  	}
		});
		}
	},
	
	SimpanSatuanKecil1: function(){
		var me= this;
		var err='';
		var nama =document.getElementById('nama').value;
		
		if (nama=='' ){
			alert('Maaf Data kelompok | Data Objek | Data Jenis | Data Satuan Besar Belum terisi !');
		}else{
			
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpanSatuanKecil1';
		addCoverPage2(cover,1,true,false);	
	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_formSatuanKecil1').serialize(),
			url: this.url+'&tipe=simpanSatuanKecil1',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
					me.refreshSatuanKecil1(resp.content);
					me.Close6();	
				}else{
					alert(resp.err);
				}
		  	}
		});
		}
	},	
});