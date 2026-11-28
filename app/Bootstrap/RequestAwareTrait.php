<?php

namespace App\Bootstrap;

use Illuminate\Http\Request;
use Illuminate\Support\Traits\ForwardsCalls;

trait RequestAwareTrait
{
    use ForwardsCalls;

    /**
     * The request instance
     */
    protected Request|null $request = null;

    /**
     * Inject request object dan synchronize semua global arrays ($_GET, $_POST, $_SERVER, etc)
     * dengan data dari request object. Menggunakan Symfony's overrideGlobals() method
     * untuk proper header handling dan $_REQUEST merge sesuai php.ini config.
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        if (method_exists($request, 'overrideGlobals')) {
            $request->overrideGlobals();
        }

        return $this;
    }

    /**
     * Get the request instance
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * Check if request instance is available
     */
    public function hasRequest(): bool
    {
        return $this->request !== null;
    }

    /**
     * This allows the trait to proxy method calls to the injected Request instance.
     *
     * @throws \BadMethodCallException
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->getRequest(), $method, $parameters);
    }
}
