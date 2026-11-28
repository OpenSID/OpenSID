namespace Tensor;

interface Statistical
{
    /**
     * Return the mean of the tensor.
     *
     * @return mixed
     */
    public function mean();

    /**
     * Compute the variance of the tensor.
     *
     * @param mixed mean
     * @return mixed
     */
    public function variance(mean = null);

    /**
     * Return the median of the tensor.
     *
     * @return mixed
     */
     public function median();

     /**
      * Return the q'th quantile of the tensor.
      *
      * @param float q
      * @return mixed
      */
     public function quantile(const float q);
}
