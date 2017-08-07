var RKADetail = new DaftarObj2({
	prefix : 'RKADetail',
	url : 'pages.php?Pg=rkadetail', 
	formName : 'RKA_form',
	
	loading:function(){
		//alert('loading');
		//this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
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
	
	Baru: function(){
		var me = this;
		if(document.getElementById('rkb_jmlcek')){
			var jmlcek = document.getElementById('rkb_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Pilih '+jmlcek+' Data ?')){
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: this.url+'&tipe=formBaru',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						me.autocomplete_initial();
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
		}
	},
	
	CekCheckbox:function(){//alert(this.elJmlCek);
		var errmsg = '';		

		if( document.getElementById('rkb'+'_jmlcek')){
			if((errmsg=='')  && (document.getElementById('rkb'+'_jmlcek').value >1 )){	errmsg= 'Pilih Hanya Satu Data!'; }
		}
		if((errmsg=='') && ( (document.getElementById('rkb'+'_jmlcek').value == 0)||(document.getElementById('rkb'+'_jmlcek').value == '')  )){
			errmsg= 'Data belum dipilih!';
		}
		return errmsg;
	},
	
	GetCbxChecked:function(){
		var jmldata= document.getElementById( 'rkb'+'_jmldatapage' ).value;
		for(var i=0; i < jmldata; i++){
			var box = document.getElementById( 'rkb'+'_cb' + i);
			if( box.checked){ 
				break;
			}
		}
		return box;			
	},	
	
	Edit: function(){
		var me = this;
		errmsg = this.CekCheckbox();
		/*var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		
		if(errmsg=='' && (skpd=='' || skpd=='00') ) errmsg='BIDANG belum dipilih!';
		if(errmsg=='' && (unit=='' || unit=='00') ) errmsg='ASISTEN/OPD belum dipilih!';
		if(errmsg=='' && (subunit=='' || subunit=='00') ) errmsg='BIRO/ UPTD/B belum dipilih!';*/
		
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
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
	
	Hapus : function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);
			if(confirm('Hapus Data ini?')){			
				var cover = this.prefix+'_cover';
				addCoverPage2(cover,1,true,false);	
				document.body.style.overflow='hidden';
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=Hapus',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');	
						if (resp.err ==''){		
							document.getElementById(cover).innerHTML = resp.content;
							//me.AfterFormEdit(resp);
							alert('Data berhasil dihapus');
							delElem(cover);
							me.refreshList(true);
						}else{
							alert(resp.err);
							delElem(cover);
							me.refreshList(true);
							//document.body.style.overflow='auto';
						}
				  	}
				});
			}
		}else{
			alert(errmsg);
		}
		
	},		
	
	CariBarang:function(){		
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();						
		var idref = document.getElementById(this.prefix+'_idplh').value;
		//var tahun = document.getElementById('tahun').value;
		//var sesiCari = document.getElementById('sesi').value;
		var c = document.getElementById('c').value;
		var d = document.getElementById('d').value;
		var e = document.getElementById('e').value;
		//var id_mutasi = document.getElementById('id_mutasi').value;		
		var cover = this.prefix+'_formcovercari';
		addCoverPage2(cover,999,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=formCariBarang&sw='+sw+'&sh='+sh+'&id_mutasi='+idref,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if (resp.err ==''){		
					document.getElementById(cover).innerHTML = resp.content;
					//var c = ''; var d=''; var e='';
					document.getElementById('div_detailcaribarang').innerHTML = 
					'<input type=\"hidden\" id=\"formcaribi\" name=\"formcaribi\" value=\"10\">'+
					'<input type=\"hidden\" id=\"multiSelectNo\" name=\"multiSelectNo\" value=\"1\">'+
					'<input type=\"hidden\" id=\"fmSKPD\" name=\"fmSKPD\" value=\"'+c+'\">'+
					'<input type=\"hidden\" id=\"fmUNIT\" name=\"fmUNIT\" value=\"'+d+'\">'+
					'<input type=\"hidden\" id=\"fmSUBUNIT\" name=\"fmSUBUNIT\" value=\"'+e+'\">'+
					'<input type=\"hidden\" id=\"idref\" name=\"idref\" value=\"'+idref+'\">'+
					//'<input type=\"hidden\" id=\"tahun_anggaran\" name=\"tahun_anggaran\" value=\"'+tahun+'\">'+
					//'<input type=\"hidden\" id=\"sesicari\" name=\"sesicari\" value=\"'+sesiCari+'\">'+					 
					'<input type=\"hidden\" id=\"boxchecked\" name=\"boxchecked\" value=\"2\">'+
					//'<input type=\"hidden\" id=\"GetSPg\" name=\"GetSPg\" value=\"03\">'+
					//'<input type=\"hidden\" id=\"SPg\" name=\"SPg\" value=\"03\">'+					
					'<div id=\"penatausaha_cont_opt\"></div>'+
					'<div id=\"penatausaha_cont_list\"></div>'+
					'<div id=\"penatausaha_cont_hal\"><input type=\"hidden\" value=\"1\" id=HalDefault></div>'+
					'';
					//generate data
					Penatausaha.getDaftarOpsi();
					Penatausaha.resetPilih();
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
	
	CloseCariBarang:function(){//alert(this.elCover);
		Penatausaha.resetPilih();		
		var cover = this.prefix+'_formcovercari';
		if(document.getElementById(cover)) delElem(cover);								
	},		
		
	PilihBarang:function(){ //pilih cari
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
				url: this.url +'&tipe=simpanPilihBarang',
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.CloseCariBarang();
						document.getElementById('idbi').value = resp.content.idbi;
						document.getElementById('idbi_awal').value = resp.content.idbi_awal;						
						document.getElementById('kode_barang').value = resp.content.kode_barang;
						document.getElementById('nama_barang').value = resp.content.nama_barang;						
						document.getElementById('thn_perolehan').value = resp.content.thn_perolehan;
						document.getElementById('noreg').value = resp.content.noreg;					
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
	},
	
	autocomplete_initial:function (){
	 				var me=this;
						$(function() {							
							$( '#status_barang' ).autocomplete({
						      source: function( request, response ) {
							  //var waktu_klinik = document.getElementById('waktu_klinik').value;
						        $.ajax({
								  url: 'pages.php?Pg=kir&tipe=autocomplete_stbarang_getdata',
						          dataType: 'json',
						          data: {
						            //featureClass: 'P',
						            style: 'full',
						            maxRows: 12,
									name_startsWith: request.term	
							
						          },
								  success: function( data ) {						         
									  response( $.map( data, function( item ) {
										    return {
												id: item.id,
										        label: item.label,
												value: item.value
										    }
										}));
									}
						        });
						      },
						      minLength: 1,
						      select: function( event, ui ) {
						        console.log( ui.item ?
						          'Selected: id=' + ui.item.id+' label=' + ui.item.label  :
						          'Nothing selected, input was ' + this.value);
								 document.getElementById('id_status_barang').value=ui.item.id;
								 //me.YangMenerima();
						      },
						      open: function() {
						        $( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
						      },
						      close: function() {
						        $( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
								//me.tarif();
						      }
						    });
						});
	},				
	
	Baru2: function(){	
		
		var me = this;
		var err='';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcoverbaru2';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaru2',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					me.autocomplete_initial();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},	
	
	Cari: function(){
		var me = this;
		RefStatusBarang.el_id_status_brg_temp = 'id_status_barang';
		RefStatusBarang.el_nm_status_brg_temp = 'status_barang';		
		RefStatusBarang.windowSaveAfter= function(){};
		RefStatusBarang.windowShow();	
	},	
	
	CloseCari: function(){
		document.body.style.overflow='auto';
		delElem(this.prefix+'_formcoverbaru2');
	},		
	
	CekCheckbox2:function(){//alert(this.elJmlCek);
		var errmsg = '';		
		//alert(document.getElementById(this.prefix+'_jmlcek').value );
		//if( document.getElementById(this.elJmlCek)){
		if( document.getElementById('CBPB'+'_jmlcek')){
			if((errmsg=='')  && (document.getElementById('CBPB'+'_jmlcek').value >1 )){	errmsg= 'Pilih Hanya Satu Barang!'; }
		}
		if((errmsg=='') && ( (document.getElementById('CBPB'+'_jmlcek').value == 0)||(document.getElementById('CBPB'+'_jmlcek').value == '')  )){
			errmsg= 'Barang belum dipilih!';
		}
		return errmsg;
	},
	
	CariRuang: function(){
		var me = this;	
		
		RuangPilih.fmSKPD = document.getElementById('c').value;
		RuangPilih.fmUNIT = document.getElementById('d').value;
		RuangPilih.fmSUBUNIT = document.getElementById('e').value;
		RuangPilih.el_idruang = 'id_ruang';
		RuangPilih.el_kdruang = 'kode_ruang';
		RuangPilih.el_nmruang= 'nama_ruang';
		RuangPilih.windowSaveAfter= function(){};
		RuangPilih.windowShow();	
	},	
	/*CariRuang: function(){
		var me = this;	
		
		CBPB.fmSKPD = document.getElementById('c').value;
		CBPB.fmUNIT = document.getElementById('d').value;
		CBPB.fmSUBUNIT = document.getElementById('e').value;
		CBPB.el_f = 'f';
		CBPB.el_g= 'g';
		CBPB.el_merk= 'merk';
		CBPB.el_type= 'type';
		CBPB.el_spesifikasi= 'spesifikasi';
		CBPB.el_kode_barang= 'kode_barang';
		CBPB.el_nama_barang= 'nama_barang';
		CBPB.el_mts= 'mts';
		CBPB.el_jumlah_kebutuhan= 'jumlah_kebutuhan';
		CBPB.el_harga_berdasarkan_satuan= 'harga_berdasarkan_satuan';
		CBPB.el_satuan= 'satuan';
		//CBPB.el_nama_satuan= 'nama_satuan';
		CBPB.el_pengadaan_sebelumnya= 'pengadaan_sebelumnya';
		CBPB.el_sisa_kebutuhan= 'sisa_kebutuhan';
		CBPB.el_ref_id_dkbp= 'ref_id_dkbp';																			
		CBPB.windowSaveAfter= function(){};
		CBPB.windowShow();	
	},*/	
	
	CariPejabatPengadaan: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.el_idpegawai = 'ref_idpengadaan';
		PegawaiPilih.el_nip= 'nip_pejabat_pengadaan';
		PegawaiPilih.el_nama= 'nama_pejabat_pengadaan';
		PegawaiPilih.el_jabat= 'jabatan_pejabat_pengadaan';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},	
	
	CariPejabatKomitmen: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.el_idpegawai = 'ref_idkomitmen';
		PegawaiPilih.el_nip= 'nip_pejabat_komitmen';
		PegawaiPilih.el_nama= 'nama_pejabat_komitmen';
		PegawaiPilih.el_jabat= 'jabatan_pejabat_komitmen';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	
	toRp: function(angka){
	    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
	    var rev2    = '';
	    for(var i = 0; i < rev.length; i++){
	        rev2  += rev[i];
	        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
	            rev2 += '.';
	        }
	    }
	    return rev2.split('').reverse().join('');
	},
	
	HitungHarga: function(){
		var jumlah_kebutuhan = document.getElementById('jumlah_kebutuhan').value;
		var jumlah_pengadaan_sebelumnya = document.getElementById('pengadaan_sebelumnya').value;
		var jumlah_pengadaan_barang = document.getElementById('jumlah_pengadaan_barang').value;				
		var harga_berdasarkan_satuan = document.getElementById('harga_berdasarkan_satuan').value;
		var hbs = harga_berdasarkan_satuan.split(",");
		var harga1 = hbs[0].replace(/[^a-zA-Z0-9]/g,"");
		var harga2 = hbs[1];
		var jml_harga = harga1+"."+harga2; 
		hasil=parseFloat(jumlah_pengadaan_barang) *  parseFloat(jml_harga);
		document.getElementById('jumlah_harga').value = formatNumber(hasil,2,',','.'); 
		
	},
	
	CekJP: function(){
		var jumlah_kebutuhan = document.getElementById('jumlah_kebutuhan').value;
		var jumlah_pengadaan_sebelumnya = document.getElementById('pengadaan_sebelumnya').value;
		var jumlah_pengadaan_barang = document.getElementById('jumlah_pengadaan_barang').value;
		var sisa_kebutuhan = jumlah_kebutuhan-jumlah_pengadaan_sebelumnya-jumlah_pengadaan_barang;//document.getElementById('sisa_kebutuhan').value;
		
		if(parseInt(jumlah_pengadaan_barang)>parseInt(jumlah_kebutuhan)){
			alert("Jumlah pengadaan tidak boleh lebih dari jumlah perencanaan");
			document.getElementById('jumlah_pengadaan_barang').value="";
		}else if(parseInt(jumlah_pengadaan_barang)>parseInt(jumlah_kebutuhan-jumlah_pengadaan_sebelumnya)){
			alert("Jumlah pengadaan tidak boleh lebih dari Sisa perencanaan");
			document.getElementById('jumlah_pengadaan_barang').value="";
			document.getElementById('sisa_kebutuhan').value=jumlah_kebutuhan-jumlah_pengadaan_sebelumnya;
		}else if(parseInt(jumlah_pengadaan_barang)<0){
			alert("Jumlah pengadaan tidak boleh Min");
			document.getElementById('jumlah_pengadaan_barang').value="";			
		}else if(parseInt(jumlah_pengadaan_barang)==0){
			alert("Jumlah pengadaan tidak boleh 0!");
			document.getElementById('jumlah_pengadaan_barang').value="";			
		}else{
			document.getElementById('sisa_kebutuhan').value=sisa_kebutuhan;
		}	
	},					
	
	AfterSimpan : function(){
		if(document.getElementById('Kunjungan_cont_daftar')){
		this.refreshList(true);}
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
	}		
});
