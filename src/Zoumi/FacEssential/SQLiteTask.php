<?php

namespace Zoumi\FacEssential;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class SQLiteTask extends AsyncTask {

    private $request;

    public function __construct(string $request)
    {
        $this->request = $request;
    }

    public function onRun()
    {
        try {
            Main::getInstance()->database->query($this->request);
        }catch (\mysqli_sql_exception $exception){
        }
    }

}