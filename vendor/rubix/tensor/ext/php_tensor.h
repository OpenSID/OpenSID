
/* This file was generated automatically by Zephir do not modify it! */

#ifndef PHP_TENSOR_H
#define PHP_TENSOR_H 1

#ifdef PHP_WIN32
#define ZEPHIR_RELEASE 1
#endif

#include "kernel/globals.h"

#define PHP_TENSOR_NAME        "tensor"
#define PHP_TENSOR_VERSION     "3.0.5"
#define PHP_TENSOR_EXTNAME     "tensor"
#define PHP_TENSOR_AUTHOR      "The Rubix ML Community"
#define PHP_TENSOR_ZEPVERSION  "0.17.0-$Id$"
#define PHP_TENSOR_DESCRIPTION "A library and extension that provides objects for scientific computing in PHP."



ZEND_BEGIN_MODULE_GLOBALS(tensor)

	int initialized;

	/** Function cache */
	HashTable *fcache;

	zephir_fcall_cache_entry *scache[ZEPHIR_MAX_CACHE_SLOTS];

	/* Cache enabled */
	unsigned int cache_enabled;

	/* Max recursion control */
	unsigned int recursive_lock;

	
ZEND_END_MODULE_GLOBALS(tensor)

#ifdef ZTS
#include "TSRM.h"
#endif

ZEND_EXTERN_MODULE_GLOBALS(tensor)

#ifdef ZTS
	#define ZEPHIR_GLOBAL(v) ZEND_MODULE_GLOBALS_ACCESSOR(tensor, v)
#else
	#define ZEPHIR_GLOBAL(v) (tensor_globals.v)
#endif

#ifdef ZTS
	ZEND_TSRMLS_CACHE_EXTERN()
	#define ZEPHIR_VGLOBAL ((zend_tensor_globals *) (*((void ***) tsrm_get_ls_cache()))[TSRM_UNSHUFFLE_RSRC_ID(tensor_globals_id)])
#else
	#define ZEPHIR_VGLOBAL &(tensor_globals)
#endif

#define ZEPHIR_API ZEND_API

#define zephir_globals_def tensor_globals
#define zend_zephir_globals_def zend_tensor_globals

extern zend_module_entry tensor_module_entry;
#define phpext_tensor_ptr &tensor_module_entry

#endif
