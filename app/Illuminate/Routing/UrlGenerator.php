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

namespace Illuminate\Routing;

use App\Services\Laravel;
use BackedEnum;
use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Support\Str;
use InvalidArgumentException;
use OpenSID\RouteBuilder;

class UrlGenerator
{
    use InteractsWithTime;

    /**
     * The forced URL root.
     *
     * @var string
     */
    protected $forcedRoot;

    /**
     * The forced schema for URLs.
     *
     * @var string
     */
    protected $forceScheme;

    /**
     * The cached URL root.
     *
     * @var string|null
     */
    protected $cachedRoot;

    /**
     * A cached copy of the URL schema for the current request.
     *
     * @var string|null
     */
    protected $cachedSchema;

    /**
     * The encryption key resolver callable.
     *
     * @var callable
     */
    protected $keyResolver;

    /**
     * Create a new URL redirector instance.
     *
     * @return void
     */
    public function __construct(
        /**
         * The application instance.
         */
        protected Laravel $app
    ) {
    }

    /**
     * Get the full URL for the current request.
     *
     * @return string
     */
    public function full()
    {
        return $this->app->make('request')->fullUrl();
    }

    /**
     * Get the current URL for the request.
     *
     * @return string
     */
    public function current()
    {
        return $this->to($this->app->make('request')->getPathInfo());
    }

    /**
     * Generate a url for the application.
     *
     * @param string $path
     * @param array  $extra
     * @param bool   $secure
     *
     * @return string
     */
    public function to($path, $extra = [], $secure = null)
    {
        // First we will check if the URL is already a valid URL. If it is we will not
        // try to generate a new one but will simply return the URL as is, which is
        // convenient since developers do not always have to check if it's valid.
        if ($this->isValidUrl($path)) {
            return $path;
        }

        $scheme = $this->getSchemeForUrl($secure);

        $tail = implode(
            '/',
            array_map(
                'rawurlencode',
                (array) $extra
            )
        );

        // Once we have the scheme we will compile the "tail" by collapsing the values
        // into a single string delimited by slashes. This just makes it convenient
        // for passing the array of parameters to this URL as a list of segments.
        $root = $this->getRootUrl($scheme);

        return $this->trimUrl($root, $path, $tail);
    }

    /**
     * Generate a secure, absolute URL to the given path.
     *
     * @param string $path
     * @param array  $parameters
     *
     * @return string
     */
    public function secure($path, $parameters = [])
    {
        return $this->to($path, $parameters, true);
    }

    /**
     * Generate a URL to an application asset.
     *
     * @param string    $path
     * @param bool|null $secure
     *
     * @return string
     */
    public function asset($path, $secure = null)
    {
        if ($this->isValidUrl($path)) {
            return $path;
        }

        // Once we get the root URL, we will check to see if it contains an index.php
        // file in the paths. If it does, we will remove it since it is not needed
        // for asset paths, but only for routes to endpoints in the application.
        $root = $this->getRootUrl($this->formatScheme($secure));

        return $this->removeIndex($root) . '/' . trim($path, '/');
    }

    /**
     * Generate a URL to an application asset from a root domain such as CDN etc.
     *
     * @param string    $root
     * @param string    $path
     * @param bool|null $secure
     */
    public function assetFrom($root, $path, $secure = null): string
    {
        // Once we get the root URL, we will check to see if it contains an index.php
        // file in the paths. If it does, we will remove it since it is not needed
        // for asset paths, but only for routes to endpoints in the application.
        $root = $this->getRootUrl($this->formatScheme($secure), $root);

        return $this->removeIndex($root) . '/' . trim($path, '/');
    }

    /**
     * Generate a URL to a secure asset.
     *
     * @param string $path
     *
     * @return string
     */
    public function secureAsset($path)
    {
        return $this->asset($path, true);
    }

    /**
     * Force the schema for URLs.
     */
    public function forceScheme(string $schema): void
    {
        $this->cachedSchema = null;

        $this->forceScheme = $schema . '://';
    }

    /**
     * Get the default scheme for a raw URL.
     *
     * @param bool|null $secure
     *
     * @return string
     */
    public function formatScheme($secure = null)
    {
        if (null !== $secure) {
            return $secure ? 'https://' : 'http://';
        }

        if (null === $this->cachedSchema) {
            $this->cachedSchema = $this->forceScheme ?: $this->app->make('request')->getScheme() . '://';
        }

        return $this->cachedSchema;
    }

