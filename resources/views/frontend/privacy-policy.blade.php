@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", $metas->canonical)

@section("og_graph") @stop

@section("content")

<style type="text/css">
    .mobileImage {
        height: 150px !important;
    }

    .offcanvas-backdrop {
        background: #FFF !important;
    }

    .widget a {
        text-decoration: none !important;
        color: #777;
    }

    .widget-title a {
        text-decoration: none !important;
        font-family: Poppins, sans-serif;
        color: #343a40;
        font-size: 18px;
    }

    .widget {
        border-bottom: 1px solid #e7e7e7;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: rgb(231, 231, 231);
        border: 1px solid #dee2e6 !important;
        margin-top: 5px;
        margin-right: 5px;
        margin-left: 5px;
    }

    .widget-body li a {
        font-size: 14px;
    }

    .nav-tabs.nav-item a {
        text-decoration: none !important;
        color: #343a40;
    }

    .nav-tabs.nav-tabs .nav-link {
        color: #31343a;
        border: none;
    }

    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        color: black !important;
        border-bottom: 2px solid #000000 !important;
    }

    .nav-tabs.nav-link:hover {
        border-bottom: 2px solid #000000 !important;
    }

    h1,
    .h1,
    h2,
    .h2,
    h3,
    .h3,
    h4,
    .h4,
    h5,
    .h5,
    h6,
    .h6 {
        font-weight: 700;
        line-height: 1.1;
        font-family: Poppins, sans-serif;
    }

    body {
        font-family: "Open Sans", sans-serif;
    }

    @media(max-width: 576px) {
        .cameraBlock {
            border: none !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .screenBlock {
            border-bottom: 1px solid #dee2e6 !important;
        }

        .mobileTable tr td:first-child {
            display: none;
        }

        .mobileTable tr th {
            color: #dc3545 !important
        }

        .table {
            font-size: .8rem;
        }

        .imgDiv {
            height: 120px;
        }

        .detailDiv {
            height: 120px;
        }

        .product-title {
            font-size: 14px;
            font-weight: normal;
        }

        .product-price {
            font-size: 16px;
        }
    }

    @media(min-width: 577px) {
        .imgDiv {
            height: 130px;
        }

        .detailDiv {
            height: 130px;
        }

        .product-title {
            font-size: 18px;
            font-weight: normal;
        }

        .product-price {
            font-size: 20px;
        }
    }

    .nav-tabs .nav-link {
        font-size: .9rem;
        padding-right: 7px;
        padding-left: 7px;
    }

    .mobile_image {
        max-height: 160px;
        width: auto;
    }

    .product-title>a {
        text-decoration: none;
    }

    .category>a {
        text-decoration: none;
    }

    .product-label {
        animation: label-groups 2s infinite;
        padding: 3px 6px;
        background-color: #fe5858;
        font-size: 11px;
        color: white;
        border-radius: 20px;
    }

    .label-groups {
        position: absolute;
        top: -0.3rem;
        right: 1.0rem;
    }

    @keyframes label-groups {
        0% {
            background-color: #ed6161
        }

        /*25%     { background-color: #1056c0;}*/
        50% {
            background-color: #ed6161;
        }

        /*75%     { background-color: #254878;}*/
        100% {
            background-color: #7661ed;
        }
    }

    .product-default .group-new .product-label {
        font-size: 8px;
        animation: group-new 2s infinite;
    }

    @keyframes group-new {
        0% {
            background-color: #8abf6f
        }

        /*25%     { background-color: #1056c0;}*/
        /*50%     { background-color: #ed6161;}*/
        /*75%     { background-color: #254878;}*/
        100% {
            background-color: #3e8f15;
        }
    }

    .page-link {
        color: #000000 !important;
    }
</style>
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="16">
                    </a></li>
                <li class="breadcrumb-item active text-secondary" aria-current="page">
                    {{isset($brand->name) ? Str::title($brand->name) : $metas->name}}</li>
            </ol>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="row">
                    <h1>Privacy Policy for Mobile Ki Shop</h1>
                    <p>At Mobile Ki Shop, accessible from mobileki.site, one of our main priorities is the privacy of
                        our visitors. This Privacy Policy document contains types of information that is collected and
                        recorded by Mobile Ki Shop and how we use it.</p>

                    <p>If you have additional questions or require more information about our Privacy Policy, do not
                        hesitate to contact us.</p>

                    <p>This Privacy Policy applies only to our online activities and is valid for visitors to our
                        website with regards to the information that they shared and/or collect in Mobile Ki Shop. This
                        policy is not applicable to any information collected offline or via channels other than this
                        website.</p>

                    <h2>Consent</h2>

                    <p>By using our website, you hereby consent to our Privacy Policy and agree to its terms.</p>

                    <h2>Information we collect</h2>

                    <p>The personal information that you are asked to provide, and the reasons why you are asked to
                        provide it, will be made clear to you at the point we ask you to provide your personal
                        information.</p>
                    <p>If you contact us directly, we may receive additional information about you such as your name,
                        email address, phone number, the contents of the message and/or attachments you may send us, and
                        any other information you may choose to provide.</p>
                    <p>When you register for an Account, we may ask for your contact information, including items such
                        as name, company name, address, email address, and telephone number.</p>

                    <h2>How we use your information</h2>

                    <p>We use the information we collect in various ways, including to:</p>

                    <ul class="ms-4 ms-md-5">
                        <li>Provide, operate, and maintain our website</li>
                        <li>Improve, personalize, and expand our website</li>
                        <li>Understand and analyze how you use our website</li>
                        <li>Develop new products, services, features, and functionality</li>
                        <li>Communicate with you, either directly or through one of our partners, including for customer
                            service, to provide you with updates and other information relating to the website, and for
                            marketing and promotional purposes</li>
                        <li>Send you emails</li>
                        <li>Find and prevent fraud</li>
                    </ul>

                    <h2>Log Files</h2>

                    <p>Mobile Ki Shop follows a standard procedure of using log files. These files log visitors when
                        they visit websites. All hosting companies do this and a part of hosting services' analytics.
                        The information collected by log files include internet protocol (IP) addresses, browser type,
                        Internet Service Provider (ISP), date and time stamp, referring/exit pages, and possibly the
                        number of clicks. These are not linked to any information that is personally identifiable. The
                        purpose of the information is for analyzing trends, administering the site, tracking users'
                        movement on the website, and gathering demographic information.</p>

                    <h2>Cookies and Web Beacons</h2>

                    <p>Like any other website, Mobile Ki Shop uses 'cookies'. These cookies are used to store
                        information including visitors' preferences, and the pages on the website that the visitor
                        accessed or visited. The information is used to optimize the users' experience by customizing
                        our web page content based on visitors' browser type and/or other information.</p>

                    <h2>Google DoubleClick DART Cookie</h2>

                    <p>Google is one of a third-party vendor on our site. It also uses cookies, known as DART cookies,
                        to serve ads to our site visitors based upon their visit to www.website.com and other sites on
                        the internet. However, visitors may choose to decline the use of DART cookies by visiting the
                        Google ad and content network Privacy Policy at the following URL â€“ <a
                            href="https://policies.google.com/technologies/ads">https://policies.google.com/technologies/ads</a>
                    </p>

                    <h2>Our Advertising Partners</h2>

                    <p>Some of advertisers on our site may use cookies and web beacons. Our advertising partners are
                        listed below. Each of our advertising partners has their own Privacy Policy for their policies
                        on user data. For easier access, we hyperlinked to their Privacy Policies below.</p>

                    <ul class="ms-4 ms-md-5">
                        <li>
                            <p>Google</p>
                            <p><a
                                    href="https://policies.google.com/technologies/ads">https://policies.google.com/technologies/ads</a>
                            </p>
                        </li>
                    </ul>

                    <h2>Advertising Partners Privacy Policies</h2>

                    <P>You may consult this list to find the Privacy Policy for each of the advertising partners of
                        Mobile Ki Shop.</p>

                    <p>Third-party ad servers or ad networks uses technologies like cookies, JavaScript, or Web Beacons
                        that are used in their respective advertisements and links that appear on Mobile Ki Shop, which
                        are sent directly to users' browser. They automatically receive your IP address when this
                        occurs. These technologies are used to measure the effectiveness of their advertising campaigns
                        and/or to personalize the advertising content that you see on websites that you visit.</p>

                    <p>Note that Mobile Ki Shop has no access to or control over these cookies that are used by
                        third-party advertisers.</p>

                    <h2>Third Party Privacy Policies</h2>

                    <p>Mobile Ki Shop's Privacy Policy does not apply to other advertisers or websites. Thus, we are
                        advising you to consult the respective Privacy Policies of these third-party ad servers for more
                        detailed information. It may include their practices and instructions about how to opt-out of
                        certain options. </p>

                    <p>You can choose to disable cookies through your individual browser options. To know more detailed
                        information about cookie management with specific web browsers, it can be found at the browsers'
                        respective websites.</p>

                    <h2>CCPA Privacy Rights (Do Not Sell My Personal Information)</h2>

                    <p>Under the CCPA, among other rights, California consumers have the right to:</p>
                    <p>Request that a business that collects a consumer's personal data disclose the categories and
                        specific pieces of personal data that a business has collected about consumers.</p>
                    <p>Request that a business delete any personal data about the consumer that a business has
                        collected.</p>
                    <p>Request that a business that sells a consumer's personal data, not sell the consumer's personal
                        data.</p>
                    <p>If you make a request, we have one month to respond to you. If you would like to exercise any of
                        these rights, please contact us.</p>

                    <h2>GDPR Data Protection Rights</h2>

                    <p>We would like to make sure you are fully aware of all of your data protection rights. Every user
                        is entitled to the following:</p>
                    <p>The right to access â€“ You have the right to request copies of your personal data. We may charge
                        you a small fee for this service.</p>
                    <p>The right to rectification â€“ You have the right to request that we correct any information you
                        believe is inaccurate. You also have the right to request that we complete the information you
                        believe is incomplete.</p>
                    <p>The right to erasure â€“ You have the right to request that we erase your personal data, under
                        certain conditions.</p>
                    <p>The right to restrict processing â€“ You have the right to request that we restrict the
                        processing of your personal data, under certain conditions.</p>
                    <p>The right to object to processing â€“ You have the right to object to our processing of your
                        personal data, under certain conditions.</p>
                    <p>The right to data portability â€“ You have the right to request that we transfer the data that we
                        have collected to another organization, or directly to you, under certain conditions.</p>
                    <p>If you make a request, we have one month to respond to you. If you would like to exercise any of
                        these rights, please contact us.</p>

                    <h2>Children's Information</h2>

                    <p>Another part of our priority is adding protection for children while using the internet. We
                        encourage parents and guardians to observe, participate in, and/or monitor and guide their
                        online activity.</p>

                    <p>Mobile Ki Shop does not knowingly collect any Personal Identifiable Information from children
                        under the age of 13. If you think that your child provided this kind of information on our
                        website, we strongly encourage you to contact us immediately and we will do our best efforts to
                        promptly remove such information from our records.</p>

                </div>

            </div>

        </div><!-- End .container -->
    </div>
</main><!-- End .main -->
@stop




@section("script")
<script type="text/javascript">
    var base_url = "{{Request::url()}}";
    $(".select-filter").change(function () {
        console.log("change");
        $(".formFilter").submit();
    });
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org/", 
  "@type": "BreadcrumbList", 
  "itemListElement": [{
    "@type": "ListItem", 
    "position": 1, 
    "name": "Home",
    "item": "{{url('/')}}/"  
  },{
    "@type": "ListItem", 
    "position": 2, 
    "name": "{{$metas->name}}",
    "item": "{{$metas->canonical}}"  
  }]
}
</script>
@stop

@section("style")
<style type="text/css">
    .filter-select {
        height: 4rem !important;
    }

    .icon-angle-right {
        background: #928989ad;
        margin-left: 10px;
        padding-left: 15px !important;
        padding-right: 12px !important;
        padding-bottom: 3px !important;
    }

    .icon-angle-left {
        background: #928989ad;
        margin-left: 10px;
        padding-left: 12px !important;
        padding-right: 15px !important;
        padding-bottom: 3px !important;
    }

    .select-filter:after {
        margin-top: 8px !important;
    }

    #sort_filter:after {
        margin-top: -1px !important;
    }

    .fs-12 {
        font-size: 12px !important;
    }

    .fs-14 {
        font-size: 14px !important;
    }

    .fs-15 {
        font-size: 15px !important;
    }

    .fs-16 {
        font-size: 16px !important;
    }
</style>
@stop