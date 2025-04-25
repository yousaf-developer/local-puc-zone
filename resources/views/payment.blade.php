<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
</head>
<body>
    <div style="text-align:center;">
        <h2>Payment Page</h2>
        @if(@$source_type == 'WALLET')
            <button type="button"><a href="{{route('wallet')}}" style="text-decoration:unset;">Go To Wallet</a></button>
        @else
            <button type="button"><a href="{{route('login')}}" style="text-decoration:unset;">Go To Home</a></button>
        @endif
    </div>
    <!-- <form action="" method="POST">
        @csrf
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount">
        <button type="submit">Pay</button>
    </form> -->

    @if(isset($orderId))
        <form id="razorpay-form"  action="{{$CALLBACK_URL}}" method="POST" style="display:none;">
            @csrf
            <script src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="{{$keyId}}"
                    data-amount="{{ $amount }}"
                    data-currency="INR"
                    data-order_id="{{ $orderId }}"
                    data-buttontext="Pay with Razorpay"
                    data-name="{{$website}}"
                    data-description="{{$website}}"
                    data-image="{{asset('assets_user/images/NewLogo.png')}}"
                    data-prefill.name="{{$username}}"
                    data-prefill.email="{{$userEmail}}"
                    data-theme.color="#F37254"></script>
            <input type="hidden" name="source_id" value="{{$source_id}}">
            <input type="hidden" name="source_type" value="{{$source_type}}">
        </form>
        <script>
            window.onload = function() {
                setTimeout(function() {
                    document.querySelector('.razorpay-payment-button').click();
                }, 1000);
            }
        </script>
    @endif
</body>
</html>
