var Rekonhitung = new DaftarObj2({
	prefix : 'Rekonhitung',
	url : 'pages.php?Pg=rekonhitung', 
	formName : 'RekonhitungForm',	
		
	cetakhitung: function (){	
		var aForm = document.getElementById('RekonhitungForm');		
		var err='';
		if(err==''){
			aForm.action= this.url+'&tipe=cetakhitung';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
			aForm.target='_self';
			aForm.submit();	
		}else{
			alert(err);
		}		
	},
});