<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected array $keys = [
        'APP_NAME',
        'APP_LOGO',
        'PAKASIR_PROJECT_SLUG',
        'PAKASIR_API_KEY',
        'MAIL_MAILER',
        'MAIL_SCHEME',
        'MAIL_HOST',
        'MAIL_PORT',
        'MAIL_USERNAME',
        'MAIL_PASSWORD',
        'MAIL_FROM_ADDRESS',
        'MAIL_FROM_NAME',
    ];

    public function index()
    {
        $data = [];
        foreach ($this->keys as $k) {
            $data[$k] = env($k, '');
        }

        return view('settingsmanagement', ['values' => $data]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'APP_NAME' => 'required|string|max:255',
            'PAKASIR_PROJECT_SLUG' => 'nullable|string|max:255',
            'PAKASIR_API_KEY' => 'nullable|string|max:1024',
            'MAIL_MAILER' => 'required|string|max:50',
            'MAIL_SCHEME' => 'nullable|string|max:20',
            'MAIL_HOST' => 'required|string|max:255',
            'MAIL_PORT' => 'nullable|integer',
            'MAIL_USERNAME' => 'nullable|string|max:255',
            'MAIL_PASSWORD' => 'nullable|string|max:1024',
            'MAIL_FROM_ADDRESS' => 'nullable|email|max:255',
            'MAIL_FROM_NAME' => 'nullable|string|max:255',
            'APP_LOGO' => 'nullable|image|max:4096',
        ]);

        // Default values if not provided
        if (empty($validated['MAIL_PORT'])) {
            $validated['MAIL_PORT'] = 587;
        }
        if (empty($validated['MAIL_FROM_NAME'])) {
            $validated['MAIL_FROM_NAME'] = '${APP_NAME}';
        }

        // Handle logo upload separately
        if ($request->hasFile('APP_LOGO')) {
            // Delete previous logo file if present
            $current = env('APP_LOGO', '');
            if ($current) {
                $toDelete = null;
                if (str_starts_with($current, '/storage/')) {
                    $toDelete = substr($current, strlen('/storage/'));
                } elseif (preg_match('#/storage/#', $current)) {
                    $parts = explode('/storage/', $current);
                    $toDelete = end($parts);
                }

                if ($toDelete) {
                    try {
                        Storage::disk('public')->delete($toDelete);
                    } catch (\Throwable $e) {
                        // ignore delete errors
                    }
                }
            }

            $file = $request->file('APP_LOGO');
            // store on the public disk under 'logos' so Storage::url returns /storage/logos/...
            $path = $file->store('logos', 'public');
            // generate public URL
            $url = Storage::disk('public')->url($path);
            $validated['APP_LOGO'] = $url;
        }

        $errors = [];
        foreach ($this->keys as $k) {
            $value = $validated[$k] ?? '';
            try {
                $this->setEnvValue($k, $value);
            } catch (\Throwable $e) {
                Log::error('Failed to write .env key ' . $k . ': ' . $e->getMessage());
                $errors[] = $k;
            }
        }

        // clear config cache so changes take effect
        try {
            Artisan::call('config:clear');
        } catch (\Throwable $e) {
            // non-fatal
        }

        if (!empty($errors)) {
            return redirect()->back()->with('error', 'Gagal menyimpan beberapa pengaturan: ' . implode(', ', $errors));
        }

        return redirect()->back()->with('status', 'settings-saved');
    }

    public function deleteLogo(Request $request)
    {
        $current = env('APP_LOGO', '');
        if ($current) {
            // If URL contains '/storage/', map to storage path under public disk
            $toDelete = null;
            if (str_starts_with($current, '/storage/')) {
                $toDelete = substr($current, strlen('/storage/'));
            } elseif (preg_match('#/storage/#', $current)) {
                $parts = explode('/storage/', $current);
                $toDelete = end($parts);
            }

            if ($toDelete) {
                // delete from public disk
                try {
                    Storage::disk('public')->delete($toDelete);
                } catch (\Throwable $e) {
                    // ignore delete errors
                }
            }

            // clear env value
            try {
                $this->setEnvValue('APP_LOGO', '');
            } catch (\Throwable $e) {
                Log::error('Failed to clear APP_LOGO: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal menghapus logo.');
            }
        }

        try { Artisan::call('config:clear'); } catch (\Throwable $e) {}
        return redirect()->back()->with('status', 'logo-deleted');
    }

    private function setEnvValue(string $key, $value): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath) || !is_writable($envPath)) {
            throw new \RuntimeException('.env file tidak ditemukan atau tidak dapat ditulis.');
        }

        $content = file_get_contents($envPath);
        $escaped = str_replace('"', '\\"', (string)$value);

        // Quote values that contain spaces or special characters
        if (preg_match('/\s/', $escaped) || $escaped === '') {
            $replacement = $key . '="' . $escaped . '"';
        } else {
            $replacement = $key . '=' . $escaped;
        }

        if (preg_match('/^' . preg_quote($key, '/') . '=/m', $content)) {
            // replace existing line
            $content = preg_replace('/^' . preg_quote($key, '/') . '=.*$/m', $replacement, $content);
        } else {
            // append
            $content = rtrim($content, "\n") . "\n" . $replacement . "\n";
        }

        file_put_contents($envPath, $content, LOCK_EX);
    }
}
