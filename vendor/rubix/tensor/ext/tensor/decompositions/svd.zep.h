
extern zend_class_entry *tensor_decompositions_svd_ce;

ZEPHIR_INIT_CLASS(Tensor_Decompositions_Svd);

PHP_METHOD(Tensor_Decompositions_Svd, decompose);
PHP_METHOD(Tensor_Decompositions_Svd, __construct);
PHP_METHOD(Tensor_Decompositions_Svd, u);
PHP_METHOD(Tensor_Decompositions_Svd, singularValues);
PHP_METHOD(Tensor_Decompositions_Svd, s);
PHP_METHOD(Tensor_Decompositions_Svd, v);
PHP_METHOD(Tensor_Decompositions_Svd, vT);

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_svd_decompose, 0, 1, Tensor\\Decompositions\\Svd, 0)
	ZEND_ARG_OBJ_INFO(0, a, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_decompositions_svd___construct, 0, 0, 3)
	ZEND_ARG_OBJ_INFO(0, u, Tensor\\Matrix, 0)
	ZEND_ARG_ARRAY_INFO(0, singularValues, 0)
	ZEND_ARG_OBJ_INFO(0, vT, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_svd_u, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_decompositions_svd_singularvalues, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_svd_s, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_svd_v, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_svd_vt, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_decompositions_svd_method_entry) {
	PHP_ME(Tensor_Decompositions_Svd, decompose, arginfo_tensor_decompositions_svd_decompose, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Decompositions_Svd, __construct, arginfo_tensor_decompositions_svd___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(Tensor_Decompositions_Svd, u, arginfo_tensor_decompositions_svd_u, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Decompositions_Svd, singularValues, arginfo_tensor_decompositions_svd_singularvalues, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Decompositions_Svd, s, arginfo_tensor_decompositions_svd_s, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Decompositions_Svd, v, arginfo_tensor_decompositions_svd_v, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Decompositions_Svd, vT, arginfo_tensor_decompositions_svd_vt, ZEND_ACC_PUBLIC)
	PHP_FE_END
};
