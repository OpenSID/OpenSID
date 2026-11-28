
/* This file was generated automatically by Zephir do not modify it! */

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <php.h>

#include "php_ext.h"
#include "tensor.h"

#include <ext/standard/info.h>

#include <Zend/zend_operators.h>
#include <Zend/zend_exceptions.h>
#include <Zend/zend_interfaces.h>

#include "kernel/globals.h"
#include "kernel/main.h"
#include "kernel/fcall.h"
#include "kernel/memory.h"


#include "cblas.h"

zend_class_entry *tensor_algebraic_ce;
zend_class_entry *tensor_arithmetic_ce;
zend_class_entry *tensor_arraylike_ce;
zend_class_entry *tensor_comparable_ce;
zend_class_entry *tensor_special_ce;
zend_class_entry *tensor_statistical_ce;
zend_class_entry *tensor_trigonometric_ce;
zend_class_entry *tensor_exceptions_tensorexception_ce;
zend_class_entry *tensor_tensor_ce;
zend_class_entry *tensor_exceptions_invalidargumentexception_ce;
zend_class_entry *tensor_vector_ce;
zend_class_entry *tensor_columnvector_ce;
zend_class_entry *tensor_decompositions_cholesky_ce;
zend_class_entry *tensor_decompositions_eigen_ce;
zend_class_entry *tensor_decompositions_lu_ce;
zend_class_entry *tensor_decompositions_svd_ce;
zend_class_entry *tensor_exceptions_dimensionalitymismatch_ce;
zend_class_entry *tensor_exceptions_runtimeexception_ce;
zend_class_entry *tensor_matrix_ce;
zend_class_entry *tensor_reductions_ref_ce;
zend_class_entry *tensor_reductions_rref_ce;
zend_class_entry *tensor_settings_ce;

ZEND_DECLARE_MODULE_GLOBALS(tensor)

PHP_INI_BEGIN()
	
PHP_INI_END()

static PHP_MINIT_FUNCTION(tensor)
{
	REGISTER_INI_ENTRIES();
	zephir_module_init();
	ZEPHIR_INIT(Tensor_Algebraic);
	ZEPHIR_INIT(Tensor_Arithmetic);
	ZEPHIR_INIT(Tensor_ArrayLike);
	ZEPHIR_INIT(Tensor_Comparable);
	ZEPHIR_INIT(Tensor_Special);
	ZEPHIR_INIT(Tensor_Statistical);
	ZEPHIR_INIT(Tensor_Trigonometric);
	ZEPHIR_INIT(Tensor_Exceptions_TensorException);
	ZEPHIR_INIT(Tensor_Tensor);
	ZEPHIR_INIT(Tensor_Exceptions_InvalidArgumentException);
	ZEPHIR_INIT(Tensor_Vector);
	ZEPHIR_INIT(Tensor_ColumnVector);
	ZEPHIR_INIT(Tensor_Decompositions_Cholesky);
	ZEPHIR_INIT(Tensor_Decompositions_Eigen);
	ZEPHIR_INIT(Tensor_Decompositions_Lu);
	ZEPHIR_INIT(Tensor_Decompositions_Svd);
	ZEPHIR_INIT(Tensor_Exceptions_DimensionalityMismatch);
	ZEPHIR_INIT(Tensor_Exceptions_RuntimeException);
	ZEPHIR_INIT(Tensor_Matrix);
	ZEPHIR_INIT(Tensor_Reductions_Ref);
	ZEPHIR_INIT(Tensor_Reductions_Rref);
	ZEPHIR_INIT(Tensor_Settings);
	openblas_set_num_threads(1);
	return SUCCESS;
}

#ifndef ZEPHIR_RELEASE
static PHP_MSHUTDOWN_FUNCTION(tensor)
{
	
	zephir_deinitialize_memory();
	UNREGISTER_INI_ENTRIES();
	return SUCCESS;
}
#endif

/**
 * Initialize globals on each request or each thread started
 */
static void php_zephir_init_globals(zend_tensor_globals *tensor_globals)
{
	tensor_globals->initialized = 0;

	/* Cache Enabled */
	tensor_globals->cache_enabled = 1;

	/* Recursive Lock */
	tensor_globals->recursive_lock = 0;

	/* Static cache */
	memset(tensor_globals->scache, '\0', sizeof(zephir_fcall_cache_entry*) * ZEPHIR_MAX_CACHE_SLOTS);

	
	
}

/**
 * Initialize globals only on each thread started
 */
static void php_zephir_init_module_globals(zend_tensor_globals *tensor_globals)
{
	
}

static PHP_RINIT_FUNCTION(tensor)
{
	zend_tensor_globals *tensor_globals_ptr;
	tensor_globals_ptr = ZEPHIR_VGLOBAL;

	php_zephir_init_globals(tensor_globals_ptr);
	zephir_initialize_memory(tensor_globals_ptr);

	
	return SUCCESS;
}

static PHP_RSHUTDOWN_FUNCTION(tensor)
{
	
	zephir_deinitialize_memory();
	return SUCCESS;
}



static PHP_MINFO_FUNCTION(tensor)
{
	php_info_print_box_start(0);
	php_printf("%s", PHP_TENSOR_DESCRIPTION);
	php_info_print_box_end();

	php_info_print_table_start();
	php_info_print_table_header(2, PHP_TENSOR_NAME, "enabled");
	php_info_print_table_row(2, "Author", PHP_TENSOR_AUTHOR);
	php_info_print_table_row(2, "Version", PHP_TENSOR_VERSION);
	php_info_print_table_row(2, "Build Date", __DATE__ " " __TIME__ );
	php_info_print_table_row(2, "Powered by Zephir", "Version " PHP_TENSOR_ZEPVERSION);
	php_info_print_table_end();
	
	DISPLAY_INI_ENTRIES();
}

static PHP_GINIT_FUNCTION(tensor)
{
#if defined(COMPILE_DL_TENSOR) && defined(ZTS)
	ZEND_TSRMLS_CACHE_UPDATE();
#endif

	php_zephir_init_globals(tensor_globals);
	php_zephir_init_module_globals(tensor_globals);
}

static PHP_GSHUTDOWN_FUNCTION(tensor)
{
	
}


zend_function_entry php_tensor_functions[] = {
	ZEND_FE_END

};

static const zend_module_dep php_tensor_deps[] = {
	
	ZEND_MOD_END
};

zend_module_entry tensor_module_entry = {
	STANDARD_MODULE_HEADER_EX,
	NULL,
	php_tensor_deps,
	PHP_TENSOR_EXTNAME,
	php_tensor_functions,
	PHP_MINIT(tensor),
#ifndef ZEPHIR_RELEASE
	PHP_MSHUTDOWN(tensor),
#else
	NULL,
#endif
	PHP_RINIT(tensor),
	PHP_RSHUTDOWN(tensor),
	PHP_MINFO(tensor),
	PHP_TENSOR_VERSION,
	ZEND_MODULE_GLOBALS(tensor),
	PHP_GINIT(tensor),
	PHP_GSHUTDOWN(tensor),
#ifdef ZEPHIR_POST_REQUEST
	PHP_PRSHUTDOWN(tensor),
#else
	NULL,
#endif
	STANDARD_MODULE_PROPERTIES_EX
};

/* implement standard "stub" routine to introduce ourselves to Zend */
#ifdef COMPILE_DL_TENSOR
# ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE()
# endif
ZEND_GET_MODULE(tensor)
#endif
