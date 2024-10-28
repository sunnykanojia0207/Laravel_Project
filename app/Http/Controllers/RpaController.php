<?php
use App\Events\RpaLogBroadcast;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RpaController extends Controller
{
    public function runRpaProcess()
    {
        $scriptPath = storage_path('C:/xampp/htdocs/laravel10-vue3-main/laravel10-vue3-main/rpa_download/main.py');
        $process = new Process(['python', $scriptPath]);

        // Execute the process and capture output
        $process->run(function ($type, $buffer) {
            if ($process::OUT === $type) {
                // Broadcast each print statement from the bot
                broadcast(new RpaLogBroadcast($buffer));  // Sends log to the front end
            } else {
                // You can also handle errors and broadcast them
                broadcast(new RpaLogBroadcast("Error: " . $buffer));
            }
        });

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return response()->json(['status' => 'RPA Process Started']);
    }
}
