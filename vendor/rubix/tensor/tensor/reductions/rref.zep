namespace Tensor\Reductions;

use Tensor\Matrix;
use InvalidArgumentException;
use RuntimeException;

/**
 * RREF
 *
 * The reduced row echelon form (RREF) of a matrix.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class Rref
{
    /**
     * The reduced matrix in reduced row echelon form.
     *
     * @var \Tensor\Matrix
     */
    protected a;

    /**
     * Factory method to decompose a matrix.
     *
     * @param \Tensor\Matrix a
     * @return self
     */
    public static function reduce(const <Matrix> a) -> <Rref>
    {
        int i, j;
        float scale, divisor;
        
        array b = [];
        array rowB = [];
        array t = [];

        int m = (int) a->m();
        int n = (int) a->n();

        int row = 0;
        int col = 0;

        let b = (array) a->ref()->a()->asArray();

        while row < m && col < n {
            let t = (array) b[row];

            if abs(t[col]) == 0 {
                let col++;

                continue;
            }

            let divisor = (float) t[col];

            if divisor !== 1.0 {
                for i in range(0, n - 1) {
                    let t[i] = t[i] / divisor;
                }
            }

            for i in reverse range(0, row - 1) {
                let rowB = (array) b[i];

                let scale = (float) rowB[col];

                if scale !== 0.0 {
                    for j in range(0, n - 1) {
                        let rowB[j] = rowB[j] - scale * t[j];
                    }
                }

                let b[i] = rowB;
            }

            let b[row] = t;

            let row++;
            let col++;
        }

        return new self(Matrix::quick(b));
    }

    /**
     * @param \Tensor\Matrix a
     * @throws \InvalidArgumentException
     */
    public function __construct(const <Matrix> a)
    {
        let this->a = a;
    }

    /**
     * Return the reduced matrix in reduced row echelon form.
     *
     * @return \Tensor\Matrix
     */
    public function a() -> <Matrix>
    {
        return this->a;
    }
}
