
extern zend_class_entry *tensor_algebraic_ce;

ZEPHIR_INIT_CLASS(Tensor_Algebraic);

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_abs, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_square, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_sqrt, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_exp, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_expm1, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_log, 0, 0, 0)
	ZEND_ARG_TYPE_INFO(0, base, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_log1p, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_round, 0, 0, 0)
	ZEND_ARG_TYPE_INFO(0, precision, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_floor, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_ceil, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_sign, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_algebraic_negate, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_algebraic_method_entry) {
	PHP_ABSTRACT_ME(Tensor_Algebraic, abs, arginfo_tensor_algebraic_abs)
	PHP_ABSTRACT_ME(Tensor_Algebraic, square, arginfo_tensor_algebraic_square)
	PHP_ABSTRACT_ME(Tensor_Algebraic, sqrt, arginfo_tensor_algebraic_sqrt)
	PHP_ABSTRACT_ME(Tensor_Algebraic, exp, arginfo_tensor_algebraic_exp)
	PHP_ABSTRACT_ME(Tensor_Algebraic, expm1, arginfo_tensor_algebraic_expm1)
	PHP_ABSTRACT_ME(Tensor_Algebraic, log, arginfo_tensor_algebraic_log)
	PHP_ABSTRACT_ME(Tensor_Algebraic, log1p, arginfo_tensor_algebraic_log1p)
	PHP_ABSTRACT_ME(Tensor_Algebraic, round, arginfo_tensor_algebraic_round)
	PHP_ABSTRACT_ME(Tensor_Algebraic, floor, arginfo_tensor_algebraic_floor)
	PHP_ABSTRACT_ME(Tensor_Algebraic, ceil, arginfo_tensor_algebraic_ceil)
	PHP_ABSTRACT_ME(Tensor_Algebraic, sign, arginfo_tensor_algebraic_sign)
	PHP_ABSTRACT_ME(Tensor_Algebraic, negate, arginfo_tensor_algebraic_negate)
	PHP_FE_END
};
