<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpireMedications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-medications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredMedications = \App\Models\Medication::where('status', 'active')
            ->where('end_date', '<', now()->toDateString())
            ->with(['child.caregivers'])
            ->get();

        if ($expiredMedications->isEmpty()) {
            $this->info('No expired medications found.');
            return;
        }

        foreach ($expiredMedications as $medication) {
            // Update status to completed (auto set to blank in active lists)
            $medication->update(['status' => 'completed']);

            // Notify assigned caregivers
            $caregivers = $medication->child->caregivers;
            
            if ($caregivers->isNotEmpty()) {
                \Illuminate\Support\Facades\Notification::send($caregivers, new \App\Notifications\MedicationExpiredNotification($medication));
            }

            $this->info("Processed medication: {$medication->medication_name} for {$medication->child->first_name}");
        }

        $this->info('Successfully processed ' . $expiredMedications->count() . ' medications.');
    }
}
