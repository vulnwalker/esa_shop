var popupJadwal = new DaftarObj2({
	prefix : 'popupJadwal',
	url : 'pages.php?Pg=popupJadwal', 
	formName : 'popupJadwalForm',
	el_namaHistori : '',
	el_idHistori: '',	
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
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
			//	me.sumHalRender();
		  	}
		});
	},
	
	Baru:function(){
		var me = this;
		var cover = this.prefix+'_formcover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
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
	newRow:function(){
	

		var me = this;
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
		  	url: this.url+'&tipe=newRow',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				popupJadwal.refreshList(true);
				
		  	}
		});
	},
	remove:function(id){
		var me = this;
		$.ajax({
			type:'POST', 
			data: {id : id},
		  	url: this.url+'&tipe=remove',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				popupJadwal.refreshList(true);
				
		  	}
		});
	},
	save:function(id){
		var me = this;
		$.ajax({
			type:'POST', 
			data: {
					id : id,
					namaTahap : $("#namaTahap"+id).val(),
					idModul : $("#namaModul"+id).val(),
					jenisForm : $("#jenisForm"+id).val(),
					statusAktif : $("#statusAktif"+id).val(),	
				   },
		  	url: this.url+'&tipe=save',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				if(resp.err == ''){
					popupJadwal.refreshList(true);
				}else{
					alert(resp.err);
				}
				
				
		  	}
		});
	},
	cancel:function(id){
		var me = this;
		$.ajax({
			type:'POST', 
			data: {
					id : id,
				   },
		  	url: this.url+'&tipe=cancel',
		  	success: function(data) {		
				popupJadwal.refreshList(true);
				
				
		  	}
		});
					
				
	},
	modulChanged:function(id){
		var me = this;
		$.ajax({
			type:'POST', 
			data: {id : id,
				   namaModul : $("#namaModul"+id).val()
				  },
		  	url: this.url+'&tipe=modulChanged',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				$("#jenisForm"+id).html(resp.content.jenisForm);
				
		  	}
		});
	},
	edit:function(id){
		$("#spanNamaTahap"+id).html("<input type='text' id='namaTahap"+id+"' value='"+$("#spanNamaTahap"+id).text()+"' style='width:100%;' >");
		var me = this;
		$.ajax({
			type:'POST', 
			data: {id : id,
				   namaModul : $("#spanNamaModul"+id).text(),
				   jenisForm : $("#spanJenisFormModul"+id).text(),
				   statusAktif : $("#spanStatusAktif"+id).text(),
				   
				  },
		  	url: this.url+'&tipe=fiturEdit',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				$("#spanNamaModul"+id).html(resp.content.cmbModul);
				$("#spanJenisFormModul"+id).html(resp.content.jenisForm);
				$("#spanStatusAktif"+id).html(resp.content.statusAktif);
				$("#action"+id).html(resp.content.aksi);
		  	}
		});
	},
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if(resp.err==''){
					document.getElementById(cover).innerHTML = resp.content;	
					me.loading();					
				}else{
					alert(resp.err);
					delElem(cover);
					document.body.style.overflow='auto';
				}
		  	}
		});
	},
		
	windowClose: function(){
		document.body.style.overflow='auto';
		delElem(this.prefix+'_cover');
	},
	
	windowSave: function(){
		var me= this;


		$.ajax({
			type:'POST', 
			data: {tahun : $("#tahun").val(),
				   jenis_anggaran : $("#jenis_anggaran").val()
				  },
		  	url: 'pages.php?Pg=popupJadwal&tipe=cekDataAda',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				if(resp.content.ada == 'ya'){
						if (confirm('Data sudah ada ingin menggantinya ?')) {
							popupJadwal.execute();
						} else {
						
						}
				}else{
					popupJadwal.execute();
				}
		  	}
		});
		
		

		
			
			
			
		
	},
	execute:function(){
		var me= this;
		var cover = 'popupJadwal_getdata';
		addCoverPage2(cover,1,true,false);
		
		$.ajax({
		  url: this.url+'&tipe=getdata',
		  type : 'POST',
		  data : $('#'+this.formName).serialize(),
		  success: function(data) {
		  var resp = eval('('+data+')');
		  	if(resp.err == ''){
				delElem(cover);
				ref_tahap_anggaran.refreshList(true);
				me.windowClose();
			}else{
				delElem(cover);
				alert(resp.err);
			}
		  	
		  }
		});
	},
	windowSaveAfter: function(){
		//alert('tes');
	},		
		
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
					me.Close();
					me.refreshList(true);
					me.AfterSimpan();	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	}
});
