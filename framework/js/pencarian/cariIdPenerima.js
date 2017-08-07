/*var ref_rekeningSkpd = new SkpdCls({
	prefix : 'ref_rekeningSkpd', formName:'ref_rekeningForm',
	
	pilihBidangAfter : function(){cariRekening.refreshList(true);},
	pilihKelompokAfter : function(){cariRekening.refreshList(true);},
	pilihSubKelompokAfter : function(){cariRekening.refreshList(true);},
	pilihSekSubKelompokAfter : function(){cariRekening.refreshList(true);}
});*/

var cariIdPenerima = new DaftarObj2({
	prefix : 'cariIdPenerima',
	url : 'pages.php?Pg=cariIdPenerima', 
	formName : 'cariIdPenerimaForm',
	satuan_form : '0',//default js satuan
	loading: function(){
		//alert('loading');
		this.topBarRender();
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
	windowClose: function(){
		document.body.style.overflow='auto';
		delElem(this.prefix+'_cover');
	},
	
	pilPen: function(idpenerima, refid){
		var me = this;
			
		/*document.getElementById('id_penerimaan').value=idpenerima;
		document.getElementById('refid_terima').value=refid;
		me.windowClose();*/
		var cover = this.prefix+'_cover';
		//var idrekeningnya = document.getElementById('idrek').value;
		
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=getid&idpenerimanya='+idpenerima+'&refidnya='+refid,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if(resp.err==''){
					delElem(cover);
					
					document.getElementById('id_penerimaan').value=resp.content.idpenerima;
					document.getElementById('refid_terima').value=resp.content.refid;
					document.getElementById('prog').value = resp.content.p;
					document.getElementById('program').value = resp.content.program;
					document.getElementById('dafkeg').innerHTML = resp.content.kegiatan;
					
					document.getElementById('pekerjaan').value = resp.content.pekerjaan;
					document.getElementById('pekerjaan').readOnly = true;
					document.getElementById('progcar').disabled = true;
					
					me.windowClose();
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	
	windowShow: function(){
		var me = this;
		
		var cover = this.prefix+'_cover';
		var c1nya = document.getElementById('c1nya').value;
		var cnya = document.getElementById('cnya').value;
		var dnya = document.getElementById('dnya').value;
		var enya = document.getElementById('enya').value;
		var e1nya = document.getElementById('e1nya').value;
		var jns_transaksi = document.getElementById('jns_transaksi').value;
		
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow&c1='+c1nya+'&c='+cnya+'&d='+dnya+'&e='+enya+'&e1='+e1nya+'&jnsnya='+jns_transaksi,
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
	Baru: function(){	
		
		var me = this;
		var err='';
		
		if (err =='' ){		
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
					if(me.satuan_form==0){
						me.Close();
						me.AfterSimpan();						
					}else{
						me.Close();
						barang.refreshComboSatuan();
					}

				}else{
					alert(resp.err);
				}
		  	}
		});
	}
		
});
