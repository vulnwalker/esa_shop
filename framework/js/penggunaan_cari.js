var Penggunaan_CariSkpd = new SkpdCls({
	prefix : 'Penggunaan_CariSkpd', formName:'PenggunaanKetetapan_form'
});


var Penggunaan_Cari = new DaftarObj2({
	prefix : 'Penggunaan_Cari',
	url : 'pages.php?Pg=Penggunaan_Cari', 
	formName : 'PenggunaanKetetapan_form',// 'ruang_form',
	el_ipk : '',
	
	CariPenggunaan : function(){	
		
		var me = this;
		var err='';
			
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formCariPenggunaan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormCariPenggunaan();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	
	AfterFormCariPenggunaan:function(){ 
		
	//id_jual = document.getElementById('FarmasiKunjungan_idplh').value;
	document.getElementById('daftarcaripenggunaan').innerHTML = 
			//"<div id='Penggunaan_cont_title' style='position:relative'></div>"+
			"<div id='Penggunaan_cont_opsi' style='position:relative'>"+
			//"<input type='hidden' name='id_jual' id='id_jual' value='"+id_jual+"'>"+
			"</div>"+
			"<div id='Penggunaan_cont_daftar' style='position:relative'></div>"+
			"<div id='Penggunaan_cont_hal' style='position:relative'></div>"
			//"<div><input type='button' name='cariobat' id='cariobat' value='Tambah'  onclick=\"javascript:FarmasiJualDetail.Cari()\" >"+
			//"<input type='button' name='editobat' id='editobat' value='Edit'  onclick=\"javascript:FarmasiJualDetail.Edit("+menu+")\" >"+			
			//"<input type='button' name='hapusobat' id='hapusobat' value='Hapus'  onclick=\"javascript:FarmasiJualDetail.Hapus()\" ></div>"
			;
		//generate data
	   Penggunaan.loading();
	},
	
	PilihPenggunaan:function(){ //pilih cari
		var me = this;
		errmsg = '';
		alert(Penggunaan.daftarPilih);	 	
		if((errmsg=='') && (Penggunaan.daftarPilih.length == 0 )){
			errmsg= 'Data belum dipilih!';
		}
		if((errmsg=='') && (Penggunaan.daftarPilih.length > 1 )){
			errmsg= 'Pilih 1 data!';
		}
		if(errmsg ==''){	
			//alert('simpan');
			$.ajax({
				type:'POST', 
				data:$('#PenggunaanForm').serialize(),
				url: this.url +'&tipe=simpanPilihPenggunaan',
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.Close();
						Penggunaan.refreshList(true);
						//Pemanfaatan.refreshList(true);						
						//Pemanfaatan.resetPilih();
						//fmIDBARANG, fmNMBARANG, noreg, thn_perolehan
						//document.getElementById('idbi').value = resp.content.idbi;
						//document.getElementById('c').value = resp.content.c;
						//document.getElementById('d').value = resp.content.d;
						//document.getElementById('e').value = resp.content.e;
						//document.getElementById('e1').value = resp.content.e1;
						
						
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
	},	
	
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow&ipk='+me.el_ipk,//&fmSKPD='+me.fmSKPD+'&fmUNIT='+me.fmUNIT+'&fmSUBUNIT='+me.fmSUBUNIT,
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
	
	windowSaveAfter: function(){
		//alert('tes');
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
			ipk = document.getElementById('ipk').value;
			
			
			var cover = 'Penggunaan_Cari_cover';
			addCoverPage2(cover,1,true,false);				
			$.ajax({
				url: 'pages.php?Pg=Penggunaan_Cari&tipe=windowsave&id='+this.idpilih+'&ipk='+ipk,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						me.windowClose();
						me.windowSaveAfter();
						Penggunaan.refreshList(true);
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
		alert('tes');
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
	
	Usulan : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='ASISTEN/OPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='BIRO/ UPTD/B belum dipilih!';
		
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formUsulan',
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
	
	cetakSK: function (){	
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=cetakSK';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		
		
	},		
	
	
});
