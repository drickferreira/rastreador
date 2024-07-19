<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Devices\Entities\Device;
use Carbon\Carbon;
use Mail;

class Fechamento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fechamento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fechamento de número de Veiculos da ADM';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
			$start = new Carbon('first day of last month');
			$end = new Carbon('last day of last month');
			$adm = 'c72f88b9-fe7a-4448-91cf-f04b725df90b';
      $devices = Device::where('company_id', $adm)->whereNotNull('install_date')->count();
      $install = Device::where('company_id', $adm)->whereBetween('install_date', [$start->toDateString(), $end->toDateString()])->count();
			$total = $devices * 3;
			
			$this->info('Aparelhos instalados no último Mês:'.$install);
			$this->info('Aparelhos ativos:'.$devices);
			
			$subject = "Fechamento ADM Assistência Ref: ".$start->format("m/Y");
			
			Mail::send('emails.fechamento', ['devices' => $devices, 'install' => $install, 'start' => $start, 'end' => $end, 'total' => $total], function ($m) use ($subject) {
				$m->from(env('MAIL_USERNAME','rastreador@afinet.com.br'), 'Rastreamento Afinet');
				$m->to('financeiro@afinet.com.br');
				$m->cc('tasrj@afinet.com.br');
				$m->subject($subject);
			});
    }
}
