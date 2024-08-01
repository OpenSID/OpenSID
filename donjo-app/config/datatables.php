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

return [
    // DataTables search options.
    'search' => [
        /*
         * Smart search will enclose search keyword with wildcard string "%keyword%".
         * SQL: column LIKE "%keyword%"
         */
        'smart' => true,

        // Multi-term search will explode search keyword using spaces resulting into multiple term search.
        'multi_term' => true,

        /*
         * Case insensitive will search the keyword in lower case format.
         * SQL: LOWER(column) LIKE LOWER(keyword)
         */
        'case_insensitive' => true,

        /*
         * Wild card will add "%" in between every characters of the keyword.
         * SQL: column LIKE "%k%e%y%w%o%r%d%"
         */
        'use_wildcards' => false,

        /*
         * Perform a search which starts with the given keyword.
         * SQL: column LIKE "keyword%"
         */
        'starts_with' => false,
    ],

    // DataTables internal index id response column name.
    'index_column' => 'DT_RowIndex',

    /*
     * List of available builders for DataTables.
     * This is where you can register your custom dataTables builder.
     */
    'engines' => [
        'eloquent'   => App\Services\DataTables\EloquentDataTable::class,
        'query'      => App\Services\DataTables\QueryDataTable::class,
        'collection' => App\Services\DataTables\CollectionDataTable::class,
        'resource'   => App\Services\DataTables\ApiResourceDataTable::class,
    ],

    /*
     * DataTables accepted builder to engine mapping.
     * This is where you can override which engine a builder should use
     * Note, only change this if you know what you are doing!
     */
    'builders' => [
        //Illuminate\Database\Eloquent\Relations\Relation::class => 'eloquent',
        //Illuminate\Database\Eloquent\Builder::class            => 'eloquent',
        //Illuminate\Database\Query\Builder::class               => 'query',
        //Illuminate\Support\Collection::class                   => 'collection',
    ],

    /*
     * Nulls last sql pattern for PostgreSQL & Oracle.
     * For MySQL, use 'CASE WHEN :column IS NULL THEN 1 ELSE 0 END, :column :direction'
     */
    'nulls_last_sql' => ':column :direction NULLS LAST',

    /*
     * User friendly message to be displayed on user if error occurs.
     * Possible values:
     * null             - The exception message will be used on error response.
     * 'throw'          - Throws a \Yajra\DataTables\Exceptions\Exception. Use your custom error handler if needed.
     * 'custom message' - Any friendly message to be displayed to the user. You can also use translation key.
     */
    'error' => null,

    // Default columns definition of dataTable utility functions.
    'columns' => [
        // List of columns hidden/removed on json response.
        'excess' => ['rn', 'row_num'],

        /*
         * List of columns to be escaped. If set to *, all columns are escape.
         * Note: You can set the value to empty array to disable XSS protection.
         */
        'escape' => '*',

        /*
         * List of columns that are allowed to display html content.
         * Note: Adding columns to list will make us available to XSS attacks.
         */
        'raw' => ['action'],

        // List of columns are forbidden from being searched/sorted.
        'blacklist' => ['password', 'remember_token'],

        /*
         * List of columns that are only allowed fo search/sort.
         * If set to *, all columns are allowed.
         */
        'whitelist' => '*',
    ],

    // JsonResponse header and options config.
    'json' => [
        'header'  => [],
        'options' => 0,
    ],

];
