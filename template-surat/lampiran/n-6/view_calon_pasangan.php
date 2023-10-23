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
            <td colspan="30">[Nama_desA]</td>
        </tr>
        <tr>
            <td colspan="17">KECAMATAN</td>
            <td>: </td>
            <td colspan="30">[Nama_kecamataN]</td>
        </tr>
        <tr>
            <td colspan="17">KABUPATEN/KOTA</td>
            <td>:</td>
            <td colspan="30">[Nama_kabupateN]</td>
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
            <td colspan="27"><strong>[Nama_dsT]</strong></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Binti</td>
            <td>: </td>
            <td colspan="27">[Bin_suami_terdahulu_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>: </td>
            <td colspan="27">[Nik_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>: </td>
            <td colspan="27">[Tempatlahir_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="27">[Tanggallahir_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27">[Warga_negara_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27">[Agama_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27">[Pekerjaan_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>: </td>
            <td colspan="27">[Alamat_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Telah meninggal dunia pada tanggal</td>
            <td>: </td>
            <td colspan="27">[Tanggal_meninggal_suami_terdahulu_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Di</td>
            <td>: </td>
            <td colspan="27">[Tempat_meninggal_suami_terdahulu_dsT]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="27">[Alamat_dsT]</td>
        </tr>

        <tr>
            <td colspan="48">Yang bersangkutan adalah suami / isteri*) dari :</td>
        </tr>

        <tr>
            <td colspan="1">B.</td>
            <td colspan="20">1. Nama lengkap dan alias</td>
            <td>&nbsp;</td>
            <td colspan="27"><strong>[Nama_dcpW]</strong></td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Bin/Binti</td>
            <td>&nbsp;</td>
            <td colspan="27">[Nama_dapW]</td>
        </tr>


        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Nomor Induk Kependudukan</td>
            <td>&nbsp;</td>
            <td colspan="27">[Nik_dcpW]</td>
        </tr>


        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan tanggal lahir</td>
            <td>&nbsp;</td>
            <td colspan="27">[Tempatlahir_dcpW], [Tanggallahir_dcpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>&nbsp;</td>
            <td colspan="27">[Warga_negara_dcpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>&nbsp;</td>
            <td colspan="27">[Agama_dcpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>&nbsp;</td>
            <td colspan="27">[Pekerjaan_dcpW]</td>
        </tr>

        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Alamat</td>
            <td>&nbsp;</td>
            <td colspan="27">[Alamat_dcpW]</td>
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
            <td colspan="10" class="tengah">[Nama_desA], [TgL_surat]</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="37">&nbsp;</td>
            <td colspan="10" class="tengah">[Atas_namA]</td>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="10"><?= $qrcode ?? '' ?></td>
            <td colspan="37" class="tengah"><br><br><br><br></td>
            <td colspan="2">&nbsp;</td>
        </tr>        
        <tr>
            <td colspan="37">&nbsp;</td>
            <td colspan="10" class="tengah"><strong>[Nama_pamonG]</strong></td>
            <td colspan="2">&nbsp;</td>
        </tr>
    </table>        
</page>