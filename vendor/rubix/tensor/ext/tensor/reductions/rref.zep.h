
extern zend_class_entry *tensor_reductions_rref_ce;

ZEPHIR_INIT_CLASS(Tensor_Reductions_Rref);

PHP_METHOD(Tensor_Reductions_Rref, reduce);
PHP_METHOD(Tensor_Reductions_Rref, __construct);
PHP_METHOD(Tensor_Reductions_Rref, a);

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_reductions_rref_reduce, 0, 1, Tensor\\Reductions\\Rref, 0)
	ZEND_ARG_OBJ_INFO(0, a, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_reductions_rref___construct, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, a, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_reductions_rref_a, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_reductions_rref_method_entry) {
	PHP_ME(Tensor_Reductions_Rref, reduce, arginfo_tensor_reductions_rref_reduce, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Reductions_Rref, __construct, arginfo_tensor_reductions_rref___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(Tensor_Reductions_Rref, a, arginfo_tensor_reductions_rref_a, ZEND_ACC_PUBLIC)
	PHP_FE_END
};
