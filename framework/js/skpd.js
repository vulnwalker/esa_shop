var SkpdCls = function(params_){
	this.params = params_;
	
	this.pilihBidangAfter = function(){}
	this.pilihUnitAfter = function(){}
	this.pilihSubUnitAfter= function(){}
	this.pilihSeksiAfter= function(){}

	this.pilihBidang = function(){
		var me = this; //alert('tes');	//alert(this.prefix);
		document.getElementById(this.prefix+'fmUNIT').value='00';
		document.getElementById(this.prefix+'fmSUBUNIT').value='00';
		document.getElementById(this.prefix+'fmSEKSI').value='000';
		//this.formName = 'adminForm';
		$.ajax({
		  url: 'pages.php?Pg=skpd&nm='+this.prefix,
		  type : 'POST',
		  //data:$('#adminForm').serialize(),
		  data:$('#'+this.formName).serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			//console.info(me);
			//console.info('id='+me.prefix+'CbxUnit');
			document.getElementById(me.prefix+'CbxUnit').innerHTML = resp.unit;
			document.getElementById(me.prefix+'CbxSubUnit').innerHTML = resp.subunit;
			document.getElementById(me.prefix+'CbxSeksi').innerHTML = resp.seksi;
			me.pilihBidangAfter();
		  }
		});
	}
	
	this.pilihUnit = function(){
		var me = this;	//alert('tes');
		document.getElementById(this.prefix+'fmSUBUNIT').value='00';
		document.getElementById(this.prefix+'fmSEKSI').value='000';
		$.ajax({
		  url: 'pages.php?Pg=skpd&nm='+this.prefix,
		  type : 'POST',
		  data:$('#'+this.formName).serialize(), //data:$('#adminForm').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');
			//document.getElementById('cbxUnit').innerHTML = resp.unit;
			document.getElementById(me.prefix+'CbxSubUnit').innerHTML = resp.subunit;
			document.getElementById(me.prefix+'CbxSeksi').innerHTML = resp.seksi;
			me.pilihUnitAfter();
		  }
		});
	}
	
	
	this.pilihSubUnit = function(){
		var me= this;
		document.getElementById(this.prefix+'fmSEKSI').value='000';
		$.ajax({
		  url: 'pages.php?Pg=skpd&nm='+this.prefix,
		  type : 'POST',
		  data:$('#'+this.formName).serialize(), //data:$('#adminForm').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');	//document.getElementById('cbxUnit').innerHTML = resp.unit;	//document.getElementById(me.prefix+'CbxSubUnit').innerHTML = resp.subunit;
			document.getElementById(me.prefix+'CbxSeksi').innerHTML = resp.seksi;
			me.pilihSubUnitAfter();
		  }
		});
		
	}
	
	this.pilihSeksi = function(){
		var me= this;
		$.ajax({
		  url: 'pages.php?Pg=skpd&nm='+this.prefix,
		  type : 'POST',
		  data:$('#'+this.formName).serialize(), //data:$('#adminForm').serialize(),
		  success: function(data) {		
			var resp = eval('(' + data + ')');			
			me.pilihSeksiAfter();
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
var Skpd = new SkpdCls({
	prefix : '', formName:'adminForm'
});
var SensusSkpd = new SkpdCls({
	prefix : 'SensusSkpd', formName:'SensusPageForm'
});
var SensusBaruSkpd = new SkpdCls({
	prefix : 'SensusBaru', formName:'Sensus_form'	
});
var RuangSkpd = new SkpdCls({
	prefix : 'RuangSkpd', formName:'adminForm',
	
	
	pilihGedung: function(){
		$.ajax({
			url: 'pages.php?Pg=ruang&tipe=cbxgedung',
		  	type : 'POST',
		  	data:$('#adminForm').serialize(), //data:$('#adminForm').serialize(),
		  	success: function(data) {		
				var resp = eval('(' + data + ')');				
				document.getElementById('cbxRuangGedung').innerHTML =  resp.content;
		  	}
		});
	},
	pilihBidangAfter : function(){
		this.pilihGedung();
	},
	pilihUnitAfter: function(){
		this.pilihGedung();
	},
	pilihSubUnitAfter : function(){
		//alert('tes');
		this.pilihGedung();
		
	}
});
var PegawaiSkpd = new SkpdCls({
	prefix : 'PegawaiSkpd', formName:'adminForm'
});
