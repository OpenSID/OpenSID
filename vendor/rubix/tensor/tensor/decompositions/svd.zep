namespace Tensor\Decompositions;

use Tensor\Matrix;
use Tensor\Exceptions\RuntimeException;

/**
 * SVD
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class Svd
{
    /**
     * The U matrix.
     *
     * @var \Tensor\Matrix
     */
     protected u;

    /**
     * The singular values of the matrix A.
     *
     * @var (int|float)[]
     */
    protected singularValues;

    /**
     * The transposed V matrix.
     *
     * @var \Tensor\Matrix
     */
    protected vT;

    /**
     * Factory method to decompose a matrix.
     *
     * @param \Tensor\Matrix a
     * @throws \Tensor\Exceptions\RuntimeException
     * @return self
     */
    public static function decompose(const <Matrix> a) -> <Svd>
    {
        var result = tensor_svd(a->asArray());

        if is_null(result) {
            throw new RuntimeException("Failed to decompose matrix.");
        }

        array usvT = [];

        let usvT = (array) result;

        var u = Matrix::quick(usvT[0]);
        var singularValues = usvT[1];
        var vT = Matrix::quick(usvT[2]);

        return new self(u, singularValues, vT);
    }

    /**
     * @param \Tensor\Matrix u
     * @param list<int|float> singularValues
     * @param \Tensor\Matrix vT
     */
    public function __construct(const <Matrix> u, const array singularValues, const <Matrix> vT)
    {
        let this->u = u;
        let this->singularValues = singularValues;
        let this->vT = vT;
    }
 
    /**
     * Return the U matrix.
     *
     * @return \Tensor\Matrix
     */
    public function u() -> <Matrix>
    {
        return this->u;
    }

    /**
     * Return the singular values of matrix A.
     *
     * @return list<float>
     */
    public function singularValues() -> array
    {
        return this->singularValues;
    }
 
    /**
     * Return the singular value matrix.
     *
     * @return \Tensor\Matrix
     */
    public function s() -> <Matrix>
    {
        return Matrix::diagonal(this->singularValues);
    }

    /**
     * Return the V matrix.
     *
     * @return \Tensor\Matrix
     */
    public function v() -> <Matrix>
    {
        return this->vT->transpose();
    }
 
    /**
     * Return the V transposed matrix.
     *
     * @return \Tensor\Matrix
     */
    public function vT() -> <Matrix>
    {
       return this->vT;
    }
}
