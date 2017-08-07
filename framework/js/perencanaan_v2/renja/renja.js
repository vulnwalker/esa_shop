var renja_v2 = new DaftarObj2({
	prefix : 'renja_v2',
	url : 'pages.php?Pg=renja_v2', 
	formName : 'renja_v2Form',
	renja_v2_form : '0',
	loading: function(){
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
	
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
	InputBaru: function(){
	var me = this;
			
		errmsg = '';
		
		if($("#fmSKPDUrusan").val() == '00'){
			errmsg = "Pilih Urusan";
		}else if($("#fmSKPDBidang").val() == '00'){
			errmsg = "Pilih Bidang";
		}else if($("#fmSKPDskpd").val() == '00'){
			errmsg = "Pilih SKPD";
		}
		
		
		if(errmsg ==''){
		
			var me = this;
			var aForm = document.getElementById(this.formName);
			$.ajax({
			  url: this.url+'&tipe=newTab',
			  type : 'POST',
			  data:$('#'+this.formName).serialize(),
			  success: function(data) {
					var resp = eval('(' + data + ')');
					if(resp.err == "") {
						var IDrenja_v2 = Number(resp.content.idrenja_v2);
						aForm.action= 'pages.php?Pg=renja_ins_v2&YN=1&id='+IDrenja_v2;
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
	},
	BidangAfter2: function(){
		renja_v2.refreshList(true);
	},
	BidangAfterform: function(){
		var me = this;
		$.ajax({
		  url: this.url+'&tipe=BidangAfterForm',
		  type : 'POST',
		  data:{ fmSKPDBidang: $("#cmbBidangForm").val(),
		  		 fmSKPDUrusan: $("#cmbUrusanForm").val(),
		  		 fmSKPDskpd: $("#cmbSKPDForm").val(),
		  		 fmSKPDUnit: $("#cmbUnitForm").val(),
		  		 fmSKPDSubUnit: $("#cmbSubUnitForm").val() },
		  success: function(data) {
			var resp = eval('(' + data + ')');	
				document.getElementById('cmbBidangForm').innerHTML=resp.content.bidang;
				document.getElementById('cmbSKPDForm').innerHTML=resp.content.skpd;
				document.getElementById('cmbUnitForm').innerHTML=resp.content.unit;
				document.getElementById('cmbSubUnitForm').innerHTML=resp.content.subunit;
		  }
		});

	},
	jenisChanged: function(){
		var me = this;
		$.ajax({
		  url: this.url+'&tipe=jenisChanged',
		  type : 'POST',
		  data:{ jenisKegiatan: $("#jenisKegiatan").val()},
		  success: function(data) {
			var resp = eval('(' + data + ')');	
				document.getElementById('tempatPlus').innerHTML=resp.content.plus;
				document.getElementById('tempatMinus').innerHTML=resp.content.minus;
				document.getElementById('keyPP').textContent ="";
				document.getElementById('keyMM').textContent ="";
		  }
		});

	},
	pilihBidang: function(){
				renja_v2.refreshList(true);

	},
	comboSKPDChanged: function(){
				renja_v2.refreshList(true);
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
				//me.sumHalRender();
		  	}
		});
	},
	Baru: function(){	
		
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			if(me.renja_v2_form==0){//baru dari renja_v2
				addCoverPage2(cover,1,true,false);	
			}else{//baru dari barang
				addCoverPage2(cover,2,true,false);	
			}
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize() ,
			  	url: this.url+'&tipe=formBaru',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;	
					me.AfterFormBaru();
					$("#tanggalMulai").datepicker({ dateFormat: 'dd-mm-yy' });
					$("#tanggalSelesai").datepicker({ dateFormat: 'dd-mm-yy' });
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	sesuai:function(id){
		$.ajax({
				type:'POST', 
				data:{idAwal : id,
					  angkaKoreksi : $("#valueJumlah"+id).val()},
				url: this.url+'&tipe=koreksi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if(resp.err != ''){
						alert(resp.err);
						if(resp.err == 'Tahap Koreksi Telah Habis'){
							window.location.reload();
						}
					}else{
						renja_v2.refreshList(true);
					}
					
				 }
			  });
		/*$("#span"+id).html("<input type='text' name='"+id+"' id='"+id+"' onkeyup='return renja_v2.submitKoreksi(event,id)'  value='"+$("#valueJumlah"+id).val()+"'  style='text-align: right;'>  <span id='bantu"+id+"' >"+popupBarang.formatCurrency($("#valueJumlah"+id).val())+"</span>");*/

	},
	koreksi:function(id){
		$("#span"+id).html("<input type='text' name='"+id+"' id='"+id+"' onkeyup='return renja_v2.submitKoreksi(event,id)' onkeypress='return event.charCode >= 48 && event.charCode <= 57'  style='text-align: right;'>  <span id='bantu"+id+"' ></span>");
	},
	submitKoreksi:function(e,id) {
	var angkaKoreksi = $("#"+id).val();
	    if (e.keyCode == 13) {
			
			if(angkaKoreksi <= '0'){
				alert("angka Salah");
			}else{
			  $.ajax({
				type:'POST', 
				data:{idAwal : id,
					  angkaKoreksi : angkaKoreksi},
				url: this.url+'&tipe=koreksi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if(resp.err != ''){
						alert(resp.err);
						if(resp.err == 'Tahap Koreksi Telah Habis'){
							window.location.reload();
						}
					}else{
						renja_v2.refreshList(true);
					}
					
				 }
			  });
			}
	        return false;
	    }else if (e.keyCode == 27) {
			renja_v2.refreshList(true);
	        return false;
	    }else{
			document.getElementById('bantu'+id).innerHTML = popupBarang.formatCurrency(angkaKoreksi);
		}
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
	Info: function(){	
		var me = this;
	    var cover = this.prefix+'_formcover';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize() ,
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
					if(me.renja_v2_form==0){
						me.Close();
						me.AfterSimpan();						
					}

				}else{
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
						if(resp.err == 'Tahap Penyusunan Telah Habis'){
							alert(resp.err);
							window.location.reload();
						}else{
							alert(resp.err);
						}
					}			
			
			  	}
			});
		}else{
			alert(errmsg);
		}
		
	},
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
					if(me.renja_v2_form == 0){
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
	detailrenja_v2: function(kode){	
		
		var me = this;
			var box = this.GetCbxChecked();
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,2,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data: $('#'+this.formName).serialize()+"&renja_v2_cb%5B%5D=1.08.01.01.01" ,
				url: this.url+'&tipe=lihatrenja_v2',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						me.AfterFormEdit(resp);
						$("#findProgram").attr("disabled",true);
						$("#plafon").attr("readonly",true);
						$("#keyPlafon").text("Rp. " + popupProgram.formatCurrency($("#plafon").val()));
						
						
					}else{
						alert(resp.err);
						delElem(cover);
						document.body.style.overflow='auto';
					}
			  	}
			});
	},
	pesanPasif : function(){
	  alert("STATUS PASIF");	
	},
	
	comboChanged : function(){
			$.ajax({
			type:'POST', 
			data:{ c1 : $("#cmbUrusan").val(),
				   c  : $("#cmbBidang").val(),
				   d  : $("#cmbSKPD").val()
					 },
			url: this.url+'&tipe=comboPlafon',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				document.getElementById("cmbUrusan").innerHTML = resp.content.urusan;
				document.getElementById("cmbBidang").innerHTML = resp.content.bidang;
				document.getElementById("cmbSKPD").innerHTML = resp.content.skpd;
				document.getElementById("angkaPlafon").innerHTML = resp.content.angkaPlafon;

				
		  	}
		});
	},
	
	Edit:function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			var aForm = document.getElementById(this.formName);
			$.ajax({
			  url: this.url+'&tipe=editTab',
			  type : 'POST',
			  data:$('#'+this.formName).serialize(),
			  success: function(data) {
					var resp = eval('(' + data + ')');	
					if(resp.err ==''){
						var ID_PLAFON = Number(resp.content.ID_PLAFON);
						var ID_EDIT = Number(resp.content.ID_EDIT);
						aForm.action= 'pages.php?Pg=renja_ins_v2&id='+ID_PLAFON+"&ID_RENJA="+ID_EDIT;
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
		
	},
	
	CariProgramKegiatan : function(){
		popupProgram.windowSaveAfter= function(){};
		 if(document.getElementById('bk').value == ''){
		 	alert("PILIH URUSAN PROGRAM");
		 }else if(document.getElementById('bk').value != '0' && document.getElementById('ck').value == '' ){
		 	alert("PILIH BIDANG PROGRAM"); 
		 }else{
		 	 popupProgram.filterAkun=document.getElementById('bk').value + "." + document.getElementById('ck').value;
		 	 popupProgram.windowShow();	
		 }
	},
	
	CariUrusanProgram : function(){
		 
		 popupUrusan.filterAkun='0';
		 popupUrusan.windowShow();	
	},
	
	CariBidangProgram : function(){
		 popupUrusan.windowSaveAfter= function(){};
		 popupUrusan.filterAkun= document.getElementById('bk').value;
		 if(document.getElementById('bk').value == ''){
		 	alert("PILIH URUSAN PROGRAM");
		 }else if(document.getElementById('bk').value == '0'){
		 	alert("NON URUSAN TIDAK MEMILIKI BIDANG"); 
		 }else{
		 	 popupUrusan.windowShow();	
		 }
		
	},
		
	Simpan: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		var tahunJamak = ( $("#tahunJamak").is(':checked') ) ? 1 : 0;
		if($("#tahunJamak").c){
			
		}
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize()+ "&tahunJamak="+tahunJamak,
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					if(me.renja_v2_form==0){
						if (confirm('Input Lagi ?')) {
	    					$.ajax({
								type : 'POST',
								data : {c1 : $('#cmbUrusanForm').val(),
										c  : $('#cmbBidangForm').val(),
										d  : $('#cmbSKPDForm').val()
										 },
								url  : this.url+'&tipe=inputLagi',
								success : function(data){
									var resp = eval('(' + data + ')');	
									$('#plafon').attr('readonly', true);
									
									document.getElementById("cmbUrusanForm").innerHTML = resp.content.urusan;
									document.getElementById("cmbBidangForm").innerHTML = resp.content.bidang;
									document.getElementById("cmbSKPDForm").innerHTML = resp.content.skpd;
									
									
									
									$("#sisaPlafonDariDB").val(resp.content.sisaBuatKurang);
									$("#sisaPlafon").val($("#plafon").val() - $("#sisaPlafonDariDB").val());
									$("#cmbUnitForm").prop('selectedIndex', 0);
									$("#cmbSubUnitForm").prop('selectedIndex', 0);
									$("#lokasiKegiatan").val("");
									$("select#jenisKegiatan").prop('selectedIndex', 0);
									$("#tanggalMulai").val("");
									$("#tanggalSelesai").val("");
									$("#tanggalMulai").val("");
									$("#tahunJamak").prop('checked',false);
									$("#paguIndikatif").val("");
									$("#plus").val("");
									$("#min").val("");
									$("#cmbSumberDana").prop('selectedIndex', 0);
									
									document.getElementById("keyPagu").textContent = "Rp.";
									document.getElementById("keyPP").textContent = "Rp.";
									document.getElementById("keyMM").textContent = "Rp.";
									
									/*document.getElementById("CPTU").textContent = "";
									document.getElementById("CPTK").textContent = "";
									document.getElementById("HTU").textContent = "";
									document.getElementById("HTK").textContent = "";
									document.getElementById("KTU").textContent = "";
									document.getElementById("KTK").textContent = "";
									document.getElementById("MTU").textContent = "";
									document.getElementById("MTK").textContent = "";*/
									$('#CPTU').val(""); 
									$('#CPTK').val(""); 
									$('#HTU').val(""); 
									$('#HTK').val(""); 
									$('#KTU').val(""); 
									$('#KTK').val(""); 
									$('#MTU').val(""); 
									$('#MTK').val(""); 
									//lhoh keluar:  tapi kok aneh tadi pertama w coba kek gini gak ilang ikacang kacang
									//apa ? tar2 wa d panggil
									
									$("#sasaranKegiatan").val("");
									
									
								}
							});
						} else {
							me.Close()
							me.refreshList(true);
						}				
					}else{
						me.Close();
						me.refreshComborenja_v2();
					}

				}else{
					alert(resp.err);
				}
		  	}
		});
	},Laporan: function(){
		var aForm = document.getElementById(this.formName);
			$.ajax({
			  url: this.url+'&tipe=postReport',
			  type : 'POST',
			  data:$('#'+this.formName).serialize(),
			  success: function(data) {
					var resp = eval('(' + data + ')');	
					if(resp.err !=''){
						alert(resp.err);
					}else{
						aForm.action= 'pages.php?Pg=renja_v2&tipe=Laporan';
						aForm.target='_blank';
						aForm.submit();	
						aForm.target='';
					}
			  }
			});

	},	
		
});