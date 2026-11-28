namespace Tensor\Decompositions;

use Tensor\Matrix;
use Tensor\Exceptions\InvalidArgumentException;
use Tensor\Exceptions\RuntimeException;

/**
 * Cholesky
 *
 * An efficient decomposition of a square positive definite matrix into a lower triangular matrix and its conjugate transpose.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class Cholesky
{
    /**
     * The lower triangular matrix.
     * 
     * @var \Tensor\Matrix
     */
    protected l;

    /**
     * Factory method to decompose a matrix.
     *
     * @param \Tensor\Matrix a
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @throws \Tensor\Exceptions\RuntimeException
     * @return self
     */
    public static function decompose(const <Matrix> a) -> <Cholesky>
    {
        if !a->isSquare() {
            throw new InvalidArgumentException("Matrix must be"
                . " square, " . $a->shapeString() . " given.");
        }

        var l = tensor_cholesky(a->asArray());

        if is_null(l) {
            throw new RuntimeException("Failed to decompose matrix.");
        }

        return new self(Matrix::quick(l));
    }

    /**
     * @param \Tensor\Matrix l
     */
    public function __construct(const <Matrix> l)
    {
        let this->l = l;
    }

    /**
     * Return the lower triangular matrix.
     *
     * @return \Tensor\Matrix
     */
    public function l() -> <Matrix>
    {
        return this->l;
    }

    /**
     * Return the conjugate transpose of the lower triangular matrix.
     *
     * @return \Tensor\Matrix
     */
    public function lT() -> <Matrix>
    {
        return this->l->transpose();
    }
}