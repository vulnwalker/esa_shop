var dpaSKPD221_v2 = new DaftarObj2({
	prefix : 'dpaSKPD221_v2',
	url : 'pages.php?Pg=dpa-skpd-2.2.1_v2', 
	formName : 'dpaSKPD221_v2Form',
	dpaSKPD221_v2_form : '0',//default js dpaSKPD221_v2
	loading: function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
/*		this.sumHalRender();*/ 


		
	},
	filterRenderAfter : function(){
    	this.daftarRender();
  	},
	programChanged : function(){
		var arrayP = $("#p").val().split('.');;
		var bk = arrayP[0];
		var ck = arrayP[1];
		var hiddenP = arrayP[2];
	
		$("#bk").val(bk);
		$("#ck").val(ck);
		$("#hiddenP").val(hiddenP);
		$("#q").val('');
		dpaSKPD221_v2.refreshList(true);	
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
		
	},pilihBidang: function(){
		dpaSKPD221_v2.refreshList(true);

	},BidangAfter2: function(){
		dpaSKPD221_v2.refreshList(true);

	},comboSKPDChanged: function(){
		dpaSKPD221_v2.refreshList(true);

	},CariModul: function(){
		var me = this;	

		popupModul.el_namaModul= 'namaModul';		
		popupModul.el_idModul= 'idModul';																				
/*		RefBarangButuh.el_nama_barang= 'nama_barang';		*/																	
		popupModul.windowSaveAfter= function(){};
		popupModul.windowShow();	
	},
		
	histori: function(id){
		var me = this;	

		popupHistori.el_namaHistori= 'namaHistori';		
		popupHistori.el_idHistori= 'idHistori';											
		popupHistori.windowSaveAfter= function(){};
		popupHistori.windowShow(id);	
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
				
/*				me.sumHalRender();*/
		  	}
		});
	},
	Baru: function(){	
		var me = this;
		if($("#cmbUrusan").val()  == ''){
		 	alert("Pilih Urusan");
		}else if($("#cmbBidang").val()  == ''){
			alert("Pilih Bidang");
		}else if($("#cmbSKPD").val()  == ''){
			alert("Pilih SKPD");
		}else if($("#cmbUnit").val()  == ''){
			alert("Pilih Unit");
		}else if($("#cmbSubUnit").val()  == ''){
			alert("Pilih Sub Unit");
		}else if($("#hiddenP").val()  == ''){
			alert("Pilih Program");
		}else if($("#q").val()  == ''){
			alert("Pilih Kegiatan");
		}else{
				
				var skpd = $("#cmbUrusan").val()+'.'+$("#cmbBidang").val()+'.'+$("#cmbSKPD").val()+'.'+$("#cmbUnit").val()+'.'+$("#cmbSubUnit").val()+'.'+$("#bk").val()+'.'+$("#ck").val()+'.'+$("#hiddenP").val()+'.'+$("#q").val();
	
				var aForm = document.getElementById(this.formName);
				$.ajax({
				type:'POST', 
			  	url: this.url+'&tipe=clearTemp',
			  	success: function(data) {		
					
					aForm.action= 'pages.php?Pg=formRkaSkpd221_v2&skpd='+skpd;
					aForm.target='_blank';
					aForm.submit();	
					aForm.target='';
				 }
			});
				
			
		}
		
	},
	Info: function(){	
		var aForm = document.getElementById(this.formName);
				$.ajax({
				  url: this.url+'&tipe=hubla',
				  type : 'POST',
				  data:$('#'+this.formName).serialize(),
				  success: function(data) {
						var resp = eval('(' + data + ')');
						if(resp.err == "") {
							var kodeRek = resp.content.kodeRek;
							aForm.action= 'pages.php?Pg=alokasiDpa';
							aForm.target='_blank';
							aForm.submit();	
							aForm.target='';
						}else{
							alert(resp.err);		
						}	
	
				  }
				});
	},
	unlockFindRekening: function(){
		var me = this;
		if( $("#cmbJenisRKAForm").val() == ""){
			$("#findRekening").attr('disabled',true);
		}else{
			$("#findRekening").attr('disabled',false);
			$("#findRekening").attr('onclick',"dpaSKPD221_v2.findRekening('"+$("#cmbJenisRKAForm").val()+"');");
			
		}


	},
	Edit:function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if($("#cmbUrusan").val()  == ''){
		 	alert("Pilih Urusan");
		}else if($("#cmbBidang").val()  == ''){
			alert("Pilih Bidang");
		}else if($("#cmbSKPD").val()  == ''){
			alert("Pilih SKPD");
		}else if($("#cmbUnit").val()  == ''){
			alert("Pilih Unit");
		}else if($("#cmbSubUnit").val()  == ''){
			alert("Pilih Sub Unit");
		}else if($("#hiddenP").val()  == ''){
			alert("Pilih Program");
		}else if($("#q").val()  == ''){
			alert("Pilih Kegiatan");
		}else{
			if(errmsg ==''){ 
				var box = this.GetCbxChecked();
				var skpd = $("#cmbUrusan").val()+'.'+$("#cmbBidang").val()+'.'+$("#cmbSKPD").val()+'.'+$("#cmbUnit").val()+'.'+$("#cmbSubUnit").val()+'.'+$("#bk").val()+'.'+$("#ck").val()+'.'+$("#hiddenP").val()+'.'+$("#q").val();
	
				var aForm = document.getElementById(this.formName);
				$.ajax({
				  url: this.url+'&tipe=editTab',
				  type : 'POST',
				  data:$('#'+this.formName).serialize(),
				  success: function(data) {
						var resp = eval('(' + data + ')');
						if(resp.err == "") {
							var kodeRek = resp.content.kodeRek;
							aForm.action= 'pages.php?Pg=formRkaSkpd221_v2&rek='+kodeRek+'&skpd='+skpd;
							aForm.target='_blank';
							aForm.submit();	
							aForm.target='';
						}else{
							alert(resp.err);		
						}	
	
				  }
				});
			}else{
				alert(errmsg);
			}
		}
	},
	Remove:function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if($("#cmbUrusan").val()  == ''){
		 	alert("Pilih Urusan");
		}else if($("#cmbBidang").val()  == ''){
			alert("Pilih Bidang");
		}else if($("#cmbSKPD").val()  == ''){
			alert("Pilih SKPD");
		}else if($("#cmbUnit").val()  == ''){
			alert("Pilih Unit");
		}else if($("#cmbSubUnit").val()  == ''){
			alert("Pilih Sub Unit");
		}else if($("#hiddenP").val()  == ''){
			alert("Pilih Program");
		}else if($("#q").val()  == ''){
			alert("Pilih Kegiatan");
		}else{
			if(errmsg ==''){ 
				var box = this.GetCbxChecked();
	
				$.ajax({
				  url: this.url+'&tipe=Remove',
				  type : 'POST',
				  data:$('#'+this.formName).serialize(),
				  success: function(data) {
						var resp = eval('(' + data + ')');
						if(resp.err == "") {
							dpaSKPD221_v2.refreshList(true);
						}else{
							alert(resp.err);		
						}	
	
				  }
				});
			}else{
				alert(errmsg);
			}
		}
	},
	sesuai:function(id){
	

		$.ajax({
				type:'POST', 
				data:{idAwal : id,
					  koreksiVolumebarang : document.getElementById("volumeRekSesuai"+id).value,
					  koreksiSatuanHarga : document.getElementById("hargaSatuanSesuai"+id).value
					  },
				url: this.url+'&tipe=koreksi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if(resp.err == ''){
						dpaSKPD221_v2.refreshList(true);	
					}else{
						alert(resp.err);
						if(resp.err == 'Tahap Koreksi Telah Habis'){
							window.location.reload();
						}
					}
					
				 }
			  });

	},
	koreksi:function(id){
		$("#spanVolumeBarang"+id).html("<input type='text' name='volumeBarang"+id+"' id='volumeBarang"+id+"' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='dpaSKPD221_v2.bantuVolumeBarang("+id+")'   style='text-align: right;'>  <span id='bantuVolumeBarang"+id+"' ></span>");
		$("#spanSatuanHarga"+id).html("<input type='text' name='satuanHarga"+id+"' id='satuanHarga"+id+"' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='dpaSKPD221_v2.bantuSatuanHarga("+id+")'    style='text-align: right;'>  <span id='bantuSatuanHarga"+id+"' ></span>");
		$("#buttonSubmitKoreksi"+id).html("<img src='datepicker/save.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=dpaSKPD221_v2.submitKoreksi('"+id+"');></img>&nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=dpaSKPD221_v2.refreshList(true);></img>");
		$("#alignButton"+id).attr('align','center' );	
	},
	submitKoreksi:function(id) {

			if( document.getElementById("volumeBarang"+id).value == 0 ){
				alert("Isi Volume Barang");
			}else if( document.getElementById("satuanHarga"+id).value == 0 ){
				alert("Isi Satuan Harga");
			}else{
			  $.ajax({
				type:'POST', 
				data:{idAwal : id,
					  koreksiVolumebarang : document.getElementById("volumeBarang"+id).value,
					  koreksiSatuanHarga : document.getElementById("satuanHarga"+id).value
					  },
				url: this.url+'&tipe=koreksi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if(resp.err == ''){
						dpaSKPD221_v2.refreshList(true);	
					}else{
						alert(resp.err);
						if(resp.err == 'Tahap Koreksi Telah Habis'){
							window.location.reload();
						}
					}
					
				 }
			  });
			}
		
	},
	
	bantuVolumeBarang : function(id){
		document.getElementById('bantuVolumeBarang'+id).innerHTML = dpaSKPD221_v2.formatCurrency2($("#volumeBarang"+id).val());
		var hasilKali = Number($("#volumeBarang"+id).val()) * Number($("#satuanHarga"+id).val()) ; 
		$("#spanJumlahHarga"+id).text(dpaSKPD221_v2.formatCurrency(hasilKali));
	},
	bantuSatuanHarga : function(id){
		document.getElementById('bantuSatuanHarga'+id).innerHTML =  dpaSKPD221_v2.formatCurrency($("#satuanHarga"+id).val());
		var hasilKali = Number($("#volumeBarang"+id).val()) * Number($("#satuanHarga"+id).val()) ; 
		$("#spanJumlahHarga"+id).text(dpaSKPD221_v2.formatCurrency(hasilKali));
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
	formatCurrency2:function(num) {
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
		return (((sign)?'':'-') + '' + num );
	},
	
	bantu : function(){
		$("#bantu").text("Rp. "+dpaSKPD221_v2.formatCurrency($("#hargaSatuan").val())  );
		
	},
	
	findRekening : function(jenisRka){
		var me = this;	
		var filterRek = "";
		if(jenisRka == "2.2.1"){
			filterRek = "RKA 2.2.1";
		}else if(jenisRka == "2.1"){
			filterRek = "RKA 2.1";
		}
		popupRekening.el_kode_rekening = 'kodeRekening';
		popupRekening.el_nama_rekening = 'namaRekening';
		popupRekening.windowSaveAfter= function(){};
		popupRekening.filterAkun=filterRek;
		popupRekening.windowShow();
		
	},
	
	findBarang : function(kodeRENJA){
		var me = this;	
		popupBarangRKA.windowShow(kodeRENJA);
		
	},
	
	catatan:function(id){
		var me = this;

			
			 var cover = this.prefix+'_formcover';

			$.ajax({
				type:'POST', 
				data:{idAwal : id },
			  	url: this.url+'&tipe=Catatan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					if(resp.err == ''){
					   
						document.body.style.overflow='hidden';
						addCoverPage2(cover,999,true,false);	
						document.getElementById(cover).innerHTML = resp.content;
					}else{
					/*	delElem(cover);*/
						alert(resp.err);
					}			
					
					
			  	}
			});

		
	},
   Validasi:function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
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
			  	url: this.url+'&tipe=Validasi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					if(resp.err == ''){
						document.getElementById(cover).innerHTML = resp.content;
					}else{
						delElem(cover);
						alert(resp.err);
						if(resp.err == 'Tahap Validasi Telah Habis'){
							window.location.reload();
						}
					}			
					
					//setTimeout(function myFunction() {pemasukan.jam()},100);	
					//me.AfterFormBaru();
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
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					if(me.dpaSKPD221_v2_form==0){
						me.Close();
						me.AfterSimpan();						
					}else{
						me.Close();
						barang.refreshCombodpaSKPD221_v2();
					}

				}else{
					alert(resp.err);
				}
		  	}
		});
	}
	,	
   SaveValid: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=SaveValid',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					if(me.dpaSKPD221_v2_form==0){
						me.Close();
						me.AfterSimpan();						
					}

				}else{
					alert(resp.err);
				}
		  	}
		});
	}
	,	
   SaveCatatan: function(id){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:{id : id,
				  catatan : $("#catatan").val()},
			url: this.url+'&tipe=SaveCatatan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
					if(me.dpaSKPD221_v2_form==0){
						me.Close();
						me.AfterSimpan();						
					}

				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	Laporan:function(){
			
			var url2 = this.url;
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=Report',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					if(resp.err == ''){
						window.open(url2+'&tipe=Laporan','_blank');
						
					}else{
						alert(resp.err);
					}			
			
			  	}
			});
		
		
	},
	formAlokasi:function(){
		var me = this;
		var cover = this.prefix+'_formcover';


			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formAlokasi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					if(resp.err == ''){
						document.body.style.overflow='hidden';
						addCoverPage2(cover,999,true,false);	
						document.getElementById(cover).innerHTML = resp.content;
					}else{
						alert(resp.err);
					}			
					
					
			  	}
			});

		
	},
	formAlokasiTriwulan:function(){
		var me = this;
		var cover = this.prefix+'_formcover';
	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formAlokasiTriwulan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					if(resp.err == ''){
						document.body.style.overflow='hidden';
						addCoverPage2(cover,999,true,false);	
						document.getElementById(cover).innerHTML = resp.content;
					}else{
						alert(resp.err);
					}			
					
					
			  	}
			});
		
	},
	
	jenisPerhitunganChanged : function(){
		if($("#jenisPerhitungan").val() == "SEMI OTOMATIS" ){
			$("#buttonHitung").attr('disabled',false);
		}else{
			$("#buttonHitung").attr('disabled',true);
		}
		
	},
	jenisAlokasiChanged : function(){
		
		
		if($("#jenisAlokasi").val() == "BULANAN"){
			var me= this;	
			this.OnErrorClose = false;	
			document.body.style.overflow='hidden';
			var cover = this.prefix+'_formsimpan';
			addCoverPage2(cover,1,true,false);	
			delElem(cover);	
			me.Close();
			$.ajax({
				type:'POST', 
				url: this.url+'&tipe=clearAlokasi',
				success: function(data) {	
					dpaSKPD221_v2.formAlokasi();
				}
			});	
			
			
		}else if($("#jenisAlokasi").val() == "TRIWULAN"){
			var me= this;	
			this.OnErrorClose = false;	
			document.body.style.overflow='hidden';
			var cover = this.prefix+'_formsimpan';
			addCoverPage2(cover,1,true,false);	
			delElem(cover);	
			me.Close();
			$.ajax({
				type:'POST', 
				url: this.url+'&tipe=clearAlokasi',
				success: function(data) {	
					dpaSKPD221_v2.formAlokasiTriwulan();
				}
			});	
			
		}else{
			var me= this;	
			this.OnErrorClose = false;	
			document.body.style.overflow='hidden';
			var cover = this.prefix+'_formsimpan';
			addCoverPage2(cover,1,true,false);	
			delElem(cover);	
			me.Close();
			$.ajax({
				type:'POST', 
				url: this.url+'&tipe=clearAlokasi',
				success: function(data) {	
					dpaSKPD221_v2.formAlokasi();
				}
			});	
		}
		
		
		
	},
	hitungSelisih:function(namaBantu){
		var jan = Number($("#jan").val());
		var feb = Number($("#feb").val());
		var mar = Number($("#mar").val());
		var apr = Number($("#apr").val());
		var mei = Number($("#mei").val());
		var jun = Number($("#jun").val());
		var jul = Number($("#jul").val());	
		var agu = Number($("#agu").val());
		var sep = Number($("#sep").val());
		var okt = Number($("#okt").val());
		var nop = Number($("#nop").val());
		var des = Number($("#des").val());
		
		var idPost =  namaBantu.replace("bantu", "");
		idPost = idPost.toLowerCase();
		$("#"+namaBantu).text("Rp. "+this.formatCurrency($("#"+idPost).val())  );
		
		var jumlahHargaAlokasi = Number( jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nop + des );
		$("#jumlahHargaAlokasi").val(jumlahHargaAlokasi);
		var selisih = Number( Number($("#jumlahHargaForm").val()) -  Number( jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nop + des ) );
		$("#selisih").val(selisih);	
		$("#bantuPenjumlahan").text("Rp. "+this.formatCurrency(jumlahHargaAlokasi)  );		
		$("#bantuSelisih").text("Rp. "+this.formatCurrency(selisih)  );				
	},	
	hitung: function(){
		if($("#jenisAlokasi").val() == '' ){
			alert("Pilih Jenis Alokasi");
		}else if($("#jenisPerhitungan").val() == ''){
			alert("Pilih Jenis Perhitungan");
		}else{
			if($("#jenisAlokasi").val() == "BULANAN"){
				var jumlahHargaForm = $("#jumlahHargaForm").val();
				$("#jan").val(jumlahHargaForm / 12);
				$("#feb").val(jumlahHargaForm / 12);
				$("#mar").val(jumlahHargaForm / 12);
				$("#apr").val(jumlahHargaForm / 12);
				$("#mei").val(jumlahHargaForm / 12);
				$("#jun").val(jumlahHargaForm / 12);
				$("#jul").val(jumlahHargaForm / 12);
				$("#agu").val(jumlahHargaForm / 12);
				$("#sep").val(jumlahHargaForm / 12);
				$("#okt").val(jumlahHargaForm / 12);
				$("#nop").val(jumlahHargaForm / 12);
				$("#des").val(jumlahHargaForm / 12);
				this.hitungSelisih('bantuJan');
				this.hitungSelisih('bantuFeb');
				this.hitungSelisih('bantuApr');
				this.hitungSelisih('bantuMei');
				this.hitungSelisih('bantuJul');
				this.hitungSelisih('bantuAgu');
				this.hitungSelisih('bantuOkt');
				this.hitungSelisih('bantuNop');
				this.hitungSelisih('bantuMar');
				this.hitungSelisih('bantuJun');
				this.hitungSelisih('bantuSep');
				this.hitungSelisih('bantuDes');
			}else if($("#jenisAlokasi").val() == "TRIWULAN"){
				var jumlahHargaForm = $("#jumlahHargaForm").val();
				$("#jan").val('');
				$("#feb").val('');
				$("#mar").val(jumlahHargaForm / 4);
				$("#apr").val('');
				$("#mei").val('');
				$("#jun").val(jumlahHargaForm / 4);
				$("#jul").val('');
				$("#agu").val('');
				$("#sep").val(jumlahHargaForm / 4);
				$("#okt").val('');
				$("#nop").val('');
				$("#des").val(jumlahHargaForm / 4);
				this.hitungSelisih('bantuMar');
				this.hitungSelisih('bantuJun');
				this.hitungSelisih('bantuSep');
				this.hitungSelisih('bantuDes');
			}
			
		}
		
		
	},
	
					
	
	checkAlokasi : function(){
			
			var me = this;
			errmsg = this.CekCheckbox();
			if(errmsg ==''){ 
				var box = this.GetCbxChecked();
				$.ajax({
				type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=checkAlokasi',
			  		success: function(data) {		
						var resp = eval('(' + data + ')');	
						if(resp.err == ''){
							if(resp.content.jenis == "BULANAN"){
								dpaSKPD221_v2.formAlokasi();
							}else if(resp.content.jenis == "TRIWULAN"){
								dpaSKPD221_v2.formAlokasiTriwulan();
							}else{
								dpaSKPD221_v2.formAlokasi();
							}
						}else{
						alert(resp.err);
						if(resp.err == 'Tahap Telah Habis'){
							window.location.reload();
							}
						}
					
					}
				});
			}else{
				alert(errmsg);
			}
			
	
					
	},	
	
	SaveAlokasi : function(id){
			
			var me= this;	
			this.OnErrorClose = false;	
			document.body.style.overflow='hidden';
			var cover = this.prefix+'_formsimpan';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:{  jumlahHarga : $("#jumlahHargaForm").val(),
						jenisAlokasi : $("#jenisAlokasi").val(),
						jan	: $("#jan").val(),
						feb : $("#feb").val(),
						mar : $("#mar").val(),
						apr : $("#apr").val(),
						mei : $("#mei").val(),
						jun : $("#jun").val(),
						jul : $("#jul").val(),
						agu : $("#agu").val(),
						sep : $("#sep").val(),
						okt : $("#okt").val(),
						nop : $("#nop").val(),
						des : $("#des").val(),
						jumlahHargaAlokasi : $("#jumlahHargaAlokasi").val(),
						c1 : $("#cmbUrusan").val(),
						c  : $("#cmbBidang").val(),
						d  : $("#cmbSKPD").val(),
						bk : $("#bk").val(),
						ck : $("#ck").val(),
						p : $("#hiddenP").val(),
						q : $("#q").val(),
						idRekening : id
						
						},
				url: this.url+'&tipe=saveAlokasi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					delElem(cover);	
					if(resp.err == ''){
						me.Close();	
						dpaSKPD221_v2.refreshList(true);	
					}else{
						alert(resp.err);
						if(resp.err == 'Tahap Telah Habis'){
							window.location.reload();
						}
					}
					
				 }
			});
	
					
	},	
	formRincianVolume:function(id){
		var me = this;
		var cover = this.prefix+'_formcover';

		if($("#volumeBarang").val() == '' || $("#volumeBarang").val() == '0' ){
			alert("Volume Kosong");
		}else{
		
				$.ajax({
					type:'POST', 
					data : {
							 id : id	
					},
				  	url: this.url+'&tipe=formRincianVolume',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');
						if(resp.err == ''){
							document.body.style.overflow='hidden';
							addCoverPage2(cover,2,true,false);	
							document.getElementById(cover).innerHTML = resp.content;
						}else{
							alert(resp.err);
						}			
						
						
				  	}
				});
		}
		
	},
	setTotalRincian : function(){
		
		var jumlah1 = Number($("#jumlah1").val());
		var jumlah2 = Number($("#jumlah2").val());
		var jumlah3 = Number($("#jumlah3").val());
		if($("#jumlah3").val() == ""){
			var jumlahTotal = Number(jumlah1 * jumlah2 );
		}else{
			var jumlahTotal = Number(jumlah1 * jumlah2 * jumlah3);
		}
		
		$("#jumlah4").val(jumlahTotal);
		
	},
	newSatuanSatuan:function(){
		var me = this;
		var cover = this.prefix+'_formcover2';

				$.ajax({
					type:'POST', 
				  	url: this.url+'&tipe=newSatuanSatuan',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');
						if(resp.err == ''){
							document.body.style.overflow='hidden';
							addCoverPage2(cover,3,true,false);	
							document.getElementById(cover).innerHTML = resp.content;
						}else{
							alert(resp.err);
						}			
						
						
				  	}
				});
	},
	Close2 : function(){
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formcover2';
		
		delElem(cover);	
	},
	SaveSatuanSatuan: function(){
		var me= this;	
		this.OnErrorClose = false	

		$.ajax({
			type:'POST', 
			data:{namaSatuan : $("#namaSatuan").val()},
			url: this.url+'&tipe=SaveSatuanSatuan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if(resp.err==''){
					var myOptions = {
						    val1 : $("#namaSatuan").val()
						};
					$.each(myOptions, function(val, text) {
						  $('#satuanSatuan1').append( new Option(text,val) );
						  $('#satuanSatuan2').append( new Option(text,val) );
						  $('#satuanSatuan3').append( new Option(text,val) );
						});
					me.Close2();
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	newSatuanVolume:function(){
		var me = this;
		var cover = this.prefix+'_formcover2';

				$.ajax({
					type:'POST', 
				  	url: this.url+'&tipe=newSatuanVolume',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');
						if(resp.err == ''){
							document.body.style.overflow='hidden';
							addCoverPage2(cover,3,true,false);	
							document.getElementById(cover).innerHTML = resp.content;
						}else{
							alert(resp.err);
						}			
						
						
				  	}
				});
	},
	SaveSatuanVolume: function(){
		var me= this;	
		this.OnErrorClose = false	

		$.ajax({
			type:'POST', 
			data:{namaSatuan : $("#namaSatuan").val()},
			url: this.url+'&tipe=SaveSatuanVolume',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if(resp.err==''){
					var myOptions = {
						    val1 : $("#namaSatuan").val()
						};
					$.each(myOptions, function(val, text) {
						  $('#satuanVolume').append( new Option(text,val) );
						  $('#satuan').append( new Option(text,val) );
						});
					me.Close2();
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	SaveRincianVolume : function(id){
		
			var me= this;	
			this.OnErrorClose = false;	
			document.body.style.overflow='hidden';
			var cover = this.prefix+'_formsimpan';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:{  
						satuan1 : $("#satuanSatuan1").val(),
						satuan2 : $("#satuanSatuan2").val(),
						satuan3 : $("#satuanSatuan3").val(),
						satuanTotal : $("#satuanVolume").val(),
						jumlah1 : $("#jumlah1").val(),
						jumlah2 : $("#jumlah2").val(),
						jumlah3 : $("#jumlah3").val(),
						jumlahTotal : $("#jumlah4").val(),	
						id : id
							
						},
				url: this.url+'&tipe=SaveRincianVolume',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					delElem(cover);		
					if(resp.err==''){
						me.Close();	
					}else{
						alert(resp.err);
					}
			  	}
			});
	
					
	},
});
