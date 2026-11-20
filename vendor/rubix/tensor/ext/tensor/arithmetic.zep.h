
extern zend_class_entry *tensor_arithmetic_ce;

ZEPHIR_INIT_CLASS(Tensor_Arithmetic);

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_arithmetic_multiply, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_arithmetic_divide, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_arithmetic_add, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_arithmetic_subtract, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_arithmetic_pow, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_arithmetic_mod, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_arithmetic_method_entry) {
	PHP_ABSTRACT_ME(Tensor_Arithmetic, multiply, arginfo_tensor_arithmetic_multiply)
	PHP_ABSTRACT_ME(Tensor_Arithmetic, divide, arginfo_tensor_arithmetic_divide)
	PHP_ABSTRACT_ME(Tensor_Arithmetic, add, arginfo_tensor_arithmetic_add)
	PHP_ABSTRACT_ME(Tensor_Arithmetic, subtract, arginfo_tensor_arithmetic_subtract)
	PHP_ABSTRACT_ME(Tensor_Arithmetic, pow, arginfo_tensor_arithmetic_pow)
	PHP_ABSTRACT_ME(Tensor_Arithmetic, mod, arginfo_tensor_arithmetic_mod)
	PHP_FE_END
};
