<?php namespace App\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Helper\Table;
use Illuminate\Foundation\Application;
use DB;

class ConfigCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'nut:config';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	protected $keys = [
		'site.allow_pw_reset',
		'site.user_registration',
		'site.date_time_format'
	];

	private $config = [];

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Application $app)
	{
		parent::__construct();
		// $this->memory = $app['orchestra.memory']->make();
		// $this->memory = App::make('orchestra.memory')->make();
		$this->app = $app;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{	
		$this->config = $this->getConfig();

		$opts = $this->option();

		if ($opts['forget']) {

			if (isset(array_dot($this->config)[$opts['forget']])) {

				$keys = explode('.', $opts['forget']);

				array_forget($this->config, $opts['forget']);

				if (empty($this->config[$keys[0]])) {

					DB::table('config')
						->where('name', $keys[0])
						->delete();

				} else {

					DB::table('config')
					->where('name', $keys[0])
					->update(['value' => serialize($this->config[$keys[0]])]);

				}

				$this->info($opts['forget'] . ' removed.');
			}

			return $this->showList();

			
		}

		if ($opts['key'] && $opts['value']) {

			$value = trim($opts['value']);
			if (in_array($value, ['true', 'false', '0', '1'])) {
				$value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
			}

			$old_config = $this->config;
			array_set($this->config, $opts['key'], $value);

			$keys = explode('.', $opts['key']);

			if (isset($old_config[$keys[0]])) {
				//update
				DB::table('config')
					->where('name', $keys[0])
					->update(['value' => serialize($this->config[$keys[0]])]);
			} else {
				//insert
				DB::table('config')
					->insert(['name' => $keys[0], 'value' => serialize($this->config[$keys[0]])]);
			}
		}

		return $this->showList();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('key', 'k', InputOption::VALUE_REQUIRED, 'Key to edit.', null),
			array('value', 'l', InputOption::VALUE_REQUIRED, 'New value for key.', null),
			array('forget', 'f', InputOption::VALUE_REQUIRED, 'Delete/forget key.', null)
		);
	}

	private function getTable() {
		return new Table($this->output);
	}

	private function getConfig() {

		$rows = DB::table('config')->get();
		$config = [];
		
		foreach ($rows as $row) {
			$config[$row->name] = unserialize($row->value);
		}

		return $config;

	}

	private function showList() {

		$table = $this->getTable();
		$table->setHeaders(['Key', 'Value']);
		$data = [];
		// dd(array_dot($this->memory->all()));
		foreach (array_dot($this->config) as $key => $value) {
			$data[] = [$key, is_bool($value) ? var_export($value, true) : $value];
		}

		$table->setRows($data);
		$table->render();
	}

}
