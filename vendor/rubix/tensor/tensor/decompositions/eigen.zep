namespace Tensor\Decompositions;

use Tensor\Matrix;
use Tensor\Vector;
use Tensor\ColumnVector;
use Tensor\Exceptions\InvalidArgumentException;
use Tensor\Exceptions\RuntimeException;

/**
 * Eigen
 *
 * The Eigendecompositon or (Spectral decomposition) is a matrix factorization resulting in a
 * matrix of eigenvectors and a corresponding array of eigenvalues.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class Eigen
{
    /**
     * The computed eigenvalues.
     *
     * @var (int|float)[]
     */
    protected eigenvalues;

    /**
     * The eigenvectors of the eigendecomposition.
     *
     * @var \Tensor\Matrix
     */
    protected eigenvectors;

    /**
     * Factory method to decompose a matrix.
     *
     * @param \Tensor\Matrix a
     * @param bool symmetric
     * @throws \Tensor\Exceptions\InvalidArgumentException
     * @throws \Tensor\Exceptions\RuntimeException
     * @return self
     */
    public static function decompose(const <Matrix> a, const bool symmetric = false) -> <Eigen>
    {
        if unlikely !a->isSquare() {
            throw new InvalidArgumentException("Matrix must be"
                . " square, " . $a->shapeString() . " given.");
        }

        var result;

        if symmetric {
            let result = tensor_eig_symmetric(a->asArray());
        } else {
            let result = tensor_eig(a->asArray());
        }

        if is_null(result) {
            throw new RuntimeException("Failed to decompose matrix.");
        }

        array eig = [];

        let eig = (array) result;

        var eigenvalues = eig[0];
        var eigenvectors = Matrix::quick(eig[1])->transpose();

        return new self(eigenvalues, eigenvectors);
    }

    /**
     * @param list<int|float> eigenvalues
     * @param \Tensor\Matrix eigenvectors
     */
    public function __construct(const array eigenvalues, const <Matrix> eigenvectors)
    {
        let this->eigenvalues = eigenvalues;
        let this->eigenvectors = eigenvectors;
    }

    /**
     * Return the eigenvalues.
     *
     * @return list<int|float>
     */
    public function eigenvalues() -> array
    {
        return this->eigenvalues;
    }

    /**
     * Return the eigenvectors.
     *
     * @return \Tensor\Matrix
     */
    public function eigenvectors() -> <Matrix>
    {
        return this->eigenvectors;
    }
}
