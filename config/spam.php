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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

return [
    // Path model ML hasil training. Bila tidak ada, deteksi ML dilewati.
    'model_path' => env('SPAM_MODEL_PATH', storage_path('app/spam-model/model.rbx')),

    // Dataset latih berlabel (Judol). Diunduh saat training bila belum tersedia.
    // Format: comment_id,author,comment_text,label (label: 0 = bukan spam, 1 = spam).
    'dataset_path' => env('SPAM_DATASET_PATH', storage_path('app/spam-model/dataset_judol.csv')),
    'dataset_url'  => env('SPAM_DATASET_URL', 'https://raw.githubusercontent.com/gbennnn/judol-comment-classification/main/dataset/lab/dataset_judol_labelled_full.csv'),

    // Dataset SMS Spam Indonesia (Andikazidanef15).
    // Format: Teks,label (label: 0=Normal, 1=Spam, 2=Promo → mapping 1,2→spam, 0→not_spam).
    'sms_dataset_path' => env('SPAM_SMS_DATASET_PATH', storage_path('app/spam-model/dataset_sms_spam.csv')),
    'sms_dataset_url'  => env('SPAM_SMS_DATASET_URL', 'https://raw.githubusercontent.com/Andikazidanef15/Sentiment-Analysis-on-Indonesian-SMS-Dataset/main/dataset_sms_spam_v1.csv'),

    // Sampel seed khas layanan desa OpenSID (dikelola manual, di-track git).
    // Format: text,label (label: spam / not_spam).
    'seed_path' => env('SPAM_SEED_PATH', storage_path('app/spam-model/spam-seed.csv')),
];
