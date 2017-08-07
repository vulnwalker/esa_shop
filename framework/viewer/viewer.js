
dlgDetailObj = function(){
	this.formID = 'dlgDetail';
	this.width= '640px';
	this.height= '550px';
	this.fmCaption ='';	
	this.OnShow = function() { //loadingClose('dlgProgress');		//this.showModal( );	
		tabdetail(1);		
    }
}
dlgDetailObj.prototype = new dlgFormObj();
dlgDetail = new dlgDetailObj();

function getdat(event){
	//loadingShow('dlgProgress','images/administrator/images/loading.gif');
	
	//document.body.style.overflow='hidden';
	//window.scroll(0,0);
	//alert('tes');
	
	var el = getSenderFromE(event);
	if (document.getElementById('cbxDlmRibu').checked ){
		str = '&cbxDlmRibu=1'
	}else{
		str='';
	}
	dlgDetail.uri = 'viewer/view_cari_det.php?id='+el.parentNode.id+'&fid=dlgDetail'+str;//alert(detail.uri);
	dlgDetail.GetData();
}

function getdat2(ID){
	loadingShow('dlgProgress','images/administrator/images/loading.gif');	
	dlgDetail.uri = 'viewer/view_cari_det.php?id='+ID+'&fid=dlgDetail';//alert(detail.uri);
	dlgDetail.GetData();
}

function tabdetail(pg){//	alert(pg);
	document.getElementById('pagecontain').innerHTML = '';
	switch (pg){
		case 1:	//alert(pg1)
			document.getElementById('pagecontain').innerHTML = document.getElementById('tb0').innerHTML;
			tab = document.getElementById('t0');			
			break;
		case 2:	//alert(pg2)
			document.getElementById('pagecontain').innerHTML = document.getElementById('tb1').innerHTML;
			tab = document.getElementById('t1');			
			break;
		case 3:			
			document.getElementById('pagecontain').innerHTML = document.getElementById('tb2').innerHTML;
			tab = document.getElementById('t2');
			break;
		case 4:			
			document.getElementById('pagecontain').innerHTML = document.getElementById('tb3').innerHTML;
			tab = document.getElementById('t3');
			break;
		case 5:			
			document.getElementById('pagecontain').innerHTML = document.getElementById('tb4').innerHTML;
			tab = document.getElementById('t4');
			break;
		case 6:			
			document.getElementById('pagecontain').innerHTML = document.getElementById('tb5').innerHTML;
			tab = document.getElementById('t5');
			break;
		case 7:			
			document.getElementById('pagecontain').innerHTML = document.getElementById('tb6').innerHTML;
			tab = document.getElementById('t6');
			break;
		/*case 8: //kib G
			document.getElementById('pagecontain').innerHTML = document.getElementById('tb7').innerHTML;
			tab = document.getElementById('t7');
			break;
		*/
	}
	
	var i = 0;
	for(i==0;i<=6;i++){
		tab = document.getElementById('t'+i);
		if(i!=pg-1){
					
			tab.style.color= 'black'; 
			tab.style.backgroundColor= 'white'; 
			//tab.style.borderColor= '#62';
		}else{
			tab.style.color= 'white'; 
			tab.style.backgroundColor= '#627AAD'; 
			tab.style.borderColor= '#627AAD';
		}
		
	}
	
}


function resetForm(){
	//alert('tes');
	
	//document.fmSKPD.value = '';
	
}

dlgRkpBrgObj = function(){
	this.formID = 'dlgRkpBrg';
	this.width= '640px';
	this.height= '550px';
	this.fmCaption ='';	
	this.OnShow = function() { //loadingClose('dlgProgress');		//this.showModal( );	
		//tabdetail(1);		
    }
	this.print = function(){
		var form = document.getElementById('adminForm2');
		if (document.getElementById('cbxDlmRibu').checked ){
			cbxDlmRibu = '&cbxDlmRibu=1';
		}else{
			cbxDlmRibu = '';
		}
		form.action='?Pg=cetakbrg'+cbxDlmRibu;
		form.target='_blank';
		form.submit();
		//alert('ts');
		
	}

	this.print_xls = function(){
		var form = document.getElementById('adminForm2');
		if (document.getElementById('cbxDlmRibu').checked ){
			cbxDlmRibu = '&cbxDlmRibu=1';
		}else{
			cbxDlmRibu = '';
		}
		form.action='?Pg=cetakbrg&SDest=XLS'+cbxDlmRibu;
		form.target='_blank';
		form.submit();
		//alert('ts');
		
	}	
}
dlgRkpBrgObj.prototype = new dlgFormObj();
dlgRkpBrg = new dlgRkpBrgObj();

function getRkpBrg(event){
	//window.scroll(0,0);
	var el = getSenderFromE(event);
	if (el.tagName == 'B'){
		var sid = el.parentNode.id;
	} else {
		var sid = el.id;
	}
	//alert(sid);	
	
	if (document.getElementById('cbxDlmRibu').checked){ 
		cbxDlmRibu = '&cbxDlmRibu=1'
	}else{
		cbxDlmRibu = '';
	}
	if (document.getElementById('fmTahun')){ 
		fmTahun = document.getElementById('fmTahun').value;
	}else{
		fmTahun = '';
	}
	//alert(fmTahun);
		
	dlgRkpBrg.uri = 'viewer/viewer_rekapbrg.php?id='+sid+'&fid=dlgRkpBrg'+cbxDlmRibu+'&fmTahun='+fmTahun;	
	//alert(dlgRkpBrg.uri);
	dlgRkpBrg.GetData();
}

