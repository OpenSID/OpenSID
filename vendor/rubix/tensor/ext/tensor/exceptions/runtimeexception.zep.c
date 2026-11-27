
#ifdef HAVE_CONFIG_H
#include "../../ext_config.h"
#endif

#include <php.h>
#include "../../php_ext.h"
#include "../../ext.h"

#include <Zend/zend_operators.h>
#include <Zend/zend_exceptions.h>
#include <Zend/zend_interfaces.h>

#include "kernel/main.h"
#include "ext/spl/spl_exceptions.h"


ZEPHIR_INIT_CLASS(Tensor_Exceptions_RuntimeException)
{
	ZEPHIR_REGISTER_CLASS_EX(Tensor\\Exceptions, RuntimeException, tensor, exceptions_runtimeexception, spl_ce_RuntimeException, NULL, 0);

	zend_class_implements(tensor_exceptions_runtimeexception_ce, 1, tensor_exceptions_tensorexception_ce);
	return SUCCESS;
}

