
extern zend_class_entry *tensor_matrix_ce;

ZEPHIR_INIT_CLASS(Tensor_Matrix);

PHP_METHOD(Tensor_Matrix, build);
PHP_METHOD(Tensor_Matrix, quick);
PHP_METHOD(Tensor_Matrix, identity);
PHP_METHOD(Tensor_Matrix, zeros);
PHP_METHOD(Tensor_Matrix, ones);
PHP_METHOD(Tensor_Matrix, diagonal);
PHP_METHOD(Tensor_Matrix, fill);
PHP_METHOD(Tensor_Matrix, rand);
PHP_METHOD(Tensor_Matrix, gaussian);
PHP_METHOD(Tensor_Matrix, poisson);
PHP_METHOD(Tensor_Matrix, uniform);
PHP_METHOD(Tensor_Matrix, __construct);
PHP_METHOD(Tensor_Matrix, shape);
PHP_METHOD(Tensor_Matrix, shapeString);
PHP_METHOD(Tensor_Matrix, isSquare);
PHP_METHOD(Tensor_Matrix, size);
PHP_METHOD(Tensor_Matrix, m);
PHP_METHOD(Tensor_Matrix, n);
PHP_METHOD(Tensor_Matrix, rowAsVector);
PHP_METHOD(Tensor_Matrix, columnAsVector);
PHP_METHOD(Tensor_Matrix, diagonalAsVector);
PHP_METHOD(Tensor_Matrix, asArray);
PHP_METHOD(Tensor_Matrix, asVectors);
PHP_METHOD(Tensor_Matrix, asColumnVectors);
PHP_METHOD(Tensor_Matrix, flatten);
PHP_METHOD(Tensor_Matrix, map);
PHP_METHOD(Tensor_Matrix, reduce);
PHP_METHOD(Tensor_Matrix, transpose);
PHP_METHOD(Tensor_Matrix, inverse);
PHP_METHOD(Tensor_Matrix, pseudoinverse);
PHP_METHOD(Tensor_Matrix, det);
PHP_METHOD(Tensor_Matrix, trace);
PHP_METHOD(Tensor_Matrix, rank);
PHP_METHOD(Tensor_Matrix, fullRank);
PHP_METHOD(Tensor_Matrix, symmetric);
PHP_METHOD(Tensor_Matrix, matmul);
PHP_METHOD(Tensor_Matrix, dot);
PHP_METHOD(Tensor_Matrix, convolve);
PHP_METHOD(Tensor_Matrix, ref);
PHP_METHOD(Tensor_Matrix, rref);
PHP_METHOD(Tensor_Matrix, lu);
PHP_METHOD(Tensor_Matrix, cholesky);
PHP_METHOD(Tensor_Matrix, eig);
PHP_METHOD(Tensor_Matrix, svd);
PHP_METHOD(Tensor_Matrix, l1Norm);
PHP_METHOD(Tensor_Matrix, l2Norm);
PHP_METHOD(Tensor_Matrix, infinityNorm);
PHP_METHOD(Tensor_Matrix, maxNorm);
PHP_METHOD(Tensor_Matrix, multiply);
PHP_METHOD(Tensor_Matrix, divide);
PHP_METHOD(Tensor_Matrix, add);
PHP_METHOD(Tensor_Matrix, subtract);
PHP_METHOD(Tensor_Matrix, pow);
PHP_METHOD(Tensor_Matrix, mod);
PHP_METHOD(Tensor_Matrix, equal);
PHP_METHOD(Tensor_Matrix, notEqual);
PHP_METHOD(Tensor_Matrix, greater);
PHP_METHOD(Tensor_Matrix, greaterEqual);
PHP_METHOD(Tensor_Matrix, less);
PHP_METHOD(Tensor_Matrix, lessEqual);
PHP_METHOD(Tensor_Matrix, reciprocal);
PHP_METHOD(Tensor_Matrix, abs);
PHP_METHOD(Tensor_Matrix, square);
PHP_METHOD(Tensor_Matrix, sqrt);
PHP_METHOD(Tensor_Matrix, exp);
PHP_METHOD(Tensor_Matrix, expm1);
PHP_METHOD(Tensor_Matrix, log);
PHP_METHOD(Tensor_Matrix, log1p);
PHP_METHOD(Tensor_Matrix, sin);
PHP_METHOD(Tensor_Matrix, asin);
PHP_METHOD(Tensor_Matrix, cos);
PHP_METHOD(Tensor_Matrix, acos);
PHP_METHOD(Tensor_Matrix, tan);
PHP_METHOD(Tensor_Matrix, atan);
PHP_METHOD(Tensor_Matrix, rad2deg);
PHP_METHOD(Tensor_Matrix, deg2rad);
PHP_METHOD(Tensor_Matrix, sum);
PHP_METHOD(Tensor_Matrix, product);
PHP_METHOD(Tensor_Matrix, min);
PHP_METHOD(Tensor_Matrix, max);
PHP_METHOD(Tensor_Matrix, mean);
PHP_METHOD(Tensor_Matrix, median);
PHP_METHOD(Tensor_Matrix, quantile);
PHP_METHOD(Tensor_Matrix, variance);
PHP_METHOD(Tensor_Matrix, covariance);
PHP_METHOD(Tensor_Matrix, round);
PHP_METHOD(Tensor_Matrix, floor);
PHP_METHOD(Tensor_Matrix, ceil);
PHP_METHOD(Tensor_Matrix, clip);
PHP_METHOD(Tensor_Matrix, clipLower);
PHP_METHOD(Tensor_Matrix, clipUpper);
PHP_METHOD(Tensor_Matrix, sign);
PHP_METHOD(Tensor_Matrix, negate);
PHP_METHOD(Tensor_Matrix, augmentAbove);
PHP_METHOD(Tensor_Matrix, augmentBelow);
PHP_METHOD(Tensor_Matrix, augmentLeft);
PHP_METHOD(Tensor_Matrix, augmentRight);
PHP_METHOD(Tensor_Matrix, repeat);
PHP_METHOD(Tensor_Matrix, multiplyMatrix);
PHP_METHOD(Tensor_Matrix, divideMatrix);
PHP_METHOD(Tensor_Matrix, addMatrix);
PHP_METHOD(Tensor_Matrix, subtractMatrix);
PHP_METHOD(Tensor_Matrix, powMatrix);
PHP_METHOD(Tensor_Matrix, modMatrix);
PHP_METHOD(Tensor_Matrix, equalMatrix);
PHP_METHOD(Tensor_Matrix, notEqualMatrix);
PHP_METHOD(Tensor_Matrix, greaterMatrix);
PHP_METHOD(Tensor_Matrix, greaterEqualMatrix);
PHP_METHOD(Tensor_Matrix, lessMatrix);
PHP_METHOD(Tensor_Matrix, lessEqualMatrix);
PHP_METHOD(Tensor_Matrix, multiplyVector);
PHP_METHOD(Tensor_Matrix, divideVector);
PHP_METHOD(Tensor_Matrix, addVector);
PHP_METHOD(Tensor_Matrix, subtractVector);
PHP_METHOD(Tensor_Matrix, powVector);
PHP_METHOD(Tensor_Matrix, modVector);
PHP_METHOD(Tensor_Matrix, equalVector);
PHP_METHOD(Tensor_Matrix, notEqualVector);
PHP_METHOD(Tensor_Matrix, greaterVector);
PHP_METHOD(Tensor_Matrix, greaterEqualVector);
PHP_METHOD(Tensor_Matrix, lessVector);
PHP_METHOD(Tensor_Matrix, lessEqualVector);
PHP_METHOD(Tensor_Matrix, multiplyColumnVector);
PHP_METHOD(Tensor_Matrix, divideColumnVector);
PHP_METHOD(Tensor_Matrix, addColumnVector);
PHP_METHOD(Tensor_Matrix, subtractColumnVector);
PHP_METHOD(Tensor_Matrix, powColumnVector);
PHP_METHOD(Tensor_Matrix, modColumnVector);
PHP_METHOD(Tensor_Matrix, equalColumnVector);
PHP_METHOD(Tensor_Matrix, notEqualColumnVector);
PHP_METHOD(Tensor_Matrix, greaterColumnVector);
PHP_METHOD(Tensor_Matrix, greaterEqualColumnVector);
PHP_METHOD(Tensor_Matrix, lessColumnVector);
PHP_METHOD(Tensor_Matrix, lessEqualColumnVector);
PHP_METHOD(Tensor_Matrix, multiplyScalar);
PHP_METHOD(Tensor_Matrix, divideScalar);
PHP_METHOD(Tensor_Matrix, addScalar);
PHP_METHOD(Tensor_Matrix, subtractScalar);
PHP_METHOD(Tensor_Matrix, powScalar);
PHP_METHOD(Tensor_Matrix, modScalar);
PHP_METHOD(Tensor_Matrix, equalScalar);
PHP_METHOD(Tensor_Matrix, notEqualScalar);
PHP_METHOD(Tensor_Matrix, greaterScalar);
PHP_METHOD(Tensor_Matrix, greaterEqualScalar);
PHP_METHOD(Tensor_Matrix, lessScalar);
PHP_METHOD(Tensor_Matrix, lessEqualScalar);
PHP_METHOD(Tensor_Matrix, count);
PHP_METHOD(Tensor_Matrix, offsetSet);
PHP_METHOD(Tensor_Matrix, offsetExists);
PHP_METHOD(Tensor_Matrix, offsetUnset);
PHP_METHOD(Tensor_Matrix, offsetGet);
PHP_METHOD(Tensor_Matrix, getIterator);

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_build, 0, 0, Tensor\\Matrix, 0)
#if PHP_VERSION_ID >= 80000
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, a, IS_ARRAY, 0, "[]")
#else
	ZEND_ARG_ARRAY_INFO(0, a, 0)
