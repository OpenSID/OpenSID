#ifndef TENSOR_COMPARISON_H
#define TENSOR_COMPARISON_H

#include <Zend/zend.h>

void tensor_equal(zval * return_value, zval * a, zval * b);
void tensor_not_equal(zval * return_value, zval * a, zval * b);
void tensor_greater(zval * return_value, zval * a, zval * b);
void tensor_greater_equal(zval * return_value, zval * a, zval * b);
void tensor_less(zval * return_value, zval * a, zval * b);
void tensor_less_equal(zval * return_value, zval * a, zval * b);

void tensor_equal_scalar(zval * return_value, zval * a, zval * b);
void tensor_not_equal_scalar(zval * return_value, zval * a, zval * b);
void tensor_greater_scalar(zval * return_value, zval * a, zval * b);
void tensor_greater_equal_scalar(zval * return_value, zval * a, zval * b);
void tensor_less_scalar(zval * return_value, zval * a, zval * b);
void tensor_less_equal_scalar(zval * return_value, zval * a, zval * b);

#endif