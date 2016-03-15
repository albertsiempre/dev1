<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Assign a placeholder value to prevent an exception being thrown when running
// command line utilities like artisan or composer.
$domain = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'warnet.local';

$rootDomain = URL::getRootDomain($domain);
$environment = App::environment();
$subDomainPrefix = null;
switch($environment)
{
    case "dev":
        $subDomainPrefix = "dev";
        break;
    case "qa":
        $subDomainPrefix = "qa";
        break;
    case "beta":
        $subDomainPrefix = "beta";
        break;
    default:
        $subDomainPrefix = "";
        break;
}

// Route::when('*', 'csrf', array('post', 'put', 'delete', 'patch'));

/*
|--------------------------------------------------------------------------
| Warnet routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('domain' => $subDomainPrefix . 'warnet.' . $rootDomain), function ()
{
	Route::group(array('before' => 'qeon_session|auth|privilege'), function ()
    {
        Route::get('/', array(
            'as'    => GROUP_WARNET . '.dashboard',
            'uses'  => "QInterface\Controllers\Warnet\homeController@dashboard"
        ));

        Route::get('/home', array(
            'as'    => GROUP_WARNET . '.home',
            'uses'  => 'QInterface\Controllers\Warnet\homeController@index'
        ));
        Route::get("/test", array(
            'as'    => GROUP_WARNET . '.test',
            'uses'  => 'QInterface\Controllers\Warnet\homeController@test'
        ));
        Route::get("/add", array(
            "as"    => GROUP_WARNET . ".add",
            "uses"  => "QInterface\Controllers\Warnet\homeController@formAdd"
        ));
        Route::post("/add-warnet", array(
            "as"    => GROUP_WARNET . ".addWarnet",
            "uses"  => "QInterface\Controllers\Warnet\homeController@submitForm"
        ));
        Route::post("/list-warnet", array(
            "as" => GROUP_WARNET . '.list_warnet', 
            "uses" => "QInterface\Controllers\Warnet\homeController@list_warnet"
        ));
        Route::get("/edit/{warnet_id?}", array(
            "as" => GROUP_WARNET . ".editWarnet", 
            'uses' => "QInterface\Controllers\Warnet\homeController@edit_form"
        ));
        Route::get('/del/{warnet_id?}', array(
            "as" => GROUP_WARNET . ".delWarnet", 
            'uses' => "QInterface\Controllers\Warnet\homeController@del_warnet"
        ));

        Route::group(array('prefix' => 'request_warnet'), function(){
            Route::get("/", array("as" => GROUP_WARNET . '.request_warnet', "uses" => "QInterface\Controllers\Warnet\RequestWarnet@index"));
            Route::post("/", array("as" => GROUP_WARNET . '.list_request_warnet', "uses" => "QInterface\Controllers\Warnet\RequestWarnet@list_request_warnet"));
        });

        Route::group(array('prefix' => 'pending_warnet'), function(){
            Route::get("/", array("as" => GROUP_WARNET . '.pending_warnet', "uses" => "QInterface\Controllers\Warnet\PendingWarnet@index"));
            Route::post("/", array("as" => GROUP_WARNET . '.list_pending_warnet', "uses" => "QInterface\Controllers\Warnet\PendingWarnet@list_pending_warnet"));
            Route::get("/single/{warnet_id?}", array("as" => GROUP_WARNET . '.single_pending_warnet', 'uses' => "QInterface\Controllers\Warnet\PendingWarnet@single_pending_warnet"));
            Route::post("/proccess", array("as" => GROUP_WARNET . ".process_warnet", 'uses' => "QInterface\Controllers\Warnet\PendingWarnet@proccess_warnet"));
        });

    });

    Route::get('/get_city/{province_id?}', array(
        'as'    => GROUP_WARNET . '.get_city',
        'uses'  => "QInterface\Controllers\Warnet\homeController@getCity"
    ));
});

/*
|--------------------------------------------------------------------------
| Internal routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('domain' => $subDomainPrefix . 'internal.' . $rootDomain), function(){
    
    Route::group(array('before' => 'qeon_session|auth|privilege'), function ()
    {
        Route::get('/', array(
            'as'    => GROUP_INTERNAL . '.home',
            'uses'  => "QInterface\Controllers\Internal\DVDController@Home"
        ));

        Route::group(array('prefix' => 'dvd'), function(){
            
            Route::get('/', array(
                'as'    => GROUP_INTERNAL . '.free_dvd',
                'uses'  => "QInterface\Controllers\Internal\DVDController@FreeDVD"
            ));
            
            Route::post('list_dvd', array(
                'as'    => GROUP_INTERNAL . '.list_dvd',
                'uses'  => "QInterface\Controllers\Internal\DVDController@FreeDVDList"
            ));

            Route::post('detail_request', array(
                'as'    => GROUP_INTERNAL . '.detail_request',
                'uses'  => 'QInterface\Controllers\Internal\DVDController@detailRequest'
            ));

            Route::post('send_request', array(
                'as'    => GROUP_INTERNAL . '.send_request',
                'uses'  => 'QInterface\Controllers\Internal\DVDController@sendRequest'
            ));

            Route::post('submit_request', array(
                'as'    => GROUP_INTERNAL . '.submit_request',
                'uses'  => 'QInterface\Controllers\Internal\DVDController@submitRequest'
            ));

            Route::post('update_note', array(
                'as'    => GROUP_INTERNAL . ".update_note",
                "uses"  => 'QInterface\Controllers\Internal\DVDController@updateNote'
            ));

            Route::get('checkout', array(
                'as'    => GROUP_INTERNAL . ".checkout",
                'uses'  => 'QInterface\Controllers\Internal\DVDController@checkout'
            ));

            Route::get('print_checkout/{is_box?}', array(
                'as'    => GROUP_INTERNAL . ".print_checkout",
                'uses'  => 'QInterface\Controllers\Internal\DVDController@print_checkout'
            ));

            Route::get('submit_checkout', array(
                'as'    => GROUP_INTERNAL . ".submit_checkout",
                'uses'  => 'QInterface\Controllers\Internal\DVDController@submit_checkout'
            ));

            Route::group(array('prefix' => 'report'), function()
            {

                Route::group(array('prefix' => 'get'), function()
                {
                    Route::get('city', array(
                        'as'    => GROUP_INTERNAL . ".report.city",
                        "uses"  => 'QInterface\Controllers\Internal\ReportDVD@byCity'
                    ));

                    Route::get('game', array(
                        'as'    => GROUP_INTERNAL . ".report.game",
                        "uses"  => 'QInterface\Controllers\Internal\ReportDVD@byGame'
                    ));

                    Route::get('user', array(
                        'as'    => GROUP_INTERNAL . '.report_user',
                        'uses'  => 'QInterface\Controllers\Internal\ReportDVD@userDvdRequest'
                    ));
                });

                Route::group(array('prefix' => 'post'), function(){
                    Route::post('city', array(
                        'as'    => GROUP_INTERNAL . ".report.city.filter",
                        "uses"  => 'QInterface\Controllers\Internal\ReportDVD@filterCity'
                    ));

                    Route::post('game', array(
                        'as'    => GROUP_INTERNAL . ".report.game.filter",
                        "uses"  => 'QInterface\Controllers\Internal\ReportDVD@filterGame'
                    ));

                    Route::post('user', array(
                        'as'    => GROUP_INTERNAL . '.filter_report_user',
                        'uses'  => 'QInterface\Controllers\Internal\ReportDVD@filterUserDvdRequest'
                    ));
                });
            });
        });
        
        //MGS
        Route::group(array('prefix' => 'mgs'), function(){      
            Route::get('/', array(
                'as'    => GROUP_INTERNAL . '.event',
                'uses'  => "QInterface\Controllers\Internal\MGSController@Event"
            ));
            
            Route::get('team', array(
                'as'    => GROUP_INTERNAL . '.team',
                'uses'  => "QInterface\Controllers\Internal\MGSController@Team"
            ));
            
            Route::get('winner', array(
                'as'    => GROUP_INTERNAL . '.winner',
                'uses'  => "QInterface\Controllers\Internal\MGSController@Winner"
            ));
            
            Route::get('teamversus', array(
                'as'    => GROUP_INTERNAL . '.teamversus',
                'uses'  => "QInterface\Controllers\Internal\MGSController@TeamVersus"
            ));
                        
            Route::post('list_event', array(
                'as'    => GROUP_INTERNAL . '.list_event',
                'uses'  => "QInterface\Controllers\Internal\MGSController@EventList"
            ));
            
            Route::post('list_team', array(
                'as'    => GROUP_INTERNAL . '.list_team',
                'uses'  => "QInterface\Controllers\Internal\MGSController@TeamList"
            ));
            
            Route::post('list_winner', array(
                'as'    => GROUP_INTERNAL . '.list_winner',
                'uses'  => "QInterface\Controllers\Internal\MGSController@WinnerList"
            ));
            
            Route::post('list_teamversus', array(
                'as'    => GROUP_INTERNAL . '.list_teamversus',
                'uses'  => "QInterface\Controllers\Internal\MGSController@TeamVersusList"
            ));
            
            Route::group(array('prefix' => 'event'), function(){
                Route::get('form/{event_id?}', array(
                    'as'    => GROUP_INTERNAL . '.form.event',
                    'uses'  => "QInterface\Controllers\Internal\MGSController@FormEvent"
                ));
                
                Route::post('submit', array(
                    'as'    => GROUP_INTERNAL . '.submit.event',
                    'uses'  => "QInterface\Controllers\Internal\MGSController@SubmitEvent"
                ));                
            });
            
            Route::group(array('prefix' => 'team'), function(){
                Route::get('form/{team_id?}', array(
                    'as'    => GROUP_INTERNAL . '.form.team',
                    'uses'  => "QInterface\Controllers\Internal\MGSController@FormTeam"
                ));
                
                Route::post('submit', array(
                    'as'    => GROUP_INTERNAL . '.submit.team',
                    'uses'  => "QInterface\Controllers\Internal\MGSController@SubmitTeam"
                ));                
            });
            
            Route::group(array('prefix' => 'winner'), function(){
                Route::get('form/{winner_id?}', array(
                    'as'    => GROUP_INTERNAL . '.form.winner',
                    'uses'  => "QInterface\Controllers\Internal\MGSController@FormWinner"
                ));
                
                Route::post('submit', array(
                    'as'    => GROUP_INTERNAL . '.submit.winner',
                    'uses'  => "QInterface\Controllers\Internal\MGSController@SubmitWinner"
                ));                
            });
            
            Route::group(array('prefix' => 'teamversus'), function(){
                Route::get('form/{teamversus_id?}', array(
                    'as'    => GROUP_INTERNAL . '.form.teamversus',
                    'uses'  => "QInterface\Controllers\Internal\MGSController@FormTeamVersus"
                ));
                
                Route::post('submit', array(
                    'as'    => GROUP_INTERNAL . '.submit.teamversus',
                    'uses'  => "QInterface\Controllers\Internal\MGSController@SubmitTeamVersus"
                ));                
            });
            
        });

        //NEWSLETTER
        Route::group(array('prefix' => 'newsletter'), function(){ 
            Route::get('/', array(
                'as'    => GROUP_INTERNAL . '.newsletter',
                'uses'  => "QInterface\Controllers\Internal\Newsletter@main"
            ));            

            Route::get('report_bounce', array(
                'as'    => GROUP_INTERNAL . '.report_bounce',
                'uses'  => "QInterface\Controllers\Internal\Newsletter@ReportBounce"
            ));  

            Route::post('list_bounce', array(
                'as'    => GROUP_INTERNAL . '.list_bounce',
                'uses'  => "QInterface\Controllers\Internal\Newsletter@ReportBounceList"
            ));

            Route::get('download_verified_email', array(
                'as'    => GROUP_INTERNAL . '.download_verified_email',
                'uses'  => "QInterface\Controllers\Internal\Newsletter@DownloadVerifiedEmail"
            ));            

            Route::get('download_read_email', array(
                'as'    => GROUP_INTERNAL . '.download_read_email',
                'uses'  => "QInterface\Controllers\Internal\Newsletter@DownloadReadEmail"
            ));
        }); 

        //Microsite
        Route::group(array('prefix' => 'microsite'), function(){      
            Route::get('/', array(
                'as'    => GROUP_INTERNAL . '.banner',
                'uses'  => "QInterface\Controllers\Internal\MsiteController@Banner"
            ));
                        
            Route::post('list_banner', array(
                'as'    => GROUP_INTERNAL . '.list_banner',
                'uses'  => "QInterface\Controllers\Internal\MsiteController@BannerList"
            ));
            
            Route::group(array('prefix' => 'banner'), function(){
                Route::get('form/{banner_id?}', array(
                    'as'    => GROUP_INTERNAL . '.form.banner',
                    'uses'  => "QInterface\Controllers\Internal\MsiteController@FormBanner"
                ));
                
                Route::post('submit', array(
                    'as'    => GROUP_INTERNAL . '.submit.banner',
                    'uses'  => "QInterface\Controllers\Internal\MsiteController@SubmitBanner"
                ));                
            });
            
        });

        /** Widgets **/
        Route::group(array('prefix' => 'widgets'), function(){      
            Route::get('/', array(
                'as'    => GROUP_INTERNAL . '.widgets',
                'uses'  => "QInterface\Controllers\Internal\WidgetController@index"
            ));
            Route::post('submit', array(
                'as'    => GROUP_INTERNAL . '.addWidget',
                'uses'  => "QInterface\Controllers\Internal\WidgetController@submit"
            ));
            Route::post('filter', array(
                'as'    => GROUP_INTERNAL . '.filterWidget',
                'uses'  => "QInterface\Controllers\Internal\WidgetController@filterWidget"
            ));
            Route::get('add/{id?}', array(
                'as'    => GROUP_INTERNAL . '.formAdd',
                'uses'  => "QInterface\Controllers\Internal\WidgetController@formAdd"
            ));
            Route::get('delete/{id?}', array(
                'as'    => GROUP_INTERNAL . '.del_widget',
                'uses'  => "QInterface\Controllers\Internal\WidgetController@delete"
            ));
        });
    });

    Route::get('/get_city/{province_id?}', array(
        'as'    => GROUP_INTERNAL . '.get_city',
        'uses'  => "QInterface\Controllers\Warnet\homeController@getCity"
    ));
});

