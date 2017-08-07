
var Sensus = new DaftarObj2({
	prefix : 'Sensus',
	//elCurrPage : 'pbbpenetapanHalDefault',	
	url : 'pages.php?Pg=sensus&ajx=1',
	formName : 'SensusPageForm',
	
	
	daftarRenderAfter : function(resp){
		$('.toggler').click(function(e){
						        e.preventDefault();
						        //$("#det_"+$(this).attr("data-prod-cat")).toggle();
								$("tr[id=det_"+$(this).attr("data-prod-cat")+"]").toggle();
								//alert($(this).attr('state'));
								if( $(this).attr('state') == 'colapse' ){
									$(this).attr('state', 'expand');
									$(this).html ( "<img id='toggler_img' src='images/tumbs/up_2.png'  style='cursor:pointer;width:10px'>" );	
								}else{
									$(this).attr('state', 'colapse');
									$(this).html( "<img id='toggler_img' src='images/tumbs/down_2.png'  style='cursor:pointer;width:10px'>");										
								}					
								
								//alert('tes');
						    });	
	},
	
	entryAdaOnchange :function(th){
		//alert(th.value);
		if(th.value== '1'){
			document.getElementById('btreset1').removeAttribute('disabled');
			document.getElementById('btreset2').removeAttribute('disabled');
			document.getElementById('btreset3').removeAttribute('disabled');
			document.getElementById('btreset4').removeAttribute('disabled');			
			document.getElementById('btRuang').removeAttribute('disabled');
			document.getElementById('btPemegang2').removeAttribute('disabled');
			document.getElementById('btPemegang').removeAttribute('disabled');
			document.getElementById('btPenanggung').removeAttribute('disabled');
			document.getElementById('catatan').removeAttribute('disabled');
			document.getElementById('kondisi').removeAttribute('disabled');			
			document.getElementById('petugas').removeAttribute('disabled');
			document.getElementById('status_penguasaan').removeAttribute('disabled');			
		}else{			
			document.getElementById('btreset1').setAttribute('disabled', '1');
			document.getElementById('btreset2').setAttribute('disabled', '1');
			document.getElementById('btreset3').setAttribute('disabled', '1');
			document.getElementById('btreset4').setAttribute('disabled', '1');
			
			document.getElementById('kondisi').setAttribute('disabled', '1');
			document.getElementById('btRuang').setAttribute('disabled', '1');
			document.getElementById('btPemegang2').setAttribute('disabled', '1');
			document.getElementById('btPemegang').setAttribute('disabled', '1');
			document.getElementById('btPenanggung').setAttribute('disabled', '1');
			document.getElementById('catatan').setAttribute('disabled', '1');
			document.getElementById('petugas').setAttribute('disabled', '1');
			document.getElementById('status_penguasaan').setAttribute('disabled', '1');
			
			document.getElementById('kondisi').value= '';
			document.getElementById('catatan').value= '';
			document.getElementById('petugas').value= '';
			
			document.getElementById('nm_gedung').value= '';
			document.getElementById('nm_ruang').value= '';
			document.getElementById('ref_idruang').value= '';
			
			document.getElementById('ref_idpemegang2').value= '';
			document.getElementById('nama3').value= '';
			document.getElementById('nip3').value= '';
			document.getElementById('jbt3').value= '';
			
			document.getElementById('ref_idpemegang').value= '';
			document.getElementById('nama1').value= '';
			document.getElementById('nip1').value= '';
			document.getElementById('jbt1').value= '';
			
			document.getElementById('ref_idpenanggung').value= '';
			document.getElementById('nama2').value= '';
			document.getElementById('nip2').value= '';
			document.getElementById('jbt2').value= '';
			
			document.getElementById('status_penguasaan').value='';
			
		}	
	},
	usulHapus: function(){
		//alert('tes');	
		var me =this;
		var err='';
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		
		if(err==''){
			
		
		
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		
		if(jmlcek ==0){
			alert('Data Belum Dipilih!');
		}else{
			
			if(confirm('Usulkan Penghapusan '+jmlcek+' Data ?')){
				//alert('form usulan hapus');
				var cover = 'UsulanHapus_formcover';//this.prefix+'_formUsulCover';
				//document.body.style.overflow='hidden';
				//addCoverPage2(cover,999,true,false);
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: 'pages.php?Pg=usulanhapus&ajx=1&tipe=insertUsulan',
				  	success: function(data) {		
						var resp = eval('(' + data + ')');		
						
						if(resp.err==''){							
							document.getElementById(cover).innerHTML = resp.content;//;	
							document.getElementById('div_detail').innerHTML = 
							"<div id='div_detail' style='position:relative'>"+
								"<div id='UsulanHapusdetail_cont_title' style='position:relative'></div>"+
								"<div id='UsulanHapusdetail_cont_opsi' style='position:relative'></div>"+
								"<div id='UsulanHapusdetail_cont_daftar' style='position:relative'></div>"+
								"<div id='UsulanHapusdetail_cont_hal' style='position:relative'></div>"+
							"</div>";
							//generate data
							UsulanHapusdetail.loading();	
						}else{
							delElem(cover);			
							alert(resp.err);
						}							
						
				  	}
				});
				//*/
			}	
		}
		
		}else{
			alert(err);
		}
	},
	
	topBarRender : function(){
		var me=this;
		//render subtitle
		$.ajax({
		  url: this.url+'&tipe=subtitle',
		 type:'POST', 
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			document.getElementById(me.prefix+'_cont_title').innerHTML = resp.content;
		  }
		});
	},
	
	AfterFormBaru : function(){
		//if(document.getElementById('Sensus_cont_daftar')) 
		SensusTmp.loading();
		
		//if(document.getElementById('penatausaha_cont_list')) Penatausaha.refreshList(true);
	},
	filterRenderAfter : function(){
		barcodeSensus.loading();
	},
	Batal: function(){
		var me = this;
		
		//var terus = true;
		var idplh = document.getElementById('Sensus_idplh').value;
		var terus = idplh!=''? true : confirm('Batalkan?');
		
		if (terus){				
			$.ajax({
				url: 'pages.php?Pg=sensus&tipe=batal',
			  	type : 'POST',
			  	data:$('#'+this.prefix+'_form').serialize(), //data:$('#adminForm').serialize(),
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					//delElem(cover);
					if(resp.err==''){
						me.Close();	
						if(document.getElementById('Sensus_cont_daftar')) me.refreshList(true);				
						if(document.getElementById('penatausaha_cont_list')) Penatausaha.refreshList(true);
					}else{
						alert(resp.err)	
					}
			  	}
			});
		}
	},
	simpanBaru: function(){
		var me = this;
		var cover = this.name+'_cover_simpanBaru';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			url: 'pages.php?Pg=sensus&tipe=simpanBaru',
		  	type : 'POST',
		  	data:$('#'+this.prefix+'_form').serialize(), //data:$('#adminForm').serialize(),
		  	success: function(data) {		
				var resp = eval('(' + data + ')');			
				delElem(cover);
				if(resp.err==''){
					me.Close();	
					//me.refreshList(true);		
					if(document.getElementById('Sensus_cont_daftar')) me.refreshList(true);				
					if(document.getElementById('penatausaha_cont_list')) Penatausaha.refreshList(true);		
				}else{
					alert(resp.err)	
				}
		  	}
		});
	},
	simpanEdit: function(){
		var me = this;
		var cover = this.name+'_cover_simpanEdit';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			url: 'pages.php?Pg=sensus&tipe=simpanEdit',
		  	type : 'POST',
		  	data:$('#'+this.prefix+'_form').serialize(), //data:$('#adminForm').serialize(),
		  	success: function(data) {		
				var resp = eval('(' + data + ')');			
				delElem(cover);
				if(resp.err==''){
					me.Close();	
					//me.refreshList(true);				
					if(document.getElementById('Sensus_cont_daftar')) me.refreshList(true);				
					if(document.getElementById('penatausaha_cont_list')) Penatausaha.refreshList(true);
				}else{
					alert(resp.err)	
				}
		  	}
		});
	},
	sumHalRenderAfter:function(resp){
		if(resp.err ==''){
			if(resp.content.jmldata == 0) alert('Barang Tidak Ada!');
			barcodeSensus.reset();
		}
	},
	pilihRuang : function(){		
		var me = this;		
		
		if(document.getElementById('SensusEditSkpdfmSKPD')) {
			RuangPilih.fmSKPD = document.getElementById('SensusEditSkpdfmSKPD').value;
			RuangPilih.fmUNIT = document.getElementById('SensusEditSkpdfmUNIT').value;
			RuangPilih.fmSUBUNIT = document.getElementById('SensusEditSkpdfmSUBUNIT').value;
			RuangPilih.fmSEKSI = document.getElementById('SensusEditSkpdfmSEKSI').value;
		}else if(document.getElementById('SensusTmpSkpdfmSKPD')) {
			RuangPilih.fmSKPD = document.getElementById('SensusTmpSkpdfmSKPD').value;
			RuangPilih.fmUNIT = document.getElementById('SensusTmpSkpdfmUNIT').value;
			RuangPilih.fmSUBUNIT = document.getElementById('SensusTmpSkpdfmSUBUNIT').value;
			RuangPilih.fmSEKSI = document.getElementById('SensusTmpSkpdfmSEKSI').value;
		}
		
		RuangPilih.el_idruang= 'ref_idruang';
		RuangPilih.el_nmgedung= 'nm_gedung';
		RuangPilih.el_nmruang= 'nm_ruang';
		RuangPilih.windowSaveAfter= function(){};			
		
		RuangPilih.windowShow();
	},	
	pilihPemegang : function(){		
		var me = this;	
		
		if(document.getElementById('SensusEditSkpdfmSKPD')) {
			PegawaiPilih.fmSKPD = document.getElementById('SensusEditSkpdfmSKPD').value;
			PegawaiPilih.fmUNIT = document.getElementById('SensusEditSkpdfmUNIT').value;
			PegawaiPilih.fmSUBUNIT = document.getElementById('SensusEditSkpdfmSUBUNIT').value;
			PegawaiPilih.fmSEKSI = document.getElementById('SensusEditSkpdfmSEKSI').value;
		}else if(document.getElementById('SensusTmpSkpdfmSKPD')) {
			PegawaiPilih.fmSKPD = document.getElementById('SensusTmpSkpdfmSKPD').value;
			PegawaiPilih.fmUNIT = document.getElementById('SensusTmpSkpdfmUNIT').value;
			PegawaiPilih.fmSUBUNIT = document.getElementById('SensusTmpSkpdfmSUBUNIT').value;
			PegawaiPilih.fmSEKSI = document.getElementById('SensusTmpSkpdfmSEKSI').value;
		}else if(document.getElementById('fmSKPD')) { //KIP
			PegawaiPilih.fmSKPD = document.getElementById('fmSKPD').value;
			PegawaiPilih.fmUNIT = document.getElementById('fmUNIT').value;
			PegawaiPilih.fmSUBUNIT = document.getElementById('fmSUBUNIT').value;
			PegawaiPilih.fmSEKSI = document.getElementById('fmSEKSI').value;
		}
		PegawaiPilih.el_idpegawai = 'ref_idpemegang';
		PegawaiPilih.el_nip= 'nip1';
		PegawaiPilih.el_nama= 'nama1';
		PegawaiPilih.el_jabat= 'jbt1';
					
		
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();
	},	
	pilihPemegang2 : function(){		
		var me = this;	
		
		if(document.getElementById('SensusEditSkpdfmSKPD')) {
			PegawaiPilih.fmSKPD = document.getElementById('SensusEditSkpdfmSKPD').value;
			PegawaiPilih.fmUNIT = document.getElementById('SensusEditSkpdfmUNIT').value;
			PegawaiPilih.fmSUBUNIT = document.getElementById('SensusEditSkpdfmSUBUNIT').value;
			PegawaiPilih.fmSEKSI = document.getElementById('SensusEditSkpdfmSEKSI').value;
		}else if(document.getElementById('SensusTmpSkpdfmSKPD')) {
			PegawaiPilih.fmSKPD = document.getElementById('SensusTmpSkpdfmSKPD').value;
			PegawaiPilih.fmUNIT = document.getElementById('SensusTmpSkpdfmUNIT').value;
			PegawaiPilih.fmSUBUNIT = document.getElementById('SensusTmpSkpdfmSUBUNIT').value;			
			PegawaiPilih.fmSEKSI = document.getElementById('SensusTmpSkpdfmSEKSI').value;			
		}
		PegawaiPilih.el_idpegawai = 'ref_idpemegang2';
		PegawaiPilih.el_nip= 'nip3';
		PegawaiPilih.el_nama= 'nama3';
		PegawaiPilih.el_jabat= 'jbt3';
					
		
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();
	},	
	pilihPenanggung : function(){		
		var me = this;		
		
		if(document.getElementById('SensusEditSkpdfmSKPD')) {
			PegawaiPilih.fmSKPD = document.getElementById('SensusEditSkpdfmSKPD').value;
			PegawaiPilih.fmUNIT = document.getElementById('SensusEditSkpdfmUNIT').value;
			PegawaiPilih.fmSUBUNIT = document.getElementById('SensusEditSkpdfmSUBUNIT').value;
			PegawaiPilih.fmSEKSI = document.getElementById('SensusEditSkpdfmSEKSI').value;
		}else if(document.getElementById('SensusTmpSkpdfmSKPD')) {
			PegawaiPilih.fmSKPD = document.getElementById('SensusTmpSkpdfmSKPD').value;
			PegawaiPilih.fmUNIT = document.getElementById('SensusTmpSkpdfmUNIT').value;
			PegawaiPilih.fmSUBUNIT = document.getElementById('SensusTmpSkpdfmSUBUNIT').value;			
			PegawaiPilih.fmSEKSI = document.getElementById('SensusTmpSkpdfmSEKSI').value;			
		}	
		PegawaiPilih.el_idpegawai = 'ref_idpenanggung';
		PegawaiPilih.el_nip= 'nip2';
		PegawaiPilih.el_nama= 'nama2';
		PegawaiPilih.el_jabat= 'jbt2';
				
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();
	},	
	
	cetakKertasKerja: function(tipe){
		if(tipe==0){
			adminForm.action='index.php?Pg=PR&SPg=belumsensus&tipe=kertaskerja';
		}else{
			adminForm.action='index.php?Pg=PR&SPg=belumsensus&tipe=kertaskerja&ctk=1';
			
		}
		adminForm.target='_blank';
		adminForm.submit();
		adminForm.target='';	
		
	},
	cetakKertasKerjaKib: function(tipe,xls){
		//this.setFormCetakKK();
		//if(tipe==0){
		//var start = document
		if ( document.getElementById('limitdata').checked) {
			var limitdata = 0;
		}else{
			var limitdata = 1;
		};
		var limitstart = document.getElementById('limitstart').value;
		var limitend = document.getElementById('limitend').value;
		var limit = '&limitdata='+limitdata+'&limitstart='+limitstart+'&limitend='+limitend;
		if (!xls) xls=0;
		
		if(xls==1) {
			var vxls = '&xls=1';
		}else{
			var vxls = '';
		}
		
		switch (tipe){
			case 1 : adminForm.action='index.php?Pg=PR&SPg=kib_a_cetak&tipe=kertaskerja&ctk=1'+limit+vxls; break;
			case 2 : adminForm.action='index.php?Pg=PR&SPg=kib_b_cetak&tipe=kertaskerja&ctk=1'+limit+vxls; break;
			case 3 : adminForm.action='index.php?Pg=PR&SPg=kib_c_cetak&tipe=kertaskerja&ctk=1'+limit+vxls; break;
			case 4 : adminForm.action='index.php?Pg=PR&SPg=kib_d_cetak&tipe=kertaskerja&ctk=1'+limit+vxls; break;
			case 5 : adminForm.action='index.php?Pg=PR&SPg=kib_e_cetak&tipe=kertaskerja&ctk=1'+limit+vxls; break;
		}
			
		//}else{
		//	adminForm.action='index.php?Pg=PR&SPg=belumsensus&tipe=kertaskerja&ctk=1';			
		//}
		adminForm.target='_blank';
		adminForm.submit();
		adminForm.target='';	
		
	},
	
	Baru : function(){
		var me = this;
		var cover = this.prefix+'_formcover';
		document.body.style.overflow='hidden';
		//addCoverPage2(cover,999,true,false);	
		addCoverPage2(cover,1,true,false);
		if(document.getElementById(this.formName)) formName = this.formName;
		//if(document.getElementById('adminFormSensusScan')) formName = 'adminFormSensusScan';
		if(document.getElementById('adminForm')) formName = 'adminForm';
		
		$.ajax({
			type:'POST', 
			data:$('#'+formName).serialize(),
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
	
	AfterFormEdit2 : function(){
		SensusTmp.loading();
	},
	
	//*
	Edit2 : function(){
		var me = this;
		errmsg = this.CekCheckbox('SensusScan');
		if(errmsg ==''){ 
			var box = this.GetCbxChecked('SensusScan');
					
			var cover = this.prefix+'_formcover';
			//addCoverPage2(cover,999,true,false);	
			addCoverPage2(cover,1,true,false);
			document.body.style.overflow='hidden';
			if(document.getElementById(this.formName)) formName = this.formName;
			if(document.getElementById('adminFormSensusScan')) formName = 'adminFormSensusScan';
			if(document.getElementById('adminForm')) formName = 'adminForm';
			$.ajax({
				type:'POST', 
				data:$('#'+formName).serialize(),
				url: this.url+'&tipe=formEdit2',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						me.AfterFormEdit2(resp);
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
	//*/
	
	formCetakKKCek: function(){
		if ( document.getElementById('limitdata').checked) {
			document.getElementById('limitstart').disabled   = true;
			document.getElementById('limitend').disabled  = true;
		}else{
			document.getElementById('limitstart').disabled  = false;
			document.getElementById('limitend').disabled  = false;
		}
	},
	formCetakKKClose : function(){
		delElem('sensus_formCetakCover');	
	},
	setFormCetakKK: function(tipe, jmldata, vjmldata){
		switch(tipe){
			case '1': kib='A'; break;
			case '2': kib='B'; break;
			case '3': kib='C'; break;
			case '4': kib='D'; break;
			case '5': kib='E'; break;
			case '6': kib='F'; break;
		}
		var form_judul = 'Cetak Kertas Kerja KIB '+kib;
		var form_width = '500';
		var form_height = '200';
		var cover ='sensus_formCetakCover';
		//var jmldata = 10;
		//document.body.style.overflow='hidden';
		//addCoverPage2(cover,999,true,false);
		addCoverPage2(cover,1,true,false);
		
		var form_menu =
			"<div style='padding: 0 8 9 8;height:22; '>"+
			"<div style='float:right;'>"+
				"<input type='button' value='Excel' onclick='Sensus.cetakKertasKerjaKib("+tipe+",1)'>"+
				"<input type='button' value='Cetak' onclick='Sensus.cetakKertasKerjaKib("+tipe+")'>"+
				"<input type='button' value='Batal' onclick='Sensus.formCetakKKClose()'>"+
				"<input type='hidden' id='Sensus_idplh' name='Sensus_idplh' value='109'><input type='hidden' id='Sensus_fmST' name='Sensus_fmST' value='1'>"+
				"<input type='hidden' id='sesi' name='sesi' value=''>"+
			"</div>"+
			"</div>";
		
		var content = 
			"<div id='Sensus_form_div' style='margin:9 8 8 8; overflow:auto; border:1px solid #E5E5E5;width:"+(form_width-20)+";height:"+(form_height-80)+";'>"+
				"<table style='width:100%' class='tblform'><tr><td style='padding:4'>"+
					"<table style='width:100%:height:100%'>"+
						"<tr>"+
							"<td style='' >"+
							"Ada "+vjmldata+" data "+
							"</td>"+
							"<td style='' colspan='2'>"+
							"</td>"+														
						"</tr>"+
						"<tr>"+
							"<td style='' colspan='3' >"+
							"<input type='checkbox' id='limitdata' name='limitdata' style='margin-left:0;' onchange='Sensus.formCetakKKCek()'> Cetak Semua </td>"+														
						"</tr>"+
						"<tr>"+
							"<td style='width:80'>Cetak data ke</td>"+
							"<td style='width:10'>:</td>"+
							"<td>"+
								"<input type='text' id='limitstart' name='limitstart' value='1' style='width:100;'>"+
								"&nbsp;&nbsp; s/d data ke &nbsp;&nbsp;"+
								"<input type='text' id='limitend' name='limitend' style='width:100;' value='1'>"+
							"</td>"+
						"</tr>"+						
					"</table>"+
				"</td></tr></table>"+
			"</div>";
		
		
		document.getElementById(cover).innerHTML= 
			"<table width='100%' height='100%'><tbody><tr><td align='center'>"+
			//"rtera"+
			"<div id='div_border' style='width:"+form_width+";height:"+form_height+"; background-color:white; border-color: rgba(0, 0, 0, 0.3);   border-style: solid;  border-width:1; box-shadow: 6px 6px 5px rgba(0, 0, 0, 0.3);'>"+
			"<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tbody><tr><td style='padding:0'>"+
				"<div class='menuBar2' style='height:20'>"+			
				"<span style='cursor:default;position:relative;left:6;top:2;color:White;font-size:12;font-weight:bold'>"+form_judul+"</span>"+
				"</div>"+
			"</td></tr></tbody></table>"+			
			content+
			form_menu+		
			"</div>"+
				
			"</td></tr>"+
			"</table>";
		
			
	},
	formCetakKKShow : function(tipe){
		var me = this;
		if(document.getElementById(this.formName)) formName = this.formName;
		if(document.getElementById('adminForm')) formName = 'adminForm';
		$.ajax({
			type:'POST', 
			data:$('#'+formName).serialize(),
		  	url: this.url+'&tipe=getjmlcetakkkerja&kib='+tipe,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				if (resp.err ==''){
					//document.getElementById(cover).innerHTML = resp.content;			
					//me.AfterFormBaru(resp);	
					//alert(resp.content.jmldata);
					me.setFormCetakKK(tipe, resp.content.jmldata, resp.content.vjmldata);
				}else{
					alert(resp.err);
					//delElem(cover);
					//document.body.style.overflow='auto';
				}			
				
		  	}
		});
		
	},
	CekBarang : function (){
	jmldata = document.getElementById('boxchecked').value ;
	c=document.getElementById('fmSKPD').value;
	d=document.getElementById('fmUNIT').value;
	e=document.getElementById('fmSUBUNIT').value;
	e1=document.getElementById('fmSEKSI').value;
	errmsg='';
	if (c=='00' || d=='00' || e=='00' || e1=='00' || e1=='000' ){
		errmsg='1';
		alert('SKPD Belum Dipilih!');	
		
	}
	if (errmsg=='')
	{
		
	
	if (jmldata>0 ) {
	lanjut=  confirm('Apakah '+jmldata+' barang ini akan di sensus?') ;
	if (lanjut){
		var cover = 'coverinsertbarcode';	
		addCoverPage(cover,100);
					
		$.ajax({		
			type:'POST', 		
			url: 'pages.php?Pg=sensustmp&tipe=insertsensusmanual', 
			data:$('#adminForm').serialize(), 
			success: function(response) {
				var resp = eval('(' + response + ')');
				delElem('coverinsertbarcode');
				if(resp.err != ''){
				}else{
				}
				// me.reset();
				Penatausaha.refreshList(true);
			}
		});


	}	
	} else {
		alert('Data Belum Dipilih!');	
		errmsg='1';

	}
	}
		
	},	
	
	FormCetakShow : function(jnsform){	
		
		var me = this;
		var err='';		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=FormCetakShow&jnsform='+jnsform,
			  	success: function(data) {	
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;	
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},		
	
	formCetakCek: function(){
		if ( document.getElementById('limitdata').checked) {
			document.getElementById('limitstart').disabled   = true;
			document.getElementById('limitend').disabled  = true;
		}else{
			document.getElementById('limitstart').disabled  = false;
			document.getElementById('limitend').disabled  = false;
		}
	},
	
	cetakHal : function(){
		var haldefault = document.getElementById('haldefault').value;
		var tipe_cetak = 'cetak_hal&haldefault='+haldefault;
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe='+tipe_cetak;
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	},
	cetakAll : function(){
		var limitstart = document.getElementById('limitstart').value;
		var limitend = document.getElementById('limitend').value;
		if(document.getElementById('limitdata').checked==true){//cetak semua
			var tipe_cetak = 'cetak_all&checked=1';
		}else{//cetak limit
			var tipe_cetak = 'cetak_hal&limitstart='+limitstart+'&limitend='+limitend+'&checked=0';
		}
		//var limit = '&limitstart='+limitstart+'&limitend='+limitend+'&checked='+checked;
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe='+tipe_cetak;
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	},
	exportXlsHal : function(){		
		var haldefault = document.getElementById('haldefault').value;
		var tipe_cetak = 'exportXlsHal&haldefault='+haldefault;		
		var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe='+tipe_cetak;
		//aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	},
	exportXlsAll : function(){		
		var limitstart = document.getElementById('limitstart').value;
		var limitend = document.getElementById('limitend').value;
		if(document.getElementById('limitdata').checked==true){//cetak semua
			var tipe_cetak = 'exportXlsAll&checked=1';
		}else{//cetak limit
			var tipe_cetak = 'exportXlsHal&limitstart='+limitstart+'&limitend='+limitend+'&checked=0';
		}var aForm = document.getElementById(this.formName);		
		aForm.action=this.url+'&tipe='+tipe_cetak;
		//aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	}
});

var SensusTmp = new DaftarObj2({
	prefix : 'SensusTmp',
	url : 'pages.php?Pg=sensustmp', 
	formName : 'Sensus_form',
	filterRenderAfter : function(){		
		barcodeSensusBaru.loading();
	},	
	entryCatatan : function(){		
		var me = this;		
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}		
		var pilihsemua = document.getElementById('cbxsemua').checked;
		if(jmlcek ==0 && pilihsemua==false){
			alert('Data Belum Dipilih!');
		}else{
			var lanjut= true;
			if(pilihsemua){
				lanjut=  confirm('Entry Catatan untuk semua data ?') ;
			}else if (jmlcek>1 ){
				lanjut=  confirm('Entry Catatan untuk '+jmlcek+' data ?') ;
			}
			if(lanjut){			
				var cover = 'coverEntryCatatan';
				//addCoverPage2(cover,999,true,false);	
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=entryCatatan',
					success: function(data) {		
						var resp = eval('(' + data + ')');					
						if(resp.err == ''){
							document.getElementById(cover).innerHTML=resp.content ;
						}else{
							alert(resp.err);
						}
					}
				});
			}
		}
	},
	entryCatatanClose: function(){
		delElem('coverEntryCatatan');
	},
	
	entryCatatanSimpan: function(){
		var me= this;		
		var catatan = document.getElementById('catatan').value;
		//this.pilihKondisiClose();
		//save ---------------------
		var cover = 'coverEntryCatatanSimpan';
		//addCoverPage2(cover,999,true,false);	
		addCoverPage2(cover,1,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=simpanEntryCatatan&catatan='+catatan,
			success: function(data) {		
				var resp = eval('(' + data + ')');							
				delElem(cover);
				me.entryCatatanClose();
				me.refreshList(false);
			}
		});
	},
	entryAda: function(){
		var me = this;		
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		var pilihsemua = document.getElementById('cbxsemua').checked;				
		if(jmlcek ==0 && pilihsemua==false){
			alert('Data Belum Dipilih!');
		}else{
			var lanjut= true;
			if(pilihsemua){
				lanjut=  confirm('Entry untuk semua data ?') ;
			}else if (jmlcek>1 ){
				lanjut=  confirm('Entry untuk '+jmlcek+' data ?') ;
			}
			if(lanjut){	
				var cover = 'coverEntryAda';
				//addCoverPage2(cover,999,true,false);	
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=entryAda',
					success: function(data) {		
						var resp = eval('(' + data + ')');					
						if(resp.err == ''){
							document.getElementById(cover).innerHTML=resp.content ;
						}else{
							alert(resp.err);
						}
					}
				});
			
			}
		}
	},	
	entryAdaSimpan: function(){
		var me= this;		
		var catatan = document.getElementById('catatan').value;
		//this.pilihKondisiClose();
		//save ---------------------
		//alert($('#'+this.formName).serialize() +'&'+ $('#SensusTmp_form').serialize());
		var cover = 'coverEntryCatatanSimpan';
		//addCoverPage2(cover,999,true,false);	
		addCoverPage2(cover,1,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize() +'&'+ $('#SensusTmp_form').serialize(),
			url: this.url+'&tipe=simpanEntryAda',
			success: function(data) {		
				var resp = eval('(' + data + ')');			
				delElem(cover);
				if(resp.err==''){					
					me.entryAdaClose();
					me.refreshList(false);	
				}else{
					alert(resp.err);
				}	
				
			}
		});
	},	
	entryAdaClose: function(){
		delElem('coverEntryAda');
	},
	pilihKondisi : function(){		
		var me = this;		
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}		
		var pilihsemua = document.getElementById('cbxsemua').checked;
		if(jmlcek ==0 && pilihsemua==false){
			alert('Data Belum Dipilih!');
		}else{
			var lanjut= true;
			if(pilihsemua){
				lanjut=  confirm('Pilih Kondisi untuk semua data ?') ;
			}else if (jmlcek>1 ){
				lanjut=  confirm('Pilih Kondisi untuk '+jmlcek+' data ?') ;
			}
			if(lanjut){			
				var cover = 'coverPilihKondisi';
				//addCoverPage2(cover,999,true,false);	
				addCoverPage2(cover,1,true,false);
				$.ajax({
					type:'POST', 
					data:$('#'+this.formName).serialize(),
					url: this.url+'&tipe=pilihkondisi',
					success: function(data) {		
						var resp = eval('(' + data + ')');					
						if(resp.err == ''){
							document.getElementById(cover).innerHTML=resp.content ;
						}else{
							alert(resp.err);
						}
					}
				});
			}
		}
	},
	pilihKondisiClose: function(){
		delElem('coverPilihKondisi');
	},
	pilihKondisiSimpan: function(){
		var me= this;		
		var kondisi = document.getElementById('kondisi').value;
		this.pilihKondisiClose();
		//save ---------------------
		var cover = 'coverpilihKondisiSimpan';
		//addCoverPage2(cover,999,true,false);	
		addCoverPage2(cover,1,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=simpankondisi&kondisi='+kondisi,
			success: function(data) {		
				var resp = eval('(' + data + ')');			
				//document.getElementById(cover).innerHTML = resp.content;					
				
				delElem(cover);
				me.refreshList(false);
			}
		});
	},
	pilihPenanggung: function(){
		var me = this;		
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}	
		var pilihsemua = document.getElementById('cbxsemua').checked;			
		if(jmlcek ==0 && pilihsemua==false){
			alert('Data Belum Dipilih!');
		}else{
			var lanjut= true;
			if(pilihsemua){
				lanjut=  confirm('Pilih Kuasa Penanggung Barang untuk semua data ?') ;
			}else if (jmlcek>1 ){
				lanjut=  confirm('Pilih Kuasa Penanggung Barang untuk '+jmlcek+' data ?') ;
			}
			if(lanjut){	
				PegawaiPilih.fmSKPD = document.getElementById('SensusTmpSkpdfmSKPD').value;
				PegawaiPilih.fmUNIT = document.getElementById('SensusTmpSkpdfmUNIT').value;
				PegawaiPilih.fmSUBUNIT = document.getElementById('SensusTmpSkpdfmSUBUNIT').value;
				PegawaiPilih.fmSEKSI = document.getElementById('SensusTmpSkpdfmSEKSI').value;
						
				PegawaiPilih.windowSaveAfter= function(){
					me.simpanPenanggung(this.idpilih);
				}
				PegawaiPilih.windowShow();
			}
		}
	},
	simpanPenanggung: function(idpilih){		
		var me= this;
		//save ---------------------
		var cover = 'coverSimpanPenanggung';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=simpanpenanggung&idpegawai='+idpilih,
			success: function(data) {		
				var resp = eval('(' + data + ')');							
				delElem(cover);
				me.refreshList(false);
			}
		});
	},
	
	pilihPegang2: function(){
		var me = this;		
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		var pilihsemua = document.getElementById('cbxsemua').checked;				
		if(jmlcek ==0 && pilihsemua==false){
			alert('Data Belum Dipilih!');
		}else{
			var lanjut= true;
			if(pilihsemua){
				lanjut=  confirm('Pilih Penanggung Jawab Barang untuk semua data ?') ;
			}else if (jmlcek>1 ){
				lanjut=  confirm('Pilih Penanggung Jawab Barang untuk '+jmlcek+' data ?') ;
			}
			if(lanjut){			
				PegawaiPilih.fmSKPD = document.getElementById('SensusTmpSkpdfmSKPD').value;
				PegawaiPilih.fmUNIT = document.getElementById('SensusTmpSkpdfmUNIT').value;
				PegawaiPilih.fmSUBUNIT = document.getElementById('SensusTmpSkpdfmSUBUNIT').value;
				PegawaiPilih.fmSEKSI = document.getElementById('SensusTmpSkpdfmSEKSI').value;
				//alert(Pegawa)
				PegawaiPilih.windowSaveAfter= function(){					
					me.simpanPemegang2(this.idpilih);
				}
				PegawaiPilih.windowShow();
			}
		}
	},
	simpanPemegang2: function(idpilih){		
		var me= this;
		//save ---------------------
		var cover = 'coverSimpanPemegang2';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=simpanpemegang2&idpegawai='+idpilih,
			success: function(data) {		
				var resp = eval('(' + data + ')');							
				delElem(cover);
				me.refreshList(false);
			}
		});
	},
	
	pilihPegang: function(){
		var me = this;		
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		var pilihsemua = document.getElementById('cbxsemua').checked;				
		if(jmlcek ==0 && pilihsemua==false){
			alert('Data Belum Dipilih!');
		}else{
			var lanjut= true;
			if(pilihsemua){
				lanjut=  confirm('Pilih Pengurus Barang/Pembantu untuk semua data ?') ;
			}else if (jmlcek>1 ){
				lanjut=  confirm('Pilih Pengurus Barang/Pembantu untuk '+jmlcek+' data ?') ;
			}
			if(lanjut){			
				PegawaiPilih.fmSKPD = document.getElementById('SensusTmpSkpdfmSKPD').value;
				PegawaiPilih.fmUNIT = document.getElementById('SensusTmpSkpdfmUNIT').value;
				PegawaiPilih.fmSUBUNIT = document.getElementById('SensusTmpSkpdfmSUBUNIT').value;
				PegawaiPilih.fmSEKSI = document.getElementById('SensusTmpSkpdfmSEKSI').value;
				//alert(Pegawa)
				PegawaiPilih.windowSaveAfter= function(){					
					me.simpanPemegang(this.idpilih);
				}
				PegawaiPilih.windowShow();
			}
		}
	},
	simpanPemegang: function(idpilih){		
		var me= this;
		//save ---------------------
		var cover = 'coverSimpanPemegang';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=simpanpemegang&idpegawai='+idpilih,
			success: function(data) {		
				var resp = eval('(' + data + ')');							
				delElem(cover);
				me.refreshList(false);
			}
		});
	},
	pilihRuang : function(){		
		var me = this;		
		if(document.getElementById(this.prefix+'_jmlcek')){
			var jmlcek = document.getElementById(this.prefix+'_jmlcek').value ;	
		}else{
			var jmlcek = '';
		}
		var pilihsemua = document.getElementById('cbxsemua').checked;	
		if(jmlcek ==0 && pilihsemua==false){
			alert('Data Belum Dipilih!');
		}else{
			var lanjut= true;
			if(pilihsemua){
				lanjut=  confirm('Pilih Ruang untuk semua data ?') ;
			}else if (jmlcek>1 ){
				lanjut=  confirm('Pilih Ruang untuk '+jmlcek+' data ?') ;
			}
			if(lanjut){	
				RuangPilih.fmSKPD = document.getElementById('SensusTmpSkpdfmSKPD').value;
				RuangPilih.fmUNIT = document.getElementById('SensusTmpSkpdfmUNIT').value;
				RuangPilih.fmSUBUNIT = document.getElementById('SensusTmpSkpdfmSUBUNIT').value;
				RuangPilih.fmSEKSI = document.getElementById('SensusTmpSkpdfmSEKSI').value;
				
				RuangPilih.windowSaveAfter= function(){
					me.simpanRuang(this.idpilih);
				}
				RuangPilih.windowShow();
			}
		}
	},	
	simpanRuang: function(idruang){
		//alert('pilih '+idpilih);
		var me= this;
		
		//save ---------------------
		var cover = 'coverSimpanRuang';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=simpanruang&idruang='+idruang,
			success: function(data) {		
				var resp = eval('(' + data + ')');			
				//document.getElementById(cover).innerHTML = resp.content;					
				
				delElem(cover);
				me.refreshList(false);
			}
		});
		
		
	},
