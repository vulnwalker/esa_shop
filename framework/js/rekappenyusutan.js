var RekapPenyusutanSkpd = new SkpdCls({
	prefix : 'RekapPenyusutanSkpd', formName:'adminForm'
});


var RekapPenyusutan = new DaftarObj2({
	prefix : 'RekapPenyusutan',
	//url : 'pages.php?Pg=Rekap2Ajx', 
	url : 'pages.php?Pg=RekapPenyusutan', 
	formName : 'adminForm',// 'ruang_form',
	
	nodeList : null,
	iterator: 0,
	ajx:null,
	jmlgagal : 0,
	jmlkol: 14,
	
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
				var href_ = node.getAttribute('href');
				var idel = node.getAttribute('id');					
			}
			me.reqTotal(href_,idel,me.iterator);
			//alert(me.nodeList.length);	
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
		var href__ = href_.replace("JurnalPenyusutan", "RekapPenyusutan");		
		//alert(iterator_);
		if(href_!=''){
			document.getElementById(idel_).innerHTML = "<img src='images/wait.gif' height='10px' >";
			this.ajx = $.ajax({
			  	url: href__+'&tipe=rekapNeraca&idel='+idel_+'&iter='+iterator_,
			 	type:'POST', 
				data:$('#'+this.formName).serialize(), 
			  	success: function(data) {		
					var resp = eval('(' + data + ')');				
					document.getElementById(resp.content.idel).innerHTML = resp.content.vtotal;
					var idel = resp.content.idel;
					var arrid = idel.split('_');
					//alert(document.getElementById('fmTplDetail').value);
					
					if(document.getElementById('fmTplDetail').checked){
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).innerHTML = resp.content.vtotal1;//+"<input type='hidden' value='"+resp.content.vSaldoAkBrg+"'>"; //saldo akhit					
						//if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).setAttribute('title', resp.content.vSaldoAk );
						
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+2)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+2).innerHTML = resp.content.vtotal2;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+3).innerHTML = resp.content.vtotal3;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+4)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+4).innerHTML = resp.content.vtotal4;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+5).innerHTML = resp.content.vtotal5;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+6).innerHTML = resp.content.vtotal6;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+7).innerHTML = resp.content.vtotal7;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+8).innerHTML = resp.content.vtotal8;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+9).innerHTML = resp.content.vtotal9;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+10).innerHTML = resp.content.vtotal10;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+11).innerHTML = resp.content.vtotal11;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+12).innerHTML = resp.content.vtotal12;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+13).innerHTML = resp.content.vtotal13;
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14).innerHTML = resp.content.vtotal14;						
					}else{
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+1).innerHTML = resp.content.vtotal1;//+"<input type='hidden' value='"+resp.content.vSaldoAkBrg+"'>"; //saldo akhit
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+15)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+15).innerHTML = resp.content.vtotal15;//+"<input type='hidden' value='"+resp.content.vSaldoAkBrg+"'>"; //saldo akhit					
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+16)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+16).innerHTML = resp.content.vtotal16;											
						if(document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14)) document.getElementById(arrid[0]+'_'+arrid[1]+'_'+14).innerHTML = resp.content.vtotal14;
					}

					
										
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
		//set jml kolom jika tampil detail dipilih
		if(document.getElementById('fmTplDetail').checked){
			this.jmlkol = 14;
		}else{
			this.jmlkol = 4;	
		}		
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
