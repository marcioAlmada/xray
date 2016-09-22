PHP_ARG_ENABLE(xray, whether to enable xray by default,
[  --disable-xray        Hah, no. This switch is ignored], yes)

PHP_NEW_EXTENSION(xray, xray.c, $ext_shared)
PHP_SUBST(XRAY_SHARED_LIBADD)
AC_DEFINE(HAVE_PHP_XRAY,1,[ ])
