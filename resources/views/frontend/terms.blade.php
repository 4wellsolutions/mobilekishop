@extends('layouts.techspec')

@section('title', $metas->title)
@section('description', $metas->description)
@section("canonical", $metas->canonical)

@section("content")
  <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-8">
    <div class="bg-surface-card rounded-2xl shadow-sm p-6 md:p-10">
      <h1 class="mb-6 text-2xl md:text-3xl font-bold text-text-main">{{ $metas->h1 }}</h1>
      <div class="text-text-muted leading-relaxed space-y-4">
        <p>Welcome to MobileKiShop (MKS). By accessing our website, you agree to be bound by these Terms and Conditions.
          Please read them carefully.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">1. Agreement to Terms</h3>
        <p>By using our Website, you agree to be bound by these Terms and Conditions. If you do not agree with all of
          these Terms and Conditions, then you are expressly prohibited from using the site and you must discontinue use
          immediately.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">2. Intellectual Property Rights</h3>
        <p>Unless otherwise indicated, the Site is our proprietary property and all source code, databases, functionality,
          software, website designs, audio, video, text, photographs, and graphics on the Site and the trademarks, service
          marks, and logos contained therein are owned or controlled by us or licensed to us.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">3. User Representations</h3>
        <p>By using the Site, you represent and warrant that:</p>
        <ul class="list-disc list-inside space-y-1 pl-2">
          <li>All registration information you submit will be true, accurate, current, and complete.</li>
          <li>You will maintain the accuracy of such information and promptly update such registration information as
            necessary.</li>
          <li>You have the legal capacity and you agree to comply with these Terms and Conditions.</li>
          <li>You will not use the Site for any illegal or unauthorized purpose.</li>
        </ul>

        <h3 class="mt-6 text-text-main font-bold text-lg">4. Prohibited Activities</h3>
        <p>You may not access or use the Site for any purpose other than that for which we make the Site available. The
          Site may not be used in connection with any commercial endeavors except those that are specifically endorsed or
          approved by us.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">5. Modifications and Interruptions</h3>
        <p>We reserve the right to change, modify, or remove the contents of the Site at any time or for any reason at our
          sole discretion without notice. However, we have no obligation to update any information on our Site.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">6. Accuracy of Information</h3>
        <p>MobileKiShop strives to provide accurate information regarding mobile prices, specifications, and features.
          However, we do not guarantee the absolute accuracy of the data. Prices and specifications are subject to change
          without prior notice.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">7. Governing Law</h3>
        <p>These Terms and Conditions and your use of the Site are governed by and construed in accordance with the laws
          of the Islamic Republic of Pakistan.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">8. Contact Us</h3>
        <p>In order to resolve a complaint regarding the Site or to receive further information regarding use of the Site,
          please contact us at our provided contact details.</p>
      </div>
    </div>
  </main>
@endsection

@section("script")
  <script type="application/ld+json">
      {
        "@@context": "https://schema.org/", 
        "@@type": "BreadcrumbList", 
        "itemListElement": [{
          "@@type": "ListItem", 
          "position": 1, 
          "name": "Home",
          "item": "{{url('/')}}/"  
        },{
          "@@type": "ListItem", 
          "position": 2, 
          "name": "{{$metas->name}}",
          "item": "{{$metas->canonical}}"  
        }]
      }
      </script>
@endsection