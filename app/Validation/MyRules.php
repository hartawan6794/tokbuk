<?php 
namespace App\Validation;
use App\Models\UserbiodataModel;

class MyRules{

    public function mobileValidation(string $str, string $fields, array $data){

        if(preg_match('/^[0-9]{1}[0-9]+/', $data['telpon'])){
            $bool = preg_match('/^[0-9]{13}+$/',$data['telpon']);
            return  $bool == 0 ? true:false;
        }else{
            return false;
        }

    }

    public function mobileExist(string $str, string $fields, array $data){
        $m = new UserbiodataModel();
        $d = $m->where(['telpon' => $data['telpon']])->first();
        if(!$d){
            if($data['id'] == ''){
                return false;
            }
        }

        return true;

    }

    public function nikExist(string $str, string $fields, array $data){
        $m = new UserbiodataModel();
        $d = $m->nikExist($data['nik_user']);
        if(!$d){
            return true;
        }   
        return false;
    }

}

?>