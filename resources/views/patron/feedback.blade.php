@extends('layouts.patron')

@section('title', 'Feedback')

@push('styles')
  @vite('resources/css/feedback.css')
@endpush

@section('content')
  <div class="container">
    <div class="left-section">
      <div class="contact-box">
        <h1>We’d Love to Hear From You</h1>
        <p>Please share your experience, suggestions, or any issues you faced. Your feedback helps us improve.</p>
        <form>
          <label for="name">Full Name</label>
          <input type="text" id="name" placeholder="John Doe">

          <label for="email">Email Address</label>
          <input type="email" id="email" placeholder="you@example.com">

          <label for="feedback">Your Feedback</label>
          <textarea id="feedback" rows="5" placeholder="Write your comments here..."></textarea>

          <button type="submit">Send Feedback</button>
        </form>
      </div>

        <div class="contact-info">
            <h2>Visit Us</h2>
            <p>Address: #229 MLQ St., Purok 3, New Lower Bicutan, Taguig City, Metro Manila, Philippines</p>
            <p>Email: <a href="villasaludcateringservices@gmail.com" target="_blank">villasaludcateringservices@gmail.com</a></p>
            <p>Facebook: <a href="https://www.facebook.com/villasaludcatering" target="_blank">Villa Salud Catering Services</a></p>
            <p>Instagram: <a href="https://www.instagram.com/villasaludcatering?igsh=aDQ0bGQ4eW9pczlv" target="_blank">@villasaludcatering (IG)</a></p>
            <p>Office Tel. No. 8837-68-01/8776-68-67│Tele-fax: 8856-21-81</p>
            <p>Office CP No.0928-492-5493│0906-223-6120</p>
        </div>
    </div>

    <div class="right-section">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3860.507360975934!2d121.06038407520304!3d14.49783818089743!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397cf6c40000001%3A0x2ea834ddefa91a11!2sVilla%20Salud%20Catering%20Services!5e0!3m2!1sen!2sph!4v1717830000000!5m2!1sen!2sph"        allowfullscreen=""
        loading="lazy">
      </iframe>
    </div>
  </div>
@endsection

@push('scripts')
  @vite('resources/js/feedback.js')
@endpush
