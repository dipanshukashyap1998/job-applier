<?php

namespace App\Jobs;

use App\Models\Company;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class SendJobEmail
{
    private const RESUME_DIRECTORY = 'app/public/resumes';

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $companies = Company::query()
            ->where('status', 'pending')
            ->whereNotNull('email')
            // ->whereDate('apply_date', '<=', now()->toDateString())
            ->get();

            Log::info($companies);

        foreach ($companies as $company) {
            $resumePath = $this->resumePathFor($company);

            if ($resumePath === null) {
                Log::warning('No resume found for company ID: ' . $company->id);
                continue;
            }

            try {
                Mail::to($company->email)->send(new JobEmail($company, $resumePath));
                $company->update(['status' => 'applied',
                'apply_date' => Carbon::now()]);
                sleep(2);

            } catch (\Throwable $e) {
                Log::error('Failed to send email for company ID: ' . $company->id . '. Error: ' . $e->getMessage());
            }
        }
    }

    private function resumePathFor(Company $company): ?string
    {
        $resume = null;

        if (preg_match('/\b(php|laravel|backend)\b/i', $company->designation)) {
            $resume = 'dipanshu-resume-backend.pdf';
        } elseif (preg_match('/\b(full\s*stack)\b/i', $company->designation)) {
            $resume = 'dipanshu-resume-fullstack.pdf';
        } else {
            $company->status = 'rejected';
            $company->save();
        }

        if ($resume === null) {
            return null;
        }

        $path = storage_path(self::RESUME_DIRECTORY . DIRECTORY_SEPARATOR . $resume);

        if (! File::exists($path)) {
            Log::warning('Resume file does not exist: ' . $path);

            return null;
        }

        return $path;
    }
}
