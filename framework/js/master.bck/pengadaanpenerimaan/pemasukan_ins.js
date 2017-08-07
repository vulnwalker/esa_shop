var pemasukan_insSKPD = new SkpdCls({
	prefix : 'pemasukan_insSKPD', formName:'pemasukan_insForm', kolomWidth:120,
	
	a : function(){
		alert('dsf');
	},
});

var pemasukan_ins = new DaftarObj2({
	prefix : 'pemasukan_ins',
	url : 'pages.php?Pg=pemasukan_ins', 
	formName : 'pemasukan_insForm',
	satuan_form : '0',//default js satuan
	
	
	
	loading: function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		//setTimeout(function myFunction() {var me=this;me.tabelRekening()},100);
		//setTimeout(function myFunction() {var me=this;me.tabelRekening()},100);
		//setTimeout(function myFunction() {document.getElementById('pekerjaan').focus()},1000);
		//this.daftarRender();
		//this.sumHalRender();
	
	},	
	
	nyalakandatepicker: function(){
		
		$( ".datepicker" ).datepicker({ 
			dateFormat: "dd-mm-yy", 
			showAnim: "slideDown",
			inline: true,
			showOn: "button",
			buttonImage: "datepicker/calender1.png",
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			buttonText : "",
		});	
	},

	inputpenerimaan: function(){
		/*document.getElementById('inputpenerimaanbarang').style.color = "blue";
		document.getElementById('inputpenerimaanbarang').style.fontWeight = "bold";
		document.getElementById('rincianpenerimaanbarang').style.color = "red";
		document.getElementById('rincianpenerimaanbarang').style.fontWeight = "";*/
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=inputpenerimaanDET',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('databarangnya').innerHTML = resp.content;
				}else{
					alert(resp.err);
				}
			}
		});	
		
	},
	
	rincianpenerimaan: function(){
		/*document.getElementById('rincianpenerimaanbarang').style.color = "blue";
		document.getElementById('rincianpenerimaanbarang').style.fontWeight = "bold";
		document.getElementById('inputpenerimaanbarang').style.color = "red";
		document.getElementById('inputpenerimaanbarang').style.fontWeight = "";*/
		
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=rincianpenerimaanDET',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('rinciandatabarangnya').innerHTML = resp.content;
					setTimeout(function myFunction() {pemasukan_ins.CekSesuai()},1000);
					
				}else{
					alert(resp.err);
				}
			}
		});	
		
	},
		
	tabelRekening: function(hapus = 1){
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=tabelRekening&HapusData='+hapus,
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('tbl_rekening').innerHTML = resp.content.tabel;
					document.getElementById('totalbelanja23').innerHTML = resp.content.jumlah;
					if(document.getElementById('koderek')){
						document.getElementById('koderek').focus();
						document.getElementById('atasbutton').innerHTML = resp.content.atasbutton;
						setTimeout(function myFunction() {pemasukan_ins.CekSesuai()},1000);
					}
				}else{
					alert(resp.err);
				}
			}
		});	
		
		setTimeout(function myFunction() {pemasukan_ins.CekSesuai()},1000);
	},
	
	caraperolehan: function(){
		var asalusul = document.getElementById('asalusul').value;
		
		if(asalusul == '1'){
			$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=caraperolehan',
					success: function(data) {	
						var resp = eval('(' + data + ')');			
						if(resp.err==''){
							document.getElementById('pilCaraPerolehan').innerHTML = resp.content;
							setTimeout(function myFunction() {pemasukan_ins.tabelRekening()},100);
						}else{
							alert(resp.err);
						}
					}
				});
		}else{
			document.getElementById('pilCaraPerolehan').innerHTML = '';
			document.getElementById('tbl_rekening').innerHTML = '';
			document.getElementById('totalbelanja23').innerHTML = '';
		}	
	},
	BaruRekening: function(){
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=InsertRekening',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					if(resp.content == 1){
						var me = this ;
						pemasukan_ins.tabelRekening(0);
					}
				}else{
					alert(resp.err);
				}
			}
		});	
	},
	
	namarekening: function(){
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=namarekening',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('namaakun_'+resp.content.idrek).innerHTML = resp.content.namarekening;
				}else{
					alert(resp.err);
				}
			}
		});	
	},
	
	HapusRekening: function(isi){
		var konfrim = confirm("Hapus Data Rekening ?");
		if(konfrim == true){
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=HapusRekening&idrekei='+isi,
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						//document.getElementById('namaakun_'+resp.content.idrek).innerHTML = resp.content.namarekening;
						pemasukan_ins.tabelRekening();
					}else{
						alert(resp.err);
					}
				}
			});	
		}
		
	},
	
	updKodeRek: function(){
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=updKodeRek',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('koderekeningnya_'+resp.content.idrek).innerHTML = resp.content.koderek;
					document.getElementById('jumlanya_'+resp.content.idrek).innerHTML = resp.content.jumlahnya;
					document.getElementById('option_'+resp.content.idrek).innerHTML = resp.content.option;
					
					pemasukan_ins.tabelRekening();
					//document.getElementById('namaakun_'+resp.content.idrek).innerHTML = resp.content.namarekening;
				}else{
					alert(resp.err);
				}
			}
		});	
	},
	
	JADIKANINPUT: function(idna){
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=jadiinput&idrekeningnya='+idna,
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						document.getElementById('koderekeningnya_'+resp.content.idrek).innerHTML = resp.content.koderek;
						document.getElementById('jumlanya_'+resp.content.idrek).innerHTML = resp.content.jumlahnya;
						document.getElementById('option_'+resp.content.idrek).innerHTML = resp.content.option;
						document.getElementById('atasbutton').innerHTML = resp.content.atasbutton;
						//document.getElementById('namaakun_'+resp.content.idrek).innerHTML = resp.content.namarekening;
					}else{
						alert(resp.err);
					}
				}
			});	
	},
	
	jadiinput: function(idna){
		this.tabelRekening();
		setTimeout(function myFunction() {pemasukan_ins.JADIKANINPUT(idna)},100);
	},
	
	
	
	
	CariProgram: function(){
		var me = this;
		cariprogram.windowShow();	
	},
	
	TglNomorDokumen: function(){
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=Tglnomordokumen',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('tgl_dokcopy').value = resp.content.tgl;
					document.getElementById('tgl_dok').value = resp.content.tgl_dok;
				}else{
					alert(resp.err);
				}
			}
		});	
	},
	
	
	cekCaraPerolehan: function(){
		var asalusul = document.getElementById('asalusul').value;
		
		if(asalusul != '1'){
			/*document.getElementById('metodepengadaan').disabled =true;
			document.getElementById('pencairan_dana').disabled =true;
			document.getElementById('program').value ='';
			document.getElementById('progcar').disabled =true;
			document.getElementById('kegiatan').value ="";
			document.getElementById('kegiatan').disabled =true;
			document.getElementById('pekerjaan').disabled =true;
			document.getElementById('pekerjaan').value ="";
			document.getElementById('tgl_dokcopy').value ="";
			document.getElementById('nomdok').value ="";
			document.getElementById('BaruNoDok').disabled =true;
			
			document.getElementById('prog').value ='';*/
		}else{
			/*document.getElementById('progcar').disabled =false;
			document.getElementById('pekerjaan').disabled =false;*/
			//document.getElementById('metodepengadaan').disabled =false;
		}
	},
	
	ambilNomorDokumen: function(pil = '', lang='t'){
		var me = this;
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=nomordokumen&nom='+pil,
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('nomber').innerHTML = resp.content.isi;
					if(lang == 'y'){
						setTimeout(function myFunction() {me.TglNomorDokumen()},100);
					}else{
						document.getElementById('tgl_dokcopy').value = '';
						document.getElementById('tgl_dok').value = '';
					}
				}else{
					alert(resp.err);
				}
			}
		});	
	},
	
	BaruNomDok: function(){	
		
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			if(me.satuan_form==0){//baru dari satuan
				addCoverPage2(cover,1,true,false);	
			}else{//baru dari barang
				addCoverPage2(cover,999,true,false);	
			}
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaruNomDok',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					
					if(resp.err ==""){
						document.getElementById(cover).innerHTML = resp.content;
						pemasukan_ins.nyalakandatepicker();	
					}else{
						alert(resp.err);
						delElem(cover);
					}			
					
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	
	SimpanNomorDokumen: function(){
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
			url: this.url+'&tipe=simpanNomorDokumen',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					me.Close();
					me.ambilNomorDokumen(resp.content.nomdok, 'y');
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	BaruPenyedia: function(){	
		
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			if(me.satuan_form==0){//baru dari satuan
				addCoverPage2(cover,1,true,false);	
			}else{//baru dari barang
				addCoverPage2(cover,999,true,false);	
			}
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaruPenyedia',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					
					if(resp.err ==""){
						document.getElementById(cover).innerHTML = resp.content;
						//$( "#datepicker" ).datepicker({ dateFormat: "dd-mm-yy" });	
					}else{
						alert(resp.err);
						delElem(cover);
					}			
					
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	
	SimpanPenyedia: function(){
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
			url: this.url+'&tipe=simpanPenyedia',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					me.Close();
					document.getElementById('dafpenyedia').innerHTML = resp.content.penyedian;
					//me.ambilNomorDokumen(resp.content.nomdok, 'y');
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	SimpanPenerima: function(){
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
			url: this.url+'&tipe=simpanPenerima',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					me.Close();
					if(document.getElementById('dafpenerima')){
						document.getElementById('dafpenerima').innerHTML = resp.content.penerima;
					}else{
						
						setTimeout(function myFunctionPersen() {pemasukan.TutupForm('pemasukan_formcover_TTD');},100);
						setTimeout(function myFunctionPersen() {pemasukan.LaporanTTD();},101);
					}
					//me.ambilNomorDokumen(resp.content.nomdok, 'y');
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	pilihPangkat : function(){
	var me = this; 
		$.ajax({
		  url: this.url+'&tipe=pilihPangkat',
		  type : 'POST',
		  data:$('#'+this.prefix+'_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			
			if(resp.err == ''){
				document.getElementById('golang_akhir').value = resp.content;
			}else{
				alert(resp.err);
			}				
		}	
		});
	},
	
	BaruPenerima: function(FORM_DPLH = '#'+this.formName){	
		
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
				addCoverPage2(cover,999,true,false);
			$.ajax({
				type:'POST', 
				data:$(FORM_DPLH).serialize(),
			  	url: this.url+'&tipe=formBaruPenerima',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					
					if(resp.err ==""){
						document.getElementById(cover).innerHTML = resp.content;
						//$( "#datepicker" ).datepicker({ dateFormat: "dd-mm-yy" });	
					}else{
						alert(resp.err);
						delElem(cover);
					}			
					
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	
	detail: function(){
		//alert('detail');
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();			
			//UserAktivitasDet.genDetail();			
			
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
			if(me.satuan_form==0){//baru dari satuan
				addCoverPage2(cover,1,true,false);	
			}else{//baru dari barang
				addCoverPage2(cover,999,true,false);	
			}
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
	
	SimpanDet: function(){
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=SimpanDet',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						//	rincianpenerimaan.inputpenerimaan
						//document.getElementById('namaakun_'+resp.content.idrek).innerHTML = resp.content.namarekening;
						pemasukan_ins.rincianpenerimaan();
						pemasukan_ins.inputpenerimaan();
					}else{
						alert(resp.err);
					}
				}
			});	
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
					if(me.satuan_form==0){
						me.Close();
						me.AfterSimpan();						
					}else{
						me.Close();
						barang.refreshComboSatuan();
					}

				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	formatCurrency:function(num) {
		num = num.toString().replace(/\$|\,/g,'');
		if(isNaN(num))
		num = "0";
		sign = (num == (num = Math.abs(num)));
		num = Math.floor(num*100+0.50000000001);
		cents = num%100;
		num = Math.floor(num/100).toString();
		if(cents<10)
		cents = "0" + cents;
		for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+'.'+
		num.substring(num.length-(4*i+3));
		return (((sign)?'':'-') + '' + num + ',' + cents);
	},
	
	isNumberKey: function(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		
		return false;
		return true;
	},
	
	hitungjumlahHarga: function(){
		var jumlah_barang = document.getElementById('jumlah_barang').value;
		var harga_satuan = document.getElementById('harga_satuan').value;
		
		var jns_trans = document.getElementById('jns_transaksi').value;
		if(jns_trans == '2'){
			var kuantitas = document.getElementById('kuantitas').value;
			var volume = jumlah_barang * kuantitas;
			var total = volume * harga_satuan;
			
			document.getElementById('volume').value = volume;
			
		}else{
			var total = jumlah_barang * harga_satuan;
		}
		
		document.getElementById('jumlah_harga').value = this.formatCurrency(total);
	},
	
	HapusRincianPenerimaan: function(isi){
		var konfrim = confirm("Hapus Rincian Penerimaan ?");
		if(konfrim == true){
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=HapusRincianPenerimaan&IdRincian='+isi,
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						//document.getElementById('namaakun_'+resp.content.idrek).innerHTML = resp.content.namarekening;
						pemasukan_ins.rincianpenerimaan();
						pemasukan_ins.inputpenerimaan();
					}else{
						alert(resp.err);
					}
				}
			});	
		}
		
		//alert(isi);
		
	},
	
	UbahRincianPenerimaan: function(isi){
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=UbahRincianPenerimaan&IdRincian='+isi,
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						//	rincianpenerimaan.inputpenerimaan
						//document.getElementById('namaakun_'+resp.content.idrek).innerHTML = resp.content.namarekening;
						/*pemasukan_ins.rincianpenerimaan();*/
						document.getElementById('databarangnya').innerHTML = resp.content;
					}else{
						alert(resp.err);
					}
				}
			});	
	},
	
	CekSesuai: function(){
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=CekSesuai',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						//	rincianpenerimaan.inputpenerimaan
							document.getElementById('jumlahsudahsesuai').innerHTML = resp.content.ceklis;	
							document.getElementById('idpenerimaan').value = resp.content.idpenerimaaan;	
												
					}else{
						alert(resp.err);
					}
				}
			});	
	},
	
	SimpanSemua: function(){
		idna = document.getElementById('pemasukan_ins_idplh').value;
		
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=SimpanSemua',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						alert("Data Berhasil Disimpan !");
						
						var konfrim = confirm("CETAK SURAT PERMOHONAN VALIDASI INPUT DATA ?");
						if(konfrim == true){
							pemasukan.CetakPermohonan(idna);
						}else{
							window.close();
							window.opener.location.reload();
						}
						
						//	rincianpenerimaan.inputpenerimaan
							//document.getElementById('jumlahsudahsesuai').innerHTML = resp.content.ceklis;	
												
					}else{
						alert(resp.err);
					}
				}
			});	
	},
	
	SetPenerima: function(){
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=SetPenerima',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						//	rincianpenerimaan.inputpenerimaan
						//document.getElementById('namaakun_'+resp.content.idrek).innerHTML = resp.content.namarekening;
						
					}else{
						alert(resp.err);
					}
				}
			});	
	},
	
	BatalSemua: function(){
		idna = document.getElementById('pemasukan_ins_idplh').value;
		
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=BatalSemua',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
							window.close();
							window.opener.location.reload();									
					}else{
						alert(resp.err);
					}
				}
			});	
	},
	
		
});
