<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CommandTest2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Test2';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('这仅仅是个确认测试：');

        $result = $this->confirm('Do you really wish to run this command?', true);

        $this->line('Your input is '.($result ? 'yes' : 'no').'.');
    }
}
