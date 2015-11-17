<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'AccessCondition' => $baseDir . '/auth/AccessCondition.php',
    'EasyPeasyICS' => $vendorDir . '/phpmailer/phpmailer/extras/EasyPeasyICS.php',
    'Fortress\\ClientSideValidator' => $vendorDir . '/alexweissman/fortress/fortress/ClientSideValidator.php',
    'Fortress\\DataSanitizer' => $vendorDir . '/alexweissman/fortress/fortress/DataSanitizer.php',
    'Fortress\\DataSanitizerInterface' => $vendorDir . '/alexweissman/fortress/fortress/DataSanitizer.php',
    'Fortress\\HTTPRequestFortress' => $vendorDir . '/alexweissman/fortress/fortress/HTTPRequestFortress.php',
    'Fortress\\MessageStream' => $vendorDir . '/alexweissman/fortress/fortress/MessageStream.php',
    'Fortress\\MessageTranslator' => $vendorDir . '/alexweissman/fortress/fortress/MessageTranslator.php',
    'Fortress\\RequestSchema' => $vendorDir . '/alexweissman/fortress/fortress/RequestSchema.php',
    'Fortress\\ServerSideValidator' => $vendorDir . '/alexweissman/fortress/fortress/ServerSideValidator.php',
    'Fortress\\ServerSideValidatorInterface' => $vendorDir . '/alexweissman/fortress/fortress/ServerSideValidator.php',
    'PHPMailer' => $vendorDir . '/phpmailer/phpmailer/class.phpmailer.php',
    'POP3' => $vendorDir . '/phpmailer/phpmailer/class.pop3.php',
    'ParserNodeFunctionEvaluator' => $baseDir . '/auth/ParserNodeFunctionEvaluator.php',
    'SMTP' => $vendorDir . '/phpmailer/phpmailer/class.smtp.php',
    'Slim\\Extras\\Middleware\\CsrfGuard' => $baseDir . '/auth/CsrfGuard.php',
    'Slim\\Extras\\Middleware\\DatabaseCheck' => $baseDir . '/models/DatabaseCheck.php',
    'UserFrosting\\AccessConditionExpression' => $baseDir . '/auth/AccessConditionExpression.php',
    'UserFrosting\\AccountController' => $baseDir . '/controllers/AccountController.php',
    'UserFrosting\\AdminController' => $baseDir . '/controllers/AdminController.php',
    'UserFrosting\\Authentication' => $baseDir . '/auth/Authentication.php',
    'UserFrosting\\AuthorizationException' => $baseDir . '/auth/AuthorizationException.php',
    'UserFrosting\\BaseController' => $baseDir . '/controllers/BaseController.php',
    'UserFrosting\\DatabaseInterface' => $baseDir . '/models/DatabaseInterface.php',
    'UserFrosting\\DatabaseObjectInterface' => $baseDir . '/models/DatabaseInterface.php',
    'UserFrosting\\DatabaseTable' => $baseDir . '/models/DatabaseTable.php',
    'UserFrosting\\DatabaseTableInterface' => $baseDir . '/models/DatabaseInterface.php',
    'UserFrosting\\FilterController' => $baseDir . '/controllers/FilterController.php',
    'UserFrosting\\GroupController' => $baseDir . '/controllers/GroupController.php',
    'UserFrosting\\GroupLoaderInterface' => $baseDir . '/models/DatabaseInterface.php',
    'UserFrosting\\GroupObjectInterface' => $baseDir . '/models/DatabaseInterface.php',
    'UserFrosting\\InstallController' => $baseDir . '/controllers/InstallController.php',
    'UserFrosting\\MySqlAuthLoader' => $baseDir . '/models/mysql/MySqlAuthLoader.php',
    'UserFrosting\\MySqlBlacklistCategories' => $baseDir . '/models/mysql/MySqlBlacklistCategories.php',
    'UserFrosting\\MySqlBlacklistCategoriesLoader' => $baseDir . '/models/mysql/MySqlBlacklistCategoriesLoader.php',
    'UserFrosting\\MySqlConfgen' => $baseDir . '/models/mysql/MySqlConfgen.php',
    'UserFrosting\\MySqlConfgenLoader' => $baseDir . '/models/mysql/MySqlConfgenLoader.php',
    'UserFrosting\\MySqlCustomBlacklist' => $baseDir . '/models/mysql/MySqlCustomBlacklist.php',
    'UserFrosting\\MySqlCustomBlacklistLoader' => $baseDir . '/models/mysql/MySqlCustomBlacklistLoader.php',
    'UserFrosting\\MySqlCustomConf' => $baseDir . '/models/mysql/MySqlCustomConf.php',
    'UserFrosting\\MySqlCustomConfItem' => $baseDir . '/models/mysql/MySqlCustomConfItem.php',
    'UserFrosting\\MySqlCustomConfItemLoader' => $baseDir . '/models/mysql/MySqlCustomConfItemLoader.php',
    'UserFrosting\\MySqlCustomConfLoader' => $baseDir . '/models/mysql/MySqlCustomConfLoader.php',
    'UserFrosting\\MySqlCustomWhitelist' => $baseDir . '/models/mysql/MySqlCustomWhitelist.php',
    'UserFrosting\\MySqlCustomWhitelistLoader' => $baseDir . '/models/mysql/MySqlCustomWhitelistLoader.php',
    'UserFrosting\\MySqlDatabase' => $baseDir . '/models/mysql/MySqlDatabase.php',
    'UserFrosting\\MySqlDatabaseObject' => $baseDir . '/models/mysql/MySqlDatabaseObject.php',
    'UserFrosting\\MySqlGroup' => $baseDir . '/models/mysql/MySqlGroup.php',
    'UserFrosting\\MySqlGroupLoader' => $baseDir . '/models/mysql/MySqlGroupLoader.php',
    'UserFrosting\\MySqlLogline' => $baseDir . '/models/mysql/MySqlLogline.php',
    'UserFrosting\\MySqlLoglineLoader' => $baseDir . '/models/mysql/MySqlLoglineLoader.php',
    'UserFrosting\\MySqlObjectLoader' => $baseDir . '/models/mysql/MySqlObjectLoader.php',
    'UserFrosting\\MySqlSalle' => $baseDir . '/models/mysql/MySqlSalle.php',
    'UserFrosting\\MySqlSalleLoader' => $baseDir . '/models/mysql/MySqlSalleLoader.php',
    'UserFrosting\\MySqlSiteSettings' => $baseDir . '/models/mysql/MySqlSiteSettings.php',
    'UserFrosting\\MySqlUser' => $baseDir . '/models/mysql/MySqlUser.php',
    'UserFrosting\\MySqlUserLoader' => $baseDir . '/models/mysql/MySqlUserLoader.php',
    'UserFrosting\\MySqlWorkingHours' => $baseDir . '/models/mysql/MySqlWorkingHours.php',
    'UserFrosting\\MySqlWorkingHoursLoader' => $baseDir . '/models/mysql/MySqlWorkingHoursLoader.php',
    'UserFrosting\\ObjectLoaderInterface' => $baseDir . '/models/DatabaseInterface.php',
    'UserFrosting\\PageSchema' => $baseDir . '/models/PageSchema.php',
    'UserFrosting\\SalleController' => $baseDir . '/controllers/SalleController.php',
    'UserFrosting\\SiteSettingsInterface' => $baseDir . '/models/DatabaseInterface.php',
    'UserFrosting\\StatsController' => $baseDir . '/controllers/StatsController.php',
    'UserFrosting\\UFDatabase' => $baseDir . '/models/UFDatabase.php',
    'UserFrosting\\UserController' => $baseDir . '/controllers/UserController.php',
    'UserFrosting\\UserFrosting' => $baseDir . '/controllers/UserFrosting.php',
    'UserFrosting\\UserLoaderInterface' => $baseDir . '/models/DatabaseInterface.php',
    'UserFrosting\\UserObjectInterface' => $baseDir . '/models/DatabaseInterface.php',
    'ntlm_sasl_client_class' => $vendorDir . '/phpmailer/phpmailer/extras/ntlm_sasl_client.php',
    'phpmailerException' => $vendorDir . '/phpmailer/phpmailer/class.phpmailer.php',
);
