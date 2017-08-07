var Pembukuan3_2Ajx2Skpd = new SkpdCls({
	prefix : 'Pembukuan3_2Ajx2Skpd', formName:'adminForm'
});


var Pembukuan3_2Ajx2 = new DaftarObj2({
	prefix : 'Pembukuan3_2Ajx2',
	//url : 'pages.php?Pg=Rekap3Ajx', 
	url : 'pages.php?Pg=Rekap3', 
	formName : 'adminForm',// 'ruang_form',
	
	nodeList : null,
	iterator: 0,
	ajx:null,
	jmlgagal : 0,
	
	loading : function(){
		//alert('loading');
		this.topBarRender();
		this.filterRender();
		//this.daftarRender();
		//this.sumHalRender();
	},
	
	refreshList: function(resetPageNo){
		if (resetPageNo && document.getElementById(this.prefix+'_hal') ) document.getElementById(this.prefix+'_hal').value=1;
		this.daftarRender();
		this.filterRender();
		//this.sumHalRender();
		
		
		
	},

	cetakAll: function(){
		var aForm = document.getElementById(this.formName);	
		var str = document.getElementById( this.prefix+'_cont_daftar').innerHTML;
		//var re = new RegExp('"', 'g');
		str = replaceAll(str, '"', '');
		str = replaceAll(str, 'class=koptable', 'class=cetak');
		str = replaceAll(str, '<a ', '<span ');
		str = replaceAll(str, 'GarisDaftar', 'GarisCetak');
		str = str.replaceAll(/[\n\r]/g, '');
		
		//str = str.replace( '"', '' );
		 document.getElementById('daftarcetak').value = str;
		aForm.action=this.url+'&tipe=cetak_all';
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
	},
	
	exportXls : function(){
		var aForm = document.getElementById(this.formName);	
		
		//var tmp = document.getElementById('div_cek').innerHTML;
		//document.getElementById('div_cek').innerHTML = ''; 
		var str = document.getElementById( this.prefix+'_cont_daftar').innerHTML;
		//var re = new RegExp('"', 'g');
		str = replaceAll(str, '"', '');
		str = replaceAll(str, 'class=koptable', 'class=cetak');
		str = replaceAll(str, '<a ', '<span ');
		str = replaceAll(str, 'GarisDaftar', 'GarisCetak');
		str = str.replace(/[\n\r]/g, '');
		
		document.getElementById('daftarcetak').value = str;
		
		aForm.action=this.url+'&tipe=exportXls';
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		//document.getElementById('div_cek').innerHTML = tmp; 
		
	},
	
	reqTotalbyIter:function(iterator_){
		var me=this;
		//me.iterator++;
		if(me.iterator < me.nodeList.length ){		
			//alert('Rekap '+me.iterator+' - '+me.nodeList.length);
				//me.nodeList= document.getElementsByName('vrekap');
			var node = me.nodeList[me.iterator];
			var href ='';
			if (node.innerHTML==''){
				var node = me.nodeList[me.iterator];
				//if(node == null) console.info(me.iterator);
				var href = node.getAttribute('href');
				var idel = node.getAttribute('id');					
			}
			me.reqTotal(href,idel,me.iterator);	
		}else{
			var btTampil = document.getElementById('btTampil');
			if(btTampil) btTampil.disabled=false; 
			if(me.jmlgagal==0){
				alert('Rekap selesai ');//+me.iterator+' - '+me.nodeList.length);
				
			}else{
				var jmlgagal= me.jmlgagal;
				me.jmlgagal=0;
				if(confirm('Ada '+jmlgagal+' data gagal dihitung! Coba lagi? ')){
					me.iterator=0; 
					me.reqTotalbyIter(me.iterator);
					/*var node = me.nodeList[me.iterator];
					var href ='';						
					if (node.innerHTML ==''){
						var href = node.getAttribute('href');
						var idel = node.getAttribute('id');
					}
					me.reqTotal(href,idel);
					*/
				}				
			}
		}
	},
	
	reqTotal : function(href_, idel_,iterator_){
		var me = this;
		//var href = node.getAttribute('href');
		//var idel = node.getAttribute('id');
		/*if(iterator_==1){
			me.jmlgagal++;
			href_='';
		}*/
		if(href_!=''){
			//alert('Rekap '+me.iterator+' - '+me.nodeList.length+' - '+href_);
			this.ajx = $.ajax({
			  	url: href_+'&tipe=total&idel='+idel_+'&iter='+iterator_,
			 	type:'POST', 
				data:$('#'+this.formName).serialize(), 
			  	success: function(data) {		
					var resp = eval('(' + data + ')');				
					document.getElementById(resp.content.idel).innerHTML = resp.content.vtotal;
					
					//do{
						
					
					me.iterator++;
					me.reqTotalbyIter(me.iterator);
					/*if(me.iterator < me.nodeList.length ){							
					
						//me.nodeList= document.getElementsByName('vrekap');
						var node = me.nodeList[me.iterator];
						var href ='';
						if (node.innerHTML==''){
							//if(node == null) console.info(me.iterator);
							var href = node.getAttribute('href');
							var idel = node.getAttribute('id');	
						}
						me.reqTotal(href,idel);
					}else{
						alert('Rekap selesai');
					}
					//} while (href != null);
					
					*/
			  	},
				error: function(request, type, errorThrown){
					if(type != 'abort'){
						me.jmlgagal ++;	
						me.iterator++;
						me.reqTotalbyIter(me.iterator);
					}
					
				}
				
			});	
		}else{
			me.iterator++;
			me.reqTotalbyIter(me.iterator);
			/*if(me.iterator < me.nodeList.length ){		
					//me.nodeList= document.getElementsByName('vrekap');
				var node = me.nodeList[me.iterator];
				var href ='';
				if (node.innerHTML==''){
					var node = me.nodeList[me.iterator];
					//if(node == null) console.info(me.iterator);
					var href = node.getAttribute('href');
					var idel = node.getAttribute('id');					
				}
				me.reqTotal(href,idel);	
			}else{
				if(me.jmlgagal==0){
					alert('Rekap selesai');
				}else{
					var jmlgagal= me.jmlgagal;
					me.jmlgagal=0;
					if(confirm('Ada '+jmlgagal+' data gagal dihitung! Coba lagi? ')){
						me.iterator=0; 
						var node = me.nodeList[me.iterator];
						var href ='';						
						if (node.innerHTML ==''){
							var href = node.getAttribute('href');
							var idel = node.getAttribute('id');
						}
						me.reqTotal(href,idel);
					}
					
				}
			}*/
		}
	},
	
	daftarRenderAfter : function(){
		var me= this;
		//alert('tes');
		
		var btTampil = document.getElementById('btTampil');
		if(btTampil) btTampil.disabled=true; 
		
		if(this.ajx) this.ajx.abort();
		this.nodeList= document.getElementsByName('vrekap');
		this.jmlgagal = 0;
		this.iterator=0;
		me.reqTotalbyIter(me.iterator);
		//node = nodeList[1];
		/*var node = this.nodeList[this.iterator];
		//while (node = this.nodeList[this.iterator++] ) {
		var href ='';
		if (node.innerHTML ==''){
			var href = node.getAttribute('href');
			var idel = node.getAttribute('id');
		}
		this.reqTotal(href,idel);
		*/
			//if(href!=''){
			/*$.ajax({
			  	url: href+'&tipe=total&idel='+idel,
			 	type:'POST', 
				data:$('#'+this.formName).serialize(), 
			  	success: function(data) {		
					var resp = eval('(' + data + ')');
					//alert(idel+' '+resp.content.vtotal);
					document.getElementById(resp.content.idel).innerHTML = resp.content.vtotal;
					
					
					
			  	}
			});	
			*/	
			
			
		//}
	},
	
	
	daftarRender : function(){
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
				if(me.withPilih) me.cbTampil();				
				me.sumHalRender();
				me.daftarRenderAfter();
		  	}
		});
	}
	
	
});
