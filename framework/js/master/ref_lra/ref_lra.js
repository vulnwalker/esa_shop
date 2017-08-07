var Ref_LRASkpd = new SkpdCls({
	prefix : 'Ref_LRASkpd', formName:'Ref_LRAForm'
});


var Ref_LRA = new DaftarObj2({
	prefix : 'Ref_LRA',
	url : 'pages.php?Pg=lra', 
	formName : 'Ref_LRAForm',
	 
	Baru : function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById('fmSKPDBidang').value; 
		var unit = document.getElementById('fmSKPDskpd').value;
		//var bidang = document.getElementById('fmBIDANG').value; 
		/*
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		*/
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG SKPD belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		//if(err=='' && (bidang=='' || bidang=='00') ) err='BIDANG belum dipilih!';
		/*
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='000') ) err='SUBUNIT belum dipilih!';
		*/
		
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
	
	
	Simpan: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					alert('Data berhasil disimpan');
					me.Close();
					me.refreshList(true);
					me.AfterSimpan();	
				}
				else{
					alert(resp.err);
				}
		  	}
		});
	},		
	
	BidangAfter: function(){
		var me = this;
		var fmBidang = document.getElementById('fmBIDANG2').value;
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=BidangAfter&fmBidang='+fmBidang,
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
				document.getElementById('kelompok_formdiv').innerHTML=resp.content.kelompok;
				document.getElementById('subkelompok_formdiv').innerHTML=resp.content.subkelompok;
				document.getElementById('subsubkelompok_formdiv').innerHTML=resp.content.subsubkelompok;
				
		  }
		});
	},
	
	KelompokAfter: function(){
		var me = this;
		var fmBidang = document.getElementById('fmBIDANG2').value;
		var fmKelompok = document.getElementById('fmKELOMPOK2').value;
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=KelompokAfter&fmBidang='+fmBidang+'&fmKelompok='+fmKelompok,
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
				document.getElementById('subkelompok_formdiv').innerHTML=resp.content.subkelompok;
				document.getElementById('subsubkelompok_formdiv').innerHTML=resp.content.subsubkelompok;
				
		  }
		});
	},
	
	SubKelompokAfter: function(){
		var me = this;
		var fmBidang = document.getElementById('fmBIDANG2').value;
		var fmKelompok = document.getElementById('fmKELOMPOK2').value;
		var fmSubKelompok = document.getElementById('fmSUBKELOMPOK2').value;
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=SubKelompokAfter&fmBidang='+fmBidang+'&fmKelompok='+fmKelompok+'&fmSubKelompok='+fmSubKelompok,
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
				document.getElementById('subsubkelompok_formdiv').innerHTML=resp.content;
				
		  }
		});
	},
	
	BidangAfter2: function(){
		var me = this;
		
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=BidangAfter2',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
				document.getElementById('fmSKPDskpd').innerHTML=resp.content;
				
		  }
		});
	},
	SKPDAfter: function(){
		var me = this;
		
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=SKPDAfter',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
				
				
		  }
		});
	}, 
	
});
