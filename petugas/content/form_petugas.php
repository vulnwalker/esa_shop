<!-- Popup Tambah -->
<a href="#x" class="overlay" id="add_form"></a>
    <div class="popup" id="peg">
        <a class="close" href="index.php?page=petugas"></a>
            <div class="judul-set"><i class="fa fa-users"></i> Tambah Data Petugas</div>
        
            <?php echo"<form method='post' action='proses.php?act=add_petugas'>"?>            
                <div class="wadah">
                    <div>
                      <label>Nama</label>
                      <input type="text" class="input"  name="nama" placeholder="Nama" required />
                    </div>
                    <div>
                      <label>Email</label>
                      <input type="email" class="input"  name="email" placeholder="Email" required />
                    </div>               
                    <div>
                      <label>No Telepon</label>
                      <input type="tel" class="input" maxlength="12" pattern="[0-9]+" name="tlp" max-length="13" placeholder="No Telepon" required />
                    </div>
                    <div>
                      <label style="float:left; width:100px;">Alamat</label>
                      <textarea class="input ket" placeholder="Alamat" name="alamat" required=""></textarea>
                    </div>
                    <div>
                    <label>Password</label>
                      <input type='password' name='pass' id='pass1' class='input' placeholder='Password' required pattern='^(?=.*\d)[0-9a-zA-Z]{8,}$' title='Harus Minimal 8 character dan di sertai dengan anngka di belakannya'>    
                    </div>
                    <div>
                    <label>Re-Password</label>
                      <input type='password' name='pass1' id='pass2' onkeyup='checkPass(); return false;' class='input' placeholder='Confirm Password' required> <i id='confirmMessage'></i>
                    </div>             
                    <div>
                      <button type="submit" class="btn-save"><i class="fa fa-save"></i> Simpan</button>
                      <button type="reset"  class="btn-reset"><i class="fa fa-times"></i> Batal</button>
                    </div>
                </div>
            </form>          
    </div>