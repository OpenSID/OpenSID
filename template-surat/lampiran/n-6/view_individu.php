<page orientation="portrait" format="210x330" style="font-size: 10pt">

    <!-- Judul Lampiran -->
    <table align="right">
        <tr>
            <td colspan="10">Lampiran IX</td>
        </tr>
        <tr>
            <td colspan="10">Kepdirjen Bimas Islam Nomor 473 Tahun 2020</td>
        </tr>
        <tr>
            <td colspan="10">Tentang</td>
        </tr>
        <tr>
            <td colspan="10">Petunjuk Teknis Pelaksanaan Pencatatan Nikah</td>
        </tr>
        <tr>
            <?php for ($i = 0; $i < 48; $i++) : ?>
                <td>&nbsp;</td>
            <?php endfor; ?>
        </tr>
    </table>

    <!-- Model N6 -->
    <table align="right">
        <tr>
            <td><strong>Model N6</strong></td>
            <td colspan="30">&nbsp;</td>
        </tr>
    </table>

    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr><td colspan="48">&nbsp;</td></tr>
        <tr>
            <td colspan="17">KANTOR DESA/KELURAHAN</td>
            <td>: </td>
            <td colspan="30"><?= strtoupper($config['nama_desa']); ?></td>            
        </tr>
        <tr>
            <td colspan="17">KECAMATAN</td>
            <td>: </td>
            <td colspan="30"><?= strtoupper($config['nama_kecamatan']); ?></td>            
        </tr>
        <tr>
            <td colspan="17">KABUPATEN/KOTA</td>
            <td>:</td>
            <td colspan="30"><?= strtoupper($config['nama_kabupaten']); ?></td>            
        </tr>                
        <tr>
            <?php for ($i = 0; $i < 48; $i++): ?>
                <td>&nbsp;</td>
            <?php endfor; ?>
        </tr>
    </table>

    <p style="margin: 0; text-align: center;" class="title"><u>SURAT KETERANGAN KEMATIAN</u></p>
    <p style="margin: 0; text-align: center;">Nomor : <?= $format_surat ?></p>

    <p>Yang bertanda tangan dibawah ini menjelaskan dengan sesungguhnya bahwa : </p>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
         
        <tr>
            <td colspan="1">A. </td>
            <td colspan="20">1.  Nama lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong><?= $dataIndividuN6['nama_pasangan_terdahulu']; ?></strong></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Binti</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN6['binti_pasangan_terdahulu']; ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN6['nik_pasangan_terdahulu']; ?></td>            
        </tr>

         <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>: </td>
            <td colspan="27"><?= tgl_indo2( !empty($dataIndividuN6['tanggal_lahir_pasangan_terdahulu']) ? date('Y-m-d', strtotime($dataIndividuN6['tanggal_lahir_pasangan_terdahulu'])) : ''); ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="27"><?= $dataIndividuN6['tempat_lahir_pasangan_terdahulu']; ?></td>            
        </tr>

         <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN6['warga_negara_pasangan_terdahulu']; ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN6['agama_pasangan_terdahulu']; ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN6['pekerjaan_pasangan_terdahulu']; ?></td>            
        </tr>

         <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN6['tempat_tinggal_pasangan_terdahulu']; ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Telah meninggal dunia pada tanggal</td>
            <td>: </td>
            <td colspan="27"><?= tgl_indo2(!empty($dataIndividuN6['tanggal_meninggal_pasangan_terdahulu']) ? date('Y-m-d', strtotime($dataIndividuN6['tanggal_meninggal_pasangan_terdahulu'])) : ''); ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Di</td>
            <td>: </td>
            <td colspan="27"><?= $dataIndividuN6['tempat_meninggal_pasangan_terdahulu']; ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="27"><?= $dataIndividuN6['tempat_tinggal_pasangan_terdahulu']; ?></td>            
        </tr>

        <tr>
            <td colspan="48">Yang bersangkutan adalah suami / isteri*) dari :</td>
        </tr>

         <tr>
            <td colspan="1">B.</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>&nbsp;</td>
            <td colspan="27"><strong><?= $dataIndividuN6['nama']; ?></strong></td>            
        </tr>

         <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin/Binti</td>
            <td>&nbsp;</td>
            <td colspan="27"><?= $dataIndividuN6['bin']; ?></td>            
        </tr>


        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>&nbsp;</td>
            <td colspan="27"><?= $dataIndividuN6['nik']; ?></td>            
        </tr>


        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>&nbsp;</td>
            <td colspan="27"><?= $dataIndividuN6['tempatlahir']; ?> <?= tgl_indo2($dataIndividuN6['tanggallahir']); ?></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>&nbsp;</td>
            <td colspan="27"><?= $dataIndividuN6['warganegara']; ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>&nbsp;</td>
            <td colspan="27"><?= $dataIndividuN6['agama']; ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>&nbsp;</td>
            <td colspan="27"><?= $dataIndividuN6['pekerjaan']; ?></td>            
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>&nbsp;</td>
            <td colspan="27"><?= $dataIndividuN6['alamat']; ?></td>            
        </tr>

         
    </table>
    
    
    <p>Demikian surat pengantar ini dibuat dengan mengingat sumpah jabatan dan untuk
dipergunakan sebagaimana mestinya.</p>

    <!-- Penandatangan -->
    <br><br><br>
    <table class="disdukcapil" style="margin-top: -5px; border: 0px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="37">&nbsp;</td>
            <td colspan="10" class="tengah"><?= $config['nama_desa']; ?>, <?= tgl_indo(date('Y m d', time())) ?></td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="37">&nbsp;</td>
            <td colspan="10" class="tengah"><?= $penandatangan['atas_nama'] ?></td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10"><?= $qrcode ?? '' ?></td>
            <td colspan="37" class="tengah"><br><br><br><br></td>            
            <td colspan="2">&nbsp;</td>
        </tr>        
        <tr>
            <td colspan="37">&nbsp;</td>            
            <td colspan="10" class="tengah"><strong><?= $penandatangan['nama'] ?></strong></td>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>        
</page>