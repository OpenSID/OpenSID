<?php

namespace Tensor;

use Tensor\Exceptions\DimensionalityMismatch;

/**
 * Column Vector
 *
 * A one dimensional (rank 1) tensor with integer and/or floating point elements.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
class ColumnVector extends Vector
{
    /**
     * Return the number of rows in the vector.
     *
     * @return int
     */
    public function m() : int
    {
        return $this->n;
    }

    /**
     * Return the number of columns in the vector.
     *
     * @return int
     */
    public function n() : int
    {
        return 1;
    }

    /**
     * Transpose the vector i.e. rotate it.
     *
     * @return mixed
     */
    public function transpose()
    {
        return Vector::quick($this->a);
    }

    /**
     * Compute the vector-matrix product of this vector and matrix a.
     *
     * @param Matrix $b
     * @return Matrix
     */
    public function matmul(Matrix $b) : Matrix
    {
        return $this->asColumnMatrix()->matmul($b);
    }

    /**
     * Multiply this column vector with a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function multiplyMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA * $valueB;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Divide this column vector with a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function divideMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA / $valueB;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Add this column vector to a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function addMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA + $valueB;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Subtract a matrix from this column vector.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function subtractMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA - $valueB;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Raise this column vector to the power of a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function powMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA ** $valueB;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Mod this column vector with a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function modMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA % $valueB;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise equality comparison of this column vector
     * and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function equalMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA == $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise not equal comparison of this column vector
     * and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function notEqualMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA != $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise greater than comparison of this column
     * vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function greaterMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA > $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise greater than or equal to comparison of
     * this column vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function greaterEqualMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA >= $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise less than comparison of this column
     * vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function lessMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA < $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }

    /**
     * Return the element-wise less than or equal to comparison of
     * this column vector and a matrix.
     *
     * @param Matrix $b
     * @throws DimensionalityMismatch
     * @return Matrix
     */
    public function lessEqualMatrix(Matrix $b) : Matrix
    {
        if ($this->n !== $b->m()) {
            throw new DimensionalityMismatch('Vector A requires'
                . " {$this->n} rows but Matrix B has {$b->m()}.");
        }

        $c = [];

        foreach ($b->asArray() as $i => $rowB) {
            $valueA = $this->a[$i];

            $rowC = [];

            foreach ($rowB as $valueB) {
                $rowC[] = $valueA <= $valueB ? 1 : 0;
            }

            $c[] = $rowC;
        }

        return Matrix::quick($c);
    }
}
