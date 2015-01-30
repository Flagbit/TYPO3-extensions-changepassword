<?php
namespace Flagbit\FbChangepassword\Validation\Validator;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Vanessa Grunz <vanessa.grunz@flagbit.de>, Flagbit GmbH & Co. KG
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * PasswordValidator
 */
class PasswordValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator {


	/**
	 * @var string
	 */
	private $extensionName = 'FbChangepassword';

	/**
	 * Check if $value is valid. If it is not valid, needs to add an error
	 * to Result.
	 *
	 * @param array $value
	 * @return bool
	 */
	protected function isValid($value) {

        $oldPassword = $GLOBALS['TSFE']->fe_user->user['password'];
        $instanceSalted = \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance();


		if(array_key_exists('minLength', $this->options)) {
			$minimumLength = $this->options['minLength'];
		} else {
			$minimumLength = 7;
		}

        // check if old password correct
        if ($instanceSalted->isValidSaltedPW($oldPassword) && ! $instanceSalted->checkPassword($value[0], $oldPassword)) {
            $this->addError('Old password is wrong.', time());
            $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', \TYPO3\CMS\extbase\Utility\LocalizationUtility::translate('tx_fbchangepassword_oldPassword_required', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            \TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
            return false;
        }

		// check for empty password
		if ($value[1] === '') {
			$this->addError('Password must not be empty.', time());
			$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', \TYPO3\CMS\extbase\Utility\LocalizationUtility::translate('tx_fbchangepassword_updatePassword_required', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			\TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
            return false;
		}

		// check for password length
		if (strlen($value[1]) < $minimumLength) {
			$this->addError('Minimum length is '.$minimumLength.' characters.', time());
			$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', sprintf(\TYPO3\CMS\extbase\Utility\LocalizationUtility::translate('tx_fbchangepassword_updatePassword_minLength', $this->extensionName), $minimumLength), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			\TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
			return false;
		}

        // check for new password not the same as oldpassword
        if ($instanceSalted->checkPassword($value[1], $oldPassword)) {
            $this->addError('New Password must not be the same as old password.', time());
            $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', \TYPO3\CMS\extbase\Utility\LocalizationUtility::translate('tx_fbchangepassword_updatePassword_notTheSame', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            \TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
            return false;
        }

		// check that the passwords are the same
		if (strcmp($value[1], $value[2]) != 0) {
			$this->addError('Passwords do not match.', time());
			$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', \TYPO3\CMS\extbase\Utility\LocalizationUtility::translate('tx_fbchangepassword_updatePassword_noMatch', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			\TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
			return false;
		}

        // check for empty repeated password
        if ($value[2] === '') {
            $this->addError('Repeated password must not be empty.', time());
            $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', \TYPO3\CMS\extbase\Utility\LocalizationUtility::translate('tx_fbchangepassword_repeatedPassword_required', $this->extensionName), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            \TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
            return false;
        }
	}
}