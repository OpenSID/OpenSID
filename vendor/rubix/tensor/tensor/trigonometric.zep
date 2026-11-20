namespace Tensor;

interface Trigonometric
{
    const M_PI = 3.14159265358979323846;

    const TWO_PI = 6.28318530718;
    
    /**
     * Return the sine of the tensor.
     *
     * @return mixed
     */
    public function sin();

    /**
     * Compute the arc sine of the tensor.
     *
     * @return mixed
     */
    public function asin();

    /**
     * Return the cosine of the tensor.
     *
     * @return mixed
     */
    public function cos();

    /**
     * Compute the arc cosine of the tensor.
     *
     * @return mixed
     */
    public function acos();

    /**
     * Return the tangent of the tensor.
     *
     * @return mixed
     */
    public function tan();

    /**
     * Compute the arc tangent of the tensor.
     *
     * @return mixed
     */
    public function atan();

    /**
     * Convert angles from radians to degrees.
     *
     * @return mixed
     */
    public function rad2deg();

    /**
     * Convert angles from degrees to radians.
     *
     * @return mixed
     */
    public function deg2rad();
}
