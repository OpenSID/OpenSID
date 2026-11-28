
extern zend_class_entry *tensor_trigonometric_ce;

ZEPHIR_INIT_CLASS(Tensor_Trigonometric);

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_trigonometric_sin, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_trigonometric_asin, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_trigonometric_cos, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_trigonometric_acos, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_trigonometric_tan, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_trigonometric_atan, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_trigonometric_rad2deg, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_trigonometric_deg2rad, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_trigonometric_method_entry) {
	PHP_ABSTRACT_ME(Tensor_Trigonometric, sin, arginfo_tensor_trigonometric_sin)
	PHP_ABSTRACT_ME(Tensor_Trigonometric, asin, arginfo_tensor_trigonometric_asin)
	PHP_ABSTRACT_ME(Tensor_Trigonometric, cos, arginfo_tensor_trigonometric_cos)
	PHP_ABSTRACT_ME(Tensor_Trigonometric, acos, arginfo_tensor_trigonometric_acos)
	PHP_ABSTRACT_ME(Tensor_Trigonometric, tan, arginfo_tensor_trigonometric_tan)
	PHP_ABSTRACT_ME(Tensor_Trigonometric, atan, arginfo_tensor_trigonometric_atan)
	PHP_ABSTRACT_ME(Tensor_Trigonometric, rad2deg, arginfo_tensor_trigonometric_rad2deg)
	PHP_ABSTRACT_ME(Tensor_Trigonometric, deg2rad, arginfo_tensor_trigonometric_deg2rad)
	PHP_FE_END
};
