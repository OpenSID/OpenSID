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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

return [
    /*
     * The default serializer to be used when performing a transformation. It
     * may be left empty to use Fractal's default one. This can either be a
     * string or a League\Fractal\Serializer\SerializerAbstract subclass.
     */
    'default_serializer' => '',

    /* The default paginator to be used when performing a transformation. It
     * may be left empty to use Fractal's default one. This can either be a
     * string or a League\Fractal\Paginator\PaginatorInterface subclass.
     */
    'default_paginator' => '',

    /*
     * League\Fractal\Serializer\JsonApiSerializer will use this value
     * as a prefix for generated links. Set to `null` to disable this.
     */
    'base_url' => null,

    /*
     * If you wish to override or extend the default Spatie\Fractal\Fractal
     * instance provide the name of the class you want to use.
     */
    'fractal_class' => Spatie\Fractal\Fractal::class,

    'auto_includes' => [

        /*
         * If enabled Fractal will automatically add the includes who's
         * names are present in the `include` request parameter.
         */
        'enabled' => true,

        // The name of key in the request to where we should look for the includes to include.
        'request_key' => 'include',
    ],

    'auto_excludes' => [

        /*
         * If enabled Fractal will automatically add the excludes who's
         * names are present in the `exclude` request parameter.
         */
        'enabled' => true,

        // The name of key in the request to where we should look for the excludes to exclude.
        'request_key' => 'exclude',
    ],
];
