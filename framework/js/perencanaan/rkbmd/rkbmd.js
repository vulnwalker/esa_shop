var rkbmdSkpd = new SkpdCls({
	prefix : 'rkbmdSkpd', 
	formName: 'rkbmdForm',
	pilihBidangAfter : function(){rkbmd.refreshList(true);},
	pilihUnitAfter : function(){rkbmd.refreshList(true);},
	pilihSubUnitAfter : function(){rkbmd.refreshList(true);},
	pilihSeksiAfter : function(){rkbmd.refreshList(true);}
});

var rkbmd = new DaftarObj2({
	prefix : 'rkbmd',
	url : 'pages.php?Pg=rkbmd', 
	formName : 'rkbmdForm',
	
	loading:function(){
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
		
	},
		
	daftarRender:function(){
		var jenisKegiatan = document.getElementById('cmbJenisRKBMD');
		/*cmbJenisRKBMD.remove(0);*/
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
	
	
	Cari: function(){
		popupBarang.kodeBarang = 'kodeBarang';
		popupBarang.namaBarang = 'namaBarang'; 
		popupBarang.satuanBarang = 'satuan'; 
		popupBarang.windowShow();
		
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
					if(resp.err == "") {
						var IDrenja = Number(resp.content.idrenja);
						aForm.action= 'pages.php?Pg=rkbmd_'+resp.content.kemana+'&YN=1&id='+IDrenja;
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
	remove:function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			$.ajax({
			  url: this.url+'&tipe=remove',
			  type : 'POST',
			  data:$('#'+this.formName).serialize(),
			  success: function(data) {
					var resp = eval('(' + data + ')');
					rkbmd.refreshList(true);
					
			  }
			});
		}else{
			alert(errmsg);
		}
		
	},Validasi:function(){
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
				if(resp.err==''){
					me.Close();
						/*me.AfterSimpan();	*/
						rkbmd.refreshList(true);
				}else{
					alert(resp.err);
				}
		  	}
		});
	}
	,		
	AfterSimpan : function(){
		if(document.getElementById('Kunjungan_cont_daftar')){
		this.refreshList(true);}
	}, 
	InputBaru: function(){
		var me = this;
			
		errmsg = '';
		
		if($("#rkbmdSkpdfmUrusan").val() == '00'){
			errmsg = "Pilih Urusan";
		}else if($("#rkbmdSkpdfmSKPD").val() == '00'){
			errmsg = "Pilih Bidang";
		}else if($("#rkbmdSkpdfmUNIT").val() == '00'){
			errmsg = "Pilih SKPD";
		}else if($("#rkbmdSkpdfmSUBUNIT").val() == '00'){
			errmsg = "Pilih UNIT";
		}else if($("#rkbmdSkpdfmSEKSI").val() == '000'){
			errmsg = "Pilih SUB UNIT";
		}else if($("#cmbJenisRKBMD").val() == ''){
			errmsg = "Pilih JENIS RKBMD";
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
						var IDrenja = Number(resp.content.idrenja);
						aForm.action= 'pages.php?Pg=rkbmd_'+resp.content.kemana+'&YN=1&id='+IDrenja;
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
	koreksi:function(id){
		/*$("#alignKoreksi").attr('align','center');*/
		$("#updatePengguna"+id).attr('align','center');
		$("#spanJumlah"+id).html("<input type='text' name='jumlah"+id+"' id='jumlah"+id+"' onkeyup=rkbmd.bantu('Jumlah"+id+"'); style='text-align: right;'>  ");
		$("#spanCaraPemenuhan"+id).html("<input type='text' name='pemenuhan"+id+"' id='pemenuhan"+id+"' style='text-align: left;'> ");
		$("#save"+id).html("<img src='datepicker/save.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.submitKoreksi('"+id+"');></img> &nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.cancelKoreksi('"+id+"');></img> ");
	},
	sesuai:function(id){
		var isi = $("#volumeBarang"+id+"").val();
		$("#updatePengguna"+id).attr('align','center');
		/*$("#alignKoreksi").attr('align','center');*/
		$("#spanJumlah"+id).html("<input type='text' value='"+isi+"' name='jumlah"+id+"' id='jumlah"+id+"' style='text-align: right;' readonly> ");
		$("#spanCaraPemenuhan"+id).html("<input type='text' name='pemenuhan"+id+"' id='pemenuhan"+id+"' style='text-align: left;'> ");
		$("#save"+id).html("<img src='datepicker/save.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.submitKoreksi('"+id+"');></img> &nbsp  &nbsp <img src='images/administrator/images/invalid.png' width='20px' heigh='20px' style='cursor : pointer;' onclick=rkbmd.cancelKoreksi('"+id+"');></img> ");
	},
	submitKoreksi:function(id) {
	var angkaKoreksi = $("#jumlah"+id).val();
	var caraPemenuhan = $("#pemenuhan"+id).val();
	if(angkaKoreksi <= '0'){
		alert("angka Salah");
	}else if(caraPemenuhan == ''){
		alert("isi cara pemenuhan");
	}else{
	    $.ajax({
				type:'POST', 
				data:{idAwal : id,
					  angkaKoreksi : angkaKoreksi,
					  caraPemenuhan : caraPemenuhan,
					  jenisRKBMD  : $("#cmbJenisRKBMD").val()	
					  },
				url: this.url+'&tipe=koreksi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if(resp.err != ''){
						alert(resp.err);
					}else{
						rkbmd.refreshList(true);
					}
					
				 }
			  });
		}
	    
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
						me.Close();
						me.AfterSimpan();						
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
		return (((sign)?'':'-') + '' + num );
	},
	bantu : function(id){
		var idAngka = id.replace('J','j');
		var angka = $("#"+idAngka).val();
		$("#bantu"+id).text(rkbmd.formatCurrency(angka));
	},
	cancelKoreksi : function(id){
		rkbmd.refreshList(true);
	}
});
