<?php

namespace Tensor;

use Tensor\Exceptions\InvalidArgumentException;
use Tensor\Exceptions\DimensionalityMismatch;
use Tensor\Exceptions\RuntimeException;
use ArrayIterator;
use Traversable;

use function count;
use function is_float;
use function array_slice;
use function array_fill;
use function gettype;

/**
 * Vector
 *
 * A one dimensional (rank 1) tensor with integer and/or floating point elements.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class Vector implements Tensor
{
    /**
     * The 1-d sequential array that holds the values of the vector.
     *
     * @var list<float>
     */
    protected array $a;

    /**
     * The number of elements in the vector.
     *
     * @var int<0,max>
     */
    protected int $n;

    /**
     * Factory method to build a new vector from an array.
     *
     * @param (int|float)[] $a
     * @return mixed
     */
    public static function build(array $a = [])
    {
        return new static($a, true);
    }

    /**
     * Build a vector foregoing any validation for quicker instantiation.
     *
     * @param (int|float)[] $a
     * @return mixed
     */
    public static function quick(array $a = [])
    {
        return new static($a, false);
    }

    /**
     * Build a vector of zeros with n elements.
     *
     * @param int $n
     * @return self
     */
    public static function zeros(int $n) : self
    {
        return static::fill(0.0, $n);
    }

    /**
     * Build a vector of ones with n elements.
     *
     * @param int $n
     * @return self
     */
    public static function ones(int $n) : self
    {
        return static::fill(1.0, $n);
    }

    /**
     * Fill a vector with a given value.
     *
     * @param float $value
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function fill(float $value, int $n) : self
    {
        if ($n < 1) {
            throw new InvalidArgumentException('Number of elements'
                . " must be greater than 0, $n given.");
        }

        return static::quick(array_fill(0, $n, $value));
    }

    /**
     * Return a random uniform vector with values between 0 and 1.
     *
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function rand(int $n) : self
    {
        if ($n < 1) {
            throw new InvalidArgumentException('Number of elements'
                . " must be greater than 0, $n given.");
        }

        $max = getrandmax();

        $a = [];

        while (count($a) < $n) {
            $a[] = rand() / $max;
        }

        return static::quick($a);
    }

    /**
     * Return a standard normally distributed (Gaussian) random vector with mean 0
     * and unit variance.
     *
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function gaussian(int $n) : self
    {
        if ($n < 1) {
            throw new InvalidArgumentException('Number of elements'
                . " must be greater than 0, $n given.");
        }

        $max = getrandmax();

        $a = [];

        while (count($a) < $n) {
            $r = sqrt(-2.0 * log(rand() / $max));

            $phi = rand() / $max * TWO_PI;

            $a[] = $r * sin($phi);
            $a[] = $r * cos($phi);
        }

        if (count($a) > $n) {
            $a = array_slice($a, 0, $n);
        }

        return static::quick($a);
    }

    /**
     * Generate a vector with n elements from a Poisson distribution.
     *
     * @param int $n
     * @param float $lambda
     * @throws InvalidArgumentException
     * @return self
     */
    public static function poisson(int $n, float $lambda = 1.0) : self
    {
        if ($n < 1) {
            throw new InvalidArgumentException('Number of elements'
                . " must be greater than 0, $n given.");
        }

        $max = getrandmax();

        $l = exp(-$lambda);

        $a = [];

        while (count($a) < $n) {
            $k = 0.0;
            $p = 1.0;

            while ($p > $l) {
                ++$k;

                $p *= rand() / $max;
            }

            $a[] = $k - 1.0;
        }

        return static::quick($a);
    }

    /**
     * Return a uniform random vector with mean 0 and unit variance.
     *
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function uniform(int $n) : self
    {
        if ($n < 1) {
            throw new InvalidArgumentException('Number of elements'
                . " must be greater than 0, $n given.");
        }

        $max = getrandmax();

        $a = [];

        while (count($a) < $n) {
            $a[] = rand(-$max, $max) / $max;
        }

        return static::quick($a);
    }

    /**
     * Return evenly spaced values within a given interval.
     *
     * @param float $start
     * @param float $end
     * @param float $interval
     * @return self
     */
    public static function range(float $start, float $end, float $interval = 1.0) : self
    {
        return static::quick(range($start, $end, $interval));
    }

    /**
     * Return a vector of n evenly spaced numbers between minimum and maximum.
     *
     * @param float $min
     * @param float $max
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function linspace(float $min, float $max, int $n) : self
    {
        if ($min > $max) {
            throw new InvalidArgumentException('Minimum must be'
                . ' less than maximum.');
        }

        if ($n < 2) {
            throw new InvalidArgumentException('Number of elements'
                . " must be greater than 1, $n given.");
        }

        $k = $n - 1;

        $interval = abs($max - $min) / $k;

        $a = [$min];

        while (count($a) < $k) {
            $a[] = end($a) + $interval;
        }

        $a[] = $max;

        return self::quick($a);
    }

    /**
     * @param mixed[] $a
     * @param bool $validate
     */
    final public function __construct(array $a, bool $validate = true)
    {
        if ($a and $validate) {
            $a = array_values($a);

            foreach ($a as &$valueA) {
                if (!is_float($valueA)) {
                    $valueA = (float) $valueA;
                }
            }
        }

        $this->a = $a;
        $this->n = count($a);
    }

    /**
     * Return a tuple with the dimensionality of the tensor.
     *
     * @return array{int<0,max>}
     */
    public function shape() : array
    {
        return [$this->n];
    }

    /**
     * Return the shape of the tensor as a string.
     *
     * @return string
     */
    public function shapeString() : string
    {
        return (string) $this->n;
    }

    /**
     * Return the number of elements in the vector.
     *
     * @return int<0,max>
     */
    public function size() : int
    {
        return $this->n;
    }

    /**
     * Return the number of rows in the vector.
     *
     * @return int<0,max>
     */
    public function m() : int
    {
        return 1;
    }

    /**
     * Return the number of columns in the vector.
     *
     * @return int<0,max>
     */
    public function n() : int
    {
        return $this->n;
    }

    /**
     * Return the vector as an array.
     *
     * @return list<float>
     */
    public function asArray() : array
    {
        return $this->a;
    }

    /**
     * Return this vector as a row matrix.
     *
     * @return Matrix
     */
    public function asRowMatrix() : Matrix
    {
        return Matrix::quick([$this->a]);
    }

    /**
     * Return this vector as a column matrix.
     *
     * @return Matrix
     */
    public function asColumnMatrix() : Matrix
    {
        $b = [];

        foreach ($this->a as $valueA) {
            $b[] = [$valueA];
        }

        return Matrix::quick($b);
    }

    /**
     * Return a matrix in the shape specified.
     *
     * @param int $m
     * @param int $n
     * @throws InvalidArgumentException
     * @return Matrix
     */
    public function reshape(int $m, int $n) : Matrix
    {
        $p = $m * $n;

        if ($p !== $this->n) {
            throw new InvalidArgumentException("$p elements are"
                . " expected but vector has {$this->n}.");
        }

        $k = 0;

        $b = [];

        while (count($b) < $m) {
            $rowB = [];

            while (count($rowB) < $n) {
                $rowB[] = $this->a[$k++];
            }

            $b[] = $rowB;
        }

        return Matrix::quick($b);
    }

    /**
     * Transpose the vector i.e. rotate it.
     *
     * @return mixed
     */
    public function transpose()
    {
        return ColumnVector::quick($this->a);
    }

    /**
     * Map a function over the elements in the vector and return a new vector.
     *
     * @internal
     *
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback) : self
    {
        return static::quick(array_map($callback, $this->a));
    }

    /**
     * Reduce the vector down to a scalar.
     *
     * @internal
     *
     * @param callable $callback
     * @param float $initial
     * @return float
     */
    public function reduce(callable $callback, float $initial = 0.0) : float
    {
        return array_reduce($this->a, $callback, $initial);
    }

    /**
     * Compute the dot product of this vector and another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return float
     */
    public function dot(Vector $b) : float
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but vector B has {$b->size()}.");
        }

        $sigma = 0.0;

        foreach ($b as $i => $valueB) {
            $sigma += $this->a[$i] * $valueB;
        }

        return $sigma;
    }

    /**
     * Compute the vector-matrix product of this vector and matrix a.
     *
     * @param Matrix $b
     * @return Matrix
     */
    public function matmul(Matrix $b) : Matrix
    {
        return $this->asRowMatrix()->matmul($b);
    }

    /**
     * Return the inner product of two vectors.
     *
     * @param Vector $b
     * @return float
     */
    public function inner(Vector $b) : float
    {
        return $this->dot($b);
    }

    /**
     * Calculate the outer product of this and another vector.
     *
     * @param Vector $b
     * @return Matrix
     */
    public function outer(Vector $b) : Matrix
    {
        $c = [];

        $bHat = $b->asArray();

        foreach ($this->a as $valueA) {
            $rowC = [];

            foreach ($bHat as $valueB) {
                $rowC[] = $valueA * $valueB;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the 1D convolution of this vector and a kernel vector with given stride.
     *
     * @param Vector $b
     * @param int $stride
     * @throws InvalidArgumentException
     * @return self
     */
    public function convolve(Vector $b, int $stride = 1) : self
    {
        $n = $b->size();

        if ($n > $this->n) {
            throw new InvalidArgumentException('Vector B cannot be'
                . ' larger than Vector A.');
        }

        if ($stride < 1) {
            throw new InvalidArgumentException('Stride cannot be'
                . " less than 1, $stride given.");
        }

        $nHat = $this->n + $n - 1;

        $b = $b->asArray();

        $c = [];

        for ($i = 0; $i < $nHat; $i += $stride) {
            $jmin = $i >= $n - 1 ? $i - ($n - 1) : 0;
            $jmax = $i < $this->n ? $i : $this->n - 1;

            $sigma = 0.0;

            for ($j = $jmin; $j <= $jmax; ++$j) {
                $sigma += $this->a[$j] * $b[$i - $j];
            }

            $c[] = $sigma;
        }

        return static::quick($c);
    }

    /**
     * Calculate the L1 or Manhattan norm of the vector.
     *
     * @return float
     */
    public function l1Norm() : float
    {
        return $this->abs()->sum();
    }

    /**
     * Calculate the L2 or Euclidean norm of the vector.
     *
     * @return float
     */
    public function l2Norm() : float
    {
        return sqrt($this->square()->sum());
    }

    /**
     * Calculate the p-norm of the vector.
     *
     * @param float $p
     * @throws InvalidArgumentException
     * @return float
     */
    public function pNorm(float $p = 3.0) : float
    {
        if ($p <= 0.0) {
            throw new InvalidArgumentException("P must be greater than 0, $p given.");
        }

        return $this->abs()->powScalar($p)->sum() ** (1.0 / $p);
    }

    /**
     * Calculate the max norm of the vector.
     *
     * @return float
     */
    public function maxNorm() : float
    {
        return (float) $this->abs()->max();
    }

    /**
     * A universal function to multiply this vector with another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function multiply($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->multiplyVector($b);

                    case $b instanceof Matrix:
                        return $this->multiplyMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->multiplyScalar($b);
        }

        throw new InvalidArgumentException('Cannot multiply'
            . ' vector to the given input.');
    }

    /**
     * A universal function to divide this vector by another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function divide($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->divideVector($b);

                    case $b instanceof Matrix:
                        return $this->divideMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->divideScalar($b);
        }

        throw new InvalidArgumentException('Cannot divide'
            . ' vector by the given input.');
    }

    /**
     * A universal function to add this vector with another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function add($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->addVector($b);

                    case $b instanceof Matrix:
                        return $this->addMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->addScalar($b);
        }

        throw new InvalidArgumentException('Cannot add'
            . ' vector to the given input.');
    }

    /**
     * A universal function to subtract this vector from another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function subtract($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->subtractVector($b);

                    case $b instanceof Matrix:
                        return $this->subtractMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->subtractScalar($b);
        }

        throw new InvalidArgumentException('Cannot subtract'
            . ' vector from the given input.');
    }

    /**
     * A universal function to raise this vector to the power of another
     * tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function pow($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->powVector($b);

                    case $b instanceof Matrix:
                        return $this->powMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->powScalar($b);
        }

        throw new InvalidArgumentException('Cannot raise'
            . ' vector to the power of the given input.');
    }

    /**
     * A universal function to compute the modulus of this vector and
     * another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function mod($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->modVector($b);

                    case $b instanceof Matrix:
                        return $this->modMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->modScalar($b);
        }

        throw new InvalidArgumentException('Cannot mod'
            . ' vector with the given input.');
    }

    /**
     * A universal function to compute the equality comparison of
     * this vector and another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function equal($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->equalVector($b);

                    case $b instanceof Matrix:
                        return $this->equalMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->equalScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' vector with the given input.');
    }

    /**
     * A universal function to compute the not equal comparison of
     * this vector and another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function notEqual($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->notEqualVector($b);

                    case $b instanceof Matrix:
                        return $this->notEqualMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->notEqualScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' vector with the given input.');
    }

    /**
     * A universal function to compute the greater than comparison of
     * this vector and another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function greater($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->greaterVector($b);

                    case $b instanceof Matrix:
                        return $this->greaterMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->greaterScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' vector with the given input.');
    }

    /**
     * A universal function to compute the greater than or equal to
     * comparison of this vector and another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function greaterEqual($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->greaterEqualVector($b);

                    case $b instanceof Matrix:
                        return $this->greaterEqualMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->greaterEqualScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' vector with the given input.');
    }

    /**
     * A universal function to compute the less than comparison of
     * this vector and another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function less($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->lessVector($b);

                    case $b instanceof Matrix:
                        return $this->lessMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->lessScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' vector with the given input.');
    }

    /**
     * A universal function to compute the less than or equal to
     * comparison of this vector and another tensor element-wise.
     *
     * @param mixed $b
     * @throws InvalidArgumentException
     * @return mixed
     */
    public function lessEqual($b)
    {
        switch (gettype($b)) {
            case 'object':
                switch (true) {
                    case $b instanceof Vector:
                        return $this->lessEqualVector($b);

                    case $b instanceof Matrix:
                        return $this->lessEqualMatrix($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->lessEqualScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' vector with the given input.');
    }

    /**
     * Return the reciprocal of the vector element-wise.
     *
     * @return self
     */
    public function reciprocal() : self
    {
        return static::ones($this->n)
            ->divideVector($this);
    }

    /**
     * Return the absolute value of the vector.
     *
     * @return self
     */
    public function abs() : self
    {
        return $this->map('abs');
    }

    /**
     * Square the vector.
     *
     * @return self
     */
    public function square() : self
    {
        return $this->multiplyVector($this);
    }

    /**
     * Take the square root of the vector.
     *
     * @return self
     */
    public function sqrt() : self
    {
        return $this->map('sqrt');
    }

    /**
     * Exponentiate each element in the vector.
     *
     * @return self
     */
    public function exp() : self
    {
        return $this->map('exp');
    }

    /**
     * Return the exponential of the tensor minus 1.
     *
     * @return self
     */
    public function expm1() : self
    {
        return $this->map('expm1');
    }

    /**
     * Take the log to the given base of each element in the vector.
     *
     * @param float $base
     * @return self
     */
    public function log(float $base = M_E) : self
    {
        $b = [];

        foreach ($this->a as $valueA) {
            $b[] = log($valueA, $base);
        }

        return static::quick($b);
    }

    /**
     * Return the log of 1 plus the tensor.
     *
     * @return self
     */
    public function log1p() : self
    {
        return $this->map('log1p');
    }

    /**
     * Return the sine of this vector.
     *
     * @return self
     */
    public function sin() : self
    {
        return $this->map('sin');
    }

    /**
     * Compute the arc sine of the vector.
     *
     * @return self
     */
    public function asin() : self
    {
        return $this->map('asin');
    }

    /**
     * Return the cosine of this vector.
     *
     * @return self
     */
    public function cos() : self
    {
        return $this->map('cos');
    }

    /**
     * Compute the arc cosine of the vector.
     *
     * @return self
     */
    public function acos() : self
    {
        return $this->map('acos');
    }

    /**
     * Return the tangent of this vector.
     *
     * @return self
     */
    public function tan() : self
    {
        return $this->map('tan');
    }

    /**
     * Compute the arc tangent of the vector.
     *
     * @return self
     */
    public function atan() : self
    {
        return $this->map('atan');
    }

    /**
     * Convert angles from radians to degrees.
     *
     * @return self
     */
    public function rad2deg() : self
    {
        return $this->map('rad2deg');
    }

    /**
     * Convert angles from degrees to radians.
     *
     * @return self
     */
    public function deg2rad() : self
    {
        return $this->map('deg2rad');
    }

    /**
     * The sum of the vector.
     *
     * @return float
     */
    public function sum() : float
    {
        return array_sum($this->a);
    }

    /**
     * Return the product of the vector.
     *
     * @return float
     */
    public function product() : float
    {
        return array_product($this->a);
    }

    /**
     * Return the minimum element in the vector.
     *
     * @return float
     */
    public function min() : float
    {
        return (float) min($this->a);
    }

    /**
     * Return the maximum element in the vector.
     *
     * @return float
     */
    public function max() : float
    {
        return (float) max($this->a);
    }

    /**
     * Return the mean of the vector.
     *
     * @return float
     */
    public function mean() : float
    {
        return $this->sum() / $this->n;
    }

    /**
     * Return the median of the vector.
     *
     * @return float
     */
    public function median() : float
    {
        $mid = intdiv($this->n, 2);

        $a = $this->a;

        sort($a);

        if ($this->n % 2 === 1) {
            $median = $a[$mid];
        } else {
            $median = ($a[$mid - 1] + $a[$mid]) / 2.;
        }

        return $median;
    }

    /**
     * Return the q'th quantile of the vector.
     *
     * @param float $q
     * @throws InvalidArgumentException
     * @return float
     */
    public function quantile(float $q) : float
    {
        if ($q < 0.0 or $q > 1.0) {
            throw new InvalidArgumentException('Q must be between'
                . " 0 and 100, $q given.");
        }

        $a = $this->a;

        sort($a);

        $x = $q * ($this->n - 1) + 1;

        $xHat = (int) $x;

        $remainder = $x - $xHat;

        $t = $a[$xHat - 1];

        return $t + $remainder * ($a[$xHat] - $t);
    }

    /**
     * Return the variance of the vector.
     *
     * @param float|null $mean
     * @return float
     */
    public function variance($mean = null) : float
    {
        if ($mean === null) {
            $mean = $this->mean();
        }

        $ssd = $this->subtractScalar($mean)
            ->square()
            ->sum();

        return $ssd / $this->n;
    }

    /**
     * Round the elements in the matrix to a given decimal place.
     *
     * @param int $precision
     * @throws InvalidArgumentException
     * @return self
     */
    public function round(int $precision = 0) : self
    {
        if ($precision < 0) {
            throw new InvalidArgumentException('Decimal precision cannot'
                . " be less than 0, $precision given.");
        }

        $b = [];

        foreach ($this->a as $valueA) {
            $b[] = round($valueA, $precision);
        }

        return static::quick($b);
    }

    /**
     * Round the elements in the vector down to the nearest integer.
     *
     * @return self
     */
    public function floor() : self
    {
        return $this->map('floor');
    }

    /**
     * Round the elements in the vector up to the nearest integer.
     *
     * @return self
     */
    public function ceil() : self
    {
        return $this->map('ceil');
    }

    /**
     * Clip the elements in the vector to be between given minimum and maximum
     * and return a new vector.
     *
     * @param float $min
     * @param float $max
     * @throws InvalidArgumentException
     * @return self
     */
    public function clip(float $min, float $max) : self
    {
        if ($min > $max) {
            throw new InvalidArgumentException('Minimum cannot be'
                . ' greater than maximum.');
        }

        $b = [];

        foreach ($this->a as $valueA) {
            if ($valueA > $max) {
                $b[] = $max;

                continue;
            }

            if ($valueA < $min) {
                $b[] = $min;

                continue;
            }

            $b[] = $valueA;
        }

        return static::quick($b);
    }

    /**
     * Clip the tensor to be lower bounded by a given minimum.
     *
     * @param float $min
     * @return self
     */
    public function clipLower(float $min) : self
    {
        $b = [];

        foreach ($this->a as $valueA) {
            if ($valueA < $min) {
                $b[] = $min;

                continue;
            }

            $b[] = $valueA;
        }

        return static::quick($b);
    }

    /**
     * Clip the tensor to be upper bounded by a given maximum.
     *
     * @param float $max
     * @return self
     */
    public function clipUpper(float $max) : self
    {
        $b = [];

        foreach ($this->a as $valueA) {
            if ($valueA > $max) {
                $b[] = $max;

                continue;
            }

            $b[] = $valueA;
        }

        return static::quick($b);
    }

    /**
     * Return the element-wise sign indication.
     *
     * @return self
     */
    public function sign() : self
    {
        $b = [];

        foreach ($this->a as $valueA) {
            if ($valueA > 0) {
                $b[] = 1.0;
            } elseif ($valueA < 0) {
                $b[] = -1.0;
            } else {
                $b[] = 0.0;
            }
        }

        return static::quick($b);
    }

    /**
     * Negate the vector i.e take the negative of each value element-wise.
     *
     * @return self
     */
    public function negate() : self
    {
        $b = [];

        foreach ($this->a as $valueA) {
            $b[] = -$valueA;
        }

        return static::quick($b);
    }

    /**
     * Multiply this vector with a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function multiplyMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA * $rowB[$j];
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Divide this vector with a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function divideMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA / $rowB[$j];
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Add this vector to a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function addMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA + $rowB[$j];
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Subtract a matrix from this vector.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function subtractMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA - $rowB[$j];
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Raise this vector to the power of a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function powMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA ** $rowB[$j];
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Mod this vector with a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function modMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA % $rowB[$j];
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise equality comparison of this vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function equalMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA == $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise not equal comparison of this vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function notEqualMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA != $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise greater than comparison of this vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function greaterMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA > $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise greater than or equal to comparison of this vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function greaterEqualMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA >= $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise less than comparison of this vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function lessMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA < $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise less than or equal to comparison of this vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function lessEqualMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->n()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        $c = [];

        foreach ($b->asArray() as $rowB) {
            $rowC = [];

            foreach ($this->a as $j => $valueA) {
                $rowC[] = $valueA <= $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Multiply this vector with another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function multiplyVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] * $valueB;
        }

        return static::quick($c);
    }

    /**
     * Divide this vector by another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function divideVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] / $valueB;
        }

        return static::quick($c);
    }

    /**
     * Add this vector to another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function addVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] + $valueB;
        }

        return static::quick($c);
    }

    /**
     * Subtract a vector from this vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function subtractVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] - $valueB;
        }

        return static::quick($c);
    }

    /**
     * Raise this vector to a power of another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function powVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] ** $valueB;
        }

        return static::quick($c);
    }

    /**
     * Calculate the modulus of this vector with another vector element-wise.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function modVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] % $valueB;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise equality comparison of this vector and a another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function equalVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] == $valueB ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise not equal comparison of this vector and a another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function notEqualVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] != $valueB ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise greater than comparison of this vector and a another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function greaterVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] > $valueB ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise greater than or equal to comparison of this vector and a another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function greaterEqualVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] >= $valueB ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise less than comparison of this vector and a another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function lessVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $c[] = $this->a[$i] < $valueB ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise less than or equal to comparison of this vector and a another vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return static
     */
    public function lessEqualVector(Vector $b) : self
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Vector A expects'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        $c = [];

        foreach ($b as $i => $valueB) {
            $c[] = $this->a[$i] <= $valueB ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Multiply this vector by a scalar.
     *
     * @param float $b
     * @return static
     */
    public function multiplyScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA * $b;
        }

        return static::quick($c);
    }

    /**
     * Divide this vector by a scalar.
     *
     * @param float $b
     * @return static
     */
    public function divideScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA / $b;
        }

        return static::quick($c);
    }

    /**
     * Add a scalar to this vector.
     *
     * @param float $b
     * @return static
     */
    public function addScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA + $b;
        }

        return static::quick($c);
    }

    /**
     * Subtract a scalar from this vector.
     *
     * @param float $b
     * @return static
     */
    public function subtractScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA - $b;
        }

        return static::quick($c);
    }

    /**
     * Raise the vector to a the power of a scalar value.
     *
     * @param float $b
     * @return static
     */
    public function powScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA ** $b;
        }

        return static::quick($c);
    }

    /**
     * Calculate the modulus of this vector with a scalar.
     *
     * @param float $b
     * @return static
     */
    public function modScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA % $b;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise equality comparison of this vector and a scalar.
     *
     * @param float $b
     * @return static
     */
    public function equalScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA == $b ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise not equal comparison of this vector and a scalar.
     *
     * @param float $b
     * @return static
     */
    public function notEqualScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA != $b ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise greater than comparison of this vector and a scalar.
     *
     * @param float $b
     * @return static
     */
    public function greaterScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA > $b ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise greater than or equal to comparison of this vector and a scalar.
     *
     * @param float $b
     * @return static
     */
    public function greaterEqualScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA >= $b ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise less than comparison of this vector and a scalar.
     *
     * @param float $b
     * @return static
     */
    public function lessScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA < $b ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Return the element-wise less than or equal to comparison of this vector and a scalar.
     *
     * @param float $b
     * @return static
     */
    public function lessEqualScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $valueA) {
            $c[] = $valueA <= $b ? 1 : 0;
        }

        return static::quick($c);
    }

    /**
     * Count method to implement countable interface.
     *
     * @return int
     */
    public function count() : int
    {
        return $this->n;
    }

    /**
     * @param mixed $index
     * @param mixed[] $values
     * @throws RuntimeException
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($index, $values) : void
    {
        throw new RuntimeException('Vector cannot be mutated directly.');
    }

    /**
     * Does a given column exist in the matrix.
     *
     * @param mixed $index
     * @return bool
     */
    public function offsetExists($index) : bool
    {
        return isset($this->a[$index]);
    }

    /**
     * @param mixed $index
     * @throws RuntimeException
     */
    public function offsetUnset($index) : void
    {
        throw new RuntimeException('Vector cannot be mutated directly.');
    }

    /**
     * Return a row from the matrix at the given index.
     *
     * @param mixed $index
     * @throws InvalidArgumentException
     * @return float
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($index)
    {
        if (isset($this->a[$index])) {
            return $this->a[$index];
        }

        throw new InvalidArgumentException('Element not found at the'
            . " given offset $index.");
    }

    /**
     * Get an iterator for the rows in the matrix.
     *
     * @return \ArrayIterator<int,float>
     */
    #[\ReturnTypeWillChange]
    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->a);
    }
}
