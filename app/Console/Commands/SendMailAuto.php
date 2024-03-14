<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendMailAuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:handle
                                        {--send-mail-to-contractor : send mail to contractor}
                                        {--send-mail-to-shop : send mail to shop}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail auto ';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('send-mail-to-contractor')) {
            $this->sendMailToContractor();
        }
        // one a week
        if ($this->option('send-mail-to-shop')) {
            $this->sendMailToShop();
        }

    }

    public function sendMailToContractor()
    {
        $listUsers = DB::table('m_users')
        ->join('t_billinges', 't_billinges.user_id', '=', 'm_users.id')
        ->where('t_billinges.status', '2')->get();
       
        foreach ($listUsers as $key => $value) {
            $data = ['content' => '', 'title' => 'ご請求額確定のお知らせ'];
            Mail::send('admin.mail_contractor', $data, function($message) use ($value) {
                $message->to($value->email)->subject('ご請求額確定のお知らせ');
                $message->attach(public_path('uploads/aaa.pdf'), [
                    'as' => 'aaa.pdf',
                    'mime' => 'application/pdf',
                ]);
                $message->from(config('mail.from.address'));
            });
        }
    }

    public function sendMailToShop()
    {
        $listShops = DB::table('m_shops')
        ->join('t_billinges', 't_billinges.shop_id', '=', 'm_shops.id')
        ->where('t_billinges.status', '2')->get();

        foreach ($listShops as $key => $value) {
            
            Mail::send('admin.mail_shop', ["shop_name"=>$value->name], function($message) use ($value) {
                $message->to($value->email)->subject('STR広域通信サービス　回線利用レポート');
                $message->attach(public_path('uploads/aaa.pdf'), [
                    'as' => 'aaa.pdf',
                    'mime' => 'application/pdf',
                ]);
                $message->from(config('mail.from.address'));
            });
        }
    }
}
