
#ifdef HAVE_CONFIG_H
#include "../ext_config.h"
#endif

#include <php.h>
#include "../php_ext.h"
#include "../ext.h"

#include <Zend/zend_operators.h>
#include <Zend/zend_exceptions.h>
#include <Zend/zend_interfaces.h>

#include "kernel/main.h"
#include "kernel/exception.h"
#include "kernel/memory.h"
#include "kernel/fcall.h"
#include "kernel/concat.h"
#include "kernel/operators.h"
#include "kernel/object.h"
#include "include/settings.h"


/**
 * Settings
 *
 * @internal
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
ZEPHIR_INIT_CLASS(Tensor_Settings)
{
	ZEPHIR_REGISTER_CLASS(Tensor, Settings, tensor, settings, tensor_settings_method_entry, 0);

	return SUCCESS;
}

/**
 * Set the number of CPU threads to use when multiprocessing.
 *
 * @param int threads
 * @return void
 */
PHP_METHOD(Tensor_Settings, setNumThreads)
{
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zval *threads_param = NULL, _0$$3, _1$$3, _2$$3, _3$$3, status, _4;
	zend_long threads, ZEPHIR_LAST_CALL_STATUS;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&_0$$3);
	ZVAL_UNDEF(&_1$$3);
	ZVAL_UNDEF(&_2$$3);
	ZVAL_UNDEF(&_3$$3);
	ZVAL_UNDEF(&status);
	ZVAL_UNDEF(&_4);
#if PHP_VERSION_ID >= 80000
	bool is_null_true = 1;
	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_LONG(threads)
	ZEND_PARSE_PARAMETERS_END();
#endif


	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &threads_param);
	threads = zephir_get_intval(threads_param);


	if (UNEXPECTED(threads < 1)) {
		ZEPHIR_INIT_VAR(&_0$$3);
		object_init_ex(&_0$$3, tensor_exceptions_invalidargumentexception_ce);
		ZVAL_LONG(&_1$$3, threads);
		ZEPHIR_CALL_FUNCTION(&_2$$3, "strval", NULL, 4, &_1$$3);
		zephir_check_call_status();
		ZEPHIR_INIT_VAR(&_3$$3);
		ZEPHIR_CONCAT_SSVS(&_3$$3, "The number of threads", " must be greater than 0, ", &_2$$3, " given.");
		ZEPHIR_CALL_METHOD(NULL, &_0$$3, "__construct", NULL, 3, &_3$$3);
		zephir_check_call_status();
		zephir_throw_exception_debug(&_0$$3, "tensor/settings.zep", 26);
		ZEPHIR_MM_RESTORE();
		return;
	}
	ZEPHIR_INIT_VAR(&status);
	ZVAL_LONG(&_4, threads);
	tensor_set_num_threads(&status, &_4);
	ZEPHIR_MM_RESTORE();
}

/**
 * Return the number of CPU threads used for multiprocessing.
 *
 * @return int
 */
PHP_METHOD(Tensor_Settings, numThreads)
{
	zval *this_ptr = getThis();



	tensor_get_num_threads(return_value);
	return;
}

