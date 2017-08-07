var rkbmd_pemeliharaan_v2SKPD = new SkpdCls({
	prefix : 'rkbmd_pemeliharaan_v2SKPD', formName:'rkbmd_pemeliharaan_v2Form', kolomWidth:120,
	
	a : function(){
		alert('dsf');
	},
});

var rkbmd_pemeliharaan_v2 = new DaftarObj2({
	prefix : 'rkbmd_pemeliharaan_v2',
	url : 'pages.php?Pg=rkbmd_pemeliharaan_v2', 
	formName : 'rkbmd_pemeliharaan_v2Form',
	satuan_form : '0',//default js satuan
	
	
	
	loading: function(){

		this.topBarRender();
		this.filterRender();
		
	
	},	
	CekAda : function(){
			$.ajax({
				type:'POST', 
				data:{urusan : $("#urusan").val(),
					  bidang : $("#bidang").val(),
					  skpd   : $("#skpd").val(),
					  unit   : $("#unit").val(),
					  subunit : $("#subunit").val(),
					  bk     : $("#bk").val(),
					  ck 	 : $("#ck").val(),
					  dk     : $("#dk").val(),
					  p      : $("#p").val(),
					  q      : $("#q").val()
					 },
				url: this.url+'&tipe=CekAda',
				success: function(data) {	
					var resp = eval('(' + data + ')');	
					if(resp.content.status == 'ada'){
						rkbmd_pemeliharaan_v2.refreshList(true);	
					}		
								
				}
			});
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
					rkbmd_pemeliharaan_v2.refreshList(true);			
				}
			});
		}else{
			window.opener.location.reload();
			var ww = window.open(window.location, '_self');
			ww.close();
		}
		 		
		
		
	},
	

	BidangAfterform: function(){
		var me = this;
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		$.ajax({
		  url: this.url+'&tipe=BidangAfterForm',
		  type : 'POST',
		  data:{ fmSKPDBidang: c,
		  		 fmSKPDUrusan: c1,
		  		 fmSKPDskpd: d,
		  		 fmSKPDUnit: $("#cmbUnitForm").val(),
		  		 fmSKPDSubUnit: $("#cmbSubUnitForm").val() },
		  success: function(data) {
			var resp = eval('(' + data + ')');	
				document.getElementById('cmbSubUnitForm').innerHTML=resp.content.subunit;
		  }
		});

	},
	
	
	rincianpenerimaan: function(){
	    
		$('.datepicker').datepicker({
						    dateFormat: 'dd-mm-yy',
							showAnim: 'slideDown',
						    inline: true,
							showOn: "button",
     						buttonImage: "images/calendar.gif",
      						buttonImageOnly: true,
							changeMonth: true,
      						changeYear: true,
							yearRange: "+1:+5",
							buttonText : '',
							defaultDate: +365
		});	
	
		
		rkbmd_pemeliharaan_v2.refreshList(true);
		
	},
    CariProgram: function(idRenja){
		var me = this;
		popupProgramRKBMD_v2.idRenja = idRenja;
		popupProgramRKBMD_v2.kategori = "PEMELIHARAAN";
		popupProgramRKBMD_v2.windowShow();	
		
	},
	CariBarang: function(kodeSKPD){
		var me = this;
		if($("#q").val() == ''){
			alert("Pilih Program dan Kegiatan");
		}else{
			popupBarangPemeliharaan_v2.skpd = kodeSKPD;
			popupBarangPemeliharaan_v2.windowShow();	
		}
		
	
		/*alert(kodeSKPD);*/
		
		
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
		  	}
		});
	},
	
	EDIT:function(){
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=EDIT',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						alert("Data Tersimpan");
						
							window.opener.location.reload();
							var ww = window.open(window.location, '_self');
		 					ww.close();


					}else{
						alert(resp.err);
					}
				}
			});	
		
	},
	
	Simpan: function(){
		$.ajax({
				type:'POST', 
				data:{urusan : $("#urusan").val(),
					  bidang : $("#bidang").val(),
					  skpd   : $("#skpd").val(),
					  unit   : $("#unit").val(),
					  subunit : $("#subunit").val(),
					  bk     : $("#bk").val(),
					  ck 	 : $("#ck").val(),
					  dk     : $("#dk").val(),
					  p      : $("#p").val(),
					  q      : $("#q").val(),
					  kodeBarang : $("#kodeBarang").val(),
					  keterangan : $("#keterangan").val(),
					  jumlah : $("#jumlah").val(),
					  jumlahKebutuhanMaksimal : $("#jumlahKebutuhanMaksimal").val(),
					  jumlahKebutuhanOptimal : $("#jumlahKebutuhanOptimal").val(),
					  jumlahKebutuhanRiil : $("#jumlahKebutuhanRiil").val(),
					  tahunAnggaran : $("#tahunAnggaran").val()
					 },
				url: this.url+'&tipe=Simpan',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						document.getElementById("findProgram").disabled = true;	
						document.getElementById("q").innerHTML= resp.content.q;		
						document.getElementById("q").disabled = true;	
						/*document.getElementById("keterangan").disabled = true;*/
						rkbmd_pemeliharaan_v2.tabelPemeliharaan(1);
						document.getElementById('btsave').href = 'javascript:rkbmd_pemeliharaan_v2.subSimpan();';
						document.getElementById('btcancel').href = 'javascript:rkbmd_pemeliharaan_v2.subCancel();'; 
						document.getElementById('findBarang').disabled = true;
						document.getElementById('satuanBarang').readOnly = true;
						$("#tbl_pemeliharaan").show();
						if(resp.content.hubla != 0){
							setTimeout(function(){
							 rkbmd_pemeliharaan_v2.subSubEdit(resp.content.hubla);
							}, 500);
							 
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
	hapus : function(id){
		$.ajax({
				type:'POST', 
				data:{id : id},
				url: this.url+'&tipe=subDelete',
				success: function(data) {	
					rkbmd_pemeliharaan_v2.refreshList(true);
				}
			});	
	},subHapus : function(id){
		$.ajax({
				type:'POST', 
				data:{id : id},
				url: this.url+'&tipe=subHapus',
				success: function(data) {	
					var resp = eval('(' + data + ')');	
					if(resp.content == 'refresh'){
						rkbmd_pemeliharaan_v2.refreshList(true);
					}else{
						location.reload();
					}
					
				}
			});	
	},tabelPemeliharaan: function(status){
		$.ajax({
			type:'POST', 
			data:{status : status},
			url: this.url+'&tipe=tabelPemeliharaan',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('tbl_pemeliharaan').innerHTML = resp.content.tabel;
					document.getElementById('totalbelanja23').innerHTML = resp.content.jumlah;
					
				}else{
					alert(resp.err);
				}
			}
		});	
		
	},
	newPemeliharaan: function(){
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var arrayE = $("#unit").val().split('.');
		var arrayE1 = $("#subunit").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		var e = arrayE[0];
		var e1 = arrayE1[0];
		$.ajax({
			type:'POST', 
			data:{c1 		 : c1,
				  c 		 : c,
				  d 		 : d,
				  e  		 : e,
				  e1 		 : e1,
				  bk		 : $("#bk").val(),
				  ck		 : $("#ck").val(),
				  dk		 : '0',
				  p			 : $("#p").val(),
				  q			 : $("#q").val(),
				  kodeBarang : $("#kodeBarang").val(),
				  satuan     : $("#satuanBarang").val()
				  },
			url: this.url+'&tipe=newRowPemeliharaan',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
						var idnya = resp.content.id;
						rkbmd_pemeliharaan_v2.tabelPemeliharaan(idnya);
						
				}else{
					alert(resp.err);
				}
			}
		});	
	},
	subSubSave: function(id){
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var arrayE = $("#unit").val().split('.');
		var arrayE1 = $("#subunit").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		var e = arrayE[0];
		var e1 = arrayE1[0];
		$.ajax({
			type:'POST', 
			data:{c1 		 : c1,
				  c 		 : c,
				  d 		 : d,
				  e  		 : e,
				  e1 		 : e1,
				  bk		 : $("#bk").val(),
				  ck		 : $("#ck").val(),
				  dk		 : '0',
				  p			 : $("#p").val(),
				  q			 : $("#q").val(),
				  kodeBarang : $("#kodeBarang").val(),
				  id 		 : id,
				  idJenisPemeliharaan : $("#jenisPemeliharaan"+id).val(),
				  uraianPemeliharaan  : $("#uraianPemeliharaan"+id).val(),
				  volumeBarang		  : $("#jumlah"+id).val(),
				  keterangan		  : $("#keterangan"+id).val(),
				  limit				  : $("#jumlah").val()
				  
				  },
			url: this.url+'&tipe=subSubSave',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
						rkbmd_pemeliharaan_v2.tabelPemeliharaan(1);
				}else{
					alert(resp.err);
				}
			}
		});	
	},
	
	subSimpan: function(){
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var arrayE = $("#unit").val().split('.');
		var arrayE1 = $("#subunit").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		var e = arrayE[0];
		var e1 = arrayE1[0];
		$.ajax({
			type:'POST', 
			data:{c1 		 : c1,
				  c 		 : c,
				  d 		 : d,
				  e  		 : e,
				  e1 		 : e1,
				  bk		 : $("#bk").val(),
				  ck		 : $("#ck").val(),
				  dk		 : '0',
				  p			 : $("#p").val(),
				  q			 : $("#q").val(),
				  kodeBarang : $("#kodeBarang").val(),
				  satuan    : $("#satuanBarang").val(),
				  
				  },
			url: this.url+'&tipe=subSimpan',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
						
						/*document.getElementById("q").innerHTML= resp.content.q;		
						document.getElementById("q").disabled = true;
						document.getElementById("findProgram").disabled = true;	
						$("#tbl_pemeliharaan").hide();
						$("#findBarang").attr('disabled',false);
						$("#kodeBarang").val("");
						$("#namaBarang").val("");
						$("#jumlah").val("");
						$("#satuanBarang").val("");
						$("#baik").val("");
						$("#kurangBaik").val("");
						$("#rusakBerat").val("");*/
						rkbmd_pemeliharaan_v2.refreshList(true);
				}else{
					alert(resp.err);
				}
			}
		});	
	},
	
	moveBack: function(){
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var arrayE = $("#unit").val().split('.');
		var arrayE1 = $("#subunit").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		var e = arrayE[0];
		var e1 = arrayE1[0];
		$.ajax({
			type:'POST', 
			data:{c1 		 : c1,
				  c 		 : c,
				  d 		 : d,
				  e  		 : e,
				  e1 		 : e1,
				  bk		 : $("#bk").val(),
				  ck		 : $("#ck").val(),
				  dk		 : '0',
				  p			 : $("#p").val(),
				  q			 : $("#q").val(),
				  satuan 	 : $("#satuanBarang").val(),
				  kodeBarang : $("#kodeBarang").val(),
				  
				  },
			url: this.url+'&tipe=moveBack',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
						rkbmd_pemeliharaan_v2.refreshList(true);
				}else{
					alert(resp.err);
				}
			}
		});	
	},
	
	subSubDel: function(id){
		$.ajax({
			type:'POST', 
			data:{id : id},
			url: this.url+'&tipe=subSubDelete',
			success: function(data) {	
				rkbmd_pemeliharaan_v2.tabelPemeliharaan(1);
			}
		});	
	},
	subSubEdit: function(id){
		/*rkbmd_pemeliharaan_v2.tabelPemeliharaan(1);*/
		$("#linkAtasButton").attr('href','javascript:rkbmd_pemeliharaan_v2.subSubCancel()');
		$("#gambarAtasButton").attr('src','datepicker/cancel.png');
		$("#jenisPemeliharaan"+id).attr('disabled',false);
		$("#uraianPemeliharaan"+id).attr('readonly',false);
		$("#jumlah"+id).attr('readonly',false);
		$("#keterangan"+id).attr('readonly',false);
		$("#action"+id).attr('src','datepicker/save.png');
		$("#action"+id).attr('onclick','rkbmd_pemeliharaan_v2.subSubSave('+id+')');
		
	},
	subSubCancel: function(id){
		$.ajax({
			type:'POST', 
			data:{id : id},
			url: this.url+'&tipe=subSubCancel',
			success: function(data) {	
				rkbmd_pemeliharaan_v2.tabelPemeliharaan(1);
			}
		});	
	},
	moveList: function(id){
		$.ajax({
			type:'POST', 
			data:{id : id},
			url: this.url+'&tipe=moveList',
			success: function(data) {	
				var resp = eval('(' + data + ')');	
				document.getElementById('btsave').href = 'javascript:rkbmd_pemeliharaan_v2.moveBack();';
				document.getElementById('btcancel').href = 'javascript:rkbmd_pemeliharaan_v2.subCancel();'; 
				rkbmd_pemeliharaan_v2.tabelPemeliharaan(1);
				$("#tbl_pemeliharaan").show();
				$("#kodeBarang").val(resp.content.kodeBarang);
				$("#namaBarang").val(resp.content.namaBarang);
				$("#baik").val(resp.content.baik);
				$("#kurangBaik").val(resp.content.kurangBaik);
				$("#rusakBerat").val(resp.content.rusakBerat);
				$("#satuanBarang").val(resp.content.satuan);
				$("#jumlah").val(resp.content.jumlah);
				$("#satuanBarang").attr('readonly',true);
				document.getElementById('findBarang').disabled = true;
			}
		});	
	},
	
	edit : function(id){
		$.ajax({
				type:'POST', 
				data:{id : id},
				url: this.url+'&tipe=subShowEdit',
				success: function(data) {	
					var resp = eval('(' + data + ')');	
					$("#kodeBarang").val(resp.content.kodeBarang);
					$("#namaBarang").val(resp.content.namaBarang);
					$("#satuan").val(resp.content.satuan);
					$("#jumlahKebutuhanRiil").val(resp.content.jumlahKebutuhanOptimal);
					$("#jumlahKebutuhanMaksimal").val(resp.content.jumlahKebutuhanMaksimal);
					$("#jumlahKebutuhanOptimal").val(resp.content.jumlahKebutuhanOptimal);
					$("#jumlah").val(resp.content.jumlah);
					$("#satuanBarang").val(resp.content.satuan);
					$("#keterangan").val(resp.content.keterangan);
					document.getElementById('btsave').href = 'javascript:rkbmd_pemeliharaan_v2.subEdit('+id+');';
					document.getElementById('btcancel').href = 'javascript:rkbmd_pemeliharaan_v2.subCancel();'; 
					document.getElementById('findBarang').disabled = true;
				}
			});	
		
		
	},
	subCancel : function(){
		$.ajax({
				type:'POST', 
				data:{kodeBarang : $("#kodeBarang").val()},
				url: this.url+'&tipe=subCancel',
				success: function(data) {	
					var resp = eval('(' + data + ')');	
					$("#kodeBarang").val('');
					$("#namaBarang").val('');
					$("#satuan").val('');
					$("#baik").val('');
					$("#kurangBaik").val('');
					$("#rusakBerat").val('');
					$("#tbl_pemeliharaan").hide();
					$("#satuanBarang").val('');
					$("#keterangan").val('');
					$("#jumlah").val('');
					document.getElementById('btsave').href = 'javascript:rkbmd_pemeliharaan_v2.Simpan();';
					document.getElementById('btcancel').href = 'javascript:rkbmd_pemeliharaan_v2.closeTab();'; 
					document.getElementById('findBarang').disabled = false;
					document.getElementById('satuanBarang').readOnly= false;
				}
			});	
	
	
					
	},
	subEdit : function(id){
			$.ajax({
				type:'POST', 
				data:{id : id,
					  jumlah : $("#jumlah").val(),
					  jumlahKebutuhanRiil : $("#jumlahKebutuhanRiil").val(),
					  keterangan : $("#keterangan").val()},
				url: this.url+'&tipe=subEdit',
				success: function(data) {
					var resp = eval('(' + data + ')');	
					if(resp.err == ''){
						rkbmd_pemeliharaan_v2.refreshList(true);
						document.getElementById('btsave').href = 'javascript:rkbmd_pemeliharaan_v2.Simpan());';
						document.getElementById('btcancel').href = 'javascript:rkbmd_pemeliharaan_v2.closeTab();'; 
						document.getElementById('findBarang').disabled = false;
					}else{
						alert(resp.err);
					}
					
				}
			});			
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
