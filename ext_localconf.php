<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use WapplerSystems\FrontendChangePassword\Controller\UserController;

ExtensionUtility::configurePlugin(
    'fe_changepw',
    'Password',
    [
        UserController::class => 'password',
    ],
    [
        UserController::class => 'password',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);


ExtensionManagementUtility::addTypoScriptSetup(
    'plugin.tx_form {
    settings {
        yamlConfigurations {
            1738878704 = EXT:fe_changepw/Configuration/Yaml/FormSetup.yaml
        }
    }
}'
);
