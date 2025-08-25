<?php

namespace App\Console\Commands;

use App\Models\Box;
use App\Mail\TaskCompletedMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BoxScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'box:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the box scheduler to double the number of boxes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentCount = Box::count();

        // If no boxes exist, create the first one
        if ($currentCount === 0) {
            $this->createInitialBox();
            $this->info('Created initial box');
            return;
        }

        // If we've reached 16 boxes, stop the scheduler and send email
        if ($currentCount >= 16) {
            $this->info('Box limit reached (16). Sending completion email...');
            $this->sendCompletionEmail($currentCount);
            return;
        }

        // Double the number of boxes
        $boxesToCreate = $currentCount;
        $colors = ['red', 'yellow', 'green', 'blue', 'pink', 'grey'];

        for ($i = 0; $i < $boxesToCreate; $i++) {
            Box::create([
                'height' => 40,
                'width' => 100,
                'color' => $colors[array_rand($colors)]
            ]);
        }

        $newCount = Box::count();
        $this->info("Boxes doubled from {$currentCount} to {$newCount}");

        // Log the activity
        Log::info("Box scheduler ran: {$currentCount} -> {$newCount} boxes");

        // Check if we've reached the limit after doubling
        if ($newCount >= 16) {
            $this->info('Box limit reached (16). Sending completion email...');
            $this->sendCompletionEmail($newCount);
        }
    }

    /**
     * Create the initial box
     */
    private function createInitialBox()
    {
        $colors = ['red', 'yellow', 'green', 'blue', 'pink', 'grey'];

        Box::create([
            'height' => 40,
            'width' => 100,
            'color' => $colors[array_rand($colors)]
        ]);
    }

    /**
     * Send completion email when 16 boxes are reached
     */
    private function sendCompletionEmail(int $boxCount)
    {
        try {
            // Send email to Dawood.ahmed@collaborak.com with your full name
            Mail::to('Dawood.ahmed@collaborak.com')->send(
                new TaskCompletedMail($boxCount, 'Hurak') // Replace 'Muhammad Ahmad' with your actual full name
            );

            $this->info('Completion email sent successfully!');
            Log::info("Completion email sent for {$boxCount} boxes");
        } catch (\Exception $e) {
            $this->error('Failed to send completion email: ' . $e->getMessage());
            Log::error('Failed to send completion email: ' . $e->getMessage());
        }
    }
}
