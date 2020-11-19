<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\item ;
use App\User ;
use App\item_panier ;
use Validator;
use Response ;

/**
 * a code to modify after
 */
class notificationsController extends AuthController
{
    //
    public function show_all_notifications()
    {
         return response()->json([
            'refused_item' =>$this->refused_emprunts(),
            'Accepted_items' =>$this->waiting_validation_of_user()
        ]);
    }

//modify this function name to accepted
    public function waiting_validation_of_user()
    {
        $user_id = $this->authUserId();
/**
 * WHERE users.user_id = 1 && t_items_emprunts.emprunt_statut_id=2
 */
        $notification = DB::table('t_items_emprunts')
        ->select('t_items_emprunts.emprunt_id','t_items_emprunts.emprunt_statut_id' , 'users.name as demandeur' , 'property.name as demande_de' , 't_items.item_name' ,'t_items.item_id'
        , 't_items_emprunts.emprunt_id' ,'t_items_emprunts_statut.emprunt_statut_nom')
        ->join('users' ,'users.user_id','=','t_items_emprunts.user_id')
        ->join('t_items_emprunts_statut' ,'t_items_emprunts.emprunt_statut_id','=','t_items_emprunts_statut.emprunt_statut_id')
        ->join('t_items' ,'t_items.item_id','=','t_items_emprunts.item_id')
        ->join('users as property' ,'t_items.id_user','=','property.user_id')
        ->where('users.user_id', '=', $user_id)
        ->where('t_items_emprunts.emprunt_statut_id','=' ,2);//2 attente_validation waiting validation of this user

        if($notification->exists())
        {
            return array(
                'statut' =>'success',
                'message' =>'',
                'data' => $notification->get(),
            );

        }else{
            return  array(
                'statut' => 'failed',
                'message' =>'There is no accepted item for this user',
                'data' => $notification->get(),
            );

        }
    }

    public function refused_emprunts()
    {

        $user_id = $this->authUserId();
        /**
         * WHERE users.user_id = 1 && t_items_emprunts.emprunt_statut_id=2
         */
                $notification = DB::table('t_emprunts_history')
                ->select(/*'t_items_emprunts.emprunt_id' ,*/ 'users.name as demandeur' , 'property.name as demande_de' , 't_items.item_name' ,
                 /*'t_items_emprunts.emprunt_id',*/'t_items.item_id' ,'t_items_emprunts_statut.emprunt_statut_nom')
                ->join('users' ,'users.user_id','=','t_emprunts_history.user_id')
                ->join('t_items_emprunts_statut' ,'t_emprunts_history.emprunt_statut_id','=','t_items_emprunts_statut.emprunt_statut_id')
                ->join('t_items' ,'t_items.item_id','=','t_emprunts_history.item_id')
                ->join('users as property' ,'t_items.id_user','=','property.user_id')
                ->where('users.user_id', '=', $user_id)
                //->where('t_items_emprunts.emprunt_statut_id','=' ,3);
                ->where('t_emprunts_history.emprunt_statut_id','=' ,3);

                if($notification->exists())
                {
                    return array(
                        'statut' =>'success',
                        'message' =>'',
                        'data' => $notification->get(),
                    );

                }else{
                    return array(
                        'statut' =>'failed',
                        'message' =>'there is no refused item for this user',
                        'data' => $notification->get(),
                    );

                }
    }

}
