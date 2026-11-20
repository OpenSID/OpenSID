
extern zend_class_entry *tensor_comparable_ce;

ZEPHIR_INIT_CLASS(Tensor_Comparable);

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_comparable_equal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_comparable_notequal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_comparable_greater, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_comparable_greaterequal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_comparable_less, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_comparable_lessequal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_comparable_method_entry) {
	PHP_ABSTRACT_ME(Tensor_Comparable, equal, arginfo_tensor_comparable_equal)
	PHP_ABSTRACT_ME(Tensor_Comparable, notEqual, arginfo_tensor_comparable_notequal)
	PHP_ABSTRACT_ME(Tensor_Comparable, greater, arginfo_tensor_comparable_greater)
	PHP_ABSTRACT_ME(Tensor_Comparable, greaterEqual, arginfo_tensor_comparable_greaterequal)
	PHP_ABSTRACT_ME(Tensor_Comparable, less, arginfo_tensor_comparable_less)
	PHP_ABSTRACT_ME(Tensor_Comparable, lessEqual, arginfo_tensor_comparable_lessequal)
	PHP_FE_END
};