entryBarang:function()
	{
		//alert("tes");
		var sw = getBrowserWidth();
		var sh = getBrowserHeight();
		
		var idusul = document.getElementById('Sensus_idplh').value;
		var sesiCari = document.getElementById('sesi').value;
		var c = document.getElementById('SensusTmpSkpdfmSKPD').value;
		var d = document.getElementById('SensusTmpSkpdfmUNIT').value;
		var e = document.getElementById('SensusTmpSkpdfmSUBUNIT').value;
		var e1 = document.getElementById('SensusTmpSkpdfmSEKSI').value;
		
		var cover = this.prefix+'_formcovercari';
		//addCoverPage2(cover,999,true,false);
		addCoverPage2(cover,1,true,false);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=entryBarang&sw='+sw+'&sh='+sh,
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if (resp.err ==''){		
					document.getElementById(cover).innerHTML = resp.content;
					document.getElementById('div_detailcari').innerHTML = 
					"<input type='hidden' id='formcaribi' name='formcaribi' value='0'>"+
					"<input type='hidden' id='fmThnSensus' name='fmThnSensus' value='1'>"+
					"<input type='hidden' id='fmSKPD' name='fmSKPD' value='"+c+"'>"+
					"<input type='hidden' id='fmUNIT' name='fmUNIT' value='"+d+"'>"+
					"<input type='hidden' id='fmSUBUNIT' name='fmSUBUNIT' value='"+e+"'>"+
					"<input type='hidden' id='fmSEKSI' name='fmSEKSI' value='"+e1+"'>"+
					 
					"<input type='hidden' id='idusul' name='idusul' value='"+idusul+"'>"+
					"<input type='hidden' id='sesicari' name='sesicari' value='"+sesiCari+"'>"+					 
					"<input type='hidden' id='boxchecked' name='boxchecked' value='2'>"+
					"<input type='hidden' id='GetSPg' name='GetSPg' value='03'>"+
					"<div id='penatausaha_cont_opt'></div>"+
					"<div id='penatausaha_cont_list'></div>"+
					"<div id='penatausaha_cont_hal'><input type='hidden' value='1' id=HalDefault></div>"+
					"";
					//generate data
					Penatausaha.getDaftarOpsi();
					Penatausaha.refreshList(true);
					document.body.style.overflow='hidden';
					//barcodeCariBarang.loading();
				}else{
					alert(resp.err);
					//delElem(cover);
					document.body.style.overflow='auto';
				}
		  	}
		});
		alert();
		
	},
	
	Closecari:function(){//alert(this.elCover);
		var cover = this.prefix+'_formcovercari';
		if(document.getElementById(cover)) delElem(cover);	
		Penatausaha.resetPilih();		
		//document.body.style.overflow='auto';					
	},
	
	Pilih:function(){ //03 April
		var me = this;
		//document.getElementById(this.Prefix+'_idc').value=a
		
		errmsg = '';			
		
		//if((errmsg=='') && (adminForm.boxchecked.value == 0 )){
		if((errmsg=='') && (Penatausaha.daftarPilih.length == 0 )){
			errmsg= 'Data belum dipilih!';
		}
		
		if(errmsg ==''){	
			//alert('simpan');
			$.ajax({
				type:'POST', 
				data:$('#adminForm').serialize(),
				url: 'pages.php?Pg=usulanhapusdetail'+'&tipe=simpanPilih',
			  	success: function(data) {
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){
						me.Closecari();
						UsulanHapusdetail.refreshList(true);
						//document.body.style.overflow='hidden';
						Penatausaha.resetPilih();
					}else{
						alert(resp.err);
					}
				}
			});
			
		}else{
			alert(errmsg);
		}
		
		
	},		
});
