<?php // phpcs:disable Generic.NamingConventions.ConstructorName.OldStyle,WebimpressCodingStandard.NamingConventions.AbstractClass.Prefix

namespace Laminas\Captcha;

use Laminas\Stdlib\ArrayUtils;
use Traversable;

use function class_exists;
use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function sprintf;
use function strtolower;

abstract class Factory
{
    /** @var array Known captcha types */
    protected static $classMap = [
        'dumb'      => Dumb::class,
        'figlet'    => Figlet::class,
        'image'     => Image::class,
        'recaptcha' => ReCaptcha::class,
    ];

    /**
     * Create a captcha adapter instance
     *
     * @param  iterable<string, mixed> $options
     * @return AdapterInterface
     * @throws Exception\InvalidArgumentException For a non-array, non-Traversable $options.
     * @throws Exception\DomainException If class is missing or invalid.
     */
    public static function factory($options)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (! is_array($options)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects an array or Traversable argument; received "%s"',
                __METHOD__,
                is_object($options) ? get_class($options) : gettype($options)
            ));
        }

        if (! isset($options['class'])) {
            throw new Exception\DomainException(sprintf(
                '%s expects a "class" attribute in the options; none provided',
                __METHOD__
            ));
        }

        $class = $options['class'];
        if (isset(static::$classMap[strtolower($class)])) {
            $class = static::$classMap[strtolower($class)];
        }
        if (! class_exists($class)) {
            throw new Exception\DomainException(sprintf(
                '%s expects the "class" attribute to resolve to an existing class; received "%s"',
                __METHOD__,
                $class
            ));
        }

        unset($options['class']);

        if (isset($options['options'])) {
            $options = $options['options'];
        }
        $captcha = new $class($options);

        if (! $captcha instanceof AdapterInterface) {
            throw new Exception\DomainException(sprintf(
                '%s expects the "class" attribute to resolve to a valid %s instance; received "%s"',
                __METHOD__,
                AdapterInterface::class,
                $class
            ));
        }

        return $captcha;
    }
}
