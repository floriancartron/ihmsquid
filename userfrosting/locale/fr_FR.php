<?php

/**
 * fr_FR
 *
 * FR French message token translations
 *
 * @package UserFrosting
 * @link http://www.userfrosting.com/components/#i18n
 * 
 */

/*
{{name}} - Dymamic markers which are replaced at run time by the relevant index.
*/

$lang = array();

// Site Content
$lang = array_merge($lang, [
	"REGISTER_WELCOME" => "",
	"MENU_USERS" => "Utilisateurs",
	"MENU_CONFIGURATION" => "Configuration du site",
	"MENU_SITE_SETTINGS" => "Paramètres",
	"MENU_GROUPS" => "Groupes",
	"HEADER_MESSAGE_ROOT" => "Vous connecté en tant qu'utilisateur Root"
]);

// Installer
$lang = array_merge($lang,array(
	"INSTALLER_INCOMPLETE" => "You cannot register the root account until the installer has been successfully completed!",
	"MASTER_ACCOUNT_EXISTS" => "The master account already exists!",
	"MASTER_ACCOUNT_NOT_EXISTS" => "You cannot register an account until the master account has been created!",
	"CONFIG_TOKEN_MISMATCH" => "Sorry, that configuration token is not correct."
));

// Account
$lang = array_merge($lang,array(
	"ACCOUNT_SPECIFY_USERNAME" => "Entrez votre nom d'utilisateur.",
	"ACCOUNT_SPECIFY_DISPLAY_NAME" => "Entrez votre nom.",
	"ACCOUNT_SPECIFY_PASSWORD" => "Entrez votre mot de passe.",
	"ACCOUNT_SPECIFY_EMAIL" => "Entrez votre adresse email.",
	"ACCOUNT_SPECIFY_CAPTCHA" => "Entrez le code captcha.",
	"ACCOUNT_SPECIFY_LOCALE" => "Entrez une langue valide.",
	"ACCOUNT_INVALID_EMAIL" => "Adresse email non valide",
	"ACCOUNT_INVALID_USERNAME" => "Nom d'utilisateur non valide",
	"ACCOUNT_INVALID_USER_ID" => "L'User ID demandé n'existe pas.",
	"ACCOUNT_USER_OR_EMAIL_INVALID" => "Mauvais nom d'utilisateur email.",
	"ACCOUNT_USER_OR_PASS_INVALID" => "Mauvais nom d'utilisateur ou mot de passe.",
	"ACCOUNT_ALREADY_ACTIVE" => "Votre compte est déjà activé.",
	"ACCOUNT_REGISTRATION_DISABLED" => "Désolé, la création de compte est désactivée.",
	"ACCOUNT_REGISTRATION_LOGOUT" => "Vous ne pouvez pas créer de compte car vous êtes déjà connecté.",
	"ACCOUNT_INACTIVE" => "Votre compte est inactif.",
	"ACCOUNT_DISABLED" => "Ce compte a été désactivé.",
	"ACCOUNT_USER_CHAR_LIMIT" => "Votre nom d'utilisateur doit comprendre entre {{min}} et {{max}} caractères.",
	"ACCOUNT_DISPLAY_CHAR_LIMIT" => "Votre nom doit comprendre entre {{min}} et {{max}} caractères.",
	"ACCOUNT_PASS_CHAR_LIMIT" => "Votre mot de passe doit comprendre entre {{min}} et {{max}} caractères.",
	"ACCOUNT_EMAIL_CHAR_LIMIT" => "L'adresse email doit comprendre entre {{min}} et {{max}} caractères.",
	"ACCOUNT_TITLE_CHAR_LIMIT" => "Le titre doit comprendre entre {{min}} et {{max}} caractères.",
	"ACCOUNT_PASS_MISMATCH" => "Les mots de passe sont différents.",
	"ACCOUNT_DISPLAY_INVALID_CHARACTERS" => "Le nom doit contenir uniquement des caractères alpha-numériques.",
	"ACCOUNT_USERNAME_IN_USE" => "Le nom d'utilisateur '{{user_name}}' est déjà utilisé",
	"ACCOUNT_DISPLAYNAME_IN_USE" => "Le nom '{{display_name}}' est déjà utilisé",
	"ACCOUNT_EMAIL_IN_USE" => "L'adresse email '{{email}}' est déjà utilisée",
	"ACCOUNT_LINK_ALREADY_SENT" => "An activation email has already been sent to this email address in the last {{resend_activation_threshold}} second(s). Please try again later.",
	"ACCOUNT_NEW_ACTIVATION_SENT" => "We have emailed you a new activation link, please check your email",
	"ACCOUNT_SPECIFY_NEW_PASSWORD" => "Veuillez entrer votre nouveau mot de passe",
	"ACCOUNT_SPECIFY_CONFIRM_PASSWORD" => "Veuillez confirmer votre nouveau mot de passe",
	"ACCOUNT_NEW_PASSWORD_LENGTH" => "Le nouveau mot de passe doit comprendre entre {{min}} et {{max}} caractères",
	"ACCOUNT_PASSWORD_INVALID" => "Le mot de passe actuel n'a pas été correctemet renseigné",
	"ACCOUNT_DETAILS_UPDATED" => "Compte mis à jour pour l'utilisateur '{{user_name}}'",
	"ACCOUNT_CREATION_COMPLETE" => "Le compte pour le nouvel utilisateur '{{user_name}}' a été créé.",
	"ACCOUNT_ACTIVATION_COMPLETE" => "You have successfully activated your account. You can now login.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE1" => "You have successfully registered. You can now login.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE2" => "You have successfully registered. You will soon receive an activation email. You must activate your account before logging in.",
	"ACCOUNT_PASSWORD_NOTHING_TO_UPDATE" => "Le nouveau mot de passe est identique à l'ancien",
	"ACCOUNT_PASSWORD_CONFIRM_CURRENT" => "Veuillez confirmer votre mot de passe actuel",
	"ACCOUNT_SETTINGS_UPDATED" => "Paramètres du compte mis à jour",
	"ACCOUNT_PASSWORD_UPDATED" => "Mot de passe mis à jour",
	"ACCOUNT_EMAIL_UPDATED" => "Email mis à jour",
	"ACCOUNT_TOKEN_NOT_FOUND" => "Token does not exist / Account is already activated",
	"ACCOUNT_USER_INVALID_CHARACTERS" => "Le nom d'utilisateur doit contenir uniquement des caractères alphanumériques.",
	"ACCOUNT_DELETE_MASTER" => "Vous ne pouvez pas supprimer le compte maître!",
	"ACCOUNT_DISABLE_MASTER" => "Vous ne pouvez pas désactiver le compte maître!",
	"ACCOUNT_DISABLE_SUCCESSFUL" => "Le compte '{{user_name}}' a été désactivé.",
	"ACCOUNT_ENABLE_SUCCESSFUL" => "Le compte '{{user_name}}' a été activé.",
	"ACCOUNT_DELETION_SUCCESSFUL" => "L'utilisateur '{{user_name}}' a été supprimé.",
	"ACCOUNT_MANUALLY_ACTIVATED" => "Le compte {{user_name}}' a été activé manuellement.",
	"ACCOUNT_DISPLAYNAME_UPDATED" => "Le nom de l'utilisateur {{user_name}} a été changé pour '{{display_name}}'",
	"ACCOUNT_TITLE_UPDATED" => "Le titre de l'utilisateur {{user_name}} a été changé pour '{{title}}'",
	"ACCOUNT_GROUP_ADDED" => "Utilisateur ajouté au groupe '{{name}}'.",
	"ACCOUNT_GROUP_REMOVED" => "Utilisateur supprimé du groupe '{{name}}'.",
	"ACCOUNT_GROUP_NOT_MEMBER" => "L'utilisateur n'est pas membre du groupe {{name}}'.",
	"ACCOUNT_GROUP_ALREADY_MEMBER" => "L'utilisateur est déjà un membre du groupe '{{name}}'.",
	"ACCOUNT_PRIMARY_GROUP_SET" => "Le groupe principal '{{user_name}}' a été affecté.",
	"ACCOUNT_WELCOME" => "Bonjour, {{display_name}}"
));

