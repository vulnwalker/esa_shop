
/*var UserProfilSkpd = new SkpdCls({
	prefix : 'UserAktivitasSkpd', formName:'adminForm'
});
*/
var UserProfil = new DaftarObj2({
	prefix : 'UserProfil',
	url : 'pages.php?Pg=userprofil', 
	formName : 'adminForm',// 'ruang_form',
	
	statusEdit : 0, //0 belum ada yang dipilih ,1 nama ,2 password
	/**05 Maret 2013 - 06 maret 2013**/
	
	
	tampilForm: function(i){
		//i: 1=nama, 2=pass, 3=upload
		var id = '';
		switch (i){
			case 1: 
				id='div_nama';	
				//get data lama
				
			break;
			case 2: id='div_pass'; break;
			case 3: id='div_upload'; break;
		}
		
		//tampil div
		if(id!=''){
			document.getElementById(id).style.display='none';
			document.getElementById(id+'_edit').style.display='block';	
		}
		
	},
	
	suntingNama:function()
	{
		if(this.statusEdit ==0)	{ 		
			//batalkan semua
		        this.batalEdit(1 );
				this.batalEdit(2 );
				this.batalEdit(3 );			
				//aktifkan baru
			   	this.tampilForm(1);
		} else	{
			//alert(this.statusEdit)
			if(confirm('batalkan'))	{
				//batalkan yang lama
				this.batalEdit(this.statusEdit );
				
				//aktifkan baru
				this.tampilForm(1)
				//reset status edit
				this.statusEdit = 0;
			}
		}		
	},
	
	suntingPass:function()
	{
		if(this.statusEdit ==0)
		{ 		//batalkan semua
		        this.batalEdit(1 );
				this.batalEdit(2 );
				this.batalEdit(3 );
				
				//aktifkan baru
			    this.tampilForm(2);
		}
		else
		{
			//alert(this.statusEdit)
			if(confirm('batalkan'))
			{
				//batalkan yang lama
				this.batalEdit(this.statusEdit );
				
				//aktifkan baru
			    this.tampilForm(2);
				//reset status edit
				this.statusEdit = 0;
			}
		
		}
		
	},
	suntingUpload: function(){
		if(this.statusEdit ==0){ 		
			//batalkan semua
		       this.batalEdit(1 );
				this.batalEdit(2 );
				this.batalEdit(3 );
				
				//aktifkan baru
			    this.tampilForm(3);
		}else{
			//alert(this.statusEdit)
			if(confirm('batalkan'))
			{
				//batalkan yang lama
				this.batalEdit(this.statusEdit );
				
				//aktifkan baru
			    this.tampilForm(3);
				//reset status edit
				this.statusEdit = 0;
			}
		
		}
		
		
	},
	//07 Maret 2013
	cekStatusEdit:function(nilailama,th,i)
	{
		var idelement='';
		switch(i) {
		 	case 1: idelement = 'btsimpanNama'; break; //nama
			case 2: idelement = 'btsimpanPass'; break; //password
			case 3: idelement = 'btSimpanUpload'; break;
		 }
		 
		 //utk i = 1
		 if(i==1){
		 	nilailama = document.getElementById('nama_old').value;
		 }
		 
		if(nilailama !=th.value){
			document.getElementById(idelement).removeAttribute('disabled');	
			this.statusEdit=i; //set
		}else {
			document.getElementById(idelement).setAttribute('disabled','true');				
			this.statusEdit=0; //reset
		
		}
		
		
	},
	
	batalEdit:function(i)
	{
		var ide1 ='';
		var ide2 = '';
		var bt = '';
		
		switch(i)
		{
		    case 1:
				//nama
				ide1 = 'div_nama'
				ide2 = 'div_nama_edit'
				bt = 'btsimpanNama';
				//clearform
				document.getElementById('nama_pass').value="";
				document.getElementById('nama').value= document.getElementById('nama_old').value;
				
			break;
			case 2:
				//password
				ide1 = 'div_pass'
				ide2 = 'div_pass_edit'
				bt = 'btsimpanPass';
				//clearform
				document.getElementById('passBaru').value="";
				document.getElementById('passBaru1').value="";
				document.getElementById('passLama').value="";
			break;	
			case 3:
				//password
				ide1 = 'div_upload'
				ide2 = 'div_upload_edit'
				bt = 'btSimpanUpload';
				//clearform
				document.getElementById('ImageFile').value="";				
			break;	
		}
		document.getElementById(ide1).style.display='block';
		document.getElementById(ide2).style.display='none';	
		//document.getElementById(bt).setAttribute('class','buttonui');	
		document.getElementById(bt).setAttribute('disabled','true');	
		this.statusEdit=0;
		
		
		
	},
	
	update_nama:function(){
		var me= this;
		 // get the form values  
		var nama = document.getElementById('nama');  
		var nama_pass = document.getElementById('nama_pass');  
		 
		$.ajax({
			url:'pages.php?Pg=userprofil&tipe=update_nama',
			type: "POST",
			data: "nama="+nama.value+"&nama_pass="+nama_pass.value, 
			success : function(data){
				var resp = eval('(' + data + ')');
				if(resp.err =='') {					
					document.getElementById('nama_lengkap').innerHTML = resp.content
					document.getElementById('nama_old').value = resp.content
					alert('Sukses Ganti Nama');
					me.batalEdit(1);
				
				}else{
					alert(resp.err)
				}		
			},
			error : function(){
				alert('Data Gagal di Update');
			}
		});
	},	
	//06 Maret 2013
	
	update_pass:function(){
		//get value form
		var me= this;
		 var passLama = document.getElementById('passLama');  
	     var passBaru = document.getElementById('passBaru');  
	     var passBaru1 = document.getElementById('passBaru1');  
		 //passBaru1=passBaru;
	 	  //alert(passLama.value)
	      //alert(passBaru.value)
	      //alert(passBaru1.value)
	  //**
		//create ajax syntax
		$.ajax({
		    url:'pages.php?Pg=userprofil&tipe=update_pass',
			type: "POST",
			data: "passLama="+passLama.value+"&passBaru="+passBaru.value+"&passBaru1="+passBaru1.value, 
			success : function(data){
				//alert('Data Berhasil di Update');
				var resp = eval('(' + data + ')');
				if(resp.err =='') {
					alert('Sukses Ganti Password');
					me.batalEdit(2);
				}else{
					alert(resp.err)
				}		
			},
			error : function(){
				alert('Data Gagal di Update');
			}
		});
	
	},
	
	updatePhoto: function(fname, fncallback){
		var err='';
		$.ajax({
	    url:'pages.php?Pg=userprofil&tipe=update_photo&fname='+fname,
		type: "POST",
		//data: "fname="+fname, 
		success : function(data){			
			var resp = eval('(' + data + ')');
			if(resp.err =='') {
				fncallback();
			}else{
				alert(resp.err);
			}		
		},
		});
	}
	
});

