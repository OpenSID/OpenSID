
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_exceptions.h>

#include "kernel/main.h"


ZEPHIR_INIT_CLASS(Tensor_Special)
{
	ZEPHIR_REGISTER_INTERFACE(Tensor, Special, tensor, special, tensor_special_method_entry);

	return SUCCESS;
}

/**
 * Sum the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Special, sum);
/**
 * Calculate the product of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Special, product);
/**
 * Return the minimum of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Special, min);
/**
 * Return the maximum of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Special, max);
/**
 * Clip the tensor to be between the given minimum and maximum.
 *
 * @param float min
 * @param float max
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Special, clip);
/**
 * Clip the tensor to be lower bounded by a given minimum.
 *
 * @param float min
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Special, clipLower);
/**
 * Clip the tensor to be upper bounded by a given maximum.
 *
 * @param float max
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Special, clipUpper);
