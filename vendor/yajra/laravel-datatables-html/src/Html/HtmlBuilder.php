<?php

namespace Yajra\DataTables\Html;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\HtmlString;

class HtmlBuilder
{
    /**
     * Create a new HTML builder instance.
     */
    public function __construct(
        /**
         * The URL generator instance.
         */
        protected UrlGenerator $url,
        /**
         * The View Factory instance.
         */
        protected Factory $view
    ) {}

    /**
     * Convert entities to HTML characters.
     */
    public function decode(string $value): string
    {
        return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate a link to a JavaScript file.
     */
    public function script(string $url, array $attributes = [], ?bool $secure = null): HtmlString
    {
        $attributes['src'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<script'.$this->attributes($attributes).'></script>');
    }

    /**
     * Transform the string to an Html serializable object
     */
    protected function toHtmlString(string $html): HtmlString
    {
        return new HtmlString($html);
    }

    /**
     * Build an HTML attribute string from an array.
     */
    public function attributes(array $attributes): string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (! is_null($element)) {
                $html[] = $element;
            }
        }

        return ! empty($html) ? ' '.implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     */
    protected function attributeElement(string $key, mixed $value): mixed
    {
        // For numeric keys we will assume that the value is a boolean attribute
        // where the presence of the attribute represents a true value and the
        // absence represents a false value.
        // This will convert HTML attributes such as "required" to a correct
        // form instead of using incorrect numerics.
        if (is_numeric($key)) {
            return $value;
        }

        // Treat boolean attributes as HTML properties
        if (is_bool($value) && $key !== 'value') {
            return $value ? $key : '';
        }

        if (is_array($value) && $key === 'class') {
            return 'class="'.implode(' ', $value).'"';
        }

        if (is_bool($value) || is_float($value) || is_int($value) || is_resource($value) || is_string($value)) {
            return $key.'="'.e(strval($value), false).'"';
        }

        return null;
    }

    /**
     * Generate a link to a CSS file.
     */
    public function style(string $url, array $attributes = [], ?bool $secure = null): HtmlString
    {
        $defaults = ['media' => 'all', 'type' => 'text/css', 'rel' => 'stylesheet'];

        $attributes = array_merge($defaults, $attributes);

        $attributes['href'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<link'.$this->attributes($attributes).'>');
    }

    /**
     * Generate an HTML image element.
     */
    public function image(string $url, ?string $alt = null, array $attributes = [], ?bool $secure = null): HtmlString
    {
        $attributes['alt'] = $alt;

        return $this->toHtmlString('<img src="'.$this->url->asset($url,
            $secure).'"'.$this->attributes($attributes).'>');
    }

    /**
     * Generate a link to a Favicon file.
     */
    public function favicon(string $url, array $attributes = [], ?bool $secure = null): HtmlString
    {
        $defaults = ['rel' => 'shortcut icon', 'type' => 'image/x-icon'];

        $attributes = array_merge($defaults, $attributes);

        $attributes['href'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<link'.$this->attributes($attributes).'>');
    }

    /**
     * Generate a HTTPS HTML link.
     */
    public function secureLink(
        string $url,
        ?string $title = null,
        array $attributes = [],
        bool $escape = true
    ): HtmlString {
        return $this->link($url, $title, $attributes, true, $escape);
    }

    /**
     * Generate a HTML link.
     */
    public function link(
        string $url,
        ?string $title = null,
        array $attributes = [],
        ?bool $secure = null,
        bool $escape = true
    ): HtmlString {
        $url = $this->url->to($url, [], $secure);

        if (is_null($title)) {
            $title = $url;
        }

        if ($escape) {
            $title = $this->entities($title);
        }

        return $this->toHtmlString(
            '<a href="'.$this->entities($url).'"'.$this->attributes($attributes).'>'.$title.'</a>'
        );
    }

    /**
     * Convert an HTML string to entities.
     */
    public function entities(string $value): string
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * Generate a HTTPS HTML link to an asset.
     */
    public function linkSecureAsset(
        string $url,
        ?string $title = null,
        array $attributes = [],
        bool $escape = true
    ): HtmlString {
        return $this->linkAsset($url, $title, $attributes, true, $escape);
    }

    /**
     * Generate a HTML link to an asset.
     */
    public function linkAsset(
        string $url,
        ?string $title = null,
        array $attributes = [],
        ?bool $secure = null,
        bool $escape = true
    ): HtmlString {
        $url = $this->url->asset($url, $secure);

        return $this->link($url, $title ?: $url, $attributes, $secure, $escape);
    }

    /**
     * Generate a HTML link to a named route.
     */
    public function linkRoute(
        string $name,
        ?string $title = null,
        array $parameters = [],
        array $attributes = [],
        ?bool $secure = null,
        bool $escape = true
    ): HtmlString {
        return $this->link($this->url->route($name, $parameters), $title, $attributes, $secure, $escape);
    }

    /**
     * Generate a HTML link to a controller action.
     */
    public function linkAction(
        string $action,
        ?string $title = null,
        array $parameters = [],
        array $attributes = [],
        ?bool $secure = null,
        bool $escape = true
    ): HtmlString {
        return $this->link($this->url->action($action, $parameters), $title, $attributes, $secure, $escape);
    }

    /**
     * Generate a HTML link to an email address.
     */
    public function mailto(string $email, ?string $title = null, array $attributes = [], bool $escape = true): HtmlString
    {
        $email = $this->email($email);

        $title = $title ?: $email;

        if ($escape) {
            $title = $this->entities($title);
        }

        $email = $this->obfuscate('mailto:').$email;

        return $this->toHtmlString('<a href="'.$email.'"'.$this->attributes($attributes).'>'.$title.'</a>');
    }

    /**
     * Obfuscate an e-mail address to prevent spam-bots from sniffing it.
     */
    public function email(string $email): string
    {
        return str_replace('@', '&#64;', $this->obfuscate($email));
    }

    /**
     * Obfuscate a string to prevent spam-bots from sniffing it.
     *
     * @throws \Exception
     */
    public function obfuscate(string $value): string
    {
        $safe = '';

        foreach (str_split($value) as $letter) {
            if (ord($letter) > 128) {
                return $letter;
            }

            // To properly obfuscate the value, we will randomly convert each letter to
            // its entity or hexadecimal representation, keeping a bot from sniffing
            // the randomly obfuscated letters out of the string on the responses.
            switch (random_int(1, 3)) {
                case 1:
                    $safe .= '&#'.ord($letter).';';
                    break;

                case 2:
                    $safe .= '&#x'.dechex(ord($letter)).';';
                    break;

                case 3:
                    $safe .= $letter;
            }
        }

        return $safe;
    }

    /**
     * Generates non-breaking space entities based on number supplied.
     */
    public function nbsp(int $num = 1): string
    {
        return str_repeat('&nbsp;', $num);
    }

    /**
     * Generate an ordered list of items.
     */
    public function ol(array $list, array $attributes = []): HtmlString|string
    {
        return $this->listing('ol', $list, $attributes);
    }

    /**
     * Create a listing HTML element.
     */
    protected function listing(string $type, array $list, array $attributes = []): HtmlString|string
    {
        $html = '';

        if (empty($list)) {
            return $html;
        }

        // Essentially we will just spin through the list and build the list of the HTML
        // elements from the array. We will also handled nested lists in case that is
        // present in the array. Then we will build out the final listing elements.
        foreach ($list as $key => $value) {
            $html .= $this->listingElement($key, $type, $value);
        }

        $attributes = $this->attributes($attributes);

        return $this->toHtmlString("<{$type}{$attributes}>{$html}</{$type}>");
    }

    /**
     * Create the HTML for a listing element.
     */
    protected function listingElement(mixed $key, string $type, mixed $value): HtmlString|string
    {
        if (is_array($value)) {
            return $this->nestedListing($key, $type, $value);
        } else {
            if (is_bool($value)
                || is_float($value)
                || is_int($value)
                || is_resource($value)
                || is_string($value)
                || is_null($value)
            ) {
                return '<li>'.e(strval($value), false).'</li>';
            }
        }

        return '<li>'.$value.'</li>';
    }

    /**
     * Create the HTML for a nested listing attribute.
     */
    protected function nestedListing(mixed $key, string $type, array $value): HtmlString|string
    {
        if (is_int($key)) {
            return $this->listing($type, $value);
        } else {
            return '<li>'.$key.$this->listing($type, $value).'</li>';
        }
    }

    /**
     * Generate an un-ordered list of items.
     */
    public function ul(array $list, array $attributes = []): HtmlString|string
    {
        return $this->listing('ul', $list, $attributes);
    }

    /**
     * Generate a description list of items.
     */
    public function dl(array $list, array $attributes = []): HtmlString
    {
        $attributes = $this->attributes($attributes);

        $html = "<dl{$attributes}>";

        foreach ($list as $key => $value) {
            $value = (array) $value;

            $html .= "<dt>$key</dt>";

            foreach ($value as $v_value) {
                $html .= "<dd>$v_value</dd>";
            }
        }

        $html .= '</dl>';

        return $this->toHtmlString($html);
    }

    /**
     * Generate a meta tag.
     */
    public function meta(string $name, string $content, array $attributes = []): HtmlString
    {
        $defaults = compact('name', 'content');

        $attributes = array_merge($defaults, $attributes);

        return $this->toHtmlString('<meta'.$this->attributes($attributes).'>');
    }

    /**
     * Generate an html tag.
     */
    public function tag(string $tag, mixed $content, array $attributes = []): HtmlString
    {
        $content = is_array($content) ? implode('', $content) : $content;

        if (is_bool($content)
            || is_float($content)
            || is_int($content)
            || is_resource($content)
            || is_string($content)
            || is_null($content)
        ) {
            return $this->toHtmlString(
                '<'.$tag.$this->attributes($attributes).'>'.$this->toHtmlString(strval($content)).'</'.$tag.'>'
            );
        }

        return $this->toHtmlString('<'.$tag.$this->attributes($attributes).'>'.$content.'</'.$tag.'>');
    }
}
