var rkaPPKD32 = new DaftarObj2({
	prefix : 'rkaPPKD32',
	url : 'pages.php?Pg=rka-ppkd-3.2', 
	formName : 'rkaPPKD32Form',
	rkaPPKD32_form : '0',//default js rkaPPKD32
	loading: function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
/*		this.sumHalRender();*/ 

    setTimeout(function myFunction() {rkaPPKD32.refreshList(true);},1000);
		
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
		rkaPPKD32.refreshList(true);	
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
		rkaPPKD32.refreshList(true);

	},BidangAfter2: function(){
		rkaPPKD32.refreshList(true);

	},comboSKPDChanged: function(){
		rkaPPKD32.refreshList(true);

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
		}else{
				
				var skpd = $("#cmbUrusan").val()+'.'+$("#cmbBidang").val()+'.'+$("#cmbSKPD").val()+'.'+$("#cmbUnit").val()+'.'+$("#cmbSubUnit").val();
	
				var aForm = document.getElementById(this.formName);
				$.ajax({
				type:'POST', 
				data : {c1 : $("#cmbUrusan").val(),
						c  : $("#cmbBidang").val(),
						d  : $("#cmbSKPD").val(),
						e  : $("#cmbUnit").val(),
						e1 : $("#cmbSubUnit").val()
						
						},				
			  	url: this.url+'&tipe=clearTemp',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					if(resp.err == ''){
						aForm.action= 'pages.php?Pg=formRkaPpkd32&skpd='+skpd;
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
			$("#findRekening").attr('onclick',"rkaPPKD32.findRekening('"+$("#cmbJenisRKAForm").val()+"');");
			
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
		}else{
			if(errmsg ==''){ 
				var box = this.GetCbxChecked();
				var skpd = $("#cmbUrusan").val()+'.'+$("#cmbBidang").val()+'.'+$("#cmbSKPD").val()+'.'+$("#cmbUnit").val()+'.'+$("#cmbSubUnit").val();
	
				var aForm = document.getElementById(this.formName);
				$.ajax({
				  url: this.url+'&tipe=editTab',
				  type : 'POST',
				  data:$('#'+this.formName).serialize(),
				  success: function(data) {
						var resp = eval('(' + data + ')');
						if(resp.err == "") {
							var kodeRek = resp.content.kodeRek;
							aForm.action= 'pages.php?Pg=formRkaPpkd32&rek='+kodeRek+'&skpd='+skpd;
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
		}else{
			if(errmsg ==''){ 
				var box = this.GetCbxChecked();
				var skpd = $("#cmbUrusan").val()+'.'+$("#cmbBidang").val()+'.'+$("#cmbSKPD").val()+'.'+$("#cmbUnit").val()+'.'+$("#cmbSubUnit").val();
	
				$.ajax({
				  url: this.url+'&tipe=Remove',
				  type : 'POST',
				  data:$('#'+this.formName).serialize(),
				  success: function(data) {
						var resp = eval('(' + data + ')');
						if(resp.err == "") {
							rkaPPKD32.refreshList(true);
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
						rkaPPKD32.refreshList(true);	
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
		$("#spanVolumeBarang"+id).html("<input type='text' name='volumeBarang"+id+"' id='volumeBarang"+id+"' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='rkaPPKD32.bantuVolumeBarang("+id+")'   style='text-align: right;'>  <span id='bantuVolumeBarang"+id+"' ></span>");
		$("#spanSatuanHarga"+id).html("<input type='text' name='satuanHarga"+id+"' id='satuanHarga"+id+"' onkeypress='return event.charCode >= 48 && event.charCode <= 57' onkeyup='rkaPPKD32.bantuSatuanHarga("+id+")'    style='text-align: right;'>  <span id='bantuSatuanHarga"+id+"' ></span>");
		$("#buttonSubmitKoreksi"+id).html("<img src='datepicker/save.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkaPPKD32.submitKoreksi('"+id+"');></img>&nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkaPPKD32.refreshList(true);></img>");
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
						rkaPPKD32.refreshList(true);	
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
		document.getElementById('bantuVolumeBarang'+id).innerHTML = rkaPPKD32.formatCurrency2($("#volumeBarang"+id).val());
		var hasilKali = Number($("#volumeBarang"+id).val()) * Number($("#satuanHarga"+id).val()) ; 
		$("#spanJumlahHarga"+id).text(rkaPPKD32.formatCurrency(hasilKali));
	},
	bantuSatuanHarga : function(id){
		document.getElementById('bantuSatuanHarga'+id).innerHTML =  rkaPPKD32.formatCurrency($("#satuanHarga"+id).val());
		var hasilKali = Number($("#volumeBarang"+id).val()) * Number($("#satuanHarga"+id).val()) ; 
		$("#spanJumlahHarga"+id).text(rkaPPKD32.formatCurrency(hasilKali));
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
		$("#bantu").text("Rp. "+rkaPPKD32.formatCurrency($("#hargaSatuan").val())  );
		
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
					if(me.rkaPPKD32_form==0){
						me.Close();
						me.AfterSimpan();						
					}else{
						me.Close();
						barang.refreshComborkaPPKD32();
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
					if(me.rkaPPKD32_form==0){
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
					if(me.rkaPPKD32_form==0){
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
		
		
	}	
});
