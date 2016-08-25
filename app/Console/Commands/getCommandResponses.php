<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Commands\Entities\Command as CommandXML;
use Modules\CommandsResponse\Entities\CommandsResponse;
use Carbon\Carbon;
use Storage;

class getCommandResponses extends Command 
{

    protected $signature = 'commands:response';

    protected $description = 'Recupera o retorno dos comandos do Gateway';

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
        $ftp = Storage::disk('ftp');
        $list = $ftp->files('commands_response');
        $count = 0;
				
        //$file = $list[0];
        foreach ($list as $file) 
        {
						$filename = basename($file);
						$this->info(Carbon::now()->toDateTimeString()." - Processando arquivo $file"); 
						if ($content = $ftp->read($file)){
							$xml = simplexml_load_string($content);
							$ftp->delete($file);
						}

						$command = CommandXML::where('id_command', xmlGetVal($xml,'ID_COMMAND'))
															->first();
						$response = new CommandsResponse;
						//['command_id', 'fragment_number', '', '', '', '', 'timestamp']
						$response->fragment_number = xmlGetVal($xml,'FRAGMENT_NUMBER','int');
						$response->fragment_count = xmlGetVal($xml,'FRAGMENT_COUNT','int');
						$response->attempt = xmlGetVal($xml,'ATTEMPT','int');
						$response->sts_id = xmlGetVal($xml,'STS_ID','int');
						$response->timestamp = xmlGetVal($xml,'STS_TIMESTAMP','str');
						$response->desc = xmlGetVal($xml,'DESC','str');
						$command->Responses()->save($response);								
						$count++;
        }
        if ($count>0){
            $this->info("$count Respostas de Comandos processadas!");
        } else {
            $this->error("Nenhuma Resposta de Comando encontrada!");
		}
    }
}
