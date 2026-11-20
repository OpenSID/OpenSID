
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_exceptions.h>

#include "kernel/main.h"


ZEPHIR_INIT_CLASS(Tensor_Functional)
{
	ZEPHIR_REGISTER_INTERFACE(Tensor, Functional, tensor, functional, tensor_functional_method_entry);

	zephir_declare_class_constant_double(tensor_functional_ce, SL("M_E"), 2.7182818284590452354);

	return SUCCESS;
}

/**
 * Take the absolute value of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, abs);
/**
 * Square the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, square);
/**
 * Return the square root of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, sqrt);
/**
 * Return the exponential of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, exp);
/**
 * Return the exponential of the tensor minus 1.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, expm1);
/**
 * Return the logarithm of the tensor in a specified base.
 *
 * @param float base
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, log);
/**
 * Return the log of 1 plus the tensor i.e. a transform.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, log1p);
/**
 * Sum the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, sum);
/**
 * Calculate the product of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, product);
/**
 * Return the minimum of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, min);
/**
 * Return the maximum of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, max);
/**
 * Round the elements in the tensor to a given decimal place.
 *
 * @param int precision
 * @return self
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, round);
/**
 * Round the tensor down to the nearest integer.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, floor);
/**
 * Round the tensor up to the nearest integer.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, ceil);
/**
 * Return the element-wise sign indication.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, sign);
/**
 * Negate the tensor i.e take the negative.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, negate);
/**
 * Clip the tensor to be between the given minimum and maximum.
 *
 * @param float min
 * @param float max
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, clip);
/**
 * Clip the tensor to be lower bounded by a given minimum.
 *
 * @param float min
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, clipLower);
/**
 * Clip the tensor to be upper bounded by a given maximum.
 *
 * @param float max
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Functional, clipUpper);
