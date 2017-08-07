var rkpbSkpd = new SkpdCls({
	prefix : 'rkpbSkpd', formName:'rkpbForm'
});

var rkpb = new DaftarObj2({
	prefix : 'rkpb',	
	url : 'pages.php?Pg=rkpb&ajx=1',
	formName : 'rkpbForm',
	//formName : 'adminForm',
	
	Baru2: function(){	
		var err='';
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		if(document.getElementById(this.prefix+'SkpdfmSEKSI')) var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		
		if(document.getElementById(this.prefix+'SkpdfmSEKSI')){
			if(err=='' && (seksi=='' || seksi=='00' || seksi=='000' ) ) err='SUB UNIT belum dipilih!';
		}
		
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
				if(stat==1){
					alert('RKPB sudah DKPB!');
				}else{
					alert('RKPB sudah RKA!');
				}
			}
		}else{
			alert(errmsg);
		}
	},
	
	showRKPB: function(){
		var me = this;
		var aForm = document.getElementById(this.formName);	
		aForm.action=this.url+'&tipe=formDKPB';
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
						
		//window.open(this.url+'&tipe=formDKB');
		/*$.post(this.url+'&tipe=formDKB', $("#"+this.formName).serialize(), function(result) {
			window.open(result,'_blank');
		});*/
		
	},
	
	setDKPB: function(){
		//this.showRKB();
		var me = this;	
		var cover = this.prefix+'_formcover';
		if(document.getElementById(this.elJmlCek)){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		errmsg = this.CekCheckbox();
		if(errmsg ==''){
			var box = this.GetCbxChecked();
			//alert(box.stat);
			//alert(box.getAttribute("stat"));
			var stat = box.getAttribute("stat");
			if(stat==2){
				this.showRKPB();
			}
			/*(else if(stat==1){
				alert('DKPB sudah ada!');
			}*/
			else{
				alert('Data yang dipilih harus RKA!');
			}
			/*addCoverPage2(cover,1,true,false);
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				//cek sudah rkb -------------------
			  	url: this.url+'&tipe=getsat',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					delElem(cover);
					if(resp.content.stat=='1'){
						alert('DKB sudah ada!');						
					}else{
						me.showRKB();
					}					
			  	}
			});	*/
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
	
	SimpanDKPB: function(){
		var err ='';
		if(err == ''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=simpanDKPB',
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
	
	cekJmlBrgBI: function(){
		//alert('tess');
		var err = '';
		var tahun = document.getElementById('fmTAHUN').value;
		var kdbarang = document.getElementsByName('fmIDBARANG').value;
		if (err=='' && tahun =='') err= "Tahun Anggaran belum diisi!";
		if (err=='' && kdbarang =='') err= "Kode Barang belum diisi!";
		
		if(err==''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=getJmlBrgBI',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						document.getElementById('jmlbi').value = resp.content.jmlbi;
						document.getElementById('standar').value = resp.content.jmlstandar;
						//alert('Sukses Simpan Data');
						//window.close();		
					}else{
						//alert(resp.err);
					}
					
			  	}
			});
		}else{
			alert(err);
		}
	},
	
	caribarang1:function(){		
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();						
		var idref = document.getElementById(this.prefix+'_idplh').value;
		var skpd = document.getElementById('c').value; 
		var unit = document.getElementById('d').value;
		var subunit = document.getElementById('e').value;
		var seksi = document.getElementById('e1').value;
		
		var cover = this.prefix+'_formcovercari';
		addCoverPage2(cover,999,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=formCari&sw='+sw+'&sh='+sh,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if (resp.err ==''){		
					document.getElementById(cover).innerHTML = resp.content;
					document.getElementById('div_detailcaribi').innerHTML = 
					//"<input type='hidden' id='formcaribi' name='formcaribi' value='1'>"+
					"<input type='hidden' id='fmSKPD' name='fmSKPD' value='"+skpd+"'>"+
					"<input type='hidden' id='fmUNIT' name='fmUNIT' value='"+unit+"'>"+
					"<input type='hidden' id='fmSUBUNIT' name='fmSUBUNIT' value='"+subunit+"'>"+
					"<input type='hidden' id='fmSEKSI' name='fmSEKSI' value='"+seksi+"'>"+
					
					"<input type='hidden' id='formcaribi' name='formcaribi' value='rkpb'>"+
					"<input type='hidden' id='boxchecked' name='boxchecked' value='2'>"+
					"<input type='hidden' id='GetSPg' name='GetSPg' value='03'>"+
					"<div id='penatausaha_cont_opt'></div>"+
					"<div id='penatausaha_cont_list'></div>"+
					"<div id='penatausaha_cont_hal'><input type='hidden' value='1' id=HalDefault></div>"+
					"";
					//generate data
					Penatausaha.getDaftarOpsi();
					Penatausaha.refreshList(true);
					document.body.style.overflow='hidden';
					//barcodeCariBarang.loading();
				}else{
					alert(resp.err);
					//delElem(cover);
					document.body.style.overflow='auto';
				}
		  	}
		});						
	} ,
	
	Closecari:function(){//alert(this.elCover);
		Penatausaha.resetPilih();		
		var cover = this.prefix+'_formcovercari';
		if(document.getElementById(cover)) delElem(cover);								
	},
	
	Pilih:function(){ //pilih cari
		var me = this;
		errmsg = '';		
		if((errmsg=='') && (Penatausaha.daftarPilih.length == 0 )){
			errmsg= 'Data belum dipilih!';
		}
		if((errmsg=='') && (Penatausaha.daftarPilih.length > 1 )){
			errmsg= 'Pilih 1 data!';
		}
		if(errmsg ==''){	
			//alert('simpan');
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: this.url +'&tipe=pilihcaribi',
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.Closecari();
						if( resp.content.plhkode_account !='.....') {
							document.getElementById('kode_account').value = resp.content.plhkode_account;
							document.getElementById('nama_account').value = resp.content.plhnama_account;
						}
						document.getElementById('tahun_account').value = resp.content.plhtahun_account;
						document.getElementById('fmIDBARANG').value = resp.content.plhIDBARANG;
						document.getElementById('fmNMBARANG').value = resp.content.plhNMBARANG;	
						
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
	},
	
	CariJurnal: function(){
		var me = this;	
		
		RefJurnal.el_kode_account = 'kode_account';
		RefJurnal.el_nama_account = 'nama_account';
		RefJurnal.el_tahun_account = 'tahun_account';
		RefJurnal.filterAkun='5.1.2';
		RefJurnal.windowSaveAfter= function(){};
		RefJurnal.windowShow();	
	},
	
	Get_JmlHarga: function(){
		var jml_barang= document.getElementById('jml_barang').value;
		var kuantitas= document.getElementById('kuantitas').value;
		var harga= document.getElementById('harga').value;
		var Jml_harga =  jml_barang * kuantitas * harga;
		document.getElementById('jml_harga').value = Jml_harga;
	}
});