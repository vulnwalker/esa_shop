var RKASkpd = new SkpdCls({
	prefix : 'RKASkpd', 
	formName: 'RKAForm',
});

var RKA = new DaftarObj2({
	prefix : 'RKA',
	url : 'pages.php?Pg=rka', 
	formName : 'RKAForm',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
	},
	
	topBarRender: function(){
		var me=this;
		var jns=document.getElementById('jns').value;
		//render subtitle
		$.ajax({
		  url: this.url+'&tipe=subtitle&jns='+jns,
		 
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_title').innerHTML = resp.content;
		  }
		});
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
	
	Baru: function(jns){
		if(jns==0){
			id_jmlcek = "rkb_jmlcek";
			formName="adminForm";
		}else{
			id_jmlcek = "rkpb_jmlcek";
			formName="rkpbForm";			
		}
		
		var me = this;
		if(document.getElementById(id_jmlcek)){
			var jmlcek = document.getElementById(id_jmlcek).value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Pilih '+jmlcek+' Data ?')){
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+formName).serialize(),
				url: this.url+'&tipe=formBaru&jns='+jns,
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
			}else{
				alert(errmsg);
			}
		}
	},
	
	AfterFormBaru:function(){ 
		idrka = document.getElementById('RKA_idplh').value;
		document.getElementById('rkadetail').innerHTML = 
			//"<div id='DistribusiDetail_cont_title' style='position:relative'></div>"+
			"<div id='RKADetail_cont_opsi' style='position:relative'>"+
			"<input type='hidden' name='idrka' id='idrka' value='"+idrka+"'>"+
			"</div>"+
			"<div align='right'>"+					
			//"<input type='button' name='Tambah' id='Tambah' value='Tambah'  onclick=\"javascript:DistribusiDetail.Baru()\" >"+
			//"<input type='button' name='Edit' id='Edit' value='Edit'  onclick=\"javascript:DistribusiDetail.Edit()\" >"+	
			//"<input type='button' name='Hapus' id='Hapus' value='Hapus'  onclick=\"javascript:DistribusiDetail.Hapus()\" >"+
			"</div>"+
			"<div id='RKADetail_cont_daftar' style='position:relative'></div>"+
			"<div id='RKADetail_cont_hal' style='position:relative'></div>"
			;
		//generate data
	   RKADetail.loading();
	},	
	
	CekCheckbox2:function(){//alert(this.elJmlCek);
		var errmsg = '';		

		if( document.getElementById('rkb'+'_jmlcek')){
			if((errmsg=='')  && (document.getElementById('rkb'+'_jmlcek').value >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }
		}
		if((errmsg=='') && ( (document.getElementById('rkb'+'_jmlcek').value == 0)||(document.getElementById('rkb'+'_jmlcek').value == '')  )){
			errmsg= 'Data belum dipilih!';
		}
		return errmsg;
	},
	
	GetCbxChecked2:function(){
		var jmldata= document.getElementById( 'rkb'+'_jmldatapage' ).value;
		for(var i=0; i < jmldata; i++){
			var box = document.getElementById( 'rkb'+'_cb' + i);
			if( box.checked){ 
				break;
			}
		}
		return box;			
	},
	
	Kegiatan: function(){	
		
		var me = this;
		var err='';
		var program = document.getElementById('program').value; 			
		var bk = document.getElementById('bk').value; 
		var ck = document.getElementById('ck').value; 				
		var dk = document.getElementById('dk').value; 
						
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			//if (err =='' ){	
			$.ajax({
				type:'POST', 
				data:"program="+program+"&bk="+bk+"&ck="+ck+"&dk="+dk,
			  	url: this.url+'&tipe=kegiatan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					document.getElementById('div_kegiatan').innerHTML = resp.content;
				}
			});
	},	
	
	fmKegiatan: function(){	
		
		var me = this;
		var err='';
		var fmBidang = document.getElementById('fmBidang').value; 			
		var fmSKPD = document.getElementById('fmSKPD').value; 
		var fmProgram = document.getElementById('fmProgram').value; 			
				
			var cover = this.prefix+'_formcover';
			//document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			//if (err =='' ){	
			$.ajax({
				type:'POST', 
				data:"fmProgram="+fmProgram+"&fmBidang="+fmBidang+"&fmSKPD="+fmSKPD,
			  	url: this.url+'&tipe=fmkegiatan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					document.getElementById('div_fmkegiatan').innerHTML = resp.content;
				}
			});
	},
	
	fmBidang: function(){	
		
		var me = this;
		var err='';
		var fmBidang = document.getElementById('fmBidang').value; 			
				
			var cover = this.prefix+'_formcover';
			//document.body.style.overflow='scroll';
			//addCoverPage2(cover,1,true,false);	
			//if (err =='' ){	
			$.ajax({
				type:'POST', 
				data:"fmBidang="+fmBidang,
			  	url: this.url+'&tipe=fmbidang',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					document.getElementById('div_fmskpd').innerHTML = resp.content;
					me.fmSKPD();
					me.fmKegiatan();		
				}
			});
	},

	fmSKPD: function(){	
		
		var me = this;
		var err='';
		var fmBidang = document.getElementById('fmBidang').value; 			
		var fmSKPD = document.getElementById('fmSKPD').value; 
				
			var cover = this.prefix+'_formcover';
			//document.body.style.overflow='scroll';
			//addCoverPage2(cover,1,true,false);	
			//if (err =='' ){	
			$.ajax({
				type:'POST', 
				data:"fmBidang="+fmBidang+"&fmSKPD="+fmSKPD,
			  	url: this.url+'&tipe=fmskpd',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					document.getElementById('div_fmprogram').innerHTML = resp.content;
					me.fmKegiatan();						
				}
			});
	},
	
	CariJurnal: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'fmKdAkun';
		RefJurnal.el_nama_account = 'fmNmAkun';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.windowShow();	
	},							
	ResetCariJurnal: function(){
		var me = this;
		document.getElementById('fmKdAkun').value='';
		document.getElementById('fmNmAkun').value='';
	},


	Edit: function(){
		var me = this;
		errmsg = this.CekCheckbox();
		
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formEdit',
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
					rkb.refreshList(true);
					//me.AfterSimpan();	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	}		
});
