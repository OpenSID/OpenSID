#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>
#include "kernel/operators.h"

void tensor_equal(zval * return_value, zval * a, zval * b)
{
    unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) == zephir_get_doubleval(zend_hash_index_find(ab, i))) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_not_equal(zval * return_value, zval * a, zval * b)
{
    unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) != zephir_get_doubleval(zend_hash_index_find(ab, i))) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_greater(zval * return_value, zval * a, zval * b)
{
    unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) > zephir_get_doubleval(zend_hash_index_find(ab, i))) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_greater_equal(zval * return_value, zval * a, zval * b)
{
    unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) >= zephir_get_doubleval(zend_hash_index_find(ab, i))) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_less(zval * return_value, zval * a, zval * b)
{
    unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) < zephir_get_doubleval(zend_hash_index_find(ab, i))) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_less_equal(zval * return_value, zval * a, zval * b)
{
    unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) <= zephir_get_doubleval(zend_hash_index_find(ab, i))) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_equal_scalar(zval * return_value, zval * a, zval * b)
{
	unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);

    double ab = zephir_get_doubleval(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) == ab) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_not_equal_scalar(zval * return_value, zval * a, zval * b)
{
	unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);

    double ab = zephir_get_doubleval(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) != ab) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_greater_scalar(zval * return_value, zval * a, zval * b)
{
	unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);

    double ab = zephir_get_doubleval(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) > ab) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_greater_equal_scalar(zval * return_value, zval * a, zval * b)
{
	unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);

    double ab = zephir_get_doubleval(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) >= ab) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_less_scalar(zval * return_value, zval * a, zval * b)
{
	unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);

    double ab = zephir_get_doubleval(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) < ab) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}

void tensor_less_equal_scalar(zval * return_value, zval * a, zval * b)
{
	unsigned int i;
	zval c;

    zend_array * aa = Z_ARR_P(a);

    Bucket * ba = aa->arData;

    double ab = zephir_get_doubleval(b);

    unsigned int n = zend_array_count(aa);

	array_init_size(&c, n);

    for (i = 0; i < n; ++i) {
        if (zephir_get_doubleval(zend_hash_index_find(aa, i)) <= ab) {
            add_next_index_long(&c, 1);
        } else {
            add_next_index_long(&c, 0);
        }
    }

    RETVAL_ARR(Z_ARR(c));
}
