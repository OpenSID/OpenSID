<?php

declare(strict_types=1);

namespace Laminas\Captcha;

use Laminas\Validator\ValidatorInterface;

/**
 * Generic Captcha adapter interface
 *
 * Each specific captcha implementation should implement this interface
 */
interface AdapterInterface extends ValidatorInterface
{
    /**
     * Generate a new captcha
     *
     * @return string new captcha ID
     */
    public function generate();

    /**
     * Set captcha name
     *
     * @param  string $name
     * @return AdapterInterface
     */
    public function setName($name);

    /**
     * Get captcha name
     *
     * @return string
     */
    public function getName();

    /**
     * Get helper name to use when rendering this captcha type
     *
     * @return string
     */
    public function getHelperName();
}
