<?php
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// Moodle configuration file                                             //
//                                                                       //
// This file should be renamed "config.php" in the top-level directory   //
//                                                                       //
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 1999 onwards  Martin Dougiamas  http://moodle.com       //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 3 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////

if (!defined('CONFIG_PHP')):
define('CONFIG_PHP', true);

unset($CFG);  // Ignore this line
global $CFG;  // This is necessary here for PHPUnit execution
$CFG = new stdClass();

$CFG->dbhost = getenv('DB_HOST');
$CFG->dbname = getenv('DB_NAME');
$CFG->dbuser = getenv('DB_USER');
$CFG->dbpass = getenv('DB_PASSWORD');
$CFG->dbtype = 'mariadb';
$CFG->dblibrary = 'native';
$CFG->prefix = '';
$CFG->dboptions = [
    'dbpersist' => false,
    'dbsocket'  => false,
    'dbport'    => '',
    'dbhandlesoptions' => false,
    'dbcollation' => 'utf8mb4_unicode_ci',
];

// do not redirect backend calls
if (empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $CFG->wwwroot = 'https://moodle';
} else {
    $CFG->wwwroot = getenv('WWW_ROOT');
}

$CFG->dataroot  = '/moodledata';
$CFG->directorypermissions = 02777;
$CFG->admin = 'admin';

// The following lines are for handling email bounces.
//      $CFG->handlebounces = true;
//      $CFG->minbounces = 10;
//      $CFG->bounceratio = .20;
// The next lines are needed both for bounce handling and any other email to module processing.
// mailprefix must be EXACTLY four characters.
// Uncomment and customise this block for Postfix
//      $CFG->mailprefix = 'mdl+'; // + is the separator for Exim and Postfix.
//      $CFG->mailprefix = 'mdl-'; // - is the separator for qmail
//      $CFG->maildomain = 'youremaildomain.com';

