
var HrgSatPilih = new DaftarObj2({
	prefix : 'HrgSatPilih',
	url : 'pages.php?Pg=HrgSatPilih', 
	formName : 'HrgSatPilihForm',// 'ruang_form',
	fmSKPD:'',
	fmUNIT:'',
	fmSUBUNIT:'',
	fmSEKSI:'',
	fmIDBARANG:'',
	fmNMBARANG:'',
	
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
	
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		/*var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		*/
		
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
	}	,
	
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow&fmIDBARANG='+me.fmIDBARANG+'&fmNMBARANG='+me.fmNMBARANG,
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
		var me= this;
		//alert('save');
		var errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			//alert(box.value);
			this.idpilih = box.value;
			
			
			var cover = 'pegawai_getdata';
			addCoverPage2(cover,1,true,false);				
			$.ajax({
				url: 'pages.php?Pg=pegawai&tipe=getdata&id='+this.idpilih,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						if(document.getElementById(me.el_idpegawai)) document.getElementById(me.el_idpegawai).value= me.idpilih;
						if(document.getElementById(me.el_nip)) document.getElementById(me.el_nip).value= resp.content.nip;						
						if(document.getElementById(me.el_nama)) document.getElementById(me.el_nama).value= resp.content.nama;
						if(document.getElementById(me.el_jabat)) document.getElementById(me.el_jabat).value= resp.content.jabatan;
						
						//utk kip
						if(document.getElementById('fmEntryNIPNAMA')){
							document.getElementById('fmEntryNIPNAMA').value = resp.content.nama;
							document.getElementById('fmPILNIPNAMA').value = 2;	
							Penatausaha.refreshList(true);						 
						}
						
						me.windowClose();
						me.windowSaveAfter();
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
		//alert('tes');
	},
	
	/*CariDKB: function(){
		var me = this;	
		dkb.fmSKPD = '02';//document.getElementById(this.prefix+'fmSKPD').value;
		dkb.fmUNIT = '01';//document.getElementById(this.prefix+'fmUNIT').value;
		dkb.el_idpemanfaat = 'idpemanfaat';
		dkb.el_kd_barang = 'kd_barang';
		dkb.el_nm_barang = 'nm_barang';
		dkb.el_kd_account = 'kd_account';
		dkb.el_nm_account = 'nm_account';
		dkb.el_e = 'e';
		dkb.el_e1 = 'e1';
		dkb.windowSaveAfter= function(){};
		dkb.windowShow();	
	},
	
	CariPemeliharaRencana: function(){
		var me = this;	
		Pemelihara_Rencana.fmSKPD = '02';//document.getElementById(this.prefix+'fmSKPD').value;
		Pemelihara_Rencana.fmUNIT = '01';//document.getElementById(this.prefix+'fmUNIT').value;
		Pemelihara_Rencana.el_iddkpb = 'iddkpb';
		Pemelihara_Rencana.el_kd_barang = 'kd_barang';
		Pemelihara_Rencana.el_nm_barang = 'nm_barang';
		Pemelihara_Rencana.el_kd_account = 'kd_account';
		Pemelihara_Rencana.el_nm_account = 'nm_account';
		Pemelihara_Rencana.el_e = 'e';
		Pemelihara_Rencana.el_e1 = 'e1';
		Pemelihara_Rencana.windowSaveAfter= function(){};
		Pemelihara_Rencana.windowShow();	
	},
	
	CariPemanfaatRencana: function(){
		var me = this;	
		Pemanfaat_Rencana.fmSKPD = '02';//document.getElementById(this.prefix+'fmSKPD').value;
		Pemanfaat_Rencana.fmUNIT = '01';//document.getElementById(this.prefix+'fmUNIT').value;
		Pemanfaat_Rencana.el_idpemanfaat = 'idpemanfaat';
		Pemanfaat_Rencana.el_kd_barang = 'kd_barang';
		Pemanfaat_Rencana.el_nm_barang = 'nm_barang';
		Pemanfaat_Rencana.el_kd_account = 'kd_account';
		Pemanfaat_Rencana.el_nm_account = 'nm_account';
		Pemanfaat_Rencana.el_e = 'e';
		Pemanfaat_Rencana.el_e1 = 'e1';
		Pemanfaat_Rencana.windowSaveAfter= function(){};
		Pemanfaat_Rencana.windowShow();	
	},
	
	CariPemindahtanganRencana: function(){
		var me = this;	
		Pemindahtangan_Rencana.fmSKPD = '02';//document.getElementById(this.prefix+'fmSKPD').value;
		Pemindahtangan_Rencana.fmUNIT = '01';//document.getElementById(this.prefix+'fmUNIT').value;
		Pemindahtangan_Rencana.el_idpilih = 'idpilih';
		Pemindahtangan_Rencana.el_kd_barang = 'kd_barang';
		Pemindahtangan_Rencana.el_nm_barang = 'nm_barang';
		Pemindahtangan_Rencana.el_kd_account = 'kd_account';
		Pemindahtangan_Rencana.el_nm_account = 'nm_account';
		Pemindahtangan_Rencana.el_e = 'e';
		Pemindahtangan_Rencana.el_e1 = 'e1';
		Pemindahtangan_Rencana.windowSaveAfter= function(){};
		Pemindahtangan_Rencana.windowShow();	
	},*/
	
	formatRupiah:function(objek, separator) {
	  a = objek.value;
	  b = a.replace(/[^\d]/g,"");
	  c = "";
	  panjang = b.length;
	  j = 0;
	  for (i = panjang; i > 0; i--) {
	    j = j + 1;
	    if (((j % 3) == 1) && (j != 1)) {
	      c = b.substr(i-1,1) + separator + c;
	    } else {
	      c = b.substr(i-1,1) + c;
	    }
	  }
	  objek.value = c;
   }
	
});

