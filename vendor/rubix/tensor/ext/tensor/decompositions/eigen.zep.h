
extern zend_class_entry *tensor_decompositions_eigen_ce;

ZEPHIR_INIT_CLASS(Tensor_Decompositions_Eigen);

PHP_METHOD(Tensor_Decompositions_Eigen, decompose);
PHP_METHOD(Tensor_Decompositions_Eigen, __construct);
PHP_METHOD(Tensor_Decompositions_Eigen, eigenvalues);
PHP_METHOD(Tensor_Decompositions_Eigen, eigenvectors);

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_eigen_decompose, 0, 1, Tensor\\Decompositions\\Eigen, 0)
	ZEND_ARG_OBJ_INFO(0, a, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, symmetric, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_decompositions_eigen___construct, 0, 0, 2)
	ZEND_ARG_ARRAY_INFO(0, eigenvalues, 0)
	ZEND_ARG_OBJ_INFO(0, eigenvectors, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_decompositions_eigen_eigenvalues, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_decompositions_eigen_eigenvectors, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_decompositions_eigen_method_entry) {
	PHP_ME(Tensor_Decompositions_Eigen, decompose, arginfo_tensor_decompositions_eigen_decompose, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Decompositions_Eigen, __construct, arginfo_tensor_decompositions_eigen___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(Tensor_Decompositions_Eigen, eigenvalues, arginfo_tensor_decompositions_eigen_eigenvalues, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Decompositions_Eigen, eigenvectors, arginfo_tensor_decompositions_eigen_eigenvectors, ZEND_ACC_PUBLIC)
	PHP_FE_END
};
