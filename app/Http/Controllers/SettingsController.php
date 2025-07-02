<?php

namespace App\Http\Controllers;

use DotEnvEditor\DotenvEditor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{

    /**
     * Show app settings page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setting()
    {
        $environment = config('mail.driver');
        return view('user.admin.app-setting', [
            'environment' => $environment
        ]);
    }

    /**
     * Save mail settings
     * @param Request $request
     */
    public function mailSetting(Request $request)
    {
        $env = new DotenvEditor();
        $env->changeEnv([
            'MAIL_HOST' => $request->get('host'),
            'MAIL_PORT' => $request->get('port'),
            'MAIL_USERNAME' => $request->get('mail_address'),
            'MAIL_PASSWORD' => "'" . $request->get('password') . "'",
            'MAIL_FROM_NAME' => "'" . $request->get('mail_form') . "'",
            'MAIL_ENCRYPTION' => $request->get('encryption') != "" ? $request->get('encryption') : null
        ]);
    }

    /**
     * Save pusher settings
     * @param Request $request
     */
    public function pusherSetting(Request $request)
    {
        $env = new DotenvEditor();
        $env->changeEnv([
            'PUSHER_APP_ID' => $request->get('app_id'),
            'PUSHER_APP_KEY' => $request->get('key'),
            'PUSHER_APP_SECRET' => $request->get('secret'),
            'PUSHER_OPTION_CLUSTER' => $request->get('cluster'),
            'PUSHER_OPTION_ENCRYPTED' => $request->get('encrypted')
        ]);

    }

    /**
     * Save database settings
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dbSetting(Request $request)
    {
        $env = new DotenvEditor();
        $env->changeEnv([
            'APP_URL' => request()->getHttpHost(),
            'DB_HOST' => $request->get('host'),
            'DB_PORT' => $request->get('port'),
            'DB_DATABASE' => $request->get('mysql_db'),
            'DB_USERNAME' => $request->get('mysql_user'),
            'DB_PASSWORD' => "'" . $request->get('mysql_pass') . "'"
        ]);
        Artisan::call('config:cache');
        Artisan::call('migrate');
        $this->markAsInstall();
        return redirect()->to('http://' . config('app.url') . '/install-success');

    }

    /**
     * Save currency and restaurant info
     * @param Request $request
     */
    public function currencySetting(Request $request)
    {
        
            $envPath = base_path('.env');

            $data = [
                'RESTAURANT_CURRENCY_SYMBOL' => $request->input('symbol'),
                'RESTAURANT_CURRENCY_CURRENCY' => $request->input('currency'),
                'RESTAURANT_VAT_NUMBER' => $request->input('vat_number'),
                'RESTAURANT_VAT_PERCENTAGE' => $request->input('var_percentage'),
                'RESTAURANT_PHONE' => $request->input('phone'),
                'RESTAURANT_ADDRESS' => $request->input('address'),
            ];

            $env = file_get_contents($envPath);

            foreach ($data as $key => $value) {
                $escapedValue = '"' . str_replace('"', '\"', $value) . '"'; 
                $pattern = "/^{$key}=.*$/m";
                $line = "{$key}={$escapedValue}";

                if (preg_match($pattern, $env)) {
                    $env = preg_replace($pattern, $line, $env);
                } else {
                    $env .= "\n{$line}";
                }
            }

            file_put_contents($envPath, $env);

            Artisan::call('config:clear');
            Artisan::call('config:cache');
    }

    /**
     * Save time zone
     * @param Request $request
     */
    // public function timezoneSetting(Request $request)
    // {
    //     $request->validate([
    //         'timezone' => 'required|timezone'
    //     ]);

    //     $env = new DotenvEditor();
    //     $env->changeEnv([
    //         'APP_TIMEZONE' => $request->get('timezone'),
    //         'APP_NAME' => '"' . $request->get('app_name') . '"'
    //     ]);

    // }

    public function timezoneSetting(Request $request)
    {
        $request->validate([
            'timezone' => 'required|timezone',
            'app_name' => 'required|string',
        ]);

        $envPath = base_path('.env');

        $data = [
            'APP_TIMEZONE' => $request->input('timezone'),
            'APP_NAME' => $request->input('app_name'),
        ];

        $env = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $escapedValue = '"' . str_replace('"', '\"', $value) . '"';
            $pattern = "/^{$key}=.*$/m";
            $line = "{$key}={$escapedValue}";

            if (preg_match($pattern, $env)) {
                $env = preg_replace($pattern, $line, $env);
            } else {
                $env .= "\n{$line}";
            }
        }

        file_put_contents($envPath, $env);

        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return response()->json(['message' => 'Timezone and app name updated successfully.']);
    }


    /**
     * Config the application cache
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cacheConfig()
    {
        Artisan::call('config:cache');
        return redirect()->to('http://' . config('app.url') . '/cache-config-success');
    }

    /**
     * Show a success page that cache has been config
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cacheConfigSuccess()
    {
        return view('cache-config-success');
    }

    /**
     * Mark as install so any one cannot make change the database once after install
     */
    public function markAsInstall()
    {
        $env = new DotenvEditor();
        $env->changeEnv([
            'HAS_INSTALL' => 1
        ]);
        Artisan::call('config:cache');
    }

    public function getConfig(): \Illuminate\Http\JsonResponse
    {
        return response()->json(config('restaurant'));
    }

}
