var backup = new DaftarObj2({
	prefix : 'backup',
	url : 'pages.php?Pg=backup', 
	formName : 'backupForm',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		this.sumHalRender();
	},
	refreshList : function( resetPageNo){
		if (resetPageNo && document.getElementById(this.prefix+'_hal') ) document.getElementById(this.prefix+'_hal').value=1;
		this.topBarRender();		
		this.filterRender();
		this.daftarRender();				
		this.sumHalRender();						
	},
	/*daftarRender:function(){
		var me =this; //render daftar 
		addCoverPage2(
			'daftar_cover',	1, 	true, true,	{renderTo: this.prefix+'_cont_daftar',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		    url: me.url+'&tipe=daftar',
		 	type:'POST', 
			data:$('#'+me.formName).serialize(), 
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
				me.sumHalRender();
		  	}
		});
	},*/
	
	daftarRender:function(){
		var me =this; //render daftar 
		addCoverPage2(
			'daftar_cover',	1, 	true, true,	{renderTo: this.prefix+'_cont_daftar',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		
		$.ajax({
		    url: this.url+'&tipe=isidata',
		 	type:'POST', 
			data:$('#'+this.formName).serialize(), 
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				//delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					//alert('Data berhasil disimpan');
					//me.Close();
					//me.AfterSimpan();
					//me.refreshList(true);	
                    $.ajax({
		   			 url: me.url+'&tipe=daftar',
		 			 type:'POST', 
					 data:$('#'+me.formName).serialize(), 
		  		     success: function(data) {		
					 var resp = eval('(' + data + ')');
					 document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
					 me.sumHalRender();
		  				}
					});
				}
				else{
					alert(resp.err);
				}
		  	}
					
		});
		
	},
	
	AfterHapus : function(){
		alert('Sukses Hapus Data');
		this.refreshList(true);
	}, 
	
	Hapus : function(){
		var me =this;
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			if(confirm('Hapus '+jmlcek+' Data ?')){
				//document.body.style.overflow='hidden'; 
				var cover = this.prefix+'_hapuscover';
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=hapus',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');		
						delElem(cover);		
						if(resp.err==''){							
							me.AfterHapus();	
						}else{
							alert(resp.err);
						}							
						
				  	}
				});
				
			}	
		}
	},
	
	cektanggal: function(){
		url: this.url+'&tipe=cekdatabackup'
	},
	
	backupdata: function(){
		var me = this;
		var cover = this.prefix+'_bckcover';
		addCoverPage2(cover,1,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=backupdata',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');		
				delElem(cover);		
				if(resp.err==''){							
					//me.AfterHapus();	
					alert ('Sukses backup');
				}else{
					alert(resp.err);
				}							
				
		  	}
		});
	},
		
});
