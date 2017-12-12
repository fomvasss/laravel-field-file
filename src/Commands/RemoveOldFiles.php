<?php

namespace Fomvasss\FieldFile\Commands;

use Fomvasss\FieldFile\FieldFileServiceProvider;
use Fomvasss\FieldFile\Managers\BaseFileManager;
use Illuminate\Console\Command;

/**
 * Class TaxonomyMigrate
 *
 * @package \Fomvasss\Taxonomy
 */
class RemoveOldFiles extends Command
{
    protected $signature = 'field-file:remove
            {over : Over hours unused files}
            {--queue= : Whether the job should be queued}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all original old files with files db and disk';
    /**
     * Create a new command instance.
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();

        $overHour = $this->argument('over');

        $this->runRemove($overHour);
    }

    protected function runRemove($overHour)
    {
        $fileManager = app()->make(BaseFileManager::class);

        $fileManager->deleteAllOldNonUsed($overHour);
    }

}
