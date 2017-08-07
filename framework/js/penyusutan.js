var PenyusutanSkpd = new SkpdCls({
	prefix : 'PenyusutanSkpd', formName:'adminForm'
});


var Penyusutan = new DaftarObj2({
	prefix : 'Penyusutan',
	url : 'pages.php?Pg=Penyusutan', 
	formName : 'Penyusutan_form',// 'ruang_form',
	
	formSusut: function(){
		var me = this;
		var cover = this.prefix+'_formcover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#adminForm').serialize(),
		  	url: this.url+'&tipe=formSusut',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				
					
				if (resp.err ==''){						
					document.getElementById(cover).innerHTML = resp.content;
								
				}else{
					delElem(cover);
					document.body.style.overflow='auto';
					alert(resp.err);
					
				}			
				
		  	}
		});
	},
	
	formSusutSatu: function(){ //1 barang
		var me = this;
		errmsg = this.CekCheckboxBi();
		if(errmsg ==''){ 
			//var box = this.GetCbxChecked();
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
			  	url: this.url+'&tipe=formSusutSatu',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
										
					if (resp.err ==''){						
						document.getElementById(cover).innerHTML = resp.content;
									
					}else{
						delElem(cover);
						document.body.style.overflow='auto';
						alert(resp.err);
						
					}			
					
			  	}
			});
		}
	},
	
	formKoreksi: function(){ //1 barang
		var me = this;
		errmsg = this.CekCheckboxBi();
		if(errmsg ==''){ 
			//var box = this.GetCbxChecked();
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
			  	url: this.url+'&tipe=formKoreksi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
										
					if (resp.err ==''){						
						document.getElementById(cover).innerHTML = resp.content;
									
					}else{
						delElem(cover);
						document.body.style.overflow='auto';
						alert(resp.err);
						
					}			
					
			  	}
			});
		}
	},	
	
	batalSusut : function(){
		var me = this;
		var errmsg = '';
		
		//errmsg = this.CekCheckboxBi();
		if((errmsg=='') && ( (document.getElementById('boxchecked').value == 0)||(document.getElementById('boxchecked').value == '')  )){
			errmsg= 'Data belum dipilih!';
		}

		if(errmsg =='' && confirm('Batalkan semua penyusutan untuk '+adminForm.boxchecked.value+' barang?') ){ 			
			var me = this;
			var cover = this.prefix+'_formcover';
			//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
			  	url: this.url+'&tipe=batalSusut',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');									
					delElem(cover);
					if (resp.err ==''){						
						//document.getElementById(cover).innerHTML = resp.content;
						//me.filterRender();
						//me.daftarRender();
						alert(resp.content.alert);
						Penatausaha.refreshList(true);							
					}else{
						
						//document.body.style.overflow='auto';
						alert(resp.err);					
					}
			  	}
			});
		
		}else {
			alert( errmsg);
		}
	},
	
	susut: function(){
		//alert('tes');
		var me = this;
		var err= '';
		var cover = this.prefix+'_formcoversust';
		
		var thnsusut = document.getElementById('thnsusut').value;
		if(err=='' && (thnsusut=='' || thnsusut == null ) ) err= ' Tahun laporan belum dipilih! ';
		//document.body.style.overflow='hidden';
		if(err==''){
			
			document.getElementById('btproses').disabled=true;
			document.getElementById('btbatal').disabled=true;
			document.getElementById('progressbox').style.display='block';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+'Penyusutan_form').serialize(),
			  	url: this.url+'&tipe=susut',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					delElem(cover);
					document.getElementById('btproses').disabled=false;
					document.getElementById('btbatal').disabled=false;
					//	document.body.style.overflow='auto';
					if (resp.err ==''){
						var  jmldata = parseInt(document.getElementById('jmldata').value);
						var  prog = parseInt(document.getElementById('prog').value);
						prog = prog + resp.content.jml;
						if(prog>jmldata) prog = jmldata;
						document.getElementById('prog').value = prog;
						var persen = ((prog/jmldata)*100);
						document.getElementById('progressbar').style.width = persen +'%';			
						//document.getElementById('progressmsg').innerHTML = prog+'/'+jmldata;			
						document.getElementById('progressmsg').innerHTML = formatNumber(prog,0,',','.')+'/'+formatNumber(jmldata,0,',','.');			
						if(persen<100) {
							 me.susut();
						}else{
							alert('Penyusutan Selesai');
							document.getElementById('progressbox').style.display='none';
							me.Close();
							Penatausaha.refreshList(true);
						}
					}else{
						alert(resp.err);
						
					}			
					
			  	}
			});
		}else{
			alert(err);
		}

	},
	
	susutSatu: function(){
		//alert('tes');
		var me = this;
		var err='';// 'Sementara tidak dapat melakukan penyusutan';
		
		var cover = this.prefix+'_formcoversust';
		
		var thnsusut = document.getElementById('thnsusut').value;
		if(err=='' && (thnsusut=='' || thnsusut == null ) ) err= ' Tahun laporan belum dipilih! ';
		//document.body.style.overflow='hidden';
		if(err==''){
			
			document.getElementById('btproses').disabled=true;
			document.getElementById('btbatal').disabled=true;
			document.getElementById('progressbox').style.display='block';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+'Penyusutan_form').serialize(),
			  	url: this.url+'&tipe=susutSatu',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					delElem(cover);
					document.getElementById('btproses').disabled=false;
					document.getElementById('btbatal').disabled=false;	//	document.body.style.overflow='auto';
					if (resp.err ==''){
						var  jmldata = parseInt(document.getElementById('jmldata').value);
						var  prog = parseInt(document.getElementById('prog').value);
						prog = prog + resp.content.jml;
						if(prog>jmldata) prog = jmldata;
						document.getElementById('prog').value = prog;
						var persen = ((prog/jmldata)*100);
						document.getElementById('progressbar').style.width = persen +'%';//document.getElementById('progressmsg').innerHTML = prog+'/'+jmldata;			
						document.getElementById('progressmsg').innerHTML = formatNumber(prog,0,',','.')+'/'+formatNumber(jmldata,0,',','.');			
						/*if(persen<100) {
							 me.susutSatu();
						}else{*/
							alert('Penyusutan Selesai');
							document.getElementById('progressbox').style.display='none';
							me.Close();
							Penatausaha.refreshList(true);
						//}
					}else{
						alert(resp.err);
						
					}			
					
			  	}
			});
		}else{
			alert(err);
		}

	},

	susutKoreksi: function(){
		//alert('tes');
		var me = this;
		var err='';// 'Sementara tidak dapat melakukan penyusutan';
		
		var cover = this.prefix+'_formcoversust';
		
		var tgl_koreksi = document.getElementById('tgl_koreksi').value;
		if(err=='' && (tgl_koreksi=='' || tgl_koreksi == null ) ) err= ' Tanggal Koreksi Belum diisi! ';
		//document.body.style.overflow='hidden';
		if(err==''){
			
			document.getElementById('btproses').disabled=true;
			document.getElementById('btbatal').disabled=true;
			document.getElementById('progressbox').style.display='block';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+'Penyusutan_form').serialize(),
			  	url: this.url+'&tipe=susutKoreksi',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					delElem(cover);
					document.getElementById('btproses').disabled=false;
					document.getElementById('btbatal').disabled=false;	//	document.body.style.overflow='auto';
					if (resp.err ==''){
						var  jmldata = parseInt(document.getElementById('jmldata').value);
						var  prog = parseInt(document.getElementById('prog').value);
						prog = prog + resp.content.jml;
						if(prog>jmldata) prog = jmldata;
						document.getElementById('prog').value = prog;
						var persen = ((prog/jmldata)*100);
						document.getElementById('progressbar').style.width = persen +'%';//document.getElementById('progressmsg').innerHTML = prog+'/'+jmldata;			
						document.getElementById('progressmsg').innerHTML = formatNumber(prog,0,',','.')+'/'+formatNumber(jmldata,0,',','.');			
						/*if(persen<100) {
							 me.susutSatu();
						}else{*/
							alert('Penyusutan Selesai');
							document.getElementById('progressbox').style.display='none';
							me.Close();
							Penatausaha.refreshList(true);
						//}
					}else{
						alert(resp.err);
						
					}			
					
			  	}
			});
		}else{
			alert(err);
		}

	},
	
	formRincian: function(){
		var me= this;
		errmsg = this.CekCheckboxBi();
		if(errmsg ==''){ 			
			var me = this;
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
			  	url: this.url+'&tipe=formRincian',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');									
					if (resp.err ==''){						
						document.getElementById(cover).innerHTML = resp.content;
						me.filterRender();
						me.daftarRender();							
					}else{
						delElem(cover);
						document.body.style.overflow='auto';
						alert(resp.err);					
					}
			  	}
			});
		
		}else {
			alert( errmsg);
		}
	},
	
	changeTahun: function(){
		//alert('tes');
		document.getElementById('btproses').disabled=true;
		document.getElementById('btbatal').disabled=true;
		$.ajax({
			type:'POST', 
			data:$('#'+'Penyusutan_form').serialize(),
		  	url: this.url+'&tipe=changeTahun',
		  	success: function(data) {	
				document.getElementById('btproses').disabled=false;
				document.getElementById('btbatal').disabled=false;
				var resp = eval('(' + data + ')');									
				if (resp.err ==''){	
					document.getElementById('vjmldata').innerHTML = resp.content.vjml;
					document.getElementById('jmldata').value =	resp.content.jml;			
					//document.getElementById(cover).innerHTML = resp.content; //me.filterRender(); //me.daftarRender();							
				}else{
					//delElem(cover); //document.body.style.overflow='auto';
					alert(resp.err);					
				}
		  	}
		});
	}
});
