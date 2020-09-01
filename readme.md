# Shepa.com Laravel

Laravel package to connect to Shepa.com Payment Gateway

## Installation

`composer require nasrinrezaei45/shepa`

## Publish Configurations

`php artisan vendor:publish --provider="NasrinRezaei45\Shepacom\ShepacomServiceProvider"`

## Config

Set your api key and redirect url in `.env` file:

    SHEPACOM_API_KEY=test
    SHEPACOM_REDIRECT=/shepacom/callback
    
## Usage

### Payment Controller

    <?php
    
    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Nasrinrezaei45\Shepacom\Exceptions\SendException;
    use Nasrinrezaei45\Shepacom\Exceptions\VerifyException;
    use Nasrinrezaei45\Shepacom\ShepacomPG;
    
    class PaymentController extends Controller
{
    public function pay()
    {

        $shepacom = new ShepacomPG();
        $shepacom->amount = 100000; // Required, Amount
        $shepacom->factorNumber = 'Factor-Number'; // Optional
        $shepacom->description = 'Some Description'; // Optional
        $shepacom->mobile = '09133239584'; // Optional, If you want to show user's saved card numbers in gateway
        $shepacom->email = 'nasrinrezaei45@gmail.com'; // Optional, If you want to show user's saved card numbers in gateway
        $shepacom->callback = 'http://127.0.0.1:8000/verify'; // Optional, If you want to show user's saved card numbers in gateway
        try {
            $shepacom->send();
            return redirect($shepacom->paymentUrl);
        } catch (SendException $e) {
            throw $e;
        }
    }

    public function verify(Request $request)
    {
        if(isset($request->status) && ($request->status == "success")) {
            $shepacom = new ShepacomPG();
            $shepacom->token = $request->token; // Shepa.com returns this token to your redirect url
            $shepacom->amount = 100000; // Required, Amount
            try {
                $verify = $shepacom->verify(); // returns verify result from Shepa.com like (transId, cardNumber, ...)

                dd($verify);
            } catch (VerifyException $e) {
                throw $e;
            }
        }
        else {
            dd("cancel payment");
        }

    }
}

### Routes

    Route::get('/shepacom/callback', 'PaymentController@verify');
    
## Usage with facade

Config `aliases` in `config/app.php` :

    'Shepacom' => NasrinRezaei45\Shepacom\Facades\Shepacom::class
    
*Send*

    Shepacom::send($amount, $redirect = null, $email = null, $mobile = null, $description = null, $api = null);
    
*Verify*

    Shepacom::verify($token);
    
## Security

If you discover any security related issues, please create an issue or email me (nasrinrezaei45@gmail.com)
    
## License

This repo is open-sourced software licensed under the MIT license.
