#ifndef XRAY_COMPILER_HOOK
#define XRAY_COMPILER_HOOK

zend_op_array *(*orig_zend_compile_file)(zend_file_handle *file_handle, int type);
zend_op_array *(*orig_zend_compile_string)(zval *src, char *filename);

#define XRAY_NS "xray"

#define XRAY_HOOK(fn)    \
    orig_zend_##fn = zend_##fn; \
    zend_##fn = xray_##fn;

#define XRAY_IF_HOOK_IS_AVAILABLE() if (Z_TYPE_P(XRAY_G(hook)) != IS_NULL)

#define XRAY_CALL_COMPILER_HOOK(result, params) \
    call_user_function(EG(function_table), NULL, XRAY_G(hook), &result, sizeof(params)/sizeof(zval), params) == SUCCESS

#define XRAY_THROW_RESULT_MUST_BE_STRING() \
    zend_throw_exception(zend_exception_get_default(TSRMLS_C), "Return value of X-Ray compiler hook must be a string", 0 TSRMLS_CC);

ZEND_BEGIN_MODULE_GLOBALS(xray)
zval hook;
ZEND_END_MODULE_GLOBALS(xray)

#ifdef ZTS
# define XRAY_G(v) &TSRMG(xray_globals_id, zend_xray_globals *, v)
#else
# define XRAY_G(v) &xray_globals.v
#endif

#endif /* XRAY_COMPILER_HOOK */

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */

