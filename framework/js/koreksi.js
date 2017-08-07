var KoreksiSkpd = new SkpdCls({
	prefix : 'KoreksiSkpd', formName:'adminForm'
});


var Koreksi = new DaftarObj2({
	prefix : 'Koreksi',
	url : 'pages.php?Pg=Koreksi', 
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
	},
	
	GetHrg_Asal : function (){
		var me = this;
		var formName = document.getElementById('Koreksi_form');
		var idbukuinduk = this.GetCbxCheckedBi().value;
		var tgl=document.getElementById('tgl').value;
		var tgl_perolehan=document.getElementById('tgl_perolehan').value;
		//var idbi=document.getElementById('idbi').value;
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=GetHrg_Asal&tgl='+tgl+'&tgl_perolehan='+tgl_perolehan+'&idbukuinduk='+idbukuinduk,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					if (resp.err ==''){
						document.getElementById('hrg').value = resp.content.hrg;
						document.getElementById('hrg2').innerHTML = resp.content.hrg2;
					}else{
						alert(resp.err);						
					}	
					
				}
			});	
		//var hrg = document.getElementById('hrg2').value;
		//document.getElementById('hrg_baru').value=hrg;
	},
	
	TglEntry_createtgl : function(elName){	
	tgl = document.getElementById(elName+'_tgl').value; //tgl = '.$fmName.'.'.$elName.'_tgl.value;
	if (tgl.length==1){
		tgl ='0'+tgl;
	}
	document.getElementById(elName).value= 
		document.getElementById(elName+'_thn').value+"-"+
		document.getElementById(elName+'_bln').value+"-"+
		tgl;
		Koreksi.GetHrg_Asal();
	}
	
});
