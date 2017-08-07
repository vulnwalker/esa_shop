var penerimaanSkpd = new SkpdCls({
	prefix : 'penerimaanSkpd', formName:'adminForm'
});

var penerimaan = new DaftarObj2({
	prefix : 'penerimaan',	
	url : 'pages.php?Pg=penerimaan&ajx=1',
	formName : 'adminForm',
	
	pilihPenerima: function(){
		var me = this;			
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idpenerima';
		PegawaiPilih.el_nip= 'nip_penerima';
		PegawaiPilih.el_nama= 'penerima';
		PegawaiPilih.el_jabat= 'jbt_penerima';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	pilihPenyimpan: function(){
		var me = this;			
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idpenyimpan';
		PegawaiPilih.el_nip= 'nip_penyimpan';
		PegawaiPilih.el_nama= 'penyimpan';
		PegawaiPilih.el_jabat= 'jbt_penyimpan';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	pilihPemeriksa: function(){
		var me = this;			
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idpemeriksa';
		PegawaiPilih.el_nip= 'nip_pemeriksa';
		PegawaiPilih.el_nama= 'pemeriksa';
		PegawaiPilih.el_jabat= 'jbt_pemeriksa';
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
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		
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
	
	hitungSisa: function(){
		var jmldpb = document.getElementById('jml_dpb').value;
		var jml = document.getElementById('jml_barang').value;
		var jmlada = document.getElementById('jml_ada').value;
		document.getElementById('jml_sisa').value = parseInt(jmldpb) - ( parseInt(jmlada) + parseInt(jml) );
		
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
	
	pilihGudang: function(){
		var me = this;		
		/*if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		var pilihsemua = document.getElementById('cbxsemua').checked;				
		if(jmlcek ==0 && pilihsemua==false){
			alert('Data Belum Dipilih!');
		}else{*/
			/*var lanjut= true;
			if(pilihsemua){
				lanjut=  confirm('Pilih Pengurus Barang/Pembantu untuk semua data ?') ;
			}else if (jmlcek>1 ){
				lanjut=  confirm('Pilih Pengurus Barang/Pembantu untuk '+jmlcek+' data ?') ;
			}
			if(lanjut){			*/
				GudangPilih.fmSKPD = document.getElementById('c').value;
				GudangPilih.fmUNIT = document.getElementById('d').value;
				GudangPilih.fmSUBUNIT = document.getElementById('e').value;
				GudangPilih.fmSEKSI = document.getElementById('e1').value;
				//alert(Pegawa)
				GudangPilih.windowPilihAfter= function(resp){					
					//me.simpanPemegang(this.idpilih);
					//alert(resp.content.id_gudang+' '+resp.content.nm_gudang);					
					document.getElementById('id_gudang').value= resp.content.id_gudang;
					document.getElementById('nm_gudang').value= resp.content.nm_gudang;
					
				}
				GudangPilih.windowShow();
			//}
		//}
	},
});