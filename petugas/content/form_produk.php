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
                            $sqlmerk = "SELECT *FROM merek ";
                            $hasilmerk = mysql_query($sqlmerk);
                            while ($data = mysql_fetch_row($hasilmerk)) {
                             echo "<option value='$data[0]'>$data[1]</option>";
                              }
                             echo"</select>
                           <td>";
                          ?>
                      </select>
                      <select name="kategori" class="inputjenis" required>
                      <option value="" selected disabled>- Pilih Kategori -</option>
                          <?php
                            $sqlkategori = "SELECT *FROM kategori ";
                            $hasilkategori = mysql_query($sqlkategori);
                            while ($datak = mysql_fetch_row($hasilkategori)) {
                             echo "<option value='$datak[0]'>$datak[1]</option>";
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
  $kode=$_GET['id'];
  $query = mysql_query("SELECT *FROM produk WHERE id='$kode'");
  $data = mysql_fetch_array($query);
?>

<a href="#x" class="overlay" id="edit_form"></a>
    <div class="popup" id="produk">
        <a class="close" href="index.php?page=produk"></a>
            <div class="judul-set"><i class="fa fa-product-hunt"></i> Tambah Produk</div>
             <?php echo"<form method='post' enctype='multipart/form-data' action='proses.php?act=edit_produk'>"?>            
                <div class="wadah">
                <?php echo'<input type="hidden" value="'.$data[0].'" name="id">';?>             
                    <div>
                      <label>Nama Produk</label>
                      <?php echo'<input type="text" class="input" value="'.$data[1].'"  name="nama" placeholder="Nama Produk" required />';?>
                    </div> 
                    <div>
                      <label>Jenis Produk</label>
                      <select name="jenis" class="input" required>
                        <?php
                        if ($data[2]=='Pria') {
                            echo"<option value=" . $data[2] . " selected>" . $data['2'] . "</option>
                                 <option value='Wanita'>Wanita</option>
                                 <option value='Pria dan Wanita'>Pria dan Wanita</option>"; 
                        } elseif ($data[2]=='Wanita') {
                            echo"<option value=" . $data[2] . " selected>" . $data['2'] . "</option>
                                 <option value='Pria'>Pria</option>
                                 <option value='Pria dan Wanita'>Pria dan Wanita</option>";                     
                        } else {
                            echo"<option value=" . $data[2] . " selected>" . $data['2'] . "</option>
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
                            $sqlmerk = "SELECT *FROM merek ";
                            $hasilmerk = mysql_query($sqlmerk);
                            while ($datam = mysql_fetch_row($hasilmerk)) {
                              if ($data[3] == $datam[0]) {
                                echo"<option value=" . $data[3] . " selected>" . $datam['1'] . "</option>";
                              } else {    
                             echo "<option value='$datam[0]'>$datam[1]</option>";
                              }
                             } 
                             echo"</select>
                           <td>";
                          ?>
                      </select>
                      <select name="kategori" class="inputjenis" required>
                      <option value="" selected disabled>- Pilih Kategori -</option>
                          <?php
                            $sqlkategori = "SELECT *FROM kategori ";
                            $hasilkategori = mysql_query($sqlkategori);
                            while ($datak = mysql_fetch_row($hasilkategori)) {
                              if ($data[4] == $datak[0]) {
                                echo"<option value=" . $data[4] . " selected>" . $datak['1'] . "</option>";
                              } else {    
                             echo "<option value='$datak[0]'>$datak[1]</option>";
                              }
                             } 
                             echo"</select>
                           <td>";
                          ?>
                      </select>
                    </div>
                    <div>
                      <label>Harga</label>
                      <?php echo'<input type="text" class="input" value="'.$data[5].'"name="harga" placeholder="Harga Produk" required />';?>
                    </div>            
                    <div>
                      <label style="float:left; width:125px;">Deskripsi</label>
                      <?php echo'<textarea class="input ket" placeholder="Deskripsi" name="deskripsi" required>
                      '.$data[8].' </textarea>';?>
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
  $id=$_GET['kd'];
  $sql = mysql_query("SELECT *FROM produk WHERE id='$id'");
  $row = mysql_fetch_array($sql);
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