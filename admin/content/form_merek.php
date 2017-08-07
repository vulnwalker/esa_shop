<?php
    $a   = query("select max(id_merek) as id_max from merek");
    $data= fetch($a);
    $s=$data['id_max'];
    $no_urut=(int) substr($s, 5, 4);
    $no_urut++;
    $inc="MRK";
    $merek=$inc . sprintf("%03s", $no_urut);
?>

<!-- Popup Tambah -->

<a href="#x" class="overlay" id="add_form"></a>
    <div class="popup">
        <a class="close" href="index.php?page=merek"></a>
            <div class="judul-set"><i class="fa fa-product-hunt"></i> Tambah Merek</div>
             <?php echo"<form method='post' enctype='multipart/form-data' action='proses.php?act=add_merek'>"?>            
                <div class="wadah">
                    <div>
                      <label>Id Merek</label>
                      <?php  echo"<input type='text' class='input not' name='id_merek' value='$merek' readonly />"?> 
                    </div>             
                    <div>
                      <label>Nama Merek</label>
                      <input type="text" class="input" value=""  name="nama" placeholder="Nama" required />
                    </div>            
                    <div>
                      <label style="float:left; width:100px;">Keterangan</label>
                      <textarea class="input ket" placeholder="Keterangan" name="keterangan" required></textarea>
                    </div>
                    <div>
                      <label>Foto</label>
                      <input type="file" name="gambar" required />
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
  $query = query("SELECT *FROM merek WHERE id_merek='$kode'");
  $data  = fetch($query);
?>

<a href="#x" class="overlay" id="edit_form"></a>
    <div class="popup">
        <a class="close" href="index.php?page=merek"></a>
            <div class="judul-set"><i class="fa fa-product-hunt"></i> Edit Merek</div>
             <?php echo"<form method='post' enctype='multipart/form-data' action='proses.php?act=edit_merek'>"?>            
                <div class="wadah">
                    <div>
                      <label>Id Merek</label>
                      <?php  echo"<input type='text' class='input not' name='id_merek' value='$data[id_merek]' readonly />"?> 
                    </div>             
                    <div>
                      <label>Nama Merek</label>
                      <?php echo'<input type="text" class="input" value="'.$data['nama_merek'].'"  name="nama" placeholder="Nama" required />';?>
                    </div>            
                    <div>
                      <label style="float:left; width:100px;">Keterangan</label>
                      <?php echo'<textarea class="input ket" placeholder="Keterangan" name="keterangan" required>'.$data[keterangan_merek].'</textarea>'?>
                    </div>
                    <div>
                      <label>Foto</label>
                      <input type="file" name="gambar">
                    </div>                           
                    <div>
                       <button type="submit" class="btn-save"><i class="fa fa-save"></i> Simpan</button>
                       <button type="reset"  class="btn-reset"><i class="fa fa-times"></i> Batal</button>
                    </div>
                </div>
            </form>
    
        </div>          
    </div>