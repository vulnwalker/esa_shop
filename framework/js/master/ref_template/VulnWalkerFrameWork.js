function setValCurrentTextbox(obj){
var idnya = obj.id;
var valuenya = obj.value;



		$.ajax({
		  url: 'pages.php?Pg=ref_template&tipe=updateTempDetail',
		  type : 'POST',
		  data :{ ID : idnya, VALUE : valuenya },
		  success: function(data) {
					var resp = eval('(' + data + ')');
					document.getElementById('tempatJumlah').textContent = resp.content.total;
			  }
		});


}