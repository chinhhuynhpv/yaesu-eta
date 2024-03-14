<?php

namespace App\Providers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class DataBaseQueryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        if (config('logging.channels.sql.enable') === false) {
            return;
        }
        DB::listen(function ($query) {
            $sql = $query->sql;
            // migration系はlogに出力しない
            if (preg_match("/DROP/i",$sql)) {
                return;
            }
            if (preg_match("/SHOW/i",$sql)) {
                return;
            }
            if (preg_match("/information_schema/i",$sql)) {
                return;
            }
            if (preg_match("/^CREATE/i",$sql)) {
                return;
            }
            if (preg_match("/migrations/i",$sql)) {
                return;
            }
            if (preg_match("/alter/i",$sql)) {
                return;
            }
            if (preg_match("/FOREIGN_KEY_CHECKS/i",$sql)) {
                return;
            }

            foreach ($query->bindings as $binding) {
                if (is_string($binding)) {
                    $binding = "'{$binding}'";
                } elseif (is_bool($binding)) {
                    $binding = $binding ? '1' : '0';
                } elseif (is_int($binding)) {
                    $binding = (string) $binding;
                } elseif ($binding === null) {
                    $binding = 'NULL';
                } elseif ($binding instanceof Carbon) {
                    $binding = "'{$binding->toDateTimeString()}'";
                } elseif ($binding instanceof DateTime) {
                    $binding = "'{$binding->format('Y-m-d H:i:s')}'";
                }

                $sql = preg_replace('/\\?/', $binding, $sql, 1);
            }

            Log::channel("sql")->debug('SQL', ['sql' => $sql, 'time' => "{$query->time} ms"]);
        });

        Event::listen(TransactionBeginning::class, function (TransactionBeginning $event) {
            Log::channel("sql")->debug('START TRANSACTION');
        });

        Event::listen(TransactionCommitted::class, function (TransactionCommitted $event) {
            Log::channel("sql")->debug('COMMIT');
        });

        Event::listen(TransactionRolledBack::class, function (TransactionRolledBack $event) {
            Log::channel("sql")->debug('ROLLBACK');
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
