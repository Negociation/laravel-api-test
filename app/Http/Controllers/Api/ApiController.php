<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller{
    
    public function healthData(){
        return response()->json([
            "runtime"=>"microseconds",
            "memoryUsage"=>(memory_get_usage() /1024)."kb",
            "databaseConnection"=> $this->getDatabaseStatus(),
            "lastCronExecutionTime"=>"timestamp"
        ],200);
    }

    private function getDatabaseStatus(){
        try{
            DB::connection('mongodb')->getDatabaseName();
            return true;
        }catch(Exception $e){
            echo $e->getMessage();
            return false;
        }
    }
}
