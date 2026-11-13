<?php

namespace Modules\Uhelpupdate\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Ticket\Ticket;
use App\Models\Customer;
use Auth;

class Cannedmessages extends Model
{
    use HasFactory;

    protected $table = 'cannedmessages';

    protected $fillable = [
        'title',
        'messages',
        'status',
    ];

    public static function getDetailsList(){
	    $return_object=array();
	    $return_object["app_name"]="The Application Name";
	    $return_object["site_url"]="The Site URL";
	    $return_object["ticket_user"]="The Customer who has opened ticket";
	    $return_object["ticket_title"]="The Ticket Title";	
	    $return_object["ticket_id"]="The Ticket ID";	
	    $return_object["ticket_priority"]="The Ticket Priority";	
	    $return_object["user_reply"]="The Employee's who reply to the ticket";
	    $return_object["user_role"]="The Employee's Role";
	    
	    return $return_object;
	}

    public static function getDetailsListClearData(){
	    $return_object=self::getDetailsList();
	    $return_object=array_map(function($value){
	        $value="";
	    }, $return_object);
	    $return_object["app_name"]=env('APP_NAME');
	    $return_object["site_url"]=env('APP_URL');
	    return $return_object;
	}

    static function get_real_message($params,$str){
	    if(count($params)>0){
            $search=array();
    	    $replace=array();
            
    	    foreach ($params as $key=>$value){
    	        $search[]="{{".$key."}}";
    	        $replace[]=$value;
    	    }   
             	   
    	    return str_replace($search, $replace, $str);
	    }
	    return $str;
	} 

    /**
	 * @param Ticket $ticketDetails
	 * @return multitype:
	 */
    public static function tickedetails($ticketDetails)
    {

        $ticket = Ticket::where('ticket_id', $ticketDetails)->first();
        if($ticket){

            $response_object = [];
            $cannedmessages = self::where('status', '1')->get();


            if(count($cannedmessages)>0){
                $details=self::getDetailsListClearData();
                $ticket_users=Customer::find($ticket->cust_id);
                $details["ticket_user"]=$ticket_users->firstname." ". $ticket_users->lastname;
                $details["ticket_title"]=$ticket->subject;
                $details["ticket_id"]=$ticket->ticket_id;
                $details["ticket_priority"]=$ticket->priority;
                $details["user_reply"]=Auth::user()->name;
                if(!empty(Auth::user()->getRoleNames()[0])){

                    $details["user_role"]=Auth::user()->getRoleNames()[0];
                }
                foreach ($cannedmessages as $msg){
                    $msg->messages=self::get_real_message($details, $msg->messages);
                    $response_object[$msg->id]=$msg;
                }
                return $response_object;
            }
        }

        return [];
    }
    
    protected static function newFactory()
    {
        return \Modules\Uhelpupdate\Database\factories\CannedmessagesFactory::new();
    }
}
