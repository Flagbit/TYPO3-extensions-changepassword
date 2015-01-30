<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Flagbit.' . $_EXTKEY,
	'Pi1',
	array(
		'FrontendUser' => 'list, save, ',
		
	),
	// non-cacheable actions
	array(
		'FrontendUser' => 'list, save',
		
	)
);