// Generic validation
$lang = array_merge($lang, array(
	"VALIDATE_REQUIRED" => "Le champ doit être renseigné.",
	"VALIDATE_BOOLEAN" => "La valeur doit être '0' ou '1'.",
	"VALIDATE_INTEGER" => "La valeur doit être un entier.",
	"VALIDATE_ARRAY" => "Les valeurs doivent être dans un tableau."
));

// Configuration
$lang = array_merge($lang,array(
	"CONFIG_PLUGIN_INVALID" => "You are trying to update settings for plugin '{{plugin}}', but there is no plugin by that name.",
	"CONFIG_SETTING_INVALID" => "You are trying to update the setting '{{name}}' for plugin '{{plugin}}', but it does not exist.",
	"CONFIG_NAME_CHAR_LIMIT" => "Site name must be between {{min}} and {{max}} characters in length",
	"CONFIG_URL_CHAR_LIMIT" => "Site url must be between {{min}} and {{max}} characters in length",
	"CONFIG_EMAIL_CHAR_LIMIT" => "Site email must be between {{min}} and {{max}} characters in length",
	"CONFIG_TITLE_CHAR_LIMIT" => "New user title must be between {{min}} and {{max}} characters in length",
	"CONFIG_ACTIVATION_TRUE_FALSE" => "Email activation must be either `true` or `false`",
	"CONFIG_REGISTRATION_TRUE_FALSE" => "User registration must be either `true` or `false`",
	"CONFIG_ACTIVATION_RESEND_RANGE" => "Activation Threshold must be between {{min}} and {{max}} hours",
	"CONFIG_EMAIL_INVALID" => "The email you have entered is not valid",
	"CONFIG_UPDATE_SUCCESSFUL" => "Your site's configuration has been updated. You may need to load a new page for all the settings to take effect",
	"MINIFICATION_SUCCESS" => "Successfully minified and concatenated CSS and JS for all page groups."
));

