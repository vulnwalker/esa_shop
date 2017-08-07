
var refrefKotaKecPilih = new DaftarObj2({
	prefix : 'refrefKotaKecPilih',
	url : 'pages.php?Pg=refrefKotaKecPilih', 
	formName : 'refrefKotaKecPilih_Form',
	kdkotapilih:'',
	kdkecpilih:'',
	el_kd_kota: 'kd_kota',
	el_kd_kec: 'kd_kec',
	el_nm_wilayah: 'nm_wilayah',
	el_koordinat_gps: 'koordinat_gps',
	el_koord_bidang: 'koord_bidang',
	el_zoom: 'zoom',
	
	
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,30,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow',
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
			
			
			var cover = 'refKotaKec_getdata';
			addCoverPage2(cover,31,true,false);				
			$.ajax({
				url: 'pages.php?Pg=refKotaKec&tipe=getdata',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
//						if(document.getElementById(me.el_idpegawai)) document.getElementById(me.el_idpegawai).value= me.idpilih;
						if(document.getElementById(me.el_kd_kota)) document.getElementById(me.el_kd_kota).value= resp.kd_kota;						
						if(document.getElementById(me.el_kd_kec)) document.getElementById(me.el_kd_kec).value= resp.content.kd_kec;						if(document.getElementById(me.el_nm_wilayah)) document.getElementById(me.el_nm_wilayah).value= resp.content.nm_wilayah;
						
						//utk kip
/*
						if(document.getElementById('fmEntryNIPNAMA')){
							document.getElementById('fmEntryNIPNAMA').value = resp.content.nama;
							document.getElementById('fmPILNIPNAMA').value = 2;	
							Penatausaha.refreshList(true);						 
						}
*/						
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
	}
	
});


var refKotaKec = new DaftarObj2({
	prefix : 'refKotaKec',
	url : 'pages.php?Pg=refKotaKec', 
	formName : 'adminForm',// 'ruang_form',
	
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
	
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,30,true,false);	
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
	}	
	
	
});

