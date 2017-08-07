
var PindahTanganObj = function(){	
	this.formbaru = 
		new AjxFormObj2(
			this.nameobj,
			this.url_form+'&' ,
			'adminForm',				
			'','',
			"alert('Sukses Simpan Data');"+
			'Penatausaha.refreshList(false);',
			"alert('Sukses batal');"	
		);	
	this.Baru2 = function(){		
		
		this.formbaru.Baru();		
	}
	this.SimpanBaru = function(){
		this.formbaru.Simpan();
	}
}		
PindahTanganObj.prototype = 	new PageObj(
	'Pindahtangan',		
	'pages.php?Pg=10', 		
	'pages.php?Pg=10', 		
	'pages.php?Pg=10'	
);
Pindahtangan = new PindahTanganObj();
