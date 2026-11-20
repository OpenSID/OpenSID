
extern zend_class_entry *tensor_vector_ce;

ZEPHIR_INIT_CLASS(Tensor_Vector);

PHP_METHOD(Tensor_Vector, build);
PHP_METHOD(Tensor_Vector, quick);
PHP_METHOD(Tensor_Vector, zeros);
PHP_METHOD(Tensor_Vector, ones);
PHP_METHOD(Tensor_Vector, fill);
PHP_METHOD(Tensor_Vector, rand);
PHP_METHOD(Tensor_Vector, gaussian);
PHP_METHOD(Tensor_Vector, poisson);
PHP_METHOD(Tensor_Vector, uniform);
PHP_METHOD(Tensor_Vector, range);
PHP_METHOD(Tensor_Vector, linspace);
PHP_METHOD(Tensor_Vector, __construct);
PHP_METHOD(Tensor_Vector, shape);
PHP_METHOD(Tensor_Vector, shapeString);
PHP_METHOD(Tensor_Vector, size);
PHP_METHOD(Tensor_Vector, m);
PHP_METHOD(Tensor_Vector, n);
PHP_METHOD(Tensor_Vector, asArray);
PHP_METHOD(Tensor_Vector, asRowMatrix);
PHP_METHOD(Tensor_Vector, asColumnMatrix);
PHP_METHOD(Tensor_Vector, reshape);
PHP_METHOD(Tensor_Vector, transpose);
PHP_METHOD(Tensor_Vector, map);
PHP_METHOD(Tensor_Vector, reduce);
PHP_METHOD(Tensor_Vector, dot);
PHP_METHOD(Tensor_Vector, convolve);
PHP_METHOD(Tensor_Vector, matmul);
PHP_METHOD(Tensor_Vector, inner);
PHP_METHOD(Tensor_Vector, outer);
PHP_METHOD(Tensor_Vector, l1Norm);
PHP_METHOD(Tensor_Vector, l2Norm);
PHP_METHOD(Tensor_Vector, pNorm);
PHP_METHOD(Tensor_Vector, maxNorm);
PHP_METHOD(Tensor_Vector, multiply);
PHP_METHOD(Tensor_Vector, divide);
PHP_METHOD(Tensor_Vector, add);
PHP_METHOD(Tensor_Vector, subtract);
PHP_METHOD(Tensor_Vector, pow);
PHP_METHOD(Tensor_Vector, mod);
PHP_METHOD(Tensor_Vector, equal);
PHP_METHOD(Tensor_Vector, notEqual);
PHP_METHOD(Tensor_Vector, greater);
PHP_METHOD(Tensor_Vector, greaterEqual);
PHP_METHOD(Tensor_Vector, less);
PHP_METHOD(Tensor_Vector, lessEqual);
PHP_METHOD(Tensor_Vector, reciprocal);
PHP_METHOD(Tensor_Vector, abs);
PHP_METHOD(Tensor_Vector, square);
PHP_METHOD(Tensor_Vector, sqrt);
PHP_METHOD(Tensor_Vector, exp);
PHP_METHOD(Tensor_Vector, expm1);
PHP_METHOD(Tensor_Vector, log);
PHP_METHOD(Tensor_Vector, log1p);
PHP_METHOD(Tensor_Vector, sin);
PHP_METHOD(Tensor_Vector, asin);
PHP_METHOD(Tensor_Vector, cos);
PHP_METHOD(Tensor_Vector, acos);
PHP_METHOD(Tensor_Vector, tan);
PHP_METHOD(Tensor_Vector, atan);
PHP_METHOD(Tensor_Vector, rad2deg);
PHP_METHOD(Tensor_Vector, deg2rad);
PHP_METHOD(Tensor_Vector, sum);
PHP_METHOD(Tensor_Vector, product);
PHP_METHOD(Tensor_Vector, min);
PHP_METHOD(Tensor_Vector, max);
PHP_METHOD(Tensor_Vector, mean);
PHP_METHOD(Tensor_Vector, median);
PHP_METHOD(Tensor_Vector, quantile);
PHP_METHOD(Tensor_Vector, variance);
PHP_METHOD(Tensor_Vector, round);
PHP_METHOD(Tensor_Vector, floor);
PHP_METHOD(Tensor_Vector, ceil);
PHP_METHOD(Tensor_Vector, clip);
PHP_METHOD(Tensor_Vector, clipLower);
PHP_METHOD(Tensor_Vector, clipUpper);
PHP_METHOD(Tensor_Vector, sign);
PHP_METHOD(Tensor_Vector, negate);
PHP_METHOD(Tensor_Vector, multiplyMatrix);
PHP_METHOD(Tensor_Vector, divideMatrix);
PHP_METHOD(Tensor_Vector, addMatrix);
PHP_METHOD(Tensor_Vector, subtractMatrix);
PHP_METHOD(Tensor_Vector, powMatrix);
PHP_METHOD(Tensor_Vector, modMatrix);
PHP_METHOD(Tensor_Vector, equalMatrix);
PHP_METHOD(Tensor_Vector, notEqualMatrix);
PHP_METHOD(Tensor_Vector, greaterMatrix);
PHP_METHOD(Tensor_Vector, greaterEqualMatrix);
PHP_METHOD(Tensor_Vector, lessMatrix);
PHP_METHOD(Tensor_Vector, lessEqualMatrix);
PHP_METHOD(Tensor_Vector, multiplyVector);
PHP_METHOD(Tensor_Vector, divideVector);
PHP_METHOD(Tensor_Vector, addVector);
PHP_METHOD(Tensor_Vector, subtractVector);
PHP_METHOD(Tensor_Vector, powVector);
PHP_METHOD(Tensor_Vector, modVector);
PHP_METHOD(Tensor_Vector, equalVector);
PHP_METHOD(Tensor_Vector, notEqualVector);
PHP_METHOD(Tensor_Vector, greaterVector);
PHP_METHOD(Tensor_Vector, greaterEqualVector);
PHP_METHOD(Tensor_Vector, lessVector);
PHP_METHOD(Tensor_Vector, lessEqualVector);
PHP_METHOD(Tensor_Vector, multiplyScalar);
PHP_METHOD(Tensor_Vector, divideScalar);
PHP_METHOD(Tensor_Vector, addScalar);
PHP_METHOD(Tensor_Vector, subtractScalar);
PHP_METHOD(Tensor_Vector, powScalar);
PHP_METHOD(Tensor_Vector, modScalar);
PHP_METHOD(Tensor_Vector, equalScalar);
PHP_METHOD(Tensor_Vector, notEqualScalar);
PHP_METHOD(Tensor_Vector, greaterScalar);
PHP_METHOD(Tensor_Vector, greaterEqualScalar);
PHP_METHOD(Tensor_Vector, lessScalar);
PHP_METHOD(Tensor_Vector, lessEqualScalar);
PHP_METHOD(Tensor_Vector, count);
PHP_METHOD(Tensor_Vector, offsetSet);
PHP_METHOD(Tensor_Vector, offsetExists);
PHP_METHOD(Tensor_Vector, offsetUnset);
PHP_METHOD(Tensor_Vector, offsetGet);
PHP_METHOD(Tensor_Vector, getIterator);

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_build, 0, 0, 0)
#if PHP_VERSION_ID >= 80000
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, a, IS_ARRAY, 0, "[]")
#else
	ZEND_ARG_ARRAY_INFO(0, a, 0)