    /**
     * Get the URL to a named route.
     *
     * @param string    $name
     * @param bool|null $secure
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function route($name, mixed $parameters = [], $secure = null)
    {
        $route = RouteBuilder::getByName($name);
        $uri   = $this->to($route->buildUrl($parameters), [], $secure);

        $filteredParameters = array_filter($parameters, static fn ($value, $key): bool => ! $route->hasParam($key), ARRAY_FILTER_USE_BOTH);

        if ($filteredParameters) {
            $uri .= '?' . http_build_query($filteredParameters);
        }

        return $uri;
    }

    /**
     * Determine if the given path is a valid URL.
     *
     * @param string $path
     *
     * @return bool
     */
    public function isValidUrl($path)
    {
        if (Str::startsWith($path, ['#', '//', 'mailto:', 'tel:', 'sms:', 'http://', 'https://'])) {
            return true;
        }

        return filter_var($path, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Set the forced root URL.
     *
     * @param string $root
     */
    public function forceRootUrl($root): void
    {
        $this->forcedRoot = rtrim($root, '/');

        $this->cachedRoot = null;
    }

    /**
     * Create a signed route URL for a named route.
     *
     * @param BackedEnum|string                       $name
     * @param DateInterval|DateTimeInterface|int|null $expiration
     * @param bool                                    $absolute
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    public function signedRoute($name, mixed $parameters = [], $expiration = null, $absolute = true)
    {
        $this->ensureSignedRouteParametersAreNotReserved(
            $parameters = Arr::wrap($parameters)
        );

        if ($expiration) {
            $parameters += ['expires' => $this->availableAt($expiration)];
        }

        ksort($parameters);

        $key = ($this->keyResolver)();

        return $this->route($name, $parameters + [
            'signature' => hash_hmac(
                'sha256',
                $this->route($name, $parameters, $absolute),
                is_array($key) ? $key[0] : trim((string) $key)
            ),
        ], $absolute);
    }

    /**
     * Create a temporary signed route URL for a named route.
     *
     * @param BackedEnum|string                  $name
     * @param DateInterval|DateTimeInterface|int $expiration
     * @param array                              $parameters
     * @param bool                               $absolute
     *
     * @return string
     */
    public function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true)
    {
        return $this->signedRoute($name, $parameters, $expiration, $absolute);
    }

    /**
     * Determine if the given request has a valid signature.
     *
     * @param bool $absolute
     */
    public function hasValidSignature(Request $request, $absolute = true, Closure|array $ignoreQuery = []): bool
    {
        return $this->hasCorrectSignature($request, $absolute, $ignoreQuery)
            && $this->signatureHasNotExpired($request);
    }

    /**
     * Determine if the given request has a valid signature for a relative URL.
     */
    public function hasValidRelativeSignature(Request $request, Closure|array $ignoreQuery = []): bool
    {
        return $this->hasValidSignature($request, false, $ignoreQuery);
    }

    /**
     * Determine if the signature from the given request matches the URL.
     *
     * @param bool $absolute
     */
    public function hasCorrectSignature(Request $request, $absolute = true, Closure|array $ignoreQuery = []): bool
    {
        $url = $absolute ? $request->url() : '/' . $request->path();

        $queryString = (new Collection(explode('&', (string) ci()->input->server('QUERY_STRING'))))
            ->reject(static function ($parameter) use ($ignoreQuery) {
                $parameter = Str::before($parameter, '=');

                if ($parameter === 'signature') {
                    return true;
                }

                if ($ignoreQuery instanceof Closure) {
                    return $ignoreQuery($parameter);
                }

                return in_array($parameter, $ignoreQuery);
            })
            ->join('&');

        $original = rtrim($url . '?' . $queryString, '?');

        $keys = ($this->keyResolver)();
        $keys = is_array($keys) ? $keys : [$keys];

        foreach ($keys as $key) {
            if (hash_equals(
                hash_hmac('sha256', $original, trim((string) $key)),
                (string) $request->query('signature', '')
            )) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the expires timestamp from the given request is not from the past.
     */
    public function signatureHasNotExpired(Request $request): bool
    {
        $expires = $request->query('expires');

        return ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }

    /**
     * Set the encryption key resolver.
     *
     * @return $this
     */
    public function setKeyResolver(callable $keyResolver): static
    {
        $this->keyResolver = $keyResolver;

        return $this;
    }

    /**
     * Clone a new instance of the URL generator with a different encryption key resolver.
     */
    public function withKeyResolver(callable $keyResolver): static
    {
        return (clone $this)->setKeyResolver($keyResolver);
    }

    /**
     * Remove the index.php file from a path.
     *
     * @param string $root
     *
     * @return string
     */
    protected function removeIndex($root)
    {
        $i = 'index.php';

        return Str::contains($root, $i) ? str_replace('/' . $i, '', $root) : $root;
    }

    /**
     * Get the scheme for a raw URL.
     *
     * @param bool|null $secure
     *
     * @return string
     */
    protected function getSchemeForUrl($secure)
    {
        if (null === $secure) {
            if (null === $this->cachedSchema) {
                $this->cachedSchema = $this->formatScheme($secure);
            }

            return $this->cachedSchema;
        }

        return $secure ? 'https://' : 'http://';
    }

    /**
     * Get the base URL for the request.
     *
     * @param string $scheme
     * @param string $root
     *
     * @return string
     */
    protected function getRootUrl($scheme, $root = null): string|array|null
    {
        if (null === $root) {
            if (null === $this->cachedRoot) {
                $this->cachedRoot = $this->forcedRoot ?: $this->app->make('request')->root();
            }

            $root = $this->cachedRoot;
        }

        $start = Str::startsWith($root, 'http://') ? 'http://' : 'https://';

        return preg_replace('~' . $start . '~', $scheme, (string) $root, 1);
    }

    /**
     * Format the given URL segments into a single URL.
     */
    protected function trimUrl(string $root, string $path, string $tail = ''): string
    {
        return trim($root . '/' . trim($path . '/' . $tail, '/'), '/');
    }

    /**
     * Ensure the given signed route parameters are not reserved.
     *
     * @return void
     */
    protected function ensureSignedRouteParametersAreNotReserved(mixed $parameters)
    {
        if (array_key_exists('signature', $parameters)) {
            throw new InvalidArgumentException(
                '"Signature" is a reserved parameter when generating signed routes. Please rename your route parameter.'
            );
        }

        if (array_key_exists('expires', $parameters)) {
            throw new InvalidArgumentException(
                '"Expires" is a reserved parameter when generating signed routes. Please rename your route parameter.'
            );
        }
    }
}
