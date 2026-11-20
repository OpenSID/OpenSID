namespace Tensor\Decompositions;

use Tensor\Matrix;
use Tensor\Exceptions\InvalidArgumentException;
use Tensor\Exceptions\RuntimeException;

/**
 * LU
 *
 * The LU decomposition is a factorization of a Matrix as the product of a
 * lower and upper triangular matrix as well as a permutation matrix.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class Lu
{
    /**
     * The lower triangular matrix.
     *
     * @var \Tensor\Matrix
     */
    protected l;

    /**
     * The upper triangular matrix.
     *
     * @var \Tensor\Matrix
     */
    protected u;

    /**
     * The permutation matrix.
     *
     * @var \Tensor\Matrix
     */
    protected p;

    /**
     * Factory method to decompose a matrix.
     *
     * @param \Tensor\Matrix a
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @throws \Tensor\Exceptions\RuntimeException
     * @return self
     */
    public static function decompose(const <Matrix> a) -> <Lu>
    {
        if unlikely !a->isSquare() {
            throw new InvalidArgumentException("Matrix must be"
                . " square, " . $a->shapeString() . " given.");
        }

        var result = tensor_lu(a->asArray());

        if is_null(result) {
            throw new RuntimeException("Failed to decompose matrix.");
        }

        array lup = [];

        let lup = (array) result;

        var l = Matrix::quick(lup[0]);
        var u = Matrix::quick(lup[1]);
        var p = Matrix::quick(lup[2]);

        return new self(l, u, p);
    }

    /**
     * @param \Tensor\Matrix l
     * @param \Tensor\Matrix u
     * @param \Tensor\Matrix p
     */
    public function __construct(const <Matrix> l, const <Matrix> u, const <Matrix> p)
    {
        let this->l = l;
        let this->u = u;
        let this->p = p;
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
     * Return the upper triangular matrix.
     *
     * @return \Tensor\Matrix
     */
    public function u() -> <Matrix>
    {
        return this->u;
    }

    /**
     * Return the permutation matrix.
     *
     * @return \Tensor\Matrix
     */
    public function p() -> <Matrix>
    {
        return this->p;
    }
}
