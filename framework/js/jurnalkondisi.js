
var JurnalKondisi = new DaftarObj2({
	prefix : 'JurnalKondisi',
	url : 'pages.php?Pg=jurnalkondisi', 
	formName : 'adminForm',// 'ruang_form',
	
	formKondisiClose : function(){
		delElem(this.prefix+'_formKondisiCover');
		document.body.style.overflow='auto';
	},
	formKondisiSimpan : function(){
		var me = this;
		//delElem(this.prefix+'_formKondisiCover');
		var cover = this.prefix+'_formKondisiSimpanCover';
		//document.body.style.overflow='hidden';
		addCoverPage2(cover,10,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.prefix+'_form').serialize(),
		  	url: this.url+'&tipe=formKondisiSimpan',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				delElem(cover);
				if (resp.err==''){
					//document.getElementById(cover).innerHTML = resp.content;			
					alert('Sukses simpan data');
					me.formKondisiClose();
					document.getElementById('nmkondisi').innerHTML = resp.content.nmkondisi
					document.getElementById('fmKONDISIBARANG').value = resp.content.kondisi
					
				}else{					
					alert(resp.err);
				}				
		  	}
		});
	},
	formKondisi : function(){
		var cover = this.prefix+'_formKondisiCover';
		document.body.style.overflow='hidden';
		addCoverPage2(cover,10,true,false);	
		$.ajax({
			type:'POST', 
			data:$('#'+this.formName).serialize(),
		  	url: this.url+'&tipe=formKondisi',
		  	success: function(data) {		
				var resp = eval('(' + data + ')');
				if (resp.err==''){
					document.getElementById(cover).innerHTML = resp.content;			
					
				}else{
					delElem(cover);
					document.body.style.overflow='auto';
					alert(resp.err);
				}
				
		  	}
		});

	},
	formKondisi_:function(){		
		var form_judul = 'Update Kondisi Barang';
		var form_width = '450';
		var form_height = '190';
		var cover ='Penatausaha_formKondisiCover';		
		addCoverPage2(cover,999,true,false);
		
		var form_menu =
			"<div style='padding: 0 8 9 8;height:22; '>"+
			"<div style='float:right;'>"+
				"<input type='button' value='Simpan' onclick='Penatausaha.formKondisiSimpan()'>"+
				"<input type='button' value='Batal' onclick='Penatausaha.formKondisiClose()'>"+
				"<input type='hidden' id='Sensus_idplh' name='Sensus_idplh' value='109'><input type='hidden' id='Sensus_fmST' name='Sensus_fmST' value='1'>"+
				"<input type='hidden' id='sesi' name='sesi' value=''>"+
			"</div>"+
			"</div>";
		
		var kondawal = document.getElementById('nmkondisi').innerHTML;
		var tglkond = 
			"<div id='tgl_kond_content'>"+
			"<div style='float:left;padding: 0 4 0 0'>"+
				"<select style='height:20' onchange=\"TglEntry_createtgl('tgl_kond')\" name='tgl_kond_tgl' id='tgl_kond_tgl'>"+
				"<option value=''>Tgl</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option><option value='11'>11</option><option value='12'>12</option><option value='13'>13</option><option value='14'>14</option><option value='15'>15</option><option value='16'>16</option><option value='17'>17</option><option value='18'>18</option><option value='19'>19</option><option value='20'>20</option><option value='21'>21</option><option value='22'>22</option><option value='23'>23</option><option value='24'>24</option><option value='25'>25</option><option value='26'>26</option><option value='27'>27</option><option value='28'>28</option><option value='29'>29</option><option value='30'>30</option><option selected='' value='31'>31</option></select></div>"+
			"<div style='float:left;padding: 0 4 0 0'>"+
				"<select style='height:20' name='tgl_kond_bln' id='tgl_kond_bln' onchange=\"TglEntry_createtgl('tgl_kond')\">"+
				"<option value=''>Pilih Bulan</option><option value='01'>Januari</option><option value='02'>Pebruari</option><option value='03'>Maret</option><option value='04'>April</option><option value='05'>Mei</option><option value='06'>Juni</option><option value='07'>Juli</option><option value='08'>Agustus</option><option value='09'>September</option><option value='10'>Oktober</option><option value='11'>Nopember</option><option selected='' value='12'>Desember</option></select>"+
			"</div>"+
			"<div style='float:left;padding: 0 4 0 0'><select id='tgl_buku_thn' name='tgl_buku_thn' onchange=\"TglEntry_createtgl('tgl_kond')\">"+
				"<option value=''>Tahun</option><option value='1999'>1999</option><option value='2000'>2000</option><option value='2001'>2001</option><option value='2002'>2002</option><option value='2003'>2003</option><option value='2004'>2004</option><option value='2005'>2005</option><option value='2006'>2006</option><option value='2007'>2007</option><option value='2008'>2008</option><option selected='true' value='2009'>2009</option><option value='2010'>2010</option><option value='2011'>2011</option><option value='2012'>2012</option><option value='2013'>2013</option><option value='2014'>2014</option><option value='2015'>2015</option><option value='2016'>2016</option><option value='2017'>2017</option><option value='2018'>2018</option><option value='2019'>2019</option></select></div><div style='float:left;padding: 0 4 0 0'>"+
				"<input type='button' value='Clear' name='tgl_buku_btClear' id='tgl_buku_btClear' onclick=\"TglEntry_cleartgl('tgl_kond')\">"+
			"</div><div style='float:left;padding: 0 4 0 0'>"+
				//&nbsp;&nbsp;
				//<span style='color:red;'></span>
			"</div>	"+
				"<input $dis='' type='hidden' id='tgl_kond' name='tgl_kond' value=''>"+
				"<input type='hidden' id='tgl_kond_kosong' name='tgl_kond_kosong' value=''>"+
			"</div>";
		
		var content = 
			"<div id='Sensus_form_div' style='margin:9 8 8 8; overflow:auto; border:1px solid #E5E5E5;width:"+(form_width-20)+";height:"+(form_height-80)+";'>"+
				"<table style='width:100%' class='tblform'><tr><td style='padding:4'>"+
					"<table style='width:100%:height:100%'>"+
						"<tr style='height:24'>"+
							"<td style='width:100'>Kondisi Awal</td>"+
							"<td style='width:10'>:</td>"+
							"<td>"+kondawal+"</td>"+
						"</tr>"+						
						"<tr>"+
							"<td style='width:100'>Tanggal</td>"+
							"<td style='width:10'>:</td>"+
							"<td>"+tglkond+"</td>"+
						"</tr>"+						
						"<tr>"+
							
							"<td style='width:100'>Kondisi Sekarang</td>"+
							"<td style='width:10'>:</td>"+
							"<td>"+
								"<select name='jnslabel' id='jnslabel'>"+
								"<option value=''>Pilih</option>"+
								"<option selected value='1'>Baik</option>"+
								"<option value='2'>Kurang Baik</option>"+
								"<option value='3'>Rusak Berat</option>"+
								"</select>"+
							"</td>"+
						"</tr>"+
						
					"</table>"+
				"</td></tr></table>"+
			"</div>";
		
		
		document.getElementById(cover).innerHTML= 
			"<table width='100%' height='100%'><tbody><tr><td align='center'>"+
			//"rtera"+
			"<div id='div_border' style='width:"+form_width+";height:"+form_height+"; background-color:white; border-color: rgba(0, 0, 0, 0.3);   border-style: solid;  border-width:1; box-shadow: 6px 6px 5px rgba(0, 0, 0, 0.3);'>"+
			"<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tbody><tr><td style='padding:0'>"+
				"<div class='menuBar2' style='height:20'>"+			
				"<span style='cursor:default;position:relative;left:6;top:2;color:White;font-size:12;font-weight:bold'>"+form_judul+"</span>"+
				"</div>"+
			"</td></tr></tbody></table>"+			
			content+
			form_menu+		
			"</div>"+
				
			"</td></tr>"+
			"</table>";
			 
	}
	
	
	
});

