<?php namespace App\Services;

use App\Repositories\ReportInterface;
use App\Validators\ReportValidator;
use View, Paginator, Request, Validator, Exception;

class Report {

	protected $config;

	protected $variable_types = ['daterange'];

	public function __construct(ReportInterface $db, ReportValidator $validator) {
		$this->db = $db;
		$this->validator = $validator;
	}

	public function make($report, $type = 'html') {

		if (!class_exists($report)) {
			throw new Exception('Report class does not exit');
		}

		$class = $this->createReportClass($report);

		return $this->{'make' . ucfirst($type)}($class);

	}

	public function setConfig(array $config, $overwrite = false) {

	}

	public function build() {

	}

	private function createReportClass($class_name) {
		return new $class_name;
	}

	protected function makeHtml($class) {

		$params = Request::query();

		$validator = Validator::make($params, $this->buildRules($class->getVariables()));

		if ($validator->fails()) {
		 	$params = [];
		 }

		$data = $class->make($params, $this->db);
		$data['errors'] = $validator->errors();
		return View::make('reports.index', $data);
	}

	// protected function validate($class->, $params) {

	// 	$rules = $this->buildRules($class);

	// 	return null;

	// }

	protected function buildRules($variables) {

		$rules = [];

		$validation_rules = $this->validator->getRules();

		foreach ($variables as $var) {

			if (isset($var['rule'])) {

				$rules[$var['name']] = $var['rule'];

			} elseif (in_array($var['type'], $this->variable_types)) {

				$rules[$var['name']] = $validation_rules[$var['type']];
			
			}

		}

		return $rules;

	}

}