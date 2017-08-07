
var UnloadHandler = function(){
	

	this.init = function(){
		$(window).unload(function() {
	  		alert('Handler for .unload() called.');
		});	
	}
	
}
UnloadHandler.init();