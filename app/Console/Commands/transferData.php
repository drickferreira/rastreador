<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Positions\Entities\Position;
use Carbon\Carbon;
use DB;

class transferData extends Command 
{

    protected $signature = 'positions:move';

    protected $description = 'Move posições do banco de Dados antigo para o novo';

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
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
				$positions = DB::connection('origem')
										 ->table('positions')
										 ->whereRaw("deleted_at is null")
										 ->limit(1000)
										 ->get();
				foreach ($positions as $position){
					$this->info("Id: ". $position->id. " - Data: ". $position->date." - Serial: " . $position->serial);
					$pos = DB::connection('destino')
								 ->table('positions')
								 ->where('id', $position->id)
								 ->get();
					if (count($pos)==0) {
						$new = array();
						foreach($position as $key => $value){
							$new[$key] = $value;
						}
						$insert = DB::connection('destino')
								->table('positions')
								->insert($new);
					} else {
						$insert = true;
					}
					if($insert){
						$this->info("Salvo com sucesso!");
						DB::connection('origem')
								->table('positions')
								->where('id', $position->id)
								->update(['deleted_at' => Carbon::now()]);
					} else {
						$this->error("Ocorreu um erro!");
					}
				}
    }
}
