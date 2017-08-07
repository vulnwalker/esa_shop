var Pembukuan2AjxSkpd = new SkpdCls({
	prefix : 'Pembukuan2AjxSkpd', formName:'adminForm'
});


var Pembukuan2Ajx = new DaftarObj2({
	prefix : 'Pembukuan2Ajx',
	//url : 'pages.php?Pg=Rekap2Ajx', 
	url : 'pages.php?Pg=Rekap2', 
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
	
	refreshList: function(resetPageNo, versi=''){
		if(versi == 'KOTA_BANDUNG'){
			if(document.getElementById('fmFiltThnBuku').value < '2015'){
				alert("Tidak ada data !");
			}else{
				if (resetPageNo && document.getElementById(this.prefix+'_hal') ) document.getElementById(this.prefix+'_hal').value=1;
				this.daftarRender();
				this.filterRender();
			}
		}else{
			if (resetPageNo && document.getElementById(this.prefix+'_hal') ) document.getElementById(this.prefix+'_hal').value=1;
			this.daftarRender();
			this.filterRender();
		}
		
		
	},
	
	exportXls : function(){
		var aForm = document.getElementById(this.formName);	
		
		var tmp = document.getElementById('div_cek').innerHTML;
		document.getElementById('div_cek').innerHTML = ''; 
		var str = document.getElementById('Pembukuan2Ajx_cont_daftar').innerHTML;
		//var re = new RegExp('"', 'g');
		str = replaceAll(str, '"', '');
		str = replaceAll(str, 'class=koptable', 'class=cetak');
		str = replaceAll(str, '<a ', '<span ');
		str = replaceAll(str, 'GarisDaftar', 'GarisCetak');
		str = str.replace(/[\n\r]/g, '');
		
		document.getElementById('daftarcetak').value = str;
		
		aForm.action=this.url+'&tipe=exportXls';
		//aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		document.getElementById('div_cek').innerHTML = tmp; 
		
	},
	
	cetakAll: function(){
		var aForm = document.getElementById(this.formName);
		
		var tmp = document.getElementById('div_cek').innerHTML;
		document.getElementById('div_cek').innerHTML = ''; 			
		var str = document.getElementById('Pembukuan2Ajx_cont_daftar').innerHTML;
		//var re = new RegExp('"', 'g');
		str = replaceAll(str, '"', '');
		str = replaceAll(str, 'class=koptable', 'class=cetak');
		str = replaceAll(str, '<a ', '<span ');
		str = replaceAll(str, 'GarisDaftar', 'GarisCetak');
		
		//str = str.replace( '"', '' );
		 document.getElementById('daftarcetak').value = str;
		aForm.action=this.url+'&tipe=cetak_all';
		aForm.target='_blank';
		aForm.submit();	
		aForm.target='';
		document.getElementById('div_cek').innerHTML = tmp;
	},
	
	reqTotalbyIter:function(iterator_){
		var me=this;
		//me.iterator++;
		if(me.iterator < me.nodeList.length ){						
			var node = me.nodeList[me.iterator];
			var href ='';
			if (node.innerHTML==''){
				var node = me.nodeList[me.iterator];				
				var href = node.getAttribute('href');
				var idel = node.getAttribute('id');					
			}
			me.reqTotal(href,idel,me.iterator);	
		}else{ //selesai
			var btTampil = document.getElementById('btTampil');
			if(btTampil) btTampil.disabled=false; 
			
			if(me.jmlgagal==0){
				alert('Rekap selesai');
			}else{
				var jmlgagal= me.jmlgagal;
				me.jmlgagal=0;
				if(confirm('Ada '+jmlgagal+' data gagal dihitung! Coba lagi? ')){
					me.iterator=0; 
					me.reqTotalbyIter(me.iterator);					
				}
				
			}
		}
	},
	
	reqTotal : function(href_, idel_,iterator_){
		var me = this;		
		if(href_!=''){
			document.getElementById(idel_).innerHTML = "<img src='images/wait.gif' height='10px' >";
			this.ajx = $.ajax({
			  	url: href_+'&tipe=rekapNeraca&idel='+idel_+'&iter='+iterator_,
			 	type:'POST', 
				data:$('#'+this.formName).serialize(), 
			  	success: function(data) {		
					//var resp = eval('(' + data + ')');				
					var resp = JSON.parse(data);
					document.getElementById(resp.content.idel).innerHTML = resp.content.vtotal;
					var idel = resp.content.idel;
					var arrid = idel.split('_');
					
					/*for(i=0;i<21<i++){
						document.getElementById(arrid[0]+'_'+arrid[1]+'_'+i).innerHTML = 
							resp.content.vtotalN[arrid[1]-1][i];	
					}*/
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).innerHTML = resp.content.vtotal2 ;//+"<input type='hidden' value='"+resp.content.vtotal1+"'>";  //saldo aw	
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).setAttribute('title',resp.content.total1 );
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+2).innerHTML = resp.content.vtotal3 ; //susut awa
					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3).innerHTML = resp.content.vtotalN[0][0];//+"<input type='hidden' value='"+resp.content.vtotalN[0][2]+			"'>"; //bm					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3).setAttribute('title',resp.content.totalN[0][2] );
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+4).innerHTML = resp.content.vtotalN[1][0]; //atribusi
					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5).innerHTML = resp.content.vtotalN[2][0];//+"<input type='hidden' value='"+resp.content.vtotalN[2][2]+"'>"; //kapitalisasi
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5).setAttribute('title',resp.content.totalN[2][2] );
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6).innerHTML = resp.content.vtotalN[2][1];//+"<input type='hidden' value='"+resp.content.vtotalN[2][3]+"'>"; 
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6).setAttribute('title',resp.content.totalN[2][3] );
					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7).innerHTML = resp.content.vtotalN[3][0];//+"<input type='hidden' value='"+resp.content.vtotalN[3][2]+"'>"; //hibah
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7).setAttribute('title',resp.content.totalN[3][2] );
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8).innerHTML = resp.content.vtotalN[3][1];//+"<input type='hidden' value='"+resp.content.vtotalN[3][3]+"'>";
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8).setAttribute('title',resp.content.totalN[3][3] );
					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9).innerHTML = resp.content.vtotalN[4][0];//+"<input type='hidden' value='"+resp.content.vtotalN[4][2]+"'>"; //pindah skpd
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9).setAttribute('title',resp.content.totalN[4][2] );
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10).innerHTML = resp.content.vtotalN[4][1];//+"<input type='hidden' value='"+resp.content.vtotalN[4][3]+"'>";
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10).setAttribute('title',resp.content.totalN[4][3] );
					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11).innerHTML = resp.content.vtotalN[5][0];//+"<input type='hidden' value='"+resp.content.vtotalN[5][2]+"'>"; //penilaian
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11).setAttribute('title',resp.content.totalN[5][2] );
					//document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12).innerHTML = resp.content.vtotalN[5][1];
						
					//document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13).innerHTML = resp.content.vtotalN[6][0]; //penghapsuan
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12).innerHTML = resp.content.vtotalN[6][1];//+"<input type='hidden' value='"+resp.content.vtotalN[6][3]+"'>";
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12).setAttribute('title',resp.content.totalN[6][3] );
					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13).innerHTML = resp.content.vtotalN[7][0];//+"<input type='hidden' value='"+resp.content.vtotalN[7][2]+"'>"; //koreksi
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13).setAttribute('title',resp.content.totalN[7][2] );
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14).innerHTML = resp.content.vtotalN[7][1];//+"<input type='hidden' value='"+resp.content.vtotalN[7][3]+"'>";
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14).setAttribute('title',resp.content.totalN[7][3] );
					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+15).innerHTML = resp.content.vtotalN[8][0];//+"<input type='hidden' value='"+resp.content.vtotalN[8][2]+"'>"; //reklas
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+15).setAttribute('title',resp.content.totalN[8][2] );
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+16).innerHTML = resp.content.vtotalN[8][1];//+"<input type='hidden' value='"+resp.content.vtotalN[8][3]+"'>";
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+16).setAttribute('title',resp.content.totalN[8][3] );
					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+17).innerHTML = resp.content.vtotalN[9][0];//+"<input type='hidden' value='"+resp.content.vtotalN[9][2]+"'>"; //penyusutan
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+17).setAttribute('title',resp.content.totalN[9][2] );
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+18).innerHTML = resp.content.vtotalN[9][1];//+"<input type='hidden' value='"+resp.content.vtotalN[9][3]+"'>";
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+18).setAttribute('title',resp.content.totalN[9][3] );
					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+19).innerHTML = resp.content.vSaldoAk;//+"<input type='hidden' value='"+resp.content.vSaldoAkBrg+"'>"; //saldo akhit					
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+19).setAttribute('title', resp.content.saldoAkBrg );
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+20).innerHTML = resp.content.vSusutAk; //susut ak
					document.getElementById(arrid[0]+'_'+arrid[1]+'_'+21).innerHTML = resp.content.vNilaibukuAk; //nilai buku ak
					
					
					me.iterator+=21;
					me.reqTotalbyIter(me.iterator);
					
			  	},
				error: function(request, type, errorThrown){
					if(type != 'abort'){
						me.jmlgagal ++;	
						me.iterator+=21;
						document.getElementById(idel_).innerHTML = '';
						me.reqTotalbyIter(me.iterator);
					}
					
				}
				
			});	
		}else{
			me.iterator+=21;
			me.reqTotalbyIter(me.iterator);
			
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
				//me.sumHalRender();
				me.daftarRenderAfter();
		  	}
		});
	}
	
	
});
