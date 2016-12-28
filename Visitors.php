<?php
require_once 'dbconn.php';

$obj = new Visitors();//create an instance of class Visitors

$obj->index();//call index method


class Visitors {
    protected $db_obj;
    
    //class constructor
    public function __construct(){
        //create an instance of the dbconn class which interfaces with our db
        $this->db_obj = new Dbconn();
    }

    
    
    public function index(){
        if(isset($_POST['action'])){
            $data_to_return_to_client = "";
            
            $action = filter_input(INPUT_POST, 'action');
            
            switch($action){
                case "insert":
                    $data_to_return_to_client = $this->saveInfoToDb();
                    break;
                
                case "checkout":
                    $data_to_return_to_client = $this->visitorCheckOut();
                    break;
                
                default:
                    header('location:index.php');
                    break;
            }
            
            
            echo json_encode($data_to_return_to_client);
        }
        
        else{
            header('location:index.php');
        }
    }
    
    
    /**
     * 
     * @return type
     */
    private function saveInfoToDb(){
        $encoded_image = filter_input(INPUT_POST, 'i');
        $name = filter_input(INPUT_POST, 'n');
        $email = filter_input(INPUT_POST, 'e', FILTER_VALIDATE_EMAIL);
        $phone = filter_input(INPUT_POST, 'p');
        $from = filter_input(INPUT_POST, 'f');
        $to_see = filter_input(INPUT_POST, 't');
            
        $file_name = time().".jpeg";
        $decoded_image = base64_decode($encoded_image);
        
        //move the files to the final destination and insert details in db
        file_put_contents("visitors/".$file_name, $decoded_image);
        
        //set the url
        $img_url = "visitors/".$file_name;
        
        try{
            $inserted_id = $this->db_obj->insertNewVisitor($name, $email, $phone, $from, $to_see, $img_url);
        
            if($inserted_id){
                $data_to_return = ['status'=>1, 'id'=>$inserted_id, 'cit'=>date('jS M, Y h:i:s a')];
            }

            else{
                $data_to_return = ['status'=>0];
            }
        } 
        
        catch (Exception $ex) {
            $data_to_return['msg'] = $ex->getMessage();
            $data_to_return['status'] = 0;
        }
        
        return $data_to_return;
    }
    
    
    
    
    /**
     * 
     */
    private function visitorCheckOut(){
        //get post value
        $pass_id = filter_input(INPUT_POST, 'pi', FILTER_VALIDATE_INT);
        
        //cll function to do the checkout
        $checked_out = $this->db_obj->checkout($pass_id);

        //set data to return
        $json['status'] = $checked_out ? 1 : 0;
        $json['cot'] = $checked_out ? date('jS M, Y h:i:sa') : "";

        return $json;
    }
}