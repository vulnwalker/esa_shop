function BandingObj(params_){
	this.params=params_;
	this.prefix = 'banding';// 'pbb_penetapan_daftar';
	this.url = 'viewer.php?Pg=banding';
	this.formName = 'banding_form';
	
	this.gotoHalaman= function(nothing, Hal){	
		document.getElementById(this.prefix+'_hal').value = Hal;
		this.loading();
	}
	this.loading = function(){
		//alert('loading');
		//this.topBarRender();
		this.filterRender();
		this.daftarRender();		
	}
	this.filterRenderAfter = function(){
		
	}
	this.filterRender = function(){
		var me=this;
		//render filter
		$.ajax({
		  url: this.url+'&tipe=filter',
		  type:'POST', 
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_opsi').innerHTML = resp.content;
			me.filterRenderAfter()
		  }
		});
	}
	this.sumHalRender = function(){
		var me =this; //render sumhal
		addCoverPage2(
			'daftar_cover',	1, 	true, 	true,{renderTo: this.prefix+'_cont_hal',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		  url: this.url+'&tipe=sumhal',
		  type:'POST', 
			data:$('#'+this.formName).serialize(), 
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_hal').innerHTML = resp.content.hal;
			if (document.getElementById(me.prefix+'_cont_sum')){
				document.getElementById(me.prefix+'_cont_sum').innerHTML = resp.content.sums[0];	
			}
			for (var i=1; i<resp.content.sums.length; i++){
				if (document.getElementById(me.prefix+'_cont_sum'+i))
					document.getElementById(me.prefix+'_cont_sum'+i).innerHTML = resp.content.sums[i];	
			
			}
			
		  }
		});	
	}
	this.daftarRender = function(){
		var me =this; //render daftar 
		//alert('tes');
		addCoverPage2(
			'daftar_cover',	1, 	true, true,	{renderTo: this.prefix+'_cont_daftar',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		  	url: this.url+'&tipe=daftar',
		 	type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(), 
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
				me.sumHalRender();
		  	}
		});
	}
	this.cetakHal= function(){		
		var aForm = document.getElementById(this.prefix+'_form');		
		aForm.action= this.url+'&tipe=cetak_hal';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
	this.exportXls= function(){
		var aForm = document.getElementById(this.prefix+'_form');		
		aForm.action=this.url+'&tipe=exportXls';
		aForm.target='';
		aForm.submit();	
		aForm.target='';
	}
}

var banding = new BandingObj({});