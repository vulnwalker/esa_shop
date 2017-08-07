var Rekonlampiran1 = new DaftarObj2({
	prefix : 'Rekonlampiran1',
	url : 'pages.php?Pg=rekonlampiran1', 
	formName : 'Rekonlampiran1Form',	
		
	cetaklampiran1: function (){	
		var aForm = document.getElementById('Rekonlampiran1Form');		
		var err='';
		if(err==''){
			aForm.action= this.url+'&tipe=cetaklampiran';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
			aForm.target='_self';
			aForm.submit();	
		}else{
			alert(err);
		}		
	},
});