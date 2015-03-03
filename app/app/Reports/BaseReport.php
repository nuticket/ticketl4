<?php namespace App\Reports;

use Carbon\Carbon;
use Illuminate\Support\Str;

class BaseReport {

	public $title = 'No Title';

	public $description = 'No Description';

	protected $variables = [];

	private $query_params;

	private $sql_params;

	protected $sql;

	protected $data;

	public function __construct() {
	}

	public function make(array $query, $db) {

		$this->data = [
			'title' => $this->title,
			'slug' => Str::slug($this->title)
		];

		$this->query_params = $query;
		$this->parseVaribles();

		$this->data['results'] = $db->query($this->sql, $this->sql_params);

		return $this->data;
	}

	public function getVariables() {
		return $this->variables;
	}

	protected function parseVaribles() {

		foreach ($this->variables as $var) {

			$this->{'variable' . ucfirst($var['type'])}($var);			
		}
	}

	protected function variableDaterange($config) {

		//format start/end dates
		if (isset($this->query_params[$config['name']])) { 
	
			$value = $this->query_params[$config['name']];
			$range = explode('-', $this->query_params[$config['name']]);
			$start = Carbon::createFromFormat('m/d/Y', trim($range[0]))->startOfDay()->toDateTimeString();
			$end = Carbon::createFromFormat('m/d/Y', trim($range[1]))->endOfDay()->toDateTimeString(); 

		} else {

			$start = $this->parseCarbon($config['default']['start']);
			$end = $this->parseCarbon($config['default']['end']);
			$value = $start->format('m/d/Y') . ' - ' . $end->format('m/d/Y');
		}

		//add to sql params
		$this->sql_params[$config['name'] . '_start'] = $start;
		$this->sql_params[$config['name'] . '_end'] = $end;

		//viewdata
		$this->data['header'][] = ['name' => $config['name'], 'value' => $value, 'type' => 'daterange'];

	}

	protected function parseCarbon($config) {

		$carbon = new Carbon;

		foreach ($config as $method) {
			$carbon->{$method}();
		}

		return $carbon;
	}

}