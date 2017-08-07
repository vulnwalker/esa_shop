var dppbSkpd = new SkpdCls({
	prefix : 'dppbSkpd', formName:'adminForm'
});

var dppb = new DaftarObj2({
	prefix : 'dppb',	
	url : 'pages.php?Pg=dppb&ajx=1',
	formName : 'adminForm',
	
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
	pilihPembuatKomitmen: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idkomitmen';
		PegawaiPilih.el_nip= 'nip_pembuat_komitmen';
		PegawaiPilih.el_nama= 'nama_pembuat_komitmen';
		PegawaiPilih.el_jabat= 'jbt_pembuat_komitmen';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	
	Baru2: function(){	
		var err='';
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		
		if(err==''){
			var aForm = document.getElementById(this.formName);		
			aForm.action=this.url+'&tipe=formBaru2';
			aForm.target='_blank';
			aForm.submit();	
			aForm.target='';
		}else{
			alert(err);
		}
	},
	
	Edit2: function(){	
		if(document.getElementById(this.elJmlCek)){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			var stat = box.getAttribute("stat");
			if(stat==0){
				
				var aForm = document.getElementById(this.formName);		
				aForm.action=this.url+'&tipe=formEdit2';
				aForm.target='_blank';
				aForm.submit();	
				aForm.target='';
			}else{
				alert('RKB sudah DKB!');
			}
		}else{
			alert(errmsg);
		}
	},
	
	
	
	
	Simpan2: function(){
		var err ='';
		if(err == ''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=simpan2',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						alert('Sukses Simpan Data');
						window.close();		
					}else{
						alert(resp.err);
					}					
			  	}
			});
			
		}else{
			alert(err);
		}
		
	},
	
	
	
	formNoDialog_show: function(){
		
	},
	
	
	hitungSisa: function(){
		var jmldkb = document.getElementById('jml_dkb').value;
		var jml = document.getElementById('jml_barang').value;
		var jmlada = document.getElementById('jml_ada').value;
		if (jmldkb == '') jmldkb=0;
		if (jml == '') jml=0;
		if (jmlada == '') jmlada=0;
		document.getElementById('jml_sisa').value = parseInt(jmldkb) - ( parseInt(jmlada) + parseInt(jml) );
		
	},
});