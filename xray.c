#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "zend_types.h"
#include "zend_exceptions.h"
#include "php_xray.h"

ZEND_DECLARE_MODULE_GLOBALS(xray);

static ZEND_FUNCTION(set_compiler_hook);

ZEND_BEGIN_ARG_INFO_EX(arginfo_xray_set_compiler_hook, 0, 0, 1)
    ZEND_ARG_INFO(0, callable)
ZEND_END_ARG_INFO()

ZEND_FUNCTION(set_compiler_hook)
{
    zval *hook;
    zend_string *hook_name = NULL;

    if (zend_parse_parameters(ZEND_NUM_ARGS(), "z", &hook) == FAILURE) {
        return;
    }

    if (!zend_is_callable(hook, 0, &hook_name)) {
        zend_error(E_WARNING, "%s() expects the argument (%s) to be a valid callback",
                   get_active_function_name(), hook_name ? ZSTR_VAL(hook_name) : "unknown");
        zend_string_release(hook_name);

        RETURN_FALSE;
    }

    ZVAL_COPY_VALUE(XRAY_G(hook), hook);
    ZVAL_COPY(XRAY_G(hook), hook);

    RETURN_TRUE;
}

static ZEND_FUNCTION(restore_compiler_hook);

ZEND_BEGIN_ARG_INFO(arginfo_xray_restore_compiler_hook, 0)
ZEND_END_ARG_INFO()

ZEND_FUNCTION(restore_compiler_hook)
{
    if (zend_parse_parameters_none() == FAILURE) {
        return;
    }

    ZVAL_COPY(return_value, XRAY_G(hook));

    ZVAL_NULL(XRAY_G(hook));
}

static const zend_function_entry xray_functions[] = { /* {{{ */
    ZEND_NS_FE(XRAY_NS, set_compiler_hook, arginfo_xray_set_compiler_hook)
    ZEND_NS_FE(XRAY_NS, restore_compiler_hook, arginfo_xray_restore_compiler_hook)
    ZEND_FE_END
};
/* }}} */

ZEND_API zend_op_array *xray_compile_string(zval *src, char *filename) {
    zval params[2], result;
    zend_op_array *op_array;

    XRAY_IF_HOOK_IS_AVAILABLE() {
        ZVAL_NULL(&result);
        params[0] = *src; // source string
        ZVAL_NULL(&params[1]); // file name

        if (XRAY_CALL_COMPILER_HOOK(result, params)) {
            if (Z_TYPE(result) == IS_STRING) {
                src = &result;
            }
            else {
                XRAY_THROW_RESULT_MUST_BE_STRING();
            }
        }

        zval_dtor(&params[1]);
    }

    op_array = orig_zend_compile_string(src, filename);

    XRAY_IF_HOOK_IS_AVAILABLE() {
        zval_dtor(&result);
    }

    return op_array;
}

ZEND_API zend_op_array* xray_compile_file(zend_file_handle* file, int type) {

    char *buf;
    size_t size;
    zend_op_array *op_array;
    zend_file_handle fake;
    zval params[2], result;

    int status = zend_stream_fixup(file, &buf, &size);

    if (status == SUCCESS) {
        XRAY_IF_HOOK_IS_AVAILABLE() {

            ZVAL_NULL(&result);

            char *filename = (char *)(file->opened_path ? ZSTR_VAL(file->opened_path) : file->filename);

            ZVAL_STRINGL(&params[0], buf, size); // source string
            ZVAL_STRINGL(&params[1], filename, strlen(filename)); // file name

            if (XRAY_CALL_COMPILER_HOOK(result, params)) {
                if (Z_TYPE(result) == IS_STRING) {
                    memset(&fake, 0, sizeof(fake));
                    fake.type = ZEND_HANDLE_MAPPED;
                    fake.free_filename = 0;
                    fake.filename = (char *)(file->opened_path ? ZSTR_VAL(file->opened_path) : file->filename);
                    fake.opened_path = file->opened_path;
                    fake.handle.stream.mmap.buf = ZSTR_VAL(Z_STR(result));
                    fake.handle.stream.mmap.len = ZSTR_LEN(Z_STR(result));

                    file = &fake;
                }
                else {
                    XRAY_THROW_RESULT_MUST_BE_STRING();
                }
            }

            zval_dtor(&params[0]);
            zval_dtor(&params[1]);
        }
    }

    op_array = orig_zend_compile_file(file, type);

    if (status == SUCCESS) {
        XRAY_IF_HOOK_IS_AVAILABLE() {
            zval_dtor(&result);
            if (Z_TYPE(result) == IS_STRING) {
                fake.opened_path = NULL;
                zend_file_handle_dtor(&fake);
            }
        }
    }

    return op_array;
}

/* {{{ */
static void php_xray_init_globals(zend_xray_globals *xray_globals) {
    ZVAL_NULL(&xray_globals->hook);
} /* }}} */

/* {{{ PHP_MINIT_FUNCTION */
PHP_MINIT_FUNCTION(xray)
{
    ZEND_INIT_MODULE_GLOBALS(xray, php_xray_init_globals, NULL);

    XRAY_HOOK(compile_file);
    XRAY_HOOK(compile_string);

    return SUCCESS;
}
/* }}} */


/* {{{ xray_module_entry */
zend_module_entry xray_module_entry = {
    STANDARD_MODULE_HEADER,
    "xray",
    xray_functions,
    PHP_MINIT(xray),
    NULL,
    NULL,
    NULL,
    NULL,
    "0.1.0",
    STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_XRAY
ZEND_GET_MODULE(xray)
#endif

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */
