var Pembukuan2_2Skpd = new SkpdCls({
	prefix : 'Pembukuan2_2Skpd', formName:'adminForm'
});


var Pembukuan2_2 = new DaftarObj2({
	prefix : 'Pembukuan2_2',
	url : 'pages.php?Pg=Rekap2_old', 
	formName : 'adminForm',// 'ruang_form',
	
	loading : function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		//this.daftarRender();
		//this.sumHalRender();
	}
	
	
});