// Forgot Password
$lang = array_merge($lang,array(
	"FORGOTPASS_INVALID_TOKEN" => "Jeton d'activation non valide",
	"FORGOTPASS_OLD_TOKEN" => "Jeton d'activation expiré",
	"FORGOTPASS_COULD_NOT_UPDATE" => "La mise à jour du mot de passe a échoué",
	"FORGOTPASS_NEW_PASS_EMAIL" => "Un email contenant votre nouveau mot de passe a été envoyé",
	"FORGOTPASS_REQUEST_CANNED" => "Requête de récupération de mot de passe annulée",
	"FORGOTPASS_REQUEST_EXISTS" => "Il y a déjà une demande de réinitialisation de mot de passe en cours pour ce compte",
	"FORGOTPASS_REQUEST_SUCCESS" => "Nous vous avons envoyé un mail expliquant comment récupérer l'accès à votre compte"
));

// Mail
$lang = array_merge($lang,array(
	"MAIL_ERROR" => "Erreur durant l'envoi de mail",
));

// Miscellaneous
$lang = array_merge($lang,array(
	"PASSWORD_HASH_FAILED" => "Erreur lors du hashage du mot de passe.",
	"NO_DATA" => "Pas de données envoyées",
	"CAPTCHA_FAIL" => "Echec de la saisie du Captcha",
	"CONFIRM" => "Confirmer",
	"DENY" => "Bloquer",
	"SUCCESS" => "Succès",
	"ERROR" => "Erreur",
	"SERVER_ERROR" => "Erreur interne du serveur.",
	"NOTHING_TO_UPDATE" => "Rien a mettre à jour",
	"SQL_ERROR" => "Erreur SQL",
	"FEATURE_DISABLED" => "Cette fonctionnalité est désactivé",
	"ACCESS_DENIED" => "Vous n'avez pas le droit d'afficher cette page.",
	"LOGIN_REQUIRED" => "Vous devez être connecté pour accéder à cette page.",
	"LOGIN_ALREADY_COMPLETE" => "Vous êtes déjà connectés!"
));

// Permissions
$lang = array_merge($lang,array(
	"GROUP_INVALID_ID" => "L'ID de groupe demandé n'existe pas",
	"GROUP_NAME_CHAR_LIMIT" => "Le nom de groupe doit comprendre entre {{min}} et {{max}} caractères",
	"GROUP_NAME_IN_USE" => "Le nom de groupe '{{name}}' est déjà utilisé",
	"GROUP_DELETION_SUCCESSFUL" => "Le groupe '{{name}} a été supprimé'",
	"GROUP_CREATION_SUCCESSFUL" => "Le groupe '{{name}} a été créé'",
	"GROUP_UPDATE" => "Les détails du group '{{name}}' ont été mis à jour.",
	"CANNOT_DELETE_GROUP" => "Le groupe '{{name}}' ne peut pas être supprimé",
	"GROUP_CANNOT_DELETE_DEFAULT_PRIMARY" => "Le groupe '{{name}}' ne peut pas être supprimé car c'est le groupe principal par défaut des nouveaux utilisateurs."
));

return $lang;
