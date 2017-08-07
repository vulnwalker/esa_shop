var formRkaSkpd221SKPD = new SkpdCls({
	prefix : 'formRkaSkpd221SKPD', formName:'formRkaSkpd221Form', kolomWidth:120,
	
	a : function(){
		alert('dsf');
	},
});

var formRkaSkpd221 = new DaftarObj2({
	prefix : 'formRkaSkpd221',
	url : 'pages.php?Pg=formRkaSkpd221', 
	formName : 'formRkaSkpd221Form',
	satuan_form : '0',//default js satuan
	
	
	
	loading: function(){

		this.topBarRender();
		this.filterRender();
		formRkaSkpd221.refreshList(true);
	
	},	
	
	closeTab : function(){
				
		if(document.getElementById('q').disabled == true){
			$.ajax({
				type:'POST', 
				url: this.url+'&tipe=clear',
				success: function(data) {	
					$("#bk").val('');
					$("#ck").val('');
					$("#p").val('');
					$("#q").val('');
					$("#program").val('');
					formRkaSkpd221.refreshList(true);			
				}
			});
		}else{
			window.opener.location.reload();
			var ww = window.open(window.location, '_self');
			ww.close();
		}
		 		
		
		
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
	newJob:function(){
		var me = this;

			
			 var cover = this.prefix+'_formcover';

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
	    var cover = this.prefix+'_formcover';
		
		if($("#o1").val() == ''){
			alert("Pilih pekerjaan");
		}else{
			$.ajax({
				type:'POST', 
				data : {o1 : $("#o1").val() },
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
	
	newSatuan:function(){
		var me = this;

			
			 var cover = this.prefix+'_formcover';

			$.ajax({
				type:'POST', 
			  	url: this.url+'&tipe=newSatuan',
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
		$("#"+namaBantu).text("Rp. "+formRkaSkpd221.formatCurrency($("#"+idPost).val())  );
		
		var jumlahHargaAlokasi = Number( jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nop + des );
		$("#jumlahHargaAlokasi").val(jumlahHargaAlokasi);
		var selisih = Number( Number($("#jumlahHargaForm").val()) -  Number( jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nop + des ) );
		$("#selisih").val(selisih);	
		$("#bantuPenjumlahan").text("Rp. "+formRkaSkpd221.formatCurrency(jumlahHargaAlokasi)  );		
		$("#bantuSelisih").text("Rp. "+formRkaSkpd221.formatCurrency(selisih)  );				
	},
	formAlokasi:function(){
		var me = this;
		var cover = this.prefix+'_formcover';

	if($("#jumlahHarga").val() == '' || $("#jumlahHarga").val() == '0' ){
		alert("Jumlah Harga Kosong");
	}else{
			$.ajax({
				type:'POST', 
				data : {
						 jumlahHarga : $("#jumlahHarga").val()	
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
	
	
	formAlokasiTriwulan:function(){
		var me = this;
		var cover = this.prefix+'_formcover';

	if($("#jumlahHarga").val() == '' || $("#jumlahHarga").val() == '0' ){
		alert("Jumlah Harga Kosong");
	}else{
			$.ajax({
				type:'POST', 
				data : {
						 jumlahHarga : $("#jumlahHarga").val()	
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
	

	
	
	formRincianVolume:function(){
		var me = this;
		var cover = this.prefix+'_formcover';

		if($("#volumeBarang").val() == '' || $("#volumeBarang").val() == '0' ){
			alert("Volume Kosong");
		}else{
		
				$.ajax({
					type:'POST', 
					data : {
							 volumeRek : $("#volumeBarang").val()	
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
	
	
	setSatuanHarga:function(){
		var arrayKodeBarang = $("#kodeBarang").val().split('.');
		
		var f1 = arrayKodeBarang[0];
		var f2 = arrayKodeBarang[1];
		var f = arrayKodeBarang[2];
		var g = arrayKodeBarang[3];
		var h = arrayKodeBarang[4];
		var i = arrayKodeBarang[5];
		var j = arrayKodeBarang[6];
		
			$.ajax({
					type:'POST', 
				  	url: this.url+'&tipe=setSatuanHarga',
					data : {
								f1 	: f1,
								f2 	: f2,
								f	: f,
								g 	: g,
								h	: h,
								i	: i,
								j	: j
					},
				  	success: function(data) {		
						var resp = eval('(' + data + ')');
						if(resp.err == ''){
							$("#hargaSatuan").val(resp.content.harga);
							$("#bantuSatuanHarga").text(resp.content.bantu);
							$("#jumlahHarga").val($("#hargaSatuan").val() * $("#volumeBarang").val());
							$("#bantuJumlahHarga").text("Rp. "+formRkaSkpd221.formatCurrency($("#jumlahHarga").val()));
						}else{
							alert(resp.err);
						}			
				  	}
			});
		
	},
	bantu : function(){
		$("#bantuSatuanHarga").text("Rp. "+formRkaSkpd221.formatCurrency($("#hargaSatuan").val())  );
		$("#jumlahHarga").val($("#hargaSatuan").val() * $("#volumeBarang").val());
		$("#bantuJumlahHarga").text("Rp. "+formRkaSkpd221.formatCurrency($("#jumlahHarga").val()));
		$("#teralokasi").val('');
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
	SaveJob: function(){
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var arrayE = $("#unit").val().split('.');
		var arrayE1 = $("#subunit").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		var e = arrayE[0]
		var e1 = arrayE1[0];	
		var bk = $("#bk").val();
		var ck = $("#ck").val();
		var p = $("#hiddenP").val();
		var q = $("#q").val();
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:{namaPekerjaan : $("#namaPekerjaan").val(),
				  c1 : c1,
				  c : c,
						d : d,
						e : e,
						e1 : e1,
						bk : bk,
						ck : ck,
						p : p,
						q : q,
						kodeRekening : $("#kodeRekening").val()
				  },
			url: this.url+'&tipe=SaveJob',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
					$("#o1").html(resp.content.cmbPekerjaan);
					$("#noUrut").val(resp.content.left_urut);
					$("#nomorUrut").val(resp.content.urut);
						me.Close();
						//me.AfterSimpan();	
									
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	SaveEditJob: function(){
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var arrayE = $("#unit").val().split('.');
		var arrayE1 = $("#subunit").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		var e = arrayE[0]
		var e1 = arrayE1[0];	
		var bk = $("#bk").val();
		var ck = $("#ck").val();
		var p = $("#hiddenP").val();
		var q = $("#q").val();
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:{namaPekerjaan : $("#namaPekerjaan").val(),
				  o1 : $("#o1").val(),
				  c1 : c1,
				  c : c,
						d : d,
						e : e,
						e1 : e1,
						bk : bk,
						ck : ck,
						p : p,
						q : q,
						kodeRekening : $("#kodeRekening").val()
				  },
			url: this.url+'&tipe=SaveEditJob',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
					$("#o1").html(resp.content.cmbPekerjaan);
					$("#noUrut").val(resp.content.left_urut);
					$("#nomorUrut").val(resp.content.urut);
						me.Close();
									
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
	
	SaveSatuan: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:{namaPekerjaan : $("#namaSatuan").val()},
			url: this.url+'&tipe=SaveSatuan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				if(resp.err==''){
						var myOptions = {
						    val1 : $("#namaSatuan").val()
						};
						$.each(myOptions, function(val, text) {
						    $('#satuan').append( new Option(text,val) );
						});
						me.Close();
									
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	setNoUrut:function(){
		
		$.ajax({
		  	url: this.url+'&tipe=cekNoUrut',
		 	type:'POST', 
			data: {noPekerjaan : $("#o1").val()
					}, 
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				$("#nomorUrut").val(resp.content.noUrut);
				$("#noUrut").val(resp.content.leftUrut);
		  	}
		});
		
	},
    CariProgram: function(idRenja){
		var me = this;
		popupProgramRKBMD.idRenja = idRenja;
		popupProgramRKBMD.windowShow();	
		
	},
	CariBarang: function(kodeSKPD){
		var me = this;
		popupBarangPemeliharaan.skpd = kodeSKPD;
		popupBarangPemeliharaan.windowShow();	
	
		
		
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
				formRkaSkpd221.hitungSelisih('bantuJan');
				formRkaSkpd221.hitungSelisih('bantuFeb');
				formRkaSkpd221.hitungSelisih('bantuApr');
				formRkaSkpd221.hitungSelisih('bantuMei');
				formRkaSkpd221.hitungSelisih('bantuJul');
				formRkaSkpd221.hitungSelisih('bantuAgu');
				formRkaSkpd221.hitungSelisih('bantuOkt');
				formRkaSkpd221.hitungSelisih('bantuNop');
				formRkaSkpd221.hitungSelisih('bantuMar');
				formRkaSkpd221.hitungSelisih('bantuJun');
				formRkaSkpd221.hitungSelisih('bantuSep');
				formRkaSkpd221.hitungSelisih('bantuDes');
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
				formRkaSkpd221.hitungSelisih('bantuMar');
				formRkaSkpd221.hitungSelisih('bantuJun');
				formRkaSkpd221.hitungSelisih('bantuSep');
				formRkaSkpd221.hitungSelisih('bantuDes');
			}
			
		}
		
		
	},
	jenisPerhitunganChanged : function(){
		if($("#jenisPerhitungan").val() == "SEMI OTOMATIS" ){
			$("#buttonHitung").attr('disabled',false);
		}else{
			$("#buttonHitung").attr('disabled',true);
		}
		
	},
	jenisAlokasiChanged : function(){
		/*if($("#jenisAlokasi").val() == "BULANAN"){
			$("#jan").attr('readonly',false);
			$("#feb").attr('readonly',false);
			$("#mar").attr('readonly',false);
			$("#apr").attr('readonly',false);
			$("#mei").attr('readonly',false);
			$("#jun").attr('readonly',false);
			$("#jul").attr('readonly',false);
			$("#agu").attr('readonly',false);
			$("#sep").attr('readonly',false);
			$("#okt").attr('readonly',false);
			$("#nop").attr('readonly',false);
			$("#des").attr('readonly',false);
		}else if($("#jenisAlokasi").val() == "TRIWULAN"){
			$("#jan").attr('readonly',true);
			$("#feb").attr('readonly',true);
			$("#mar").attr('readonly',false);
			$("#apr").attr('readonly',true);
			$("#mei").attr('readonly',true);
			$("#jun").attr('readonly',false);
			$("#jul").attr('readonly',true);
			$("#agu").attr('readonly',true);
			$("#sep").attr('readonly',false);
			$("#okt").attr('readonly',true);
			$("#nop").attr('readonly',true);
			$("#des").attr('readonly',false);
			
			$("#jan").val('');
			$("#feb").val('');
			$("#mar").val('');
			$("#apr").val('');
			$("#mei").val('');
			$("#jun").val('');
			$("#jul").val('');
			$("#agu").val('');
			$("#sep").val('');
			$("#okt").val('');
			$("#nop").val('');
			$("#des").val('');
		}else{
			$("#jan").attr('readonly',true);
			$("#feb").attr('readonly',true);
			$("#mar").attr('readonly',true);
			$("#apr").attr('readonly',true);
			$("#mei").attr('readonly',true);
			$("#jun").attr('readonly',true);
			$("#jul").attr('readonly',true);
			$("#agu").attr('readonly',true);
			$("#sep").attr('readonly',true);
			$("#okt").attr('readonly',true);
			$("#nop").attr('readonly',true);
			$("#des").attr('readonly',true);
		}*/
		
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
					formRkaSkpd221.formAlokasi();
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
					formRkaSkpd221.formAlokasiTriwulan();
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
					formRkaSkpd221.formAlokasi();
				}
			});	
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
	hapus : function(id){
		$.ajax({
				type:'POST', 
				data:{id : id},
				url: this.url+'&tipe=hapus2',
				success: function(data) {	
					formRkaSkpd221.refreshList(true);
					
				}
			});	
	},
	cancelEdit : function(){
		formRkaSkpd221.refreshList(true);
		setTimeout(function myFunction() {formRkaSkpd221.refreshList(true);},1000);
	},
	
	programChanged : function(){
		var arrayP = $("#p").val().split('.');;
		var bk = arrayP[0];
		var ck = arrayP[1];
		var hiddenP = arrayP[2];
	
		$("#bk").val(bk);
		$("#ck").val(ck);
		$("#hiddenP").val(hiddenP);
		formRkaSkpd221.refreshList(true);
	},
	edit : function(id){
		$.ajax({
				type:'POST', 
				data:{id : id},
				url: this.url+'&tipe=edit',
				success: function(data) {	
					var resp = eval('(' + data + ')');	
					if(resp.content.kunci == '0'){
						$("#rincianPerhitungan").attr('readonly',false);
						$("#rincianPerhitungan").val(resp.content.rincianPerhitungan2);
					}else{
						$("#rincianPerhitungan").attr('readonly',true);
						$("#rincianPerhitungan").val(resp.content.rincianPerhitungan);
					}
						$("#volumeBarang").val(resp.content.volume);
						if(resp.content.kodeRekening.length > 12){
							$("#kodeRekening").val('');
						}else{
							$("#kodeRekening").val(resp.content.kodeRekening);
						}
						if(resp.content.jenis_alokasi_kas == "TRIWULAN"){
							$("#tombolAlokasi").attr("onclick","formRkaSkpd221.formAlokasiTriwulan();");
						}else{
							$("#tombolAlokasi").attr("onclick","formRkaSkpd221.formAlokasi();");
						}
						$("#namaRekening").val(resp.content.namaRekening);
						$("#bk").val(resp.content.bk);
						$("#ck").val(resp.content.ck);
						$("#hiddenP").val(resp.content.hiddenP);
						$("#p").html(resp.content.cmbProgram);
						$("#q").html(resp.content.cmbKegiatan);
						$("#p").attr('disabled',true);
						$("#q").attr('disabled',true);
						$("#o1").html(resp.content.cmbPekerjaan);
						/*$("#noUrut").val(resp.content.noUrut);*/
						$("#hargaSatuan").val(resp.content.hargaSatuan);
						$("#jumlahHarga").val(resp.content.jumlahHarga);
						$("#btsave").attr('href','javascript:formRkaSkpd221.saveEdit('+id+')');
						$("#btcancel").attr('href','javascript:formRkaSkpd221.cancelEdit()');
						$("#kodeBarang").val(resp.content.kodeBarang);
						if(resp.content.statusAlokasi == 'true'){
							$("#teralokasi").val('true');
							$("#rincianVolume").val('true');
						}
						$("#satuan").val(resp.content.satuan);
						formRkaSkpd221.setNoUrut();
				}
			});	
		
		
	},
	saveEdit : function(id){
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var arrayE = $("#unit").val().split('.');
		var arrayE1 = $("#subunit").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		var e = arrayE[0]
		var e1 = arrayE1[0];
		$.ajax({
				type:'POST', 
				data:{ id : id,
					   c1 : c1,
					   c : c,
					   d : d,
					   e : e,
					   e1 : e1,
					   bk : $("#bk").val(),
					   ck : $("#ck").val(),
					   p : $("#hiddenP").val(),
					   q : $("#q").val(),
					   kodeRekening : $("#kodeRekening").val(),
					   namaRekening : $("#namaRekening").val(),
					   noPekerjaan : $("#o1").val(),
					   kodeBarang : $("#kodeBarang").val(),
					   rincianPerhitungan : $("#rincianPerhitungan").val(),
					   volumeRek : $("#volumeBarang").val(),
					   satuanRek : $("#satuan").val(),
					   hargaSatuan : $("#hargaSatuan").val(),
					   teralokasi : $("#teralokasi").val(),
					   rincianVolume : $("#rincianVolume").val(),
					   noUrutPekerjaan : $("#nomorUrut").val(),
					   o1Html : escape($("#o1").html()),
					  },
				url: this.url+'&tipe=saveEdit',
				success: function(data) {	
					var resp = eval('(' + data + ')');	
					if(resp.err == ''){
						$("#nomorUrut").val('');
						$("#satuan").val('');
						$("#hargaSatuan").val('');
						$("#jumlahHarga").val('');
						formRkaSkpd221.refreshList(true);
						setTimeout(function myFunction() {
							$("#kodeRekening").val(resp.content.kodeRekening);
							$("#namaRekening").val(resp.content.namaRekening);
							$("#o1").html(unescape(resp.content.o1Html));
							formRkaSkpd221.setNoUrut();
							},1000);
					}else{
						if(resp.err == "Lengkapi Alokasi"){
							formRkaSkpd221.formAlokasi();
						}else if(resp.err == "Lengkapi Rincian Volume"){
							formRkaSkpd221.formRincianVolume();
						}else{
							alert(resp.err);
						}
						
					}
					
					
				}
			});			
	},
	
	Simpan : function(){
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var arrayE = $("#unit").val().split('.');
		var arrayE1 = $("#subunit").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		var e = arrayE[0]
		var e1 = arrayE1[0];
		$.ajax({
				type:'POST', 
				data:{ c1 : c1,
					   c : c,
					   d : d,
					   e : e,
					   e1 : e1,
					   bk : $("#bk").val(),
					   ck : $("#ck").val(),
					   p : $("#hiddenP").val(),
					   q : $("#q").val(),
					   kodeRekening : $("#kodeRekening").val(),
					   namaRekening : $("#namaRekening").val(),
					   noPekerjaan : $("#o1").val(),
					   kodeBarang : $("#kodeBarang").val(),
					   rincianPerhitungan : $("#rincianPerhitungan").val(),
					   volumeRek : $("#volumeBarang").val(),
					   satuanRek : $("#satuan").val(),
					   hargaSatuan : $("#hargaSatuan").val(),
					   teralokasi : $("#teralokasi").val(),
					   rincianVolume : $("#rincianVolume").val(),
					   noUrutPekerjaan : $("#nomorUrut").val(),
					   o1Html : escape($("#o1").html()),
					  },
				url: this.url+'&tipe=Simpan2',
				success: function(data) {	
					var resp = eval('(' + data + ')');	
					if(resp.err == ''){
						$("#nomorUrut").val('');
						$("#satuan").val('');
						$("#hargaSatuan").val('');
						$("#jumlahHarga").val('');
						formRkaSkpd221.refreshList(true);
						setTimeout(function myFunction() {
						$("#kodeRekening").val(resp.content.kodeRekening);
						$("#namaRekening").val(resp.content.namaRekening);
						$("#o1").html(unescape(resp.content.o1Html));
							formRkaSkpd221.setNoUrut();
							},1000);
					}else{
						if(resp.err == "Lengkapi Alokasi"){
							formRkaSkpd221.formAlokasi();
							
						}else if(resp.err == "Lengkapi Rincian Volume"){
							formRkaSkpd221.formRincianVolume();
						}else{
							alert(resp.err);
						}
					}
					
					
				}
			});			
	},
	SaveAlokasi : function(){
		
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
						jumlahHargaAlokasi : $("#jumlahHargaAlokasi").val()	},
				url: this.url+'&tipe=SaveAlokasi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					delElem(cover);		
					if(resp.err==''){
						me.Close();	
						$("#teralokasi").val('true');	
					}else{
						alert(resp.err);
					}
			  	}
			});
	
					
	},
	
	SaveRincianVolume : function(){
		
			var me= this;	
			this.OnErrorClose = false;	
			document.body.style.overflow='hidden';
			var cover = this.prefix+'_formsimpan';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:{  volumeRek : $("#volumeRek").val(),
						satuan1 : $("#satuanSatuan1").val(),
						satuan2 : $("#satuanSatuan2").val(),
						satuan3 : $("#satuanSatuan3").val(),
						satuanTotal : $("#satuanVolume").val(),
						jumlah1 : $("#jumlah1").val(),
						jumlah2 : $("#jumlah2").val(),
						jumlah3 : $("#jumlah3").val(),
						jumlahTotal : $("#jumlah4").val(),	
							
						},
				url: this.url+'&tipe=SaveRincianVolume',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					delElem(cover);		
					if(resp.err==''){
						me.Close();	
						$("#rincianVolume").val('true');	
					}else{
						alert(resp.err);
					}
			  	}
			});
	
					
	},
		resetRincian : function(){
		$("#rincianVolume").val('');
		$("#teralokasi").val('');
		formRkaSkpd221.bantu();
		
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
	finish : function(){
	
		$.ajax({
				type:'POST', 
				data:{test : 'test'},
				url: this.url+'&tipe=finish',
				success: function(data) {
					var resp = eval('(' + data + ')');	
					if(resp.err == ""){
						window.opener.location.reload();
						var ww = window.open(window.location, '_self');
						ww.close();
					}else{
						alert(resp.err);
					}
					
					
				}
			});	
				
	}
		
});