#endif
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_quick, 0, 0, Tensor\\Matrix, 0)
#if PHP_VERSION_ID >= 80000
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, a, IS_ARRAY, 0, "[]")
#else
	ZEND_ARG_ARRAY_INFO(0, a, 0)
#endif
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_identity, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_zeros, 0, 2, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, m, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_ones, 0, 2, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, m, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_diagonal, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_ARRAY_INFO(0, elements, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_fill, 0, 3, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, m, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_rand, 0, 2, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, m, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_gaussian, 0, 2, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, m, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_poisson, 0, 2, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, m, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, lambda, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_uniform, 0, 2, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, m, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix___construct, 0, 0, 1)
	ZEND_ARG_ARRAY_INFO(0, a, 0)
	ZEND_ARG_TYPE_INFO(0, validate, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_shape, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_shapestring, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_issquare, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_size, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_m, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_n, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_rowasvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, index, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_columnasvector, 0, 1, Tensor\\ColumnVector, 0)
	ZEND_ARG_TYPE_INFO(0, index, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_diagonalasvector, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_asarray, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_asvectors, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_ascolumnvectors, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_flatten, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_map, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_reduce, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_INFO(0, callback)
	ZEND_ARG_TYPE_INFO(0, initial, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_transpose, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_inverse, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_pseudoinverse, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_det, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_trace, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_rank, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_fullrank, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_symmetric, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_matmul, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_dot, 0, 1, Tensor\\ColumnVector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_convolve, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, stride, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_ref, 0, 0, Tensor\\Reductions\\Ref, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_rref, 0, 0, Tensor\\Reductions\\Rref, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_lu, 0, 0, Tensor\\Decompositions\\Lu, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_cholesky, 0, 0, Tensor\\Decompositions\\Cholesky, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_eig, 0, 0, Tensor\\Decompositions\\Eigen, 0)
	ZEND_ARG_TYPE_INFO(0, symmetric, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_svd, 0, 0, Tensor\\Decompositions\\Svd, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_l1norm, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_l2norm, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_infinitynorm, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_maxnorm, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_multiply, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_divide, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_add, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_subtract, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_pow, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_mod, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_equal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_notequal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_greater, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_greaterequal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_less, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_matrix_lessequal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_reciprocal, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_abs, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_square, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_sqrt, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_exp, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_expm1, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_log, 0, 0, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, base, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_log1p, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_sin, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_asin, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_cos, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_acos, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_tan, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_atan, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_rad2deg, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_deg2rad, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_sum, 0, 0, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_product, 0, 0, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_min, 0, 0, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_max, 0, 0, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_mean, 0, 0, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_median, 0, 0, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_quantile, 0, 1, Tensor\\ColumnVector, 0)
	ZEND_ARG_TYPE_INFO(0, q, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_variance, 0, 0, Tensor\\ColumnVector, 0)
	ZEND_ARG_INFO(0, mean)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_covariance, 0, 0, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, mean, Tensor\\ColumnVector, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_round, 0, 0, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, precision, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_floor, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_ceil, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_clip, 0, 2, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, min, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, max, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_cliplower, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, min, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_clipupper, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, max, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_sign, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_negate, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_augmentabove, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_augmentbelow, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_augmentleft, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_augmentright, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_repeat, 0, 2, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, m, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_multiplymatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_dividematrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_addmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_subtractmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_powmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_modmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_equalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_notequalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_greatermatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_greaterequalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_lessmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_lessequalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_multiplyvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_dividevector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_addvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_subtractvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_powvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_modvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_equalvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_notequalvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_greatervector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_greaterequalvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_lessvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_lessequalvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_multiplycolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_dividecolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_addcolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_subtractcolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_powcolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_modcolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_equalcolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_notequalcolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_greatercolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_greaterequalcolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_lesscolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_lessequalcolumnvector, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\ColumnVector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_multiplyscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_dividescalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_addscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_subtractscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_powscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_modscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_equalscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_notequalscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_greaterscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_greaterequalscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_lessscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_lessequalscalar, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_count, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_offsetset, 0, 2, IS_VOID, 0)

	ZEND_ARG_INFO(0, index)
	ZEND_ARG_INFO(0, values)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_offsetexists, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_INFO(0, index)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_matrix_offsetunset, 0, 1, IS_VOID, 0)

	ZEND_ARG_INFO(0, index)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_offsetget, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_INFO(0, index)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_matrix_getiterator, 0, 0, Traversable, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_matrix_method_entry) {
	PHP_ME(Tensor_Matrix, build, arginfo_tensor_matrix_build, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, quick, arginfo_tensor_matrix_quick, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, identity, arginfo_tensor_matrix_identity, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, zeros, arginfo_tensor_matrix_zeros, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, ones, arginfo_tensor_matrix_ones, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, diagonal, arginfo_tensor_matrix_diagonal, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, fill, arginfo_tensor_matrix_fill, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, rand, arginfo_tensor_matrix_rand, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, gaussian, arginfo_tensor_matrix_gaussian, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, poisson, arginfo_tensor_matrix_poisson, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, uniform, arginfo_tensor_matrix_uniform, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Matrix, __construct, arginfo_tensor_matrix___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(Tensor_Matrix, shape, arginfo_tensor_matrix_shape, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, shapeString, arginfo_tensor_matrix_shapestring, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, isSquare, arginfo_tensor_matrix_issquare, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, size, arginfo_tensor_matrix_size, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, m, arginfo_tensor_matrix_m, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, n, arginfo_tensor_matrix_n, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, rowAsVector, arginfo_tensor_matrix_rowasvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, columnAsVector, arginfo_tensor_matrix_columnasvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, diagonalAsVector, arginfo_tensor_matrix_diagonalasvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, asArray, arginfo_tensor_matrix_asarray, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, asVectors, arginfo_tensor_matrix_asvectors, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, asColumnVectors, arginfo_tensor_matrix_ascolumnvectors, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, flatten, arginfo_tensor_matrix_flatten, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, map, arginfo_tensor_matrix_map, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, reduce, arginfo_tensor_matrix_reduce, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, transpose, arginfo_tensor_matrix_transpose, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, inverse, arginfo_tensor_matrix_inverse, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, pseudoinverse, arginfo_tensor_matrix_pseudoinverse, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, det, arginfo_tensor_matrix_det, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, trace, arginfo_tensor_matrix_trace, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, rank, arginfo_tensor_matrix_rank, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, fullRank, arginfo_tensor_matrix_fullrank, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, symmetric, arginfo_tensor_matrix_symmetric, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, matmul, arginfo_tensor_matrix_matmul, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, dot, arginfo_tensor_matrix_dot, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, convolve, arginfo_tensor_matrix_convolve, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, ref, arginfo_tensor_matrix_ref, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, rref, arginfo_tensor_matrix_rref, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lu, arginfo_tensor_matrix_lu, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, cholesky, arginfo_tensor_matrix_cholesky, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, eig, arginfo_tensor_matrix_eig, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, svd, arginfo_tensor_matrix_svd, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, l1Norm, arginfo_tensor_matrix_l1norm, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, l2Norm, arginfo_tensor_matrix_l2norm, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, infinityNorm, arginfo_tensor_matrix_infinitynorm, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, maxNorm, arginfo_tensor_matrix_maxnorm, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, multiply, arginfo_tensor_matrix_multiply, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, divide, arginfo_tensor_matrix_divide, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, add, arginfo_tensor_matrix_add, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, subtract, arginfo_tensor_matrix_subtract, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, pow, arginfo_tensor_matrix_pow, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, mod, arginfo_tensor_matrix_mod, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, equal, arginfo_tensor_matrix_equal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, notEqual, arginfo_tensor_matrix_notequal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greater, arginfo_tensor_matrix_greater, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greaterEqual, arginfo_tensor_matrix_greaterequal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, less, arginfo_tensor_matrix_less, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lessEqual, arginfo_tensor_matrix_lessequal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, reciprocal, arginfo_tensor_matrix_reciprocal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, abs, arginfo_tensor_matrix_abs, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, square, arginfo_tensor_matrix_square, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, sqrt, arginfo_tensor_matrix_sqrt, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, exp, arginfo_tensor_matrix_exp, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, expm1, arginfo_tensor_matrix_expm1, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, log, arginfo_tensor_matrix_log, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, log1p, arginfo_tensor_matrix_log1p, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, sin, arginfo_tensor_matrix_sin, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, asin, arginfo_tensor_matrix_asin, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, cos, arginfo_tensor_matrix_cos, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, acos, arginfo_tensor_matrix_acos, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, tan, arginfo_tensor_matrix_tan, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, atan, arginfo_tensor_matrix_atan, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, rad2deg, arginfo_tensor_matrix_rad2deg, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, deg2rad, arginfo_tensor_matrix_deg2rad, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, sum, arginfo_tensor_matrix_sum, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, product, arginfo_tensor_matrix_product, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, min, arginfo_tensor_matrix_min, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, max, arginfo_tensor_matrix_max, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, mean, arginfo_tensor_matrix_mean, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, median, arginfo_tensor_matrix_median, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, quantile, arginfo_tensor_matrix_quantile, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, variance, arginfo_tensor_matrix_variance, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, covariance, arginfo_tensor_matrix_covariance, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, round, arginfo_tensor_matrix_round, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, floor, arginfo_tensor_matrix_floor, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, ceil, arginfo_tensor_matrix_ceil, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, clip, arginfo_tensor_matrix_clip, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, clipLower, arginfo_tensor_matrix_cliplower, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, clipUpper, arginfo_tensor_matrix_clipupper, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, sign, arginfo_tensor_matrix_sign, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, negate, arginfo_tensor_matrix_negate, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, augmentAbove, arginfo_tensor_matrix_augmentabove, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, augmentBelow, arginfo_tensor_matrix_augmentbelow, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, augmentLeft, arginfo_tensor_matrix_augmentleft, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, augmentRight, arginfo_tensor_matrix_augmentright, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, repeat, arginfo_tensor_matrix_repeat, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, multiplyMatrix, arginfo_tensor_matrix_multiplymatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, divideMatrix, arginfo_tensor_matrix_dividematrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, addMatrix, arginfo_tensor_matrix_addmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, subtractMatrix, arginfo_tensor_matrix_subtractmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, powMatrix, arginfo_tensor_matrix_powmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, modMatrix, arginfo_tensor_matrix_modmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, equalMatrix, arginfo_tensor_matrix_equalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, notEqualMatrix, arginfo_tensor_matrix_notequalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greaterMatrix, arginfo_tensor_matrix_greatermatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greaterEqualMatrix, arginfo_tensor_matrix_greaterequalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lessMatrix, arginfo_tensor_matrix_lessmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lessEqualMatrix, arginfo_tensor_matrix_lessequalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, multiplyVector, arginfo_tensor_matrix_multiplyvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, divideVector, arginfo_tensor_matrix_dividevector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, addVector, arginfo_tensor_matrix_addvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, subtractVector, arginfo_tensor_matrix_subtractvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, powVector, arginfo_tensor_matrix_powvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, modVector, arginfo_tensor_matrix_modvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, equalVector, arginfo_tensor_matrix_equalvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, notEqualVector, arginfo_tensor_matrix_notequalvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greaterVector, arginfo_tensor_matrix_greatervector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greaterEqualVector, arginfo_tensor_matrix_greaterequalvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lessVector, arginfo_tensor_matrix_lessvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lessEqualVector, arginfo_tensor_matrix_lessequalvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, multiplyColumnVector, arginfo_tensor_matrix_multiplycolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, divideColumnVector, arginfo_tensor_matrix_dividecolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, addColumnVector, arginfo_tensor_matrix_addcolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, subtractColumnVector, arginfo_tensor_matrix_subtractcolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, powColumnVector, arginfo_tensor_matrix_powcolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, modColumnVector, arginfo_tensor_matrix_modcolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, equalColumnVector, arginfo_tensor_matrix_equalcolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, notEqualColumnVector, arginfo_tensor_matrix_notequalcolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greaterColumnVector, arginfo_tensor_matrix_greatercolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greaterEqualColumnVector, arginfo_tensor_matrix_greaterequalcolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lessColumnVector, arginfo_tensor_matrix_lesscolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lessEqualColumnVector, arginfo_tensor_matrix_lessequalcolumnvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, multiplyScalar, arginfo_tensor_matrix_multiplyscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, divideScalar, arginfo_tensor_matrix_dividescalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, addScalar, arginfo_tensor_matrix_addscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, subtractScalar, arginfo_tensor_matrix_subtractscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, powScalar, arginfo_tensor_matrix_powscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, modScalar, arginfo_tensor_matrix_modscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, equalScalar, arginfo_tensor_matrix_equalscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, notEqualScalar, arginfo_tensor_matrix_notequalscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greaterScalar, arginfo_tensor_matrix_greaterscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, greaterEqualScalar, arginfo_tensor_matrix_greaterequalscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lessScalar, arginfo_tensor_matrix_lessscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, lessEqualScalar, arginfo_tensor_matrix_lessequalscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, count, arginfo_tensor_matrix_count, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, offsetSet, arginfo_tensor_matrix_offsetset, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, offsetExists, arginfo_tensor_matrix_offsetexists, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, offsetUnset, arginfo_tensor_matrix_offsetunset, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, offsetGet, arginfo_tensor_matrix_offsetget, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Matrix, getIterator, arginfo_tensor_matrix_getiterator, ZEND_ACC_PUBLIC)
	PHP_FE_END
};
