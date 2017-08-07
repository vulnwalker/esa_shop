var detailVolumeRka221 = new DaftarObj2({
	prefix : 'detailVolumeRka221',
	url : 'pages.php?Pg=detailVolumeRka221', 
	formName : 'detailVolumeRka221Form',

	
	loading: function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		this.daftarRender();
		//this.sumHalRender();
	
	},
	
	detail: function(){
		//alert('detail');
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();			
			//UserAktivitasDet.genDetail();			
			
		}else{
		
			alert(errmsg);
		}
		
	},
	setCookiesUnit: function(){
		var me = this;
		$.ajax({
		  url: this.url+'&tipe=setCookiesUnit',
		  type : 'POST',
		  data : {
		  			cmbUrusanForm : document.getElementById('cmbUrusanForm').value,
		  			cmbBidangForm : document.getElementById('cmbBidangForm').value,
		  			cmbSKPDForm : document.getElementById('cmbSKPDForm').value,
		   			cmbUnitForm : document.getElementById('cmbUnitForm').value
		   },
		  success: function(data) {
		  
				detailVolumeRka221.refreshList(true);
		  }
		});
		
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
			//	me.sumHalRender();
		  	}
		});
	},
	 
		windowShow: function(id){
		var me = this;
		
		var cover = this.prefix+'_cover';
		
		
		document.body.style.overflow='hidden';
		addCoverPage2(cover,10,true,false);	
		$.ajax({
			type:'POST', 
			data:{id : id},
			url: this.url+'&tipe=windowshow',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');	
				if(resp.err==''){
					document.getElementById(cover).innerHTML = resp.content;				
					me.loading();
		
					
				}else{
					alert(resp.err);
					delElem(cover);
					document.body.style.overflow='auto';
				}
		  	}
		});
	},
	
	windowClose: function(){
		template.refreshList(true);
		document.body.style.overflow='auto';
		delElem(this.prefix+'_cover');
		
	},
	
	windowSave: function(){
		var me= this;

			
			$.ajax({
			type : 'POST',
				url: 'pages.php?Pg=detailVolumeRka221&tipe=setValueTemplate',
				data:{id : document.getElementById('idTemplate').value,
					  c  : document.getElementById('c').value,
					  d  : document.getElementById('d').value,
					  e  : document.getElementById('cmbUnit').value,
					  currentPosition : document.getElementById("detailVolumeRka221_hal_fmHAL").value
					 },
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					var limit = resp.cek;				
					var arrayPostIdAndValue = [];
					var jsonID = JSON.parse(resp.content);					
					for(var i = 0; i <= limit  ; i++) {

					   var IDnya = jsonID[i]['id'];
					   var jumlah = document.getElementById(IDnya).value;
					   arrayPostIdAndValue[i] = { "id": IDnya, "value" : jumlah };				  

					}
					
	
	

					
			  	}
			});		
		
	},	
	setValCurrentTextbox : function(obj){
		var idnya = obj.id;
		var valuenya = obj.value;
		
		
		
				$.ajax({
				  url: this.url+'&tipe=updateTempDetail',
				  type : 'POST',
				  data :{ ID : idnya, VALUE : valuenya },
				  success: function(data) {
							var resp = eval('(' + data + ')');
							document.getElementById('tempatJumlah').textContent = resp.content.total;
					  }
				});


}	
});
