
var PegawaiPilih = new DaftarObj2({
	prefix : 'PegawaiPilih',
	url : 'pages.php?Pg=PegawaiPilih', 
	formName : 'PegawaiPilih_Form',
	idpilih:'',
	fmSKPD:'',
	fmUNIT:'',
	fmSUBUNIT:'',
	fmSEKSI:'',
	
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
		delElem(this.prefix+'_cover');
	},
	windowSave: function(){
		//alert('save');
		var errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			//alert(box.value);
			this.idpilih = box.value;
			this.windowClose();
			this.windowSaveAfter();
		}else{
			alert(errmsg);
		}
	},
	windowSaveAfter: function(){
		alert('tes');
	}
	
});


var Pegawai = new DaftarObj2({
	prefix : 'Pegawai',
	url : 'pages.php?Pg=pegawai', 
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

