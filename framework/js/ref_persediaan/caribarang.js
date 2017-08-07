var CariBarang = new DaftarObj2({
	prefix : 'CariBarang',
	url : 'pages.php?Pg=caribarang', 
	formName : 'PersediaanBarang_form',
	withPilih:true,
	
	
	loading:function(){
		//alert('loading');
		//this.topBarRender();
		this.filterRender();
		this.daftarRender();
		this.sumHalRender();
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
	
	/*Baru: function(){	
		
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
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
	
	CekCheckbox2:function(){//alert(this.elJmlCek);
		var errmsg = '';		
		//alert(document.getElementById(this.prefix+'_jmlcek').value );
		//if( document.getElementById(this.elJmlCek)){
		if( document.getElementById('Pasien'+'_jmlcek')){
			if((errmsg=='')  && (document.getElementById('Pasien'+'_jmlcek').value >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }
		}
		if((errmsg=='') && ( (document.getElementById('Pasien'+'_jmlcek').value == 0)||(document.getElementById('Pasien'+'_jmlcek').value == '')  )){
			errmsg= 'Pasien belum dipilih!';
		}
		return errmsg;
	},
	
	GetCbxChecked2:function(){
		var jmldata= document.getElementById( 'Pasien'+'_jmldatapage' ).value;
		for(var i=0; i < jmldata; i++){
			var box = document.getElementById( 'Pasien'+'_cb' + i);
			if( box.checked){ 
				break;
			}
		}
		return box;			
	},
	
	Baru2: function(){	
		
		var me = this;
		var err='';
		errmsg = this.CekCheckbox2();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked2();
		
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+'PasienForm').serialize(),
			  	url: this.url+'&tipe=formBaru2',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
		}else{
			alert(errmsg);
		}
	},*/
	
	AfterSimpan : function(){
		if(document.getElementById('Kunjungan_cont_daftar')){
		this.refreshList(false);}
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
					me.Close();
					me.AfterSimpan();
				}else{
					alert(resp.err);
				}
		  	}
		});
	}
		
});
