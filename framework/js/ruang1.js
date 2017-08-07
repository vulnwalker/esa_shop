


var RuangPilih = new DaftarObj2({
	prefix : 'RuangPilih',
	url : 'pages.php?Pg=RuangPilih', 
	formName : 'RuangPilih_Form',
	idpilih:'',
	fmSKPD:'',
	fmUNIT:'',
	fmSUBUNIT:'',
	fmSEKSI:'',
	
	windowShow: function(){
		var me = this;
		var cover = this.prefix+'_cover';
		document.body.style.overflow='hidden';
		//var skpd = document.getElementById('SensusSkpdfmSKPD').value;
		
		addCoverPage2(cover,20,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=windowshow&fmSKPD='+me.fmSKPD+'&fmUNIT='+me.fmUNIT+'&fmSUBUNIT='+me.fmSUBUNIT+'&fmSEKSI='+me.fmSEKSI,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');							
				document.getElementById(cover).innerHTML = resp.content;	
				me.loading();						
		  	}
		});
	},	
	windowClose: function(){
		delElem(this.prefix+'_cover');
	},
	windowSave: function(){
		//alert('save');
		var me = this;
		var errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();	//alert(box.value);
			this.idpilih = box.value;
			//cek yg dipilih ruang -------------------			
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: 'pages.php?Pg=ruang&tipe=getdata&id='+this.idpilih,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					//alert(resp.content.nm_ruang);							
					if(resp.content.q == '0000') {
						alert('Pilih hanya Ruangan! Kode ruangan tidak boleh xxx.0000')
					}else{
						me.windowClose();
						me.windowSaveAfter();
					}
			  	}
			});
			
		}else{
			alert(errmsg);
		}
	},
	windowSaveAfter: function(){
		alert('tes');
	}
	
});



var Ruang = new DaftarObj2({
	prefix : 'Ruang',
	url : 'pages.php?Pg=ruang', 
	formName : 'adminForm',// 'ruang_form',
	
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById('RuangSkpdfmSKPD').value; 
		var unit = document.getElementById('RuangSkpdfmUNIT').value;
		var subunit = document.getElementById('RuangSkpdfmSUBUNIT').value;
		var SEKSI = document.getElementById('RuangSkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000' ) ) err='SUB UNIT belum dipilih!';
		
		
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
	},	
	
	formPilih : function(){	
		var me = this;		
		RuangPilih.windowSaveAfter= function(){
			me.pilihRuangAfter(this.idpilih);
		}
		RuangPilih.windowShow();
		
		
		/*var me = this;
		var cover = this.prefix+'_formPilihcover';
		document.body.style.overflow='hidden';
		//var skpd = document.getElementById('SensusSkpdfmSKPD').value;
		
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=formPilih',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');			
				document.getElementById(cover).innerHTML = resp.content;	
				RuangPilih.loading();		
				//me.AfterFormPilih();
		  	}
		});
		*/
	},
	/*
	RuangPilihClose : function(){
		delElem(this.prefix+'_formPilihcover');	
	},
	pilihRuangAfter: function(idpilih){
		alert('pilih '+idpilih);
		var cover = 'coverSimpanPilihRuang';
		//save ---------------------
		addCoverPage2(cover,1,true,false);	
			//document.body.style.overflow='hidden';
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=formEdit',
			success: function(data) {		
				var resp = eval('(' + data + ')');			
				//document.getElementById(cover).innerHTML = resp.content;					
			}
		});
		
	}
	*/
	
	
});

