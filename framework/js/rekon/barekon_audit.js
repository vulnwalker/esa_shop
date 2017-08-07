var BaRekon_Audit = new DaftarObj2({
	prefix : 'BaRekon_Audit',
	url : 'pages.php?Pg=barekon_audit', 
	formName : 'BaRekon_AuditForm',// 'ruang_form',
	
	cetakba: function (){	
		//var aForm = document.getElementById('PerjalananDinas_form');		
		var aForm = document.getElementById('BaRekon_AuditForm');		
		var err='';
		if(err==''){
			aForm.action= this.url+'&tipe=cetakba';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
			aForm.target='_blank';
			aForm.submit();	
			aForm.target='';
		}else{
			alert(err);
		}		
	},

});
