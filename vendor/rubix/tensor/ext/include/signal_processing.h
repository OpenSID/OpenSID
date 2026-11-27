#ifndef TENSOR_SIGNAL_PROCESSING_H
#define TENSOR_SIGNAL_PROCESSING_H

#include <Zend/zend.h>

void tensor_convolve_1d(zval * return_value, zval * a, zval * b, zval * stride);
void tensor_convolve_2d(zval * return_value, zval * a, zval * b, zval * stride);

#endif
