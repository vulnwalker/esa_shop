var JnsBarang = new DaftarObj2({
	prefix : 'JnsBarang',
	url : 'pages.php?Pg=JnsBarang', 
	formName : 'JnsBarangForm',
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
	
	autocomplete_initial:function (){
	 				var me=this;
						$(function() {							
							$( '#status_barang' ).autocomplete({
						      source: function( request, response ) {
							  //var waktu_klinik = document.getElementById('waktu_klinik').value;
						        $.ajax({
								  url: 'pages.php?Pg=RefStatusBarang&tipe=autocomplete_stsurvey_getdata',
						          dataType: 'json',
						          data: {
						            //featureClass: 'P',
						            style: 'full',
						            maxRows: 12,
									name_startsWith: request.term	
							
						          },
								  success: function( data ) {						         
									  response( $.map( data, function( item ) {
										    return {
												id: item.id,
										        label: item.label,
												value: item.value
										    }
										}));
									}
						        });
						      },
						      minLength: 1,
						      select: function( event, ui ) {
						        console.log( ui.item ?
						          'Selected: id=' + ui.item.id+' label=' + ui.item.label  :
						          'Nothing selected, input was ' + this.value);
								 document.getElementById('id_status_barang').value=ui.item.id;
								 //me.YangMenerima();
						      },
						      open: function() {
						        $( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
						      },
						      close: function() {
						        $( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
								//me.tarif();
						      }
						    });
						});
	},
	
	autocomplete_initialsurvey:function (){
	 				var me=this;
						$(function() {							
							$( '#fmNAMASURVEY' ).autocomplete({
						      source: function( request, response ) {
							  //var waktu_klinik = document.getElementById('waktu_klinik').value;
						        $.ajax({
								  url: 'pages.php?Pg=RefStatusBarang&tipe=autocomplete_stsurvey2_getdata',
						          dataType: 'json',
						          data: {
						            //featureClass: 'P',
						            style: 'full',
						            maxRows: 12,
									name_startsWith: request.term	
							
						          },
								  success: function( data ) {						         
									  response( $.map( data, function( item ) {
										    return {
												id: item.id,
										        label: item.label,
												value: item.value
										    }
										}));
									}
						        });
						      },
						      minLength: 1,
						      select: function( event, ui ) {
						        console.log( ui.item ?
						          'Selected: id=' + ui.item.id+' label=' + ui.item.label  :
						          'Nothing selected, input was ' + this.value);
								 document.getElementById('fmSTSURVEY').value=ui.item.id;
								 //me.YangMenerima();
						      },
						      open: function() {
						        $( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
						      },
						      close: function() {
						        $( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
								//me.tarif();
						      }
						    });
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
	
	jenisEkstra: function(){
		var me = this;
		if(document.getElementById('boxchecked')){
			var jmlcek = document.getElementById('boxchecked').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Pilih '+jmlcek+' Data ?')){
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcoverStSurvey';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: this.url+'&tipe=formJnsEkstra',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						//me.autocomplete_initial();
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
	
	jenisLain: function(){
		var me = this;
		if(document.getElementById('boxchecked')){
			var jmlcek = document.getElementById('boxchecked').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Pilih '+jmlcek+' Data ?')){
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcoverStSurvey';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: this.url+'&tipe=formJnsLain',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						//me.autocomplete_initial();
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
	
	/*
	StSurvey: function(){
		var me = this;
		if(document.getElementById('boxchecked')){
			var jmlcek = document.getElementById('boxchecked').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Pilih '+jmlcek+' Data ?')){
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcoverStSurvey';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: this.url+'&tipe=formStSurvey',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						me.autocomplete_initial();
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
	},*/
	
	Test: function(){	
			var aForm = document.getElementById(this.formName);		
				aForm.action= this.url+'&tipe=test';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
				aForm.target='_blank';
				aForm.submit();	
				aForm.target='';	
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
	
	
	SimpanJnsLain: function(mode){
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
		$.ajax({
			type:'POST', 
			data:$('#'+formName_+'_form').serialize(),
			url: this.url+'&tipe=simpanJnsLain',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);						
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
			
			var cover = 'RefStatusBarang_getdata';
			addCoverPage2(cover,1,true,false);				
			$.ajax({
				url: 'pages.php?Pg=RefStatusBarang&tipe=getdata&id='+this.idpilih,
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