#endif
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_quick, 0, 0, 0)
#if PHP_VERSION_ID >= 80000
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, a, IS_ARRAY, 0, "[]")
#else
	ZEND_ARG_ARRAY_INFO(0, a, 0)
#endif
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_zeros, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_ones, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_fill, 0, 2, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_rand, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_gaussian, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_poisson, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, lambda, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_uniform, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_range, 0, 2, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, start, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, end, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, interval, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_linspace, 0, 3, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, min, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, max, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector___construct, 0, 0, 1)
	ZEND_ARG_ARRAY_INFO(0, a, 0)
	ZEND_ARG_TYPE_INFO(0, validate, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_shape, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_shapestring, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_size, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_m, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_n, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_asarray, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_asrowmatrix, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_ascolumnmatrix, 0, 0, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_reshape, 0, 2, Tensor\\Matrix, 0)
	ZEND_ARG_TYPE_INFO(0, m, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, n, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_transpose, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_map, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_reduce, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_INFO(0, callback)
	ZEND_ARG_TYPE_INFO(0, initial, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_dot, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_convolve, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, stride, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_matmul, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_inner, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_outer, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_l1norm, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_l2norm, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_pnorm, 0, 0, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, p, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_maxnorm, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_multiply, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_divide, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_add, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_subtract, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_pow, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_mod, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_equal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_notequal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_greater, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_greaterequal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_less, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_lessequal, 0, 0, 1)
	ZEND_ARG_INFO(0, b)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_reciprocal, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_abs, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_square, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_sqrt, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_exp, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_expm1, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_log, 0, 0, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, base, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_log1p, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_sin, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_asin, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_cos, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_acos, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_tan, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_atan, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_rad2deg, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_deg2rad, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_sum, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_product, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_min, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_max, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_mean, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_median, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_quantile, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, q, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_variance, 0, 0, IS_DOUBLE, 0)
	ZEND_ARG_INFO(0, mean)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_round, 0, 0, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, precision, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_floor, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_ceil, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_clip, 0, 2, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, min, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, max, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_cliplower, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, min, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_clipupper, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, max, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_sign, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_negate, 0, 0, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_multiplymatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_dividematrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_addmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_subtractmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_powmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_modmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_equalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_notequalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_greatermatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_greaterequalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_lessmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_lessequalmatrix, 0, 1, Tensor\\Matrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Matrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_multiplyvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_dividevector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_addvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_subtractvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_powvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_modvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_equalvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_notequalvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_greatervector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_greaterequalvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_lessvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_lessequalvector, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_OBJ_INFO(0, b, Tensor\\Vector, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_multiplyscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_dividescalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_addscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_subtractscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_powscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_modscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_equalscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_notequalscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_greaterscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_greaterequalscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_lessscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_lessequalscalar, 0, 1, Tensor\\Vector, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_count, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_offsetset, 0, 2, IS_VOID, 0)

	ZEND_ARG_INFO(0, index)
	ZEND_ARG_INFO(0, values)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_offsetexists, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_INFO(0, index)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_offsetunset, 0, 1, IS_VOID, 0)

	ZEND_ARG_INFO(0, index)
