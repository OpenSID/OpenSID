
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
#include "kernel/array.h"
#include "kernel/object.h"


/**
 * RREF
 *
 * The reduced row echelon form (RREF) of a matrix.
 *
 * @category    Scientific Computing
 * @package     Rubix/Tensor
 * @author      Andrew DalPino
 */
ZEPHIR_INIT_CLASS(Tensor_Reductions_Rref)
{
	ZEPHIR_REGISTER_CLASS(Tensor\\Reductions, Rref, tensor, reductions_rref, tensor_reductions_rref_method_entry, 0);

	/**
	 * The reduced matrix in reduced row echelon form.
	 *
	 * @var \Tensor\Matrix
	 */
	zend_declare_property_null(tensor_reductions_rref_ce, SL("a"), ZEND_ACC_PROTECTED);
	return SUCCESS;
}

/**
 * Factory method to decompose a matrix.
 *
 * @param \Tensor\Matrix a
 * @return self
 */
PHP_METHOD(Tensor_Reductions_Rref, reduce)
{
	zend_bool _6, _18$$3, _13$$5, _24$$8;
	zval b, rowB, t, _5, _8$$3, _22$$7;
	double scale = 0, divisor = 0;
	zephir_method_globals *ZEPHIR_METHOD_GLOBALS_PTR = NULL;
	zephir_fcall_cache_entry *_11 = NULL, *_31 = NULL;
	zend_long ZEPHIR_LAST_CALL_STATUS, i = 0, j = 0, m, n, row, col, _19$$3, _20$$3, _14$$5, _15$$5, _25$$8, _26$$8;
	zval *a, a_sub, _0, _1, _2, _3, _4, _30, _7$$3, _9$$3, _10$$3, _12$$3, _16$$6, _17$$6, _21$$7, _23$$7, _27$$9, _28$$9, _29$$9;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&a_sub);
	ZVAL_UNDEF(&_0);
	ZVAL_UNDEF(&_1);
	ZVAL_UNDEF(&_2);
	ZVAL_UNDEF(&_3);
	ZVAL_UNDEF(&_4);
	ZVAL_UNDEF(&_30);
	ZVAL_UNDEF(&_7$$3);
	ZVAL_UNDEF(&_9$$3);
	ZVAL_UNDEF(&_10$$3);
	ZVAL_UNDEF(&_12$$3);
	ZVAL_UNDEF(&_16$$6);
	ZVAL_UNDEF(&_17$$6);
	ZVAL_UNDEF(&_21$$7);
	ZVAL_UNDEF(&_23$$7);
	ZVAL_UNDEF(&_27$$9);
	ZVAL_UNDEF(&_28$$9);
	ZVAL_UNDEF(&_29$$9);
	ZVAL_UNDEF(&b);
	ZVAL_UNDEF(&rowB);
	ZVAL_UNDEF(&t);
	ZVAL_UNDEF(&_5);
	ZVAL_UNDEF(&_8$$3);
	ZVAL_UNDEF(&_22$$7);
#if PHP_VERSION_ID >= 80000
	bool is_null_true = 1;
	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_OBJECT_OF_CLASS(a, zephir_get_internal_ce(SL("tensor\\matrix")))
	ZEND_PARSE_PARAMETERS_END();
