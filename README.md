kohana-logintracker
===================

Disallow too many login attempts

Creates a cache directory APPPATH/cache/tracker

Keeps track for each IP. Allows max 3 login attempts.

Throws HTTP_Exception_403 if max login attempts count is exceeded.

To enable, put this before 'auth' module:

    'logintracker' => MODPATH.'logintracker',
