var refskpd_urusanSkpd = new SkpdCls({
	prefix : 'refskpd_urusanSkpd', formName:'refskpd_urusanForm',
	
	pilihBidangAfter : function(){refskpd_urusan.refreshList(true);},
	pilihUnitAfter : function(){refskpd_urusan.refreshList(true);},
	pilihSubUnitAfter : function(){refskpd_urusan.refreshList(true);},
	pilihSeksiAfter : function(){refskpd_urusan.refreshList(true);}
});

var refskpd_urusan = new DaftarObj2({
	prefix : 'refskpd_urusan',
	url : 'pages.php?Pg=refskpd_urusan', 
	formName : 'refskpd_urusanForm',
	el_id_status_brg_temp : '',
	el_nm_status_brg_temp : '',
	//el_id_satuan_temp : '',
	//el_nm_satuan_temp : '',	
	
	loading: function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		this.sumHalRender();
	
	},
	
	topBarRender: function(){
		var me=this;
		//render subtitle
		$.ajax({
		  url: this.url+'&tipe=subtitle',
		  type:'POST',
		  data:$('#'+this.formName).serialize(), 
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_title').innerHTML = resp.content;
		  }
		});
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
	
	fmSKPD: function(){	
		
		var me = this;
		var err='';
		var fmBidang = document.getElementById('fmBidang').value; 			
				
			var cover = this.prefix+'_formcover';
			//document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			//if (err =='' ){	
			$.ajax({
				type:'POST', 
				data:"fmBidang="+fmBidang,
			  	url: this.url+'&tipe=fmskpd',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					document.getElementById('div_fmskpd').innerHTML = resp.content;
				}
			});
	},
	
	Urusan: function(){	
		
		var me = this;
		var err='';
		var urusan = document.getElementById('bk').value; 			
				
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			//if (err =='' ){	
			$.ajax({
				type:'POST', 
				data:"urusan="+urusan,
			  	url: this.url+'&tipe=urusan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					document.getElementById('div_bidang').innerHTML = resp.content;
				}
			});
	},	
	
	Bidang: function(){	
		
		var me = this;
		var err='';
		var urusan = document.getElementById('bk').value; 	
		var bidang = document.getElementById('ck').value; 			
				
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			//if (err =='' ){	
			$.ajax({
				type:'POST', 
				data:"urusan="+urusan+"&bidang="+bidang,
			  	url: this.url+'&tipe=bidang',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					document.getElementById('div_dinas').innerHTML = resp.content;
				}
			});
	},				
		
	Baru: function(mode){	
		
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize()+'&mode='+mode,
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
		
	Edit:function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,999,true,false);	
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
		
	Simpan: function(mode){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		if(mode==1){
			var formName_ = this.prefix; 			
		}else{
			var formName_ = this.prefix+'Survey'; 		
		}
		addCoverPage2(cover,1,true,false);	
		/*this.sendReq(
			this.url,
			{ idprs: 0, daftarProses: new Array('simpan')},
			this.formDialog);*/
		$.ajax({
			type:'POST', 
			data:$('#'+formName_+'_form').serialize(),
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){			
					if(mode==1){
						me.Close();
						me.AfterSimpan();						
					}else{
						me.CloseCari();
						Penatausaha.refreshList(true);						
					}
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	Cari: function(){
		var me = this;
		RefStatusBarang.el_id_status_brg_temp = 'id_status_barang';
		RefStatusBarang.el_nm_status_brg_temp = 'status_barang';		
		RefStatusBarang.windowSaveAfter= function(){};
		RefStatusBarang.windowShow();	
	},	
	
	CloseCari: function(){
		document.body.style.overflow='auto';
		delElem(this.prefix+'_formcoverStSurvey');
	},				
	
	windowShow: function(){
		var me = this;
		
		var cover = this.prefix+'_cover';
		
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
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
		//alert('save');
		var errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			//alert(box.value);
			this.idpilih = box.value;			
			
			var cover = 'ref_skpd_urusan_getdata';
			addCoverPage2(cover,1,true,false);				
			$.ajax({
				url: 'pages.php?Pg=refskpd_urusan&tipe=getdata&id='+this.idpilih,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						if(document.getElementById(me.el_id_status_brg_temp)) document.getElementById(me.el_id_status_brg_temp).value= resp.content.el_id_status_brg_temp;
						if(document.getElementById(me.el_nm_status_brg_temp)) document.getElementById(me.el_nm_status_brg_temp).value= resp.content.el_nm_status_brg_temp;
						//if(document.getElementById(me.el_id_satuan_temp)) document.getElementById(me.el_id_satuan_temp).value= resp.content.el_id_satuan_temp;
						//if(document.getElementById(me.el_nm_satuan_temp)) document.getElementById(me.el_nm_satuan_temp).value= resp.content.el_nm_satuan_temp;

						me.windowClose();
					}else{
						alert(resp.err)	
					}
			  	}
			});		
		}else{
			alert(errmsg);
		}
	},
	
	windowSaveAfter: function(){
		alert('tes2');	
	},
	
});
