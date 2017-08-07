var Pembukuan2_2_tesSkpd = new SkpdCls({
	prefix : 'Pembukuan2_2_tesSkpd', formName:'adminForm'
});


var Pembukuan2_2_tes = new DaftarObj2({
	prefix : 'Pembukuan2_2_tes',
	url : 'pages.php?Pg=Rekap2_tes', 
	formName : 'adminForm',
	
	loading : function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		//this.daftarRender();
		//this.sumHalRender();
	}
});
