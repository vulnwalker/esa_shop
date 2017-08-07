var refstdbutuh_v3Skpd = new SkpdCls({
	prefix : 'refstdbutuh_v3Skpd', 
	formName: 'refstdbutuh_v3Form',
	pilihBidangAfter : function(){refstdbutuh_v3.refreshList(true);},
	pilihUnitAfter : function(){refstdbutuh_v3.refreshList(true);},
	pilihSubUnitAfter : function(){refstdbutuh_v3.refreshList(true);},
	pilihSeksiAfter : function(){refstdbutuh_v3.refreshList(true);}
});

var refstdbutuh_v3 = new DaftarObj2({
	prefix : 'refstdbutuh_v3',
	url : 'pages.php?Pg=ref_std_butuh_v3', 
	formName : 'refstdbutuh_v3Form',
	
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
	
	Baru:function(){
		var me = this;
		var err ='';
		if (err =='' ){				
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,2,true,false);	
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
		}else{
		 	alert(err);
		}	
	},
	BidangAfterform: function(){
		var me = this;
		
		//this.formName = 'adminForm';
		$.ajax({
		  url: this.url+'&tipe=BidangAfterForm',
		  type : 'POST',
		  data:{fmSKPDBidang: $("#cmbBidangForm").val(),
		  		fmSKPDUrusan: $("#cmbUrusanForm").val(),
				fmSKPDskpd: $("#cmbSKPDForm").val(), 
				fmSKPDUnit: $("#cmbUnitForm").val(), 
				fmSKPDSubUnit: $("#cmbSubUnitForm").val(), 
				},
		  success: function(data) {
			var resp = eval('(' + data + ')');	//console.info(me);	//console.info('id='+me.prefix+'CbxBagian');
				document.getElementById('cmbBidangForm').innerHTML=resp.content.bidang;
				document.getElementById('cmbSKPDForm').innerHTML=resp.content.skpd;
				document.getElementById('cmbUnitForm').innerHTML=resp.content.unit;
				document.getElementById('cmbSubUnitForm').innerHTML=resp.content.subUnit;
				

				
				
		  }
		});

	},
	Cari: function(){
		popupBarang_v3.kodeBarang = 'kodeBarang';
		popupBarang_v3.namaBarang = 'namaBarang'; 
		popupBarang_v3.satuanBarang = 'satuan'; 
		popupBarang_v3.windowShow();
		
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
	}
});
