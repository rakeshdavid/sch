@extends('layouts.uploadvideo')
@section('content')
 <div class="process-steps">
        <a href="{{url('auditions')}}" class="back-arrow"><img src="/platform/img/back-arrow.png" alt="" class="img-fluid"></a>
        <a href="{{url('auditions')}}" class="close-icon"><img src="/platform/img/close.png" alt="" class="img-fluid"></a>
        <ul class="process-menu">
            <li class="active"><a href="#">UPLOAD PARTICIPATION</a></li>
            <li class="active"><a href="#">PAY</a> </li>
        </ul>
    </div>

    <!-- payment-wrap -->
    <section class="payment-wrap audition-payment">
        <div class="container-fluid">
            <h2>Payment</h2>
            <div class="row">
                <div class="col-xl-5 col-lg-6">
                    <h3 class="mb-4">Audition</h3>
                    <div class="package-info">
                        <div class="media ">
                            @if($audition->logo)
                            <div class="img-box payment-logo" style="background-image: url('{{asset('uploads/auditions')}}/{{ $audition->logo}}')">
                                
                            </div>
                            @endif
                            <div class="media-body">
                                <div class="info-box">
                                    <div class="designation">{{$audition->title}}</div>
                                    <h3>{{$audition->audition_name}}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="entry-info">
                            <div class="price"><sub>$</sub> {{$audition->audition_fee}}</div>
                            
                                <h4>Audition Entry</h4>
                                <div class="package-title">This package includes:</div>
                                <ul>
                                    <li>Verbal &amp; written feedback</li>
                                    <li>Scorecard &amp; comments</li>
                                    <li>Performance level placement </li>
                                </ul>
                            
                        </div>
                    </div>
                </div>

                <div class="col-xl-5 col-lg-6">
                    <h3 class="mb-4">Payment Details</h3>
                    @if (Session::has('success'))
                        <div class="alert text-center"  style="background-color: #E5113E;color:#fff;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p class="mb-0">{{ Session::get('success') }}</p>
                        </div>
                        <script>
                            setTimeout(function(){ 
                                window.location = "/auditions";
                            }, 3000);
                        </script>
                    @endif
                    @if (Session::has('error'))
                        <div class="alert 'alert-danger text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p class="mb-0">{{ Session::get('error') }}</p>
                        </div>
                    @endif
                    <div class="payment-form">
                        <form role="form" action="{{ route('auditoinpay.post') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_PUBLISHABLE_KEY') }}" id="payment-form">
                        {!! csrf_field() !!}
                            <input type="hidden" name="participant-id" value="{{$participant_id}}" />
                            <input type="hidden" name="audition-id" value="{{$audition->id}}" />
                            <div class="form-group required">
                                <label for="">Card Holder Name</label>
                                <input type="text" class="form-control" placeholder="Jhon Doe" autocomplete='off'>
                            </div>
                            <div class="form-group card required">
                                <label for="">Card number</label>
                                <div class="mastercard-img"><img src="/platform/img/mastercard-logo.png" alt="" class="img-fluid"></div>
                                <input type="text" class="form-control card-number" placeholder="3344   6234   5345   5235" autocomplete='off'>
                            </div>
                            <div class="form-row mb25">
                                <div class="form-group col-md-6">
                                    <label for="" class="text-center d-block p-0">Expiration date</label>
                                    <div class="btn-group w-100">
                                        <select class="form-control expiration required card-expiry-month" id="cardMonth">
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                        <select class="form-control card-expiry-year" id="cardYear">
                                            {{ $last= date('Y')+8 }}
                                            {{ $now = date('Y') }}

                                            @for ($i = $now; $i <= $last; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-xl-4 col-md-6 cvc required">
                                    <label for="" class="text-center d-block p-0">CVC</label>
                                    <input type="text" class="form-control card-cvc" placeholder="***" autocomplete='off'>
                                </div>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Add Payment Method</label>
                            </div>
                            <div class='form-row row form-group col-xl-12 col-md-12 form-check'>
                                <div class='col-md-12 error form-group d-none'>
                                    <div class='alert-danger alert'>Please correct the errors and try
                                        again.</div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-danger active-button">Pay Now</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="pay-later">
                <a href="{{url('video')}}">PAY LATER</a>
            </div>
        </div>
    </section>
@endsection
@section('js')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
  
<script type="text/javascript">
$(function() {
    var $form         = $(".require-validation");
  $('form.require-validation').bind('submit', function(e) {
    var $form         = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs       = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid         = true;
        $errorMessage.addClass('d-none');
 
        $('.has-error').removeClass('has-error');
    $inputs.each(function(i, el) {
      var $input = $(el);
      if ($input.val() === '') {
        $input.parent().addClass('has-error');
        $errorMessage.removeClass('d-none');
        e.preventDefault();
      }
    });
  
    if (!$form.data('cc-on-file')) {
      e.preventDefault();
      Stripe.setPublishableKey($form.data('stripe-publishable-key'));
      Stripe.createToken({
        number: $('.card-number').val(),
        cvc: $('.card-cvc').val(),
        exp_month: $('.card-expiry-month').val(),
        exp_year: $('.card-expiry-year').val()
      }, stripeResponseHandler);
    }
  
  });
  
  function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('d-none')
                .find('.alert')
                .text(response.error.message);
        } else {
            // token contains id, last4, and card type
            var token = response['id'];
            // insert the token into the form so it gets submitted to the server
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
  
});
</script>
@endsection