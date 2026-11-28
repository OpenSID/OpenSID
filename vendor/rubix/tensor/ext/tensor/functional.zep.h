
extern zend_class_entry *tensor_functional_ce;

ZEPHIR_INIT_CLASS(Tensor_Functional);

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_abs, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_square, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_sqrt, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_exp, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_expm1, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_log, 0, 0, 0)
	ZEND_ARG_TYPE_INFO(0, base, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_log1p, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_sum, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_product, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_min, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_max, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_round, 0, 0, 0)
	ZEND_ARG_TYPE_INFO(0, precision, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_floor, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_ceil, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_sign, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_negate, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_clip, 0, 0, 2)
	ZEND_ARG_TYPE_INFO(0, min, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, max, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_cliplower, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, min, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_functional_clipupper, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, max, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_functional_method_entry) {
	PHP_ABSTRACT_ME(Tensor_Functional, abs, arginfo_tensor_functional_abs)
	PHP_ABSTRACT_ME(Tensor_Functional, square, arginfo_tensor_functional_square)
	PHP_ABSTRACT_ME(Tensor_Functional, sqrt, arginfo_tensor_functional_sqrt)
	PHP_ABSTRACT_ME(Tensor_Functional, exp, arginfo_tensor_functional_exp)
	PHP_ABSTRACT_ME(Tensor_Functional, expm1, arginfo_tensor_functional_expm1)
	PHP_ABSTRACT_ME(Tensor_Functional, log, arginfo_tensor_functional_log)
	PHP_ABSTRACT_ME(Tensor_Functional, log1p, arginfo_tensor_functional_log1p)
	PHP_ABSTRACT_ME(Tensor_Functional, sum, arginfo_tensor_functional_sum)
	PHP_ABSTRACT_ME(Tensor_Functional, product, arginfo_tensor_functional_product)
	PHP_ABSTRACT_ME(Tensor_Functional, min, arginfo_tensor_functional_min)
	PHP_ABSTRACT_ME(Tensor_Functional, max, arginfo_tensor_functional_max)
	PHP_ABSTRACT_ME(Tensor_Functional, round, arginfo_tensor_functional_round)
	PHP_ABSTRACT_ME(Tensor_Functional, floor, arginfo_tensor_functional_floor)
	PHP_ABSTRACT_ME(Tensor_Functional, ceil, arginfo_tensor_functional_ceil)
	PHP_ABSTRACT_ME(Tensor_Functional, sign, arginfo_tensor_functional_sign)
	PHP_ABSTRACT_ME(Tensor_Functional, negate, arginfo_tensor_functional_negate)
	PHP_ABSTRACT_ME(Tensor_Functional, clip, arginfo_tensor_functional_clip)
	PHP_ABSTRACT_ME(Tensor_Functional, clipLower, arginfo_tensor_functional_cliplower)
	PHP_ABSTRACT_ME(Tensor_Functional, clipUpper, arginfo_tensor_functional_clipupper)
	PHP_FE_END
};
