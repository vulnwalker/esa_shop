var PerbandinganSkpd = new SkpdCls({
	prefix : 'PerbandinganSkpd', formName:'PerbandinganForm'
});


var Perbandingan = new DaftarObj2({
	prefix : 'Perbandingan',
	url : 'pages.php?Pg=perbandingan', 
	formName : 'PerbandinganForm',
	
	daftarRender : function(){
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
			//contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",	
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
				if(me.withPilih) me.cbTampil();				
				//me.sumHalRender();
			
		  	},
			error: ajaxError
		});
	},
	
});
