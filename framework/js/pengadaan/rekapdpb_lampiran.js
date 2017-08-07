var Rekapdpb_lampiran = new DaftarObj2({
	prefix : 'Rekapdpb_lampiran',
	url : 'pages.php?Pg=rekapdpb_lampiran', 
	formName : 'Rekapdpb_lampiran_form',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
	},
	
	topBarRender:function(){
		var me=this;
		var c=document.getElementById('c').value;
		var d=document.getElementById('d').value;
		var f=document.getElementById('f').value;
		var g=document.getElementById('g').value;
		var h=document.getElementById('h').value;
		var i=document.getElementById('i').value;
		var j=document.getElementById('j').value;
		var k=document.getElementById('k').value;
		var l=document.getElementById('l').value;
		var m=document.getElementById('m').value;
		var n=document.getElementById('n').value;
		var o=document.getElementById('o').value;
		var tahun=document.getElementById('tahun').value;
		var berdasarkan=document.getElementById('berdasarkan').value;
		//render subtitle
		if(berdasarkan==2){
		  	var url_site = this.url+'&tipe=subtitle&c='+c+'&d='+d+'&k='+k+'&l='+l+'&m='+m+'&n='+n+'&o='+o+'&tahun='+tahun+'&berdasarkan='+berdasarkan;		  	
		}else{
			var url_site = this.url+'&tipe=subtitle&c='+c+'&d='+d+'&f='+f+'&g='+g+'&h='+h+'&i='+i+'&j='+j+'&tahun='+tahun+'&berdasarkan='+berdasarkan;
		}
		
		$.ajax({
		 // url: this.url+'&tipe=subtitle&klinik='+idk,
		   	url: url_site,		  
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_title').innerHTML = resp.content;
			me.topBarRenderAfter()
		  }
		});
	},
	
	filterRenderAfter:function(){
		
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