function ajax()
{
   //---------------------
   // Private Declarations
   //---------------------
   var _request = null;
   var _this = null;
       
   //--------------------
   // Public Declarations
   //--------------------
   this.GetResponseXML = function()
   {
      return (_request) ? _request.responseXML : null;
   }
       
   this.GetResponseText = function()
   {
      return (_request) ? _request.responseText : null;
   }
       
   this.GetRequestObject = function()
   {
      return _request;
   }
       
   this.InitializeRequest = function(Method, Uri)
   {
      _InitializeRequest();
      _this = this;
               
      switch (arguments.length)
      {
         case 2:
            _request.open(Method, Uri);
            break;
                               
         case 3:
            _request.open(Method, Uri, arguments[2]);
            break;
      }
               
      if (arguments.length >= 4) _request.open(Method, Uri, arguments[2], arguments[3]);
      this.SetRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
   }
       
   this.SetRequestHeader = function(Field, Value)
   {
      if (_request) _request.setRequestHeader(Field, Value);
   }
       
   this.Commit = function(Data)
   {
      if (_request) _request.send(Data);
   }
       
   this.Close = function()
   {
      if (_request) _request.abort();
   }
       
   //---------------------------
   // Public Event Declarations.
   //---------------------------
   this.OnUninitialize = function() { };
   this.OnLoading = function() { };
   this.OnLoaded = function() { };
   this.OnInteractive = function() { };
   this.OnSuccess = function() { };
   this.OnFailure = function() { };
       
   //---------------------------
   // Private Event Declarations
   //---------------------------
   function _OnUninitialize() { _this.OnUninitialize(); };
   function _OnLoading() { _this.OnLoading(); };
   function _OnLoaded() { _this.OnLoaded(); };
   function _OnInteractive() { _this.OnInteractive(); };
   function _OnSuccess() { _this.OnSuccess(); };
   function _OnFailure() { _this.OnFailure(); };

   //------------------
   // Private Functions
   //------------------
   function _InitializeRequest()
   {
      _request = _GetRequest();
      _request.onreadystatechange = _StateHandler;
   }
       
   function _StateHandler()
   {
      switch (_request.readyState)
      {
         case 0:
            window.setTimeout("void(0)", 100);
            _OnUninitialize();
            break;
                               
         case 1:
            window.setTimeout("void(0)", 100);
            _OnLoading();
            break;
                               
         case 2:
            window.setTimeout("void(0)", 100);
            _OnLoaded();
            break;
                       
         case 3:
            window.setTimeout("void(0)", 100);
            _OnInteractive();
            break;
                               
         case 4:
            if (_request.status == 200)
               _OnSuccess();
            else
               _OnFailure();
                                       
            return;
            break;
      }
   }
       
   function _GetRequest()
   {
      var obj;
               
      try
      {
         obj = new XMLHttpRequest();
      }
      catch (error)
      {
         try
         {
            obj = new ActiveXObject("Microsoft.XMLHTTP");
         }
         catch (error)
         {
            return null;
         }
      }
               
      return obj;
   }
}

function createParamSend( arrParams ){			
	var params='';
	for (var i=0; i < arrParams.length; i++){
		if (document.getElementById(arrParams[i]) != null){
			var el = document.getElementById(arrParams[i]); 
			if (el.type == 'checkbox'  ){
				if(el.checked){	el.value = 1;}else{	el.value = 0;}
			}
			params += '&'+arrParams[i]+'='+el.value;	
		}else{
			alert(arrParams[i]+' tidak ada!');
		}
		
	}
	return params;
}

AjaxObj = function(uri_, beforeSendRequest_) {
	this.uri = uri_;// 'tes_ajax_service.php';	
	//this.afterSucess = onAfterSucess_;
	this.beforeSendRequest = beforeSendRequest_;
    this.OnSuccess = function() {
        // document.getElementById('TextDiv').innerHTML = this.GetResponseText();
		
    }

    this.SendRequest = function() {
		eval(this.beforeSendRequest);
    	this.InitializeRequest('GET', this.uri);
        this.Commit(null);
     }
}
AjaxObj.prototype = new ajax();

/*
function createParamByForm( FormName ){			
	var params='';
	if (document.getElementById(FormName) ){
		
	
	var aForm = document.getElementById(FormName); 
	for(i=0; i< aForm.elements.length; i++){
		el = aForm.elements[i]; 
		if ((el.type=='checkbox' && el.checked) || el.type=='text' || el.type=='hidden' || el.type=='select-one' 
				|| el.tagName=='TEXTAREA' || (el.type=='radio' && el.checked) ){
			if( el.type=='checkbox' ){
				//if(el.checked && (el.value=='')){	el.value = 1;}else{	el.value = 0;}
			}			
			
			if (el.id!=''  ) {
				params += '&'+el.id+'='+el.value;		
				
			}else if(el.name !='' ){
				params += '&'+el.name+'='+el.value;		
			}
			//alert(i);
		}
		//alert (el.name+' '+el.tagName+' '+el.type);
		
	}
	}
	return params;
}
*/
function createParamByForm( FormName ){			
	var params='';
	if (document.getElementById(FormName) ){
		
	
	var aForm = document.getElementById(FormName); //alert(FormName);
		for(i=0; i< aForm.elements.length; i++){
			el = aForm.elements[i]; 
			if ((el.type=='checkbox' && el.checked) || 
					el.type=='text' || 
					el.type=='hidden' || 
					el.type=='select-one'|| 
					el.tagName=='TEXTAREA' || 
					(el.type=='radio' && el.checked) ||
					el.type=='password' )
			{
				
				if( el.type=='checkbox' ){
					//if(el.checked && (el.value=='')){	el.value = 1;}else{	el.value = 0;}
				}			
				
				var namestr = el.name;
				if(namestr.indexOf('[]')>=0 ){//handling array first
					params += '&'+el.name+'='+el.value;		
				}else{
					if (el.id!=''  ) {//handling id first
						params += '&'+el.id+'='+el.value;
					}else if(el.name !=''){//handling name
						params += '&'+el.name+'='+el.value;		
					}
				}
				//alert(el.name+' '+el.value+' '+params);
			}
			//alert (el.name+' '+el.tagName+' '+el.type);
		}
	}
	//alert(params);
	return params;
}


