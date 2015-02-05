<?php namespace App\Support;

// use Eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Elegant extends Eloquent {

	public static function fromQuery($query) {

    	$model = new static;

    	$model = $model->callModelQuries(array_except($query, ['with', 'sort', 'order', '_url', 'page']), $model);

    	$query['per_page'] = isset($query['per_page']) ? $query['per_page'] : null;

		//fields
		if (isset($query['field'])) {
			$fields = explode(',', $query['field']);
			$model = $model->select(array_merge($fields, ['id']));	

		//with
		} 

		if (isset($query['with'])) {

			$withs = explode(',', $query['with']);
			// dd($model->getTable());

			foreach ($withs as $with) {
				$model = $model->with($with);
			}
		} 

		if (isset($query['sort']) && isset($query['order'])) {

			$model = $model->orderBy($query['sort'], $query['order']); 

		}		

		return $model->paginate($query['per_page']);
    }

    protected function callModelQuries($query, $model) {

    	foreach ($query as $param => $value) {
    		$method = 'query' . ucfirst($param);

    		if (method_exists($model, $method)) {
    			$model = $model->{$method}($model, $value);
    		} else {
    			$model = $model->whereIn($param, explode(',', $value));
    		}
    		
    	}

    	return $model;
    }

}
  