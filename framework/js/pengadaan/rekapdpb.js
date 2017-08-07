var rekapdpbSkpd = new SkpdCls({
	prefix : 'rekapdpbSkpd', 
	formName: 'rekapdpbForm',
});

var rekapdpb = new DaftarObj2({
	prefix : 'rekapdpb',
	url : 'pages.php?Pg=rekapdpb', 
	formName : 'rekapdpbForm',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
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
	
	Lampiran: function(){		
		var me = this;
		errmsg = this.CekCheckbox();	
		var berdasarkan = document.getElementById("fmBerdasarkan").value;
		var sem = document.getElementById("fmSemester").value;
		if(berdasarkan==2){
			kode1="k";
			kode2="l";
			kode3="m";
			kode4="n";
			kode5="o";	
		}else{
			kode1="f";
			kode2="g";
			kode3="h";
			kode4="i";
			kode5="j";		
		}
		//alert(berdasarkan);
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			var getCB = box.value;
			var cb = getCB.split(" ");
			var aForm = document.getElementById(this.formName);		
			aForm.action= 'pages.php?Pg=rekapdpb_lampiran&c='+cb[0]+'&d='+cb[1]+'&'+kode1+'='+cb[2]+'&'+kode2+'='+cb[3]+'&'+kode3+'='+cb[4]+'&'+kode4+'='+cb[5]+'&'+kode5+'='+cb[6]+'&tahun='+cb[7]+'&sem='+sem+'&berdasarkan='+berdasarkan;//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
			aForm.target='_blank';
			aForm.submit();	
			aForm.target='';
		}else{
			alert(errmsg);
		}	
	}	
});
