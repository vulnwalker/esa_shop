


var RuangPilih = new DaftarObj2({
	prefix : 'RuangPilih',
	url : 'pages.php?Pg=RuangPilih', 
	formName : 'RuangPilih_Form',
	idpilih:'',
	fmSKPD:'',
	fmUNIT:'',
	fmSUBUNIT:'',
	fmSEKSI:'',
	fmIBARANG:'',
	el_idruang: 'ref_idruang',
	el_nmgedung: 'nm_gedung',
	el_nmruang: 'nm_ruang',
	
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		//var skpd = document.getElementById('SensusSkpdfmSKPD').value;
		
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow&fmSKPD='+me.fmSKPD+'&fmUNIT='+me.fmUNIT+'&fmSUBUNIT='+me.fmSUBUNIT+'&fmSEKSI='+me.fmSEKSI,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');							
				document.getElementById(cover).innerHTML = resp.content;	
				me.loading();						
		  	}
		});
	},	
	windowClose: function(){
		document.body.style.overflow='auto';
		delElem(this.prefix+'_cover');
	},
	windowSave: function(){
		//alert('save');
		var me = this;
		var kib = this.fmIDBARANG.substr(0,2);
		var errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();	//alert(box.value);
			this.idpilih = box.value;
			//cek yg dipilih ruang -------------------			
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: 'pages.php?Pg=ruang&tipe=getdata&id='+this.idpilih,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					//alert(resp.content.nm_ruang);							
					if(resp.content.q == '0000' && kib!='03') {
						alert('Pilih hanya Ruangan! Kode ruangan tidak boleh xxx.0000')
					}else{						
						if(document.getElementById(me.el_idruang)) document.getElementById(me.el_idruang).value= me.idpilih;
						if(document.getElementById(me.el_nmgedung)) document.getElementById(me.el_nmgedung).value= resp.content.nm_gedung;
						if(document.getElementById(me.el_nmruang)) document.getElementById(me.el_nmruang).value= resp.content.nm_ruang;
						me.windowClose();
						me.windowSaveAfter();
					}
			  	}
			});
			
		}else{
			alert(errmsg);
		}
	},
	windowSaveAfter: function(){
		//alert('tes');
	},
	pilihPejabatPengadaan: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idpengadaan';
		PegawaiPilih.el_nip= 'nip_pejabat_pengadaan';
		PegawaiPilih.el_nama= 'nama_pejabat_pengadaan';
		PegawaiPilih.el_jabat= 'jbt_pejabat_pengadaan';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	
	
});

var RuangSkpd = new SkpdCls({
	prefix : 'RuangSkpd', formName:'RuangForm',
	
	pilihBidangAfter : function(){Ruang.refreshList(true);},
	pilihUnitAfter : function(){Ruang.refreshList(true);},
	pilihSubUnitAfter : function(){Ruang.refreshList(true);},
	pilihSeksiAfter : function(){Ruang.refreshList(true);}
});

var Ruang = new DaftarObj2({
	prefix : 'Ruang',
	url : 'pages.php?Pg=ruang', 
	formName : 'adminForm',// 'ruang_form',
	
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById('RuangSkpdfmSKPD').value; 
		var unit = document.getElementById('RuangSkpdfmUNIT').value;
		var subunit = document.getElementById('RuangSkpdfmSUBUNIT').value;
		var seksi = document.getElementById('RuangSkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		
		
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
	
	pilihPejabatPengadaan: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idpengadaan';
		PegawaiPilih.el_nip= 'nip_pejabat_pengadaan';
		PegawaiPilih.el_nama= 'nama_pejabat_pengadaan';
		PegawaiPilih.el_jabat= 'jbt_pejabat_pengadaan';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	
	formPilih : function(){	
		var me = this;		
		RuangPilih.windowSaveAfter= function(){
			me.pilihRuangAfter(this.idpilih);
		}
		RuangPilih.windowShow();
		
		
		/*var me = this;
		var cover = this.prefix+'_formPilihcover';
		document.body.style.overflow='hidden';
		//var skpd = document.getElementById('SensusSkpdfmSKPD').value;
		
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=formPilih',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');			
				document.getElementById(cover).innerHTML = resp.content;	
				RuangPilih.loading();		
				//me.AfterFormPilih();
		  	}
		});
		*/
	},
	/*
	RuangPilihClose : function(){
		delElem(this.prefix+'_formPilihcover');	
	},
	pilihRuangAfter: function(idpilih){
		alert('pilih '+idpilih);
		var cover = 'coverSimpanPilihRuang';
		//save ---------------------
		addCoverPage2(cover,1,true,false);	
			//document.body.style.overflow='hidden';
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=formEdit',
			success: function(data) {		
				var resp = eval('(' + data + ')');			
				//document.getElementById(cover).innerHTML = resp.content;					
			}
		});
		
	}
	*/
	
	
});

