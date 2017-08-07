var dkpbSkpd = new SkpdCls({
	prefix : 'dkpbSkpd', formName:'adminForm'
});

var dkpb = new DaftarObj2({
	prefix : 'dkpb',	
	url : 'pages.php?Pg=dkpb&ajx=1',
	formName : 'adminForm',
	
	Edit : function(){
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();
			var aForm = document.getElementById(this.formName);	
			aForm.action=this.url+'&tipe=formEdit';
			aForm.target='_blank';
			aForm.submit();	
			aForm.target='';
			
			//this.Show ('formedit',{idplh:box.value}, false, true);			
			/*var cover = this.prefix+'_formcover';
			addCoverPage2(cover,999,true,false);	
			document.body.style.overflow='hidden';
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=formEdit',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');	
					if (resp.err ==''){		
						document.getElementById(cover).innerHTML = resp.content;
						//me.AfterFormEdit(resp);
					}else{
						alert(resp.err);
						delElem(cover);
						document.body.style.overflow='auto';
					}
			  	}
			});*/
		}else{
			alert(errmsg);
		}
		
	},
	
	Simpan: function(){
		var err ='';
		if(err == ''){
			var cover = this.prefix+'_formcover';	//document.body.style.overflow='hidden';
			addCoverPage2(cover,1,true,false);	
			$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
			  	url: this.url+'&tipe=simpan',
			  	success: function(data) {		
					var resp = eval('(' + data + ')');			
					delElem(cover);
					if(resp.err==''){
						alert('Sukses Simpan Data');
						window.close();		
					}else{
						alert(resp.err);
					}					
			  	}
			});
			
		}else{
			alert(err);
		}
		
	},
	
	Get_JmlHarga: function(){
		var jml_barang= document.getElementById('jml_barang').value;
		var kuantitas= document.getElementById('kuantitas').value;
		var harga= document.getElementById('harga').value;
		var Jml_harga =  jml_barang * kuantitas * harga;
		document.getElementById('jml_harga').value = Jml_harga;
	}
});