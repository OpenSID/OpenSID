#ifndef TENSOR_SETTINGS_H
#define TENSOR_SETTINGS_H

#include <Zend/zend.h>

void tensor_set_num_threads(zval * return_value, zval * threads);
void tensor_get_num_threads(zval * return_value);

#endif
