 <nav class="vertical_nav">
    <ul id="js-menu" class="menu"> 
      <li class="menu--item">
       <?php echo"<a href='index.php' class='menu--link' title='Item 2'>";?>
          <i class="menu--icon  fa fa-fw fa-home"></i>
          <span class="menu--label">Dasboard</span>
        </a>
      </li>

      <li class="menu--item">
       <?php echo"<a href='index.php?page=transaksi' class='menu--link' title='Item 2'>";?>
          <i class="menu--icon  fa fa-fw fa-credit-card"></i>
          <span class="menu--label">Data Transaksi</span>
        </a>
      </li>

      <li class="menu--item  menu--item__has_sub_menu">
        <label class="menu--link" title="Item 4">
          <i class="menu--icon  fa fa-fw fa-product-hunt"></i>
          <span class="menu--label">Data Produk</span>
        </label>

        <ul class="sub_menu">
          <li class="sub_menu--item">
            <?php echo"<a href='index.php?page=kategori' class='sub_menu--link'>Kategori</a>";?>
          </li>
          <li class="sub_menu--item">
            <?php echo"<a href='index.php?page=merek' class='sub_menu--link'>Merek</a>";?>
          </li>
          <li class="sub_menu--item">
            <?php echo"<a href='index.php?page=produk' class='sub_menu--link'>Produk</a>";?>
          </li>
        </ul>
      </li>

      <li class="menu--item  menu--item__has_sub_menu">
        <label class="menu--link" title="Item 4">
          <i class="menu--icon  fa fa-fw fa-users"></i>
          <span class="menu--label">Data User</span>
        </label>

        <ul class="sub_menu">
          <li class="sub_menu--item">
            <?php echo"<a href='index.php?page=member' class='sub_menu--link'>Member</a>";?>
          </li>
        </ul>
      </li>

      <li class="menu--item  menu--item__has_sub_menu">
        <label class="menu--link" title="Item 4">
          <i class="menu--icon  fa fa-fw fa-print"></i>
          <span class="menu--label">Laporan</span>
        </label>

        <ul class="sub_menu">
          <li class="sub_menu--item">
            <?php echo"<a href='index.php?page=lap_transaksi' class='sub_menu--link'>Laporan Transaksi</a>";?>
          </li>
          <li class="sub_menu--item">
            <?php echo"<a href='index.php?page=lap_barang' class='sub_menu--link'>Laporan Produk</a>";?>
          </li>
        </ul>
      </li>

    </ul>

  </nav>