// Enable when setting up advanced reverse proxy load balancing configurations,
// it may be also necessary to enable this when using port forwarding.
// $CFG->reverseproxy = true;
//
// Enable when using external SSL appliance for performance reasons.
// Please note that site may be accessible via http: or https:, but not both!
$CFG->sslproxy = true;
//
// This setting will make some graphs (eg user logs) use lines instead of bars
//      $CFG->preferlinegraphs = true;
//
// This setting allows you to specify a class to rewrite outgoing urls
// enabling 'clean urls' in conjunction with an apache / nginx handler.
// The handler must implement \core\output\url_rewriter.
//      $CFG->urlrewriteclass = '\local_cleanurls\url_rewriter';
//
//   If you set Debug to "Yes" in the Configuration->Variables page some
//   performance profiling data will show up on your footer (in default theme).
//   With these settings you get more granular control over the capture
//   and printout of the data
//
//   Capture performance profiling data
//   define('MDL_PERF'  , true);
//
//   Capture additional data from DB
//   define('MDL_PERFDB'  , true);
//
//   Print to log (for passive profiling of production servers)
//   define('MDL_PERFTOLOG'  , true);
//
//   Print to footer (works with the default theme)
//   define('MDL_PERFTOFOOT', true);
//
//   Enable earlier profiling that causes more code to be covered
//   on every request (db connections, config load, other inits...).
//   Requires extra configuration to be defined in config.php like:
//   profilingincluded, profilingexcluded, profilingautofrec,
//   profilingallowme, profilingallowall, profilinglifetime
//       $CFG->earlyprofilingenabled = true;
//
// Disable database storage for profile data.
// When using an exernal plugin to store profiling data it is often
// desirable to not store the data in the database.
//
//      $CFG->disableprofilingtodatabase = true;
//
// $CFG->lang = 'de';
// Use the following flag to completely disable the installation of plugins
// (new plugins, available updates and missing dependencies) and related
// features (such as cancelling the plugin installation or upgrade) via the
// server administration web interface.
//
//      $CFG->disableupdateautodeploy = true;
//
// Some administration options allow setting the path to executable files. This can
// potentially cause a security risk. Set this option to true to disable editing
// those config settings via the web. They will need to be set explicitly in the
// config.php file
//      $CFG->preventexecpath = true;
//
// Use the following flag to set userid for noreply user. If not set then moodle will
// create dummy user and use -ve value as user id.
//      $CFG->noreplyuserid = -10;
//
// As of version 2.6 Moodle supports admin to set support user. If not set, all mails
// will be sent to supportemail.
//      $CFG->supportuserid = -20;
//
// If the upgrade key is defined here, then the value must be provided every time
// the site is being upgraded though the web interface, regardless of whether the
// administrator is logged in or not. This prevents anonymous access to the upgrade
// screens where the real authentication and authorization mechanisms can not be
// relied on.
//
// It is strongly recommended to use a value different from your real account
// password.
//
//      $CFG->upgradekey = 'put_some_password-like_value_here';
//
// Use the following flag to enable messagingallusers and set the default preference
// value for existing users to allow them to be contacted by other site users.
//
//      $CFG->keepmessagingallusersenabled = true;
//
// Disable login token validation for login pages. Login token validation is enabled
// by default unless $CFG->alternateloginurl is set.
//
//      $CFG->disablelogintoken = true;
//
// Uninstall plugins from CLI only. This stops admins from uninstalling plugins from the graphical admin
// user interface, and forces plugins to be uninstalled from the Command Line tool only, found at
// admin/cli/plugin_uninstall.php.
//
//      $CFG->uninstallclionly = true;
//
//
//=========================================================================
// 7. SETTINGS FOR DEVELOPMENT SERVERS - not intended for production use!!!
//=========================================================================
//
// Force a debugging mode regardless the settings in the site administration
// @error_reporting(E_ALL | E_STRICT); // NOT FOR PRODUCTION SERVERS!
// @ini_set('display_errors', '1');    // NOT FOR PRODUCTION SERVERS!
// $CFG->debug = (E_ALL | E_STRICT);   // === DEBUG_DEVELOPER - NOT FOR PRODUCTION SERVERS!
// $CFG->debugdisplay = 1;             // NOT FOR PRODUCTION SERVERS!
//
// You can specify a comma separated list of user ids that that always see
// debug messages, this overrides the debug flag in $CFG->debug and $CFG->debugdisplay
// for these users only.
// $CFG->debugusers = '2';
//
// Prevent theme caching
// $CFG->themedesignermode = true; // NOT FOR PRODUCTION SERVERS!
//
// Enable verbose debug information during fetching of email messages from IMAP server.
// $CFG->debugimap = true;
//
// Enable verbose debug information during sending of email messages to SMTP server.
// Note: also requires $CFG->debug set to DEBUG_DEVELOPER.
// $CFG->debugsmtp = true;
//
// Prevent JS caching
// $CFG->cachejs = false; // NOT FOR PRODUCTION SERVERS!
//
// Prevent Template caching
// $CFG->cachetemplates = false; // NOT FOR PRODUCTION SERVERS!
//
// Restrict which YUI logging statements are shown in the browser console.
// For details see the upstream documentation:
//   http://yuilibrary.com/yui/docs/api/classes/config.html#property_logInclude
//   http://yuilibrary.com/yui/docs/api/classes/config.html#property_logExclude
// $CFG->yuiloginclude = array(
//     'moodle-course-categoryexpander' => true,
// );
// $CFG->yuilogexclude = array(
//     'moodle-core-notification' => true,
// );
//
// Set the minimum log level for YUI logging statements.
// For details see the upstream documentation:
//   http://yuilibrary.com/yui/docs/api/classes/config.html#property_logLevel
// $CFG->yuiloglevel = 'debug';
//
// Prevent core_string_manager application caching
// $CFG->langstringcache = false; // NOT FOR PRODUCTION SERVERS!
//
// When working with production data on test servers, no emails or other messages
// should ever be send to real users
// $CFG->noemailever = true;    // NOT FOR PRODUCTION SERVERS!
//
// Divert all outgoing emails to this address to test and debug emailing features
// $CFG->divertallemailsto = 'root@localhost.local'; // NOT FOR PRODUCTION SERVERS!
//
// Except for certain email addresses you want to let through for testing. Accepts
// a comma separated list of regexes.
// $CFG->divertallemailsexcept = 'tester@dev.com, fred(\+.*)?@example.com'; // NOT FOR PRODUCTION SERVERS!
//
// Uncomment if you want to allow empty comments when modifying install.xml files.
// $CFG->xmldbdisablecommentchecking = true;    // NOT FOR PRODUCTION SERVERS!
//
// Since 2.0 sql queries are not shown during upgrade by default.
// Please note that this setting may produce very long upgrade page on large sites.
// $CFG->upgradeshowsql = true; // NOT FOR PRODUCTION SERVERS!
//
// Add SQL queries to the output of cron, just before their execution
// $CFG->showcronsql = true;
//
// Force developer level debug and add debug info to the output of cron
// $CFG->showcrondebugging = true;
//
// Force result of checks used to determine whether a site is considered "public" or not (such as for site registration).
// $CFG->site_is_public = false;
//
//=========================================================================
// 8. FORCED SETTINGS
//=========================================================================
// It is possible to specify normal admin settings here, the point is that
// they can not be changed through the standard admin settings pages any more.
//
// Core settings are specified directly via assignment to $CFG variable.
// Example:
//   $CFG->somecoresetting = 'value';

