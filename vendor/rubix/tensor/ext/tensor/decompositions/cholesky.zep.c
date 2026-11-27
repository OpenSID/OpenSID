
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
#include "kernel/memory.h"
#include "kernel/fcall.h"
#include "kernel/operators.h"
#include "kernel/exception.h"
#include "kernel/concat.h"
#include "kernel/object.h"
#include "include/linear_algebra.h"


/**
 * Cholesky
 *
 * An efficient decomposition of a square positive definite matrix into a lower triangular matrix and its conjugate transpose.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
ZEPHIR_INIT_CLASS(Tensor_Decompositions_Cholesky)
{
	ZEPHIR_REGISTER_CLASS(Tensor\\Decompositions, Cholesky, tensor, decompositions_cholesky, tensor_decompositions_cholesky_method_entry, 0);

	/**
	 * The lower triangular matrix.
	 * 
	 * @var \Tensor\Matrix
	 */
	zend_declare_property_null(tensor_decompositions_cholesky_ce, SL("l"), ZEND_ACC_PROTECTED);
	return SUCCESS;
}

/**
 * Factory method to decompose a matrix.
 *
 * @param \Tensor\Matrix a
 * @throws \Tensor\Exceptions\InvalidArgumentException
 * @throws \Tensor\Exceptions\RuntimeException
 * @return self
 */
PHP_METHOD(Tensor_Decompositions_Cholesky, decompose)
{
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zephir_fcall_cache_entry *_6 = NULL;
	zend_long ZEPHIR_LAST_CALL_STATUS;
	zval *a, a_sub, _0, l, _4, _5, _1$$3, _2$$3, _3$$3;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&a_sub);
	ZVAL_UNDEF(&_0);
	ZVAL_UNDEF(&l);
	ZVAL_UNDEF(&_4);
	ZVAL_UNDEF(&_5);
	ZVAL_UNDEF(&_1$$3);
	ZVAL_UNDEF(&_2$$3);
	ZVAL_UNDEF(&_3$$3);
#if PHP_VERSION_ID >= 80000
	bool is_null_true = 1;
	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_OBJECT_OF_CLASS(a, zephir_get_internal_ce(SL("tensor\\matrix")))
	ZEND_PARSE_PARAMETERS_END();
#endif


	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &a);


	ZEPHIR_CALL_METHOD(&_0, a, "issquare", NULL, 0);
	zephir_check_call_status();
	if (!(zephir_is_true(&_0))) {
		ZEPHIR_INIT_VAR(&_1$$3);
		object_init_ex(&_1$$3, tensor_exceptions_invalidargumentexception_ce);
		ZEPHIR_CALL_METHOD(&_2$$3, a, "shapestring", NULL, 0);
		zephir_check_call_status();
		ZEPHIR_INIT_VAR(&_3$$3);
		ZEPHIR_CONCAT_SSVS(&_3$$3, "Matrix must be", " square, ", &_2$$3, " given.");
		ZEPHIR_CALL_METHOD(NULL, &_1$$3, "__construct", NULL, 3, &_3$$3);
		zephir_check_call_status();
		zephir_throw_exception_debug(&_1$$3, "tensor/decompositions/cholesky.zep", 37);
		ZEPHIR_MM_RESTORE();
		return;
	}
	ZEPHIR_INIT_VAR(&l);
	ZEPHIR_CALL_METHOD(&_4, a, "asarray", NULL, 0);
	zephir_check_call_status();
	tensor_cholesky(&l, &_4);
	if (Z_TYPE_P(&l) == IS_NULL) {
		ZEPHIR_THROW_EXCEPTION_DEBUG_STR(tensor_exceptions_runtimeexception_ce, "Failed to decompose matrix.", "tensor/decompositions/cholesky.zep", 43);
		return;
	}
	object_init_ex(return_value, tensor_decompositions_cholesky_ce);
	ZEPHIR_CALL_CE_STATIC(&_5, tensor_matrix_ce, "quick", &_6, 0, &l);
	zephir_check_call_status();
	ZEPHIR_CALL_METHOD(NULL, return_value, "__construct", NULL, 24, &_5);
	zephir_check_call_status();
	RETURN_MM();
}

/**
 * @param \Tensor\Matrix l
 */
PHP_METHOD(Tensor_Decompositions_Cholesky, __construct)
{
	zval *l, l_sub;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&l_sub);
#if PHP_VERSION_ID >= 80000
	bool is_null_true = 1;
	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_OBJECT_OF_CLASS(l, zephir_get_internal_ce(SL("tensor\\matrix")))
	ZEND_PARSE_PARAMETERS_END();
#endif


	zephir_fetch_params_without_memory_grow(1, 0, &l);


	zephir_update_property_zval(this_ptr, ZEND_STRL("l"), l);
}

/**
 * Return the lower triangular matrix.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Decompositions_Cholesky, l)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "l");
}

/**
 * Return the conjugate transpose of the lower triangular matrix.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Decompositions_Cholesky, lT)
{
	zval _0;
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zend_long ZEPHIR_LAST_CALL_STATUS;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&_0);


	ZEPHIR_MM_GROW();

	zephir_read_property(&_0, this_ptr, ZEND_STRL("l"), PH_NOISY_CC | PH_READONLY);
	ZEPHIR_RETURN_CALL_METHOD(&_0, "transpose", NULL, 0);
	zephir_check_call_status();
	RETURN_MM();
}

