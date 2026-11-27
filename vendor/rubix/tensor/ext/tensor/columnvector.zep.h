
extern zend_class_entry *tensor_columnvector_ce;

ZEPHIR_INIT_CLASS(Tensor_ColumnVector);

PHP_METHOD(Tensor_ColumnVector, build);
PHP_METHOD(Tensor_ColumnVector, quick);
PHP_METHOD(Tensor_ColumnVector, m);
PHP_METHOD(Tensor_ColumnVector, n);
PHP_METHOD(Tensor_ColumnVector, transpose);
PHP_METHOD(Tensor_ColumnVector, matmul);
PHP_METHOD(Tensor_ColumnVector, multiplyMatrix);
PHP_METHOD(Tensor_ColumnVector, divideMatrix);
PHP_METHOD(Tensor_ColumnVector, addMatrix);
PHP_METHOD(Tensor_ColumnVector, subtractMatrix);
PHP_METHOD(Tensor_ColumnVector, powMatrix);
PHP_METHOD(Tensor_ColumnVector, modMatrix);
PHP_METHOD(Tensor_ColumnVector, equalMatrix);
PHP_METHOD(Tensor_ColumnVector, notEqualMatrix);
PHP_METHOD(Tensor_ColumnVector, greaterMatrix);
PHP_METHOD(Tensor_ColumnVector, greaterEqualMatrix);
PHP_METHOD(Tensor_ColumnVector, lessMatrix);
PHP_METHOD(Tensor_ColumnVector, lessEqualMatrix);

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_columnvector_build, 0, 0, 0)
#if PHP_VERSION_ID >= 80000
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, a, IS_ARRAY, 0, "[]")
#else
	ZEND_ARG_ARRAY_INFO(0, a, 0)
#endif
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_columnvector_quick, 0, 0, 0)
#if PHP_VERSION_ID >= 80000
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, a, IS_ARRAY, 0, "[]")
#else
	ZEND_ARG_ARRAY_INFO(0, a, 0)
#endif
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_columnvector_m, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_columnvector_n, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_columnvector_transpose, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_matmul, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_multiplymatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_dividematrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_addmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_subtractmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_powmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_modmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_equalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_notequalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_greatermatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_greaterequalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_lessmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_columnvector_lessequalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_columnvector_method_entry) {
	PHP_ME(Tensor_ColumnVector, build, arginfo_tensor_columnvector_build, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_ColumnVector, quick, arginfo_tensor_columnvector_quick, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_ColumnVector, m, arginfo_tensor_columnvector_m, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, n, arginfo_tensor_columnvector_n, ZEND_ACC_PUBLIC)
#if PHP_VERSION_ID >= 80000
	PHP_ME(Tensor_ColumnVector, transpose, arginfo_tensor_columnvector_transpose, ZEND_ACC_PUBLIC)
#else
	PHP_ME(Tensor_ColumnVector, transpose, NULL, ZEND_ACC_PUBLIC)
#endif
	PHP_ME(Tensor_ColumnVector, matmul, arginfo_tensor_columnvector_matmul, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, multiplyMatrix, arginfo_tensor_columnvector_multiplymatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, divideMatrix, arginfo_tensor_columnvector_dividematrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, addMatrix, arginfo_tensor_columnvector_addmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, subtractMatrix, arginfo_tensor_columnvector_subtractmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, powMatrix, arginfo_tensor_columnvector_powmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, modMatrix, arginfo_tensor_columnvector_modmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, equalMatrix, arginfo_tensor_columnvector_equalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, notEqualMatrix, arginfo_tensor_columnvector_notequalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, greaterMatrix, arginfo_tensor_columnvector_greatermatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, greaterEqualMatrix, arginfo_tensor_columnvector_greaterequalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, lessMatrix, arginfo_tensor_columnvector_lessmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_ColumnVector, lessEqualMatrix, arginfo_tensor_columnvector_lessequalmatrix, ZEND_ACC_PUBLIC)
	PHP_FE_END
};
