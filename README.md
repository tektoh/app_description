アプリのストアページから情報を取ってくるCakephp のプラグイン
=====================================================

[![Build Status](https://travis-ci.org/tektoh/app_description.svg)](https://travis-ci.org/tektoh/app_description)

## 使い方

### database.php
```php
class DATABASE_CONFIG {
	public $appDescription = [
		'datasource' => 'AppDescription.AppDescriptionSource',
	];
```

### Model
```php
class Test extends AppModel
{
    public $useDbConfig = 'appDescription';
    public $useTable = false;
}
```

### Controller
```php
class TestsController extends AppController {
	public $uses = [
        'Test',
    ];
    public function index() {
        $test = $this->Test->find('first', [
            'conditions' => [
                'url' => 'https://itunes.apple.com/jp/app/youtube/id544007664?mt=8'
            ]
        ]);
        debug($test);
    }
}
```
