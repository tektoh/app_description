<?php

class AllTests extends PHPUnit_Framework_TestSuite {

	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('All Tests');

		$path = App::pluginPath('AppDescription') . 'Test' . DS . 'Case' . DS;

		$suite->addTestFile($path . 'Lib'. DS . 'GooglePlayApiTest.php');
		$suite->addTestFile($path . 'Model'. DS . 'Datasource' . DS . 'AppDescriptionSourceTest.php');
		return $suite;
	}
}
