var SensusTidakTercatatSkpd = new SkpdCls({
	prefix : 'SensusTidakTercatatSkpd', formName:'adminForm'
});


var SensusTidakTercatat = new DaftarObj2({
	prefix : 'SensusTidakTercatat',
	url : 'pages.php?Pg=SensusTidakTercatat', 
	formName : 'adminForm',// 'ruang_form',
	
	
	formSetDetailEntry : function(){
		//set entry sesuai jenis barang 
		//alert('tes');
		var kdbrg = document.getElementById('fmIDBARANG').value;
		var jnsbrg = kdbrg.substring(0,2);
		//if( jnsbrg != '' && jnsbrg.length == 2){
		document.getElementById('tidaktercatat_formkiba').style.display = 'none';
		document.getElementById('tidaktercatat_formkibb').style.display = 'none';
		document.getElementById('tidaktercatat_formkibc').style.display = 'none';
		document.getElementById('tidaktercatat_formkibe').style.display = 'none';
		switch(jnsbrg){
			case '01': {
				document.getElementById('tidaktercatat_formkiba').style.display = 'block';
				document.getElementById('tidaktercatat_formkibc').style.display = 'block';
				break;
			}
			case '02': {
				document.getElementById('tidaktercatat_formkibb').style.display = 'block';
				break;
			}
			case '03': case '04': case '06':{
				document.getElementById('tidaktercatat_formkiba').style.display = 'block';
				break;
			}
			case '05': {
				document.getElementById('tidaktercatat_formkibe').style.display = 'block';
				break;
			}
		}	
		
	},
	Baru: function(){	
		var err='';
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='ASISTEN/OPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='BIRO/ UPTD/B belum dipilih!';
		
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
	
	Edit: function(){	
		if(document.getElementById(this.elJmlCek)){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			//var stat = box.getAttribute("stat");
			//if(stat==0){
				
				var aForm = document.getElementById(this.formName);		
				aForm.action=this.url+'&tipe=formEdit2';
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
	
	pilihRuang : function(){		
		var me = this;		
		
		RuangPilih.fmSKPD = document.getElementById('c').value;
		RuangPilih.fmUNIT = document.getElementById('d').value;
		RuangPilih.fmSUBUNIT = document.getElementById('e').value;
			
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
		PegawaiPilih.el_idpegawai = 'ref_idpengadaan1';
		PegawaiPilih.el_nip= 'nip_pejabat_pengadaan1';
		PegawaiPilih.el_nama= 'nama_pejabat_pengadaan1';
		PegawaiPilih.el_jabat= 'jbt_pejabat_pengadaan1';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();
	},
	formNoDialog_show:function(){
		
	},
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
		/*}else{
			alert(errmsg);
		}*/
	},	
	Cetak_kerja2Excel:function(){	
	   //alert('tes')
	   	var me = this;
		//errmsg = this.CekCheckbox(); //if(errmsg ==''){ 
		//var box = this.GetCbxChecked();
		
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=genCetak_kerja2Excel';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		//aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		/*}else{
			alert(errmsg);
		}*/
	},	
	
	FormCetakShow : function(jnsform){	
		
		var me = this;
		var err='';		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=FormCetakShow&jnsform='+jnsform,
			  	success: function(data) {	
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;	
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},		
	
	formCetakCek: function(){
		if ( document.getElementById('limitdata').checked) {
			document.getElementById('limitstart').disabled   = true;
			document.getElementById('limitend').disabled  = true;
		}else{
			document.getElementById('limitstart').disabled  = false;
			document.getElementById('limitend').disabled  = false;
		}
	},
	cetakHal : function(){
		var haldefault = document.getElementById('haldefault').value;
		var tipe_cetak = 'cetak_hal&haldefault='+haldefault;
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe='+tipe_cetak;
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	},
	cetakAll : function(){
		var limitstart = document.getElementById('limitstart').value;
		var limitend = document.getElementById('limitend').value;
		if(document.getElementById('limitdata').checked==true){//cetak semua
			var tipe_cetak = 'cetak_all&checked=1';
		}else{//cetak limit
			var tipe_cetak = 'cetak_hal&limitstart='+limitstart+'&limitend='+limitend+'&checked=0';
		}
		//var limit = '&limitstart='+limitstart+'&limitend='+limitend+'&checked='+checked;
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe='+tipe_cetak;
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	},
	exportXlsHal : function(){		
		var haldefault = document.getElementById('haldefault').value;
		var tipe_cetak = 'exportXlsHal&haldefault='+haldefault;		
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe='+tipe_cetak;
		//aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	},
	exportXlsAll : function(){		
		var limitstart = document.getElementById('limitstart').value;
		var limitend = document.getElementById('limitend').value;
		if(document.getElementById('limitdata').checked==true){//cetak semua
			var tipe_cetak = 'exportXlsAll&checked=1';
		}else{//cetak limit
			var tipe_cetak = 'exportXlsHal&limitstart='+limitstart+'&limitend='+limitend+'&checked=0';
		}var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe='+tipe_cetak;
		//aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
});
