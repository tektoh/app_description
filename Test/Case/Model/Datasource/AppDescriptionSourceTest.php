<?php

App::uses('AppDescriptionSource', 'AppDescription.Model.Datasource');
App::uses('AppDescriptionApi', 'AppDescription.Lib');
App::uses('ConnectionManager', 'Model');

ConnectionManager::create('test_appDescription', [
    'datasource' => 'AppDescription.AppDescriptionSource',
    'testvendor' => [
        'host' => 'test.local',
        'apiClass' => 'AppDescriptionTestApi'
    ]
]);

class AppDescriptionTestModel extends CakeTestModel
{
    public $useDbConfig = 'test_appDescription';
    public $useTable = false;
}

class AppDescriptionTestApi extends AppDescriptionApi
{
    public function lookup($url)
    {
        return ['dummy' => 'data'];
    }
}

class AppDescriptionSourceTestCase extends CakeTestCase {

    public function setUp()
    {
        parent::setUp();
        $this->Model = ClassRegistry::init('AppDescriptionTestModel');
	}

	public function tearDown()
	{
		parent::tearDown();
        ClassRegistry::flush();
		unset($this->Model);
	}

    public function testFindAll()
    {
        $result = $this->Model->find('all', [
            'conditions' => [
                'url' => 'http://test.local/hoge/hoge/12345'
            ]
        ]);
        $expected = [['dummy' => 'data']];
        $this->assertEquals($expected, $result);
    }

    public function testFindFirst()
    {
        $result = $this->Model->find('first', [
            'conditions' => [
                'url' => 'http://test.local/hoge/hoge/12345'
            ]
        ]);
        $expected = ['dummy' => 'data'];
        $this->assertEquals($expected, $result);
        
        $result = $this->Model->find('first', [
            'conditions' => [
                'hoge' => 'http://test.local/hoge/hoge/12345'
            ]
        ]);
        $expected = [];
        $this->assertEquals($expected, $result);
    }
}
