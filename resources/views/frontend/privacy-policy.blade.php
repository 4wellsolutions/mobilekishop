@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section("keywords", "Mobiles prices, mobile specification, mobile phone features")
@section("canonical", $metas->canonical)

@section("og_graph")
@endsection

@section("content")
  <main class="main container-lg">
    <div class="container my-5 shadow-sm p-4 bg-white rounded-3">
      <h1 class="mb-4 fw-bold">{{ $metas->h1 }}</h1>
      <div class="content fs-16 text-muted lh-lg">

        {{-- ... existing content ... --}}

        <p>Welcome to MobileKiShop (MKS). We are committed to protecting your personal information and your right to
          privacy. If you have any questions or concerns about our policy, or our practices with regards to your personal
          information, please contact us.</p>

        <h3 class="mt-4 text-dark fw-bold">1. Information We Collect</h3>
        <p>We collect personal information that you voluntarily provide to us when registering at the Website, expressing
          an interest in obtaining information about us or our products and services, when participating in activities on
          the Website or otherwise contacting us.</p>
        <ul>
          <li><strong>Personal Data:</strong> Name, email address, phone number, and mailing address.</li>
          <li><strong>Log Data:</strong> We automatically collect certain information when you visit, use or navigate the
            Website. This information does not reveal your specific identity (like your name or contact information) but
            may include device and usage information, such as your IP address, browser and device characteristics,
            operating system, language preferences, and referring URLs.</li>
        </ul>

        <h3 class="mt-4 text-dark fw-bold">2. How We Use Your Information</h3>
        <p>We use personal information collected via our Website for a variety of business purposes described below. We
          process your personal information for these purposes in reliance on our legitimate business interests, in order
          to enter into or perform a contract with you, with your consent, and/or for compliance with our legal
          obligations.</p>
        <ul>
          <li>To facilitate account creation and logon process.</li>
          <li>To send administrative information to you.</li>
          <li>To fulfill and manage your orders.</li>
          <li>To post testimonials with your consent.</li>
          <li>To deliver targeted advertising to you.</li>
        </ul>

        <h3 class="mt-4 text-dark fw-bold">3. Will Your Information Be Shared with Anyone?</h3>
        <p>We only share information with your consent, to comply with laws, to provide you with services, to protect your
          rights, or to fulfill business obligations.</p>

        <h3 class="mt-4 text-dark fw-bold">4. How Long Do We Keep Your Information?</h3>
        <p>We will only keep your personal information for as long as it is necessary for the purposes set out in this
          privacy policy, unless a longer retention period is required or permitted by law (such as tax, accounting or
          other legal requirements).</p>

        <h3 class="mt-4 text-dark fw-bold">5. How Do We Keep Your Information Safe?</h3>
        <p>We aim to protect your personal information through a system of organizational and technical security measures.
          However, please also remember that we cannot guarantee that the internet itself is 100% secure.</p>

        <h3 class="mt-4 text-dark fw-bold">6. Do We Use Cookies?</h3>
        <p>We may use cookies and similar tracking technologies (like web beacons and pixels) to access or store
          information. Specific information about how we use such technologies and how you can refuse certain cookies is
          set out in our Cookie Policy.</p>

        <h3 class="mt-4 text-dark fw-bold">7. Your Privacy Rights</h3>
        <p>In some regions, you have certain rights under applicable data protection laws. These may include the right to
          request access and obtain a copy of your personal information, to request rectification or erasure; to restrict
          the processing of your personal information; and, if applicable, to data portability.</p>

        <h3 class="mt-4 text-dark fw-bold">8. Updates to This Policy</h3>
        <p>We may update this privacy policy from time to time. The updated version will be indicated by an updated
          “Revised” date and the updated version will be effective as soon as it is accessible.</p>
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