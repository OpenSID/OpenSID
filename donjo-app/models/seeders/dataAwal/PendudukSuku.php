<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class PendudukSuku extends CI_Model
{
    public function getData()
    {
        return [
            [

                'id'        => 1,
                'suku'      => 'Aceh',
                'deskripsi' => 'Aceh',
            ],
            [

                'id'        => 2,
                'suku'      => 'Alas',
                'deskripsi' => 'Aceh',
            ],
            [

                'id'        => 3,
                'suku'      => 'Alor',
                'deskripsi' => 'NTT',
            ],
            [

                'id'        => 4,
                'suku'      => 'Ambon',
                'deskripsi' => 'Ambon',
            ],
            [

                'id'        => 5,
                'suku'      => 'Ampana',
                'deskripsi' => 'Sulawesi Tengah',
            ],
            [

                'id'        => 6,
                'suku'      => 'Anak Dalam',
                'deskripsi' => 'Jambi',
            ],
            [

                'id'        => 7,
                'suku'      => 'Aneuk Jamee',
                'deskripsi' => 'Aceh',
            ],
            [
                'id'        => 8,
                'suku'      => 'Arab: Orang Hadhrami',
                'deskripsi' => 'Arab: Orang Hadhrami',
            ],
            [

                'id'        => 9,
                'suku'      => 'Aru',
                'deskripsi' => 'Maluku',
            ],
            [

                'id'        => 10,
                'suku'      => 'Asmat',
                'deskripsi' => 'Papua',
            ],
            [
                'id'        => 11,
                'suku'      => 'Bare’e',
                'deskripsi' => 'Bare’e di Kabupaten Tojo Una-Una Tojo dan Tojo Barat',
            ],
            [
                'id'        => 12,
                'suku'      => 'Banten',
                'deskripsi' => 'Banten di Banten',
            ],
            [
                'id'        => 13,
                'suku'      => 'Besemah',
                'deskripsi' => 'Besemah di Sumatera Selatan',
            ],
            [
                'id'        => 14,
                'suku'      => 'Bali',
                'deskripsi' => "Bali\u{a0}di Bali terdiri dari: Suku Bali Majapahit di sebagian besar Pulau Bali; Suku Bali Aga di Karangasem dan Kintamani",
            ],
            [
                'id'        => 15,
                'suku'      => 'Balantak',
                'deskripsi' => 'Balantak di Sulawesi Tengah',
            ],
            [
                'id'        => 16,
                'suku'      => 'Banggai',
                'deskripsi' => 'Banggai di Sulawesi Tengah (Kabupaten Banggai Kepulauan)',
            ],
            [

                'id'        => 17,
                'suku'      => 'Baduy',
                'deskripsi' => "Baduy\u{a0}di Banten",
            ],
            [
                'id'        => 18,
                'suku'      => 'Bajau',
                'deskripsi' => 'Bajau di Kalimantan Timur',
            ],
            [
                'id'        => 19,
                'suku'      => 'Banjar',
                'deskripsi' => 'Banjar di Kalimantan Selatan',
            ],
            [
                'id'        => 20,
                'suku'      => 'Batak',
                'deskripsi' => 'Sumatera Utara',
            ],
            [
                'id'        => 21,
                'suku'      => 'Batak Karo',
                'deskripsi' => 'Sumatera Utara',
            ],
            [
                'id'        => 22,
                'suku'      => 'Mandailing',
                'deskripsi' => 'Sumatera Utara',
            ],
            [

                'id'        => 23,
                'suku'      => 'Angkola',
                'deskripsi' => 'Sumatera Utara',
            ],
            [

                'id'        => 24,
                'suku'      => 'Toba',
                'deskripsi' => 'Sumatera Utara',
            ],
            [

                'id'        => 25,
                'suku'      => 'Pakpak',
                'deskripsi' => 'Sumatera Utara',
            ],
            [
                'id'        => 26,
                'suku'      => 'Simalungun',
                'deskripsi' => 'Sumatera Utara',
            ],
            [

                'id'        => 27,
                'suku'      => 'Batin',
                'deskripsi' => 'Batin di Jambi',
            ],
            [
                'id'        => 28,
                'suku'      => 'Bawean',
                'deskripsi' => 'Bawean di Jawa Timur (Gresik)',
            ],
            [
                'id'        => 29,
                'suku'      => 'Bentong',
                'deskripsi' => 'Bentong di Sulawesi Selatan',
            ],
            [
                'id'        => 30,
                'suku'      => 'Berau',
                'deskripsi' => 'Berau di Kalimantan Timur (kabupaten Berau)',
            ],
            [
                'id'        => 31,
                'suku'      => 'Betawi',
                'deskripsi' => 'Betawi di Jakarta',
            ],
            [
                'id'        => 32,
                'suku'      => 'Bima',
                'deskripsi' => 'Bima NTB (kota Bima)',
            ],
            [
                'id'        => 33,
                'suku'      => 'Boti',
                'deskripsi' => 'Boti di kabupaten Timor Tengah Selatan',
            ],
            [
                'id'        => 34,
                'suku'      => 'Bolang Mongondow',
                'deskripsi' => 'Bolang Mongondow di Sulawesi Utara (Kabupaten Bolaang Mongondow)',
            ],
            [
                'id'        => 35,
                'suku'      => 'Bugis',
                'deskripsi' => "Bugis\u{a0}di Sulawesi Selatan: Orang Bugis Pagatan di Kalimantan Selatan, Kusan Hilir, Tanah Bumbu",
            ],
            [
                'id'        => 36,
                'suku'      => 'Bungku',
                'deskripsi' => 'Bungku di Sulawesi Tengah (Kabupaten Morowali)',
            ],
            [
                'id'        => 37,
                'suku'      => 'Buru',
                'deskripsi' => 'Buru di Maluku (Kabupaten Buru)',
            ],
            [
                'id'        => 38,
                'suku'      => 'Buol',
                'deskripsi' => 'Buol di Sulawesi Tengah (Kabupaten Buol)',
            ],
            [
                'id'        => 39,
                'suku'      => 'Bulungan ',
                'deskripsi' => 'Bulungan di Kalimantan Timur (Kabupaten Bulungan)',
            ],
            [
                'id'        => 40,
                'suku'      => 'Buton',
                'deskripsi' => 'Buton di Sulawesi Tenggara (Kabupaten Buton dan Kota Bau-Bau)',
            ],
            [
                'id'        => 41,
                'suku'      => 'Bonai',
                'deskripsi' => 'Bonai di Riau (Kabupaten Rokan Hilir)',
            ],
            [
                'id'        => 42,
                'suku'      => 'Cham ',
                'deskripsi' => 'Cham di Aceh',
            ],
            [
                'id'        => 43,
                'suku'      => 'Cirebon ',
                'deskripsi' => 'Cirebon di Jawa Barat (Kota Cirebon)',
            ],
            [
                'id'        => 44,
                'suku'      => 'Damal',
                'deskripsi' => 'Damal di Mimika',
            ],
            [
                'id'        => 45,
                'suku'      => 'Dampeles',
                'deskripsi' => 'Dampeles di Sulawesi Tengah',
            ],
            [
                'id'        => 46,
                'suku'      => 'Dani ',
                'deskripsi' => 'Dani di Papua (Lembah Baliem)',
            ],
            [
                'id'        => 47,
                'suku'      => 'Dairi',
                'deskripsi' => 'Dairi di Sumatera Utara',
            ],
            [
                'id'        => 48,
                'suku'      => 'Daya ',
                'deskripsi' => 'Daya di Sumatera Selatan',
            ],
            [
                'id'        => 49,
                'suku'      => 'Dayak',
                'deskripsi' => "Dayak\u{a0}terdiri dari: Suku Dayak Ahe di Kalimantan Barat; Suku Dayak Bajare di Kalimantan Barat; Suku Dayak Damea di Kalimantan Barat; Suku Dayak Banyadu di Kalimantan Barat; Suku Bakati di Kalimantan Barat; Suku Punan di Kalimantan Tengah; Suku Kanayatn di Kalimantan Barat; Suku Dayak Krio di Kalimantan Barat (Ketapang], Suku Dayak Sungai Laur di Kalimantan Barat (Ketapang], Suku Dayak Simpangh di Kalimantan Barat (Ketapang], Suku Iban di Kalimantan Barat; Suku Mualang di Kalimantan Barat (Sekada",
            ],
            [
                'id'        => 50,
                'suku'      => 'Dompu',
                'deskripsi' => 'Dompu NTB (Kabupaten Dompu)',
            ],
            [
                'id'        => 51,
                'suku'      => 'Donggo',
                'deskripsi' => 'Donggo, Bima',
            ],
            [
                'id'        => 52,
                'suku'      => 'Dongga',
                'deskripsi' => 'Donggala di Sulawesi Tengah',
            ],
            [
                'id'        => 53,
                'suku'      => 'Dondo ',
                'deskripsi' => 'Dondo di Sulawesi Tengah (Kabupaten Toli-Toli)',
            ],
            [
                'id'        => 54,
                'suku'      => 'Duri',
                'deskripsi' => 'Duri Terletak di bagian utara Kabupaten Enrekang berbatasan dengan Kabupaten Tana Toraja, meliputi tiga kecamatan induk Anggeraja, Baraka, dan Alla di Sulawesi Selatan',
            ],
            [
                'id'        => 55,
                'suku'      => 'Eropa ',
                'deskripsi' => 'Eropa (orang Indo, peranakan Eropa-Indonesia, atau etnik Mestizo)',
            ],
            [
                'id'        => 56,
                'suku'      => 'Flores',
                'deskripsi' => 'Flores di NTT (Flores Timur)',
            ],
            [
                'id'        => 57,
                'suku'      => 'Lamaholot',
                'deskripsi' => 'Lamaholot, Flores Timur, terdiri dari: Suku Wandan, di Solor Timur, Flores Timur; Suku Kaliha, di Solor Timur, Flores Timur; Suku Serang Gorang, di Solor Timur, Flores Timur; Suku Lamarobak, di Solor Timur, Flores Timur; Suku Atanuhan, di Solor Timur, Flores Timur; Suku Wotan, di Solor Timur, Flores Timur; Suku Kapitan Belen, di Solor Timur, Flores Timur',
            ],
            [
                'id'        => 58,
                'suku'      => 'Gayo',
                'deskripsi' => 'Gayo di Aceh (Gayo Lues Aceh Tengah Bener Meriah Aceh Tenggara Aceh Timur Aceh Tamiang)',
            ],
            [
                'id'        => 59,
                'suku'      => 'Gorontalo',
                'deskripsi' => 'Gorontalo di Gorontalo (Kota Gorontalo)',
            ],
            [
                'id'        => 60,
                'suku'      => 'Gumai ',
                'deskripsi' => 'Gumai di Sumatera Selatan (Lahat)',
            ],
            [
                'id'        => 61,
                'suku'      => 'India',
                'deskripsi' => 'India, terdiri dari: Suku Tamil di Aceh, Sumatera Utara, Sumatera Barat, dan DKI Jakarta; Suku Punjab di Sumatera Utara, DKI Jakarta, dan Jawa Timur; Suku Bengali di DKI Jakarta; Suku Gujarati di DKI Jakarta dan Jawa Tengah; Orang Sindhi di DKI Jakarta dan Jawa Timur; Orang Sikh di Sumatera Utara, DKI Jakarta, dan Jawa Timur',
            ],
            [
                'id'        => 62,
                'suku'      => 'Jawa',
                'deskripsi' => 'Jawa di Jawa Tengah, Jawa Timur, DI Yogyakarta',
            ],
            [
                'id'        => 63,
                'suku'      => 'Tengger',
                'deskripsi' => "Tengger\u{a0}di Jawa Timur (Probolinggo, Pasuruan, dan Malang)",
            ],
            [
                'id'        => 64,
                'suku'      => 'Osing ',
                'deskripsi' => 'Osing di Jawa Timur (Banyuwangi)',
            ],
            [
                'id'        => 65,
                'suku'      => 'Samin ',
                'deskripsi' => 'Samin di Jawa Tengah (Purwodadi)',
            ],
            [
                'id'        => 66,
                'suku'      => 'Bawean',
                'deskripsi' => 'Bawean di Jawa Timur (Pulau Bawean)',
            ],
            [
                'id'        => 67,
                'suku'      => 'Jambi ',
                'deskripsi' => 'Jambi di Jambi (Kota Jambi)',
            ],
            [
                'id'        => 68,
                'suku'      => 'Jepang',
                'deskripsi' => 'Jepang di DKI Jakarta, Jawa Timur, dan Bali',
            ],
            [
                'id'        => 69,
                'suku'      => 'Kei',
                'deskripsi' => 'Kei di Maluku Tenggara (Kabupaten Maluku Tenggara dan Kota Tual)',
            ],
            [
                'id'        => 70,
                'suku'      => 'Kaili ',
                'deskripsi' => 'Kaili di Sulawesi Tengah (Kota Palu)',
            ],
            [
                'id'        => 71,
                'suku'      => 'Kampar',
                'deskripsi' => 'Kampar',
            ],
            [
                'id'        => 72,
                'suku'      => 'Kaur ',
                'deskripsi' => 'Kaur di Bengkulu (Kabupaten Kaur)',
            ],
            [
                'id'        => 73,
                'suku'      => 'Kayu Agung',
                'deskripsi' => 'Kayu Agung di Sumatera Selatan',
            ],
            [
                'id'        => 74,
                'suku'      => 'Kerinci',
                'deskripsi' => 'Kerinci di Jambi (Kabupaten Kerinci)',
            ],
            [
                'id'        => 75,
                'suku'      => 'Komering ',
                'deskripsi' => 'Komering di Sumatera Selatan (Kabupaten Ogan Komering Ilir, Baturaja)',
            ],
            [
                'id'        => 76,
                'suku'      => 'Konjo Pegunungan',
                'deskripsi' => 'Konjo Pegunungan, Kabupaten Gowa, Sulawesi Selatan',
            ],
            [
                'id'        => 77,
                'suku'      => 'Konjo Pesisir',
                'deskripsi' => 'Konjo Pesisir, Kabupaten Bulukumba, Sulawesi Selatan',
            ],
            [
                'id'        => 78,
                'suku'      => 'Koto',
                'deskripsi' => 'Koto di Sumatera Barat',
            ],
            [
                'id'        => 79,
                'suku'      => 'Kubu',
                'deskripsi' => 'Kubu di Jambi dan Sumatera Selatan',
            ],
            [
                'id'        => 80,
                'suku'      => 'Kulawi',
                'deskripsi' => 'Kulawi di Sulawesi Tengah',
            ],
            [
                'id'        => 81,
                'suku'      => 'Kutai ',
                'deskripsi' => 'Kutai di Kalimantan Timur (Kutai Kartanegara)',
            ],
            [
                'id'        => 82,
                'suku'      => 'Kluet ',
                'deskripsi' => 'Kluet di Aceh (Aceh Selatan)',
            ],
            [
                'id'        => 83,
                'suku'      => 'Korea ',
                'deskripsi' => 'Korea di DKI Jakarta',
            ],
            [
                'id'        => 84,
                'suku'      => 'Krui',
                'deskripsi' => 'Krui di Lampung',
            ],
            [
                'id'        => 85,
                'suku'      => 'Laut,',
                'deskripsi' => 'Laut, Kepulauan Riau',
            ],
            [
                'id'        => 86,
                'suku'      => 'Lampung',
                'deskripsi' => 'Lampung, terdiri dari: Suku Sungkai di Lampung; Suku Abung di Lampung; Suku Way Kanan di Lampung, Sumatera Selatan dan Bengkulu; Suku Pubian di Lampung; Suku Tulang Bawang di Lampung; Suku Melinting di Lampung; Suku Peminggir Teluk di Lampung; Suku Ranau di Lampung, Sumatera Selatan dan Sumatera Utara; Suku Komering di Sumatera Selatan; Suku Cikoneng di Banten; Suku Merpas di Bengkulu; Suku Belalau di Lampung; Suku Smoung di Lampung; Suku Semaka di Lampung',
            ],
            [
                'id'        => 87,
                'suku'      => 'Lematang ',
                'deskripsi' => 'Lematang di Sumatera Selatan',
            ],
            [
                'id'        => 88,
                'suku'      => 'Lembak',
                'deskripsi' => 'Lembak, Kabupaten Rejang Lebong, Bengkulu',
            ],
            [
                'id'        => 89,
                'suku'      => 'Lintang',
                'deskripsi' => 'Lintang, Sumatera Selatan',
            ],
            [
                'id'        => 90,
                'suku'      => 'Lom',
                'deskripsi' => 'Lom, Bangka Belitung',
            ],
            [
                'id'        => 91,
                'suku'      => 'Lore',
                'deskripsi' => 'Lore, Sulawesi Tengah',
            ],
            [
                'id'        => 92,
                'suku'      => 'Lubu',
                'deskripsi' => 'Lubu, daerah perbatasan antara Provinsi Sumatera Utara dan Provinsi Sumatera Barat',
            ],
            [
                'id'        => 93,
                'suku'      => 'Moronene',
                'deskripsi' => 'Moronene di Sulawesi Tenggara.',
            ],
            [
                'id'        => 94,
                'suku'      => 'Madura',
                'deskripsi' => 'Madura di Jawa Timur (Pulau Madura, Kangean, wilayah Tapal Kuda)',
            ],
            [
                'id'        => 95,
                'suku'      => 'Makassar',
                'deskripsi' => 'Makassar di Sulawesi Selatan: Kabupaten Gowa, Kabupaten Takalar, Kabupaten Jeneponto, Kabupaten Bantaeng, Kabupaten Bulukumba (sebagian), Kabupaten Sinjai (bagian perbatasan Kab Gowa), Kabupaten Maros (sebagian), Kabupaten Pangkep (sebagian), Kota Makassar',
            ],
            [
                'id'        => 96,
                'suku'      => 'Mamasa',
                'deskripsi' => 'Mamasa (Toraja Barat) di Sulawesi Barat: Kabupaten Mamasa',
            ],
            [
                'id'        => 97,
                'suku'      => 'Manda',
                'deskripsi' => 'Mandar Sulawesi Barat: Polewali Mandar',
            ],
            [
                'id'        => 98,
                'suku'      => 'Melayu',
                'deskripsi' => 'Melayu, terdiri dari Suku Melayu Tamiang di Aceh (Aceh Tamiang], Suku Melayu Riau di Riau dan Kepulauan Riau; Suku Melayu Deli di Sumatera Utara; Suku Melayu Jambi di Jambi; Suku Melayu Bangka di Pulau Bangka; Suku Melayu Belitung di Pulau Belitung; Suku Melayu Sambas di Kalimantan Barat',
            ],
            [
                'id'        => 99,
                'suku'      => 'Mentawai',
                'deskripsi' => 'Mentawai di Sumatera Barat (Kabupaten Kepulauan Mentawai)',
            ],
            [
                'id'        => 100,
                'suku'      => 'Minahasa',
                'deskripsi' => 'Minahasa di Sulawesi Utara (Kabupaten Minahasa), terdiri 9 subetnik : Suku Babontehu; Suku Bantik; Suku Pasan Ratahan',
            ],
            [
                'id'        => 101,
                'suku'      => 'Ponosakan',
                'deskripsi' => 'Ponosakan; Suku Tonsea; Suku Tontemboan; Suku Toulour; Suku Tonsawang; Suku Tombulu',
            ],
            [
                'id'        => 102,
                'suku'      => 'Minangkabau',
                'deskripsi' => 'Minangkabau, Sumatera Barat',
            ],
            [
                'id'        => 103,
                'suku'      => 'Mongondow',
                'deskripsi' => 'Mongondow, Sulawesi Utara',
            ],
            [
                'id'        => 104,
                'suku'      => 'Mori',
                'deskripsi' => 'Mori, Kabupaten Morowali, Sulawesi Tengah',
            ],
            [
                'id'        => 105,
                'suku'      => 'Muko-Muko',
                'deskripsi' => 'Muko-Muko di Bengkulu (Kabupaten Mukomuko)',
            ],
            [
                'id'        => 106,
                'suku'      => 'Muna',
                'deskripsi' => 'Muna di Sulawesi Tenggara (Kabupaten Muna)',
            ],
            [
                'id'        => 107,
                'suku'      => 'Muyu',
                'deskripsi' => 'Muyu di Kabupaten Boven Digoel, Papua',
            ],
            [
                'id'        => 108,
                'suku'      => 'Mekongga',
                'deskripsi' => 'Mekongga di Sulawesi Tenggara (Kabupaten Kolaka dan Kabupaten Kolaka Utara)',
            ],
            [
                'id'        => 109,
                'suku'      => 'Moro',
                'deskripsi' => 'Moro di Kalimantan Barat dan Kalimantan Utara',
            ],
            [
                'id'        => 110,
                'suku'      => 'Nias',
                'deskripsi' => 'Nias di Sumatera Utara (Kabupaten Nias, Nias Selatan dan Nias Utara dari dua keturunan Jepang dan Vietnam)',
            ],
            [
                'id'        => 111,
                'suku'      => 'Ngada ',
                'deskripsi' => 'Ngada di NTT: Kabupaten Ngada',
            ],
            [
                'id'        => 112,
                'suku'      => 'Osing',
                'deskripsi' => 'Osing di Banyuwangi Jawa Timur',
            ],
            [
                'id'        => 113,
                'suku'      => 'Ogan',
                'deskripsi' => 'Ogan di Sumatera Selatan',
            ],
            [
                'id'        => 114,
                'suku'      => 'Ocu',
                'deskripsi' => 'Ocu di Kabupaten Kampar, Riau',
            ],
            [
                'id'        => 115,
                'suku'      => 'Padoe',
                'deskripsi' => 'Padoe di Sulawesi Tengah dan Sulawesi Selatan',
            ],
            [
                'id'        => 116,
                'suku'      => 'Papua',
                'deskripsi' => 'Papua / Irian, terdiri dari: Suku Asmat di Kabupaten Asmat; Suku Biak di Kabupaten Biak Numfor; Suku Dani, Lembah Baliem, Papua; Suku Ekagi, daerah Paniai, Abepura, Papua; Suku Amungme di Mimika; Suku Bauzi, Mamberamo hilir, Papua utara; Suku Arfak di Manokwari; Suku Kamoro di Mimika',
            ],
            [
                'id'        => 117,
                'suku'      => 'Palembang',
                'deskripsi' => 'Palembang di Sumatera Selatan (Kota Palembang)',
            ],
            [
                'id'        => 118,
                'suku'      => 'Pamona',
                'deskripsi' => 'Pamona di Sulawesi Tengah (Kabupaten Poso) dan di Sulawesi Selatan',
            ],
            [
                'id'        => 119,
                'suku'      => 'Pesisi',
                'deskripsi' => 'Pesisi di Sumatera Utara (Tapanuli Tengah)',
            ],
            [
                'id'        => 120,
                'suku'      => 'Pasir',
                'deskripsi' => 'Pasir di Kalimantan Timur (Kabupaten Pasir)',
            ],
            [
                'id'        => 121,
                'suku'      => 'Pubian',
                'deskripsi' => 'Pubian di Lampung',
            ],
            [
                'id'        => 122,
                'suku'      => 'Pattae',
                'deskripsi' => 'Pattae di Polewali Mandar',
            ],
            [
                'id'        => 123,
                'suku'      => 'Pakistani',
                'deskripsi' => 'Pakistani di Sumatera Utara, DKI Jakarta, dan Jawa Tengah',
            ],
            [
                'id'        => 124,
                'suku'      => 'Peranakan',
                'deskripsi' => 'Peranakan (Tionghoa-Peranakan atau Baba Nyonya)',
            ],
            [
                'id'        => 125,
                'suku'      => 'Rawa',
                'deskripsi' => 'Rawa, Rokan Hilir, Riau',
            ],
            [
                'id'        => 126,
                'suku'      => 'Rejang',
                'deskripsi' => 'Rejang di Bengkulu (Kabupaten Bengkulu Tengah, Kabupaten Bengkulu Utara, Kabupaten Kepahiang, Kabupaten Lebong, dan Kabupaten Rejang Lebong)',
            ],
            [
                'id'        => 127,
                'suku'      => 'Rote',
                'deskripsi' => 'Rote di NTT (Kabupaten Rote Ndao)',
            ],
            [
                'id'        => 128,
                'suku'      => 'Rongga',
                'deskripsi' => 'Rongga di NTT Kabupaten Manggarai Timur',
            ],
            [
                'id'        => 129,
                'suku'      => 'Rohingya',
                'deskripsi' => 'Rohingya',
            ],
            [
                'id'        => 130,
                'suku'      => 'Sabu',
                'deskripsi' => 'Sabu di Pulau Sabu, NTT',
            ],
            [
                'id'        => 131,
                'suku'      => 'Saluan',
                'deskripsi' => 'Saluan di Sulawesi Tengah',
            ],
            [
                'id'        => 132,
                'suku'      => 'Sambas',
                'deskripsi' => 'Sambas (Melayu Sambas) di Kalimantan Barat: Kabupaten Sambas',
            ],
            [
                'id'        => 133,
                'suku'      => 'Samin',
                'deskripsi' => 'Samin di Jawa Tengah (Blora) dan Jawa Timur (Bojonegoro)',
            ],
            [
                'id'        => 134,
                'suku'      => 'Sangi',
                'deskripsi' => 'Sangir di Sulawesi Utara (Kepulauan Sangihe)',
            ],
            [
                'id'        => 135,
                'suku'      => 'Sasak',
                'deskripsi' => "Sasak\u{a0}di NTB, Lombok",
            ],
            [
                'id'        => 136,
                'suku'      => 'Sekak Bangka',
                'deskripsi' => 'Sekak Bangka',
            ],
            [
                'id'        => 137,
                'suku'      => 'Sekayu',
                'deskripsi' => 'Sekayu di Sumatera Selatan',
            ],
            [
                'id'        => 138,
                'suku'      => 'Semendo ',
                'deskripsi' => 'Semendo di Bengkulu, Sumatera Selatan (Muara Enim)',
            ],
            [
                'id'        => 139,
                'suku'      => 'Serawai ',
                'deskripsi' => 'Serawai di Bengkulu (Kabupaten Bengkulu Selatan dan Kabupaten Seluma)',
            ],
            [
                'id'        => 140,
                'suku'      => 'Simeulue',
                'deskripsi' => 'Simeulue di Aceh (Kabupaten Simeulue)',
            ],
            [
                'id'        => 141,
                'suku'      => 'Sigulai ',
                'deskripsi' => 'Sigulai di Aceh (Kabupaten Simeulue bagian utara',
            ],
            [
                'id'        => 142,
                'suku'      => 'Suluk',
                'deskripsi' => 'Suluk di Kalimantan Utara)',
            ],
            [
                'id'        => 143,
                'suku'      => 'Sumbawa ',
                'deskripsi' => 'Sumbawa Di NTB (Kabupaten Sumbawa)',
            ],
            [
                'id'        => 144,
                'suku'      => 'Sumba',
                'deskripsi' => 'Sumba di NTT (Sumba Barat, Sumba Timur)',
            ],
            [
                'id'        => 145,
                'suku'      => 'Sunda',
                'deskripsi' => 'Sunda di Jawa Barat, Banten, DKI Jakarta, Lampung, Sumatra Selatan dan Jawa Tengah',
            ],
            [
                'id'        => 146,
                'suku'      => 'Sungkai ',
                'deskripsi' => 'Sungkai di Lampung Lampung Utara',
            ],
            [
                'id'        => 147,
                'suku'      => 'Talau',
                'deskripsi' => 'Talaud di Sulawesi Utara (Kepulauan Talaud)',
            ],
            [
                'id'        => 148,
                'suku'      => 'Talang Mamak',
                'deskripsi' => 'Talang Mamak di Riau (Indragiri Hulu)',
            ],
            [
                'id'        => 149,
                'suku'      => 'Tamiang ',
                'deskripsi' => 'Tamiang di Aceh (Kabupaten Aceh Tamiang)',
            ],
            [
                'id'        => 150,
                'suku'      => 'Tengger ',
                'deskripsi' => 'Tengger di Jawa Timur (Kabupaten Pasuruan) dan Probolinggo (lereng G. Bromo)',
            ],
            [
                'id'        => 151,
                'suku'      => 'Ternate ',
                'deskripsi' => 'Ternate di Maluku Utara (Kota Ternate)',
            ],
            [
                'id'        => 152,
                'suku'      => 'Tidore',
                'deskripsi' => 'Tidore di Maluku Utara (Kota Tidore)',
            ],
            [
                'id'        => 153,
                'suku'      => 'Tidung',
                'deskripsi' => 'Tidung di Kalimantan Timur (Kabupaten Tanah Tidung)',
            ],
            [
                'id'        => 154,
                'suku'      => 'Timor',
                'deskripsi' => 'Timor di NTT, Kota Kupang',
            ],
            [
                'id'        => 155,
                'suku'      => 'Tionghoa',
                'deskripsi' => 'Tionghoa, terdiri dari: Orang Cina Parit di Pelaihari, Tanah Laut, Kalsel; Orang Cina Benteng di Tangerang, Provinsi Banten; Orang Tionghoa Hokkien di Jawa dan Sumatera Utara; Orang Tionghoa Hakka di Belitung dan Kalimantan Barat; Orang Tionghoa Hubei; Orang Tionghoa Hainan; Orang Tionghoa Kanton; Orang Tionghoa Hokchia; Orang Tionghoa Tiochiu',
            ],
            [
                'id'        => 156,
                'suku'      => 'Tojo',
                'deskripsi' => 'Tojo di Sulawesi Tengah (Kabupaten Tojo Una-Una)',
            ],
            [
                'id'        => 157,
                'suku'      => 'Toraja',
                'deskripsi' => 'Toraja di Sulawesi Selatan (Tana Toraja)',
            ],
            [
                'id'        => 158,
                'suku'      => 'Tolaki',
                'deskripsi' => 'Tolaki di Sulawesi Tenggara (Kendari)',
            ],
            [
                'id'        => 159,
                'suku'      => 'Toli Toli',
                'deskripsi' => 'Toli Toli di Sulawesi Tengah (Kabupaten Toli-Toli)',
            ],
            [
                'id'        => 160,
                'suku'      => 'Tomini',
                'deskripsi' => 'Tomini di Sulawesi Tengah (Kabupaten Parigi Mouton',
            ],
            [
                'id'        => 161,
                'suku'      => 'Una-una ',
                'deskripsi' => 'Una-una di Sulawesi Tengah (Kabupaten Tojo Una-Una)',
            ],
            [
                'id'        => 162,
                'suku'      => 'Ulu',
                'deskripsi' => 'Ulu di Sumatera Utara (Mandailing natal)',
            ],
            [
                'id'        => 163,
                'suku'      => 'Wolio',
                'deskripsi' => 'Wolio di Sulawesi Tenggara (Buton)',
            ],
        ];
    }
}
