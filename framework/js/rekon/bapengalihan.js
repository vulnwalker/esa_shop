var BaPengalihan = new DaftarObj2({
	prefix : 'BaPengalihan',
	url : 'pages.php?Pg=bapengalihan', 
	formName : 'BaPengalihanForm',// 'ruang_form',
	
	cetakba: function (){	
		//var aForm = document.getElementById('PerjalananDinas_form');		
		var aForm = document.getElementById('BaRekonForm');		
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
