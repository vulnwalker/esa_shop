var PersediaanBarang = new DaftarObj2({
	prefix : 'PersediaanBarang',
	url : 'pages.php?Pg=persediaanbarang', 
	formName : 'PersediaanBarang_Form',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		this.sumHalRender();
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
	
	
	CariBarang: function(){	
			
			var cover = this.prefix+'_formcover1';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formCariBarang',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
  						document.getElementById(cover).innerHTML = resp.content;
						document.getElementById('daftarpersediaanbarang').innerHTML = 
						//alert("tes");
						"<div id='CariBarang_cont_title' style='position:relative'></div>"+
						"<div id='CariBarang_cont_opsi' style='position:relative'>"+
						//"<input type='hidden' name='lokasi' id='lokasi' value='"+lokasi+"'>"+
						"</div>"+
						"<div id='CariBarang_cont_daftar' style='position:relative'></div>"+
						"<div id='CariBarang_cont_hal' style='position:relative'></div>";
						
						CariBarang.resetPilih()	//reset checkbox
						//refresh data
						CariBarang.refreshList(true)
					}else{
						 alert(resp.err);
						//delElem(cover);
						document.body.style.overflow='auto';
					}
			  	}
			});
		
	},

	PilihBarang:function(){		
			
		document.getElementById(this.prefix+'_daftarpilih').value= CariBarang.daftarPilih  //ambil daftar pilih
   		var Pilih = document.getElementById(this.prefix+'_daftarpilih').value
		//var id_jual = document.getElementById('id_jual').value
		
		errmsg = this.CekCheckbox2();
		if(errmsg =='')
		{ 
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_cover';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=pilihbarang',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
					//alert(pilih)
					me.Closecaribarang();
					//FarmasiJualDetail.refreshList(true)
					document.getElementById('nama_barang').value=resp.content.nama_barang;
					document.getElementById('kode_barang').value=resp.content.kode_barang;
					
				}else{
					alert(resp.err);
				}
		  	}
		 });
		
		}
		else{
			alert(errmsg)
		}
		
	},	
	
	Closecaribarang:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcover1';
		if(document.getElementById(cover)) delElem(cover);			
		document.body.style.overflow='auto';					
	},	
	
	Baru:function(){
		var me = this;
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
	},
	
	HargaBarang: function(){
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
				url: this.url+'&tipe=formHargaBarang',
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
	
	CekCheckbox2:function(){//alert(this.elJmlCek);
		var errmsg = '';		
		//alert(document.getElementById(this.prefix+'_jmlcek').value );
		//if( document.getElementById(this.elJmlCek)){
		if( document.getElementById('CariBarang'+'_jmlcek')){
			if((errmsg=='')  && (document.getElementById('CariBarang'+'_jmlcek').value >1 )){	errmsg= 'Pilih Hanya Satu Barang!'; }
		}
		if((errmsg=='') && ( (document.getElementById('CariBarang'+'_jmlcek').value == 0)||(document.getElementById('CariBarang'+'_jmlcek').value == '')  )){
			errmsg= 'Barang belum dipilih!';
		}
		return errmsg;
	},
	
	GetCbxChecked2:function(){
		var jmldata= document.getElementById( 'CariBarang'+'_jmldatapage' ).value;
		for(var i=0; i < jmldata; i++){
			var box = document.getElementById( 'CariBarang'+'_cb' + i);
			if( box.checked){ 
				break;
			}
		}
		return box;			
	},
	
	getdata: function(){	
		
		var me = this;
		var no_rm = document.getElementById('no_rm').value; 
		//var kota = document.getElementById('kota').value;
			
			if(no_rm.length < 6) {
				alert('No RM harus 6 digit atau lebih!');
				Kunjungan.refreshList(true)	
				}
			else{
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:"no_rm="+no_rm,//$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=getdata',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if(resp.err==''){		
					document.getElementById('nama_pasien').value = resp.content.nama_pasien;
					document.getElementById('jk').value = resp.content.jk;	
					document.getElementById('darah').value = resp.content.darah;
					document.getElementById('tempat_lahir').value = resp.content.tempat_lahir;	
					document.getElementById('tgl_lahir_tgl').value=parseInt(resp.content.tgl_lahir.substr(8,2));
					document.getElementById('tgl_lahir_bln').value=resp.content.tgl_lahir.substr(5,2);
					document.getElementById('tgl_lahir_thn').value=resp.content.tgl_lahir.substr(0,4);
					document.getElementById('tgl_lahir').disable=true;		
					document.getElementById('alamat').value = resp.content.alamat;
					document.getElementById('rt').value = resp.content.rt;
					document.getElementById('rw').value = resp.content.rw;
					document.getElementById('pos').value = resp.content.pos;
					document.getElementById('umur_thn').value = resp.content.umur_thn;
					document.getElementById('umur_bln').value = resp.content.umur_bln;
					document.getElementById('umur_hari').value = resp.content.umur_hari;
					document.getElementById('kelurahan').value = resp.content.kelurahan;
					document.getElementById('div_kecamatan').innerHTML = resp.content.div_kecamatan;
					document.getElementById('KunjunganKota').value = resp.content.kota;
					document.getElementById('agama').value = resp.content.agama;
					document.getElementById('pekerjaan').value = resp.content.pekerjaan;
					document.getElementById('pendidikan').value = resp.content.pendidikan;
					document.getElementById('status').value = resp.content.status;
					document.getElementById('stkawin').value = resp.content.stkawin;
					document.getElementById('bangsa').value = resp.content.bangsa;
					document.getElementById('nama_ayah').value = resp.content.nama_ayah;
					document.getElementById('no_ktp').value = resp.content.no_ktp;
					document.getElementById('hp').value = resp.content.hp;		
					document.getElementById('kecamatan').disabled = true;
					}else{
					alert(resp.err);
				}
					}
			});
			}	
		},
		
	AfterFormBaru2 : function(){
		//if(document.getElementById('Kunjungan_cont_daftar')){
		Pasien.refreshList(true);//}
		Pasien.loading();
	},		

	Baru2: function(){	
		
		var me = this;
		var err='';
		errmsg = this.CekCheckbox2();
		if(errmsg ==''){ 
			var box = alert(this.GetCbxChecked2());
		
		
		//if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+'PasienForm').serialize(),
			  	url: this.url+'&tipe=formBaru2',
			  	success: function(data) {
				var resp = eval('(' + data + ')');	
				if(resp.err==''){					
					document.getElementById(cover).innerHTML = resp.content;
					document.getElementById('jk').disabled = true;
					document.getElementById('darah').disabled = true;
					document.getElementById('KunjunganKota').disabled = true;
					document.getElementById('agama').disabled = true;
					document.getElementById('pekerjaan').disabled = true;
					document.getElementById('pendidikan').disabled = true;
					document.getElementById('status').disabled = true;	
					document.getElementById('stkawin').disabled = true;
					document.getElementById('div_kecamatan').disabled = true;					
					me.AfterFormBaru();
					if(no_rm!=''){				
						me.getdata();						
					}					
					me.kunjungan();								
					me.autocomplete_initial();
					me.autocomplete_initial2();
					me.autocomplete_initial3();
					me.autocomplete_initial4();
					me.cara_bayar();
				}else{
		 			alert(resp.err);
				document.body.style.overflow='auto';
				delElem(cover);	
			}	
			  }
			});
		}else{
			alert(errmsg);
		}
	},
	
	/*FormBatal:function(){	
		var me = this;
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
		if(jmlcek ==0){
		alert('Data Belum Dipilih!');
		}else{
			if(confirm('Anda ingin membatalkan data ini ?')){
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBatal',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
					}else{
						alert(resp.err);
						delElem(cover);
						document.body.style.overflow='auto';
					}//me.AfterFormBaru();;
			  	}
			});}}
		}else{
			alert(errmsg);
		}
 	}, 
	
	AfterBatal : function(){
		if(document.getElementById('Kunjungan_cont_daftar')){
		this.refreshList(false);}
	},
	
	Batal: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formbatal';
		addCoverPage2(cover,1,true,false);	
		/*this.sendReq(
			this.url,
			{ idprs: 0, daftarProses: new Array('simpan')},
			this.formDialog);
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=batal',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					alert('Data berhasil dibatalkan');
					me.Close();
					me.AfterSimpan();	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	},*/
	
	CekBatal:function(){
		var me = this;
		var err = '';
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		errmsg = this.CekCheckbox();
		
		if(errmsg ==''){ 
		
			var box = this.GetCbxChecked();
			var idp = box.value;
			if(jmlcek ==0){
				alert('Data Belum Dipilih!');
			}else{
				if(confirm('Anda ingin membatalkan data ini ?')){
					//this.Show ('formedit',{idplh:box.value}, false, true);			
					var cover = this.prefix+'_formcover';
					document.body.style.overflow='hidden';
					addCoverPage2(cover,1,true,false);	
					$.ajax({
						type:'POST', 
						data:$('#'+this.formName).serialize(),
					  	url: this.url+'&tipe=CekBatal&Id='+idp,
					  	success: function(data) {		
							var resp = eval('(' + data + ')');			
							if (resp.err ==''){	
								me.FormBatal();
								//document.getElementById(cover).innerHTML = resp.content;
							}else{
								if(resp.content=='0'){
									me.LoginAdmin();
								}else{
									alert(resp.err);
									delElem(cover);
									document.body.style.overflow='auto';
								}
							}//me.AfterFormBaru();;
					  	}
					});
				}
			}
		}else{
			alert(errmsg);
		}
	},
	
	LoginAdmin: function(){	
		
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			//document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formAdmin',
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
	
	CekAdmin:function(){	
		var me = this;
		var username = document.getElementById('username').value;
		var password = document.getElementById('password').value;
		
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			//document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=CekAdmin&username='+username+'&password='+password,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					//document.getElementById(cover).innerHTML = resp.content;
					var username=resp.content;
					if(resp.err==''){
						me.FormBatal(username);	
					}else{
						alert(resp.err);
					}
			  	}
			});
 	},
	
	FormBatal: function(username){	
		
		var me = this;
		var err='';
		
		if(username==null)	username='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			//document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBatal&username='+username,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
					}else{
						alert(resp.err);
						delElem(cover);
						document.body.style.overflow='auto';
					}//me.AfterFormBaru();		
					//me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	
	Batal: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formbatal';
		addCoverPage2(cover,1,true,false);	
		/*this.sendReq(
			this.url,
			{ idprs: 0, daftarProses: new Array('simpan')},
			this.formDialog);*/
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=batal',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					alert('Data berhasil dibatalkan');
					me.Close();
					me.refreshList(true);	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	},
	

	tambah: function(){
		
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
			url: this.url+'&tipe=tambah',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					//Kunjungan.refreshList(true)
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	tambahruang: function(){
		
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
			url: this.url+'&tipe=tambahruang',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					//Kunjungan.refreshList(true)
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	tambahpenjamin: function(){
		
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
			url: this.url+'&tipe=tambahpenjamin',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					//Kunjungan.refreshList(true)
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	tambahdokter: function(){
		
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
			url: this.url+'&tipe=tambahdokter',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					//Kunjungan.refreshList(true)
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	

	
	/*Bayar:function(){
		var me=this;
		var konfirmasi = confirm("Mau langsung Bayar?");
		if(konfirmasi){
			me.refreshList(true);
			//alert("cetak !");
		}else{
			me.refreshList(true);
		}
	},*/
	/*resetpenjamin: function(){	
		document.getElementById('autocomplete2').reset();
	},*/
	
	AfterSimpan : function(){
		if(document.getElementById('Kunjungan_cont_daftar')){
		this.refreshList(true);}
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
					//with_bayar=document.getElementById('with_bayar').value;
					me.Close();
					me.refreshList(true);
					//me.Bayar();
					//alert(resp.content.kunjungan+' '+resp.content.id_kunjungan)
					//if(with_bayar==1){
					//confirm("Mau langsung ?")
						//if(confirm("Mau langsung ?")){
						
							//Tagihan.BayarById(resp.content.id_kunjungan)
						//}
					//}
					//alert(resp.content.id_kunjungan);
					//}else{
						//zconfirm("Mau langsung ?")
					//}

					me.AfterSimpan();	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	}
	
	
		
});
