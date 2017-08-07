var WilayahCls = function(params_){
	this.params = params_;
	
	this.pilihKotaKabAfter = function(){
	var me = this;

	}
	this.pilihKecamatanAfter = function(){

	
	
	}

	this.pilihNegara = function(){
	
		var me = this; //alert('tes');	
		document.getElementById(this.prefix+'fmxKotaKab').value='0';
		document.getElementById(this.prefix+'fmxKecamatan').value='0';
		//this.formName = 'adminForm';
		$.ajax({
//		  url: 'pages.php?Pg=wilayah&nm='+this.prefix,

		  url: 'pages.php?Pg=wilayah&idprs=jsongps&nm='+this.prefix,
		  type : 'POST',
		  //data:$('#adminForm').serialize(),
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			//console.info(me);
			//console.info('id='+me.prefix+'CbxUnit');
			document.getElementById(me.prefix+'CbxKotaKab').innerHTML = resp.dt_kota;
			document.getElementById(me.prefix+'CbxKecamatan').innerHTML = resp.dt_kecamatan;
			me.pilihProvinsiAfter();
		  }
		});
	}

	this.pilihProvinsi = function(){
	
		var me = this; //alert('tes');	

		document.getElementById(this.prefix+'fmxKotaKab').value='0';
		document.getElementById(this.prefix+'fmxKecamatan').value='0';
		//this.formName = 'adminForm';
		$.ajax({
//		  url: 'pages.php?Pg=wilayah&nm='+this.prefix,

		  url: 'pages.php?Pg=wilayah&idprs=jsongps&nm='+this.prefix,
		  type : 'POST',
		  //data:$('#adminForm').serialize(),
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			//console.info(me);
			//console.info('id='+me.prefix+'CbxUnit');
			document.getElementById(me.prefix+'CbxKotaKab').innerHTML = resp.dt_kota;
			document.getElementById(me.prefix+'CbxKecamatan').innerHTML = resp.dt_kecamatan;
			me.pilihProvinsiAfter();
		  }
		});
	}
	
	this.pilihKotaKab = function(){
		var me = this;	//alert('tes');
		document.getElementById(this.prefix+'fmxKecamatan').value='0';
		$.ajax({
		  url: 'pages.php?Pg=wilayah&idprs=jsongps&nm='+this.prefix,
		  type : 'POST',
		  data:$('#'+this.formName).serialize(), //data:$('#adminForm').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			//document.getElementById('cbxUnit').innerHTML = resp.unit;
			document.getElementById(me.prefix+'CbxKotaKab').innerHTML = resp.dt_kota;
			document.getElementById(me.prefix+'CbxKecamatan').innerHTML = resp.dt_kecamatan;
			me.pilihKotaKabAfter();
		  }
		});
	}
	
	
	this.pilihKecamatan = function(){
		var me= this;
		$.ajax({
		  url: 'pages.php?Pg=wilayah&idprs=jsongps&nm='+this.prefix,
		  type : 'POST',
		  data:$('#'+this.formName).serialize(), //data:$('#adminForm').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			//document.getElementById('cbxUnit').innerHTML = resp.unit;
			document.getElementById(me.prefix+'CbxKecamatan').innerHTML = resp.dt_kecamatan;
		me.pilihKecamatanAfter();
		  }
		});

	

	}
	
	this.initial = function(){		
		for (var name in this.params) {
			//console.info ('name='+name);
			eval( 'if(this.params.'+name+' != null) this.'+name+'= this.params.'+name+'; ');
		}
	}
	this.initial();
}
var Wilayah = new WilayahCls({
	prefix : 'Wilayah', formName:'adminForm'
});

