var Penggunaan_DetSkpd = new SkpdCls({
	prefix : 'Penggunaan_DetSkpd', formName:'Penggunaan_form'
});


var Penggunaan_Det = new DaftarObj2({
	prefix : 'Penggunaan_Det',
	url : 'pages.php?Pg=Penggunaan_Det', 
	formName : 'Penggunaan_form',// 'ruang_form',
	
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='ASISTEN/OPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='BIRO/ UPTD/B belum dipilih!';
		
		
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
	},
	
	Usulan : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='ASISTEN/OPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='BIRO/ UPTD/B belum dipilih!';
		
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formUsulan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},			
	
	cetakSK: function (){	
		var aForm = document.getElementById(this.formName);		
		aForm.action= this.url+'&tipe=cetakSK';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		
		
	},	
	
	cetakAll: function(){
		var aForm = document.getElementById("PenggunaanForm");		
		aForm.action=this.url+'&tipe=cetak_all';
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}		
	
	
});