function tes_ajax(){ 
	
	
   
   myObject = new ajaxObj('tes_ajax_service.php' );     
   myObject.OnSuccess = function() {
         document.getElementById('TextDiv').innerHTML = this.GetResponseText();
   }
	myObject.SendRequest();
   
  // myObject.GetData();
   //alert('tes');
}

function AjxPost(url, form_, afterSucces_ , is_json_	){
	AjxObj = function(form_,afterSucces_, is_json_){
		this.afterSucces = afterSucces_;
		this.form= form_;
		this.is_json = is_json_;
		this.OnSuccess= function(){			
			//eval(this.afterSucces);
			
			if (this.is_json){
				var resp = eval('(' + this.GetResponseText() + ')');
				this.afterSucces(resp);	
			}else{
				this.afterSucces(this.GetResponseText());
			}
			
		}
		this.sendReq = function(url){
			this.InitializeRequest('POST', url); 
			var params = this.form != '' ? createParamByForm(this.form) : 'null';
			//alert(params);			
			this.Commit(params);
		}
	}
	AjxObj.prototype = new ajax();
	ajx = new AjxObj(form_, afterSucces_, is_json_);	
	ajx.sendReq(url);
}

function AjxQue( url, jsonobj_, form_, afterSucces_  ){
	AjxObj = function(form_,afterSucces_){
		this.afterSucces = afterSucces_;
		this.form= form_;
		//this.jsonobj = jsonobj_;
		this.OnSuccess = function(){
			var resp = eval('(' + this.GetResponseText() + ')'); //alert('tess');
			//if (resp.ErrMsg ==''){
				this.afterSucces(resp);	
			if(!resp.stop) {  resp.stop = false; }
			if(!resp.next) {
				resp.idprs++; //auto next proses jika next kosong
			}else{
				resp.idprs = resp.next ; //id ditentukan
			}
			
			if (resp.ErrMsg =='' && !resp.stop){ //if eror next proses di hentikan				
				//next ---	
				//resp.idprs++;			
				if (resp.idprs < resp.daftarProses.length){	
					this.sendReq( url, {	idprs: resp.idprs,	daftarProses: resp.daftarProses } );	
				}
				
			}								
		}
		this.sendReq = function(url_,jsonobj){
			var optstring = JSON.stringify( jsonobj ); 
			if (url_ == ''){
				var url = url_+'opt='+optstring; 	
			}else{
				var url = url_+'&opt='+optstring; 
			}			
			this.InitializeRequest('POST', url); 
			var Params = this.form != '' ? createParamByForm(this.form) : 'null';
			//alert(Params);
			this.Commit(Params);
		}
	}
	AjxObj.prototype = new ajax();
	ajx = new AjxObj(form_, afterSucces_, jsonobj_);
	ajx.sendReq(url,jsonobj_);
}

function getUrlVars(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function AjxPost2(url, form_, afterSucces_ , is_json_, withcover_, nameobj_	){
	AjxObj = function(form_,afterSucces_, is_json_, withcover_, nameobj_){
		this.afterSucces = afterSucces_;
		this.form= form_;
		this.is_json = is_json_;
		this.withcover = withcover_;
		this.nameobj = nameobj_;
		this.OnSuccess= function(){			
			//eval(this.afterSucces);
			if (this.withcover !=null && this.withcover == true){
				delElem(this.nameobj+'_cover');
				document.body.style.overflow='auto';
			}
			if (this.is_json){
				//console.info('resp'+this.GetResponseText());
				var resp = eval('(' + this.GetResponseText() + ')');
				//console.info('sukses'+this.afterSucces);
				eval(this.afterSucces);	
			}else{
				this.afterSucces(this.GetResponseText());
			}
			
		}
		this.sendReq = function(url){
			if (this.withcover !=null && this.withcover == true){
				document.body.style.overflow='hidden'; 
				addCoverPage2(this.nameobj+'_cover',1,true,false);	
			}
			this.InitializeRequest('POST', url); 
			var params = this.form != '' ? createParamByForm(this.form) : 'null';
			//alert(params);			
			this.Commit(params);
		}
	}
	AjxObj.prototype = new ajax();
	ajx = new AjxObj(form_, afterSucces_, is_json_, withcover_, nameobj_);	
	ajx.sendReq(url);
}
