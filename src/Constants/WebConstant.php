<?php
namespace AppBundle\Constants;


class WebConstant
{
    // Session (array names for data stored in session)
    //
    const SESSION_EVENTS            = 'events';             // store events
    const SESSION_LOGOUT            = 'logout';             // store logout reason
    const SESSION_HTTP              = 'http_code';          // store HTTP response code
    const SESSION_TWOFA             = 'twofa';              // store twofa method
    const SESSION_LOCALE            = 'locale';             // user's locale
    const SESSION_SEC_CNTR          = 'sec_cntr';           // store security counter (twofa verification attempts)
    const SESSION_LAST_ROUTE        = 'last_route';         // store last route visited by user


    // Logout Reason (stored against self::SESSION_LOGOUT)
    //
    const LOGOUT_REAS_TIMEOUT       = 0x01;                 // user timed out
    const LOGOUT_REAS_APIERR        = 0x02;                 // API error code received
    const LOGOUT_REAS_2FA_FAIL      = 0x04;                 // user failed 2FA
    const LOGOUT_REAS_LOGGEDOUT     = 0x08;                 // user logged out


    // API cookie name
    //
    const API_COOKIE                = 'website_session';
}