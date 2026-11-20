#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>
#include <cblas.h>
#include "kernel/operators.h"

/**
 * Sets the number of threads to use when parallel processesing.
 * 
 * @param return_value
 * @param threads
 */
void tensor_set_num_threads(zval * return_value, zval * threads)
{
    int n = zephir_get_intval(threads);
    
    openblas_set_num_threads(n);

    RETURN_TRUE;
}

/**
 * Return the number of threads to use when parallel processesing.
 * 
 * @param return_value
 */
void tensor_get_num_threads(zval * return_value)
{
    long threads = openblas_get_num_threads();

    RETURN_LONG(threads);
}
