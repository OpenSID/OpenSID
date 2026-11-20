namespace Tensor\Reductions;

use Tensor\Matrix;
use Tensor\Exceptions\InvalidArgumentException;
use Tensor\Exceptions\RuntimeException;

/**
 * REF
 *
 * The row echelon form (REF) of a matrix.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class Ref
{
    /**
     * The reduced matrix in row echelon form.
     *
     * @var \Tensor\Matrix
     */
    protected a;
    
    /**
     * The number of swaps made to compute the row echelon form of the matrix.
     *
     * @var int
     */
    protected swaps;

    /**
     * Factory method to decompose a matrix.
     *
     * @param \Tensor\Matrix a
     * @throws \Tensor\Exceptions\RuntimeException
     * @return self
     */
    public static function reduce(const <Matrix> a) -> <Ref>
    {
        var result = tensor_ref(a->asArray());

        if is_null(result) {
            throw new RuntimeException("Failed to decompose matrix.");
        }

        array ref = [];

        let ref = (array) result;

        var b = Matrix::quick(ref[0]);
        var swaps = ref[1];

        return new self(b, swaps);
    }

    /**
     * @param \Tensor\Matrix a
     * @param int swaps
     * @throws \Tensor\Exceptions\InvalidArgumentException
     */
    public function __construct(const <Matrix> a, const int swaps)
    {
        if unlikely swaps < 0 {
            throw new InvalidArgumentException("The number of swaps must"
                . " be greater than or equal to 0, " . strval(swaps)
                . " given.");
        }

        let this->a = a;
        let this->swaps = swaps;
    }

    /**
     * Return the reduced matrix in row echelon form.
     *
     * @return \Tensor\Matrix
     */
    public function a() -> <Matrix>
    {
        return this->a;
    }

    /**
     * Return the number of swaps made to reduce the matrix to ref.
     *
     * @return int
     */
    public function swaps() -> int
    {
        return this->swaps;
    }
}
