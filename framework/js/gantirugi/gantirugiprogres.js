var gantirugiprogresSkpd = new SkpdCls({
	prefix : 'gantirugiprogresSkpd', 
	formName: 'gantirugiprogresForm',
});

var gantirugiprogres = new DaftarObj2({
	prefix : 'gantirugiprogres',
	url : 'pages.php?Pg=gantirugiprogres', 
	formName: 'gantirugiprogresForm',// 'adminForm'
	daftarPilih:new Array(),
	withPilih:true,
	elaktiv:'', //id elemen filter yang aktivformName : 'PerencanaanBarang_form',// 'adminForm',// 'ruang_form',
			
	
	
	penghapusan:function(){
		var me =this;
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else if(jmlcek >1){
			alert('Pilih Satu Data!');
		}else{
			if(confirm('Hapus '+jmlcek+' Data ?')){
				//document.body.style.overflow='hidden'; 
				var cover = this.prefix+'_hapuscover';
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=penghapusan',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');		
						delElem(cover);		
						if(resp.err==''){
							alert('Penghapusan Sukses!')							
							me.AfterSimpan();	
						}else{
							alert(resp.err);
						}							
						
				  	}
				});
				
			}	
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
		
	AfterFormEdit:function(){
			this.AfterFormBaru()
	},
		
	
	
});
