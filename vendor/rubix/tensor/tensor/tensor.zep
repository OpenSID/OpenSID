namespace Tensor;

interface Tensor extends ArrayLike, Arithmetic, Comparable, Algebraic, Trigonometric, Statistical, Special
{   
    const EPSILON = 0.00000001;
}
