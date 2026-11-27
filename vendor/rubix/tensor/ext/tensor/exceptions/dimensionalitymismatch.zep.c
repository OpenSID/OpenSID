
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


ZEPHIR_INIT_CLASS(Tensor_Exceptions_DimensionalityMismatch)
{
	ZEPHIR_REGISTER_CLASS_EX(Tensor\\Exceptions, DimensionalityMismatch, tensor, exceptions_dimensionalitymismatch, tensor_exceptions_invalidargumentexception_ce, NULL, 0);

	return SUCCESS;
}

