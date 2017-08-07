var dpb_spkSkpd = new SkpdCls({
	prefix : 'dpb_spkSkpd', formName:'adminForm'
});

var dpb_spk = new DaftarObj2({
	prefix : 'dpb_spk',	
	url : 'pages.php?Pg=dpb_spk',
	formName : 'adminForm',
	
	
	BidangAfter: function(){
		var me = this;
		document.getElementById('btTampil').disabled = true;
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=BidangAfter',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
				document.getElementById('fmSKPDskpd').innerHTML=resp.content;
				document.getElementById('btTampil').disabled = false;	
		  }
		});
	},
	SKPDAfter: function(){
		var me = this;
		document.getElementById('btTampil').disabled = true;
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=SKPDAfter',
		  type : 'POST',
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				
			document.getElementById('btTampil').disabled = false;	
				
		  }
		});
	},
	
	 
	Baru2: function(){	
		var me = this;
		var err='';
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();	
		var cover = this.prefix+'_formcover';var skpd = document.getElementById('fmSKPDBidang').value; 
		var unit = document.getElementById('fmSKPDskpd').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
				
		if(err==''){
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaru2&sw='+sw+'&sh='+sh,
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
			
		}else{
			alert(err);
		}
	},
	
	Edit2: function(){	
		var me = this;
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();
		errmsg = this.CekCheckbox();
		
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,1,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formEdit2&sw='+sw+'&sh='+sh,
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						me.AfterFormEdit(resp);
					}else{
						alert(resp.err);
						delElem(cover);
						document.body.style.overflow='auto';
					}
			  	}
			});
		}else{
			alert(errmsg);
		}
	},
	
	
	ProgramAfter: function(){	
		
		var me = this;
		var err='';
		var bidang = document.getElementById('c').value;
		var skpd = document.getElementById('d').value; 
		var program = document.getElementById('p').value;

			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:"c="+bidang+"&d="+skpd+"&p="+program,
			  	url: this.url+'&tipe=ProgramAfter',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById('div_q').innerHTML = resp.content;
				}
			});
	},
	showDpb_det:function(divID) {
     var item = document.getElementById(divID);
	 var img = document.getElementById('barDpb_det_ico');
	 if(item.style.display == 'none'){
          item.style.display = 'block';
		  img.src  = 'images/tumbs/down.png';
		   
	  }else{
	  	 if(item.style.display = 'block'){
		 	item.style.display = 'none';
			img.src = 'images/tumbs/right.png';
		 }  
	  }
       
     },
	 AfterFormBaru:function(){		
		//alert("tes");
		idsk = document.getElementById('idsk').value;
		c = document.getElementById('c').value;
		d = document.getElementById('d').value;
		document.getElementById('divDpb_spkList').innerHTML = 
			"<div id='dpb_spk_det_cont_opsi' style='position:relative'>"+
			"<input type='hidden' name='idsk' id='idsk' value='"+idsk+"'>"+
			"<input type='hidden' name='c' id='c' value='"+c+"'>"+
			"<input type='hidden' name='d' id='d' value='"+d+"'>"+
			"</div>"+
			"<div id='dpb_spk_det_cont_daftar' style='position:relative'></div>"+
			"<div id='dpb_spk_det_cont_hal' style='position:relative'></div>";
		//generate data
		dpb_spk_det.loading();
	},
	AfterFormEdit:function(){		
		//alert("tes");
		idsk = document.getElementById('idsk').value;
		c = document.getElementById('c').value;
		d = document.getElementById('d').value;
		document.getElementById('divDpb_spkList').innerHTML = 
			"<div id='dpb_spk_det_cont_opsi' style='position:relative'>"+
			"<input type='hidden' name='idsk' id='idsk' value='"+idsk+"'>"+
			"<input type='hidden' name='c' id='c' value='"+c+"'>"+
			"<input type='hidden' name='d' id='d' value='"+d+"'>"+
			"</div>"+
			"<div id='dpb_spk_det_cont_daftar' style='position:relative'></div>"+
			"<div id='dpb_spk_det_cont_hal' style='position:relative'></div>";
		//generate data
		dpb_spk_det.loading();
	},
	
	Simpan2: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpan2',
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
	
	formNoDialog_show: function(){
		
	},
	
	
});