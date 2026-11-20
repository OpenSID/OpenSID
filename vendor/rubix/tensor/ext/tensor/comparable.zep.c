
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_exceptions.h>

#include "kernel/main.h"


ZEPHIR_INIT_CLASS(Tensor_Comparable)
{
	ZEPHIR_REGISTER_INTERFACE(Tensor, Comparable, tensor, comparable, tensor_comparable_method_entry);

	return SUCCESS;
}

/**
 * A universal function to compute the equality comparison of a tensor and another tensor element-wise.
 *
 * @param  mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Comparable, equal);
/**
 * A universal function to compute the not equal comparison of this tensor and another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Comparable, notEqual);
/**
 * A universal function to compute the greater than comparison of a tensor and another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Comparable, greater);
/**
 * A universal function to compute the greater than or equal to comparison of a tensor and another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Comparable, greaterEqual);
/**
 * A universal function to compute the less than comparison of a tensor and another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Comparable, less);
/**
 * A universal function to compute the less than or equal to comparison of a tensor and another tensor element-wise.
 *
 * @param mixed b
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_Comparable, lessEqual);
