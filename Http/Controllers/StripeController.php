<?php

namespace Modules\Stripe\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Modules\Stripe\Entities\Mailbox;
use Stripe\StripeClient;

class StripeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Summary of index
     * @param mixed $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Mailbox $mailbox)
    {
        $mailbox = $mailbox->load('stripeSetting');

        try {
            if (isset($mailbox->stripeSetting->stripe_secret_key)) {
                $mailbox->stripeSetting->stripe_secret_key = Crypt::decryptString($mailbox->stripeSetting->stripe_secret_key);
            }
        } catch (DecryptException $e) {
            $mailbox->stripeSetting->stripe_secret_key = '';
            \Session::flash('flash_error_floating', $e->getMessage());
        }

        return view('stripe::stripe_settings', ['mailbox' => $mailbox]);
    }

    /**
     * Update stripe key
     * @param Request $request
     * @return void
     */
    public function update(Request $request, Mailbox $mailbox)
    {
        $this->validate($request, [
            'stripe_secret_key' => 'required|max:250',
        ]);

        if ($this->checkCredential($request->stripe_secret_key) !== true) {
            \Session::flash('flash_error_floating', __('Credentials Mismatch.'));
            return redirect()->back();
        }

        $requestData = [
            'mailbox_id'   => $mailbox->id,
            'stripe_secret_key' =>  Crypt::encryptString($request->stripe_secret_key),
        ];

        try {
            $mailbox->stripeSetting()->updateOrCreate(
                ['mailbox_id'   => $requestData['mailbox_id']],
                $requestData
            );
            \Session::flash('flash_success_floating', __('Secret Key Updated Successfully'));
        } catch (DecryptException $th) {
            \Session::flash('flash_error_floating', __($th->getMessage()));
        }

        return redirect()->route('stripe.settings', $mailbox->id);
    }

    /**
    *  Delete stripe key
    * @param Request $request
    * @return void
    */
    public function destroy(Request $request, Mailbox $mailbox)
    {
        try {
            $mailbox->stripeSetting()->delete();
            \Session::flash('flash_success_floating', __('Secret Key delete Successfully'));
        } catch (DecryptException $th) {
            \Session::flash('flash_error_floating', __($th->getMessage()));
        }

        return redirect()->back();
    }

    private function checkCredential($credential)
    {
        try {
            $stripeClient  = new StripeClient($credential);
            $response = $stripeClient->customers->all();

            if($response) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
