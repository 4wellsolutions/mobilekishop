@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section("canonical", $metas->canonical)

@section("content")
  <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-8">
    <div class="bg-surface-card rounded-2xl shadow-sm p-6 md:p-10">
      <h1 class="mb-6 text-2xl md:text-3xl font-bold text-text-main">{{ $metas->h1 }}</h1>
      <div class="text-text-muted leading-relaxed space-y-4">
        <p>Welcome to MobileKiShop (MKS). We are committed to protecting your privacy and ensuring a safe online
          experience. This Privacy Policy outlines how we collect, use, and safeguard your information.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">1. Information We Collect</h3>
        <p>We may collect the following types of information:</p>
        <ul class="list-disc list-inside space-y-1 pl-2">
          <li>Personal information (name, email) when you register or contact us.</li>
          <li>Usage data including IP address, browser type, and pages visited.</li>
          <li>Cookies and similar tracking technologies for analytics and personalization.</li>
        </ul>

        <h3 class="mt-6 text-text-main font-bold text-lg">2. How We Use Your Information</h3>
        <p>We use your information to:</p>
        <ul class="list-disc list-inside space-y-1 pl-2">
          <li>Provide and improve our services.</li>
          <li>Respond to your inquiries and customer support requests.</li>
          <li>Send newsletters and promotional communications (with your consent).</li>
          <li>Analyze website usage to enhance user experience.</li>
        </ul>

        <h3 class="mt-6 text-text-main font-bold text-lg">3. Data Security</h3>
        <p>We implement reasonable security measures to protect your personal information. However, no method of
          transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">4. Third-Party Services</h3>
        <p>We may use third-party analytics and advertising services (e.g., Google Analytics, Google AdSense). These
          services may collect information about your use of our site. Please review their respective privacy policies.
        </p>

        <h3 class="mt-6 text-text-main font-bold text-lg">5. Cookies</h3>
        <p>Our website uses cookies to enhance your experience. You can control cookie settings through your browser. By
          continuing to use our site, you consent to our use of cookies.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">6. Children's Privacy</h3>
        <p>Our services are not directed to children under the age of 13. We do not knowingly collect personal information
          from children. If you are a parent or guardian and believe your child has provided us with personal information,
          please contact us.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">7. Your Rights</h3>
        <p>Depending on your jurisdiction, you may have the right to access, correct, or delete your personal data. To
          exercise these rights, please contact us through our provided channels.</p>

        <h3 class="mt-6 text-text-main font-bold text-lg">8. Updates to This Policy</h3>
        <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with a revised
          "Last Updated" date and the updated version will be effective as soon as it is accessible.</p>
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