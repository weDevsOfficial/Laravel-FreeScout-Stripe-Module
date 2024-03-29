<div class="stripe-container">  
    <div class="stripe-heading">
        <div class="stripe-title">
            <h4>
                {{ __('Stripe Data') }}
            </h4> 
        </div>
    </div>
    @if(isset($productWithInvoices) && (is_array($productWithInvoices) || is_object($productWithInvoices)))

        <div class="stripe-product">
            <div class="tabs">
                @forelse($productWithInvoices as $productName => $invoices)
                <div class="tab">
                <input type="checkbox" id="{{$productName}}">
                <label class="tab-label" for="{{$productName}}">{{ $productName }}</label>
                <div class="tab-content"> 
                    <div class="subscriptions">
                        <p class="subscription-title">{{__('Subscriptions')}}<p>
                        @if(isset($productWithSubscriptions[$productName])) 
                            @foreach($productWithSubscriptions[$productName] as $key => $subscription)  
                                <div class="subscription {{ $loop->first ? 'first-subscription' : ''}}" >  
                                    <span class="status">{{$subscription->status}}</span> 
                                    
                                    @foreach($invoices as $inv)
                                        @if($inv->id == $subscription->latest_invoice)
                                            <span class="price">{{$inv->currency_symbol}}{{number_format($inv->total/100, 2)}}</span> 
                                                <span class="date-time">{{date('M d Y, h:i A', $inv->created)}}</span>
                                        @endif
                                    @endforeach

                                </div>  
                            @endforeach
                        @else
                                <p>{{__('No Subscription found')}}</p>
                        @endif
                    </div>

                    <div class="invoices">
                        <p class="invoice-title">{{__('Payments')}}<p>
                        @forelse($invoices as $key => $invoice) 
                            @if($loop->iteration <= 5)
                                <div class="invoice {{ $loop->first ? 'first-invoice' : ''}}" >  
                                    <span class="price">{{$invoice->currency_symbol}}{{number_format($invoice->total/100, 2)}}</span> 
                                    <span class="status">{{$invoice->status}}</span>
                                    <a href="{{$invoice->invoice_pdf}}" target="_blank">#invoice</a>
                                    <span class="date-time">{{date('M d Y, h:i A', $invoice->created)}}</span>
                                </div> 
                            @endif
                        @empty
                            <p>{{ __('No Invoice found') }}</p>
                        @endforelse
                    </div>
                </div>
                </div> 
                @empty
                    <p>{{ __('No Stripe data found') }}</p>
                @endforelse
            </div>
        </div> 
    @else
        <p>{{ __('No Stripe data found') }}</p>
    @endif
</div>
