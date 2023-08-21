<?php

declare(strict_types=1);

namespace Laminas\Captcha;

use Laminas\Text\Figlet\Figlet as FigletManager;

/**
 * Captcha based on figlet text rendering service
 *
 * Note that this engine seems not to like numbers
 */
class Figlet extends AbstractWord
{
    /**
     * Figlet text renderer
     *
     * @var FigletManager
     */
    protected $figlet;

    /**
     * Constructor
     *
     * @param null|iterable<string, mixed> $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->figlet = new FigletManager($options);
    }

    /**
     * Retrieve the composed figlet manager
     *
     * @return FigletManager
     */
    public function getFiglet()
    {
        return $this->figlet;
    }

    /**
     * Generate new captcha
     *
     * @return string
     */
    public function generate()
    {
        $this->useNumbers = false;
        return parent::generate();
    }

    /**
     * Get helper name used to render captcha
     *
     * @return string
     */
    public function getHelperName()
    {
        return 'captcha/figlet';
    }
}
