
extern zend_class_entry *tensor_reductions_ref_ce;

ZEPHIR_INIT_CLASS(Tensor_Reductions_Ref);

PHP_METHOD(Tensor_Reductions_Ref, reduce);
PHP_METHOD(Tensor_Reductions_Ref, __construct);
PHP_METHOD(Tensor_Reductions_Ref, a);
PHP_METHOD(Tensor_Reductions_Ref, swaps);

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_reductions_ref_reduce, 0, 1, Tensor\\Reductions\\Ref, 0)
	ZEND_ARG_OBJ_INFO(0, a, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_reductions_ref___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, a, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, swaps, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_reductions_ref_a, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_reductions_ref_swaps, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_reductions_ref_method_entry) {
	PHP_ME(Tensor_Reductions_Ref, reduce, arginfo_tensor_reductions_ref_reduce, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Reductions_Ref, __construct, arginfo_tensor_reductions_ref___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(Tensor_Reductions_Ref, a, arginfo_tensor_reductions_ref_a, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Reductions_Ref, swaps, arginfo_tensor_reductions_ref_swaps, ZEND_ACC_PUBLIC)
	PHP_FE_END
};
