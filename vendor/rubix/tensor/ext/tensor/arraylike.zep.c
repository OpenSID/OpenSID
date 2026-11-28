
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_exceptions.h>

#include "kernel/main.h"


ZEPHIR_INIT_CLASS(Tensor_ArrayLike)
{
	ZEPHIR_REGISTER_INTERFACE(Tensor, ArrayLike, tensor, arraylike, tensor_arraylike_method_entry);

	zend_class_implements(tensor_arraylike_ce, 1, zend_ce_arrayaccess);
	zend_class_implements(tensor_arraylike_ce, 1, zend_ce_aggregate);
	zend_class_implements(tensor_arraylike_ce, 1, zend_ce_countable);
	return SUCCESS;
}

/**
 * Return a tuple with the dimensionality of the array-like.
 *
 * @return int[]
 */
ZEPHIR_DOC_METHOD(Tensor_ArrayLike, shape);
/**
 * Return the shape of the tensor as a string.
 *
 * @return string
 */
ZEPHIR_DOC_METHOD(Tensor_ArrayLike, shapeString);
/**
 * Return the number of elements in the array-like object.
 *
 * @return int
 */
ZEPHIR_DOC_METHOD(Tensor_ArrayLike, size);
/**
 * Map a function over all of the elements in the tensor.
 *
 * @param callable callback
 * @return mixed
 */
ZEPHIR_DOC_METHOD(Tensor_ArrayLike, map);
/**
 * Reduce the tensor down to a scalar.
 *
 * @param callable callback
 * @param float initial
 * @return float
 */
ZEPHIR_DOC_METHOD(Tensor_ArrayLike, reduce);
/**
 * Return the elements of the tensor as an array.
 *
 * @return array
 */
ZEPHIR_DOC_METHOD(Tensor_ArrayLike, asArray);
