<?php

namespace GeorgRinger\News\Tests\Unit\ViewHelpers;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */


/**
 * Test for SimplePrevNextViewHelper
 */
class SimplePrevNextViewHelperTest extends \TYPO3\CMS\Fluid\Tests\Unit\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface|\GeorgRinger\News\ViewHelpers\SimplePrevNextViewHelper
	 */
	protected $viewHelper;

	/**
	 * Set up
	 */
	public function setUp() {
		parent::setUp();

		$this->viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\SimplePrevNextViewHelper', array('dummy'));

		$mockedDatabaseConnection = $this->getMock('TYPO3\\CMS\\Core\\Database\\DatabaseConnection', array('exec_SELECTgetSingleRow'));
		$this->viewHelper->_set('databaseConnection', $mockedDatabaseConnection);
	}

	/**
	 * @test
	 */
	public function wrongIdWillReturnNullForObject() {
		$out = $this->viewHelper->_call('getObject', 0);
		$this->assertEquals($out, NULL);
	}

	/**
	 * @test
	 */
	public function queryResultWillReturnCorrectOutputForAllLinks() {
		$viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\SimplePrevNextViewHelper', array('getObject'));

		$in = array(
			0 => array('uid' => 123),
			1 => array('uid' => 456),
			2 => array('uid' => 789),
		);
		$exp = array('prev' => 123, 'next' => 789);
		$viewHelper->expects($this->at(0))->method('getObject')->will($this->returnValue(123));
		$viewHelper->expects($this->at(1))->method('getObject')->will($this->returnValue(789));
		$out = $viewHelper->_call('mapResultToObjects', $in);
		$this->assertEquals($out, $exp);
	}

	/**
	 * @test
	 */
	public function queryResultWillReturnCorrectOutputFor2Links() {
		$viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\SimplePrevNextViewHelper', array('getObject'));

		$in = array(
			0 => array('uid' => 147),
			1 => array('uid' => 258),
		);
		$exp = array('prev' => 147);
		$viewHelper->expects($this->at(0))->method('getObject')->will($this->returnValue(147));
		$out = $viewHelper->_call('mapResultToObjects', $in);
		$this->assertEquals($out, $exp);
	}

	/**
	 * @test
	 */
	public function queryResultWillReturnCorrectOutputFor1Link() {
		$viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\SimplePrevNextViewHelper', array('getObject'));

		$in = array(
			0 => array('uid' => 369),
		);
		$exp = array('next' => 369);
		$viewHelper->expects($this->at(0))->method('getObject')->will($this->returnValue(369));
		$out = $viewHelper->_call('mapResultToObjects', $in);
		$this->assertEquals($out, $exp);
	}

	/**
	 * @test
	 * @expectedException \UnexpectedValueException
	 */
	public function queryResultWillReturnExceptionForUnknownCount() {
		$viewHelper = $this->getAccessibleMock('GeorgRinger\\News\\ViewHelpers\\SimplePrevNextViewHelper', array('getObject'));

		$in = array(
			0 => array('uid' => 369),
			1 => array('uid' => 369),
			2 => array('uid' => 369),
			3 => array('uid' => 369),
		);
		$out = $viewHelper->_call('mapResultToObjects', $in);
	}

}