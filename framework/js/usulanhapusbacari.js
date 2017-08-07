var UsulanHapusbacariSkpd = new SkpdCls({
	prefix : 'UsulanHapusbacariSkpd', formName:'UsulanHapussk_formbacari'
});

var UsulanHapusbacari = new DaftarObj2({
	prefix : 'UsulanHapusbacari',
	url : 'pages.php?Pg=usulanhapusbacari', 
	formName : 'UsulanHapussk_formbacari',// 'ruang_form',
	
	daftarPilih: new Array(),
	
	checkAll:function(PagePerHal,idcb,idtoggle,jmlcek) //untuk pilih semua
	{	//alert(PagePerHal)
		var toggle = document.getElementById(idtoggle)
		for(var i=0;i<PagePerHal;i++){
			var cb = document.getElementById('UsulanHapusbacari_cb'+i.toString());
			if(cb) //jika cb ada
			{
				if(toggle.checked)
				{
					console.log(i.toString()+ " "+cb.value)
										
					if(this.daftarPilih.indexOf(cb.value)<0)
					{
						this.daftarPilih[this.daftarPilih.push()]=cb.value
					}
					
				}
				else
				{
					var idx=this.daftarPilih.indexOf(cb.value)
			
					this.daftarPilih.splice(idx,1)
				}
				
			}
			
		}
		
		//alert(this.daftarPilih.join())
		this.setCookie(this.prefix+"_DaftarPilih",this.daftarPilih.join()) //setcookie
	},
	
	//start cookie
	getCookie:function(c_name)
	{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	  {
	  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
	  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
	  x=x.replace(/^\s+|\s+$/g,"");
	  if (x==c_name)
	    {
	    return unescape(y);
	    }
	  }
	},
	
	setCookie:function(c_name,value)
	{
		//var exdate=new Date();
		//exdate.setDate(exdate.getDate() + exdays);
		//var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		var c_value=escape(value);
		document.cookie=c_name + "=" + c_value;
	},	
	
	//end cookie
		
	cbxPilih:function(th)
	{
		if(th.checked)
		{
			//alert(this.daftarPilih.length)
			//alert(this.daftarPilih[this.daftarPilih.length-1])
			this.daftarPilih[this.daftarPilih.push()]=th.value
			
		}
		else
		{
			var idx=this.daftarPilih.indexOf(th.value)
			
			this.daftarPilih.splice(idx,1)
		}
		//alert(this.daftarPilih.join())
		this.setCookie(this.prefix+"_DaftarPilih",this.daftarPilih.join()) //setcookie
		//alert("Daftar Pilih(1)"+this.daftarPilih[1])
	}, 
	
	daftarRender:function(){
		var me =this; //render daftar 
		addCoverPage2(
			'daftar_cover',	999, 	true, true,	{renderTo: this.prefix+'_cont_daftar',
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
						
				//tampilkan di checkbox
				  me.cbTampil() //fungsi untuk memanggil tampil checkbox
				me.sumHalRender();
		  	}
		});
	},
	
	cbTampil:function() //fungsi untuk dipanggil di daftarRender() untuk Cookie
	{	
		var getCook = this.getCookie(this.prefix+'_DaftarPilih') //ambil cookies
		if(getCook)//jika ada nilai
		{	
		    //merubah string ke array
			this.daftarPilih=getCook.split(',')
			
			//pengecekan jumlah checkbox
			 for(var i=0;i<25;i++)
			{
				 if(document.getElementById('UsulanHapusbacari_cb'+i.toString()))
				 {
					var cbx = document.getElementById('UsulanHapusbacari_cb'+i.toString()); //ambil value checkbox
					var val = cbx.value;
				   	//jika value ada di daftar
					if(this.daftarPilih.indexOf(val)>=0) //pengecekan checkbox
					{
						cbx.checked=true;
					}
				 }
			 }
		 }	
	},
	  
	resetPilih:function()
	{
			this.daftarPilih=[]; //kosongkan Array
			//delete cookie
			this.setCookie(this.prefix+'_DaftarPilih','') //ambil cookies			
	},
	detail: function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();			
			//UserAktivitasDet.genDetail();			
			
		}else{
			alert(errmsg);
		}
		
	},
	
	Baru: function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaru',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	
	BaruBA: function(){	
		
		var me = this;
		var err='';
		
		//cek skpd
		var skpd = document.getElementById(this.prefix+'SkpdfmSKPD').value; 
		var unit = document.getElementById(this.prefix+'SkpdfmUNIT').value;
		var subunit = document.getElementById(this.prefix+'SkpdfmSUBUNIT').value;
		var seksi = document.getElementById(this.prefix+'SkpdfmSEKSI').value;
		
		if(err=='' && (skpd=='' || skpd=='00') ) err='BIDANG belum dipilih!';
		if(err=='' && (unit=='' || unit=='00') ) err='SKPD belum dipilih!';
		if(err=='' && (subunit=='' || subunit=='00') ) err='UNIT belum dipilih!';
		if(err=='' && (seksi=='' || seksi=='00' || seksi=='000') ) err='SUB UNIT belum dipilih!';
		
		
		if (err =='' ){		
			var cover = this.prefix+'_formcover';
			document.body.style.overflow='hidden';
			addCoverPage2(cover,999,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=formBaru',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					document.getElementById(cover).innerHTML = resp.content;			
					me.AfterFormBaru();
			  	}
			});
		
		}else{
		 	alert(err);
		}
	},
	pilihPejabatPengadaan: function(){
		var me = this;	
		
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idpengadaan';
		PegawaiPilih.el_nip= 'nip_pejabat_pengadaan';
		PegawaiPilih.el_nama= 'nama_pejabat_pengadaan';
		PegawaiPilih.el_jabat= 'jbt_pejabat_pengadaan';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},	
	pilihUsulan: function(){
		var me = this;	
		PegawaiPilih.fmSKPD = document.getElementById('c').value;
		PegawaiPilih.fmUNIT = document.getElementById('d').value;
		PegawaiPilih.fmSUBUNIT = document.getElementById('e').value;
		PegawaiPilih.fmSEKSI = document.getElementById('e1').value;
		PegawaiPilih.el_idpegawai = 'ref_idpengadaan';
		PegawaiPilih.el_nip= 'nip_pejabat_pengadaan';
		PegawaiPilih.el_nama= 'nama_pejabat_pengadaan';
		PegawaiPilih.el_jabat= 'jbt_pejabat_pengadaan';
		PegawaiPilih.windowSaveAfter= function(){};
		PegawaiPilih.windowShow();	
	},
	
	Batal: function(){	
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
				url: this.url+'&tipe=batal',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						//document.getElementById(cover).innerHTML = resp.content;
						//me.AfterFormEdit(resp);
						alert("sukses")
						me.Close();
						me.refreshList(true)
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
	}	
});
