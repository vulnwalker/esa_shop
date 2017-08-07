var BaRekonSkpd = new SkpdCls({
	prefix : 'BaRekonSkpd', formName:'BaRekonForm'
});
var BaRekon = new DaftarObj2({
	prefix : 'BaRekon',
	url : 'pages.php?Pg=barekon', 
	formName : 'BaRekonForm',// 'ruang_form',
	
	cetakba: function (){	
		//var aForm = document.getElementById('BaRekonForm');		
		var aForm = document.getElementById('BaRekon_form');	
		var thn_anggaran = document.getElementById('thn_anggaran').value;	
		var err='';
		if(err=='' && thn_anggaran=='')err='Tahun Anggaran belum diisi !';
		if(err==''){
			aForm.action= this.url+'&tipe=cetakba';//'?Op='+op+'&Pg=2&idprs=cetak_hal';		
			aForm.target='_blank';
			aForm.submit();	
			aForm.target='';
		}else{
			alert(err);
		}		
	},
	
	Surat : function(){
		var err='';
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		//if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		//if(err=='' && (seksi=='' || seksi=='00' || seksi=='000' ) ) err='SUB UNIT belum dipilih!';
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=setSurat',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;
					me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	
	Close : function(tipe){//alert(this.elCover);
		var cover = 'Rekon_formcover';
		if(document.getElementById(cover)) delElem(cover);			
		if(tipe==null){
			document.body.style.overflow='auto';						
		}
		
	},

});
