
var template = new DaftarObj2({
	prefix : 'template',
	url : 'pages.php?Pg=ref_template', 
	formName : 'templateForm',
	template_form : '0',
	loading: function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		this.sumHalRender();
	
	},
	pilihBidang: function(){
			template.refreshList(true);	
			$('#tanggal').datepicker();
	},
	pilihUnit: function(){
			template.refreshList(true);	
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
	BidangAfter2: function(){
		var me = this;
		
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=BidangAfter2',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				template.refreshList(true);
				document.getElementById('fmSKPDskpd').innerHTML=resp.content;
				document.getElementById('fmSKPDskpd').selectedIndex = 0;

		  }
		});
	},

	BidangAfterform: function(){
		var me = this;
			  $.ajax({
			  url: this.url+'&tipe=BidangAfterForm',
			  type : 'POST',
			  data:{ fmSKPDBidang: $("#cmbBidangForm").val(),
			  		 fmSKPDUrusan: $("#cmbUrusanForm").val(),
					 fmSKPDskpd : $("#cmbSKPDForm").val(),
					 fmSKPDUnit : $("#cmbUnitForm").val()  },
			  success: function(data) {
				var resp = eval('(' + data + ')');
					document.getElementById('cmbBidangForm').innerHTML = resp.content.bidang;
					document.getElementById('cmbSKPDForm').innerHTML = resp.content.skpd;
					document.getElementById('cmbUnitForm').innerHTML = resp.content.unit;				
			  }
			});
		



	},

	detailTemplate: function(id){
		var me = this;
		detailTemplate.windowShow(id);
	

	},
	buttonEditDiatas: function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=buang',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						template.Edit(resp.content);
					}
			  	}
			});
			
		}else{
			alert(errmsg);
		}

	},
	daftarRender:function(){
		var me =this; 
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
		
		
		
		if ($("#fmSKPDUrusan").val() == '00'){
			err = "Pilih Urusan";
		}else if($("#fmSKPDBidang").val() == '00'){
			err = "Pilih Bidang";
		}else if($("#fmSKPDskpd").val() == '00'){
			err = "Pilih SKPD";
		} 
		
		if (err =='' ){		

			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			if(me.template_form==0){//baru dari template
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
	    			$("#tanggal").datepicker({ dateFormat: 'dd-mm-yy' });
						  $.ajax({
						  url: this.url+'&tipe=BidangAfterForm',
						  type : 'POST',
						  data:{ fmSKPDBidang: $("#fmSKPDBidang").val(),
						  		 fmSKPDUrusan: $("#fmSKPDUrusan").val(),
								 fmSKPDskpd : $("#fmSKPDskpd").val(),
								 fmSKPDUnit : $("#fmSKPDUnit").val()  },
						  success: function(data) {
							var resp = eval('(' + data + ')');	
								document.getElementById('cmbBidangForm').innerHTML = resp.content.bidang;
								document.getElementById('cmbSKPDForm').innerHTML = resp.content.skpd;
								document.getElementById('cmbUnitForm').innerHTML = resp.content.unit;	
												  $.ajax({
								  url: 'pages.php?Pg=detailTemplate&tipe=setTempTemplate',
								  type : 'POST',
								  data : {
								  			cmbUrusanForm : document.getElementById('cmbUrusanForm').value,
								  			cmbBidangForm : document.getElementById('cmbBidangForm').value,
								  			cmbSKPDForm : document.getElementById('cmbSKPDForm').value,
								   			cmbUnitForm : document.getElementById('cmbUnitForm').value,
											nama_template : $("#nama_template").val(),
											tanggal : $('#tanggal').datepicker({ dateFormat: 'yy-mm-dd' }).val(),
											nomor_distribusi : $("#nomor").val()
								   },
								  success: function(data) {
								  
									
								  }
								});
								
											
						  }
						});
			  	}
			});

		
		}else{
		 	alert(err);
		}
	},
	AfterFormBaru:function(){ 
		document.getElementById('detailTemplate').innerHTML = 
			"<div id='detailTemplate_cont_title' style='position:relative'></div>"+
			"<div id='detailTemplate_cont_opsi' style='position:relative'>"+
			"</div>"+
			"<div id='detailTemplate_cont_daftar' style='position:relative'></div>"+
			"<div id='detailTemplate_cont_hal' style='position:relative'></div>"
			;
	detailTemplate.loading();
	
	},	
	AfterFormEdit:function(){ 
		document.getElementById('detailTemplateEdit').innerHTML = 
			"<div id='detailTemplateEdit_cont_title' style='position:relative'></div>"+
			"<div id='detailTemplateEdit_cont_opsi' style='position:relative'>"+
			"</div>"+
			"<div id='detailTemplateEdit_cont_daftar' style='position:relative'></div>"+
			"<div id='detailTemplateEdit_cont_hal' style='position:relative'></div>"
			;
	detailTemplateEdit.loading();
	
	},
	Edit:function(id){
		var me = this;
		var target = this.url+'&tipe=formEdit';
		errmsg = '';
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();		
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,999,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:{id : id},
				url: this.url+'&tipe=moveTemplateToTemp',
				success: function(data) {	
						$.ajax({
							type:'POST', 
							data:{template_cb : id},
							url: target,
						  	success: function(data) {		
								var resp = eval('(' + data + ')');	
								if (resp.err ==''){		
									document.getElementById(cover).innerHTML = resp.content;
									me.AfterFormEdit();
				    				$("#tanggal").datepicker({ dateFormat: 'dd-mm-yy' });
								}else{
									alert(resp.err);
									delElem(cover);
									document.body.style.overflow='auto';
								}
						  	}
						});
				}
			});		
		}else{
			alert(errmsg);
		}
		
	},
	Batal : function(){
		var me = this;
		$.ajax({
			type:'POST', 
			data:{halo: 'halo'},
			url: this.url+'&tipe=destroyCookies',
		  	success: function(data) {		
				
		  	}
		});
		me.Close();
		me.refreshList(true);

		
	},
	SubmitEdit: function(id){
		
		var me = this;
			if($("#nama_template").val() == '' || $('#tanggal').val() == '' || $("#nomor").val() == '' || document.getElementById('cmbUnitForm').value == '' || document.getElementById('cmbUnitForm').value == '00'  ){
				alert("Lengkapi");
			}else{
				var postTanggal = $('#tanggal').val().split("-");
						 $.ajax({
								type : 'POST',
								url: this.url+'&tipe=kembalikanKeTemplate',
								data:	{ 
										 nama : $("#nama_template").val(),
										 tanggal : postTanggal[2] + "-" + postTanggal[1] + "-" + postTanggal[0],
										 nomor : $("#nomor").val(),
										 c1: document.getElementById('cmbUrusanForm').value,
							  			 c : document.getElementById('cmbBidangForm').value,
							  			 d : document.getElementById('cmbSKPDForm').value,
							   			 e : document.getElementById('cmbUnitForm').value,
										 ID: id
										 
									 	 },
								  	success: function(data) {	
										me.Close()
										me.AfterSimpan();
										me.refreshList(true);
									}
								});		
				}

		
		
		
		
	},	
	Simpan: function(){
	
		var me = this;
			if($("#nama_template").val() == '' || $('#tanggal').val() == '' || $("#nomor").val() == '' || document.getElementById('cmbUnitForm').value == '' || document.getElementById('cmbUnitForm').value == '00'  ){
				alert("Lengkapi");
			}else{
				var postTanggal = $('#tanggal').val().split("-");
						 $.ajax({
								type : 'POST',
								url: this.url+'&tipe=updateTempRincianTemplate',
								data:	{ 
										 nama : $("#nama_template").val(),
										 tanggal : postTanggal[2] + "-" + postTanggal[1] + "-" + postTanggal[0],
										 nomor : $("#nomor").val(),
										 c1: document.getElementById('cmbUrusanForm').value,
							  			 c : document.getElementById('cmbBidangForm').value,
							  			 d : document.getElementById('cmbSKPDForm').value,
							   			 e : document.getElementById('cmbUnitForm').value
										 
									 	 },
								  	success: function(data) {	
										me.Close()
										me.AfterSimpan();
										me.refreshList(true);
									}
								});		
				}

		
		
		
	}
		
});