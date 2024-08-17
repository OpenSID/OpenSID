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

namespace Cocur\Slugify;

use Cocur\Slugify\RuleProvider\DefaultRuleProvider;
use Cocur\Slugify\RuleProvider\RuleProviderInterface;

/**
 * Slugify
 *
 * @copyright 2012-2015 Florian Eckerstorfer
 * @license   http://www.opensource.org/licenses/MIT The MIT License
 */
class Slugify implements SlugifyInterface
{
    public const LOWERCASE_NUMBERS_DASHES = '/[^A-Za-z0-9]+/';

    /**
     * @var array<string,string>
     */
    protected array $rules = [];

    protected RuleProviderInterface $provider;

    /**
     * @var array<string,mixed>
     */
    protected array $options = [
        'regexp'                 => self::LOWERCASE_NUMBERS_DASHES,
        'separator'              => '-',
        'lowercase'              => true,
        'lowercase_after_regexp' => false,
        'trim'                   => true,
        'strip_tags'             => false,
        'rulesets'               => [
            'default',
            // Languages are preferred if they appear later, list is ordered by number of
            // websites in that language
            // https://en.wikipedia.org/wiki/Languages_used_on_the_Internet#Content_languages_for_websites
            'armenian',
            'azerbaijani',
            'burmese',
            'hindi',
            'georgian',
            'norwegian',
            'vietnamese',
            'ukrainian',
            'latvian',
            'finnish',
            'greek',
            'czech',
            'arabic',
            'slovak',
            'turkish',
            'polish',
            'german',
            'russian',
            'romanian',
        ],
    ];

    public function __construct(array $options = [], ?RuleProviderInterface $provider = null)
    {
        $this->options  = array_merge($this->options, $options);
        $this->provider = $provider ?: new DefaultRuleProvider();

        foreach ($this->options['rulesets'] as $ruleSet) {
            $this->activateRuleSet($ruleSet);
        }
    }

    /**
     * Returns the slug-version of the string.
     *
     * @param string            $string  String to slugify
     * @param array|string|null $options Options
     *
     * @return string Slugified version of the string
     */
    public function slugify(string $string, $options = null): string
    {
        // BC: the second argument used to be the separator
        if (is_string($options)) {
            $separator            = $options;
            $options              = [];
            $options['separator'] = $separator;
        }

        $options = array_merge($this->options, (array) $options);

        // Add a custom ruleset without touching the default rules
        if (isset($options['ruleset'])) {
            $rules = array_merge($this->rules, $this->provider->getRules($options['ruleset']));
        } else {
            $rules = $this->rules;
        }

        $string = ($options['strip_tags'])
            ? strip_tags($string)
            : $string;

        $string = strtr($string, $rules);
        unset($rules);

        if ($options['lowercase'] && ! $options['lowercase_after_regexp']) {
            $string = mb_strtolower($string);
        }

        $string = preg_replace($options['regexp'], $options['separator'], $string);

        if ($options['lowercase'] && $options['lowercase_after_regexp']) {
            $string = mb_strtolower($string);
        }

        return ($options['trim'])
            ? trim($string, $options['separator'])
            : $string;
    }

    /**
     * Adds a custom rule to Slugify.
     *
     * @param string $character   Character
     * @param string $replacement Replacement character
     */
    public function addRule($character, $replacement): self
    {
        $this->rules[$character] = $replacement;

        return $this;
    }

    /**
     * Adds multiple rules to Slugify.
     *
     * @param array <string,string> $rules
     */
    public function addRules(array $rules): self
    {
        foreach ($rules as $character => $replacement) {
            $this->addRule($character, $replacement);
        }

        return $this;
    }

    public function activateRuleSet(string $ruleSet): self
    {
        return $this->addRules($this->provider->getRules($ruleSet));
    }

    /**
     * Static method to create new instance of {@see Slugify}.
     *
     * @param array <string,mixed> $options
     */
    public static function create(array $options = []): self
    {
        return new static($options);
    }
}
