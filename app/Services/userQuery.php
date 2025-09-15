<?php

namespace App\Services;
use Illuminate\Http\Request;

class userQuery{
protected $allowedParams=[
    'name'=> ['eq'],
    'role'=> ['eq']
];


protected $operatorMap=[
    'eq' => '=',
];
public function transform(Request $request){
    $eloQuery = [];
    foreach($this->allowedParams as $parm => $operators){

        $query = $request->query($parm);
        if(!isset($query)){
            continue;
        }
        $column = $parm;

        foreach($operators as $operator){
            if (isset($query[$operator])){

                $eloQuery[]=[$column,$this->$operatorMap($operator),$query[$operator]];
            }
        }
    }
    return $eloQuery;
}

}