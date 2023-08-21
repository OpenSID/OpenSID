<?php // phpcs:disable WebimpressCodingStandard.NamingConventions.AbstractClass.Prefix,Generic.NamingConventions.ConstructorName.OldStyle

namespace Laminas\Session\Storage;

use ArrayAccess;
use Laminas\Session\Exception;
use Laminas\Session\Storage\AbstractSessionArrayStorage;
use Laminas\Session\Storage\ArrayStorage;
use Laminas\Session\Storage\StorageInterface;
use Laminas\Stdlib\ArrayObject;
use Laminas\Stdlib\ArrayUtils;
use Traversable;

use function class_exists;
use function class_implements;
use function class_parents;
use function get_class;
use function gettype;
use function in_array;
use function is_array;
use function is_object;
use function is_string;
use function sprintf;

abstract class Factory
{
    /**
     * Create and return a StorageInterface instance
     *
     * @param  string                             $type
     * @param  array|Traversable                  $options
     * @return StorageInterface
     * @throws Exception\InvalidArgumentException For unrecognized $type or individual options.
     */
    public static function factory($type, $options = [])
    {
        if (! is_string($type)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects the $type argument to be a string class name; received "%s"',
                __METHOD__,
                is_object($type) ? get_class($type) : gettype($type)
            ));
        }
        if (! class_exists($type)) {
            $class = __NAMESPACE__ . '\\' . $type;
            if (! class_exists($class)) {
                throw new Exception\InvalidArgumentException(sprintf(
                    '%s expects the $type argument to be a valid class name; received "%s"',
                    __METHOD__,
                    $type
                ));
            }
            $type = $class;
        }

        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }
        if (! is_array($options)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s expects the $options argument to be an array or Traversable; received "%s"',
                __METHOD__,
                is_object($options) ? get_class($options) : gettype($options)
            ));
        }

        switch (true) {
            case in_array(AbstractSessionArrayStorage::class, class_parents($type)):
                return static::createSessionArrayStorage($type, $options);
            case $type === ArrayStorage::class:
            case in_array(ArrayStorage::class, class_parents($type)):
                return static::createArrayStorage($type, $options);
            case in_array(StorageInterface::class, class_implements($type)):
                return new $type($options);
            default:
                throw new Exception\InvalidArgumentException(sprintf(
                    'Unrecognized type "%s" provided; expects a class implementing %s\StorageInterface',
                    $type,
                    __NAMESPACE__
                ));
        }
    }

    /**
     * Create a storage object from an ArrayStorage class (or a descendent)
     *
     * @param  string       $type
     * @param  array        $options
     * @return ArrayStorage
     */
    protected static function createArrayStorage($type, $options)
    {
        $input         = [];
        $flags         = ArrayObject::ARRAY_AS_PROPS;
        $iteratorClass = 'ArrayIterator';

        if (isset($options['input']) && null !== $options['input']) {
            if (! is_array($options['input'])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    '%s expects the "input" option to be an array; received "%s"',
                    $type,
                    is_object($options['input']) ? get_class($options['input']) : gettype($options['input'])
                ));
            }
            $input = $options['input'];
        }

        if (isset($options['flags'])) {
            $flags = $options['flags'];
        }

        if (isset($options['iterator_class'])) {
            if (! class_exists($options['iterator_class'])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    '%s expects the "iterator_class" option to be a valid class; received "%s"',
                    $type,
                    is_object($options['iterator_class'])
                        ? get_class($options['iterator_class'])
                        : gettype($options['iterator_class'])
                ));
            }
            $iteratorClass = $options['iterator_class'];
        }

        return new $type($input, $flags, $iteratorClass);
    }

    /**
     * Create a storage object from a class extending AbstractSessionArrayStorage
     *
     * @param  string                             $type
     * @param  array                              $options
     * @return AbstractSessionArrayStorage
     * @throws Exception\InvalidArgumentException If the input option is invalid.
     */
    protected static function createSessionArrayStorage($type, array $options)
    {
        $input = null;
        if (isset($options['input'])) {
            $input = $options['input'];
            if (
                ! is_array($input)
                && ! $input instanceof ArrayAccess
            ) {
                throw new Exception\InvalidArgumentException(sprintf(
                    '%s expects the "input" option to be null, an array, or to implement ArrayAccess; received "%s"',
                    $type,
                    is_object($input) ? get_class($input) : gettype($input)
                ));
            }
        }

        return new $type($input);
    }
}
