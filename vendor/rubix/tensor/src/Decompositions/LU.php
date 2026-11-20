<?php

namespace Tensor\Decompositions;

use Tensor\Matrix;
use Tensor\Exceptions\InvalidArgumentException;

use const Tensor\EPSILON;

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
class LU
{
    /**
     * The lower triangular matrix.
     *
     * @var Matrix
     */
    protected Matrix $l;

    /**
     * The upper triangular matrix.
     *
     * @var Matrix
     */
    protected Matrix $u;

    /**
     * The permutation matrix.
     *
     * @var Matrix
     */
    protected Matrix $p;

    /**
     * Factory method to decompose a matrix.
     *
     * @param Matrix $a
     * @throws \Tensor\Exceptions\DimensionalityMismatch
     * @return self
     */
    public static function decompose(Matrix $a) : self
    {
        if (!$a->isSquare()) {
            throw new InvalidArgumentException('Matrix must be'
                . " square, {$a->shapeString()} given.");
        }

        $n = $a->n();

        $aHat = $a->asArray();

        $l = Matrix::identity($n)->asArray();
        $u = Matrix::zeros($n, $n)->asArray();
        $p = Matrix::identity($n)->asArray();

        for ($i = 0; $i < $n; ++$i) {
            $max = $aHat[$i][$i];

            $row = $i;

            for ($j = $i; $j < $n; ++$j) {
                $valueA = $aHat[$j][$i];

                if ($valueA > $max) {
                    $max = $valueA;
                    $row = $j;
                }
            }

            if ($i !== $row) {
                $temp = $p[$i];

                $p[$i] = $p[$row];
                $p[$row] = $temp;
            }
        }

        $p = Matrix::quick($p);

        $pa = $p->matmul($a)->asArray();

        for ($i = 0; $i < $n; ++$i) {
            for ($j = 0; $j <= $i; ++$j) {
                $sigma = 0.;

                for ($k = 0; $k < $j; ++$k) {
                    $sigma += $u[$k][$i] * $l[$j][$k];
                }

                $u[$j][$i] = $pa[$j][$i] - $sigma;
            }

            for ($j = $i; $j < $n; ++$j) {
                $sigma = 0.;

                for ($k = 0; $k < $i; ++$k) {
                    $sigma += $u[$k][$i] * $l[$j][$k];
                }

                $l[$j][$i] = ($pa[$j][$i] - $sigma)
                    / ($u[$i][$i] ?: EPSILON);
            }
        }

        return new self(
            Matrix::quick($l),
            Matrix::quick($u),
            $p
        );
    }

    /**
     * @param Matrix $l
     * @param Matrix $u
     * @param Matrix $p
     */
    public function __construct(Matrix $l, Matrix $u, Matrix $p)
    {
        $this->l = $l;
        $this->u = $u;
        $this->p = $p;
    }

    /**
     * Return the lower triangular matrix.
     *
     * @return Matrix
     */
    public function l() : Matrix
    {
        return $this->l;
    }

    /**
     * Return the upper triangular matrix.
     *
     * @return Matrix
     */
    public function u() : Matrix
    {
        return $this->u;
    }

    /**
     * Return the permutation matrix.
     *
     * @return Matrix
     */
    public function p() : Matrix
    {
        return $this->p;
    }
}
