
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_exceptions.h>

#include "kernel/main.h"


ZEPHIR_INIT_CLASS(Tensor_Algebraic)
{
	ZEPHIR_REGISTER_INTERFACE(Tensor, Algebraic, tensor, algebraic, tensor_algebraic_method_entry);

	zephir_declare_class_constant_double(tensor_algebraic_ce, SL("M_E"), 2.7182818284590452354);

	return SUCCESS;
}

/**
 * Take the absolute value of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, abs);
/**
 * Square the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, square);
/**
 * Return the square root of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, sqrt);
/**
 * Return the exponential of the tensor.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, exp);
/**
 * Return the exponential of the tensor minus 1.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, expm1);
/**
 * Return the logarithm of the tensor in a specified base.
 *
 * @param float base
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, log);
/**
 * Return the log of 1 plus the tensor i.e. a transform.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, log1p);
/**
 * Round the elements in the tensor to a given decimal place.
 *
 * @param int precision
 * @return self
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, round);
/**
 * Round the tensor down to the nearest integer.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, floor);
/**
 * Round the tensor up to the nearest integer.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, ceil);
/**
 * Return the element-wise sign indication.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, sign);
/**
 * Negate the tensor i.e take the negative.
 *
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Algebraic, negate);
