
#ifdef HAVE_CONFIG_H
#include "../../ext_config.h"
#endif

#include <php.h>
#include "../../php_ext.h"
#include "../../ext.h"

#include <Zend/zend_exceptions.h>

#include "kernel/main.h"


ZEPHIR_INIT_CLASS(Tensor_Exceptions_TensorException)
{
	ZEPHIR_REGISTER_INTERFACE(Tensor\\Exceptions, TensorException, tensor, exceptions_tensorexception, NULL);

	zend_class_implements(tensor_exceptions_tensorexception_ce, 1, zend_ce_throwable);
	return SUCCESS;
}