$CFG->forced_plugin_settings = [
    'auth_ldap' => [
        'host_url' => getenv('LDAP_HOST'),
        'bind_dn' => getenv('LDAP_BIND_DN'),
        'bind_pw' => getenv('LDAP_BIND_PASSWORD'),
    ],
    'enrol_ldap' => [
        'host_url' => getenv('LDAP_HOST'),
        'bind_dn' => getenv('LDAP_BIND_DN'),
        'bind_pw' => getenv('LDAP_BIND_PASSWORD'),
    ],
];

$CFG->smtphosts = getenv('SMTP_HOST');
$CFG->smtpsecure = getenv('SMTP_SECURE');
$CFG->smtpauthtype = getenv('SMTP_AUTH_TYPE');
$CFG->smtpuser = getenv('SMTP_USER');
$CFG->smtppass = getenv('SMTP_PASSWORD');

$CFG->messageinbound_host = getenv('IMAP_HOST');
$CFG->messageinbound_hostssl = getenv('IMAP_SSL');
$CFG->messageinbound_hostuser = getenv('IMAP_USER');
$CFG->messageinbound_hostpass = getenv('IMAP_PASSWORD');

$CFG->pathtogs = '/usr/bin/gs';
$CFG->pathtophp = '/usr/bin/php';
$CFG->pathtodu = '/usr/bin/du';
$CFG->pathtopython = '/usr/bin/python3';
$CFG->pathtopdftoppm = '/usr/bin/pdftoppm';
$CFG->aspellpath = '';
$CFG->pathtodot = '';
$CFG->pathtounoconv = '';

// hide configuration in phpinfo()
foreach ([
    'DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASSWORD',
    'WWW_ROOT',
    'SMTP_HOST', 'SMTP_SECURE', 'SMTP_AUTH_TYPE', 'SMTP_USER', 'SMTP_PASSWORD',
    'IMAP_HOST', 'IMAP_SSL', 'IMAP_USER', 'IMAP_PASSWORD',
    'LDAP_HOST', 'LDAP_BIND_DN', 'LDAP_BIND_PASSWORD',
] as $envName) {
    if (isset($_SERVER[$envName])) {
        unset($_SERVER[$envName]);
    }
    if (isset($_ENV[$envName])) {
        unset($_ENV[$envName]);
    }
}

require_once(__DIR__ . '/lib/setup.php'); // Do not edit

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!

endif; // !defined('CONFIG_PHP')
