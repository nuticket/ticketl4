<?php namespace App\Repositories\DB;

use App\Repositories\ReportInterface;
use Lex\Parser;
use DB;

class ReportRepository implements ReportInterface {

	public function __construct(Parser $parser) {
		$this->parser = $parser;
	} 

	public function query($sql, $params = []) {

		$params = array_map(function($value) {
			return DB::getPdo()->quote($value);
		}, $params);
		
		return DB::select(DB::raw($this->parser->parse($sql, $params)));
	}
	
}