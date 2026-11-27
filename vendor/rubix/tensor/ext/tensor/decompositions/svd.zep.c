
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
#include "include/linear_algebra.h"


/**
 * SVD
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
ZEPHIR_INIT_CLASS(Tensor_Decompositions_Svd)
{
	ZEPHIR_REGISTER_CLASS(Tensor\\Decompositions, Svd, tensor, decompositions_svd, tensor_decompositions_svd_method_entry, 0);

	/**
	 * The U matrix.
	 *
	 * @var \Tensor\Matrix
	 */
	zend_declare_property_null(tensor_decompositions_svd_ce, SL("u"), ZEND_ACC_PROTECTED);
	/**
	 * The singular values of the matrix A.
	 *
	 * @var (int|float)[]
	 */
	zend_declare_property_null(tensor_decompositions_svd_ce, SL("singularValues"), ZEND_ACC_PROTECTED);
	/**
	 * The transposed V matrix.
	 *
	 * @var \Tensor\Matrix
	 */
	zend_declare_property_null(tensor_decompositions_svd_ce, SL("vT"), ZEND_ACC_PROTECTED);
	return SUCCESS;
}

/**
 * Factory method to decompose a matrix.
 *
 * @param \Tensor\Matrix a
 * @throws \Tensor\Exceptions\RuntimeException
 * @return self
 */
PHP_METHOD(Tensor_Decompositions_Svd, decompose)
{
	zval usvT, _1;
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zephir_fcall_cache_entry *_2 = NULL;
	zend_long ZEPHIR_LAST_CALL_STATUS;
	zval *a, a_sub, result, _0, u, _3, singularValues, vT, _4;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&a_sub);
	ZVAL_UNDEF(&result);
	ZVAL_UNDEF(&_0);
	ZVAL_UNDEF(&u);
	ZVAL_UNDEF(&_3);
	ZVAL_UNDEF(&singularValues);
	ZVAL_UNDEF(&vT);
	ZVAL_UNDEF(&_4);
	ZVAL_UNDEF(&usvT);
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
	tensor_svd(&result, &_0);
	if (Z_TYPE_P(&result) == IS_NULL) {
		ZEPHIR_THROW_EXCEPTION_DEBUG_STR(tensor_exceptions_runtimeexception_ce, "Failed to decompose matrix.", "tensor/decompositions/svd.zep", 48);
		return;
	}
	ZEPHIR_INIT_VAR(&usvT);
	array_init(&usvT);
	zephir_get_arrval(&_1, &result);
	ZEPHIR_CPY_WRT(&usvT, &_1);
	zephir_array_fetch_long(&_3, &usvT, 0, PH_NOISY | PH_READONLY, "tensor/decompositions/svd.zep", 55);
	ZEPHIR_CALL_CE_STATIC(&u, tensor_matrix_ce, "quick", &_2, 0, &_3);
	zephir_check_call_status();
	ZEPHIR_OBS_VAR(&singularValues);
	zephir_array_fetch_long(&singularValues, &usvT, 1, PH_NOISY, "tensor/decompositions/svd.zep", 56);
	zephir_array_fetch_long(&_4, &usvT, 2, PH_NOISY | PH_READONLY, "tensor/decompositions/svd.zep", 57);
	ZEPHIR_CALL_CE_STATIC(&vT, tensor_matrix_ce, "quick", &_2, 0, &_4);
	zephir_check_call_status();
	object_init_ex(return_value, tensor_decompositions_svd_ce);
	ZEPHIR_CALL_METHOD(NULL, return_value, "__construct", NULL, 27, &u, &singularValues, &vT);
	zephir_check_call_status();
	RETURN_MM();
}

/**
 * @param \Tensor\Matrix u
 * @param list<int|float> singularValues
 * @param \Tensor\Matrix vT
 */
PHP_METHOD(Tensor_Decompositions_Svd, __construct)
{
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zval singularValues;
	zval *u, u_sub, *singularValues_param = NULL, *vT, vT_sub;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&u_sub);
	ZVAL_UNDEF(&vT_sub);
	ZVAL_UNDEF(&singularValues);
#if PHP_VERSION_ID >= 80000
	bool is_null_true = 1;
	ZEND_PARSE_PARAMETERS_START(3, 3)
		Z_PARAM_OBJECT_OF_CLASS(u, zephir_get_internal_ce(SL("tensor\\matrix")))
		Z_PARAM_ARRAY(singularValues)
		Z_PARAM_OBJECT_OF_CLASS(vT, zephir_get_internal_ce(SL("tensor\\matrix")))
	ZEND_PARSE_PARAMETERS_END();
#endif


	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 3, 0, &u, &singularValues_param, &vT);
	zephir_get_arrval(&singularValues, singularValues_param);


	zephir_update_property_zval(this_ptr, ZEND_STRL("u"), u);
	zephir_update_property_zval(this_ptr, ZEND_STRL("singularValues"), &singularValues);
	zephir_update_property_zval(this_ptr, ZEND_STRL("vT"), vT);
	ZEPHIR_MM_RESTORE();
}

/**
 * Return the U matrix.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Decompositions_Svd, u)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "u");
}

/**
 * Return the singular values of matrix A.
 *
 * @return list<float>
 */
PHP_METHOD(Tensor_Decompositions_Svd, singularValues)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "singularValues");
}

/**
 * Return the singular value matrix.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Decompositions_Svd, s)
{
	zval _1;
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zend_long ZEPHIR_LAST_CALL_STATUS;
	zephir_fcall_cache_entry *_0 = NULL;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&_1);


	ZEPHIR_MM_GROW();

	zephir_read_property(&_1, this_ptr, ZEND_STRL("singularValues"), PH_NOISY_CC | PH_READONLY);
	ZEPHIR_RETURN_CALL_CE_STATIC(tensor_matrix_ce, "diagonal", &_0, 0, &_1);
	zephir_check_call_status();
	RETURN_MM();
}

/**
 * Return the V matrix.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Decompositions_Svd, v)
{
	zval _0;
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zend_long ZEPHIR_LAST_CALL_STATUS;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&_0);


	ZEPHIR_MM_GROW();

	zephir_read_property(&_0, this_ptr, ZEND_STRL("vT"), PH_NOISY_CC | PH_READONLY);
	ZEPHIR_RETURN_CALL_METHOD(&_0, "transpose", NULL, 0);
	zephir_check_call_status();
	RETURN_MM();
}

/**
 * Return the V transposed matrix.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Decompositions_Svd, vT)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "vT");
}

