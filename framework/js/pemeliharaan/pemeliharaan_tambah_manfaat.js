var Pemeliharaan_Tambah_ManfaatSkpd = new SkpdCls({
	prefix : 'Pemeliharaan_Tambah_ManfaatSkpd', 
	formName: 'Pemeliharaan_Tambah_ManfaatForm',
});
var Pemeliharaan_Tambah_Manfaat = new DaftarObj2({
	prefix : 'Pemeliharaan_Tambah_Manfaat',
	url : 'pages.php?Pg=Pemeliharaan_Tambah_Manfaat', 
	formName : 'Pemeliharaan_Tambah_ManfaatForm',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();		
		this.daftarRender();
		this.sumHalRender();
	},	
	
	cekcari:function(th){
	  	var isi = th.value;
		this.elaktiv=th.id;		
		//PegawaiDetail.refreshList(true)
		this.daftarRender();
		this.sumHalRender();
		
	},
	
	filterRenderAfter: function(){
   		//console.log(this.elaktiv)
		if(document.getElementById(this.elaktiv)){
			var el=document.getElementById(this.elaktiv);
			el.focus();
			el.setSelectionRange(1000,1000);	
		}
	},
	
	daftarRender:function(){
		var me =this; //render daftar 
		addCoverPage2(
			'daftar_cover',	1, 	true, true,	{renderTo: this.prefix+'_cont_daftar',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		  	url: this.url+'&tipe=daftar',
		 	type:'POST', 
			data:$('#'+this.formName).serialize(), 
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
				me.sumHalRender();
		  	}
		});
	},	
	
	
});