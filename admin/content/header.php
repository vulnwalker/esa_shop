<?php
session_start();

$sql  = query("SELECT *FROM user WHERE username='$_SESSION[user]'");
$data = fetch($sql);

?>

 <header class="header clearfix">
    <button type="button" id="toggleMenu" class="toggle_menu">
      <i class="fa fa-bars"></i>
    </button>
    <div class="logo"><i class="fa fa fa-opencart"></i><b style="color:#11C0FF;">ESA</b> Shop</div>
    
    <button id="collapse_menu" class="collapse_menu">
      <i class="collapse_menu--icon  fa fa-bars"></i>
    </button>
	

<div class="jam"> 
<i class="fa fa-calendar"></i>&nbsp;
  <?php print date('d F Y'); ?>
  &nbsp;&nbsp;&nbsp;
<i class="fa fa-clock-o"></i>&nbsp;
<span id="clock">
  <?php print date('H:i:s'); ?> 
</span>
</div>

<div align="right">
	<?php echo"
	<button id='tombol'> <img src='data:image/png;base64,$data[foto]'> <span>$data[nama_lengkap]</span> </button>
		<div class='box'>
		<div align='center'>
		<div class='fprofile'>
          <img src='data:image/png;base64,$data[foto]'>
		<div>$data[level] </div>
		</div>
			<div class='bawah-prof'>
			<a href='index.php?page=profile'><button class='btn-logout'><i class='fa fa-bars '></i> Profile </button></a>
                &nbsp;&nbsp;&nbsp;
			<a href='proses.php?act=logout'><button class='btn-logout'><i class='fa fa-sign-out '></i>
			 Sign Out</button></a>
			</div>	
		</div>
		</div>";	
	?>
    </div>
</header>

<script>  
  $(document).ready(function() {
    $('#tombol').click(function() {
      $('.box').toggle();
    });
  });

    //set timezone
<?php date_default_timezone_set('Asia/Jakarta'); ?>
    var serverTime = new Date(<?php print date('Y, m, d, H, i, s, 0'); ?>);
    var clientTime = new Date();
    var Diff = serverTime.getTime() - clientTime.getTime();    
    
    function displayServerTime(){
        
        var clientTime = new Date();
        var time = new Date(clientTime.getTime() + Diff);
        var sh = time.getHours().toString();
        var sm = time.getMinutes().toString();
        var ss = time.getSeconds().toString();
        
        document.getElementById("clock").innerHTML = (sh.length==1?"0"+sh:sh) + ":" + (sm.length==1?"0"+sm:sm) + ":" + (ss.length==1?"0"+ss:ss);
    }
</script>
