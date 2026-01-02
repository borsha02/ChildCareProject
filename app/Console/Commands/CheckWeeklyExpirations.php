<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckWeeklyExpirations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-weekly-expirations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate weekly children whose duration has expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $children = \App\Models\Child::where('status', 'active')
            ->where('package', 'weekly')
            ->whereNotNull('enrollment_date')
            ->whereNotNull('duration')
            ->get();

        $count = 0;
        foreach ($children as $child) {
            $enrollmentDate = \Carbon\Carbon::parse($child->enrollment_date);
            // Calculate expiration date: enrollment date + duration (weeks)
            $expirationDate = $enrollmentDate->copy()->addWeeks($child->duration);

            if ($expirationDate->isPast()) {
                $child->status = 'inactive';
                $child->save();
                $this->info("Deactivated child ID {$child->id} ({$child->first_name} {$child->last_name}) - Expired on {$expirationDate->toDateString()}");
                $count++;
            }
        }

        $this->info("Checked weekly expirations. Deactivated {$count} children.");
    }
}
