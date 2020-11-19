<?php
//Artisan::call('storage:link');

Route::group([

], function () {
/*  Route::post('upload','imageController@create');
  Route::post('/','imageController@store');
  Route::get('/{image}','imageController@show');*/
  Route::post('afficher_user_photos','userController@show_user_photo');
//Route::post('telecharger_photo','userController@telecharger_photo');
Route::post('upload','userController@upload_photo');
//Route::post('url','userController@retrive_url');

    //to register a user
    Route::post('register', 'AuthController@register');
    //to log a user
    Route::post('login', 'AuthController@login');
/**
 * debug
 */
Route::post('user_id','AuthController@authUserId');
Route::post('tables', 'itemController@tables');

/**
 *  items
 */
    //search items
    Route::post('search', 'itemController@search_items');
    //show all items
    Route::post('show_all_items', 'itemController@show_all_items');
    //to create a new item
    Route::post('create_item', 'itemController@create_item');
    //to show all    items by me
    Route::post('show_my_items', 'itemController@show_my_items');
    //items resource
    //Route::resource('items','itemApiController');
    Route::resource('items','itemController');

/**
 * panier
 */

    //to show the current user panier items
    Route::post('panier_items', 'panierController@show_this_user_panier_with_items');
    //to add some items to panier  the current user
    Route::post('ajouterPanier','panierController@ajouter_au_panier');
    //to send a demande
    Route::post('panier_demande','panierController@panier_send_demande');
    //panier resource
    Route::resource('items_panier','items_panierApiController');

/**
 * notificaitons
 */
    //to show waiting items to be validated accepted
    route::post('notification_accepted','notificationsController@waiting_validation_of_user');
    //to show all refused items
    route::post('notification_refused','notificationsController@refused_emprunts');
    //to show both refused and waiting to be validated
    route::post('notification_all','notificationsController@show_all_notifications');


/**
 * recieve demandes and answer it
 */
    //to recieve a demande
    Route::post('requesting_me','demandesController@show_all_reieved_demandes');
    //to answer a demande
    //to accept a demande // answer =1 , to refuse = 0
    Route::post('answer','demandesController@answer_demande');
    //normal user validate the item and recieve it of the owner
    Route::post('validate','demandesController@user_recieve_item_and_validate');
    //this route is used to debug

    /**
    *return the object back
    */
    Route::post('rendre','demandesController@rendre_objet');


/**
 * admin
 */

    /**see if i can determine an admin or a user and what can this api used for */
    Route::resource('users','userController');

/*************************************************************************************
 * not important for now
 */

    //to return the current user panier id
    //Route::post('panier_id','ajouterPanierController@panier_id_of_User');
    Route::post('panier_id','panierController@panier_id_of_User');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');

/**
 * not used
 */
    //make a notifcation dossier inside controller and add all api thats contains recieve demande answer demande
    //to show the current user items
    //Route::post('show_user_items', 'itemController@show_current_user_items');
    //
});

Route::group(['middleware' => ["auth","admin"]],
    function() {
            Route::resource('users','userController');
});
