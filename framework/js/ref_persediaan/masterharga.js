var MasterHarga = new DaftarObj2({
	prefix : 'MasterHarga',
	url : 'pages.php?Pg=masterharga', 
	formName : 'MasterHargaForm',
	
	loading:function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		this.sumHalRender();
	},
		
	daftarRender:function(){
		var me =this; //render daftar 
		addCoverPage2(
			'daftar_cover',	1, 	true, true,	{renderTo: this.prefix+'_cont_daftar',
			imgsrc: 'images/wait.gif',
			style: {position:'absolute', top:'5', left:'5'}
			}
		);
		$.ajax({
		  	url: this.url+'&tipe=daftar',
		 	type:'POST', 
			data:$('#'+this.formName).serialize(), 
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				document.getElementById(me.prefix+'_cont_daftar').innerHTML = resp.content;
				me.sumHalRender();
		  	}
		});
	},
	
	Golongan: function(){	
		
		var me = this;
		var err='';
		var golongan = document.getElementById('golongan').value; 
			
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:"golongan="+golongan,
			  	url: this.url+'&tipe=golongan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById('div_sub_golongan').innerHTML = resp.content;
				}
			});	
	},
	
	SubGolongan: function(){	
		
		var me = this;
		var err='';
		var sub_golongan = document.getElementById('sub_golongan').value; 
		var golongan = document.getElementById('golongan').value; 			
					
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			//addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:"sub_golongan="+sub_golongan+"&golongan="+golongan,
			  	url: this.url+'&tipe=sub_golongan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById('nama_barang').value = resp.content;
					}
			});		
	},
	
	InputForm: function(no,kode,nama){	
		
		var me = this;
		var err='';
		if(no==1){
			document.getElementById('autocomplete').value = nama;	
			document.getElementById('merk').value = kode;				
		}else if(no==2){
			document.getElementById('autocomplete2').value = nama;	
			document.getElementById('type').value = kode;				
		}else if(no==3){
			document.getElementById('autocomplete3').value = nama;	
			document.getElementById('spec').value = kode;							
		}else{
			document.getElementById('autocomplete4').value = nama;	
			document.getElementById('satuan').value = kode;							

		}
	},		
	
	autocomplete_initial:function (){
	 				var me=this;
						$(function() {							
							$( '#autocomplete' ).autocomplete({
						      source: function( request, response ) {
						        $.ajax({
								  url: 'pages.php?Pg=masterharga&tipe=autocomplete_getdata',
						          dataType: 'json',
						          data: {
						            //featureClass: 'P',
						            style: 'full',
						            maxRows: 12,
									name_startsWith: request.term	
							
						          },
								  success: function( data ) {						         
									  response( $.map( data, function( item ) {
										    return {
												id: item.id,
										        label: item.label,
										        value: item.value
												
										    }
													document.getElementById('BaruMerk').disabled=false;

										}));
									}
						        });
						      },
						      minLength: 1,
						      select: function( event, ui ) {
						        console.log( ui.item ?
						          'Selected: id=' + ui.item.id+' label=' + ui.item.label  :
						          'Nothing selected, input was ' + this.value);
								 document.getElementById('merk').value=ui.item.id;
								 document.getElementById('autocomplete').value=ui.item.id;								 
								 //me.tarif();
								 //alert()
						      },
						      open: function() {
						        $( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
						      },
						      close: function() {
						        $( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
								//me.tarif();
						      }
						    });
						});
	},
	
	autocomplete_initial2:function (){
	 				var me=this;
						$(function() {							
							$( '#autocomplete2' ).autocomplete({
						      source: function( request, response ) {
						        $.ajax({
								  url: 'pages.php?Pg=masterharga&tipe=autocomplete_getdatatype',
						          dataType: 'json',
						          data: {
						            //featureClass: 'P',
						            style: 'full',
						            maxRows: 12,
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
						          'Selected: id=' + ui.item.id+' label=' + ui.item.label  :
						          'Nothing selected, input was ' + this.value);
								 document.getElementById('type').value=ui.item.id;
								 //alert()
						      },
						      open: function() {
						        $( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
						      },
						      close: function() {
						        $( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
						      }
						    });
							var position = $( '#autocomplete2' ).autocomplete( "option", "position" );
							//$( '#autocomplete2' ).autocomplete( "option", "position", {  my: "left bottom", at: "left top"} );
						    	});
	},
	
	autocomplete_initial3:function (){
	 				var me=this;
						$(function() {							
							$( '#autocomplete3' ).autocomplete({
						      source: function( request, response ) {
						        $.ajax({
								  url: 'pages.php?Pg=masterharga&tipe=autocomplete_getdataspec',
						          dataType: 'json',
						          data: {
						            //featureClass: 'P',
						            style: 'full',
						            maxRows: 12,
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
						          'Selected: id=' + ui.item.id+' label=' + ui.item.label  :
						          'Nothing selected, input was ' + this.value);
								 document.getElementById('spec').value=ui.item.id;
								 //alert()
						      },
						      open: function() {
						        $( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
						      },
						      close: function() {
						        $( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
						      }
						    });
							var position = $( '#autocomplete3' ).autocomplete( "option", "position" );
							//$( '#autocomplete3' ).autocomplete( "option", "position", {  my: "left bottom", at: "left top"} );
						    	});
	},

	autocomplete_initial4:function (){
	 				var me=this;
						$(function() {							
							$( '#autocomplete4' ).autocomplete({
						      source: function( request, response ) {
						        $.ajax({
								  url: 'pages.php?Pg=masterharga&tipe=autocomplete_getdatasatuan',
						          dataType: 'json',
						          data: {
						            //featureClass: 'P',
						            style: 'full',
						            maxRows: 12,
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
						          'Selected: id=' + ui.item.id+' label=' + ui.item.label  :
						          'Nothing selected, input was ' + this.value);
								 document.getElementById('satuan').value=ui.item.id;
								 //alert()
						      },
						      open: function() {
						        $( this ).removeClass( 'ui-corner-all' ).addClass( 'ui-corner-top' );
						      },
						      close: function() {
						        $( this ).removeClass( 'ui-corner-top' ).addClass( 'ui-corner-all' );
						      }
						    });
							var position = $( '#autocomplete4' ).autocomplete( "option", "position" );
							//$( '#autocomplete4' ).autocomplete( "option", "position", {  my: "left bottom", at: "left top"} );
						    	});
	},			
		
	formatRupiah:function(objek, separator) {
	  a = objek.value;
	  b = a.replace(/[^\d]/g,"");
	  c = "";
	  panjang = b.length;
  	  j = 0;
	  for (i = panjang; i > 0; i--) {
	    j = j + 1;
	    if (((j % 3) == 1) && (j != 1)) {
	      c = b.substr(i-1,1) + separator + c;
	    } else {
	      c = b.substr(i-1,1) + c;
	    }
	  }
	  objek.value = c;
   },	
	
	toRp: function(angka){
	    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
	    var rev2    = '';
	    for(var i = 0; i < rev.length; i++){
	        rev2  += rev[i];
	        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
	            rev2 += '.';
	        }
	    }
	    return rev2.split('').reverse().join('');
	},	
	
	Baru:function(){
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
					me.autocomplete_initial();	
					me.autocomplete_initial2();	
					me.autocomplete_initial3();
					me.autocomplete_initial4();											
				}else{
					alert(resp.err);
					delElem(cover);
					document.body.style.overflow='auto';
				}			
				
		  	}
		});
	},
	
	CopyData:function(){
		var me = this;
		var cover = this.prefix+'_formcover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
		  	url: this.url+'&tipe=formCopyData',
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
	},	
		
	Edit: function(){
		var me = this;
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
				url: this.url+'&tipe=formEdit',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						//me.Golongan();
						//me.SubGolongan(resp.content.f,resp.content.g);
						me.autocomplete_initial();	
						me.autocomplete_initial2();	
						me.autocomplete_initial3();
						me.autocomplete_initial4();																	
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
		
	
	AfterSimpan : function(){
		if(document.getElementById('Kunjungan_cont_daftar')){
		this.refreshList(true);}
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
});