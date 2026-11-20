#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>
#include "kernel/operators.h"

/**
 * 1D convolution between a vector A and B (kernel) with a given stride.
 * 
 * @param return_value
 * @param a
 * @param b
 * @param stride
 */
void tensor_convolve_1d(zval * return_value, zval * a, zval * b, zval * stride)
{
    unsigned int i, j;
    unsigned int jmin, jmax;
    double sigma;
    zval c;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int s = zephir_get_intval(stride);

    unsigned int na = zend_array_count(aa);
    unsigned int nb = zend_array_count(ab);
    unsigned int nc = na + nb - 1;

    double * va = emalloc(na * sizeof(double));
    double * vb = emalloc(nb * sizeof(double));

    for (i = 0; i < na; ++i) {
        va[i] = zephir_get_doubleval(zend_hash_index_find(aa, i));
    }

    for (i = 0; i < nb; ++i) {
        vb[i] = zephir_get_doubleval(zend_hash_index_find(ab, i));
    }

    array_init_size(&c, nc / s);

    for (i = 0; i < nc; i += s) {
        jmin = i >= nb - 1 ? i - (nb - 1) : 0;
        jmax = i <= na ? i : na - 1;

        sigma = 0.0;

        for (j = jmin; j <= jmax; ++j) {
            sigma += va[j] * vb[i - j];
        }

        add_next_index_double(&c, sigma);
    }

    RETVAL_ARR(Z_ARR(c));

    efree(va);
    efree(vb);
}

/**
 * 2D convolution between a matrix A and B (kernel) with a given stride using the "same" method for zero padding.
 * 
 * @param return_value
 * @param a
 * @param b
 * @param stride
 */
void tensor_convolve_2d(zval * return_value, zval * a, zval * b, zval * stride)
{
    unsigned int i, j, k, l;
    int x, y;
    double sigma;
    zval * row;
    zval rowC, c;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int s = zephir_get_intval(stride);

    unsigned int ma = zend_array_count(aa);
    unsigned int na = zend_array_count(Z_ARR_P(zend_hash_index_find(aa, 0)));
    unsigned int mb = zend_array_count(ab);
    unsigned int nb = zend_array_count(Z_ARR_P(zend_hash_index_find(ab, 0)));

    double * va = emalloc(ma * na * sizeof(double));
    double * vb = emalloc(mb * nb * sizeof(double));

    for (i = 0; i < ma; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < na; ++j) {
            va[i * na + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    for (i = 0; i < mb; ++i) {
        row = zend_hash_index_find(ab, i);

        for (j = 0; j < nb; ++j) {
            vb[i * nb + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    unsigned int p = mb / 2;
    unsigned int q = nb / 2;

    array_init_size(&c, ma / s);

    for (i = 0; i < ma; i += s) {
        array_init_size(&rowC, na / s);

        for (j = 0; j < na; j += s) {   
            sigma = 0.0;

            for (k = 0; k < mb; ++k) {
                x = i + p - k;

                if (x < 0 || x >= ma) {
                    continue;
                }

                for (l = 0; l < nb; ++l) {
                    y = j + q - l;

                    if (y >= 0 && y < na) {
                        sigma += va[x * na + y] * vb[k * nb + l];
                    }
                }
            }

            add_next_index_double(&rowC, sigma);
        }

        add_next_index_zval(&c, &rowC);
    }

    RETVAL_ARR(Z_ARR(c));

    efree(va);
    efree(vb);
}
