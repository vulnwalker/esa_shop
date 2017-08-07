var Pembukuan5Skpd = new SkpdCls({
	prefix : 'Pembukuan5Skpd', formName:'adminForm'
});


var Pembukuan5 = new DaftarObj2({
	prefix : 'Pembukuan5',
	url : 'pages.php?Pg=Rekap5', 
	formName : 'adminForm',// 'ruang_form',
	loading : function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		//this.sumHalRender();
	}	
	
});