/*
|--------------------------------------------------------------------------
| CRM routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('domain' => $subDomainPrefix . 'crm.' . $rootDomain), function(){
    
    Route::group(array('before' => 'qeon_session|auth|privilege'), function ()
    {
        Route::get('/', array(
            'as'    => GROUP_CRM . '.dashboard',
            'uses'  => "QInterface\Controllers\CRM\dashboard@home"
        ));

        Route::group(array('prefix' => 'faq'), function(){
            Route::group(array('prefix' => 'get'), function(){
                Route::get('/', array(
                    'as'    => GROUP_CRM . ".faq",
                    "uses"  => 'QInterface\Controllers\CRM\faq@index'
                ));

                Route::get('form', array(
                    'as'    => GROUP_CRM . ".form_faq",
                    "uses"  => 'QInterface\Controllers\CRM\faq@form'
                ));

                Route::get('del_faq/{id?}', array(
                    'as'    => GROUP_CRM . ".del_faq",
                    'uses'  => 'QInterface\Controllers\CRM\faq@del_faq'
                ));
            });

            Route::group(array('prefix' => 'post'), function(){
                Route::post('list', array(
                    'as'    => GROUP_CRM . '.list_faq',
                    'uses'  => 'QInterface\Controllers\CRM\faq@list_faq'
                ));

                Route::get('score/{fid?}/{score?}', array(
                    'as'    => GROUP_CRM . '.faq_score',
                    'uses'  => 'QInterface\Controllers\CRM\faq@score_faq'
                ));

                Route::post('submit', array(
                    'as'    => GROUP_CRM . '.addFAQ',
                    'uses'  => 'QInterface\Controllers\CRM\faq@submit_faq'
                ));
            });
        });

        Route::group(array('prefix' => 'ticketsource'), function(){
            Route::group(array('prefix' => 'get'), function(){
                Route::get('/', array(
                    'as'    => GROUP_CRM . '.ticketsource',
                    'uses'  => 'QInterface\Controllers\CRM\ticketsource@index'
                ));

                Route::get('form', array(
                    'as'    => GROUP_CRM . ".form_ticket_source",
                    "uses"  => 'QInterface\Controllers\CRM\ticketsource@form'
                ));

                Route::get('del/{id?}', array(
                    'as'    => GROUP_CRM . ".delete_ticket_source",
                    'uses'  => 'QInterface\Controllers\CRM\ticketsource@del_ticket_source'
                ));
            });

            Route::group(array('prefix' => 'post'), function(){
                Route::post('/', array(
                    'as'    => GROUP_CRM . ".submit_ticket_source",
                    'uses'  => 'QInterface\Controllers\CRM\ticketsource@submit'
                ));
            });
        });

        Route::group(array('prefix' => 'report'), function(){
            Route::group(array('prefix' => 'get'), function(){
                Route::get('faq', array(
                    'as'    => GROUP_CRM . '.report_faq',
                    'uses'  => 'QInterface\Controllers\CRM\report@faq'
                ));

                Route::get('ticket_time', array(
                    'as'    => GROUP_CRM . '.report_ticket_time',
                    'uses'  => 'QInterface\Controllers\CRM\report@ticket_time'
                ));

                Route::get('feedback', array(
                    'as'    => GROUP_CRM . '.report_feedback',
                    'uses'  => 'QInterface\Controllers\CRM\report@feedback'
                ));
            });

            Route::group(array('prefix' => 'post'), function(){
                Route::post('list_faq', array(
                    'as'    => GROUP_CRM . '.report_list_faq',
                    'uses'  => 'QInterface\Controllers\CRM\report@list_faq'
                ));

                Route::post('list_ticket_time', array(
                    'as'    => GROUP_CRM . '.report_list_ticket_time',
                    'uses'  => 'QInterface\Controllers\CRM\report@list_ticket_time'
                ));

                Route::post('list_feedback', array(
                    'as'    => GROUP_CRM . '.report_list_feedback',
                    'uses'  => 'QInterface\Controllers\CRM\report@list_feedback'
                ));
            });
        });

        Route::group(array('prefix' => 'ticket'), function(){
            Route::group(array('prefix' => 'get'), function(){
                Route::get('/', array(
                    'as'    => GROUP_CRM . '.ticket',
                    'uses'  => 'QInterface\Controllers\CRM\tickets@index'
                ));

                Route::get('form', array(
                    'as'    => GROUP_CRM . ".form_ticket",
                    "uses"  => 'QInterface\Controllers\CRM\tickets@form'
                ));

                Route::get('edit/{id?}', array(
                    'as'    => GROUP_CRM . ".edit_ticket",
                    "uses"  => 'QInterface\Controllers\CRM\tickets@edit_form'
                ));

                Route::get('del/{id?}', array(
                    'as'    => GROUP_CRM . ".del_ticket",
                    "uses"  => 'QInterface\Controllers\CRM\tickets@delete'
                ));
            });

            Route::group(array('prefix' => 'post'), function(){
                Route::post('answer', array(
                    'as'    => GROUP_CRM . '.submit_answer',
                    'uses'  => 'QInterface\Controllers\CRM\tickets@submit_answer'
                ));

                Route::post('list', array(
                    'as'    => GROUP_CRM . '.list_ticket',
                    'uses'  => 'QInterface\Controllers\CRM\tickets@list_ticket'
                ));

                Route::post('/', array(
                    'as'    => GROUP_CRM . ".submit_ticket",
                    'uses'  => 'QInterface\Controllers\CRM\tickets@submit'
                ));
            });
        });

        Route::group(array('prefix' => 'service'), function(){
            Route::group(array('prefix' => 'get'), function(){
                Route::get('/', array(
                    'as'    => GROUP_CRM . ".service",
                    'uses'  => 'QInterface\Controllers\CRM\service@index'
                ));

                Route::get('form', array(
                    'as'    => GROUP_CRM . ".form_service",
                    'uses'  => 'QInterface\Controllers\CRM\service@form'
                ));

                Route::get('del_service/{id?}', array(
                    'as'    => GROUP_CRM . ".delete_service",
                    'uses'  => 'QInterface\Controllers\CRM\service@del_service'
                ));
            });

            Route::group(array('prefix' => 'post'), function(){
                Route::post('/', array(
                    'as'    => GROUP_CRM . ".submit_service",
                    'uses'  => 'QInterface\Controllers\CRM\service@submit'
                ));
            });
        });

        Route::group(array('prefix' => 'subservice'), function(){
            Route::group(array('prefix' => 'get'), function(){
                Route::get('/', array(
                    'as'    => GROUP_CRM . '.subservice',
                    'uses'  => 'QInterface\Controllers\CRM\subservice@index'
                ));

                Route::get('form', array(
                    'as'    => GROUP_CRM . ".form_subservice",
                    'uses'  => 'QInterface\Controllers\CRM\subservice@form'
                ));

                Route::get('del_subservice/{id?}', array(
                    'as'    => GROUP_CRM . ".delete_subservice",
                    'uses'  => 'QInterface\Controllers\CRM\subservice@del_subservice'
                ));
            });

            Route::group(array('prefix' => 'post'), function(){
                Route::post('/', array(
                    'as'    => GROUP_CRM . ".submit_subservice",
                    'uses'  => 'QInterface\Controllers\CRM\subservice@submit'
                ));
            });
        });
    });
});

/*
|--------------------------------------------------------------------------
| Global routes
|--------------------------------------------------------------------------
|
*/
Route::group(array('before' => 'qeon_session|guest'), function ()
{
    Route::get('login', 'QInterface\Controllers\GatewayController@index');
});


/*
|--------------------------------------------------------------------------
| Error Routes
|--------------------------------------------------------------------------
|
*/
App::error(function($exception, $code)
{
    if (!Config::get('app.debug')) {
        switch ($code)
        {
            case 404:
                return Response::view('errors.404', array(), 404);
                break;
            case 500:
                return Response::view('errors.500', array(), 500);
                break;
            case 401:
                return Response::view('errors.noPrivilege', array(), 401);
                break;
            default:
                return Response::view('errors.missing', array(), $code);
        }
    }
});