<?php

namespace Tensor;

use Tensor\Reductions\REF;
use Tensor\Reductions\RREF;
use Tensor\Decompositions\LU;
use Tensor\Decompositions\SVD;
use Tensor\Decompositions\Eigen;
use Tensor\Decompositions\Cholesky;
use Tensor\Exceptions\InvalidArgumentException;
use Tensor\Exceptions\DimensionalityMismatch;
use Tensor\Exceptions\RuntimeException;
use Tensor\Exceptions\NotImplemented;
use Traversable;

use function count;
use function is_float;
use function array_slice;
use function array_fill;
use function gettype;

/**
 * Matrix
 *
 * A two dimensional (rank 2) tensor with integer and/or floating point elements.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class Matrix implements Tensor
{
    /**
     * A 2-dimensional sequential array that holds the values of the matrix.
     *
     * @var list<list<float>>
     */
    protected array $a;

    /**
     * The number of rows in the matrix.
     *
     * @var int<0,max>
     */
    protected int $m;

    /**
     * The number of columns in the matrix.
     *
     * @var int<0,max>
     */
    protected int $n;

    /**
     * Factory method to build a new matrix from an array.
     *
     * @param array<array<int|float>> $a
     * @return self
     */
    public static function build(array $a = []) : self
    {
        return new self($a, true);
    }

    /**
     * Build a new matrix foregoing any validation for quicker instantiation.
     *
     * @param array<array<int|float>> $a
     * @return self
     */
    public static function quick(array $a = []) : self
    {
        return new self($a, false);
    }

    /**
     * Return an identity matrix with the given dimensions.
     *
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function identity(int $n) : self
    {
        if ($n < 1) {
            throw new InvalidArgumentException('N must be'
                . " greater than 0, $n given.");
        }

        $a = [];

        for ($i = 0; $i < $n; ++$i) {
            $rowA = [];

            for ($j = 0; $j < $n; ++$j) {
                $rowA[] = $i === $j ? 1.0 : 0.0;
            }

            $a[] = $rowA;
        }

        return self::quick($a);
    }

    /**
     * Return a zero matrix with the given dimensions.
     *
     * @param int $m
     * @param int $n
     * @return self
     */
    public static function zeros(int $m, int $n) : self
    {
        return self::fill(0.0, $m, $n);
    }

    /**
     * Return a one matrix with the given dimensions.
     *
     * @param int $m
     * @param int $n
     * @return self
     */
    public static function ones(int $m, int $n) : self
    {
        return self::fill(1.0, $m, $n);
    }

    /**
     * Build a diagonal matrix with the value of each element along the diagonal and 0s everywhere else.
     *
     * @param float[] $elements
     * @return self
     */
    public static function diagonal(array $elements) : self
    {
        $n = count($elements);

        $elements = array_values($elements);

        $a = [];

        for ($i = 0; $i < $n; ++$i) {
            $rowA = [];

            for ($j = 0; $j < $n; ++$j) {
                $rowA[] = $i === $j ? $elements[$i] : 0;
            }

            $a[] = $rowA;
        }

        return self::quick($a);
    }

    /**
     * Fill a matrix with a given value at each element.
     *
     * @param float $value
     * @param int $m
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function fill(float $value, int $m, int $n) : self
    {
        if ($m < 1) {
            throw new InvalidArgumentException('M must be'
                . " greater than 0, $m given.");
        }

        if ($n < 1) {
            throw new InvalidArgumentException('N must be'
                . " greater than 0, $n given.");
        }

        return self::quick(array_fill(0, $m, array_fill(0, $n, $value)));
    }

    /**
     * Return a random uniform matrix with values between 0 and 1.
     *
     * @param int $m
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function rand(int $m, int $n) : self
    {
        if ($m < 1) {
            throw new InvalidArgumentException('M must be'
                . " greater than 0, $m given.");
        }

        if ($n < 1) {
            throw new InvalidArgumentException('N must be'
                . " greater than 0, $n given.");
        }

        $max = getrandmax();

        $a = [];

        while (count($a) < $m) {
            $rowA = [];

            while (count($rowA) < $n) {
                $rowA[] = rand() / $max;
            }

            $a[] = $rowA;
        }

        return self::quick($a);
    }

    /**
     * Return a standard normally distributed random matrix i.e values between -1 and 1.
     *
     * @param int $m
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function gaussian(int $m, int $n) : self
    {
        if ($m < 1) {
            throw new InvalidArgumentException('M must be'
                . " greater than 0, $m given.");
        }

        if ($n < 1) {
            throw new InvalidArgumentException('N must be'
                . " greater than 0, $n given.");
        }

        $max = getrandmax();

        $a = $extras = [];

        while (count($a) < $m) {
            $rowA = [];

            if ($extras) {
                $rowA[] = array_pop($extras);
            }

            while (count($rowA) < $n) {
                $r = sqrt(-2.0 * log(rand() / $max));

                $phi = rand() / $max * TWO_PI;

                $rowA[] = $r * sin($phi);
                $rowA[] = $r * cos($phi);
            }

            if (count($rowA) > $n) {
                $extras[] = array_pop($rowA);
            }

            $a[] = $rowA;
        }

        return self::quick($a);
    }

    /**
     * Generate a m x n matrix with elements from a Poisson distribution.
     *
     * @param int $m
     * @param int $n
     * @param float $lambda
     * @throws InvalidArgumentException
     * @return self
     */
    public static function poisson(int $m, int $n, float $lambda = 1.0) : self
    {
        if ($m < 1) {
            throw new InvalidArgumentException('M must be'
                . " greater than 0, $m given.");
        }

        if ($n < 1) {
            throw new InvalidArgumentException('N must be'
                . " greater than 0, $n given.");
        }

        $max = getrandmax();

        $l = exp(-$lambda);

        $a = [];

        while (count($a) < $m) {
            $rowA = [];

            while (count($rowA) < $n) {
                $k = 0.0;
                $p = 1.0;

                while ($p > $l) {
                    ++$k;

                    $p *= rand() / $max;
                }

                $rowA[] = $k - 1.0;
            }

            $a[] = $rowA;
        }

        return self::quick($a);
    }

    /**
     * Return a uniform random matrix with mean 0 and unit variance.
     *
     * @param int $m
     * @param int $n
     * @throws InvalidArgumentException
     * @return self
     */
    public static function uniform(int $m, int $n) : self
    {
        if ($m < 1) {
            throw new InvalidArgumentException('M must be'
                . " greater than 0, $m given.");
        }

        if ($n < 1) {
            throw new InvalidArgumentException('N must be'
                . " greater than 0, $n given.");
        }

        $max = getrandmax();

        $a = [];

        while (count($a) < $m) {
            $rowA = [];

            while (count($rowA) < $n) {
                $rowA[] = rand(-$max, $max) / $max;
            }

            $a[] = $rowA;
        }

        return self::quick($a);
    }

    /**
     * @param array<array<int|float>> $a
     * @param bool $validate
     * @throws InvalidArgumentException
     */
    public function __construct(array $a, bool $validate = true)
    {
        $m = count($a);
        $n = count(current($a) ?: []);

        if ($a and $validate) {
            $a = array_values($a);

            foreach ($a as $i => &$rowA) {
                if (count($rowA) !== $n) {
                    throw new InvalidArgumentException('The number of columns'
                        . " must be equal for all rows, $n required but "
                        . count($rowA) . " given at row offset $i.");
                }

                $rowA = array_values($rowA);

                foreach ($rowA as &$valueA) {
                    if (!is_float($valueA)) {
                        $valueA = (float) $valueA;
                    }
                }
            }
        }

        $this->a = $a;
        $this->m = $m;
        $this->n = $n;
    }

    /**
     * Return a tuple with the dimensionality of the tensor.
     *
     * @return array{int<0,max>,int<0,max>}
     */
    public function shape() : array
    {
        return [$this->m, $this->n];
    }

    /**
     * Return the shape of the tensor as a string.
     *
     * @return string
     */
    public function shapeString() : string
    {
        return (string) $this->m . ' x ' . (string) $this->n;
    }

    /**
     * Is this a square matrix?
     *
     * @return bool
     */
    public function isSquare() : bool
    {
        return $this->m === $this->n;
    }

    /**
     * Return the number of elements in the tensor.
     *
     * @return int<0,max>
     */
    public function size() : int
    {
        return $this->m * $this->n;
    }

    /**
     * Return the number of rows in the matrix.
     *
     * @return int<0,max>
     */
    public function m() : int
    {
        return $this->m;
    }

    /**
     * Return the number of columns in the matrix.
     *
     * @return int<0,max>
     */
    public function n() : int
    {
        return $this->n;
    }

    /**
     * Return a row as a vector from the matrix.
     *
     * @param int $index
     * @return Vector
     */
    public function rowAsVector(int $index) : Vector
    {
        return $this->offsetGet($index);
    }

    /**
     * Return a column as a vector from the matrix.
     *
     * @param int $index
     * @return ColumnVector
     */
    public function columnAsVector(int $index) : ColumnVector
    {
        return ColumnVector::quick(array_column($this->a, $index));
    }

    /**
     * Return the diagonal elements of a square matrix as a vector.
     *
     * @throws InvalidArgumentException
     * @return Vector
     */
    public function diagonalAsVector() : Vector
    {
        if (!$this->isSquare()) {
            throw new InvalidArgumentException('Matrix must be'
                . " square, {$this->shapeString()} given.");
        }

        $b = [];

        foreach ($this->a as $i => $rowA) {
            $b[] = $rowA[$i];
        }

        return Vector::quick($b);
    }

    /**
     * Return the elements of the matrix in a 2-d array.
     *
     * @return list<list<float>>
     */
    public function asArray() : array
    {
        return $this->a;
    }

    /**
     * Return each row as a vector in an array.
     *
     * @return \Tensor\Vector[]
     */
    public function asVectors() : array
    {
        return array_map([Vector::class, 'quick'], $this->a);
    }

    /**
     * Return each column as a column vector in an array.
     *
     * @return \Tensor\ColumnVector[]
     */
    public function asColumnVectors() : array
    {
        $vectors = [];

        for ($i = 0; $i < $this->n; ++$i) {
            $vectors[] = $this->columnAsVector($i);
        }

        return $vectors;
    }

    /**
     * Flatten i.e unravel the matrix into a vector.
     *
     * @return Vector
     */
    public function flatten() : Vector
    {
        return Vector::quick(array_merge(...$this->a));
    }

    /**
     * Run a function over all of the elements in the matrix.
     *
     * @internal
     *
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback) : self
    {
        $b = [];

        foreach ($this->a as $rowA) {
            $b[] = array_map($callback, $rowA);
        }

        return self::quick($b);
    }

    /**
     * Reduce the matrix down to a scalar.
     *
     * @internal
     *
     * @param callable $callback
     * @param float $initial
     * @return float
     */
    public function reduce(callable $callback, float $initial = 0.0) : float
    {
        $carry = $initial;

        foreach ($this->a as $rowA) {
            foreach ($rowA as $valueA) {
                $carry = $callback($valueA, $carry);
            }
        }

        return $carry;
    }

    /**
     * Transpose the matrix i.e row become columns and columns become rows.
     *
     * @return self
     */
    public function transpose() : self
    {
        switch ($this->m) {
            case 0:
                return $this;

            case 1:
                $b = [];

                foreach (current($this->a) ?: [] as $row) {
                    $b[] = [$row];
                }

                return self::quick($b);

            default:
                return self::quick(array_map(null, ...$this->a));
        }
    }

    /**
     * Compute the inverse of the matrix.
     *
     * @return self
     */
    public function inverse() : self
    {
        $a = self::identity($this->m)
            ->augmentLeft($this)
            ->rref()
            ->a()
            ->asArray();

        $b = [];

        foreach ($a as $rowA) {
            $b[] = array_slice($rowA, $this->n);
        }

        return self::quick($b);
    }

    /**
     * Compute the (Moore-Penrose) pseudo inverse of the general matrix.
     *
     * @return self
     */
    public function pseudoinverse() : self
    {
        throw new NotImplemented('Pseudoinverse is not implemented in Tensor PHP.');
    }

    /**
     * Calculate the determinant of the matrix.
     *
     * @throws InvalidArgumentException
     * @return float
     */
    public function det() : float
    {
        if (!$this->isSquare()) {
            throw new InvalidArgumentException("Matrix must be square, {$this->shapeString()} given.");
        }

        $ref = $this->ref();

        $pi = $ref->a()->diagonalAsVector()->product();

        return $pi * (-1.0) ** $ref->swaps();
    }

    /**
     * Return the trace of the matrix i.e the sum of all diagonal elements of a square matrix.
     *
     * @return float
     */
    public function trace() : float
    {
        return $this->diagonalAsVector()->sum();
    }

    /**
     * Calculate the rank of the matrix i.e the number of pivots in its reduced row echelon form.
     *
     * @return int
     */
    public function rank() : int
    {
        $a = $this->rref()->a()->asArray();

        $pivots = 0;

        foreach ($a as $rowA) {
            foreach ($rowA as $valueA) {
                if ($valueA != 0) {
                    ++$pivots;

                    continue 2;
                }
            }
        }

        return $pivots;
    }

    /**
     * Is the matrix full rank?
     *
     * @return bool
     */
    public function fullRank() : bool
    {
        return $this->rank() === min($this->shape());
    }

    /**
     * Is the matrix symmetric i.e. is it equal to its own transpose?
     *
     * @return bool
     */
    public function symmetric() : bool
    {
        if (!$this->isSquare()) {
            return false;
        }

        for ($i = 0; $i < $this->m - 1; ++$i) {
            $rowA = $this->a[$i];

            for ($j = $i + 1; $j < $this->n; ++$j) {
                if ($rowA[$j] != $this->a[$j][$i]) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Multiply this matrix with another matrix (matrix-matrix product).
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function matmul(Matrix $b) : self
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Matrix A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $bT = $b->transpose()->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($bT as $columnB) {
                $sigma = 0.0;

                foreach ($rowA as $k => $valueA) {
                    $sigma += $valueA * $columnB[$k];
                }

                $rowC[] = $sigma;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Compute the dot product of this matrix and a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return ColumnVector
     */
    public function dot(Vector $b) : ColumnVector
    {
        if ($this->n !== $b->size()) {
            throw new DimensionalityMismatch('Matrix A requires'
                . " {$this->n} elements but Vector B has {$b->size()}.");
        }

        return $this->matmul($b->asColumnMatrix())->columnAsVector(0);
    }

    /**
     * Return the 2D convolution of this matrix and a kernel matrix with given stride using the "same" method for zero padding.
     *
     * @param Matrix $b
     * @param int $stride
     * @throws InvalidArgumentException
     * @return self
     */
    public function convolve(Matrix $b, int $stride = 1) : self
    {
        [$m, $n] = $b->shape();

        if ($m > $this->m or $n > $this->n) {
            throw new InvalidArgumentException('Matrix B cannot be'
                . ' larger than Matrix A.');
        }

        if ($stride < 1) {
            throw new InvalidArgumentException('Stride cannot be'
                . " less than 1, $stride given.");
        }

        $b = $b->asArray();

        $p = intdiv($m, 2);
        $q = intdiv($n, 2);

        $c = [];

        for ($i = 0; $i < $this->m; $i += $stride) {
            $rowC = [];

            for ($j = 0; $j < $this->n; $j += $stride) {
                $sigma = 0;

                foreach ($b as $k => $rowB) {
                    $x = $i + $p - $k;

                    if ($x < 0 or $x >= $this->m) {
                        continue;
                    }

                    $rowA = $this->a[$x];

                    foreach ($rowB as $l => $valueB) {
                        $y = $j + $q - $l;

                        if ($y < 0 or $y >= $this->n) {
                            continue;
                        }

                        $sigma += $rowA[$y] * $valueB;
                    }
                }

                $rowC[] = $sigma;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Calculate the row echelon form (REF) of the matrix.
     *
     * @return REF
     */
    public function ref() : REF
    {
        return REF::reduce($this);
    }

    /**
     * Return the reduced row echelon (RREF) form of the matrix.
     *
     * @return RREF
     */
    public function rref() : RREF
    {
        return RREF::reduce($this);
    }

    /**
     * Return the LU decomposition of the matrix.
     *
     * @return LU
     */
    public function lu() : LU
    {
        return LU::decompose($this);
    }

    /**
     * Return the lower triangular matrix of the Cholesky decomposition.
     *
     * @return Cholesky
     */
    public function cholesky() : Cholesky
    {
        return Cholesky::decompose($this);
    }

    /**
     * Compute the eigenvalues and eigenvectors of the matrix.
     *
     * @param bool $symmetric
     * @return Eigen
     */
    public function eig(bool $symmetric = false) : Eigen
    {
        return Eigen::decompose($this, $symmetric);
    }

    /**
     * Return the singular value decomposition (SVD) of the matrix.
     *
     * @return SVD
     */
    public function svd() : SVD
    {
        return SVD::decompose($this);
    }

    /**
     * Return the L1 norm of the matrix.
     *
     * @return float
     */
    public function l1Norm() : float
    {
        return $this->transpose()->abs()->sum()->max();
    }

    /**
     * Return the L2 norm of the matrix.
     *
     * @return float
     */
    public function l2Norm() : float
    {
        return sqrt($this->square()->sum()->sum());
    }

    /**
     * Return the infinity norm of the matrix.
     *
     * @return float
     */
    public function infinityNorm() : float
    {
        return $this->abs()->sum()->max();
    }

    /**
     * Return the max norm of the matrix.
     *
     * @return float
     */
    public function maxNorm() : float
    {
        return $this->abs()->max()->max();
    }

    /**
     * A universal function to multiply this matrix with another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->multiplyMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->multiplyColumnVector($b);

                    case $b instanceof Vector:
                        return $this->multiplyVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->multiplyScalar($b);
        }

        throw new InvalidArgumentException('Cannot multiply'
            . ' matrix by the given input.');
    }

    /**
     * A universal function to divide this matrix by another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->divideMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->divideColumnVector($b);

                    case $b instanceof Vector:
                        return $this->divideVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->divideScalar($b);
        }

        throw new InvalidArgumentException('Cannot divide'
            . ' matrix by the given input.');
    }

    /**
     * A universal function to add this matrix with another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->addMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->addColumnVector($b);

                    case $b instanceof Vector:
                        return $this->addVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->addScalar($b);
        }

        throw new InvalidArgumentException('Cannot add'
            . ' matrix to the given input.');
    }

    /**
     * A universal function to subtract this matrix from another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->subtractMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->subtractColumnVector($b);

                    case $b instanceof Vector:
                        return $this->subtractVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->subtractScalar($b);
        }

        throw new InvalidArgumentException('Cannot subtract'
            . ' the given input from matrix.');
    }

    /**
     * A universal function to raise this matrix to the power of another
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
                    case $b instanceof Matrix:
                        return $this->powMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->powColumnVector($b);

                    case $b instanceof Vector:
                        return $this->powVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->powScalar($b);
        }

        throw new InvalidArgumentException('Cannot raise'
            . ' matrix to the given power.');
    }

    /**
     * A universal function to compute the integer modulus of this matrix
     * and another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->modMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->modColumnVector($b);

                    case $b instanceof Vector:
                        return $this->modVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->modScalar($b);
        }

        throw new InvalidArgumentException('Cannot mod'
            . ' matrix with the given input.');
    }

    /**
     * A universal function to compute the equality comparison of
     * this matrix and another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->equalMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->equalColumnVector($b);

                    case $b instanceof Vector:
                        return $this->equalVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->equalScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' matrix to the given input.');
    }

    /**
     * A universal function to compute the not equal comparison of
     * this matrix and another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->notEqualMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->notEqualColumnVector($b);

                    case $b instanceof Vector:
                        return $this->notEqualVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->notEqualScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' matrix to the given input.');
    }

    /**
     * A universal function to compute the greater than comparison of
     * this matrix and another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->greaterMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->greaterColumnVector($b);

                    case $b instanceof Vector:
                        return $this->greaterVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->greaterScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' matrix to the given input.');
    }

    /**
     * A universal function to compute the greater than or equal to
     * comparison of this matrix and another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->greaterEqualMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->greaterEqualColumnVector($b);

                    case $b instanceof Vector:
                        return $this->greaterEqualVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->greaterEqualScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' matrix to the given input.');
    }

    /**
     * A universal function to compute the less than comparison of
     * this matrix and another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->lessMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->lessColumnVector($b);

                    case $b instanceof Vector:
                        return $this->lessVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->lessScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' matrix to the given input.');
    }

    /**
     * A universal function to compute the less than or equal to
     * comparison of this matrix and another tensor element-wise.
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
                    case $b instanceof Matrix:
                        return $this->lessEqualMatrix($b);

                    case $b instanceof ColumnVector:
                        return $this->lessEqualColumnVector($b);

                    case $b instanceof Vector:
                        return $this->lessEqualVector($b);
                }

                break;

            case 'double':
            case 'integer':
                return $this->lessEqualScalar($b);
        }

        throw new InvalidArgumentException('Cannot compare'
            . ' matrix to the given input.');
    }

    /**
     * Return the element-wise reciprocal of the matrix.
     *
     * @return self
     */
    public function reciprocal() : self
    {
        return self::ones(...$this->shape())
            ->divideMatrix($this);
    }

    /**
     * Return the absolute value of each element in the matrix.
     *
     * @return self
     */
    public function abs() : self
    {
        return $this->map('abs');
    }

    /**
     * Return the square of the matrix element-wise.
     *
     * @return self
     */
    public function square() : self
    {
        return $this->multiplyMatrix($this);
    }

    /**
     * Return the square root of the matrix.
     *
     * @return self
     */
    public function sqrt() : self
    {
        return $this->map('sqrt');
    }

    /**
     * Return the exponential of the matrix.
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
     * Return the logarithm of the matrix in specified base.
     *
     * @param float $base
     * @return self
     */
    public function log(float $base = M_E) : self
    {
        $b = [];

        foreach ($this->a as $rowA) {
            $rowB = [];

            foreach ($rowA as $valueA) {
                $rowB[] = log($valueA, $base);
            }

            $b[] = $rowB;
        }

        return self::quick($b);
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
     * Return the sine of the matrix.
     *
     * @return self
     */
    public function sin() : self
    {
        return $this->map('sin');
    }

    /**
     * Compute the arc sine of the matrix.
     *
     * @return self
     */
    public function asin() : self
    {
        return $this->map('asin');
    }

    /**
     * Return the cosine of the matrix.
     *
     * @return self
     */
    public function cos() : self
    {
        return $this->map('cos');
    }

    /**
     * Compute the arc cosine of the matrix.
     *
     * @return self
     */
    public function acos() : self
    {
        return $this->map('acos');
    }

    /**
     * Return the tangent of the matrix.
     *
     * @return self
     */
    public function tan() : self
    {
        return $this->map('tan');
    }

    /**
     * Compute the arc tangent of the matrix.
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
     * Sum the rows of the matrix and return a vector.
     *
     * @return ColumnVector
     */
    public function sum() : ColumnVector
    {
        return ColumnVector::quick(array_map('array_sum', $this->a));
    }

    /**
     * Calculate the row product of the matrix.
     *
     * @return ColumnVector
     */
    public function product() : ColumnVector
    {
        return ColumnVector::quick(array_map('array_product', $this->a));
    }

    /**
     * Return the minimum of each row in the matrix.
     *
     * @return ColumnVector
     */
    public function min() : ColumnVector
    {
        return ColumnVector::quick(array_map('min', $this->a));
    }

    /**
     * Return the maximum of each row in the matrix.
     *
     * @return ColumnVector
     */
    public function max() : ColumnVector
    {
        return ColumnVector::quick(array_map('max', $this->a));
    }

    /**
     * Compute the means of each row and return them in a vector.
     *
     * @throws InvalidArgumentException
     * @return ColumnVector
     */
    public function mean() : ColumnVector
    {
        return $this->sum()->divideScalar($this->n);
    }

    /**
     * Compute the row variance of the matrix.
     *
     * @param ColumnVector|null $mean
     * @throws InvalidArgumentException
     * @throws DimensionalityMismatch
     * @return ColumnVector
     */
    public function variance($mean = null) : ColumnVector
    {
        if (isset($mean)) {
            if (!$mean instanceof ColumnVector) {
                throw new InvalidArgumentException('Mean must be a Column Vector.');
            }

            if ($mean->m() !== $this->m) {
                throw new DimensionalityMismatch('Mean vector must'
                    . " have {$this->m} rows, {$mean->m()} given.");
            }
        } else {
            $mean = $this->mean();
        }

        return $this->subtractColumnVector($mean)
            ->square()
            ->sum()
            ->divide($this->m);
    }

    /**
     * Return the median vector of this matrix.
     *
     * @return ColumnVector
     */
    public function median() : ColumnVector
    {
        $mid = intdiv($this->n, 2);

        $odd = $this->n % 2 === 1;

        $b = [];

        foreach ($this->a as $rowA) {
            sort($rowA);

            if ($odd) {
                $median = $rowA[$mid];
            } else {
                $median = ($rowA[$mid - 1] + $rowA[$mid]) / 2.0;
            }

            $b[] = $median;
        }

        return ColumnVector::quick($b);
    }

    /**
     * Compute the q'th quantile of the matrix.
     *
     * @param float $q
     * @throws InvalidArgumentException
     * @return ColumnVector
     */
    public function quantile(float $q) : ColumnVector
    {
        if ($q < 0.0 or $q > 1.0) {
            throw new InvalidArgumentException('Q must be between'
                . " 0 and 1, $q given.");
        }

        $x = $q * ($this->n - 1) + 1;

        $xHat = (int) $x;

        $remainder = $x - $xHat;

        $b = [];

        foreach ($this->a as $rowA) {
            sort($rowA);

            $t = $rowA[$xHat - 1];

            $b[] = $t + $remainder * ($rowA[$xHat] - $t);
        }

        return ColumnVector::quick($b);
    }

    /**
     * Compute the covariance matrix.
     *
     * @param ColumnVector|null $mean
     * @throws DimensionalityMismatch
     * @return self
     */
    public function covariance(?ColumnVector $mean = null) : self
    {
        if (isset($mean)) {
            if ($mean->m() !== $this->m) {
                throw new DimensionalityMismatch('Mean vector must'
                    . " have {$this->m} rows, {$mean->m()} given.");
            }
        } else {
            $mean = $this->mean();
        }

        $b = $this->subtractColumnVector($mean);

        return $b->matmul($b->transpose())
            ->divideScalar($this->m);
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

        foreach ($this->a as $rowA) {
            $rowB = [];

            foreach ($rowA as $valueA) {
                $rowB[] = round($valueA, $precision);
            }

            $b[] = $rowB;
        }

        return self::quick($b);
    }

    /**
     * Round the elements in the matrix down to the nearest integer.
     *
     * @return self
     */
    public function floor() : self
    {
        return $this->map('floor');
    }

    /**
     * Round the elements in the matrix up to the nearest integer.
     *
     * @return self
     */
    public function ceil() : self
    {
        return $this->map('ceil');
    }

    /**
     * Clip the elements in the matrix to be between given minimum and maximum
     * and return a new matrix.
     *
     * @param float $min
     * @param float $max
     * @throws InvalidArgumentException
     * @return self
     */
    public function clip(float $min, float $max) : self
    {
        if ($min > $max) {
            throw new InvalidArgumentException('Minimum cannot be greater than maximum.');
        }

        $b = [];

        foreach ($this->a as $rowA) {
            $rowB = [];

            foreach ($rowA as $valueA) {
                if ($valueA > $max) {
                    $rowB[] = $max;

                    continue;
                }

                if ($valueA < $min) {
                    $rowB[] = $min;

                    continue;
                }

                $rowB[] = $valueA;
            }

            $b[] = $rowB;
        }

        return self::quick($b);
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

        foreach ($this->a as $rowA) {
            $rowB = [];

            foreach ($rowA as $valueA) {
                if ($valueA < $min) {
                    $rowB[] = $min;

                    continue;
                }

                $rowB[] = $valueA;
            }

            $b[] = $rowB;
        }

        return self::quick($b);
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

        foreach ($this->a as $rowA) {
            $rowB = [];

            foreach ($rowA as $valueA) {
                if ($valueA > $max) {
                    $rowB[] = $max;

                    continue;
                }

                $rowB[] = $valueA;
            }

            $b[] = $rowB;
        }

        return self::quick($b);
    }

    /**
     * Return the element-wise sign indication.
     *
     * @return self
     */
    public function sign() : self
    {
        $b = [];

        foreach ($this->a as $rowA) {
            $rowB = [];

            foreach ($rowA as $valueA) {
                if ($valueA > 0) {
                    $rowB[] = 1.0;
                } elseif ($valueA < 0) {
                    $rowB[] = -1.0;
                } else {
                    $rowB[] = 0.0;
                }
            }

            $b[] = $rowB;
        }

        return self::quick($b);
    }

    /**
     * Negate the matrix i.e take the negative of each value element-wise.
     *
     * @return self
     */
    public function negate() : self
    {
        $b = [];

        foreach ($this->a as $rowA) {
            $rowB = [];

            foreach ($rowA as $valueA) {
                $rowB[] = -$valueA;
            }

            $b[] = $rowB;
        }

        return self::quick($b);
    }

    /**
     * Attach matrix b above this matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function augmentAbove(Matrix $b) : self
    {
        if ($this->m > 0 and $b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A requires'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        return self::quick(array_merge($b->asArray(), $this->a));
    }

    /**
     * Attach matrix b below this matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function augmentBelow(Matrix $b) : self
    {
        if ($this->m > 0 and $b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A requires'
                . " {$this->n} columns but Matrix B has {$b->n()}.");
        }

        return self::quick(array_merge($this->a, $b->asArray()));
    }

    /**
     * Attach matrix b to the left of this matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function augmentLeft(Matrix $b) : self
    {
        if ($this->m > 0 and $b->m() !== $this->m()) {
            throw new DimensionalityMismatch('Matrix A requires'
                . " {$this->m} rows but Matrix B has {$b->m()}.");
        }

        return self::quick(array_map('array_merge', $b->asArray(), $this->a));
    }

    /**
     * Attach matrix b to the left of this matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function augmentRight(Matrix $b) : self
    {
        if ($this->m > 0 and $b->m() !== $this->m()) {
            throw new DimensionalityMismatch('Matrix A requires'
                . " {$this->m} rows but Matrix B has {$b->m()}.");
        }

        return self::quick(array_map('array_merge', $this->a, $b->asArray()));
    }

    /**
     * Repeat the matrix m times along the vertical axes and n times along the horizontal axes.
     *
     * @param int $m
     * @param int $n
     * @return self
     */
    public function repeat(int $m, int $n) : self
    {
        $b = [];

        if ($n > 0) {
            foreach ($this->a as $i => $rowA) {
                $temp = [];

                for ($j = 0; $j < $n; ++$j) {
                    $temp[] = $rowA;
                }

                $b[] = array_merge($rowA, ...$temp);
            }
        }

        $temp = [];

        for ($i = 0; $i < $m; ++$i) {
            $temp[] = $b;
        }

        $b = array_merge($b, ...$temp);

        return self::quick($b);
    }

    /**
     * Return the element-wise product between this matrix and another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function multiplyMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA * $rowB[$j];
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the division of two elements, element-wise.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function divideMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA / $rowB[$j];
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Add this matrix together with another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function addMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA + $rowB[$j];
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Subtract a matrix from this matrix element-wise.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function subtractMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA - $rowB[$j];
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Raise this matrix to the power of the element-wise entry in another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function powMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA ** $rowB[$j];
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Calculate the modulus i.e remainder of division between this matrix and another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function modMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA % $rowB[$j];
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise equality comparison of this matrix and another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function equalMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA == $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise not equal comparison of this matrix and another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function notEqualMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA != $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise greater than comparison of this matrix and another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function greaterMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA > $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise greater than or equal to comparison of this matrix and another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function greaterEqualMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA >= $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise less than comparison of this matrix and another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function lessMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA < $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise less than or equal to comparison of this matrix and another matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function lessEqualMatrix(Matrix $b) : self
    {
        if ($b->shape() !== $this->shape()) {
            throw new DimensionalityMismatch("{$this->shapeString()} matrix"
                . " expected but {$b->shapeString()} given.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $j => $valueA) {
                $rowC[] = $valueA <= $rowB[$j] ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Multiply this matrix by a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function multiplyVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] * $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Divide this matrix by a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function divideVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] / $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Add this matrix by a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function addVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] + $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Subtract a vector from this matrix.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function subtractVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] - $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Raise this matrix to the power of a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function powVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] ** $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Calculate the modulus of this matrix with a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function modVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] % $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise equality comparison of this matrix and a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function equalVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] == $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise not equal comparison of this matrix and a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function notEqualVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] != $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise greater than comparison of this matrix and a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function greaterVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] > $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise greater than or equal to comparison of this matrix and a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function greaterEqualVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] >= $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise less than comparison of this matrix and a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function lessVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] < $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise less than or equal to comparison of this matrix and a vector.
     *
     * @param Vector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function lessEqualVector(Vector $b) : self
    {
        if ($b->n() !== $this->n) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->n} columns but Vector B has {$b->n()}.");
        }

        $b = $b->asArray();

        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($b as $j => $valueB) {
                $rowC[] = $rowA[$j] <= $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Multiply this matrix with a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function multiplyColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA * $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Divide this matrix with a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function divideColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA / $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Add this matrix to a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function addColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA + $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Subtract a column vector from this matrix.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function subtractColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA - $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Raise this matrix to the power of a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function powColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA ** $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Mod this matrix with a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function modColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA % $valueB;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise equality comparison of this matrix and a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function equalColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA == $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise not equal comparison of this matrix and a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function notEqualColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA != $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise greater than comparison of this matrix and a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function greaterColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA > $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise greater than or equal to comparison of this matrix and a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function greaterEqualColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA >= $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise less than comparison of this matrix and a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function lessColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA < $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise less than or equal to comparison of this matrix and a column vector.
     *
     * @param ColumnVector $b
     * @throws DimensionalityMismatch
     * @return self
     */
    public function lessEqualColumnVector(ColumnVector $b) : self
    {
        if ($b->m() !== $this->m) {
            throw new DimensionalityMismatch('Matrix A expects'
                . " {$this->m} rows but Vector B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $valueB) {
            $rowA = $this->a[$i];

            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA <= $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Multiply this matrix by a scalar.
     *
     * @param float $b
     * @return self
     */
    public function multiplyScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA * $b;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Divide this matrix by a scalar.
     *
     * @param float $b
     * @return self
     */
    public function divideScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA / $b;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Add this matrix by a scalar.
     *
     * @param float $b
     * @return self
     */
    public function addScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA + $b;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Subtract a scalar from this matrix.
     *
     * @param float $b
     * @return self
     */
    public function subtractScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA - $b;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Raise the matrix to a given scalar power.
     *
     * @param float $b
     * @return self
     */
    public function powScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA ** $b;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Calculate the modulus of this matrix with a scalar.
     *
     * @param float $b
     * @return self
     */
    public function modScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA % $b;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise equality comparison of this matrix and a scalar.
     *
     * @param float $b
     * @return self
     */
    public function equalScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA == $b ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise not equal comparison of this matrix and a scalar.
     *
     * @param float $b
     * @return self
     */
    public function notEqualScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA != $b ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise greater than comparison of this matrix and a scalar.
     *
     * @param float $b
     * @return self
     */
    public function greaterScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA > $b ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise greater than or equal to comparison of this matrix and a scalar.
     *
     * @param float $b
     * @return self
     */
    public function greaterEqualScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA >= $b ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise less than comparison of this matrix and a scalar.
     *
     * @param float $b
     * @return self
     */
    public function lessScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA < $b ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * Return the element-wise less than or equal to comparison of this matrix and a scalar.
     *
     * @param float $b
     * @return self
     */
    public function lessEqualScalar(float $b) : self
    {
        $c = [];

        foreach ($this->a as $rowA) {
            $rowC = [];

            foreach ($rowA as $valueA) {
                $rowC[] = $valueA <= $b ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return self::quick($c);
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return $this->size();
    }

    /**
     * @param mixed $index
     * @param mixed $values
     * @throws RuntimeException
     */
    public function offsetSet($index, $values) : void
    {
        throw new RuntimeException('Matrix cannot be mutated directly.');
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
        throw new RuntimeException('Matrix cannot be mutated directly.');
    }

    /**
     * Return a row from the matrix at the given index.
     *
     * @param mixed $index
     * @throws InvalidArgumentException
     * @return Vector
     */
    public function offsetGet($index) : Vector
    {
        if (isset($this->a[$index])) {
            return Vector::quick($this->a[$index]);
        }

        throw new InvalidArgumentException("Element not found at offset $index.");
    }

    /**
     * Get an iterator for the rows in the matrix.
     *
     * @return \Generator<int,\Tensor\Vector>
     */
    #[\ReturnTypeWillChange]
    public function getIterator() : Traversable
    {
        foreach ($this->a as $row) {
            yield Vector::quick($row);
        }
    }
}
