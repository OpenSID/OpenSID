<page orientation="portrait" format="210x330" style="font-size: 10pt">

    <!-- Judul Lampiran -->
    <table align="right">
        <tr>
            <td colspan="10">Lampiran V</td>
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

    <!-- Model N4 -->
    <table align="right">
        <tr>
            <td><strong>Model N1</strong></td>
            <td colspan="30">&nbsp;</td>
        </tr>
    </table>

    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
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
            <?php for ($i = 0; $i < 48; $i++) : ?>
                <td>&nbsp;</td>
            <?php endfor; ?>
        </tr>
    </table>

    <p style="margin: 0; text-align: center;" class="title"><u>PENGANTAR NIKAH</u></p>
    <p style="margin: 0; text-align: center;">Nomor : <?= $format_surat ?></p>

    <p>Yang bertanda tangan dibawah ini menjelaskan dengan sesungguhnya bahwa : </p>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">1. Nama</td>
            <td>: </td>
            <td colspan="27"><strong><?= strtoupper($dataCalonSuamiN1['nama']); ?></strong></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">2. Nomor Induk Kependudukan (NIK)</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['nik']; ?></td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">3. Jenis Kelamin</td>
            <td>: </td>
            <td colspan="27">Laki - laki</td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">4. Tempat dan Tanggal Lahir</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['tempatlahir'] . ', ' . tgl_indo2( !empty($dataCalonSuamiN1['tanggallahir']) ? date('Y-m-d', strtotime($dataCalonSuamiN1['tanggallahir'])) : ''); ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">5. Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['warganegara']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">6. Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['agama']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">7. Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['pekerjaan']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">8. Pendidikan Terakhir</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['pendidikan']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">9. Bin/Binti</td>
            <td>: </td>
            <td colspan="27"><?= ($dataCalonSuamiN1['bin']); ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">10. Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['alamat_wilayah']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">11. Status Perkawinan</td>
            <td>: </td>
            <td colspan="27"></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">
                <table>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td>a. </td>
                        <td>Jejaka, Duda atau beristri ke <?= ((int) $dataCalonSuamiN1['jumlah_pasangan_terdahulu'] + 1) ?></td>
                    </tr>
                </table>
            </td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['status_kawin']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">
                <table>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td>b. </td>
                        <td>Perempuan : Perawan, Janda</td>
                    </tr>
                </table>
            </td>
            <td>: </td>
            <td colspan="27">-</td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">12. Nama isteri / suami terdahulu</td>
            <td>: </td>
            <td colspan="27"><?= $input['nama_pasangan_terdahulu'] ?></td>

        </tr>
    </table>

    <p>Adalah benar-benar anak dari perkawinan seorang pria :</p>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Nama Lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong><?= strtoupper($dataCalonSuamiN1['nama_ayah']); ?></strong></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Nomor Induk Kependudukan (NIK)</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['ayah_nik']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Tempat dan Tanggal Lahir</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['tempat_lahir_ayah'] . ', ' . tgl_indo2( !empty($dataCalonSuamiN1['tanggal_lahir_ayah']) ? date('Y-m-d', strtotime($dataCalonSuamiN1['tanggal_lahir_ayah'])) : ''); ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['warganegara_ayah']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['agama_ayah']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['pekerjaan_ayah']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['alamat_ayah']; ?></td>

        </tr>
    </table>
    <p>dengan seorang wanita :</p>
    <table id="kop" class="disdukcapil">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="48">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Nama Lengkap dan alias</td>
            <td>: </td>
            <td colspan="27"><strong><?= strtoupper($dataCalonSuamiN1['nama_ibu']); ?></strong></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Nomor Induk Kependudukan (NIK)</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['ibu_nik']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Tempat dan Tanggal Lahir</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['tempat_lahir_ibu'] . ', ' . tgl_indo2(!empty($dataCalonSuamiN1['tanggal_lahir_ibu']) ?  date('Y-m-d', strtotime($dataCalonSuamiN1['tanggal_lahir_ibu'])) : ''); ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Kewarganegaraan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['warganegara_ibu']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Agama</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['agama_ibu']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Pekerjaan</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['pekerjaan_ibu']; ?></td>

        </tr>
        <tr>
            <td colspan="1">&nbsp;</td>
            <td colspan="20">Alamat</td>
            <td>: </td>
            <td colspan="27"><?= $dataCalonSuamiN1['alamat_ibu']; ?></td>

        </tr>
    </table>
    <p>Demikian surat pengantar ini dibuat dengan mengingat sumpah jabatan dan untuk
        dipergunakan sebagaimana mestinya.</p>

    <!-- Penandatangan -->
    <br><br><br>
    <table class="disdukcapil" style="margin-top: -5px; border: 0px;">
        <col span="48" style="width: 2.0833%;">
        <tr>
            <td colspan="32">&nbsp;</td>
            <td colspan="20" class="tengah"><?= $config['nama_desa']; ?>, <?= tgl_indo(date('Y m d', time())) ?></td>
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