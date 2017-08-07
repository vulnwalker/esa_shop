var ReclassSkpd = new SkpdCls({
	prefix : 'ReclassSkpd', formName:'adminForm'
});


var Reclass = new DaftarObj2({
	prefix : 'Reclass',
	url : 'pages.php?Pg=Reclass', 
	formName : 'adminForm',// 'ruang_form',
	
	reClass: function(){	
		var errmsg = '';
		/*if(document.getElementById(this.elJmlCek)){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}*/
		
		//errmsg = this.CekCheckboxBi();
		if(errmsg ==''){ 
			var box = this.GetCbxCheckedBi();
			//var stat = box.getAttribute("stat");
			//if(stat==0){
				
				var aForm = document.getElementById(this.formName);		
				aForm.action=this.url+'&tipe=formBaru&idasal='+box.value;
				aForm.target='_blank';
				aForm.submit();	
				aForm.target='';
			//}else{
			//	alert('RKB sudah DKB!');
			//}
		}else{
			alert(errmsg);
		}
	},
	
	Simpan2:function(){
		var me= this;	
		this.OnErrorClose = false	
		//document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		/*this.sendReq(
			this.url,
			{ idprs: 0, daftarProses: new Array('simpan')},
			this.formDialog);*/
		$.ajax({
			type:'POST', 
			data:$('#adminForm').serialize(),
			url: this.url+'&tipe=simpan2',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					//me.Close();
					//me.AfterSimpan();
					window.close();
				}else{
					alert(resp.err);
				}
		  	}
		});
	} ,
	
	getNoRegAkhir : function(){
		//alert('tes no akhir');
		$.ajax({
			type:'POST', 
			data:$('#adminForm').serialize(),
			url: this.url+'&tipe=getNoRegAkhir',
		  	success: function(data) {
				var resp = eval('(' + data + ')');					
				document.getElementById('noreg').value = resp.content.noreg;				
				//delElem(cover);
				//document.body.style.overflow='auto';
				/*if(resp.err==''){
					window.close();
				}else{
					alert(resp.err);
				}*/
		  	}
		});	
	},
	
	formSetDetailEntry : function(){
		//set entry sesuai jenis barang 
		//alert('tes');
		var kdbrg = document.getElementById('fmIDBARANG').value;
		var jnsbrg = kdbrg.substring(0,2);
		//if( jnsbrg != '' && jnsbrg.length == 2){
		document.getElementById('tidaktercatat_formkiba').style.display = 'none';
		document.getElementById('tidaktercatat_formkibb').style.display = 'none';
		document.getElementById('tidaktercatat_formkibc').style.display = 'none';
		document.getElementById('tidaktercatat_formkibd').style.display = 'none';
		document.getElementById('tidaktercatat_formkibe').style.display = 'none';
		document.getElementById('tidaktercatat_formkibf').style.display = 'none';
		document.getElementById('tidaktercatat_formalamat').style.display = 'none';
		document.getElementById('tidaktercatat_formdoc').style.display = 'none';
		document.getElementById('tidaktercatat_formluas').style.display = 'none';
		document.getElementById('tidaktercatat_formstatustanah').style.display = 'none';
		document.getElementById('tidaktercatat_formkodetanah').style.display = 'none';
		document.getElementById('tidaktercatat_formstatuspenguasaan').style.display = 'none';
		document.getElementById('tidaktercatat_formkonstruksi').style.display = 'none';
		var divid = '';
		switch(jnsbrg){
			case '01': divid= 'tidaktercatat_formkiba'; 
				document.getElementById('tidaktercatat_formalamat').style.display='block';	
				document.getElementById('tidaktercatat_formluas').style.display = 'block';
				document.getElementById('tidaktercatat_formstatuspenguasaan').style.display = 'block';
				
			break;			
			case '02': divid= 'tidaktercatat_formkibb';	break;			
			case '03': divid= 'tidaktercatat_formkibc'; 
				document.getElementById('tidaktercatat_formalamat').style.display='block';	
				document.getElementById('tidaktercatat_formdoc').style.display='block';	
				document.getElementById('tidaktercatat_formluas').style.display = 'block';
				document.getElementById('tidaktercatat_formstatustanah').style.display = 'block';				
				document.getElementById('tidaktercatat_formkodetanah').style.display = 'block';	
				document.getElementById('tidaktercatat_formstatuspenguasaan').style.display = 'block';	
				document.getElementById('tidaktercatat_formkonstruksi').style.display = 'block';		
			break;			
			case '04': 
				divid= 'tidaktercatat_formkibd';	
				document.getElementById('tidaktercatat_formalamat').style.display='block'; 
				document.getElementById('tidaktercatat_formdoc').style.display='block';	
				document.getElementById('tidaktercatat_formluas').style.display = 'block';
				document.getElementById('tidaktercatat_formstatustanah').style.display = 'block';
				document.getElementById('tidaktercatat_formkodetanah').style.display = 'block';		
				document.getElementById('tidaktercatat_formstatuspenguasaan').style.display = 'block';		
			break;			
			case '05': divid= 'tidaktercatat_formkibe';	break;			
			case '06': divid= 'tidaktercatat_formkibf';	
				document.getElementById('tidaktercatat_formalamat').style.display='block'; 
				document.getElementById('tidaktercatat_formdoc').style.display='block';	
				document.getElementById('tidaktercatat_formluas').style.display = 'block';
				document.getElementById('tidaktercatat_formstatustanah').style.display = 'block';
				document.getElementById('tidaktercatat_formkodetanah').style.display = 'block';		
				//document.getElementById('tidaktercatat_formstatuspenguasaan').style.display = 'block';	
				document.getElementById('tidaktercatat_formkonstruksi').style.display = 'block';	
			break;	
			case '07': divid= 'tidaktercatat_formkibg';	
				/*document.getElementById('uraian').style.display='block'; 
				document.getElementById('pencipta').style.display='block';	
				document.getElementById('jenis').style.display = 'block';					*/
			break;			
		}
		document.getElementById(divid).style.display = 'block';
		
		this.getNoRegAkhir();
		
	},
	/*
	
	*/
	/*
	
	*/
	/*
	Simpan : function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);			
		$.ajax({
			type:'POST', 
			data:$('#adminForm').serialize(),
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);				
				document.body.style.overflow='auto';		
				if(resp.err==''){
					window.close();					
				}else{
					alert(resp.err);
				}
		  	}
		});
	},	
	*/
	pilihRuang : function(){		
		var me = this;		
		
		RuangPilih.fmSKPD = document.getElementById('c').value;
		RuangPilih.fmUNIT = document.getElementById('d').value;
		RuangPilih.fmSUBUNIT = document.getElementById('e').value;
		RuangPilih.fmSEKSI = document.getElementById('e1').value;
			
		RuangPilih.el_idruang= 'ref_idruang';
		RuangPilih.el_nmgedung= 'nm_gedung';
		RuangPilih.el_nmruang= 'nm_ruang';
		RuangPilih.windowShow();
	},
	
	pilihPejabatPemegang2: function(){
	 //alert('tes')
		var me = this;	
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e').value;
		PegawaiPilih.el_idpegawai = 'ref_idpengadaan1';
		PegawaiPilih.el_nip= 'nip_pejabat_pengadaan1';
		PegawaiPilih.el_nama= 'nama_pejabat_pengadaan1';
		PegawaiPilih.el_jabat= 'jbt_pejabat_pengadaan1';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();
	},
	formNoDialog_show:function(){
		
	},
	/*
	Cetak_kerja2:function(){	
	   //alert('tes')
	   	var me = this;
		//errmsg = this.CekCheckbox(); //if(errmsg ==''){ 
		//var box = this.GetCbxChecked();
		
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=genCetak_kerja2';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		
	},
	*/
});
