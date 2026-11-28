
extern zend_class_entry *tensor_decompositions_cholesky_ce;

ZEPHIR_INIT_CLASS(Tensor_Decompositions_Cholesky);

PHP_METHOD(Tensor_Decompositions_Cholesky, decompose);
PHP_METHOD(Tensor_Decompositions_Cholesky, __construct);
PHP_METHOD(Tensor_Decompositions_Cholesky, l);
PHP_METHOD(Tensor_Decompositions_Cholesky, lT);

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_cholesky_decompose, 0, 1, Tensor\\Decompositions\\Cholesky, 0)
	ZEND_ARG_OBJ_INFO(0, a, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_decompositions_cholesky___construct, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, l, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_cholesky_l, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_cholesky_lt, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_decompositions_cholesky_method_entry) {
	PHP_ME(Tensor_Decompositions_Cholesky, decompose, arginfo_tensor_decompositions_cholesky_decompose, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Decompositions_Cholesky, __construct, arginfo_tensor_decompositions_cholesky___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(Tensor_Decompositions_Cholesky, l, arginfo_tensor_decompositions_cholesky_l, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Decompositions_Cholesky, lT, arginfo_tensor_decompositions_cholesky_lt, ZEND_ACC_PUBLIC)
	PHP_FE_END
};
