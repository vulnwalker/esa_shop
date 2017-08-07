var Rekonlampiran2 = new DaftarObj2({
	prefix : 'Rekonlampiran2',
	url : 'pages.php?Pg=rekonlampiran2', 
	formName : 'Rekonlampiran2Form',	
		
	cetaklampiran1: function (){	
		var aForm = document.getElementById('Rekonlampiran2Form');		
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