#endif


	ZEPHIR_MM_GROW();
	zephir_fetch_params(1, 1, 0, &a);


	ZEPHIR_INIT_VAR(&b);
	array_init(&b);
	ZEPHIR_INIT_VAR(&rowB);
	array_init(&rowB);
	ZEPHIR_INIT_VAR(&t);
	array_init(&t);
	ZEPHIR_CALL_METHOD(&_0, a, "m", NULL, 0);
	zephir_check_call_status();
	m = zephir_get_intval(&_0);
	ZEPHIR_CALL_METHOD(&_1, a, "n", NULL, 0);
	zephir_check_call_status();
	n = zephir_get_intval(&_1);
	row = 0;
	col = 0;
	ZEPHIR_CALL_METHOD(&_2, a, "ref", NULL, 0);
	zephir_check_call_status();
	ZEPHIR_CALL_METHOD(&_3, &_2, "a", NULL, 0);
	zephir_check_call_status();
	ZEPHIR_CALL_METHOD(&_4, &_3, "asarray", NULL, 0);
	zephir_check_call_status();
	zephir_get_arrval(&_5, &_4);
	ZEPHIR_CPY_WRT(&b, &_5);
	while (1) {
		_6 = row < m;
		if (_6) {
			_6 = col < n;
		}
		if (!(_6)) {
			break;
		}
		ZEPHIR_OBS_NVAR(&_7$$3);
		zephir_array_fetch_long(&_7$$3, &b, row, PH_NOISY, "tensor/reductions/rref.zep", 49);
		zephir_get_arrval(&_8$$3, &_7$$3);
		ZEPHIR_CPY_WRT(&t, &_8$$3);
		zephir_array_fetch_long(&_9$$3, &t, col, PH_NOISY | PH_READONLY, "tensor/reductions/rref.zep", 51);
		ZEPHIR_CALL_FUNCTION(&_10$$3, "abs", &_11, 12, &_9$$3);
		zephir_check_call_status();
		if (ZEPHIR_IS_LONG(&_10$$3, 0)) {
			col++;
			continue;
		}
		ZEPHIR_OBS_NVAR(&_12$$3);
		zephir_array_fetch_long(&_12$$3, &t, col, PH_NOISY, "tensor/reductions/rref.zep", 57);
		divisor = zephir_get_doubleval(&_12$$3);
		if (divisor != 1.0) {
			_15$$5 = (n - 1);
			_14$$5 = 0;
			_13$$5 = 0;
			if (_14$$5 <= _15$$5) {
				while (1) {
					if (_13$$5) {
						_14$$5++;
						if (!(_14$$5 <= _15$$5)) {
							break;
						}
					} else {
						_13$$5 = 1;
					}
					i = _14$$5;
					zephir_array_fetch_long(&_16$$6, &t, i, PH_NOISY | PH_READONLY, "tensor/reductions/rref.zep", 61);
					ZEPHIR_INIT_NVAR(&_17$$6);
					ZVAL_DOUBLE(&_17$$6, zephir_safe_div_zval_double(&_16$$6, divisor));
					zephir_array_update_long(&t, i, &_17$$6, PH_COPY | PH_SEPARATE ZEPHIR_DEBUG_PARAMS_DUMMY);
				}
			}
		}
		_20$$3 = (row - 1);
		_19$$3 = _20$$3;
		_18$$3 = 0;
		if (_19$$3 >= 0) {
			while (1) {
				if (_18$$3) {
					_19$$3--;
					if (!(_19$$3 >= 0)) {
						break;
					}
				} else {
					_18$$3 = 1;
				}
				i = _19$$3;
				ZEPHIR_OBS_NVAR(&_21$$7);
				zephir_array_fetch_long(&_21$$7, &b, i, PH_NOISY, "tensor/reductions/rref.zep", 66);
				zephir_get_arrval(&_22$$7, &_21$$7);
				ZEPHIR_CPY_WRT(&rowB, &_22$$7);
				ZEPHIR_OBS_NVAR(&_23$$7);
				zephir_array_fetch_long(&_23$$7, &rowB, col, PH_NOISY, "tensor/reductions/rref.zep", 68);
				scale = zephir_get_doubleval(&_23$$7);
				if (scale != 0.0) {
					_26$$8 = (n - 1);
					_25$$8 = 0;
					_24$$8 = 0;
					if (_25$$8 <= _26$$8) {
						while (1) {
							if (_24$$8) {
								_25$$8++;
								if (!(_25$$8 <= _26$$8)) {
									break;
								}
							} else {
								_24$$8 = 1;
							}
							j = _25$$8;
							zephir_array_fetch_long(&_27$$9, &rowB, j, PH_NOISY | PH_READONLY, "tensor/reductions/rref.zep", 72);
							zephir_array_fetch_long(&_28$$9, &t, j, PH_NOISY | PH_READONLY, "tensor/reductions/rref.zep", 72);
							ZEPHIR_INIT_NVAR(&_29$$9);
							ZVAL_LONG(&_29$$9, (zephir_get_numberval(&_27$$9) - (scale * zephir_get_numberval(&_28$$9))));
							zephir_array_update_long(&rowB, j, &_29$$9, PH_COPY | PH_SEPARATE ZEPHIR_DEBUG_PARAMS_DUMMY);
						}
					}
				}
				zephir_array_update_long(&b, i, &rowB, PH_COPY | PH_SEPARATE ZEPHIR_DEBUG_PARAMS_DUMMY);
			}
		}
		zephir_array_update_long(&b, row, &t, PH_COPY | PH_SEPARATE ZEPHIR_DEBUG_PARAMS_DUMMY);
		row++;
		col++;
	}
	object_init_ex(return_value, tensor_reductions_rref_ce);
	ZEPHIR_CALL_CE_STATIC(&_30, tensor_matrix_ce, "quick", &_31, 0, &b);
	zephir_check_call_status();
	ZEPHIR_CALL_METHOD(NULL, return_value, "__construct", NULL, 32, &_30);
	zephir_check_call_status();
	RETURN_MM();
}

/**
 * @param \Tensor\Matrix a
 * @throws \InvalidArgumentException
 */
PHP_METHOD(Tensor_Reductions_Rref, __construct)
{
	zval *a, a_sub;
	zval *this_ptr = getThis();

	ZVAL_UNDEF(&a_sub);
#if PHP_VERSION_ID >= 80000
	bool is_null_true = 1;
	ZEND_PARSE_PARAMETERS_START(1, 1)
		Z_PARAM_OBJECT_OF_CLASS(a, zephir_get_internal_ce(SL("tensor\\matrix")))
	ZEND_PARSE_PARAMETERS_END();
#endif


	zephir_fetch_params_without_memory_grow(1, 0, &a);


	zephir_update_property_zval(this_ptr, ZEND_STRL("a"), a);
}

/**
 * Return the reduced matrix in reduced row echelon form.
 *
 * @return \Tensor\Matrix
 */
PHP_METHOD(Tensor_Reductions_Rref, a)
{
	zval *this_ptr = getThis();



	RETURN_MEMBER(getThis(), "a");
}

