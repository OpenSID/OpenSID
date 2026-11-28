
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_exceptions.h>

#include "kernel/main.h"


ZEPHIR_INIT_CLASS(Tensor_Statistical)
{
	ZEPHIR_REGISTER_INTERFACE(Tensor, Statistical, tensor, statistical, tensor_statistical_method_entry);

	return SUCCESS;
}

/**
 * Return the mean of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Statistical, mean);
/**
 * Compute the variance of the tensor.
 *
 * @param mixed mean
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Statistical, variance);
/**
 * Return the median of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Statistical, median);
/**
 * Return the q'th quantile of the tensor.
 *
 * @param float q
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Statistical, quantile);
