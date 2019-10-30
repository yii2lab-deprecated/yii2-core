<?php

namespace yii2lab\core\domain\repositories\base;

use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\domain\data\query\Rest;
use yii2lab\domain\interfaces\repositories\ModifyInterface;
use yii2lab\domain\interfaces\repositories\ReadInterface;

class BaseActiveCoreRepository extends BaseCoreRepository implements ReadInterface, ModifyInterface {
	
	public function all(Query $query = null) {
		$params = $this->getQueryParams($query);
		$responseEntity = $this->get(null, $params);
		return $this->forgeEntity($responseEntity->data);
	}
	
	public function count(Query $query = null) {
		$params = $this->getQueryParams($query);
		$responseEntity = $this->get(null, $params);
		return $responseEntity->headers['x-pagination-total-count'];
	}
	
	public function one(Query $query = null) {
		/** @var Query $query */
		$query = Query::forge($query);
		$collection = $this->all($query);
		if(empty($collection)) {
			throw new NotFoundHttpException(static::class);
		}
		return $collection[0];
	}
	
	public function oneById($id, Query $query = null) {
		$responseEntity = $this->get($id);
		return $this->forgeEntity($responseEntity->data);
	}
	
	public function insert(BaseEntity $entity) {
		$this->post(null, $entity->toArray());
	}
	
	public function update(BaseEntity $entity) {
		$id = $this->getIdFromEntity($entity);
		$this->put($id, $entity->toArray());
	}
	
	public function delete(BaseEntity $entity) {
		$id = $this->getIdFromEntity($entity);
		$this->del($id);
	}
	
	private function getQueryParams(Query $query = null) {
		$query = Query::forge($query);
		$restQuery = new Rest;
		$restQuery->query = $query;
		return $restQuery->getParams();
	}
	
	private function getIdFromEntity(BaseEntity $entity) {
		$id = $entity->{$this->primaryKey};
		return $id;
	}
	
}
