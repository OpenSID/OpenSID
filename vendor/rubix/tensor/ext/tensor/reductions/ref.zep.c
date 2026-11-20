
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
#include "kernel/exception.h"
#include "kernel/operators.h"
#include "kernel/array.h"
#include "kernel/object.h"
#include "kernel/concat.h"
#include "include/linear_algebra.h"


/**
 * REF
 *
 * The row echelon form (REF) of a matrix.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
ZEPHIR_INIT_CLASS(Tensor_Reductions_Ref)
{
	ZEPHIR_REGISTER_CLASS(Tensor\\Reductions, Ref, tensor, reductions_ref, tensor_reductions_ref_method_entry, 0);

	/**
	 * The reduced matrix in row echelon form.
	 *
	 * @var \Tensor\Matrix
	 */
	zend_declare_property_null(tensor_reductions_ref_ce, SL("a"), ZEND_ACC_PROTECTED);
	/**
	 * The number of swaps made to compute the row echelon form of the matrix.
	 *
	 * @var int
	 */
	zend_declare_property_null(tensor_reductions_ref_ce, SL("swaps"), ZEND_ACC_PROTECTED);
	return SUCCESS;
}

/**
 * Factory method to decompose a matrix.
 *
 * @param \Tensor\Matrix a
 * @throws \Tensor\Exceptions\RuntimeException
 * @return self
 */
PHP_METHOD(Tensor_Reductions_Ref, reduce)
{
	zval ref, _1;
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zephir_fcall_cache_entry *_2 = NULL;
	zend_long ZEPHIR_LAST_CALL_STATUS;
	zval *a, a_sub, result, _0, b, _3, swaps;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&a_sub);
	ZVAL_UNDEF(&result);
	ZVAL_UNDEF(&_0);
	ZVAL_UNDEF(&b);
	ZVAL_UNDEF(&_3);
	ZVAL_UNDEF(&swaps);
	ZVAL_UNDEF(&ref);
	ZVAL_UNDEF(&_1);
#if PHP_VERSION_ID >= 80000
	bool is_null_true = 1;
	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_OBJECT_OF_CLASS(a, zephir_get_internal_ce(SL("tensor\\matrix")))
	ZEND_PARSE_PARAMETERS_END();
#endif


	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &a);


	ZEPHIR_INIT_VAR(&result);
	ZEPHIR_CALL_METHOD(&_0, a, "asarray", NULL, 0);
	zephir_check_call_status();
	tensor_ref(&result, &_0);
	if (Z_TYPE_P(&result) == IS_NULL) {
		ZEPHIR_THROW_EXCEPTION_DEBUG_STR(tensor_exceptions_runtimeexception_ce, "Failed to decompose matrix.", "tensor/reductions/ref.zep", 44);
		return;
	}
	ZEPHIR_INIT_VAR(&ref);
	array_init(&ref);
	zephir_get_arrval(&_1, &result);
	ZEPHIR_CPY_WRT(&ref, &_1);
	zephir_array_fetch_long(&_3, &ref, 0, PH_NOISY | PH_READONLY, "tensor/reductions/ref.zep", 51);
	ZEPHIR_CALL_CE_STATIC(&b, tensor_matrix_ce, "quick", &_2, 0, &_3);
	zephir_check_call_status();
	ZEPHIR_OBS_VAR(&swaps);
	zephir_array_fetch_long(&swaps, &ref, 1, PH_NOISY, "tensor/reductions/ref.zep", 52);
	object_init_ex(return_value, tensor_reductions_ref_ce);
	ZEPHIR_CALL_METHOD(NULL, return_value, "__construct", NULL, 31, &b, &swaps);
	zephir_check_call_status();
	RETURN_MM();
}

/**
 * @param \Tensor\Matrix a
 * @param int swaps
 * @throws \Tensor\Exceptions\InvalidArgumentException
 */
PHP_METHOD(Tensor_Reductions_Ref, __construct)
{
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zend_long swaps, ZEPHIR_LAST_CALL_STATUS;
	zval *a, a_sub, *swaps_param = NULL, _0$$3, _1$$3, _2$$3, _3$$3, _4;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&a_sub);
	ZVAL_UNDEF(&_0$$3);
	ZVAL_UNDEF(&_1$$3);
	ZVAL_UNDEF(&_2$$3);
	ZVAL_UNDEF(&_3$$3);
	ZVAL_UNDEF(&_4);
#if PHP_VERSION_ID >= 80000
	bool is_null_true = 1;
	ZEND_PARSE_PARAMETERS_START(2, 2)
		Z_PARAM_OBJECT_OF_CLASS(a, zephir_get_internal_ce(SL("tensor\\matrix")))
		Z_PARAM_LONG(swaps)
	ZEND_PARSE_PARAMETERS_END();
#endif


	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 2, 0, &a, &swaps_param);
	swaps = zephir_get_intval(swaps_param);


	if (UNEXPECTED(swaps < 0)) {
		ZEPHIR_INIT_VAR(&_0$$3);
		object_init_ex(&_0$$3, tensor_exceptions_invalidargumentexception_ce);
		ZVAL_LONG(&_1$$3, swaps);
		ZEPHIR_CALL_FUNCTION(&_2$$3, "strval", NULL, 4, &_1$$3);
		zephir_check_call_status();
		ZEPHIR_INIT_VAR(&_3$$3);
		ZEPHIR_CONCAT_SSVS(&_3$$3, "The number of swaps must", " be greater than or equal to 0, ", &_2$$3, " given.");
		ZEPHIR_CALL_METHOD(NULL, &_0$$3, "__construct", NULL, 3, &_3$$3);
		zephir_check_call_status();
		zephir_throw_exception_debug(&_0$$3, "tensor/reductions/ref.zep", 67);
		ZEPHIR_MM_RESTORE();
		return;
	}
	zephir_update_property_zval(this_ptr, ZEND_STRL("a"), a);
	ZEPHIR_INIT_ZVAL_NREF(_4);
	ZVAL_LONG(&_4, swaps);
	zephir_update_property_zval(this_ptr, ZEND_STRL("swaps"), &_4);
	ZEPHIR_MM_RESTORE();
}

/**
 * Return the reduced matrix in row echelon form.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Reductions_Ref, a)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "a");
}

/**
 * Return the number of swaps made to reduce the matrix to ref.
 *
 * @return int
 */
PHP_METHOD(Tensor_Reductions_Ref, swaps)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "swaps");
}

