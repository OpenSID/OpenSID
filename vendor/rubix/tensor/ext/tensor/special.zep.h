
extern zend_class_entry *tensor_special_ce;

ZEPHIR_INIT_CLASS(Tensor_Special);

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_special_sum, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_special_product, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_special_min, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_special_max, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_special_clip, 0, 0, 2)
	ZEND_ARG_TYPE_INFO(0, min, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, max, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_special_cliplower, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, min, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_special_clipupper, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, max, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_special_method_entry) {
	PHP_ABSTRACT_ME(Tensor_Special, sum, arginfo_tensor_special_sum)
	PHP_ABSTRACT_ME(Tensor_Special, product, arginfo_tensor_special_product)
	PHP_ABSTRACT_ME(Tensor_Special, min, arginfo_tensor_special_min)
	PHP_ABSTRACT_ME(Tensor_Special, max, arginfo_tensor_special_max)
	PHP_ABSTRACT_ME(Tensor_Special, clip, arginfo_tensor_special_clip)
	PHP_ABSTRACT_ME(Tensor_Special, clipLower, arginfo_tensor_special_cliplower)
	PHP_ABSTRACT_ME(Tensor_Special, clipUpper, arginfo_tensor_special_clipupper)
	PHP_FE_END
};
