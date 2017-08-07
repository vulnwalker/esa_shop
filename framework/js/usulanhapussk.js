var UsulanHapusskSkpd = new SkpdCls({
	prefix : 'UsulanHapusbaSkpd', formName:'adminForm'
});

var UsulanHapussk = new DaftarObj2({
	prefix : 'UsulanHapussk',
	url : 'pages.php?Pg=usulanhapussk', 
	formName : 'adminForm',// 'ruang_form',
	
	/*previewKepSekda: function(ids){
		
	},*/
	expDocKepSekDa:function(){
		var id = document.getElementById('id').value;
		adminForm.action= this.url+'&tipe=genKepSekda&xls=1&id='+id;//'?Op='+op+'&Pg=2&idprs=cetak_hal';				
		adminForm.submit();	
		adminForm.target='';	
	},
	showKepsekda: function(){
		//alert('tes');
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();			
			
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=getIdBA&idsk='+box.value,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){	
						var ids = resp.content;
						//var ids = Array(1,5);
						//me.previewKepSekda(ids);
						for (i=0 ; i< ids.length; i++ ){
							//alert(ids[i]);
							//var id = document.getElementById(this.prefix+'_cb'+i).value
							//var fm = document.getElementById('adminForm');
							//fm.action='pages.php?Pg=usulanhapussk&tipe=genKepSekda&id='+ids[i];
							//fm.target = '_blank';
							//fm.submit();										
							//fm.target = '';							
							window.open('pages.php?Pg=usulanhapussk&tipe=genKepSekda&id='+ids[i],'_blank');
							
						}
					}else{
						alert(resp.err);
						
					}
			  	}
			});

		}else{
			alert(errmsg);
		}


		/*var me =this;
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			var jmldata = document.getElementById(this.prefix+'_jmldatapage').value;
			for (i=1 ; i<=jmldata; i++ ){
				//alert(i);
				if(document.getElementById(this.prefix+'_cb'+i).checked ){
					var id = document.getElementById(this.prefix+'_cb'+i).value
					adminForm.action='pages.php?Pg=usulanhapussk&tipe=genKepGub&id='+id;
					adminForm.target = '_blank';					
					adminForm.Act.value='';
					adminForm.target = '';
				}
			}
		}*/

	},
	
	simpanDraftUsulSK: function(){
		//alert('simpan');
		var cover = 'cetak_formcover';
		//document.body.style.overflow='hidden';
		//addCoverPage2(cover,1,true,false);
		//alert(document.getElementById('d2').value)
		for(i=1;i<=28;i++){
			 document.getElementById('d_'+i).value= document.getElementById(i).innerHTML;
		}
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
		  	url: this.url+'&tipe=simpanDraftUsulSK',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				//document.getElementById(cover).innerHTML = resp.content;			
				//delElem(cover);
				//me.AfterFormBaru();
				if(resp.err==''){
					alert('Sukses Simpan');
				}else{
					alert(resp.err);
				}
		  	}
		});
	},
	draftUsulSK: function(){
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			var id= box.value;
			adminForm.action= this.url+'&tipe=genKepGub&id='+id;//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
			adminForm.target='_blank';
			adminForm.submit();	
			adminForm.target='';
		}
	},	
	expDocDraftUsulSK: function(){
			var id = document.getElementById('id').value;
			adminForm.action= this.url+'&tipe=genKepGub&xls=1&id='+id;//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
			//adminForm.target='_blank';
			adminForm.submit();	
			adminForm.target='';
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
	
	AfterFormBaru:function()
	{
		//alert("tes");
		document.getElementById('div_detail').innerHTML =
			"<div id='UsulanHapusskdet_cont_title' style='position:relative'></div>"+
			"<div id='UsulanHapusskdet_cont_opsi' style='position:relative'></div>"+
			"<div id='UsulanHapusskdet_cont_daftar' style='position:relative'></div>"+
			"<div id='UsulanHapusskdet_cont_hal' style='position:relative'></div>";
		//generate data
		UsulanHapusskdet.loading();
	},
	
	AfterFormEdit:function()
	{
		//alert("tes");
		this.AfterFormBaru()
	},
	
	Baru: function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		//var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		//var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		//var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		/**
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='ASISTEN/OPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='BIRO/ UPTD/B belum dipilih!';
		**/
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
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
	
	Barusk: function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		/**
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='ASISTEN/OPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='BIRO/ UPTD/B belum dipilih!';
		**/
		var me = this;
		err = this.CekCheckbox(); //untuk cek error
		//if(errmsg =='')
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formEditsk',
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
	
	pilihPejabatPengadaan: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = "04"//l//old document.getElementById('c').value;
		PegawaiPilih.fmUNIT = "05"//old document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT ="00" //old document.getElementById('e').value;
		PegawaiPilih.fmSEKSI ="000" //old document.getElementById('e').value;
		PegawaiPilih.el_idpegawai = 'ref_idpengadaan';
		PegawaiPilih.el_nip= 'nip_pejabat_pengadaan';
		PegawaiPilih.el_nama= 'nama_pejabat_pengadaan';
		PegawaiPilih.el_jabat= 'jbt_pejabat_pengadaan';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	simpansk:function()
	{
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpansk',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					alert("Sukses")
					me.Close();
					me.refreshList(true)
				}else{
					alert(resp.err);
				}
		  	}
		});
	
	},
	
	Cari:function()
	{
		
		var cover = this.prefix+'_formcoverbacari';
			addCoverPage2(cover,999,true,false);
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formbaCari',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err =='')
						{		
						 document.getElementById(cover).innerHTML = resp.content;
						document.getElementById('div_detailbacari').innerHTML = 
						//alert("tes");
						"<div id='UsulanHapusbacari_cont_title' style='position:relative'></div>"+
						"<div id='UsulanHapusbacari_cont_opsi' style='position:relative'></div>"+
						"<div id='UsulanHapusbacari_cont_daftar' style='position:relative'></div>"+
						"<div id='UsulanHapusbacari_cont_hal' style='position:relative'></div>";
						
						UsulanHapusbacari.resetPilih()	//reset checkbox
						//refresh data
						UsulanHapusbacari.refreshList(true)
						}
						else
						{
						alert(resp.err);
						//delElem(cover);
						document.body.style.overflow='auto';
						}
			  	}
			});
		
	},
	Closecari:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcoverbacari';
		if(document.getElementById(cover)) delElem(cover);			
		document.body.style.overflow='auto';					
	},
	
	Batalcari:function(){	
	 	var me =this;
			if(confirm('Batalkan Data ?'))
			{
				//document.body.style.overflow='hidden'; 
				var cover = this.prefix+'_batalcari';
				addCoverPage2(cover,999,true,false);
				
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=batalcari',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');		
						delElem(cover);		
						if(resp.err==''){							
							//alert("sukses")	
								me.Close();//close();
							
						}else{
							alert(resp.err);
						}							
						
				  	}
				});
				
				
			}	
			
	},
		
	Batalsk:function(){	
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
				url: this.url+'&tipe=batalsk',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						alert("Sukses")
						me.Close();
						me.refreshList(true)
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
		
	Pilihbacari:function()
	{		
		document.getElementById(this.prefix+'_daftarpilih').value= UsulanHapusbacari.daftarPilih //ambil daftar pilih
	    document.getElementById(this.prefix+'_daftarsesi').value =document.getElementById('sesi').value//ambil sesi
	    document.getElementById(this.prefix+'_daftarid').value =document.getElementById('UsulanHapussk_idplh').value//ambil Id
		//alert(document.getElementById('UsulanHapussk_idplh').value)
		//alert(sesi)
		
		var Pil = document.getElementById(this.prefix+'_daftarpilih').value
		
		if(Pil !='')
		{
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_coverpilihbacari';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_formbacari').serialize(),
			url: this.url+'&tipe=pilihbacari',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					//alert("sukses")
					me.Closecari();
					UsulanHapusskdet.refreshList(true)
				}else{
					alert(resp.err);
				}
		  	}
		 });
		
		}
		else
		{
			alert('Pilih dulu')
		}
		
	}
	
	
});
