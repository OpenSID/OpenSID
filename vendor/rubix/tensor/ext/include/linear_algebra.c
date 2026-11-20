#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>
#include <cblas.h>
#include <lapacke.h>
#include "kernel/operators.h"

/**
 * Matrix-matrix multiplication i.e. linear transformation of matrices A and B.
 * 
 * @param return_value
 * @param a
 * @param b
 */
void tensor_matmul(zval * return_value, zval * a, zval * b)
{
    unsigned int i, j;
    zval * row;
    zval rowC, c;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int m = zend_array_count(aa);
    unsigned int p = zend_array_count(ab);
    unsigned int n = zend_array_count(Z_ARR_P(zend_hash_index_find(ab, 0)));

    double * va = emalloc(m * p * sizeof(double));
    double * vb = emalloc(n * p * sizeof(double));
    double * vc = emalloc(m * n * sizeof(double));

    for (i = 0; i < m; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < p; ++j) {
            va[i * p + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    for (i = 0; i < p; ++i) {
        row = zend_hash_index_find(ab, i);

        for (j = 0; j < n; ++j) {
            vb[i * n + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    cblas_dgemm(CblasRowMajor, CblasNoTrans, CblasNoTrans, m, n, p, 1.0, va, p, vb, n, 0.0, vc, n);

    array_init_size(&c, m);

    for (i = 0; i < m; ++i) {
        array_init_size(&rowC, n);

        for (j = 0; j < n; ++j) {
            add_next_index_double(&rowC, vc[i * n + j]);
        }

        add_next_index_zval(&c, &rowC);
    }

    RETVAL_ARR(Z_ARR(c));

    efree(va);
    efree(vb);
    efree(vc);
}

/**
 * Dot product between vectors A and B.
 * 
 * @param return_value
 * @param a
 * @param b
 */
void tensor_dot(zval * return_value, zval * a, zval * b)
{
    unsigned int i;

    zend_array * aa = Z_ARR_P(a);
    zend_array * ab = Z_ARR_P(b);

    unsigned int n = zend_array_count(aa);

    double sigma = 0.0;

    for (i = 0; i < n; ++i) {
        sigma += zephir_get_doubleval(zend_hash_index_find(aa, i)) * zephir_get_doubleval(zend_hash_index_find(ab, i));
    }

    RETVAL_DOUBLE(sigma);
}

/**
 * Return the multiplicative inverse of a square matrix A.
 *
 * @param return_value
 * @param a
 */
void tensor_inverse(zval * return_value, zval * a)
{
    unsigned int i, j;
    zval * row;
    zval rowB, b;

    zend_array * aa = Z_ARR_P(a);

    unsigned int n = zend_array_count(aa);

    double * va = emalloc(n * n * sizeof(double));
    int * pivots = emalloc(n * sizeof(int));

    for (i = 0; i < n; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < n; ++j) {
            va[i * n + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }
    
    lapack_int status;

    status = LAPACKE_dgetrf(LAPACK_ROW_MAJOR, n, n, va, n, pivots);

    if (status != 0) {
        RETURN_NULL();
    }
    
    status = LAPACKE_dgetri(LAPACK_ROW_MAJOR, n, va, n, pivots);

    if (status != 0) {
        RETURN_NULL();
    }

    array_init_size(&b, n);

    for (i = 0; i < n; ++i) {
        array_init_size(&rowB, n);

        for (j = 0; j < n; ++j) {
            add_next_index_double(&rowB, va[i * n + j]);
        }

        add_next_index_zval(&b, &rowB);
    }

    RETVAL_ARR(Z_ARR(b));

    efree(va);
    efree(pivots);
}

/**
 * Return the (Moore-Penrose) pseudoinverse of a general matrix A.
 * 
 * @param return_value
 * @param a
 */
void tensor_pseudoinverse(zval * return_value, zval * a)
{
    unsigned int i, j;
    zval * row;
    zval b, rowB;

    zend_array * aa = Z_ARR_P(a);

    unsigned int m = zend_array_count(aa);
    unsigned int n = zend_array_count(Z_ARR_P(zend_hash_index_find(aa, 0)));
    unsigned int k = MIN(m, n);

    double * va = emalloc(m * n * sizeof(double));
    double * vu = emalloc(m * m * sizeof(double));
    double * vs = emalloc(k * sizeof(double));
    double * vvt = emalloc(n * n * sizeof(double));
    double * vb = emalloc(n * m * sizeof(double));

    for (i = 0; i < m; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < n; ++j) {
            va[i * n + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    lapack_int status = LAPACKE_dgesdd(LAPACK_ROW_MAJOR, 'A', m, n, va, n, vs, vu, m, vvt, n);

    if (status != 0) {
        RETURN_NULL();
    }

    for (i = 0; i < k; ++i) {
        cblas_dscal(m, 1.0 / vs[i], &vu[i], m);
    }

    cblas_dgemm(CblasRowMajor, CblasTrans, CblasTrans, n, m, m, 1.0, vvt, n, vu, m, 0.0, vb, m);

    array_init_size(&b, n);

    for (i = 0; i < n; ++i) {
        array_init_size(&rowB, m);

        for (j = 0; j < m; ++j) {
            add_next_index_double(&rowB, vb[i * m + j]);
        }

        add_next_index_zval(&b, &rowB);
    }

    RETVAL_ARR(Z_ARR(b));

    efree(va);
    efree(vu);
    efree(vs);
    efree(vvt);
    efree(vb);
}

/**
 * Return the row echelon form of matrix A.
 * 
 * @param return_value
 * @param a
 */
void tensor_ref(zval * return_value, zval * a)
{
    unsigned int i, j;
    zval * row;
    zval rowB, b;
    zval tuple;

    zend_array * aa = Z_ARR_P(a);

    unsigned int m = zend_array_count(aa);
    unsigned int n = zend_array_count(Z_ARR_P(zend_hash_index_find(aa, 0)));

    double * va = emalloc(m * n * sizeof(double));
    int * pivots = emalloc(MIN(m, n) * sizeof(int));

    for (i = 0; i < m; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < n; ++j) {
            va[i * n + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    lapack_int status = LAPACKE_dgetrf(LAPACK_ROW_MAJOR, m, n, va, n, pivots);

    if (status != 0) {
        RETURN_NULL();
    }
    
    array_init_size(&b, m);

    long swaps = 0;

    for (i = 0; i < m; ++i) {
        array_init_size(&rowB, n);

        for (j = 0; j < i; ++j) {
            add_next_index_double(&rowB, 0.0);
        }

        for (j = i; j < n; ++j) {
            add_next_index_double(&rowB, va[i * n + j]);
        }

        add_next_index_zval(&b, &rowB);

        if (i + 1 != pivots[i]) {
            ++swaps;
        }
    }

    array_init_size(&tuple, 2);
    
    add_next_index_zval(&tuple, &b);
    add_next_index_long(&tuple, swaps);

    RETVAL_ARR(Z_ARR(tuple));

    efree(va);
    efree(pivots);
}

/**
 * Compute the Cholesky decomposition of matrix A and return the lower triangular matrix.
 * 
 * @param return_value
 * @param a
 */
void tensor_cholesky(zval * return_value, zval * a)
{
    unsigned int i, j;
    zval * row;
    zval rowB, b;

    zend_array * aa = Z_ARR_P(a);

    unsigned int n = zend_array_count(aa);

    double * va = emalloc(n * n * sizeof(double));

    for (i = 0; i < n; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < n; ++j) {
            va[i * n + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    lapack_int status = LAPACKE_dpotrf(LAPACK_ROW_MAJOR, 'L', n, va, n);

    if (status != 0) {
        RETURN_NULL();
    }
    
    array_init_size(&b, n);

    for (i = 0; i < n; ++i) {
        array_init_size(&rowB, n);

        for (j = 0; j <= i; ++j) {
            add_next_index_double(&rowB, va[i * n + j]);
        }

        for (j = i + 1; j < n; ++j) {
            add_next_index_double(&rowB, 0.0);
        }

        add_next_index_zval(&b, &rowB);
    }

    RETVAL_ARR(Z_ARR(b));

    efree(va);
}

/**
 * Compute the LU factorization of matrix A and return a tuple with lower, upper, and permutation matrices.
 * 
 * @param return_value
 * @param a
 */
void tensor_lu(zval * return_value, zval * a)
{
    unsigned int i, j;
    zval * row;
    zval rowL, l, rowU, u, rowP, p;
    zval tuple;

    zend_array * aa = Z_ARR_P(a);

    unsigned int n = zend_array_count(aa);

    double * va = emalloc(n * n * sizeof(double));
    int * pivots = emalloc(n * sizeof(int));

    for (i = 0; i < n; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < n; ++j) {
            va[i * n + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    lapack_int status = LAPACKE_dgetrf(LAPACK_ROW_MAJOR, n, n, va, n, pivots);

    if (status != 0) {
        RETURN_NULL();
    }
    
    array_init_size(&l, n);
    array_init_size(&u, n);
    array_init_size(&p, n);

    for (i = 0; i < n; ++i) {
        array_init_size(&rowL, n);

        for (j = 0; j < i; ++j) {
            add_next_index_double(&rowL, va[i * n + j]);
        }

        add_next_index_double(&rowL, 1.0);

        for (j = i + 1; j < n; ++j) {
            add_next_index_double(&rowL, 0.0);
        }

        add_next_index_zval(&l, &rowL);
    }

    for (i = 0; i < n; ++i) {
        array_init_size(&rowU, n);

        for (j = 0; j < i; ++j) {
            add_next_index_double(&rowU, 0.0);
        }

        for (j = i; j < n; ++j) {
            add_next_index_double(&rowU, va[i * n + j]);
        }

        add_next_index_zval(&u, &rowU);
    }

    for (i = 0; i < n; ++i) {
        array_init_size(&rowP, n);

        for (j = 0; j < n; ++j) {
            if (j == pivots[i] - 1) {
                add_next_index_long(&rowP, 1);
            } else {
                add_next_index_long(&rowP, 0);
            }
        }

        add_next_index_zval(&p, &rowP);
    }

    array_init_size(&tuple, 3);
    
    add_next_index_zval(&tuple, &l);
    add_next_index_zval(&tuple, &u);
    add_next_index_zval(&tuple, &p);

    RETVAL_ARR(Z_ARR(tuple));

    efree(va);
    efree(pivots); 
}

/**
 * Compute the eigendecomposition of a general matrix A and return the eigenvalues and eigenvectors in a tuple.
 * 
 * @param return_value
 * @param a
 */
void tensor_eig(zval * return_value, zval * a)
{
    unsigned int i, j;
    zval * row;
    zval eigenvalues;
    zval eigenvectors;
    zval eigenvector;
    zval tuple;

    zend_array * aa = Z_ARR_P(a);

    unsigned int n = zend_array_count(aa);

    double * va = emalloc(n * n * sizeof(double));
    double * wr = emalloc(n * sizeof(double));
    double * wi = emalloc(n * sizeof(double));
    double * vr = emalloc(n * n * sizeof(double));

    for (i = 0; i < n; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < n; ++j) {
            va[i * n + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    lapack_int status = LAPACKE_dgeev(LAPACK_ROW_MAJOR, 'N', 'V', n, va, n, wr, wi, NULL, n, vr, n);

    if (status != 0) {
        RETURN_NULL();
    }

    array_init_size(&eigenvalues, n);
    array_init_size(&eigenvectors, n);

    for (i = 0; i < n; ++i) {
        add_next_index_double(&eigenvalues, wr[i]);

        array_init_size(&eigenvector, n);

        for (j = 0; j < n; ++j) {
            add_next_index_double(&eigenvector, vr[i * n + j]);
        }

        add_next_index_zval(&eigenvectors, &eigenvector);
    }

    array_init_size(&tuple, 2);
    
    add_next_index_zval(&tuple, &eigenvalues);
    add_next_index_zval(&tuple, &eigenvectors);

    RETVAL_ARR(Z_ARR(tuple));

    efree(va);
    efree(wr);
    efree(wi);
    efree(vr);
}

/**
 * Compute the eigendecomposition of a symmetric matrix A and return the eigenvalues and eigenvectors in a tuple.
 * 
 * @param return_value
 * @param a
 */
void tensor_eig_symmetric(zval * return_value, zval * a)
{
    unsigned int i, j;
    zval * row;
    zval eigenvalues;
    zval eigenvectors;
    zval eigenvector;
    zval tuple;

    zend_array * aa = Z_ARR_P(a);

    unsigned int n = zend_array_count(aa);

    double * va = emalloc(n * n * sizeof(double));
    double * wr = emalloc(n * sizeof(double));

    for (i = 0; i < n; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < n; ++j) {
            va[i * n + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    lapack_int status = LAPACKE_dsyev(LAPACK_ROW_MAJOR, 'V', 'U', n, va, n, wr);

    if (status != 0) {
        RETURN_NULL();
    }

    array_init_size(&eigenvalues, n);
    array_init_size(&eigenvectors, n);

    for (i = 0; i < n; ++i) {
        add_next_index_double(&eigenvalues, wr[i]);

        array_init_size(&eigenvector, n);

        for (j = 0; j < n; ++j) {
            add_next_index_double(&eigenvector, va[i * n + j]);
        }

        add_next_index_zval(&eigenvectors, &eigenvector);
    }

    array_init_size(&tuple, 2);
    
    add_next_index_zval(&tuple, &eigenvalues);
    add_next_index_zval(&tuple, &eigenvectors);

    RETVAL_ARR(Z_ARR(tuple));

    efree(va);
    efree(wr);
}

/**
 * Compute the singular value decomposition of a matrix A and return the singular values and unitary matrices U and VT in a tuple.
 * 
 * @param return_value
 * @param a
 */
void tensor_svd(zval * return_value, zval * a)
{
    unsigned int i, j;
    zval * row;
    zval u, rowU;
    zval s;
    zval vt, rowVt;
    zval tuple;

    zend_array * aa = Z_ARR_P(a);

    unsigned int m = zend_array_count(aa);
    unsigned int n = zend_array_count(Z_ARR_P(zend_hash_index_find(aa, 0)));
    unsigned int k = MIN(m, n);

    double * va = emalloc(m * n * sizeof(double));
    double * vu = emalloc(m * m * sizeof(double));
    double * vs = emalloc(k * sizeof(double));
    double * vvt = emalloc(n * n * sizeof(double));

    for (i = 0; i < m; ++i) {
        row = zend_hash_index_find(aa, i);

        for (j = 0; j < n; ++j) {
            va[i * n + j] = zephir_get_doubleval(zend_hash_index_find(Z_ARR_P(row), j));
        }
    }

    lapack_int status = LAPACKE_dgesdd(LAPACK_ROW_MAJOR, 'A', m, n, va, n, vs, vu, m, vvt, n);

    if (status != 0) {
        RETURN_NULL();
    }

    array_init_size(&u, m);
    array_init_size(&s, k);
    array_init_size(&vt, n);

    for (i = 0; i < m; ++i) {
        array_init_size(&rowU, m);

        for (j = 0; j < m; ++j) {
            add_next_index_double(&rowU, vu[i * m + j]);
        }

        add_next_index_zval(&u, &rowU);
    }
    
    for (i = 0; i < k; ++i) {
        add_next_index_double(&s, vs[i]);
    }

    for (i = 0; i < n; ++i) {
        array_init_size(&rowVt, n);

        for (j = 0; j < n; ++j) {
            add_next_index_double(&rowVt, vvt[i * n + j]);
        }

        add_next_index_zval(&vt, &rowVt);
    }

    array_init_size(&tuple, 3);
    
    add_next_index_zval(&tuple, &u);
    add_next_index_zval(&tuple, &s);
    add_next_index_zval(&tuple, &vt);

    RETVAL_ARR(Z_ARR(tuple));

    efree(va);
    efree(vu);
    efree(vs);
    efree(vvt);
}
