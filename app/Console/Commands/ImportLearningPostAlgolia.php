<?php

namespace App\Console\Commands;

use App\Models\Admin\Post;
use Illuminate\Console\Command;

class ImportLearningPostAlgolia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:import-learning-posts-algolia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importing Learning posts description';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Importing Learning posts');
        Post::where('post_type', 'knowledge-center')
        // ->where('locale', 'en')
        ->orWhere(function($query){
            $query->where('post_type', 'about-us')
            ->where('group_slug', 'learning');
        })->get()->searchable();
        $this->info('Posts Learning imported');
    }
}
