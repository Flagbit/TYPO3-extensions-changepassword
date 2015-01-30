<?php
namespace Flagbit\FbChangepassword\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Vanessa Grunz <vanessa.grunz@flagbit.de>, Flagbit GmbH & Co. KG
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class Flagbit\FbChangepassword\Controller\FrontendUserController.
 *
 * @author Vanessa Grunz <vanessa.grunz@flagbit.de>
 */
class FrontendUserControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Flagbit\FbChangepassword\Controller\FrontendUserController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Flagbit\\FbChangepassword\\Controller\\FrontendUserController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllFrontendUsersFromRepositoryAndAssignsThemToView() {

		$allFrontendUsers = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$frontendUserRepository = $this->getMock('', array('findAll'), array(), '', FALSE);
		$frontendUserRepository->expects($this->once())->method('findAll')->will($this->returnValue($allFrontendUsers));
		$this->inject($this->subject, 'frontendUserRepository', $frontendUserRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('frontendUsers', $allFrontendUsers);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}
}
