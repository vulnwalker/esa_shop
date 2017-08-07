var rkbmd_ins_v2SKPD = new SkpdCls({
	prefix : 'rkbmd_ins_v2SKPD', formName:'rkbmd_ins_v2Form', kolomWidth:120,
	
	a : function(){
		alert('dsf');
	},
});

var rkbmd_ins_v2 = new DaftarObj2({
	prefix : 'rkbmd_ins_v2',
	url : 'pages.php?Pg=rkbmd_ins_v2', 
	formName : 'rkbmd_ins_v2Form',
	satuan_form : '0',//default js satuan
	
	
	
	loading: function(){

		this.topBarRender();
		this.filterRender();

	
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
					rkbmd_ins_v2.refreshList(true);			
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
		/*$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=rincianpenerimaanDET',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('rinciandatabarangnya').innerHTML = resp.content;
				}else{
					alert(resp.err);
				}
			}
		});	*/
		rkbmd_ins_v2.refreshList(true);
	},
    CariProgram: function(idRenja){
		var me = this;
		popupProgramRKBMD_v2.idRenja = idRenja;
		popupProgramRKBMD_v2.kategori = "PENGADAAN";
		popupProgramRKBMD_v2.windowShow();	
		
	},
	CariBarang: function(){
		var me = this;
		popupBarangRKBMD_v2.windowShow();	
	
	
		
		
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
					  satuan : $("#satuanBarang").val(),
					  tahunAnggaran : $("#tahunAnggaran").val()
					 },
				url: this.url+'&tipe=Simpan',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						rkbmd_ins_v2.refreshList(true);
						document.getElementById("findProgram").disabled = true;	
						document.getElementById("q").innerHTML= resp.content.q;			
	
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
					var resp = eval('(' + data + ')');		
					if(resp.content == ""){
						location.reload();
					}else{
						rkbmd_ins_v2.refreshList(true);
					}
					
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
					$("#jumlahKebutuhanRiil").val(resp.content.jumlahKebutuhanRill);
					$("#jumlahKebutuhanMaksimal").val(resp.content.jumlahKebutuhanMaksimal);
					$("#jumlahKebutuhanOptimal").val(resp.content.jumlahKebutuhanOptimal);
					$("#jumlah").val(resp.content.jumlah);
					$("#satuanBarang").val(resp.content.satuan);
					$("#keterangan").val(resp.content.keterangan);
					document.getElementById('btsave').href = 'javascript:rkbmd_ins_v2.subEdit('+id+');';
					document.getElementById('btcancel').href = 'javascript:rkbmd_ins_v2.subCancel();'; 
					document.getElementById('findBarang').disabled = true;
					document.getElementById('findProgram').disabled = true;
				}
			});	
		
		
	},
	subCancel : function(){
					$("#kodeBarang").val('');
					$("#namaBarang").val('');
					$("#satuan").val('');
					$("#jumlahKebutuhanRiil").val('');
					$("#jumlahKebutuhanMaksimal").val('');
					$("#jumlahKebutuhanOptimal").val('');
					$("#jumlah").val('');
					$("#satuanBarang").val('');
					$("#keterangan").val('');
					document.getElementById('btsave').href = 'javascript:rkbmd_ins_v2.Simpan();';
					document.getElementById('btcancel').href = 'javascript:rkbmd_ins_v2.closeTab();'; 
					document.getElementById('findBarang').disabled = false;
	},
	subEdit : function(id){
			$.ajax({
				type:'POST', 
				data:{id : id,
					  jumlah : $("#jumlah").val(),
					   satuan : $("#satuanBarang").val(),
					  jumlahKebutuhanRiil : $("#jumlahKebutuhanRiil").val(),
					  keterangan : $("#keterangan").val()},
				url: this.url+'&tipe=subEdit',
				success: function(data) {
					var resp = eval('(' + data + ')');	
					if(resp.err == ''){
						rkbmd_ins_v2.refreshList(true);
						document.getElementById('btsave').href = 'javascript:rkbmd_ins_v2.Simpan());';
						document.getElementById('btcancel').href = 'javascript:rkbmd_ins_v2.closeTab();'; 
						document.getElementById('findBarang').disabled = false;
					}else{
						alert(resp.err);
					}
					
				}
			});			
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
						rkbmd_ins_v2.refreshList(true);	
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
