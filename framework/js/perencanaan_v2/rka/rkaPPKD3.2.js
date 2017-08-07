var rkaPPKD32_v2 = new DaftarObj2({
	prefix : 'rkaPPKD32_v2',
	url : 'pages.php?Pg=rka-ppkd-3.2_v2', 
	formName : 'rkaPPKD32_v2Form',
	rkaPPKD32_v2_form : '0',//default js rkaPPKD32_v2
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
		rkaPPKD32_v2.refreshList(true);	
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
		rkaPPKD32_v2.refreshList(true);

	},BidangAfter2: function(){
		rkaPPKD32_v2.refreshList(true);

	},comboSKPDChanged: function(){
		rkaPPKD32_v2.refreshList(true);

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
		}else{
				
				var skpd = $("#cmbUrusan").val()+'.'+$("#cmbBidang").val()+'.'+$("#cmbSKPD").val();
	
				var aForm = document.getElementById(this.formName);
				$.ajax({
				type:'POST', 
				data : {c1 : $("#cmbUrusan").val(),
						c  : $("#cmbBidang").val(),
						d  : $("#cmbSKPD").val(),
						
						},				
			  	url: this.url+'&tipe=clearTemp',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					if(resp.err == ''){
						aForm.action= 'pages.php?Pg=formRkaPpkd32_v2&skpd='+skpd;
						aForm.target='_blank';
						aForm.submit();	
						aForm.target='';
					}else{
						alert(resp.err);
					}
					
				 }
			});
				
			
		}
		
	},
	Info: function(){	
		var me = this;
	    var cover = this.prefix+'_formcover';
			$.ajax({
				type:'POST', 
			  	url: this.url+'&tipe=Info',
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
	unlockFindRekening: function(){
		var me = this;
		if( $("#cmbJenisRKAForm").val() == ""){
			$("#findRekening").attr('disabled',true);
		}else{
			$("#findRekening").attr('disabled',false);
			$("#findRekening").attr('onclick',"rkaPPKD32_v2.findRekening('"+$("#cmbJenisRKAForm").val()+"');");
			
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
		}else{
			if(errmsg ==''){ 
				var box = this.GetCbxChecked();
				var skpd = $("#cmbUrusan").val()+'.'+$("#cmbBidang").val()+'.'+$("#cmbSKPD").val();
	
				var aForm = document.getElementById(this.formName);
				$.ajax({
				  url: this.url+'&tipe=editTab',
				  type : 'POST',
				  data:$('#'+this.formName).serialize(),
				  success: function(data) {
						var resp = eval('(' + data + ')');
						if(resp.err == "") {
							var kodeRek = resp.content.kodeRek;
							aForm.action= 'pages.php?Pg=formRkaPpkd32_v2&rek='+kodeRek+'&skpd='+skpd;
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
		}else{
			if(errmsg ==''){ 
				var box = this.GetCbxChecked();
				var skpd = $("#cmbUrusan").val()+'.'+$("#cmbBidang").val()+'.'+$("#cmbSKPD").val();
	
				$.ajax({
				  url: this.url+'&tipe=Remove',
				  type : 'POST',
				  data:$('#'+this.formName).serialize(),
				  success: function(data) {
						var resp = eval('(' + data + ')');
						if(resp.err == "") {
							rkaPPKD32_v2.refreshList(true);
						}else{
							alert(resp.err);	
							if(resp.err == 'Tahap Koreksi Telah Habis'){
							window.location.reload();
							}	
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
						rkaPPKD32_v2.refreshList(true);	
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
		$("#spanVolumeBarang"+id).html("<input type='text' name='volumeBarang"+id+"' id='volumeBarang"+id+"' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='rkaPPKD32_v2.bantuVolumeBarang("+id+")'   style='text-align: right;'>  <span id='bantuVolumeBarang"+id+"' ></span>");
		$("#spanSatuanHarga"+id).html("<input type='text' name='satuanHarga"+id+"' id='satuanHarga"+id+"' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='rkaPPKD32_v2.bantuSatuanHarga("+id+")'    style='text-align: right;'>  <span id='bantuSatuanHarga"+id+"' ></span>");
		$("#buttonSubmitKoreksi"+id).html("<img src='datepicker/save.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkaPPKD32_v2.submitKoreksi('"+id+"');></img>&nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkaPPKD32_v2.refreshList(true);></img>");
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
						rkaPPKD32_v2.refreshList(true);	
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
		document.getElementById('bantuVolumeBarang'+id).innerHTML = rkaPPKD32_v2.formatCurrency2($("#volumeBarang"+id).val());
		var hasilKali = Number($("#volumeBarang"+id).val()) * Number($("#satuanHarga"+id).val()) ; 
		$("#spanJumlahHarga"+id).text(rkaPPKD32_v2.formatCurrency(hasilKali));
	},
	bantuSatuanHarga : function(id){
		document.getElementById('bantuSatuanHarga'+id).innerHTML =  rkaPPKD32_v2.formatCurrency($("#satuanHarga"+id).val());
		var hasilKali = Number($("#volumeBarang"+id).val()) * Number($("#satuanHarga"+id).val()) ; 
		$("#spanJumlahHarga"+id).text(rkaPPKD32_v2.formatCurrency(hasilKali));
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
		$("#bantu").text("Rp. "+rkaPPKD32_v2.formatCurrency($("#hargaSatuan").val())  );
		
	},
	
	findRekening : function(jenisRka){
		var me = this;	
		var filterRek = "";
		if(jenisRka == "2.1"){
			filterRek = "RKA 2.1";
		}else if(jenisRka == "1"){
			filterRek = "RKA 1";
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
    Validasi:function(id_anggaran){
		var me = this;
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			if(me.satuan_form==0){
				addCoverPage2(cover,1,true,false);	
			}else{
				addCoverPage2(cover,999,true,false);	
			}
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=Validasi&id_anggaran='+id_anggaran,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					if(resp.err == ''){
						document.getElementById(cover).innerHTML = resp.content;
					}else{
						delElem(cover);
						alert(resp.err);
						if(resp.err == 'Tahap Penyusunan Telah Habis'){
							window.location.reload();
						}
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
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					if(me.rkaPPKD32_v2_form==0){
						me.Close();
						me.AfterSimpan();						
					}else{
						me.Close();
						barang.refreshComborkaPPKD32_v2();
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
					if(me.rkaPPKD32_v2_form==0){
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
					if(me.rkaPPKD32_v2_form==0){
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
	formAlokasi:function(id){
		var me = this;
		var cover = this.prefix+'_formcover';
		var volumeBarang = $("#volumeBarang"+id).val();
		var satuanHarga = $("#satuanHarga"+id).val();
		jumlahHarga = Number(volumeBarang) * Number(satuanHarga);

	if($("#jumlahHarga").val() == '' || $("#jumlahHarga").val() == '0' ){
		alert("Jumlah Harga Kosong");
	}else{
			$.ajax({
				type:'POST', 
				data : {
						 jumlahHarga : jumlahHarga,
						 id	    : id	
				},
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
	}
		
	},
	
	
	formAlokasiTriwulan:function(id){
		var me = this;
		var cover = this.prefix+'_formcover';
		var volumeBarang = $("#volumeBarang"+id).val();
		var satuanHarga = $("#satuanHarga"+id).val();
		jumlahHarga = Number(volumeBarang) * Number(satuanHarga);
	if($("#jumlahHarga").val() == '' || $("#jumlahHarga").val() == '0' ){
		alert("Jumlah Harga Kosong");
	}else{
			$.ajax({
				type:'POST', 
				data : {
						  jumlahHarga : jumlahHarga,
						  id	    : id	
				},
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
		$("#"+namaBantu).text("Rp. "+rkaPPKD32_v2.formatCurrency($("#"+idPost).val())  );
		
		var jumlahHargaAlokasi = Number( jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nop + des );
		$("#jumlahHargaAlokasi").val(jumlahHargaAlokasi);
		var selisih = Number( Number($("#jumlahHargaForm").val()) -  Number( jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nop + des ) );
		$("#selisih").val(selisih);	
		$("#bantuPenjumlahan").text("Rp. "+rkaPPKD32_v2.formatCurrency(jumlahHargaAlokasi)  );		
		$("#bantuSelisih").text("Rp. "+rkaPPKD32_v2.formatCurrency(selisih)  );				
	},
	jenisPerhitunganChanged : function(){
		if($("#jenisPerhitungan").val() == "SEMI OTOMATIS" ){
			$("#buttonHitung").attr('disabled',false);
		}else{
			$("#buttonHitung").attr('disabled',true);
		}
		
	},
	jenisAlokasiChanged : function(id){
		
		
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
					rkaPPKD32_v2.formAlokasi(id);
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
					rkaPPKD32_v2.formAlokasiTriwulan(id);
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
					rkaPPKD32_v2.formAlokasi(id);
				}
			});	
		}
		
		
		
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
				rkaPPKD32_v2.hitungSelisih('bantuJan');
				rkaPPKD32_v2.hitungSelisih('bantuFeb');
				rkaPPKD32_v2.hitungSelisih('bantuApr');
				rkaPPKD32_v2.hitungSelisih('bantuMei');
				rkaPPKD32_v2.hitungSelisih('bantuJul');
				rkaPPKD32_v2.hitungSelisih('bantuAgu');
				rkaPPKD32_v2.hitungSelisih('bantuOkt');
				rkaPPKD32_v2.hitungSelisih('bantuNop');
				rkaPPKD32_v2.hitungSelisih('bantuMar');
				rkaPPKD32_v2.hitungSelisih('bantuJun');
				rkaPPKD32_v2.hitungSelisih('bantuSep');
				rkaPPKD32_v2.hitungSelisih('bantuDes');
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
				rkaPPKD32_v2.hitungSelisih('bantuMar');
				rkaPPKD32_v2.hitungSelisih('bantuJun');
				rkaPPKD32_v2.hitungSelisih('bantuSep');
				rkaPPKD32_v2.hitungSelisih('bantuDes');
			}
			
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
						idAwal : id,
					    koreksiVolumebarang : document.getElementById("volumeBarang"+id).value,
					    koreksiSatuanHarga : document.getElementById("satuanHarga"+id).value
						},
				url: this.url+'&tipe=koreksi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					delElem(cover);	
					if(resp.err == ''){
						me.Close();	
						rkaPPKD32_v2.refreshList(true);	
					}else{
						alert(resp.err);
						if(resp.err == 'Tahap Koreksi Telah Habis'){
							window.location.reload();
						}
					}
					
				 }
			});
	
					
	},
	Gruping : function(){
		var me = this;
		errmsg = '';
		
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,900,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=Gruping',
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
	newJob:function(){
		var me = this;

			
			 var cover = this.prefix+'_formcover2';

			$.ajax({
				type:'POST', 
			  	url: this.url+'&tipe=newJob',
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
	editJob:function(){
		var me = this;
	    var cover = this.prefix+'_formcover2';
		
		if($("#pekerjaan").val() == ''){
			alert("Pilih pekerjaan");
		}else{
			$.ajax({
				type:'POST', 
				data : {pekerjaan : $("#pekerjaan").val() },
			  	url: this.url+'&tipe=editJob',
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
		}
			
	},
	Close2 : function(){
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formcover2';
		
		delElem(cover);	
	},
	SaveJob: function(){
		
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:{namaPekerjaan : $("#namaPekerjaan").val()
				  },
			url: this.url+'&tipe=SaveJob',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
					$("#pekerjaan").html(resp.content.cmbPekerjaan);
						me.Close2();
						
									
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	SaveEditJob: function(){
		
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:{namaPekerjaan : $("#namaPekerjaan").val(),
				  pekerjaan : $("#pekerjaan").val(),
				  },
			url: this.url+'&tipe=SaveEditJob',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
					$("#pekerjaan").html(resp.content.cmbPekerjaan);
						me.Close2();
									
				}else{
					alert(resp.err);
				}
		  	}
		});
	},	
	setGrup: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=setGrup',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
					if(me.rkaPPKD32_v2_form==0){
						me.Close();
						me.AfterSimpan();						
					}

				}else{
					alert(resp.err);
				}
		  	}
		});
	}	
			
});
