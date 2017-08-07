
var PageObj = function(
	nameobj_,
	url_daftar_, url_cetak_, url_form_,
	 elCurrPage_){
	this.nameobj = nameobj_;
	this.cntDaftar = this.nameobj+'_cont_daftar';// ;cntDaftar_
	this.cntSum = this.nameobj+'_cont_sum';
	this.cntHal = this.nameobj+'_cont_hal';//cntHal_;	
	this.elCurrPage = elCurrPage_;
	this.url_daftar = url_daftar_;//,'index.php?Op=01&Pg=2';
	this.url_cetak = url_cetak_;//'index.php?Op=01&Pg=2';
	this.url_form = url_form_;//'index.php?Op=1&Pg=2&';
	
	this.AfterSimpan = 
		"alert('Sukses Simpan Data');"+
		this.nameobj+'.refreshList(false);';
	this.AfterHapus = 
		"alert('Sukses Hapus Data');"+
		this.nameobj+".refreshList(false);";
		
	this.daftar = 
		new DaftarObj(
			this.url_daftar,					
			'adminForm',			
			this.cntDaftar,
			this.cntSum,
			this.cntHal,
			'HalDefault',
			this.AfterHapus,
			this.nameobj+'_jmlcek' 
		);
	
	this.form = new AjxFormObj2(
				this.nameobj,
				this.url_form+'&' ,
				'adminForm',
				//'entryForm',
				//'fmPerhitunganCover',
				//'Perhitungan_jmlcek',
				//'Perhitungan_jmldatapage',
				//'Perhitungan_cb',
				'','',
				this.AfterSimpan);	
	
	this.Baru = function(){		
		//this.form.Edit();
		//var elname = this.nameobj+'_valuecek';		
		//var val = getCbxCheckedValue(this.nameobj+'_cb[]' )
		//if (val>0) {
		//	this.form.Baru({idplh:val});				
		//}		
		//this.form.Baru();
		alert('tes');
	}
	this.Edit = function(){			
		
		this.form.Edit();				
	
	}
	this.Close = function(){
		this.form.Close();
	}
	this.Simpan = function(){
		this.form.Simpan();
	}
	this.Hapus = function(){
		this.daftar.hapus();
	}
	this.loading=function(){		
		this.daftar.loading();
	}	
	this.refreshList = function(resetPageNo){
		this.daftar.refreshList(resetPageNo);		
	}	
	this.gotoHalaman= function(future, Hal){
		this.daftar.gotoHalaman(future,Hal);		
	}
	this.cetakHal= function(op){
		this.daftar.cetakHal();		
	}
	this.cetakAll= function(op){
		this.daftar.cetakAll();		
	}
}

