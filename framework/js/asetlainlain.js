var AsetLainLainSkpd = new SkpdCls({
	prefix : 'AsetLainLainSkpd', formName:'adminForm'
});


var AsetLainLain = new DaftarObj2({
	prefix : 'AsetLainLain',
	url : 'pages.php?Pg=AsetLainLain', 
	formName : 'adminForm',// 'ruang_form',
	
	BaruBI : function(){
		var me = this; var errmsg ='';
		errmsg = this.CekCheckboxBi();
		if(errmsg ==''){ 
			var box = this.GetCbxCheckedBi();
			//if(confirm("Reclass Aset Lain-lain?")){
				
			
				var cover = this.prefix+'_formcover';
				addCoverPage2(cover,1,true,false);	
				document.body.style.overflow='hidden';
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=formBaruBI',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');	
						if (resp.err ==''){		
							document.getElementById(cover).innerHTML = resp.content;
							//me.AfterFormBaru(resp);	
						}else{
							alert(resp.err);
							delElem(cover);
							document.body.style.overflow='auto';
						}
				  	}
				});
			//}
		}else{
			alert(errmsg);
		}
		
	},
	
	AfterSimpan : function(){
		//
		if(document.getElementById('penatausaha_cont_list') ) {
			Penatausaha.refreshList(false);
		}else{
			this.refreshList(false);
		}
			
	},
	
	BaruBI_ : function(){
		var me = this;
		var cover = this.prefix+'_formcover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
		  	url: this.url+'&tipe=formBaru',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				if (resp.err ==''){
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru(resp);	
				}else{
					alert(resp.err);
					delElem(cover);
					document.body.style.overflow='auto';
				}			
				
		  	}
		});
	}	
	
});
