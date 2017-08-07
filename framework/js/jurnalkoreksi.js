var JurnalKoreksi = new DaftarObj2({
	prefix : 'JurnalKoreksi',
	url : 'pages.php?Pg=jurnalkoreksi', 
	formName : 'adminForm',// 'ruang_form',
	
	Close : function(){
		delElem(this.prefix+'_formcover');
		document.body.style.overflow='auto';
	},
	simpanBaru : function(){
		var me = this;
		//delElem(this.prefix+'_formKondisiCover');
		var cover = this.prefix+'_formKondisiSimpanCover';
		//document.body.style.overflow='hidden';
		addCoverPage2(cover,10,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
		  	url: this.url+'&tipe=simpanBaru',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				delElem(cover);
				if (resp.err==''){
					//document.getElementById(cover).innerHTML = resp.content;			
					alert('Sukses simpan data');
					document.getElementById('vharga').innerHTML = resp.content.vharga;
					document.getElementById('fmHARGABARANG').value = resp.content.harga;
					me.Close();
					
					
					//document.body.style.overflow='auto';
				}else{					
					alert(resp.err);
				}				
		  	}
		});
	},
	
	/*formBaru : function(){
		var cover = this.prefix+'_formBaruCover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,10,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
		  	url: this.url+'&tipe=formBaru',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				if (resp.err==''){
					document.getElementById(cover).innerHTML = resp.content;			
					
				}else{
					delElem(cover);
					document.body.style.overflow='auto';
					alert(resp.err);
				}
				
		  	}
		});

	},*/
	
	
});

