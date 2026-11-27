<?php

namespace Tensor\Reductions;

use Tensor\Matrix;
use Tensor\Exceptions\InvalidArgumentException;
use Tensor\Exceptions\RuntimeException;

/**
 * REF
 *
 * The row echelon form (REF) of a matrix.
 *
 * References:
 * [1] M. Rogoyski. (2019). Math PHP: Powerful modern math library for PHP.
 * http://github.com/markrogoyski/math-php.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class REF
{
    /**
     * The reduced matrix in row echelon form.
     *
     * @var Matrix
     */
    protected Matrix $a;

    /**
     * The number of swaps made to compute the row echelon form of the matrix.
     *
     * @var int
     */
    protected int $swaps;

    /**
     * Factory method to decompose a matrix.
     *
     * @param Matrix $a
     * @return self
     */
    public static function reduce(Matrix $a)
    {
        try {
            return static::gaussianElimination($a);
        } catch (RuntimeException $e) {
            return static::rowReductionMethod($a);
        }
    }

    /**
     * Calculate the row echelon form (REF) of the matrix using Gaussian elimination.
     *
     * @param Matrix $a
     * @throws RuntimeException
     * @return self
     */
    public static function gaussianElimination(Matrix $a) : self
    {
        [$m, $n] = $a->shape();

        $minDim = min($m, $n);

        $b = $a->asArray();

        $swaps = 0;

        for ($i = 0; $i < $minDim; ++$i) {
            $index = $i;

            for ($j = $i; $j < $m; ++$j) {
                if (abs($b[$j][$i]) > abs($b[$index][$i])) {
                    $index = $j;
                }
            }

            if ($b[$index][$i] == 0) {
                throw new RuntimeException('Cannot compute row echelon'
                    . ' form of a singular matrix.');
            }

            if ($i !== $index) {
                $temp = $b[$i];

                $b[$i] = $b[$index];
                $b[$index] = $temp;

                ++$swaps;
            }

            $diag = $b[$i][$i];

            for ($j = $i + 1; $j < $m; ++$j) {
                $scale = $diag != 0.0 ? $b[$j][$i] / $diag : 1.0;

                for ($k = $i + 1; $k < $n; ++$k) {
                    $b[$j][$k] -= $scale * $b[$i][$k];
                }

                $b[$j][$i] = 0.0;
            }
        }

        return new self(Matrix::quick($b), $swaps);
    }

    /**
     * Calculate the row echelon form (REF) of the matrix using the row
     * reduction method.
     *
     * @param Matrix $a
     * @return self
     */
    public static function rowReductionMethod(Matrix $a) : self
    {
        [$m, $n] = $a->shape();

        $b = $a->asArray();

        $row = $col = $swaps = 0;

        while ($row < $m and $col < $n) {
            $t = $b[$row];

            if ($t[$col] == 0) {
                for ($i = $row + 1; $i < $m; ++$i) {
                    if ($b[$i][$col] != 0) {
                        $temp = $b[$i];

                        $b[$i] = $t;
                        $t = $temp;

                        ++$swaps;

                        break;
                    }
                }
            }

            if ($t[$col] == 0) {
                ++$col;

                continue;
            }

            $divisor = $t[$col];

            if ($divisor != 1) {
                for ($i = 0; $i < $n; ++$i) {
                    $t[$i] /= $divisor;
                }
            }

            for ($i = $row + 1; $i < $m; ++$i) {
                $scale = $b[$i][$col];

                if ($scale != 0) {
                    for ($j = 0; $j < $n; ++$j) {
                        $b[$i][$j] -= $scale * $t[$j];
                    }
                }
            }

            $b[$row] = $t;

            ++$row;
            ++$col;
        }

        return new self(Matrix::quick($b), $swaps);
    }

    /**
     * @param Matrix $a
     * @param int $swaps
     * @throws InvalidArgumentException
     */
    public function __construct(Matrix $a, int $swaps)
    {
        if ($swaps < 0) {
            throw new InvalidArgumentException('The number of swaps must'
                . " be greater than or equal to 0, $swaps given.");
        }

        $this->a = $a;
        $this->swaps = $swaps;
    }

    /**
     * Return the reduced matrix in row echelon form.
     *
     * @return Matrix
     */
    public function a() : Matrix
    {
        return $this->a;
    }

    /**
     * Return the number of swaps made to reduce the matrix to ref.
     *
     * @return int
     */
    public function swaps() : int
    {
        return $this->swaps;
    }
}
