@if (Auth::user()->isAdmin() || Auth::user()->hasManageMailboxPermission($mailbox->id, App\Mailbox::ACCESS_PERM_AUTO_REPLIES))
    <li @if (Route::currentRouteName() == 'stripe.settings')class="active"@endif><a href="{{ route('stripe.settings', ['id'=>$mailbox->id]) }}"><i class="glyphicon glyphicon-refresh"></i> {{ __('Stripe Settings') }}</a></li>
@endif