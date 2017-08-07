KeranjangObj = function(params_){
	this.params=params_;
	//public
	this.name = 'Keranjang',
	this.nmContainPilih = 'cb';
	this.nmContainPilihAll = 'cball';
	this.nmContainBtnDaftar = 'cntlinkdaftar';
	this.jmlContainPilih = 5;
	this.imgsrc = 'images/administrator/images/downloads_f2.png';
	this.imgsrc_off = 'images/administrator/images/downloads.png';
	
	//private
	this.daftarPilih = new Array();
	
	
	this.setCookie = function(c_name,value,exdays){
		var exdate=new Date();
		exdate.setDate(exdate.getDate() + exdays);
		var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		document.cookie=c_name + "=" + c_value;
	}
	this.getCookie = function(c_name){
		var i,x,y,ARRcookies=document.cookie.split(";");
		for (i=0;i<ARRcookies.length;i++){
			x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
			y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
			x=x.replace(/^\s+|\s+$/g,"");
			if (x==c_name){
				return unescape(y);
			}
		}
	}
	this.createPilihan = function(){
		// input nama, dan jml div container . sample: nmContainPilih= 'cb', jmlContainPilih=5
		for (i=1;i<=this.jmlContainPilih; i++){
			var contain = document.getElementById(this.nmContainPilih+i.toString());
			if(contain){
				var value = contain.getAttribute('value'); //value = idpilih
			
				contain.innerHTML = 
					//"tes"+i.toString();
					"<img src='"+this.imgsrc+"' "+
					" id='"+this.name+"_img"+i.toString()+"' "+
					"	alt='keranjang' name='Pilih' "+
					"	width='24' height='24' "+
					"	border='0' align='middle' "+					 
					"	title='' "+
					" state='0' "+
					" value='"+value+"' "+
					"	style='cursor:pointer' "+
					" onclick=\""+this.name+".add(this,'"+value+"')\""+
					">";	
			}
			
		}
		
	}
	this.createPilihanAll = function(){
		document.getElementById(this.nmContainPilihAll).innerHTML = 
			"<img src='"+this.imgsrc+"' "+
				"	alt='keranjang' name='Pilih' "+
				" id='"+this.name+"_imgall' "+
				"	width='36' height='36' "+
				"	border='0' align='middle' "+					 
				"	title='' "+
				" state='0' "+
				"	style='cursor:pointer' "+
				" onclick=\""+this.name+".addAll(this)\""+
				">";
	}
	this.setLinkDaftar = function(){
		
		return " href=\"javascript:"+this.name+".tampilDaftar()\" ";
	}
	this.createBtnDaftar = function(){
		document.getElementById(this.nmContainBtnDaftar).innerHTML = 
			
			"<table><tr><td align='center'> "+
			" <a "+this.setLinkDaftar+">"+
			"<img src='"+this.imgsrc+"' "+
			"			alt='keranjang' name='Pilih' "+
			"			width='36' height='36' "+
			"			border='0' align='middle' "+
						 
			"			title='Tampilkan Keranjang' "+
			"			style='cursor:pointer' "+
			"			><br> "+
			"<div id='"+this.name+"_jmlpilih'>0</div> "+
			"</a>"+
			"</td><tr> "+
			"</table>";
	}
	this.addAll=function(th){
		if( th.getAttribute('state')=='0'){
			var stateall ='1';
			th.setAttribute('state',stateall);
			th.src = this.imgsrc_off;		
		}else{
			var stateall ='0';
			th.setAttribute('state',stateall);
			th.src = this.imgsrc;		
		}
		
		
		for (i=1;i<=this.jmlContainPilih; i++){
			
			var img = document.getElementById(this.name+"_img"+i.toString());
			//alert(img.getAttribute('value'));
			if(img.getAttribute('state')!=stateall){
				var contain = document.getElementById(this.nmContainPilih+i.toString());
				var value = contain.getAttribute('value');
				this.add(img,value);	
			}
			
		}
		
	}
	this.addAfter = function(img,val,st){
		
	}
	this.setImage = function(img,state){
		if(state==0){
			img.src = this.imgsrc;				//var st = '0';
			
		}else{
			img.src = this.imgsrc_off;			
			
		}
		img.setAttribute('state', state);
	}
	this.add=function(img, val){
		//alert(val);
		
		if (img.getAttribute('state') == '1'){ //delete
			var st = 0;
			this.setImage(img,st);
			var idx=this.daftarPilih.indexOf(val);			
			this.daftarPilih.splice(idx,1);
			
			
		}else{ //add
			var st = 1;
			this.setImage(img,st);
			this.daftarPilih[this.daftarPilih.push()]=val;
		}		
		this.addAfter(img,val,st);
		
		//alert(this.daftarPilih.join()) //tes
		
		//simpan ke cookies
		this.setCookie(this.name, this.daftarPilih.join(), null);
		document.getElementById(this.name+'_jmlpilih').innerHTML = this.daftarPilih.length;
	}
	this.tampilDaftar = function(){
		alert(this.daftarPilih.join());
	}
	
	this.tampil = function(){
		//-- tampil awal
		this.createPilihan();
		this.createPilihanAll();
		this.createBtnDaftar();
		
		//-- ambil dari cokies
		var get = this.getCookie(this.name);
		if(get){
			this.daftarPilih = get.split(',');
			//alert (get);
			for (i=1;i<=this.jmlContainPilih; i++){
				
				var contain = document.getElementById(this.nmContainPilih+i.toString());
				if(contain){
					var value = contain.getAttribute('value');
					
					//this.add(img,value);	
					if(this.daftarPilih.indexOf(value)>=0){
						var img = document.getElementById(this.name+"_img"+i.toString());
						this.setImage(img,1);
					}	
				}
				
				
				
			}
		}
		document.getElementById(this.name+'_jmlpilih').innerHTML = this.daftarPilih.length;
		
	}
	
	this.initial = function(){	
		//change param default	
		for (var name in this.params) {
			eval( 'if(this.params.'+name+' != null) this.'+name+'= this.params.'+name+'; ');
		}
	}
	this.initial();
}