ZEND_END_ARG_INFO()

#if PHP_VERSION_ID >= 80000
ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_tensor_vector_offsetget, 0, 1, IS_MIXED, 0)
#else
ZEND_BEGIN_ARG_INFO_EX(arginfo_tensor_vector_offsetget, 0, 0, 1)
#endif
	ZEND_ARG_INFO(0, index)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_tensor_vector_getiterator, 0, 0, Traversable, 0)
ZEND_END_ARG_INFO()

ZEPHIR_INIT_FUNCS(tensor_vector_method_entry) {
	PHP_ME(Tensor_Vector, build, arginfo_tensor_vector_build, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, quick, arginfo_tensor_vector_quick, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, zeros, arginfo_tensor_vector_zeros, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, ones, arginfo_tensor_vector_ones, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, fill, arginfo_tensor_vector_fill, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, rand, arginfo_tensor_vector_rand, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, gaussian, arginfo_tensor_vector_gaussian, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, poisson, arginfo_tensor_vector_poisson, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, uniform, arginfo_tensor_vector_uniform, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, range, arginfo_tensor_vector_range, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, linspace, arginfo_tensor_vector_linspace, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	PHP_ME(Tensor_Vector, __construct, arginfo_tensor_vector___construct, ZEND_ACC_PUBLIC|ZEND_ACC_CTOR)
	PHP_ME(Tensor_Vector, shape, arginfo_tensor_vector_shape, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, shapeString, arginfo_tensor_vector_shapestring, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, size, arginfo_tensor_vector_size, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, m, arginfo_tensor_vector_m, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, n, arginfo_tensor_vector_n, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, asArray, arginfo_tensor_vector_asarray, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, asRowMatrix, arginfo_tensor_vector_asrowmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, asColumnMatrix, arginfo_tensor_vector_ascolumnmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, reshape, arginfo_tensor_vector_reshape, ZEND_ACC_PUBLIC)
#if PHP_VERSION_ID >= 80000
	PHP_ME(Tensor_Vector, transpose, arginfo_tensor_vector_transpose, ZEND_ACC_PUBLIC)
#else
	PHP_ME(Tensor_Vector, transpose, NULL, ZEND_ACC_PUBLIC)
#endif
	PHP_ME(Tensor_Vector, map, arginfo_tensor_vector_map, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, reduce, arginfo_tensor_vector_reduce, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, dot, arginfo_tensor_vector_dot, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, convolve, arginfo_tensor_vector_convolve, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, matmul, arginfo_tensor_vector_matmul, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, inner, arginfo_tensor_vector_inner, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, outer, arginfo_tensor_vector_outer, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, l1Norm, arginfo_tensor_vector_l1norm, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, l2Norm, arginfo_tensor_vector_l2norm, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, pNorm, arginfo_tensor_vector_pnorm, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, maxNorm, arginfo_tensor_vector_maxnorm, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, multiply, arginfo_tensor_vector_multiply, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, divide, arginfo_tensor_vector_divide, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, add, arginfo_tensor_vector_add, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, subtract, arginfo_tensor_vector_subtract, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, pow, arginfo_tensor_vector_pow, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, mod, arginfo_tensor_vector_mod, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, equal, arginfo_tensor_vector_equal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, notEqual, arginfo_tensor_vector_notequal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, greater, arginfo_tensor_vector_greater, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, greaterEqual, arginfo_tensor_vector_greaterequal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, less, arginfo_tensor_vector_less, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, lessEqual, arginfo_tensor_vector_lessequal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, reciprocal, arginfo_tensor_vector_reciprocal, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, abs, arginfo_tensor_vector_abs, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, square, arginfo_tensor_vector_square, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, sqrt, arginfo_tensor_vector_sqrt, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, exp, arginfo_tensor_vector_exp, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, expm1, arginfo_tensor_vector_expm1, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, log, arginfo_tensor_vector_log, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, log1p, arginfo_tensor_vector_log1p, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, sin, arginfo_tensor_vector_sin, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, asin, arginfo_tensor_vector_asin, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, cos, arginfo_tensor_vector_cos, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, acos, arginfo_tensor_vector_acos, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, tan, arginfo_tensor_vector_tan, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, atan, arginfo_tensor_vector_atan, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, rad2deg, arginfo_tensor_vector_rad2deg, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, deg2rad, arginfo_tensor_vector_deg2rad, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, sum, arginfo_tensor_vector_sum, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, product, arginfo_tensor_vector_product, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, min, arginfo_tensor_vector_min, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, max, arginfo_tensor_vector_max, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, mean, arginfo_tensor_vector_mean, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, median, arginfo_tensor_vector_median, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, quantile, arginfo_tensor_vector_quantile, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, variance, arginfo_tensor_vector_variance, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, round, arginfo_tensor_vector_round, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, floor, arginfo_tensor_vector_floor, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, ceil, arginfo_tensor_vector_ceil, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, clip, arginfo_tensor_vector_clip, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, clipLower, arginfo_tensor_vector_cliplower, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, clipUpper, arginfo_tensor_vector_clipupper, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, sign, arginfo_tensor_vector_sign, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, negate, arginfo_tensor_vector_negate, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, multiplyMatrix, arginfo_tensor_vector_multiplymatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, divideMatrix, arginfo_tensor_vector_dividematrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, addMatrix, arginfo_tensor_vector_addmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, subtractMatrix, arginfo_tensor_vector_subtractmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, powMatrix, arginfo_tensor_vector_powmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, modMatrix, arginfo_tensor_vector_modmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, equalMatrix, arginfo_tensor_vector_equalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, notEqualMatrix, arginfo_tensor_vector_notequalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, greaterMatrix, arginfo_tensor_vector_greatermatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, greaterEqualMatrix, arginfo_tensor_vector_greaterequalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, lessMatrix, arginfo_tensor_vector_lessmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, lessEqualMatrix, arginfo_tensor_vector_lessequalmatrix, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, multiplyVector, arginfo_tensor_vector_multiplyvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, divideVector, arginfo_tensor_vector_dividevector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, addVector, arginfo_tensor_vector_addvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, subtractVector, arginfo_tensor_vector_subtractvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, powVector, arginfo_tensor_vector_powvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, modVector, arginfo_tensor_vector_modvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, equalVector, arginfo_tensor_vector_equalvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, notEqualVector, arginfo_tensor_vector_notequalvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, greaterVector, arginfo_tensor_vector_greatervector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, greaterEqualVector, arginfo_tensor_vector_greaterequalvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, lessVector, arginfo_tensor_vector_lessvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, lessEqualVector, arginfo_tensor_vector_lessequalvector, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, multiplyScalar, arginfo_tensor_vector_multiplyscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, divideScalar, arginfo_tensor_vector_dividescalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, addScalar, arginfo_tensor_vector_addscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, subtractScalar, arginfo_tensor_vector_subtractscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, powScalar, arginfo_tensor_vector_powscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, modScalar, arginfo_tensor_vector_modscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, equalScalar, arginfo_tensor_vector_equalscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, notEqualScalar, arginfo_tensor_vector_notequalscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, greaterScalar, arginfo_tensor_vector_greaterscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, greaterEqualScalar, arginfo_tensor_vector_greaterequalscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, lessScalar, arginfo_tensor_vector_lessscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, lessEqualScalar, arginfo_tensor_vector_lessequalscalar, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, count, arginfo_tensor_vector_count, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, offsetSet, arginfo_tensor_vector_offsetset, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, offsetExists, arginfo_tensor_vector_offsetexists, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, offsetUnset, arginfo_tensor_vector_offsetunset, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, offsetGet, arginfo_tensor_vector_offsetget, ZEND_ACC_PUBLIC)
	PHP_ME(Tensor_Vector, getIterator, arginfo_tensor_vector_getiterator, ZEND_ACC_PUBLIC)
	PHP_FE_END
};
