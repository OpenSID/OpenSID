
extern zend_class_entry *tensor_arraylike_ce;

ZEPHIR_INIT_CLASS(Tensor_ArrayLike);

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_arraylike_shape, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_arraylike_shapestring, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_arraylike_size, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_arraylike_map, 0, 0, 1)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_arraylike_reduce, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_INFO(0, callback)
	ZEND_ARG_TYPE_INFO(0, initial, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_arraylike_asarray, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_arraylike_method_entry) {
	PHP_ABSTRACT_ME(Tensor_ArrayLike, shape, arginfo_tensor_arraylike_shape)
	PHP_ABSTRACT_ME(Tensor_ArrayLike, shapeString, arginfo_tensor_arraylike_shapestring)
	PHP_ABSTRACT_ME(Tensor_ArrayLike, size, arginfo_tensor_arraylike_size)
	PHP_ABSTRACT_ME(Tensor_ArrayLike, map, arginfo_tensor_arraylike_map)
	PHP_ABSTRACT_ME(Tensor_ArrayLike, reduce, arginfo_tensor_arraylike_reduce)
	PHP_ABSTRACT_ME(Tensor_ArrayLike, asArray, arginfo_tensor_arraylike_asarray)
	PHP_FE_END
};
