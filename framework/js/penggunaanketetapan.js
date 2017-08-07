var PenggunaanKetetapanSkpd = new SkpdCls({
	prefix : 'PenggunaanKetetapanSkpd', formName:'PenggunaanKetetapanForm'
});


var PenggunaanKetetapan = new DaftarObj2({
	prefix : 'PenggunaanKetetapan',
	url : 'pages.php?Pg=PenggunaanKetetapan', 
	formName : 'PenggunaanKetetapanForm',// 'ruang_form',
	
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		//var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		//var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		//var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		
		//if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		//if(err=='' && (unit=='' || unit=='00') ) err='ASISTEN/OPD belum dipilih!';
		//if(err=='' && (subunit=='' || subunit=='00') ) err='BIRO/ UPTD/B belum dipilih!';
		
		
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
	
	CariPenggunaan: function(){
		var me = this;	
		
		//Penggunaan.fmSKPD = document.getElementById('c').value;
		//Penggunaan.fmUNIT = document.getElementById('d').value;
		//Penggunaan.fmSUBUNIT = document.getElementById('e').value;
		Penggunaan.el_ipk= document.getElementById('PenggunaanKetetapan_idplh').value;
		Penggunaan.windowSaveAfter= function(){};
		Penggunaan.windowShow();	
	},	
	
	AfterFormBaru:function(){ 
		
	ipk = document.getElementById('PenggunaanKetetapan_idplh').value;
	document.getElementById('daftarpenggunaan').innerHTML = 
			//"<div id='Penggunaan_cont_title' style='position:relative'></div>"+
			"<div id='Penggunaan_Cari_cont_opsi' style='position:relative'>"+
			"<input type='hidden' name='ipk' id='ipk' value='"+ipk+"'>"+
			"</div>"+
			"<div id='Penggunaan_Cari_cont_daftar' style='position:relative'></div>"+
			"<div id='Penggunaan_Cari_cont_hal' style='position:relative'></div>"			;
		//generate data
	   Penggunaan_Cari.loading();
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
	
	AfterFormEdit:function(){ 
		
	ipk = document.getElementById('PenggunaanKetetapan_idplh').value;
	document.getElementById('daftarpenggunaan').innerHTML = 
			//"<div id='Penggunaan_cont_title' style='position:relative'></div>"+
			"<div id='Penggunaan_Cari_cont_opsi' style='position:relative'>"+
			"<input type='hidden' name='ipk' id='ipk' value='"+ipk+"'>"+
			"</div>"+
			"<div id='Penggunaan_Cari_cont_daftar' style='position:relative'></div>"+
			"<div id='Penggunaan_Cari_cont_hal' style='position:relative'></div>"			;
		//generate data
	   Penggunaan_Cari.loading();
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
	},	
	
	Batal : function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);
			if(confirm('Batalkan Data ini?')){			
				var cover = this.prefix+'_batalcover';
				addCoverPage2(cover,999,true,false);	
				document.body.style.overflow='hidden';
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=batal',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');	
						if (resp.err ==''){		
							document.getElementById(cover).innerHTML = resp.content;
							//me.AfterFormEdit(resp);
							alert('Data berhasil dibatalkan');
							delElem(cover);
							me.refreshList(true);
						}else{
							alert(resp.err);
							delElem(cover);
							//document.body.style.overflow='auto';
						}
				  	}
				});
			}
		}else{
			alert(errmsg);
		}
		
	},			
	
	cetakSK: function (){	
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=cetakSK';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		
		
	},		
	
	
});
