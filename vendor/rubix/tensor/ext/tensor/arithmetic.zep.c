
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_exceptions.h>

#include "kernel/main.h"


ZEPHIR_INIT_CLASS(Tensor_Arithmetic)
{
	ZEPHIR_REGISTER_INTERFACE(Tensor, Arithmetic, tensor, arithmetic, tensor_arithmetic_method_entry);

	return SUCCESS;
}

/**
 * A universal function to multiply this tensor with another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Arithmetic, multiply);
/**
 * A universal function to divide this tensor by another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Arithmetic, divide);
/**
 * A universal function to add this tensor with another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Arithmetic, add);
/**
 * A universal function to subtract this tensor from another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Arithmetic, subtract);
/**
 * A universal function to raise this tensor to the power of another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Arithmetic, pow);
/**
 * A universal function to compute the integer modulus of this tensor and another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Arithmetic, mod);
