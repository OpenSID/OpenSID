#ifndef TENSOR_LINEAR_ALGEBRA_H
#define TENSOR_LINEAR_ALGEBRA_H

#include <Zend/zend.h>

void tensor_matmul(zval * return_value, zval * a, zval * b);
void tensor_dot(zval * return_value, zval * a, zval * b);

void tensor_inverse(zval * return_value, zval * a);
void tensor_pseudoinverse(zval * return_value, zval * a);

void tensor_ref(zval * return_value, zval * a);
void tensor_cholesky(zval * return_value, zval * a);
void tensor_lu(zval * return_value, zval * a);
void tensor_eig(zval * return_value, zval * a);
void tensor_eig_symmetric(zval * return_value, zval * a);
void tensor_svd(zval * return_value, zval * a);

#endif