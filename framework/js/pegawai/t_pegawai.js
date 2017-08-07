var t_pegawaiSkpd = new SkpdCls({
	prefix : 't_pegawaiSkpd', formName:'t_pegawaiForm',
	pilihBidangAfter : function(){t_pegawai.refreshList(true);},
	pilihUnitAfter : function(){t_pegawai.refreshList(true);},
	pilihSubUnitAfter : function(){t_pegawai.refreshList(true);},
	pilihSeksiAfter : function(){t_pegawai.refreshList(true);}	
});

var t_pegawai = new DaftarObj2({
	prefix : 't_pegawai',
	url : 'pages.php?Pg=t_pegawai', 
	formName : 't_pegawaiForm',
		
	pilihPangkat : function(jns){
	var me = this; 
		$.ajax({
		  url: this.url+'&tipe=pilihPangkat&jns='+jns,
		  type : 'POST',
		  data:$('#'+this.prefix+'_form').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			if(jns==1){
				document.getElementById('golang_cpns').value = resp.content;
			}else{
				document.getElementById('golang_akhir').value = resp.content;				
			}			
		  }
		});
	},
	pilihSTkawin : function(){
	var me = this; 	
	var st_kawin = document.getElementById('st_kawin').value;
		if(st_kawin==1){//blm kawin
			document.getElementById('dt_keluarga').disabled=true;
		}else{
			document.getElementById('dt_keluarga').disabled=false;	
		}
	},
	autocomplete_initial:function (){
 	var me = this; 			
		$(function() {							
			$( '#jabatanakhir' ).autocomplete({
		      source: function( request, response ) {
		        $.ajax({
				  url: 'pages.php?Pg=t_pegawai&tipe=autocomplete_getdata',
		          dataType: 'json',
		          data: {
		            //featureClass: 'P',
		            style: 'full',
		            maxRows: 20,
					name_startsWith: request.term
			
		          },
				  success: function( data ) {						         
					  response( $.map( data, function( item ) {
						    return {
								id: item.id,
						        label: item.label,
						        value: item.value
						    }
						}));
					}
		        });
		      },
		      minLength: 1,
		      select: function( event, ui ) {
		        console.log( ui.item ?
		          'Selected: id=' + ui.item.id+' id2=' + ui.item.id2+' label=' + ui.item.label  :
		          'Nothing selected, input was ' + this.value);
				  //document.getElementById('id_penerima').value=ui.item.id;
				 //alert()
		      },
		      open: function() {
		        $( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
		      },
		      close: function() {
		        $( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
		      }
		    });
		});
	},
	TampilDTKeluarga: function(){
		var me = this;	
		t_keluarga.windowSaveAfter= function(){};
		t_keluarga.windowShow();	
	},
	Baru: function(){	
		var me = this;
		var err='';
		var c1 = document.getElementById(this.prefix+'SkpdfmUrusan').value; 
		var c = document.getElementById('t_pegawaiSkpdfmSKPD').value; 
		var d = document.getElementById('t_pegawaiSkpdfmUNIT').value;
		var e = document.getElementById('t_pegawaiSkpdfmSUBUNIT').value;
		var e1 = document.getElementById('t_pegawaiSkpdfmSEKSI').value;
		if(err=='' && (c1=='' || c1=='00') ) err='URUSAN belum dipilih !';
		if(err=='' && (c=='' || c=='00') ) err='BIDANG belum dipilih !';
		if(err=='' && (d=='' || d=='00') ) err='SKPD belum dipilih !';
		if(err=='' && (e=='' || e=='00') ) err='UNIT belum dipilih !';
		if(err=='' && (e1=='' || e1=='000') ) err='SUB UNIT belum dipilih !';
		
		var cover = this.prefix+'_formcover';
		if(err==''){
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
						me.autocomplete_initial();
						me.AfterFormBaru();
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
	AfterFormBaru:function(){ 			
		$('.datepicker').datepicker({
						    dateFormat: 'dd-mm-yy',
							showAnim: 'slideDown',
						    inline: true,
							showOn: "button",
     						buttonImage: "images/calendar.gif",
      						buttonImageOnly: true,
							changeMonth: true,
      						changeYear: true,
							yearRange: "-100:+0",
							buttonText : '',
		});							
	},
	Edit:function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			var cover = this.prefix+'_formcover';
			addCoverPage2(cover,999,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formEdit',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						document.getElementById('kode1').focus();	
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
	Simpan: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		/*this.sendReq(
			this.url,
			{ idprs: 0, daftarProses: new Array('simpan')},
			this.formDialog);*/
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){
					if(confirm('Input data pegawai lagi ?')){
						me.Close();
						me.Baru();
					}else{
						me.Close();
					}
					}else{
					alert(resp.err);
				}
		  	}
		});
	},
	Selesai: function(){
		var me= this;	
		this.OnErrorClose = false	
		document.body.style.overflow='hidden';
		var cover = this.prefix+'_formsimpan';
		addCoverPage2(cover,1,true,false);	
		/*this.sendReq(
			this.url,
			{ idprs: 0, daftarProses: new Array('simpan')},
			this.formDialog);*/
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
			url: this.url+'&tipe=simpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				delElem(cover);		
				//document.getElementById(cover).innerHTML = resp.content;
				if(resp.err==''){					
						me.Close();
					}else{
					alert(resp.err);
				}
		  	}
		});
	}
		
});
