<?php
namespace DMA\Friends\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\Command;
use DB;

/**
 * Set a cron task that will reset the points all users have earned for the week back to zero
 *
 * @package DMA\Friends\Commands
 * @author Kristen Arnold, Carlos Arroyo
 */
class DailyPoints extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'friends:points-daily';

    /**
     * Set the number of points set per week back to zero on sunday morning
     * @return void
     */
    public function fire()
    {
        DB::table('users')
            ->update(['points_today' => 0]);
    }
}