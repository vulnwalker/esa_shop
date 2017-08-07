var alokasiDpa = new DaftarObj2({
	prefix : 'alokasiDpa',
	url : 'pages.php?Pg=alokasiDpa', 
	formName : 'alokasiDpaForm',

	
	loading: function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
	
	},
	filterRenderAfter : function(){
    	this.daftarRender();
  	},
	programChanged : function(){
		var arrayP = $("#p").val().split('.');;
		var bk = arrayP[0];
		var ck = arrayP[1];
		var hiddenP = arrayP[2];
	
		$("#bk").val(bk);
		$("#ck").val(ck);
		$("#hiddenP").val(hiddenP);
		$("#q").val('');
		alokasiDpa.refreshList(true);	
	},
	detail: function(){
		//alert('detail');
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();			
			//UserAktivitasDet.genDetail();			
			
		}else{
		
			alert(errmsg);
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
			//	me.sumHalRender();
		  	}
		});
	},
	Laporan:function(){
			
			var url2 = this.url;
			
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
				  	url: this.url+'&tipe=Report',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');
						if(resp.err == ''){
							window.open(url2+'&tipe=Laporan','_blank');
							
						}else{
							alert(resp.err);
						}			
				
				  	}
				});
			
		
		
	}, 
		
});
