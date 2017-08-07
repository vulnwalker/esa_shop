var renja_ins_v2SKPD = new SkpdCls({
	prefix : 'renja_ins_v2SKPD', formName:'renja_ins_v2Form', kolomWidth:120,
	
	a : function(){
		alert('dsf');
	},
});

var renja_ins_v2 = new DaftarObj2({
	prefix : 'renja_ins_v2',
	url : 'pages.php?Pg=renja_ins_v2', 
	formName : 'renja_ins_v2Form',
	satuan_form : '0',//default js satuan
	
	
	
	loading: function(){

		this.topBarRender();
		this.filterRender();

	
	},	
	
	closeTab : function(){
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		$.ajax({
		  url: this.url+'&tipe=CekKosong',
		  type : 'POST',
		  data:{  c1 : c1,
		  		  c	  : c,
				  d : d,
				  tahunAnggaran : $("#tahunAnggaran").val()
				 },
		  success: function(data) {
		 	    window.opener.location.reload();
				var ww = window.open(window.location, '_self');
				ww.close();
				
		  }
		});
		
	},
	

	BidangAfterform: function(){
		var me = this;
		var arrayC1 = $("#urusan").val().split('.');
		var arrayC = $("#bidang").val().split('.');
		var arrayD = $("#skpd").val().split('.');
		var c1 = arrayC1[0];
		var c = arrayC[0];
		var d = arrayD[0];
		$.ajax({
		  url: this.url+'&tipe=BidangAfterForm',
		  type : 'POST',
		  data:{ fmSKPDBidang: c,
		  		 fmSKPDUrusan: c1,
		  		 fmSKPDskpd: d,
		  		 fmSKPDUnit: $("#cmbUnitForm").val(),
		  		 fmSKPDSubUnit: $("#cmbSubUnitForm").val() },
		  success: function(data) {
			var resp = eval('(' + data + ')');	
				document.getElementById('cmbSubUnitForm').innerHTML=resp.content.subunit;
		  }
		});

	},
	
	
	rincianpenerimaan: function(){
	    
		$('.datepicker').datepicker({
						    dateFormat: 'dd-mm-yy',
							showAnim: 'slideDown',
						    inline: true,
							showOn: "button",
     						buttonImage: "images/calendar.gif",
      						buttonImageOnly: true,
							changeMonth: true,
      						changeYear: true,
							yearRange: "+1:+5",
							buttonText : '',
							defaultDate: +365
		});	
		var jenisKegiatan = document.getElementById("jenisKegiatan");
		jenisKegiatan.remove(0);
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
			url: this.url+'&tipe=rincianpenerimaanDET',
			success: function(data) {	
				var resp = eval('(' + data + ')');			
				if(resp.err==''){
					document.getElementById('rinciandatabarangnya').innerHTML = resp.content;
					setTimeout(function myFunction() {renja_ins_v2.CekSesuai()},1000);
					$("#keyPP").text("Rp. "+popupProgram.formatCurrency($("#plus").val()));
					$("#keyMM").text("Rp. "+popupProgram.formatCurrency($("#minus").val()));
				}else{
					alert(resp.err);
				}
			}
		});	
		
	},
    CariProgramKegiatan: function(){
		var me = this;
		popupProgramRenja.windowShow();	
	},
	
	detail: function(){
		//alert('detail');
		var me = this;
		errmsg = this.CekCheckbox();
		if(errmsg ==''){ 
			var box = this.GetCbxChecked();			
			//UserAktivitasDet.genDetail();			
			
		}else{
		
			alert(errmsg);
		}
		
	},
	daftarRender:function(){
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
				me.sumHalRender();
		  	}
		});
	},
	
	EDIT:function(){
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=EDIT',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						alert("Data Tersimpan");
						
							window.opener.location.reload();
							var ww = window.open(window.location, '_self');
		 					ww.close();


					}else{
						alert(resp.err);
					}
				}
			});	
		
	},
	
	Simpan: function(){
		$.ajax({
				type:'POST', 
				data:$('#'+this.formName).serialize(),
				url: this.url+'&tipe=Simpan',
				success: function(data) {	
					var resp = eval('(' + data + ')');			
					if(resp.err==''){
						alert("Data Tersimpan");
						if (confirm('Input Lagi ?')) {
							window.location.reload();
						}else{
							window.opener.location.reload();
							var ww = window.open(window.location, '_self');
		 					ww.close();

						}
					}else{
						alert(resp.err);
					}
				}
			});	
	},
	formatCurrency:function(num) {
		num = num.toString().replace(/\$|\,/g,'');
		if(isNaN(num))
		num = "0";
		sign = (num == (num = Math.abs(num)));
		num = Math.floor(num*100+0.50000000001);
		cents = num%100;
		num = Math.floor(num/100).toString();
		if(cents<10)
		cents = "0" + cents;
		for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+'.'+
		num.substring(num.length-(4*i+3));
		return (((sign)?'':'-') + '' + num + ',' + cents);
	},
	
	isNumberKey: function(evt){
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		
		return false;
		return true;
	},
	
	
		
});
