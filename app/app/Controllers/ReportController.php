<?php namespace App\Controllers;

use App\Services\Report;
use View, Exception, App;

class ReportController extends BaseController {

    public function __construct(Report $report) {
        $this->report = $report;
    }

    public function index($report) {

        $name = 'App\Reports\\' . ucfirst(camel_case($report) . 'Report');

        // try {
            return $this->report->make($name);
        // } catch (Exception $e) {
            // return App::abort(404);
        // }
    }

}