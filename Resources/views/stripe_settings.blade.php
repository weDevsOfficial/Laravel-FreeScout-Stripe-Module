@extends('layouts.app')

@section('title_full', __('Stripe Settings').' - '.$mailbox->name)

@section('content')

 
@section('sidebar')
    @include('partials/sidebar_menu_toggle')
    @include('mailboxes/sidebar_menu')
@endsection

<div class="section-heading">
    {{ __('Stripe Settings') }}
</div>

@include('partials/flash_messages')
 
<div class="row-container form-container">
        <div class="row">
            @if (Auth::user()->can('updateSettings', $mailbox))
            <div class="col-xs-12 col-md-12">
                <form class="form-horizontal margin-top" method="POST" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                        <div class="form-group{{ $errors->has('stripe_secret_key') ? ' has-error' : '' }}">
                            <label for="stripe_secret_key" class="col-sm-2 control-label">{{ __('Stripe Secret Key') }}</label>

                            <div class="col-sm-6">
                                <div class="flexy">
                                    <input id="stripe_secret_key" type="password" class="form-control" name="stripe_secret_key" value="{{ old('stripe_secret_key', optional($mailbox->stripeSetting)->stripe_secret_key) }}" maxlength="255">
                                </div>

                                @include('partials/field_error', ['field'=>'stripe_secret_key'])
                            </div> 
                        </div>

                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-2">
                            @if(optional($mailbox->stripeSetting)->stripe_secret_key == null)
                                <button type="submit" class="btn btn-primary"> 
                                      {{ __('Save') }}  
                                </button>
                            @else

                                <button type="submit" class="btn btn-success"> 
                                    {{ __('Update') }}  
                                </button>
                            @endif

                            <a href="javascript:void(0)" onclick="document.getElementById('deleteForm').submit();" class="btn btn-danger">
                                    {{ __('Remove') }}
                            </a>
                        </div>
                    </div>
 
                </form>
            </div>
            
            <form id="deleteForm" action="{{route('stripe.settings.destroy', $mailbox)}}" method="POST">
                {{ csrf_field() }} 
                {{ method_field('DELETE') }}
            </form>
        @endif
    </div>
</div>
@endsection