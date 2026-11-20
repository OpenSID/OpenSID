
extern zend_class_entry *tensor_decompositions_lu_ce;

ZEPHIR_INIT_CLASS(Tensor_Decompositions_Lu);

PHP_METHOD(Tensor_Decompositions_Lu, decompose);
PHP_METHOD(Tensor_Decompositions_Lu, __construct);
PHP_METHOD(Tensor_Decompositions_Lu, l);
PHP_METHOD(Tensor_Decompositions_Lu, u);
PHP_METHOD(Tensor_Decompositions_Lu, p);

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_lu_decompose, 0, 1, Tensor\\Decompositions\\Lu, 0)
	ZEND_ARG_OBJ_INFO(0, a, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_decompositions_lu___construct, 0, 0, 3)
	ZEND_ARG_OBJ_INFO(0, l, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, u, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, p, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_lu_l, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_lu_u, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_lu_p, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_decompositions_lu_method_entry) {
	PHP_ME(Tensor_Decompositions_Lu, decompose, arginfo_tensor_decompositions_lu_decompose, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Decompositions_Lu, __construct, arginfo_tensor_decompositions_lu___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(Tensor_Decompositions_Lu, l, arginfo_tensor_decompositions_lu_l, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Decompositions_Lu, u, arginfo_tensor_decompositions_lu_u, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Decompositions_Lu, p, arginfo_tensor_decompositions_lu_p, ZEND_ACC_PUBLIC)
	PHP_FE_END
};
