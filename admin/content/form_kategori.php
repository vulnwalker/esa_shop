<?php
    $a=query("select max(id_kategori) as id_max from kategori");
    $data=fetch($a);
    $s=$data['id_max'];
    $no_urut=(int) substr($s, 5, 4);
    $no_urut++;
    $inc="KAT";
    $kategori=$inc . sprintf("%03s", $no_urut);
?>

<!-- Popup Tambah -->

<a href="#x" class="overlay" id="add_form"></a>
    <div class="popup">
        <a class="close" href="index.php?page=kategori"></a>
            <div class="judul-set"><i class="fa fa-product-hunt"></i> Tambah Kategori</div>
             <?php echo"<form method='post' action='proses.php?act=add_kategori'>"?>            
                <div class="wadah">
                    <div>
                      <label>Id Kategori</label>
                      <?php  echo"<input type='text' class='input not' name='id_kategori' value='$kategori' readonly />"?> 
                    </div>             
                    <div>
                      <label>Nama</label>
                      <input type="text" class="input" value=""  name="nama" placeholder="Nama" required />
                    </div>            
                    <div>
                      <label style="float:left; width:100px;">Keterangan</label>
                      <textarea class="input ket" placeholder="Keterangan" name="keterangan" required></textarea>
                    </div>                         
                    <div>
                       <button type="submit" class="btn-save"><i class="fa fa-save"></i> Simpan</button>
                       <button type="reset"  class="btn-reset"><i class="fa fa-times"></i> Batal</button>
                    </div>
                </div>
            </form>
    
        </div>          
    </div>

<!-- popup form #2 -->
<?php
  $kode=$_GET['id'];
  $query = query("SELECT *FROM kategori WHERE id_kategori='$kode'");
  $data  = fetch($query);
?>

<a href="#x" class="overlay" id="edit_form"></a>
    <div class="popup">
        <a class="close" href="index.php?page=kategori"></a>
            <div class="judul-set"><i class="fa fa-product-hunt"></i> Edit Kategori</div>
             <?php echo"<form method='post' action='proses.php?act=edit_kategori'>"?>            
                <div class="wadah">
                    <div>
                      <label>Id Kategori</label>
                      <?php  echo"<input type='text' class='input not' name='id_kategori' value='$data[id_kategori]' readonly />"?> 
                    </div>             
                    <div>
                      <label>Nama</label>
                      <?php echo'<input type="text" class="input" value="'.$data['nama_kat'].'"  name="nama" placeholder="Nama" required />';?>
                    </div>            
                    <div>
                      <label style="float:left; width:100px;">Keterangan</label>
                      <?php echo'<textarea class="input ket" placeholder="Keterangan" name="keterangan" required>'.$data['keterangan_kat'].'</textarea>'?>
                    </div>                          
                    <div>
                       <button type="submit" class="btn-save"><i class="fa fa-save"></i> Simpan</button>
                       <button type="reset"  class="btn-reset"><i class="fa fa-times"></i> Batal</button>
                    </div>
                </div>
            </form>
    
        </div>          
    </div>