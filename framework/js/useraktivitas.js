
var UserAktivitasSkpd = new SkpdCls({
	prefix : 'UserAktivitasSkpd', formName:'adminForm'
});

var UserAktivitas = new DaftarObj2({
	prefix : 'UserAktivitas',
	url : 'pages.php?Pg=useraktivitas', 
	formName : 'adminForm',// 'ruang_form',
	
	detail: function(){
		//alert('detail');
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();			
			UserAktivitasDet.genDetail();			
			/*var cover = this.prefix+'_formcover';
			addCoverPage2(cover,999,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=detail',
			  	success: function(data) {							
					var resp = eval('(' + data + ')');						
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						//me.AfterFormEdit(resp);
					}else{
						alert(resp.err);
						delElem(cover);
						document.body.style.overflow='auto';	
					}
			  	}
			});*/
		}else{
			alert(errmsg);
		}
		
	},
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaru',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	}	
	
	
});

var UserAktivitasDet = new DaftarObj2({
	prefix : 'UserAktivitasDet',
	url : 'pages.php?Pg=UserAktivitasDet', 
	formName : 'UserAktivitasDet_form',
	idpilih:'',
	fmSKPD:'',
	fmUNIT:'',
	fmSUBUNIT:'',
	el_idpegawai: 'ref_idpemegang',
	el_nip: 'nip1',
	el_nama: 'nama1',
	el_jabat: 'jbt1',
	
	
	genDetail: function(){
		var me = this;
		var cover = this.prefix+'_coverdet';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,999,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#adminForm').serialize(),
			url: this.url+'&tipe=genDetail',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				//document.getElementById(cover).innerHTML = resp.content;							
				document.getElementById(cover).innerHTML = resp.content;	
				me.loading();						
		  	}
		});
	},	
	
	CloseDet : function(){//alert(this.elCover);
		var cover = this.prefix+'_coverdet';
		if(document.getElementById(cover)) delElem(cover);			
		document.body.style.overflow='auto';					
	}
	
	
});