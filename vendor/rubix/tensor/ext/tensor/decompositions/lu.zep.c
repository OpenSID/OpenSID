
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
#include "kernel/array.h"
#include "kernel/object.h"
#include "include/linear_algebra.h"


/**
 * LU
 *
 * The LU decomposition is a factorization of a Matrix as the product of a
 * lower and upper triangular matrix as well as a permutation matrix.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
ZEPHIR_INIT_CLASS(Tensor_Decompositions_Lu)
{
	ZEPHIR_REGISTER_CLASS(Tensor\\Decompositions, Lu, tensor, decompositions_lu, tensor_decompositions_lu_method_entry, 0);

	/**
	 * The lower triangular matrix.
	 *
	 * @var \Tensor\Matrix
	 */
	zend_declare_property_null(tensor_decompositions_lu_ce, SL("l"), ZEND_ACC_PROTECTED);
	/**
	 * The upper triangular matrix.
	 *
	 * @var \Tensor\Matrix
	 */
	zend_declare_property_null(tensor_decompositions_lu_ce, SL("u"), ZEND_ACC_PROTECTED);
	/**
	 * The permutation matrix.
	 *
	 * @var \Tensor\Matrix
	 */
	zend_declare_property_null(tensor_decompositions_lu_ce, SL("p"), ZEND_ACC_PROTECTED);
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
PHP_METHOD(Tensor_Decompositions_Lu, decompose)
{
	zval lup, _5;
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zephir_fcall_cache_entry *_6 = NULL;
	zend_long ZEPHIR_LAST_CALL_STATUS;
	zval *a, a_sub, _0, result, _4, l, _7, u, _8, p, _9, _1$$3, _2$$3, _3$$3;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&a_sub);
	ZVAL_UNDEF(&_0);
	ZVAL_UNDEF(&result);
	ZVAL_UNDEF(&_4);
	ZVAL_UNDEF(&l);
	ZVAL_UNDEF(&_7);
	ZVAL_UNDEF(&u);
	ZVAL_UNDEF(&_8);
	ZVAL_UNDEF(&p);
	ZVAL_UNDEF(&_9);
	ZVAL_UNDEF(&_1$$3);
	ZVAL_UNDEF(&_2$$3);
	ZVAL_UNDEF(&_3$$3);
	ZVAL_UNDEF(&lup);
	ZVAL_UNDEF(&_5);
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
	if (UNEXPECTED(!zephir_is_true(&_0))) {
		ZEPHIR_INIT_VAR(&_1$$3);
		object_init_ex(&_1$$3, tensor_exceptions_invalidargumentexception_ce);
		ZEPHIR_CALL_METHOD(&_2$$3, a, "shapestring", NULL, 0);
		zephir_check_call_status();
		ZEPHIR_INIT_VAR(&_3$$3);
		ZEPHIR_CONCAT_SSVS(&_3$$3, "Matrix must be", " square, ", &_2$$3, " given.");
		ZEPHIR_CALL_METHOD(NULL, &_1$$3, "__construct", NULL, 3, &_3$$3);
		zephir_check_call_status();
		zephir_throw_exception_debug(&_1$$3, "tensor/decompositions/lu.zep", 52);
		ZEPHIR_MM_RESTORE();
		return;
	}
	ZEPHIR_INIT_VAR(&result);
	ZEPHIR_CALL_METHOD(&_4, a, "asarray", NULL, 0);
	zephir_check_call_status();
	tensor_lu(&result, &_4);
	if (Z_TYPE_P(&result) == IS_NULL) {
		ZEPHIR_THROW_EXCEPTION_DEBUG_STR(tensor_exceptions_runtimeexception_ce, "Failed to decompose matrix.", "tensor/decompositions/lu.zep", 58);
		return;
	}
	ZEPHIR_INIT_VAR(&lup);
	array_init(&lup);
	zephir_get_arrval(&_5, &result);
	ZEPHIR_CPY_WRT(&lup, &_5);
	zephir_array_fetch_long(&_7, &lup, 0, PH_NOISY | PH_READONLY, "tensor/decompositions/lu.zep", 65);
	ZEPHIR_CALL_CE_STATIC(&l, tensor_matrix_ce, "quick", &_6, 0, &_7);
	zephir_check_call_status();
	zephir_array_fetch_long(&_8, &lup, 1, PH_NOISY | PH_READONLY, "tensor/decompositions/lu.zep", 66);
	ZEPHIR_CALL_CE_STATIC(&u, tensor_matrix_ce, "quick", &_6, 0, &_8);
	zephir_check_call_status();
	zephir_array_fetch_long(&_9, &lup, 2, PH_NOISY | PH_READONLY, "tensor/decompositions/lu.zep", 67);
	ZEPHIR_CALL_CE_STATIC(&p, tensor_matrix_ce, "quick", &_6, 0, &_9);
	zephir_check_call_status();
	object_init_ex(return_value, tensor_decompositions_lu_ce);
	ZEPHIR_CALL_METHOD(NULL, return_value, "__construct", NULL, 26, &l, &u, &p);
	zephir_check_call_status();
	RETURN_MM();
}

/**
 * @param \Tensor\Matrix l
 * @param \Tensor\Matrix u
 * @param \Tensor\Matrix p
 */
PHP_METHOD(Tensor_Decompositions_Lu, __construct)
{
	zval *l, l_sub, *u, u_sub, *p, p_sub;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&l_sub);
	ZVAL_UNDEF(&u_sub);
	ZVAL_UNDEF(&p_sub);
#if PHP_VERSION_ID >= 80000
	bool is_null_true = 1;
	ZEND_PARSE_PARAMETERS_START(3, 3)
		Z_PARAM_OBJECT_OF_CLASS(l, zephir_get_internal_ce(SL("tensor\\matrix")))
		Z_PARAM_OBJECT_OF_CLASS(u, zephir_get_internal_ce(SL("tensor\\matrix")))
		Z_PARAM_OBJECT_OF_CLASS(p, zephir_get_internal_ce(SL("tensor\\matrix")))
	ZEND_PARSE_PARAMETERS_END();
#endif


	zephir_fetch_params_without_memory_grow(3, 0, &l, &u, &p);


	zephir_update_property_zval(this_ptr, ZEND_STRL("l"), l);
	zephir_update_property_zval(this_ptr, ZEND_STRL("u"), u);
	zephir_update_property_zval(this_ptr, ZEND_STRL("p"), p);
}

/**
 * Return the lower triangular matrix.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Decompositions_Lu, l)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "l");
}

/**
 * Return the upper triangular matrix.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Decompositions_Lu, u)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "u");
}

/**
 * Return the permutation matrix.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Decompositions_Lu, p)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "p");
}

