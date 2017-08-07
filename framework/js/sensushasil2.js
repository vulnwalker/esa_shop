var SensusHasil2Skpd = new SkpdCls({
	prefix : 'SensusHasil2Skpd', formName:'adminForm'
});


var SensusHasil2 = new DaftarObj2({
	prefix : 'SensusHasil2',
	//url : 'pages.php?Pg=Rekap2Ajx', 
	url : 'pages.php?Pg=SensusHasil2', 
	formName : 'adminForm',// 'ruang_form',
	
	nodeList : null,
	iterator: 0,
	ajx:null,
	jmlgagal : 0,
	jmlkol: 12,
	
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
	},
	
	
	
	cetakAll: function(){
		var aForm = document.getElementById(this.formName);	
		var str = document.getElementById( this.prefix+'_cont_daftar').innerHTML;
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
	},
	
	reqTotalbyIter:function(iterator_){
		var me=this;
		//me.iterator++;
		if(me.iterator < me.nodeList.length ){						
			var node = me.nodeList[me.iterator];
			var href_ ='';
			if (node.innerHTML==''){
				var node = me.nodeList[me.iterator];				
				var href_ = node.getAttribute('href_');
				var idel = node.getAttribute('id');					
			}
			me.reqTotal(href_,idel,me.iterator);	
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
					var resp = eval('(' + data + ')');				
					document.getElementById(resp.content.idel).innerHTML = resp.content.vtotal;
					var idel = resp.content.idel;
					var arrid = idel.split('_');
					
					
					/**
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).innerHTML = resp.content.vtotal2 ;//+"<input type='hidden' value='"+resp.content.vtotal1+"'>";  //saldo aw	
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).setAttribute('title',resp.content.total1 );
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+2)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+2).innerHTML = resp.content.vtotal3 ; //susut awa
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3).innerHTML = resp.content.vtotalN[0][0];//+"<input type='hidden' value='"+resp.content.vtotalN[0][2]+			"'>"; //bm					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3).setAttribute('title',resp.content.totalN[0][2] );
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+4)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+4).innerHTML = resp.content.vtotalN[1][0]; //atribusi
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5).innerHTML = resp.content.vtotalN[2][0];//+"<input type='hidden' value='"+resp.content.vtotalN[2][2]+"'>"; //kapitalisasi
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5).setAttribute('title',resp.content.totalN[2][2] );
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6).innerHTML = resp.content.vtotalN[2][1];//+"<input type='hidden' value='"+resp.content.vtotalN[2][3]+"'>"; 
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6).setAttribute('title',resp.content.totalN[2][3] );
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7).innerHTML = resp.content.vtotalN[3][0];//+"<input type='hidden' value='"+resp.content.vtotalN[3][2]+"'>"; //hibah
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7).setAttribute('title',resp.content.totalN[3][2] );
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8).innerHTML = resp.content.vtotalN[3][1];//+"<input type='hidden' value='"+resp.content.vtotalN[3][3]+"'>";
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8).setAttribute('title',resp.content.totalN[3][3] );
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9).innerHTML = resp.content.vtotalN[4][0];//+"<input type='hidden' value='"+resp.content.vtotalN[4][2]+"'>"; //pindah skpd
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9).setAttribute('title',resp.content.totalN[4][2] );
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10).innerHTML = resp.content.vtotalN[4][1];//+"<input type='hidden' value='"+resp.content.vtotalN[4][3]+"'>";
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10).setAttribute('title',resp.content.totalN[4][3] );
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11).innerHTML = resp.content.vtotalN[5][0];//+"<input type='hidden' value='"+resp.content.vtotalN[5][2]+"'>"; //penilaian
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11).setAttribute('title',resp.content.totalN[5][2] );
					//document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12).innerHTML = resp.content.vtotalN[5][1];
						
					//document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13).innerHTML = resp.content.vtotalN[6][0]; //penghapsuan
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12).innerHTML = resp.content.vtotalN[6][1];//+"<input type='hidden' value='"+resp.content.vtotalN[6][3]+"'>";
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12).setAttribute('title',resp.content.totalN[6][3] );
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13).innerHTML = resp.content.vtotalN[7][0];//+"<input type='hidden' value='"+resp.content.vtotalN[7][2]+"'>"; //koreksi
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13).setAttribute('title',resp.content.totalN[7][2] );
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14).innerHTML = resp.content.vtotalN[7][1];//+"<input type='hidden' value='"+resp.content.vtotalN[7][3]+"'>";
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14).setAttribute('title',resp.content.totalN[7][3] );
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+15)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+15).innerHTML = resp.content.vtotalN[8][0];//+"<input type='hidden' value='"+resp.content.vtotalN[8][2]+"'>"; //reklas
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+15)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+15).setAttribute('title',resp.content.totalN[8][2] );
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+16)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+16).innerHTML = resp.content.vtotalN[8][1];//+"<input type='hidden' value='"+resp.content.vtotalN[8][3]+"'>";
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+16)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+16).setAttribute('title',resp.content.totalN[8][3] );
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+17)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+17).innerHTML = resp.content.vtotalN[9][0];//+"<input type='hidden' value='"+resp.content.vtotalN[9][2]+"'>"; //penyusutan
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+17)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+17).setAttribute('title',resp.content.totalN[9][2] );
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+18)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+18).innerHTML = resp.content.vtotalN[9][1];//+"<input type='hidden' value='"+resp.content.vtotalN[9][3]+"'>";
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+18)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+18).setAttribute('title',resp.content.totalN[9][3] );
					**/
					//if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+19)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+19).innerHTML = resp.content.vSaldoAk;//+"<input type='hidden' value='"+resp.content.vSaldoAkBrg+"'>"; //saldo akhit					
					//if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+19)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+19).setAttribute('title', resp.content.saldoAkBrg );
					//if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+20)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+20).innerHTML = resp.content.vSusutAk; //susut ak
					//if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+21)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+21).innerHTML = resp.content.vNilaibukuAk; //nilai buku ak
					
					//if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).innerHTML = resp.content.vSaldoAk;//+"<input type='hidden' value='"+resp.content.vSaldoAkBrg+"'>"; //saldo akhit					
					//if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).setAttribute('title', resp.content.saldoAkBrg );
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).innerHTML = resp.content.vSaldoAkBrg;//+"<input type='hidden' value='"+resp.content.vSaldoAkBrg+"'>"; //saldo akhit					
					//if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).setAttribute('title', resp.content.vSaldoAk );
					
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+2)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+2).innerHTML = resp.content.vkol1;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3).innerHTML = resp.content.vkol2;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+4)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+4).innerHTML = resp.content.vkol3;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5).innerHTML = resp.content.vkol4;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6).innerHTML = resp.content.vkol5;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7).innerHTML = resp.content.vkol6;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8).innerHTML = resp.content.vkol7;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9).innerHTML = resp.content.vkol8;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10).innerHTML = resp.content.vkol9;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11).innerHTML = resp.content.vkol10;
					if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12).innerHTML = resp.content.vkol11;
					
										
					me.iterator+=me.jmlkol;
					me.reqTotalbyIter(me.iterator);
					
			  	},
				error: function(request, type, errorThrown){
					if(type != 'abort'){
						me.jmlgagal ++;	
						me.iterator+=me.jmlkol;
						document.getElementById(idel_).innerHTML = '';
						me.reqTotalbyIter(me.iterator);
					}
					
				}
				
			});	
		}else{
			me.iterator+=me.jmlkol;
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
