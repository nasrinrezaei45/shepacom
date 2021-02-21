# Shepa.com Laravel

Laravel package to connect to Shepa.com Payment Gateway

## Installation

`composer require nasrinrezaei45/shepacom`

## Publish Configurations

`php artisan vendor:publish --provider="NasrinRezaei45\Shepacom\ShepacomServiceProvider"`

## Config

Set your api key and redirect url in config/shepacom file:

   return array(
    'default' => 'sandbox',
    'drivers' => [
        'sandbox'  => [
            'api_key'  => 'sandbox',
            'callback' => env('SANDBOX_SHEPA_CALLBACK', 'http://localhost:8000/api/shepa/sandbox/verify'),
        ],
        'merchant' => [
            'api_key'  => env('SHEPACOM_API_KEY',"xxxxxx"),
            'callback' => env('SHEPACOM_CALLBACK', 'http://localhost:8000/api/shepa/merchant/verify'),
        ],
    ],
    'map'     => [
        'sandbox'  => \NasrinRezaei45\Shepacom\Drivers\Sandbox\Sandbox::class,
        'merchant' => \NasrinRezaei45\Shepacom\Drivers\Merchant\Merchant::class,
    ],
);
    
## Usage

### route 

    
    ///////////////////////////////////////////////// sandbox //////////////////////////////////////////////////////////////

Route::get('/shepa/sandbox/send', function (Request $request) {


    $result = \NasrinRezaei45\Shepacom\ShepaFacade::send(1000, "sph_1996@yahoo.com", "09xxxxxxxxx", "desc");
    return redirect($result);

});

Route::get('/shepa/sandbox/verify', function (Request $request) {

    if ($request->token && $request->status == 'success') {
        $result = \NasrinRezaei45\Shepacom\ShepaFacade::verify($request->token, 1000);
        var_dump($result);
    }
    //user canceled the request payment
});



////////////////////////////////////////////////////////////////////////// merchant ////////////////////////////////////////////
Route::get('/shepa/merchant/send', function (Request $request) {
    $result = \NasrinRezaei45\Shepacom\ShepaFacade::via("merchant")->send(1000, "sph_1996@yahoo.com", "09xxxxxxxxx", "desc");
    return redirect($result);
});


Route::get('/shepa/merchant/verify', function (Request $request) {
    if ($request->token && $request->status == 'success') {
        $result = \NasrinRezaei45\Shepacom\ShepaFacade::via("merchant")->verify($request->token, 1000);
        var_dump($result);
    }
    //user canceled the request payment
});




    
## Usage with facade

Config `aliases` in `config/app.php` :

    'Shepacom' => NasrinRezaei45\Shepacom\Facades\Shepacom::class
    
*Send*

    ShepaFacade::via("merchant")->send($amount, $email, $mobile, $description);
    
*Verify*

    ShepaFacade::via("merchant")->verify($token, $amount);
    
## Security

If you discover any security related issues, please create an issue or email me (nasrinrezaei45@gmail.com)
    
## License

This repo is open-sourced software licensed under the MIT license.
