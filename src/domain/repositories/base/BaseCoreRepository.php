<?php

namespace yii2lab\core\domain\repositories\base;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\rest\domain\entities\ResponseEntity;
use yii2lab\rest\domain\repositories\base\BaseRestRepository;

class BaseCoreRepository extends BaseRestRepository {
	
	public $version = 1;
	public $point = EMP;
	
	public function init() {
		if(empty($this->version)) {
			throw new InvalidConfigException('Undefined version in ' . self::class);
		}
		parent::init();
		$this->initBaseUrl();
		$this->initHeaders();
	}
	
	protected function showUserException(ResponseEntity $responseEntity) {
		$statusCode = $responseEntity->status_code;
		if($statusCode == 422) {
			throw new UnprocessableEntityHttpException($responseEntity->data);
		}
		parent::showUserException($responseEntity);
	}
	
	private function _getDomain() {
		$domain = env('servers.core.domain');
		$domain = rtrim($domain, SL);
		return $domain;
	}
	
	private function initBaseUrl() {
		$url = $this->_getDomain();
		if(YII_ENV_TEST) {
			$url .= SL . 'index-test.php';
		}
		$url .= SL . 'v' . $this->version;
		$point = trim($this->point, SL);
		if(!empty($point)) {
			$url .= SL . $point;
		}
		$this->baseUrl = $url;
	}
	
	private function initHeaders() {
		$this->headers = ArrayHelper::merge($this->headers, $this->getHeaders());
	}
	
	private function getHeaders() {
		$headers['Authorization'] = Yii::$app->account->auth->getToken();
		$headers['Language'] = Yii::$app->language;
		return $headers;
	}
}
