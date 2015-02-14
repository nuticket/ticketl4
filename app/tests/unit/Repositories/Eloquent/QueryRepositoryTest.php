<?php

// use App\Repositories\Eloquent\QueryRepository;
use Illuminate\Database\Eloquent\Model;
use Mockery as m;

class QueryRepositoryTest extends TestCase {

	public function testCreatedAt() {
		Request::shouldReceive('has')->once()->andReturn(true);
		Request::shouldReceive('get')->with('created_at')->once()->andReturn('10/01/2014-10/31/2014');

		$repo = $this->getRepo();
		$repo->model = new App\Ticket;

		$return = $repo->createdAt();

		$this->assertEquals(["2014-10-01 00:00:00", "2014-10-31 23:59:59"], $repo->model->getBindings());
	}

	public function testSearchOneTerm() {
		Request::shouldReceive('has')->once()->andReturn(true);
		Request::shouldReceive('get')->with('q')->once()->andReturn('phone');

		$repo = $this->getRepo();
		$repo->setSearchCols(['id', 'subject', 'status']);
		$repo->model = new App\Ticket;

		$return = $repo->search();

		$this->assertEquals($repo->model->getBindings(), ['%phone%', '%phone%', '%phone%']);
	}

	public function testSearchMultipleTerm() {
		Request::shouldReceive('has')->once()->andReturn(true);
		Request::shouldReceive('get')->with('q')->once()->andReturn('ltc-print-alamo-errors');

		$repo = $this->getRepo();
		$repo->setSearchCols(['id', 'subject', 'status']);
		$repo->model = new App\Ticket;

		$return = $repo->search();

		$this->assertCount(12, $repo->model->getBindings());
	}

	public function testFilter() {

		$repo = $this->getRepo();
		$repo->model = 'hoo';

		$repo->shouldReceive('createdAt')->once();
		$repo->shouldReceive('sortOrder')->once();
		$repo->shouldReceive('search')->once();
		$repo->shouldReceive('where')->once();

		$repo->filter();

		$this->assertEquals('hoo', $repo->model);
	}

	public function testCustom() {
		$repo = m::mock('App\Repositories\Eloquent\QueryRepository')
			->makePartial()
			->shouldAllowMockingProtectedMethods();

		$repo->custom('callback', function() {
			return 'called';
		});

		//no way to test yet

	}

	public function testSortOrderWithDefaults() {
		$repo = $this->getRepo();

		$repo->model = new App\Ticket;

		$repo->sortOrder();

		$this->assertEquals('select * from `tickets` order by `id` desc', $repo->model->toSql());
	}

	public function testSortOrderWithRequests() {
		Request::shouldReceive('get')->twice()->andReturn('description', 'asc');

		$repo = $this->getRepo();

		$repo->model = new App\Ticket;

		$repo->sortOrder();

		$this->assertEquals('select * from `tickets` order by `description` asc', $repo->model->toSql());
	}

	private function getRepo() {
		return m::mock('App\Repositories\Eloquent\QueryRepository')
			->makePartial()
			->shouldAllowMockingProtectedMethods();
	}

}
