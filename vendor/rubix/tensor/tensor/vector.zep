namespace Tensor;

use Tensor\Exceptions\InvalidArgumentException;
use Tensor\Exceptions\DimensionalityMismatch;
use Tensor\Exceptions\RuntimeException;
use ArrayIterator;

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
     * A 1-d sequential array holding the elements of the vector.
     *
     * @var list<float>
     */
    protected a;

    /**
     * The number of elements in the vector.
     *
     * @var int
     */
    protected n;

    /**
     * Factory method to build a new vector from an array.
     *
     * @param float[] a
     * @return self
     */
    public static function build(const array a = [])
    {
        return new self(a, true);
    }

    /**
     * Build a vector foregoing any validation for quicker instantiation.
     *
     * @param float[] a
     * @return self
     */
    public static function quick(const array a = [])
    {
        return new self(a, false);
    }

    /**
     * Build a vector of zeros with n elements.
     *
     * @param int n
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public static function zeros(const int n) -> <Vector>
    {
        return static::fill(0.0, n);
    }

    /**
     * Build a vector of ones with n elements.
     *
     * @param int n
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public static function ones(const int n) -> <Vector>
    {
        return static::fill(1.0, n);
    }

    /**
     * Fill a vector with a given value.
     *
     * @param float value
     * @param int n
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public static function fill(const float value, const int n) -> <Vector>
    {
        if unlikely !is_int(value) && !is_float(value) {
            throw new InvalidArgumentException("Value must be an"
                . " integer or floating point number, "
                . gettype(value) . " given.");
        }

        if unlikely n < 1 {
            throw new InvalidArgumentException("N must be"
                . " greater than 0, " . strval(n) . " given.");
        }

        return static::quick(array_fill(0, n, value));
    }

    /**
     * Return a random uniform vector with values between 0 and 1.
     *
     * @param int n
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public static function rand(const int n) -> <Vector>
    {
        if unlikely n < 1 {
            throw new InvalidArgumentException("N must be"
                . " greater than 0, " . strval(n) . " given.");
        }

        array a = [];

        int max = (int) getrandmax();

        while count(a) < n {
            let a[] = rand() / max;
        }

        return static::quick(a);
    }

    /**
     * Return a standard normally distributed (Gaussian) random vector with mean 0
     * and unit variance.
     *
     * @param int n
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public static function gaussian(const int n) -> <Vector>
    {
        if unlikely n < 1 {
            throw new InvalidArgumentException("N must be"
                . " greater than 0, " . strval(n) . " given.");
        }

        float r, phi;

        array a = [];

        int max = (int) getrandmax();

        while count(a) < n {
            let r = sqrt(-2.0 * log(rand() / max));

            let phi = rand() / max * self::TWO_PI;

            let a[] = r * sin(phi);
            let a[] = r * cos(phi);
        }

        if count(a) > n {
            array_pop(a);
        }

        return static::quick(a);
    }

    /**
     * Generate a vector with n elements from a Poisson distribution.
     *
     * @param int n
     * @param float lambda
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public static function poisson(const int n, const float lambda = 1.0) -> <Vector>
    {
        if unlikely n < 1 {
            throw new InvalidArgumentException("N must be"
                . " greater than 0, " . strval(n) . " given.");
        }

        float p, k;

        array a = [];

        float l = (float) exp(-lambda);

        int max = (int) getrandmax();

        while count(a) < n {
            let k = 0.0;
            let p = 1.0;

            while p > l {
                let k++;
                
                let p *= rand() / max;
            }

            let a[] = k - 1.0;
        }

        return static::quick(a);
    }

    /**
     * Return a random uniformly distributed vector with values between -1 and 1.
     *
     * @param int n
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public static function uniform(const int n) -> <Vector>
    {
        if unlikely n < 1 {
            throw new InvalidArgumentException("N must be"
                . " greater than 0, " . strval(n) . " given.");
        }
        
        array a = [];

        int max = (int) getrandmax();

        while count(a) < n {
            let a[] = rand(-max, max) / max;
        }

        return static::quick(a);
    }

    /**
     * Return evenly spaced values within a given interval.
     *
     * @param float start
     * @param float end
     * @param float interval
     * @return self
     */
    public static function range(const float start, const float end, const float interval = 1.0) -> <Vector>
    {
        return static::quick(range(start, end, interval));
    }

    /**
     * Return a vector of n evenly spaced numbers between minimum and maximum.
     *
     * @param float min
     * @param float max
     * @param int n
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public static function linspace(const float min, const float max, const int n) -> <Vector>
    {
        if unlikely min > max {
            throw new InvalidArgumentException("Minimum must be less than maximum.");
        }

        if unlikely n < 2 {
            throw new InvalidArgumentException("Number of elements"
                . " must be greater than 1, " . strval(n) . " given.");
        }

        int k = n - 1;

        float interval = abs(max - min) / k;

        array a = [min];

        while count(a) < k {
            let a[] = end(a) + interval;
        }

        let a[] = max;

        return self::quick(a);
    }

    /**
     * @param float[] a
     * @param bool validate
     */
    public function __construct(array a, const bool validate = true)
    {
        var valueA;
        
        if validate {
            let a = array_values(a);

            for valueA in a {
                if (!is_float(valueA)) {
                    let valueA = (float) valueA;
                }
            }
        }

        let this->a = a;
        let this->n = count(a);
    }

    /**
     * Return a tuple with the dimensionality of the tensor.
     *
     * @return int[]
     */
    public function shape() -> array
    {
        return [this->n];
    }

    /**
     * Return the shape of the tensor as a string.
     *
     * @return string
     */
    public function shapeString() -> string
    {
        return (string) this->n;
    }

    /**
     * Return the number of elements in the vector.
     *
     * @return int
     */
    public function size() -> int
    {
        return this->n;
    }

    /**
     * Return the number of rows in the vector.
     *
     * @return int
     */
    public function m() -> int
    {
        return 1;
    }

    /**
     * Return the number of columns in the vector.
     *
     * @return int
     */
    public function n() -> int
    {
        return this->n;
    }

    /**
     * Return the vector as an array.
     *
     * @return list<float>
     */
    public function asArray() -> array
    {
        return this->a;
    }

    /**
     * Return this vector as a row matrix.
     *
     * @return \Tensor\Matrix
     */
    public function asRowMatrix() -> <Matrix>
    {
        return Matrix::quick([this->a]);
    }

    /**
     * Return this vector as a column matrix.
     *
     * @return \Tensor\Matrix
     */
    public function asColumnMatrix() -> <Matrix>
    {
        var valueA;

        array b = [];

        for valueA in this->a {
            let b[] = [valueA];
        }

        return Matrix::quick(b);
    }

    /**
     * Return a matrix in the shape specified.
     *
     * @param int m
     * @param int n
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return \Tensor\Matrix
     */
    public function reshape(const int m, const int n) -> <Matrix>
    {
        if unlikely m < 0 || n < 0 {
            throw new InvalidArgumentException("The number of rows"
                . " and/or columns cannot be less than 0.");
        }

        int nHat = m * n;

        if unlikely nHat !== this->n {
            throw new InvalidArgumentException(strval(nHat) . " elements"
                . " are needed but vector only has " . this->n . ".");
        }

        int i = 0;

        array b = [];
        array rowB = [];

        while count(b) < m {
            let rowB = [];

            while count(rowB) < n {
                let rowB[] = this->a[i];

                let i++;
            }

            let b[] = rowB;
        }

        return Matrix::quick(b);
    }

    /**
     * Transpose the vector i.e. rotate it.
     *
     * @return mixed
     */
    public function transpose()
    {
        return ColumnVector::quick(this->a);
    }

    /**
     * Map a function over the elements in the vector and return a new vector.
     *
     * @internal
     *
     * @param callable callback
     * @return self
     */
    public function map(const var callback) -> <Vector>
    {
        return static::quick(array_map(callback, this->a));
    }

    /**
     * Reduce the vector down to a scalar.
     *
     * @internal
     *
     * @param callable callback
     * @param float initial
     * @return float
     */
    public function reduce(const var callback, float initial = 0.0) -> float
    {
        return array_reduce(this->a, callback, initial);
    }

    /**
     * Compute the dot product of this vector and another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return float
     */
    public function dot(const <Vector> b) -> float
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " elements but vector B has "
                . (string) b->size() . ".");
        }

        return tensor_dot(this->a, b->asArray());
    }

    /**
     * Return the 1D convolution of this vector and a kernel vector with given stride.
     *
     * @param \Tensor\Vector b
     * @param int stride
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public function convolve(const <Vector> b, const int stride = 1) -> <Vector>
    {
        if unlikely b->size() > this->n {
            throw new InvalidArgumentException("Vector B cannot be"
                . " larger than Vector A.");
        }

        if unlikely stride < 1 {
            throw new InvalidArgumentException("Stride cannot be"
                . " less than 1, " . strval(stride). " given.");
        }

        return static::quick(tensor_convolve_1d(this->a, b->asArray(), stride));
    }

    /**
     * Compute the vector-matrix product of this vector and matrix b.
     *
     * @param \Tensor\Matrix b
     * @return \Tensor\Matrix
     */
    public function matmul(const <Matrix> b) -> <Matrix>
    {
        return this->asRowMatrix()->matmul(b);
    }

    /**
     * Return the inner product of two vectors.
     *
     * @param \Tensor\Vector b
     * @return float
     */
    public function inner(const <Vector> b) -> float
    {
        return this->dot(b);
    }

    /**
     * Calculate the outer product of this and another vector.
     *
     * @param \Tensor\Vector b
     * @return \Tensor\Matrix
     */
    public function outer(const <Vector> b) -> <Matrix>
    {
        var j, valueA, valueB;
        
        array bHat = [];
        array c = [];
        array rowC = [];

        let bHat = (array) b->asArray();

        for valueA in this->a {
            let rowC = [];

            for j, valueB in bHat {
                let rowC[] = valueA * valueB;
            }

            let c[] = rowC;
        }

        return Matrix::quick(c);
    }

    /**
     * Calculate the L1 or Manhattan norm of the vector.
     *
     * @return float
     */
    public function l1Norm() -> float
    {
        return this->abs()->sum();
    }

    /**
     * Calculate the L2 or Euclidean norm of the vector.
     *
     * @return float
     */
    public function l2Norm() -> float
    {
        return sqrt(this->square()->sum());
    }

    /**
     * Calculate the p-norm of the vector.
     *
     * @param float p
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return float
     */
    public function pNorm(const float p = 3.0) -> float
    {
        if unlikely p <= 0.0 {
            throw new InvalidArgumentException("P must be greater"
                . " than 0, " . strval(p) . " given.");
        }

        return pow(this->abs()->powScalar(p)->sum(), 1.0 / p);
    }

    /**
     * Calculate the max norm of the vector.
     *
     * @return float
     */
    public function maxNorm() -> float
    {
        return (float) this->abs()->max();
    }

    /**
     * A universal function to multiply this vector with another tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function multiply(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->multiplyMatrix(b);
                    
                    case b instanceof Vector:
                        return this->multiplyVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->multiplyScalar(b);
        }

        throw new InvalidArgumentException("Cannot multiply"
            . " vector by the given input.");
    }

    /**
     * A universal function to divide this vector by another tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function divide(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->divideMatrix(b);
                    
                    case b instanceof Vector:
                        return this->divideVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->divideScalar(b);
        }

        throw new InvalidArgumentException("Cannot divide"
            . " vector by the given input.");
    }

    /**
     * A universal function to add this vector with another tensor
     * element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function add(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->addMatrix(b);
                    
                    case b instanceof Vector:
                        return this->addVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->addScalar(b);
        }

        throw new InvalidArgumentException("Cannot add"
            . " vector by the given input.");
    }

    /**
     * A universal function to subtract this vector from another tensor
     * element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function subtract(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->subtractMatrix(b);
                    
                    case b instanceof Vector:
                        return this->subtractVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->subtractScalar(b);
        }

        throw new InvalidArgumentException("Cannot subtract"
            . " vector from the given input.");
    }

    /**
     * A universal function to raise this vector to the power of another
     * tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function pow(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->powMatrix(b);
                    
                    case b instanceof Vector:
                        return this->powVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->powScalar(b);
        }

        throw new InvalidArgumentException("Cannot raise"
            . " vector to the power of the given input.");
    }

    /**
     * A universal function to compute the modulus of this vector and
     * another tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function mod(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->modMatrix(b);
                    
                    case b instanceof Vector:
                        return this->modVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->modScalar(b);
        }

        throw new InvalidArgumentException("Cannot mod"
            . " vector with the given input.");
    }

    /**
     * A universal function to compute the equality comparison of
     * this vector and another tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function equal(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->equalMatrix(b);
                    
                    case b instanceof Vector:
                        return this->equalVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->equalScalar(b);
        }

        throw new InvalidArgumentException("Cannot compare"
            . " vector to the given input.");
    }

    /**
     * A universal function to compute the not equal comparison of
     * this vector and another tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function notEqual(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->notEqualMatrix(b);
                    
                    case b instanceof Vector:
                        return this->notEqualVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->notEqualScalar(b);
        }

        throw new InvalidArgumentException("Cannot compare"
            . " vector to the given input.");
    }

    /**
     * A universal function to compute the greater than comparison of
     * this vector and another tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function greater(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->greaterMatrix(b);
                    
                    case b instanceof Vector:
                        return this->greaterVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->greaterScalar(b);
        }

        throw new InvalidArgumentException("Cannot compare"
            . " vector to the given input.");
    }

    /**
     * A universal function to compute the greater than or equal to
     * comparison of this vector and another tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function greaterEqual(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->greaterEqualMatrix(b);
                    
                    case b instanceof Vector:
                        return this->greaterEqualVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->greaterEqualScalar(b);
        }

        throw new InvalidArgumentException("Cannot compare"
            . " vector to the given input.");
    }

    /**
     * A universal function to compute the less than comparison of
     * this vector and another tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function less(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->lessMatrix(b);
                    
                    case b instanceof Vector:
                        return this->lessVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->lessScalar(b);
        }

        throw new InvalidArgumentException("Cannot compare"
            . " vector to the given input.");
    }

    /**
     * A universal function to compute the less than or equal to
     * comparison of this vector and another tensor element-wise.
     *
     * @param mixed b
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function lessEqual(const var b)
    {
        switch (gettype(b)) {
            case "object":
                switch true {
                    case b instanceof Matrix:
                        return this->lessEqualMatrix(b);
                    
                    case b instanceof Vector:
                        return this->lessEqualVector(b);
                }

                break;

            case "double":
            case "integer":
                return this->lessEqualScalar(b);
        }

        throw new InvalidArgumentException("Cannot compare"
            . " vector to the given input.");
    }

    /**
     * Return the reciprocal of the vector element-wise.
     *
     * @return self
     */
    public function reciprocal() -> <Vector>
    {
        return static::ones(this->n)
            ->divideVector(this);
    }

    /**
     * Return the absolute value of the vector.
     *
     * @return self
     */
    public function abs() -> <Vector>
    {
        return this->map("abs");
    }

    /**
     * Square the vector.
     *
     * @return self
     */
    public function square() -> <Vector>
    {
        return this->multiplyVector(this);
    }

    /**
     * Take the square root of the vector.
     *
     * @return self
     */
    public function sqrt() -> <Vector>
    {
        return this->map("sqrt");
    }

    /**
     * Exponentiate each element in the vector.
     *
     * @return self
     */
    public function exp() -> <Vector>
    {
        return this->map("exp");
    }

    /**
    * Return the exponential of the vector minus 1.
    *
    * @return self
    */
    public function expm1() -> <Vector>
    {
        return this->map("expm1");
    }

    /**
     * Take the log to the given base of each element in the vector.
     *
     * @param float base
     * @return self
     */
    public function log(const float base = self::M_E) -> <Vector>
    {
        if base === self::M_E {
            return this->map("log");
        }

        var valueA;

        array b = [];

        for valueA in this->a {
            let b[] = log(valueA, base);
        }

        return static::quick(b);
    }

    /**
    * Return the log of 1 plus the tensor i.e. a transform.
    *
    * @return self
    */
    public function log1p() -> <Vector>
    {
        return this->map("log1p");
    }

    /**
     * Return the sine of this vector.
     *
     * @return self
     */
    public function sin() -> <Vector>
    {
        return this->map("sin");
    }

    /**
     * Compute the arc sine of the vector.
     *
     * @return self
     */
    public function asin() -> <Vector>
    {
        return this->map("asin");
    }

    /**
     * Return the cosine of this vector.
     *
     * @return self
     */
    public function cos() -> <Vector>
    {
        return this->map("cos");
    }

    /**
     * Compute the arc cosine of the vector.
     *
     * @return self
     */
    public function acos() -> <Vector>
    {
        return this->map("acos");
    }

    /**
     * Return the tangent of this vector.
     *
     * @return self
     */
    public function tan() -> <Vector>
    {
        return this->map("tan");
    }

    /**
     * Compute the arc tangent of the vector.
     *
     * @return self
     */
    public function atan() -> <Vector>
    {
        return this->map("atan");
    }

    /**
     * Convert angles from radians to degrees.
     *
     * @return self
     */
    public function rad2deg() -> <Vector>
    {
        return this->map("rad2deg");
    }

    /**
     * Convert angles from degrees to radians.
     *
     * @return self
     */
    public function deg2rad() -> <Vector>
    {
        return this->map("deg2rad");
    }

    /**
     * The sum of the vector.
     *
     * @return float
     */
    public function sum() -> float
    {
        return (float) array_sum(this->a);
    }

    /**
     * Return the product of the vector.
     *
     * @return float
     */
    public function product() -> float
    {
        return (float) array_product(this->a);
    }

    /**
     * Return the minimum element in the vector.
     *
     * @return float
     */
    public function min() -> float
    {
        return (float) min(this->a);
    }

    /**
     * Return the maximum element in the vector.
     *
     * @return float
     */
    public function max() -> float
    {
        return (float) max(this->a);
    }

    /**
     * Return the mean of the vector.
     *
     * @return float
     */
    public function mean() -> float
    {
        return this->sum() / this->n;
    }

    /**
     * Return the median of the vector.
     *
     * @return float
     */
    public function median() -> float
    {
        var median;

        int mid = (int) intdiv(this->n, 2);

        var a = this->a;

        sort(a);

        if this->n % 2 === 1 {
            let median = a[mid];
        } else {
            let median = (a[mid - 1] + a[mid]) / 2.0;
        }

        return median;
    }

    /**
     * Return the q'th quantile of the vector.
     *
     * @param float q
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return float
     */
    public function quantile(const float q) -> float
    {
        if unlikely q < 0.0 || q > 1.0 {
            throw new InvalidArgumentException("Q must be"
                . " between 0 and 1, " . strval(q) . " given.");
        }

        var a = this->a;

        sort(a);

        float x = q * (this->n - 1) + 1;

        int xHat = (int) x;

        float remainder = x - xHat;

        float t = (float) a[xHat - 1];

        return t + remainder * (a[xHat] - t);
    }

    /**
     * Return the variance of the vector.
     *
     * @param mixed mean
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return float
     */
    public function variance(var mean = null) -> float
    {
        if !is_null(mean) {
            if unlikely !is_float(mean) {
                throw new InvalidArgumentException("Mean scalar must be"
                    . " a floating point number " . gettype(mean) . " given.");
            }
        } else {
            let mean = this->mean();
        }

        var ssd = this->subtractScalar(mean)
            ->square()
            ->sum();

        return ssd / this->n;
    }

    /**
     * Round the elements in the matrix to a given decimal place.
     *
     * @param int precision
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public function round(const int precision = 0) -> <Vector>
    {
        if precision === 0 {
            return this->map("round");
        }

        if unlikely precision < 0 {
            throw new InvalidArgumentException("Decimal precision cannot"
                . " be less than 0, " . strval(precision)  . " given.");
        }

        var valueA;

        array b = [];

        for valueA in this->a {
            let b[] = round(valueA, precision);
        }

        return static::quick(b);
    }

    /**
     * Round the elements in the vector down to the nearest integer.
     *
     * @return self
     */
    public function floor() -> <Vector>
    {
        return this->map("floor");
    }

    /**
     * Round the elements in the vector up to the nearest integer.
     *
     * @return self
     */
    public function ceil() -> <Vector>
    {
        return this->map("ceil");
    }

    /**
     * Clip the elements in the vector to be between given minimum and maximum
     * and return a new vector.
     *
     * @param float min
     * @param float max
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return self
     */
    public function clip(const float min, const float max) -> <Vector>
    {
        if unlikely min > max {
            throw new InvalidArgumentException("Minimum cannot be"
                . " greater than maximum.");
        }

        var valueA;

        array b = [];

        for valueA in this->a {
            if valueA > max {
                let b[] = max;

                continue;
            }

            if valueA < min {
                let b[] = min;

                continue;
            }

            let b[] = valueA;
        }

        return static::quick(b);
    }

    /**
     * Clip the tensor to be lower bounded by a given minimum.
     *
     * @param float min
     * @return self
     */
    public function clipLower(const float min) -> <Vector>
    {
        var valueA;

        array b = [];
        
        for valueA in this->a {
            if valueA < min {
                let b[] = min;

                continue;
            }

            let b[] = valueA;
        }

        return static::quick(b);
    }

    /**
     * Clip the tensor to be upper bounded by a given maximum.
     *
     * @param float max
     * @return self
     */
    public function clipUpper(const float max) -> <Vector>
    {
        var valueA;

        array b = [];

        for valueA in this->a {
            if valueA > max {
                let b[] = max;

                continue;
            }

            let b[] = valueA;
        }

        return static::quick(b);
    }

    /**
     * Return the element-wise sign indication.
     *
     * @return self
     */
    public function sign() -> <Vector>
    {
        var valueA;

        array b = [];

        for valueA in this->a {
            if valueA > 0 {
                let b[] = 1.0;
            } elseif valueA < 0 {
                let b[] = -1.0;
            } else {
                let b[] = 0.0;
            }
        }

        return static::quick(b);
    }

    /**
     * Negate the vector i.e take the negative of each value elementwise.
     *
     * @return self
     */
    public function negate() -> <Vector>
    {
        var valueA;
        
        array b = [];

        for valueA in this->a {
            let b[] = -valueA;
        }

        return static::quick(b);
    }

    /**
     * Multiply this vector with a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function multiplyMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }
 
        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_multiply(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Divide this vector with a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function divideMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_divide(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Add this vector to a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function addMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_add(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Subtract a matrix from this vector.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function subtractMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_subtract(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Raise this vector to the power of a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function powMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_pow(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Mod this vector with a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function modMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_mod(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Return the element-wise equality comparison of this vector and a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function equalMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_equal(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Return the element-wise not equal comparison of this vector and a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function notEqualMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new InvalidArgumentException("Vector A requires "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_not_equal(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Return the element-wise greater than comparison of this vector and a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function greaterMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_greater(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Return the element-wise greater than or equal to comparison of this vector and a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function greaterEqualMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_greater_equal(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Return the element-wise less than comparison of this vector and a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function lessMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_less(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Return the element-wise less than or equal to comparison of this vector and a matrix.
     *
     * @param \Tensor\Matrix b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return \Tensor\Matrix
     */
    public function lessEqualMatrix(const <Matrix> b) -> <Matrix>
    {
        if unlikely this->n !== b->n() {
            throw new DimensionalityMismatch("Vector A expects "
                . (string) this->n . " columns but Matrix B has "
                . (string) b->n() . ".");
        }

        var rowB;

        array c = [];
 
        for rowB in b->asArray() {
            let c[] = tensor_less_equal(this->a, rowB);
        }
 
        return Matrix::quick(c);
    }

    /**
     * Multiply this vector with another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function multiplyVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_multiply(this->a, b->asArray()));
    }

    /**
     * Divide this vector by another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function divideVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_divide(this->a, b->asArray()));
    }

    /**
     * Add this vector to another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function addVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_add(this->a, b->asArray()));
    }

    /**
     * Subtract a vector from this vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function subtractVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_subtract(this->a, b->asArray()));
    }

    /**
     * Raise this vector to a power of another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function powVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_pow(this->a, b->asArray()));
    }

    /**
     * Calculate the modulus of this vector with another vector elementwise.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function modVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_mod(this->a, b->asArray()));
    }

    /**
     * Return the element-wise equality comparison of this vector and a
     * another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function equalVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_equal(this->a, b->asArray()));
    }

    /**
     * Return the element-wise not equal comparison of this vector and a another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function notEqualVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_not_equal(this->a, b->asArray()));
    }

    /**
     * Return the element-wise greater than comparison of this vector and a another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function greaterVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_greater(this->a, b->asArray()));
    }

    /**
     * Return the element-wise greater than or equal to comparison of this vector and a another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function greaterEqualVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_greater_equal(this->a, b->asArray()));
    }

    /**
     * Return the element-wise less than comparison of this vector and a another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function lessVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_less(this->a, b->asArray()));
    }

    /**
     * Return the element-wise less than or equal to comparison of this vector and a another vector.
     *
     * @param \Tensor\Vector b
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public function lessEqualVector(const <Vector> b) -> <Vector>
    {
        if unlikely this->n !== b->size() {
            throw new DimensionalityMismatch("Vector A requires "
                . (string) this->n . " elements but Vector B has "
                . (string) b->size() . ".");
        }

        return static::quick(tensor_less_equal(this->a, b->asArray()));
    }

    /**
     * Multiply this vector by a scalar.
     *
     * @param float b
     * @return self
     */
     public function multiplyScalar(const float b) -> <Vector>
     {
        return static::quick(tensor_multiply_scalar(this->a, b));
    }

    /**
     * Divide this vector by a scalar.
     *
     * @param float b
     * @return self
     */
    public function divideScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_divide_scalar(this->a, b));
    }

    /**
     * Add a scalar to this vector.
     *
     * @param float b
     * @return self
     */
    public function addScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_add_scalar(this->a, b));
    }

    /**
     * Subtract a scalar from this vector.
     *
     * @param float b
     * @return self
     */
    public function subtractScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_subtract_scalar(this->a, b));
    }

    /**
     * Raise the vector to a the power of a scalar value.
     *
     * @param float b
     * @return self
     */
     public function powScalar(const float b) -> <Vector>
     {
        return static::quick(tensor_pow_scalar(this->a, b));
     }

    /**
     * Calculate the modulus of this vector with a scalar.
     *
     * @param float b
     * @return self
     */
    public function modScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_mod_scalar(this->a, b));
    }

    /**
     * Return the element-wise equality comparison of this vector and a scalar.
     *
     * @param float b
     * @return self
     */
    public function equalScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_equal_scalar(this->a, b));
    }

    /**
     * Return the element-wise not equal comparison of this vector and a scalar.
     *
     * @param float b
     * @return self
     */
    public function notEqualScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_not_equal_scalar(this->a, b));
    }

    /**
     * Return the element-wise greater than comparison of this vector and a scalar.
     *
     * @param float b
     * @return self
     */
    public function greaterScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_greater_scalar(this->a, b));
    }

    /**
     * Return the element-wise greater than or equal to comparison of
     * this vector and a scalar.
     *
     * @param float b
     * @return self
     */
    public function greaterEqualScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_greater_equal_scalar(this->a, b));
    }

    /**
     * Return the element-wise less than comparison of this vector and a scalar.
     *
     * @param float b
     * @return self
     */
    public function lessScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_less_scalar(this->a, b));
    }

    /**
     * Return the element-wise less than or equal to comparison of
     * this vector and a scalar.
     *
     * @param float b
     * @return self
     */
    public function lessEqualScalar(const float b) -> <Vector>
    {
        return static::quick(tensor_less_equal_scalar(this->a, b));
    }

    /**
     * Count method to implement countable interface.
     *
     * @return int
     */
    public function count() -> int
    {
        return this->n;
    }

    /**
     * @param mixed index
     * @param array values
     * @throws \Tensor\Exceptions\RuntimeException
     */
    public function offsetSet(const var index, const var values) -> void
    {
        throw new RuntimeException("Vector cannot be mutated directly.");
    }

    /**
     * Does a given column exist in the matrix.
     *
     * @param mixed index
     * @return bool
     */
    public function offsetExists(const var index) -> bool
    {
        return isset(this->a[index]);
    }

    /**
     * @param mixed index
     * @throws \Tensor\Exceptions\RuntimeException
     */
    public function offsetUnset(const var index) -> void
    {
        throw new RuntimeException("Vector cannot be mutated directly.");
    }

    /**
     * Return a row from the matrix at the given index.
     *
     * @param mixed index
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @return int|float
     */
    public function offsetGet(const var index) -> mixed
    {
        var value;

        if likely fetch value, this->a[index] {
            return value;
        }

        throw new InvalidArgumentException("Element not found at"
            . " offset " . (string) index . ".");
    }

    /**
     * Get an iterator for the rows in the matrix.
     *
     * @return \ArrayIterator
     */
    public function getIterator() -> <\Traversable>
    {
        return new ArrayIterator(this->a);
    }
}