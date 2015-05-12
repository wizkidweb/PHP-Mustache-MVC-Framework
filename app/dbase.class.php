<?php
/*
for EX:
$db= new DBase();
$rows = $db->Query("select * from table1 where id=? or age=? or colname=?","iis",$idValue,$ageValue,$nameValue);

//OUTPUT: ARRAY OF RETURNED ROWS
// $name= $rows[0]["name"];
if($db->NonQuery("insert into table1 values (?,?,?,?)","isss",$value1,$value2,$value3,$value4))
   //return true;
else
   // return false;
*/
class DBase {
    protected $link; 
	private $registry;
    private $user;
    private $pass;
    private $db;
    private $server;
    
    function __construct($registry){
		$this->registry = $registry;
		
		$this->user = $this->registry->Config->db->user;
		$this->pass = $this->registry->Config->db->pass;
		$this->db = $this->registry->Config->db->database;
		$this->server = $this->registry->Config->db->server;
		
        $this->link = new mysqli($this->server,$this->user,$this->pass,$this->db);
        if(mysqli_errno($this->link))
            return false;
        $this->link->set_charset("utf8")  ;
        return true;
    }
    function NonQuery(){
    	$args = func_get_args();
        $query = $args[0];
        $param = array_slice($args,1);
        $smt = $this->link->prepare($query); 
        $this->SetParams($smt, $param); 
        return $smt->execute();        	
    }
    function Query(){
        $args = func_get_args();
        $query = $args[0];
        $param = array_slice($args,1);
        $smt = $this->link->prepare($query); 
        
        $this->SetParams($smt, $param);
        $res = $smt->execute();
        if($res){                                                   
            $fields=$this->GetColumns($smt);    
            foreach ($fields as $field)
                $bind_r[] = &${$field};   
            call_user_func_array(array($smt,"bind_result"),$bind_r);
            $i=0;
            $result = array();
            while($smt->fetch()){
                  foreach ($fields as $field) { 
                        $result[$i][$field] = $$field;
                  }
                  $i++;
            }
            return $result;
        }
        return false;
    }
    private function SetParams(&$smt,&$param){
      if(count($param)>1){
      	$refs = array(); 
	    foreach($param as $key => $value) 
	        $refs[$key] = &$param[$key];
        call_user_func_array(array($smt, 'bind_param'), $refs);
      }
    }
    private function GetColumns(&$smt){
        $fields = array();
        $f = $smt->result_metadata();
        for($i=0;$i<$f->field_count;$i++){
        	$field = $f->fetch_field();
            $fields[] = $field->name;
        }
        return $fields;
    }
    
}