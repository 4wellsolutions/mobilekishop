@extends('layouts.techspec')

@section('title', $metas->title)
@section('description', $metas->description)
@section('canonical', $metas->canonical)

@section("content")
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6">
        <a class="hover:text-primary hover:underline" href="{{ url('/') }}">Home</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <span class="font-medium text-slate-900 dark:text-white">Installment Calculator</span>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 mb-8 dark:bg-slate-900 dark:ring-slate-800">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white text-center mb-6">Mobile Phone Installment
                Calculator</h1>

            <form action="" method="get" id="installmentsForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <div class="md:col-span-5">
                        <select name="brand_id" id="brand_id"
                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            <option value="">Select Brand</option>
                            @if($brands = App\Models\Brand::all())
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="md:col-span-5 hidden modelDiv">
                        <select name="product_id" id="product_id"
                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            <option value="">Select Model</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <button
                            class="w-full px-4 py-2.5 bg-primary text-white text-sm font-bold rounded-lg shadow-sm hover:bg-blue-600 transition submitBtn"
                            type="submit">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="results"></div>

        <!-- Content Section -->
        <div class="prose prose-sm max-w-none text-slate-600 dark:text-slate-400 mt-8">
            <p>Purchasing a mobile phone in installments in Pakistan is a favored alternative, which empowers
                individuals to leap up to <a href="{{ url('/') }}">new smartphones</a> without paying substantial
                cash. It creates a chance for everyone, regardless of <a
                    href="https://mobilekishop.net/blog/most-expensive-mobile-phones-of-pakistan/">expensive
                    models</a> or <a href="https://mobilekishop.net/blog/cheapest-mobile-phones-in-pakistan/">more
                    affordable ones</a>, to appreciate the benefit of the most recent phone by paying a tiny amount
                of monthly proceeds. This strategy can be excellent for individuals who can't confront popping up
                all of the money at once and need to switch to the new model immediately.</p>

            <h2>Mobile Phone on Installment in Pakistan</h2>
            <p>Buying a mobile phone on easy monthly installments from a bank in Pakistan is a great way for people
                to get a new smartphone without paying all the money at once. You can pick the phone you like and
                pay for it bit by bit every month. Banks work together with phone shops to give you lots of options
                for phones you can buy on EMI&nbsp;easy monthly installment payment plans.</p>
            <p>This way is really handy for anyone who wants to spread the cost of their phone over a while, so it's
                easier to handle their money and still get the latest phone. No matter if you want the newest top
                phone or just a simple one that works well, getting it on installment from a bank helps make the
                phone you want easy to get and gentle on your wallet.</p>

            <h2>MKS Mobile Installment Calculator</h2>
            <p>Explore the MKS mobile installment calculator &ndash; Your Ultimate Tool for Finding the best mobile
                installment options across all banks in Pakistan! Our user-friendly tool is designed to simplify
                your search for the ideal mobile phone installment plan. Especially beneficial are the plans
                offering mobiles on installment without interest and options facilitated by the government of
                Pakistan, designed to make mobile ownership accessible and affordable for everyone.</p>
            <p>With the MKS Mobile Installments Plan, you have the freedom to compare different EMI plans side by
                side, ensuring you find a deal that perfectly aligns with your budget and preferences. Our
                installment calculator is updated regularly to include the latest offers and models, so you're
                always informed about the best deals. Say goodbye to the hassle of visiting multiple websites or
                banks to gather information. With just a few clicks, discover a plan that suits your financial
                situation and get closer to owning your dream phone without stress.</p>

            <h2>Mobile Phone on Installment From Bank</h2>
            <p>Many banks offer equated monthly installment plans for mobile phones in Pakistan, allowing customers
                to pay for their new device over a period ranging from 3 months up to 24 or 36 months. Among these,
                certain banks stand out by offering a special 3-month installment plan without any interest, making
                it even more appealing for those looking to buy a new phone without feeling the financial strain.</p>

            <h3>Banks Offering 3-Month Interest-Free Installment Plans</h3>
            <ul>
                <li>Meezan Bank</li>
                <li>Alfalah Bank</li>
                <li>Faysal Bank</li>
                <li>MCB Bank</li>
                <li>Standard Chartered Bank</li>
                <li>Silk Bank</li>
            </ul>

            <p>These banks cater to a variety of customer needs, ensuring that buying a mobile phone is accessible,
                affordable, and hassle-free. Whether you are in the market for the latest high-tech smartphone or a
                simple, functional handset, these banks offer flexible solutions to help you own your preferred
                mobile phone without the burden of immediate full payment.</p>

            <h3>Other Banks Providing Mobile Installment Plans</h3>
            <ul>
                <li>Askari Bank</li>
                <li>Bank Al</li>
                <li>Bank Islami</li>
                <li>HBL</li>
                <li>Summit Bank</li>
                <li>UBL</li>
            </ul>

            <p>Checking directly with the bank for their specific installment plan details, including interest
                rates, terms, and conditions, is advisable.</p>
            <p>Leverage the power of our Mobile Ki Shop's Mobile EMI Calculator to find and compare the best mobile
                phone installment options available across these banks in Pakistan.</p>
            <p>Our tool makes it easy for everyone in Lahore, Karachi, Rawalpindi, Islamabad, and other places to
                quickly find different mobile phone installment plans. Whether you want a plan that doesn&rsquo;t
                add extra cost or you need one that spreads payments over many months, our tool shows you all the
                options clearly.</p>
        </div>
    </div>
@endsection

@section("script")
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
@endsection

@section("style")
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
@endsection