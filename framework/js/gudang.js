
var GudangPilih = new DaftarObj2({
	prefix : 'GudangPilih',
	url : 'pages.php?Pg=GudangPilih', 
	formName : 'GudangPilih_Form',
	idpilih:'',
	nmGudang:'',
	fmSKPD:'',
	fmUNIT:'',
	fmSUBUNIT:'',
	el_idpegawai: 'ref_idpemegang',
	el_nip: 'nip1',
	el_nama: 'nama1',
	el_jabat: 'jbt1',
	
	
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow&fmSKPD='+me.fmSKPD+'&fmUNIT='+me.fmUNIT+'&fmSUBUNIT='+me.fmSUBUNIT+'&fmSEKSI='+me.fmSEKSI,
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
	windowSave: function(){//pilih
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
				url: 'pages.php?Pg=gudang&tipe=getdata&id='+this.idpilih,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						/*if(document.getElementById(me.el_idpegawai)) document.getElementById(me.el_idpegawai).value= me.idpilih;
						if(document.getElementById(me.el_nip)) document.getElementById(me.el_nip).value= resp.content.nip;						
						if(document.getElementById(me.el_nama)) document.getElementById(me.el_nama).value= resp.content.nama;
						if(document.getElementById(me.el_jabat)) document.getElementById(me.el_jabat).value= resp.content.jabatan;						
						*/
						me.windowClose();
						me.windowPilihAfter(resp);
					}else{
						alert(resp.err)	
					}
			  	}
			});
			
			
			
			
		}else{
			alert(errmsg);
		}
	},
	windowPilihAfter: function(resp){
		//alert('tes');
	}
	
});


var Gudang = new DaftarObj2({
	prefix : 'Gudang',
	url : 'pages.php?Pg=gudang', 
	formName : 'adminForm',// 'ruang_form',
	
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		
		
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
	}	
	
	
});

