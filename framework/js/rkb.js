var rkbSkpd = new SkpdCls({
	prefix : 'rkbSkpd', formName:'adminForm'
});

var rkb = new DaftarObj2({
	prefix : 'rkb',	
	url : 'pages.php?Pg=rkb&ajx=1',
	formName : 'adminForm',
	idpilih:'',
	fmSKPD:'',
	fmUNIT:'',
	fmSUBUNIT:'',
	fmSEKSI:'',
	el_idpegawai: 'ref_idpemegang',
	el_nip: 'nip1',
	el_nama: 'nama1',
	el_jabat: 'jbt1',
	
	Baru2: function(){	
		var err='';
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUBUNIT belum dipilih!';
		
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
					alert('RKB sudah DKB!');
				}else{
					alert('RKB sudah RKA!');
				}
				
			}
		}else{
			alert(errmsg);
		}
	},
	
	showRKB: function(){
		var me = this;
		var aForm = document.getElementById(this.formName);	
		aForm.action=this.url+'&tipe=formDKB';
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
						
		//window.open(this.url+'&tipe=formDKB');
		/*$.post(this.url+'&tipe=formDKB', $("#"+this.formName).serialize(), function(result) {
			window.open(result,'_blank');
		});*/
		
	},
	
	setDKB: function(){
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
			if(stat==0){
				this.showRKB();
			}else{
				alert('DKB sudah ada!');
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
	
	AfterSimpan:function(){
		this.refreshList(true);
	},
	
	Simpan2: function(){
		var err ='';
		var me= this;
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
						me.AfterSimpan();	
					}else{
						alert(resp.err);
					}					
			  	}
			});
			
		}else{
			alert(err);
		}
		
	},
	
	SimpanDKB: function(){
		var err ='';
		if(err == ''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=simpanDKB',
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
						document.getElementById('jml_max').value = resp.content.info_max;
						document.getElementById('jml_brg_sblm').value = resp.content.jml_brg_sblm;
						//document.getElementById('standar').value = resp.content.jmlstandar;
						//document.getElementById('divJmlBrgBi').innerHTML = 'Kondisi : '+resp.content.jmlKondBaik+' Baik, '+resp.content.jmlKondKB+' Kurang Baik, '+resp.content.jmlKondRB+' Rusak Berat. ';
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
	
	cekJmlBrgStandar: function(){
		//alert('tess');
		var err = '';
		//var tahun = document.getElementById('fmTAHUN').value;
		var kdbarang = document.getElementsByName('fmIDBARANG').value;
		var tahun = document.getElementsByName('fmTAHUN').value;
		var jmlbi = document.getElementsByName('jmlbi').value;
		//if (err=='' && tahun =='') err= "Tahun Anggaran belum diisi!";
		if (err=='' && kdbarang =='') err= "Kode Barang belum diisi!";
		
		if(err==''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=getJmlBrgStandar',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						document.getElementById('standar').value = resp.content.jmlstandar;
						document.getElementById('jml_max').value = resp.content.info_max;
						document.getElementById('jml_brg_sblm').value = resp.content.jml_brg_sblm;
						//document.getElementById('divJmlBrgBi').innerHTML = 'Kondisi : '+resp.content.jmlKondBaik+' Baik, '+resp.content.jmlKondKB+' Kurang Baik, '+resp.content.jmlKondRB+' Rusak Berat. ';
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
	
	Info: function(){
		var me = this;	
		var err = '';
		var kdbarang = parent.document.adminForm.fmIDBARANG.value;
		if (err=='' && kdbarang =='') err= "Kode Barang belum diisi!";

		if(err==''){
			HrgSatPilih.fmIDBARANG = parent.document.adminForm.fmIDBARANG.value;
			HrgSatPilih.fmNMBARANG = parent.document.adminForm.fmNMBARANG.value;
			HrgSatPilih.el_idpegawai = 'ref_idpengadaan';
			HrgSatPilih.el_nip= 'nip_pejabat_pengadaan';
			HrgSatPilih.el_nama= 'nama_pejabat_pengadaan';
			HrgSatPilih.el_jabat= 'jbt_pejabat_pengadaan';
			HrgSatPilih.windowSaveAfter= function(){};
			HrgSatPilih.windowShow();	
		}else{
			alert(err);
		}
	},
	
	Hapus:function(){
		var me =this;
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Hapus '+jmlcek+' Data ?')){
				//document.body.style.overflow='hidden'; 
				var cover = this.prefix+'_hapuscover';
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=hapus',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');		
						delElem(cover);		
						if(resp.err==''){							
							me.AfterHapus();	
						}else{
							alert(resp.err);
						}							
						
				  	}
				});
				
			}	
		}
	},
	
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
   },
   
   Hitung: function(){
		//alert('tess');
		var err = '';
		var jml_barang = document.getElementById('jml_barang').value;
		var harga = document.getElementById('harga').value;
		
		
		if(err==''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=Hitung&jml_barang='+jml_barang+'&harga='+harga,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						document.getElementById('cnt_jmlharga').value = resp.content;		
					}else{
						//alert(resp.err);
					}
					
			  	}
			});
		}else{
			alert(err);
		}
	},
	
	tes:function(){
		
		alert(parent.document.adminForm.fmIDBARANG.value);
	}
});