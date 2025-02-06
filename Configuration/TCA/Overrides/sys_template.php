<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

call_user_func(function () {
// Adding fields to the tt_content table definition in TCA
    ExtensionManagementUtility::addStaticFile(
        'fe_changepw',
        'Configuration/TypoScript',
        'Change frontend user password'
    );
});
