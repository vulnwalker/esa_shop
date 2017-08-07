



var GantiRugiObj = function(){	
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
GantiRugiObj.prototype = 	new PageObj(
	'Gantirugi',		
	'pages.php?Pg=12', 		
	'pages.php?Pg=12', 		
	'pages.php?Pg=12'	
);
Gantirugi = new GantiRugiObj();
