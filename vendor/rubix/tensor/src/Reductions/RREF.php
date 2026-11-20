<?php

namespace Tensor\Reductions;

use Tensor\Matrix;

/**
 * RREF
 *
 * The reduced row echelon form (RREF) of a matrix.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class RREF
{
    /**
     * The reduced matrix in reduced row echelon form.
     *
     * @var Matrix
     */
    protected Matrix $a;

    /**
     * Factory method to reduce a matrix.
     *
     * @param Matrix $a
     * @return self
     */
    public static function reduce(Matrix $a) : self
    {
        $ref = $a->ref();

        $b = $ref->a();

        [$m, $n] = $b->shape();

        $b = $b->asArray();

        $row = $col = 0;

        while ($row < $m and $col < $n) {
            $t = $b[$row];

            if (abs($t[$col]) == 0) {
                ++$col;

                continue;
            }

            $divisor = $t[$col];

            if ($divisor != 1) {
                for ($i = 0; $i < $n; ++$i) {
                    $t[$i] /= $divisor;
                }
            }

            for ($i = $row - 1; $i >= 0; --$i) {
                $rowB = $b[$i];

                $scale = $rowB[$col];

                if ($scale != 0) {
                    for ($j = 0; $j < $n; ++$j) {
                        $rowB[$j] -= $scale * $t[$j];
                    }
                }

                $b[$i] = $rowB;
            }

            $b[$row] = $t;

            ++$row;
            ++$col;
        }

        return new self(Matrix::quick($b));
    }

    /**
     * @param Matrix $a
     * @throws \Tensor\Exceptions\InvalidArgumentException
     */
    public function __construct(Matrix $a)
    {
        $this->a = $a;
    }

    /**
     * Return the reduced matrix in reduced row echelon form.
     *
     * @return Matrix
     */
    public function a() : Matrix
    {
        return $this->a;
    }
}
