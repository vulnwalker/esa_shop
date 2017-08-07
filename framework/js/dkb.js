var dkbSkpd = new SkpdCls({
	prefix : 'dkbSkpd', formName:'adminForm'
});

var dkb = new DaftarObj2({
	prefix : 'dkb',	
	url : 'pages.php?Pg=dkb&ajx=1',
	formName : 'adminForm',
	fmSKPD:'',
	fmUNIT:'',
	el_iddkb:'',
	el_kd_barang:'',
	el_nm_barang:'',
	el_kd_account:'',
	el_nm_account:'',
	el_e:'',
	el_e1:'',
	

	Baru: function(){
		var me = this;
		errmsg = this.CekCheckbox2();
		
		if(errmsg ==''){ 
			var box = this.GetCbxChecked2();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcoverDKBMD';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formBaru',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						me.AfterFormBaru(resp);
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
	
	Edit: function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcoverDKBMD';
			addCoverPage2(cover,1,true,true);	
			document.body.style.overflow='hidden';
			//document.body.style.overflowY='scroll';
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
	
	CekCheckbox2:function(){//alert(this.elJmlCek);
		var errmsg = '';		

		if( document.getElementById('rkb'+'_jmlcek')){
			if((errmsg=='')  && (document.getElementById('rkb'+'_jmlcek').value >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }
		}
		if((errmsg=='') && ( (document.getElementById('rkb'+'_jmlcek').value == 0)||(document.getElementById('rkb'+'_jmlcek').value == '')  )){
			errmsg= 'Data belum dipilih!';
		}
		return errmsg;
	},
	
	GetCbxChecked2:function(){
		var jmldata= document.getElementById( 'rkb'+'_jmldatapage' ).value;
		for(var i=0; i < jmldata; i++){
			var box = document.getElementById( 'rkb'+'_cb' + i);
			if( box.checked){ 
				break;
			}
		}
		return box;			
	},		
	
	Kegiatan: function(){	
		
		var me = this;
		var err='';
		var program = document.getElementById('program_dkbmd').value; 
		var bk = document.getElementById('bk').value; 
		var ck = document.getElementById('ck').value; 				
		var dk = document.getElementById('dk').value; 			
				
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			//if (err =='' ){	
			$.ajax({
				type:'POST', 
				data:"program="+program+"&bk="+bk+"&ck="+ck+"&dk="+dk,
			  	url: this.url+'&tipe=kegiatan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					document.getElementById('div_kegiatan').innerHTML = resp.content;
				}
			});
	},
	
	HitungHarga: function(){
		var jml_dkbmd = document.getElementById('jml_dkbmd').value;
		var harga_sat_dkbmd = document.getElementById('harga_sat_dkbmd').value;
		//if (jml_dkbmd == '') jml_dkbmd=0;
		//if (harga_sat_dkbmd == '') harga_sat_dkbmd=0;
		if(jml_dkbmd == 0){
			alert('Jumlah tidak boleh 0 atau kosong !');
			document.getElementById('jml_dkbmd').value ='';
		}else if(jml_dkbmd < 0){
			alert('Jumlah tidak boleh minus !');
			document.getElementById('jml_dkbmd').value =''; 	
		}else if(harga_sat_dkbmd == 0){
			alert('Harga Satuan tidak boleh kosong !');
			document.getElementById('harga_sat_dkbmd').value =''; 	
		}else{
		document.getElementById('jml_harga_dkbmd').value = formatNumber(parseInt(jml_dkbmd) * parseInt(harga_sat_dkbmd),2,',','.'); 			
		}		
	},			
	
	/*Edit : function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			var aForm = document.getElementById(this.formName);	
			aForm.action=this.url+'&tipe=formEdit';
			aForm.target='_blank';
			aForm.submit();	
			aForm.target='';
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			/*var cover = this.prefix+'_formcover';
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
						//me.AfterFormEdit(resp);
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
		
	},*/
	
	Simpan: function(){
		var err ='';
		if(err == ''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=simpan',
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
	
	CloseDKBMD: function(){
		document.body.style.overflow='auto';
		delElem(this.prefix+'_formcoverDKBMD');
	},	
	
	SimpanDKBMD: function(){
		var err ='';
		var me = this;
		if(err == ''){
			var cover = this.prefix+'_formcoversimpanDKBMD';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+'dkb_form').serialize(),
			  	url: this.url+'&tipe=simpanDKBMD',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						delElem(cover);
						me.CloseDKBMD();
						alert('Sukses Simpan Data');
						if(document.getElementById('dkb_fmST')==1){
							dkb.refreshList(true);							
						}else{
							rkb.refreshList(true);
						} 
					}else{
						alert(resp.err);
						delElem(cover);
					}					
			  	}
			});
			
		}else{
			alert(err);
		}
		
	},	
	
	CariDKB: function(){
		var me = this;	
		dkb.fmSKPD = '02';//document.getElementById(this.prefix+'fmSKPD').value;
		dkb.fmUNIT = '01';//document.getElementById(this.prefix+'fmUNIT').value;
		dkb.el_iddkb = 'iddkb';
		dkb.el_kd_barang = 'kd_barang';
		dkb.el_nm_barang = 'nm_barang';
		dkb.el_kd_account = 'kd_account';
		dkb.el_nm_account = 'nm_account';
		dkb.windowSaveAfter= function(){};
		dkb.windowShow();	
	},
	
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow&fmSKPD='+me.fmSKPD+'&fmUNIT='+me.fmUNIT,
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
			
			
			var cover = 'Dkb_getdata';
			addCoverPage2(cover,1,true,false);				
			$.ajax({
				url: 'pages.php?Pg=dkb&tipe=getdata&id='+this.idpilih,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						if(document.getElementById(me.el_iddkb)) document.getElementById(me.el_iddkb).value= resp.content.iddkb;
						if(document.getElementById(me.el_kd_barang)) document.getElementById(me.el_kd_barang).value= resp.content.kd_barang;
						if(document.getElementById(me.el_nm_barang)) document.getElementById(me.el_nm_barang).value= resp.content.nm_barang;
						if(document.getElementById(me.el_kd_account)) document.getElementById(me.el_kd_account).value= resp.content.kd_account;
						if(document.getElementById(me.el_nm_account)) document.getElementById(me.el_nm_account).value= resp.content.nm_account;
						
						if(document.getElementById(me.el_e)) document.getElementById(me.el_e).value= resp.content.e;
						if(document.getElementById(me.el_e1)) document.getElementById(me.el_e1).value= resp.content.e1;
						me.windowClose();
						me.windowSaveAfter(resp);
					}else{
						alert(resp.err)	
					}
			  	}
			});
			
			
			
			
		}else{
			alert(errmsg);
		}
	},
	windowSaveAfter: function(resp){
		//alert('tes');
	},
	
		
	
		
});