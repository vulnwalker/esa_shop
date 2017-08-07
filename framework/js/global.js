
function showElement(id, left,top,otherStyle,innerHtml_){
	div = document.getElementById(id);
	div.setAttribute('style',
		'left:'+left+'; \
		top:'+top+'; \
		visibility:visible;'+otherStyle);
	if (innerHtml_ != '' ){
		div.innerHTML = innerHtml_
	}
}

function formCreateCaption(idForm,idFormCaption,caption,formOnClose, isDrag){
	var styleCursorMove = '';
	if (isDrag){
		styleCursorMove = "style='cursor:move;'	onmousedown=\"formCaptionOnMouseDown(event,'"+idForm+"','"+idFormCaption+"')\" ";
	}
	var str =	
		"<div class=pnlHeader id=\""+idFormCaption+"\" "+styleCursorMove+
	 	" >"+
		"<div class=labelHeader id='idlblHeader' "+
		"onmousedown=\"formCaptionOnMouseDown(event,'"+idForm+"','idlblHeader')\" >"+
		caption+
		'</div>'+		
	'<div id="'+idForm+'-sToolbar" style="float:right;">'+
	'<div class=toolbarButton title="Close">'+
	"<img src=\"img/close16.png\" alt=\"Close\" id=\"dlgClose\" style=\"float:right;cursor:pointer;\" "+
	 " onclick=\""+formOnClose+"\" width='16px' height='16px'> </img>  "+		
	'</div></div></div>';
	return str;
}

function dlgCreate( idForm ){
	if (document.getElementById(idForm)==null){
		document.body.innerHTML += '<div id ="'+idForm+'"></div>';
	}
}

function showForm3(idForm,idFormCaption,left,top,fmCaption, sFnClose, isDrag){
		dlgCreate(idForm);
		var dlg = document.getElementById(idForm);		
		
		dlg.setAttribute('style','z-index:99;width:230px;left:'+left+';top:'+top);
		
		var sContain = '<table id=\''+idForm+'-area\' class=tbl_nav style="width:100%;Height:100%" cellspacing="0" cellpadding="0">';
		var sHeader = '<thead><tr><td>'+
			formCreateCaption(idForm,idFormCaption,fmCaption,sFnClose, isDrag)+'</td></tr></thead>';
		
		var sBody = '<tbody><tr><td>'+
			'<div id="'+idForm+'-sBodyContent"></div>'+
			'</td></tr></tbody>';		
		
		var sFoot = '<tFoot><tr><td>'+
			'<div id="'+idForm+'-sFooterContent"></div>'+			
			'</td></tr></tFoot>';			
		var sContainEnd =  '<table>';
		
		
		var str = sContain+sHeader+sBody+sFoot+sContainEnd;
		showElement(idForm,left,top,'',str);
	return dlg;
}



function getSenderFromE(e){
	if (e == null) { e = window.event;}
		var sender = (typeof( window.event ) != "undefined" ) ? e.srcElement : e.target;
		return sender;
}

function cancelbbl(event){
	//cancel bubble : disable event selanjutnya
			if (!event) var event = window.event;
    		event.cancelBubble = true;
    		if (event.stopPropagation) event.stopPropagation();
		}
		
function getBrowserWidth(){
	var x,y;
	document.body.style.overflow='hidden';
	if (self.innerHeight) { // all except Explorer
		x = self.innerWidth;
		//y = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {	// Explorer 6 Strict Mode

		x = document.documentElement.clientWidth;
		//y = document.documentElement.clientHeight;
	} else if (document.body) {// other Explorers

		x = document.body.clientWidth;
		//y = document.body.clientHeight;
	}

	//alert(x+' '+y);
	document.body.style.overflow='auto';
	return x;
}

function getBrowserHeight(){
	var x,y;
	document.body.style.overflow='hidden';
	if (self.innerHeight) { // all except Explorer
		x = self.innerWidth;
		y = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {	// Explorer 6 Strict Mode

		x = document.documentElement.clientWidth;
		y = document.documentElement.clientHeight;
	} else if (document.body) {// other Explorers

		x = document.body.clientWidth;
		y = document.body.clientHeight;
	}
	document.body.style.overflow='auto';
	return y;
}

function isIPad(){
    return navigator.platform == "iPad";
}