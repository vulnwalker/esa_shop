
var gedung = new DaftarObj2({
	prefix : 'gedung',
	url : 'pages.php?Pg=gedung', 
	formName : 'gedung_form',
	filterRenderAfter : function(){		
		//barcodeSensusBaru.loading();
		//gedung.genCombo();
		
	},
	formPilih = function(){					
		var me = this;
		var cover = this.prefix+'_formPilihcover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,1,true,false);	
		$.ajax({
		  url: this.url+'&tipe=formPilih',
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			document.getElementById(cover).innerHTML = resp.content;			
			//me.AfterFormPilih();
		  }
		});
		
		
	}
});

/*var GedungCls = function(){
	this.name = 'gedung';
	this.renderTo = this.name+'_render';
	this.onchange= this.name+'.comboChange()';
	this.formName = 'adminForm';
	
	this.comboChange  = function(){
		//alert('tes');
	}
	this.genCombo = function(){
		var me = this;
		
		$.ajax({
			url: 'pages.php?Pg=gedung&tipe=getdata',
		  	type : 'POST',
		  	data:$('#'+this.formName).serialize(), //data:$('#adminForm').serialize(),
		  	success: function(data) {		
				var resp = eval('(' + data + ')');			
				
				
				var content = "<option value=''>Pilih Gedung</option>"+resp.content;
				document.getElementById(me.renderTo).innerHTML = 
					"<select name='"+me.name+"_cbx' id='"+me.name+"_cbx' onchange='"+me.onchange+"'>"+
					content+
					"</select>";
		  	}
		});
		
		
	}
}
var gedung= new GedungCls();
*/