<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init database config';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        setConfig('join_text',"لطفا در کانال ما عضو شوید");
        setConfig('residGroup',-1234);
        setConfig('validationGroup',-1234);
        setConfig('payOutGroup',-1234);
        setConfig('sellPrice',29000);
        setConfig('buyPrice',28000);
        setConfig('card',"6063-7310-8734-0775");
        setConfig('bank',"مهر اقتصاد");
        setConfig('holder',"محمدجواد صیدی");
        setConfig('contactUs',"راه های ارتباطی با ما");
        setConfig('faq',"سوالات متداول");
        setConfig('help',"راهنما");
        setConfig('role',"قوانین");
        return 0;
    }
}
