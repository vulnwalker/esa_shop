<!-- Popup Tambah -->

<a href="#x" class="overlay" id="add_form"></a>
    <div class="popup" id="produk">
        <a class="close" href="index.php?page=produk"></a>
            <div class="judul-set"><i class="fa fa-product-hunt"></i> Tambah Produk</div>
             <?php echo"<form method='post' enctype='multipart/form-data' action='proses.php?act=add_produk'>"?>            
                <div class="wadah">             
                    <div>
                      <label>Nama Produk</label>
                      <input type="text" class="input" value=""  name="nama" placeholder="Nama Produk" required />
                    </div> 
                    <div>
                      <label>Jenis Produk</label>
                      <select name="jenis" class="input" required>
                        <option value="" selected disabled>- Pilih Salah Satu -</option>
                        <option value="Pria">Pria</option>
                        <option value="Wanita">Wanita</option>
                        <option value="PriaWanita">Pria dan Wanita</option>
                      </select>
                    </div>
                    <div>
                      <label>Info Produk</label>
                      <select name="merek" class="inputjenis" required>
                        <option value="" selected disabled>- Pilih Merk -</option>
                          <?php
                            $sqlmerk = query("SELECT *FROM merek ");
                            while ($data = fetch($sqlmerk)) {
                             echo "<option value='$data[id_merek]'>$data[nama_merek]</option>";
                              }
                             echo"</select>
                           <td>";
                          ?>
                      </select>
                      <select name="kategori" class="inputjenis" required>
                      <option value="" selected disabled>- Pilih Kategori -</option>
                          <?php
                            $sqlkategori = query("SELECT *FROM kategori");
                            while ($datak = fetch($sqlkategori)) {
                             echo "<option value='$datak[id_kategori]'>$datak[nama_kat]</option>";
                              }
                             echo"</select>
                           <td>";
                          ?>
                      </select>
                    </div>
                    <div>
                      <label>Harga</label>
                      <input type="text" class="input" name="harga" placeholder="Harga Produk" required />
                    </div> 
                    <div>
                      <label>Stok</label>
                      <input type="text" class="input" name="stok" placeholder="Stok Produk" required />
                    </div>             
                    <div>
                      <label style="float:left; width:125px;">Deskripsi</label>
                      <textarea class="input ket" placeholder="Deskripsi" name="deskripsi" required></textarea>
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
  $kode  = $_GET['id'];
  $query = query("SELECT *FROM produk WHERE id='$kode'");
  $data  = fetch($query);
?>

<a href="#x" class="overlay" id="edit_form"></a>
    <div class="popup" id="produk">
        <a class="close" href="index.php?page=produk"></a>
            <div class="judul-set"><i class="fa fa-product-hunt"></i> Tambah Produk</div>
             <?php echo"<form method='post' enctype='multipart/form-data' action='proses.php?act=edit_produk'>"?>            
                <div class="wadah">
                <?php echo'<input type="hidden" value="'.$data['id'].'" name="id">';?>             
                    <div>
                      <label>Nama Produk</label>
                      <?php echo'<input type="text" class="input" value="'.$data['nama_produk'].'"  name="nama" placeholder="Nama Produk" required />';?>
                    </div> 
                    <div>
                      <label>Jenis Produk</label>
                      <select name="jenis" class="input" required>
                        <?php
                        if ($data['jenis']=='Pria') {
                            echo"<option value=" . $data['jenis'] . " selected>" . $data['jenis'] . "</option>
                                 <option value='Wanita'>Wanita</option>
                                 <option value='Pria dan Wanita'>Pria dan Wanita</option>"; 
                        } elseif ($data['jenis']=='Wanita') {
                            echo"<option value=" . $data['jenis'] . " selected>" . $data['jenis'] . "</option>
                                 <option value='Pria'>Pria</option>
                                 <option value='Pria dan Wanita'>Pria dan Wanita</option>";                     
                        } else {
                            echo"<option value=" . $data['jenis'] . " selected>" . $data['jenis'] . "</option>
                                 <option value='Pria'>Pria</option>
                                 <option value='Wanita'>Wanita</option>";                      
                        }
                        ?>    
                      </select>
                    </div>
                    <div>
                      <label>Info Produk</label>
                      <select name="merek" class="inputjenis" required>
                        <option value="" selected disabled>- Pilih Merk -</option>
                          <?php
                            $sqlmerk = query("SELECT *FROM merek");
                            while ($datam = fetch($sqlmerk)) {
                              if ($data[id_merek] == $datam[id_merek]) {
                                echo"<option value=" . $data[id_merek] . " selected>" . $datam['nama_merek'] . "</option>";
                              } else {    
                             echo "<option value='$datam[id_merek]'>$datam[nama_merek]</option>";
                              }
                             } 
                             echo"</select>
                           <td>";
                          ?>
                      </select>
                      <select name="kategori" class="inputjenis" required>
                      <option value="" selected disabled>- Pilih Kategori -</option>
                          <?php
                            $sqlkategori = query("SELECT *FROM kategori");
                            while ($datak = fetch($sqlkategori)) {
                              if ($data[id_kategori] == $datak[id_kategori]) {
                                echo"<option value=" . $data[id_kategori] . " selected>" . $datak['nama_kat'] . "</option>";
                              } else {    
                             echo "<option value='$datak[id_kategori]'>$datak[nama_kat]</option>";
                              }
                             } 
                             echo"</select>
                           <td>";
                          ?>
                      </select>
                    </div>
                    <div>
                      <label>Harga</label>
                      <?php echo'<input type="text" class="input" value="'.$data[harga].'"name="harga" placeholder="Harga Produk" required />';?>
                    </div>            
                    <div>
                      <label style="float:left; width:125px;">Deskripsi</label>
                      <?php echo'<textarea class="input ket" placeholder="Deskripsi" name="deskripsi" required>
                      '.$data[deskripsi].' </textarea>';?>
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

<!-- stok -->
<?php
  $id  = $_GET['kd'];
  $sql = query("SELECT *FROM produk WHERE id='$id'");
  $row = fetch($sql);
?>

<a href="#x" class="overlay" id="stok_form"></a>
    <div class="popup">
        <a class="close" href="index.php?page=produk"></a>
            <div class="judul-set"><i class="fa fa-product-hunt"></i> Tambah Stok</div>
             <?php echo"<form method='post' action='proses.php?act=tambah_stok'>"?>            
                <div class="wadah">
                    <div>
                      <label>Id Produk</label>
                      <?php  echo"<input type='text' class='input not' name='id' value='$id' readonly />"?> 
                    </div>             
                    <div>
                      <label>Nama</label>
                      <?php  echo"<input type='text' class='input not' name='nama_produk' value='$row[nama]' readonly />"?> 
                    </div>            
                    <div>
                      <label>Stok Awal</label>
                       <?php  echo"<input type='text' class='input not' name='stok_awal' value='$row[stok]' readonly />"?> 
                    </div>
                    <div>
                      <label>Tambah Stok</label>
                      <input type="number" class="input" name="add" min="1" value="1" required />
                    </div>                           
                    <div>
                       <button type="submit" class="btn-save"><i class="fa fa-save"></i> Simpan</button>
                       <button type="reset"  class="btn-reset"><i class="fa fa-times"></i> Batal</button>
                    </div>
                </div>
            </form>
    
        </div>          
    </div>