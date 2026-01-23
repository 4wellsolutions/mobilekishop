@extends("layouts.frontend")

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords","Mobiles prices, mobile specification, mobile phone features")

@section("canonical",$metas->canonical)

@section("og_graph") @stop

@section("content")

<main class="main my-2 my-md-4">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12 py-2">
                <form action="" method="get" id="installmentsForm">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center">Mobile Phone Installment Calculator</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-5 my-2">
                        <select class="form-control rounded" name="brand_id" id="brand_id">
                            <option value="">Select Brand</option>
                            @if($brands = App\Brand::all())
                            @foreach($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-5 my-2 modelDiv d-none">
                        <select class="form-control" name="product_id" id="product_id">
                            <option value="">Select Model</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 my-2">
                        <button class="btn btn-primary bg-blue w-100 submitBtn h-100" type="submit">Submit</button>
                    </div>
                </div>
                </form>
                <div class="results"></div>
            </div>            
            <div class="col-12">
                <p>Purchasing a mobile phone in installments in Pakistan is a favored alternative, which empowers individuals to leap up to <a href="{{url('/')}}">new smartphones</a> without paying substantial cash. It creates a chance for everyone, regardless of <a href="https://mobilekishop.net/blog/most-expensive-mobile-phones-of-pakistan/">expensive models</a> or <a href="https://mobilekishop.net/blog/cheapest-mobile-phones-in-pakistan/">more affordable ones</a>, to appreciate the benefit of the most recent phone by paying a tiny amount of monthly proceeds. This strategy can be excellent for individuals who can't confront popping up all of the money at once and need to switch to the new model immediately.</p>
                
                <h2>Mobile Phone on Installment in Pakistan</h2>
                    <p>Buying a mobile phone on easy monthly installments from a bank in Pakistan is a great way for people to get a new smartphone without paying all the money at once. You can pick the phone you like and pay for it bit by bit every month. Banks work together with phone shops to give you lots of options for phones you can buy on EMI&nbsp;easy monthly installment payment plans.</p>
                    <p>This way is really handy for anyone who wants to spread the cost of their phone over a while, so it's easier to handle their money and still get the latest phone. No matter if you want the newest top phone or just a simple one that works well, getting it on installment from a bank helps make the phone you want easy to get and gentle on your wallet.</p>

                <h2>MKS Mobile Installment Calculator</h2>
                    <p>Explore the MKS mobile installment calculator &ndash; Your Ultimate Tool for Finding the best mobile installment options across all banks in Pakistan! Our user-friendly tool is designed to simplify your search for the ideal mobile phone installment plan. Especially beneficial are the plans offering mobiles on installment without interest and options facilitated by the government of Pakistan, designed to make mobile ownership accessible and affordable for everyone.</p>
                    <p>With the MKS Mobile Installments Plan, you have the freedom to compare different EMI plans side by side, ensuring you find a deal that perfectly aligns with your budget and preferences. Our installment calculator is updated regularly to include the latest offers and models, so you're always informed about the best deals. Say goodbye to the hassle of visiting multiple websites or banks to gather information. With just a few clicks, discover a plan that suits your financial situation and get closer to owning your dream phone without stress.</p>

                <h2>Mobile Phone on Installment From Bank</h2>
                    <p>Many banks offer equated monthly installment plans for mobile phones in Pakistan, allowing customers to pay for their new device over a period ranging from 3 months up to 24 or 36 months. Among these, certain banks stand out by offering a special 3-month installment plan without any interest, making it even more appealing for those looking to buy a new phone without feeling the financial strain.</p>

                <h3>Banks Offering 3-Month Interest-Free Installment Plans</h3>
                    <ul>
                        <li aria-level="1">Meezan Bank</li>
                        <li aria-level="1">Alfalah Bank</li>
                        <li aria-level="1">Faysal Bank</li>
                        <li aria-level="1">MCB Bank</li>
                        <li aria-level="1">Standard Chartered Bank</li>
                        <li aria-level="1">Silk Bank</li>
                    </ul>

                <p>These banks cater to a variety of customer needs, ensuring that buying a mobile phone is accessible, affordable, and hassle-free. Whether you are in the market for the latest high-tech smartphone or a simple, functional handset, these banks offer flexible solutions to help you own your preferred mobile phone without the burden of immediate full payment. This initiative not only makes technology more accessible but also aligns with customer-centric financing, allowing individuals to enjoy the benefits of their new mobile phone with financial ease and convenience.</p>

                <h3>Other Banks Providing Mobile Installment Plans</h3>
                    <ul>
                        <li aria-level="1">Askari Bank</li>
                        <li aria-level="1">Bank Al</li>
                        <li aria-level="1">Bank Islami</li>
                        <li aria-level="1">HBL</li>
                        <li aria-level="1">Summit Bank</li>
                        <li aria-level="1">UBL</li>
                    </ul>
                    
                <p>Checking directly with the bank for their specific installment plan details, including interest rates, terms, and conditions, is advisable. Each of these banks may offer competitive installment plans that can suit different budgetary needs and preferences, even if they are not interest-free for the 3 months.</p>
                <p>Leverage the power of our Mobile Ki Shop's Mobile EMI Calculator to find and compare the best mobile phone installment options available across these banks in Pakistan. Our intuitive platform is designed to streamline your search and offer a comprehensive overview of the various installment plans, including those special 3-month interest-free options provided by banks like Meezan Bank, Alfalah Bank, Faysal Bank, MCB Bank, Standard Chartered Bank, and Silk Bank.</p>
                <p>Our tool makes it easy for everyone in Lahore, Karachi, Rawalpindi, Islamabad, and other places to quickly find different mobile phone installment plans. Whether you want a plan that doesn&rsquo;t add extra cost or you need one that spreads payments over many months, our tool shows you all the options clearly. It helps you decide what&rsquo;s best for you without having to check lots of websites or visit bank offices.</p>
            </div>
        </div>
    </div>
</main><!-- End .main -->
@stop

@section("script")
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
      var element = document.getElementById('brand_id');
      var choices = new Choices(element, {
        searchResultLimit: 5,
        searchEnabled: true,
        searchChoices: true,
        removeItemButton: true,
        shouldSort: true,
      });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {

        // Define a variable outside of your event handler to hold the Choices instance
        var productChoices;

        $('#brand_id').change(function() {
            $(".modelDiv").addClass("d-none");
            
            // Destroy the existing Choices instance if it exists
            if (productChoices) {
                productChoices.destroy();
            }

            var brandId = $(this).val();
            $.ajax({
                url: "{{route('get.products.by.brand')}}",
                type: 'GET',
                data: { brand_id: brandId },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $(".modelDiv").removeClass("d-none").fadeIn();
                    var productSelect = $('#product_id');
                    productSelect.empty();
                    
                    // Append default or placeholder option
                    productSelect.append($('<option>', {
                        value: '',
                        text: 'Select Model'
                    }));
                    
                    if(response.products.length > 0) {
                        $.each(response.products, function(index, product) {
                            productSelect.append($('<option>', {
                                value: product.id,
                                price: product.price_in_pkr,
                                text: product.name
                            }));
                        });
                    }
                    
                    
                    
                    // Create a new Choices instance
                    productChoices = new Choices('#product_id', {
                        searchResultLimit: 5,
                        searchEnabled: true,
                        searchChoices: true,
                        removeItemButton: true,
                        shouldSort: true,
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + "\n" + error);
                }
            });
        });


        $('#installmentsForm').submit(function(e) {
            e.preventDefault();
            var price = $("#product_id").find(':selected').attr('price');
            $(".results").html("");
            var submitBtn = $('.submitBtn');
            var originalText = submitBtn.html();
            submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            submitBtn.attr('disabled', true);
            $.ajax({
                url: "{{route('installment.plan.post')}}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.success === false) {
                        // Clear previous errors
                        $(".results").empty();
                        
                        // Assuming errors are returned as an array
                        var errors = response.errors;
                        var errorHtml = '<ul>';
                        for (var i = 0; i < errors.length; i++) {
                            errorHtml += '<li>' + errors[i] + '</li>';
                        }
                        errorHtml += '</ul>';
                        
                        $(".results").html(errorHtml);
                    } else {
                        // Handle success scenario
                        $(".results").html(response);
                    }
                    submitBtn.html(originalText);
                    submitBtn.removeAttr('disabled');
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + "\n" + error);
                }
            });
        });
    });
</script>
@stop
@section("style")
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
@stop

