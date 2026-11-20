
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_exceptions.h>

#include "kernel/main.h"


ZEPHIR_INIT_CLASS(Tensor_Tensor)
{
	ZEPHIR_REGISTER_INTERFACE(Tensor, Tensor, tensor, tensor, NULL);

	zephir_declare_class_constant_double(tensor_tensor_ce, SL("EPSILON"), 0.00000001);

	zend_class_implements(tensor_tensor_ce, 1, tensor_arraylike_ce);
	zend_class_implements(tensor_tensor_ce, 1, tensor_arithmetic_ce);
	zend_class_implements(tensor_tensor_ce, 1, tensor_comparable_ce);
	zend_class_implements(tensor_tensor_ce, 1, tensor_algebraic_ce);
	zend_class_implements(tensor_tensor_ce, 1, tensor_trigonometric_ce);
	zend_class_implements(tensor_tensor_ce, 1, tensor_statistical_ce);
	zend_class_implements(tensor_tensor_ce, 1, tensor_special_ce);
	return SUCCESS;
}

