@extends('layouts.patron')

@section('title', 'Manual Reservation')

@push('styles')
    @vite('resources/css/p_mreserve.css')
@endpush

@section('content')
<!-- Agreement Modal -->
<div id="agreementModal" class="modal agreement-modal">
    <div class="guidelines-content" id="agreementContent">
        <h2>Reservation Guidelines</h2>

        <section>
            <h2>Reservation & Payment Policy</h2>
            <p><strong>Reservation Fee:</strong> Php 25,000.00 (Venue & Catering) / Php 5,000.00 (Swimming Pool). Non-refundable, non-consumable, non-transferable.</p>
            <p><strong>Payment Terms:</strong> 50% due one month prior, full payment due two weeks before event. Cash or approved credit cards only. Bank deposits must be advised via fax/email.</p>
            <p><strong>Cancellation Charges:</strong></p>
            <ul>
                <li>25%: 8 months or more before the event</li>
                <li>50%: 4 to 7 months before the event</li>
                <li>75%: 2 to 3 months before the event</li>
                <li>100%: 60 days or less before the event</li>
            </ul>
        </section>

        <section>
            <h2>Catering Coverage & Policies</h2>
            <ul>
                <li>Excess guests will be charged accordingly.</li>
                <li>Up to 10% reduction in guest count allowed 2 weeks before the event.</li>
                <li>No refund for unconsumed catering items.</li>
                <li>Clients are liable for toll fees and external catering incidentals.</li>
                <li>Outside food/drinks are prohibited unless pre-approved in writing.</li>
                <li>Villa Salud is not liable for spoiled food due to client delay in serving.</li>
                <li>All food must be consumed immediately; Villa Salud is not liable for takeout food-related illness.</li>
            </ul>
        </section>

        <section>
            <h2>Function Set-up</h2>
            <ul>
                <li>Villa Salud controls layout and equipment setup.</li>
                <li>No attachment to walls, ceilings, or floors is allowed.</li>
                <li>Damages by client/guests will be charged based on market value.</li>
                <li>Client must secure permits for offsite catering setups.</li>
            </ul>
        </section>

        <section>
            <h2>Safety and Security</h2>
            <ul>
                <li>No flammable or explosive materials allowed.</li>
                <li>No fireworks allowed during events.</li>
                <li>Villa Salud is not responsible for lost or damaged personal items.</li>
                <li>Client assumes liability for any injury or damages caused.</li>
            </ul>
        </section>

        <section>
            <h2>Swimming Pool Rental Terms</h2>
            <p><strong>Rates:</strong> AM (7:00 AM – 5:00 PM): Php 8,500 | PM (7:00 PM – 5:00 AM): Php 9,500</p>
            <p><strong>Standard Amenities:</strong> 50 monobloc chairs, 6 tables, buffet table, griller</p>
            <p><strong>Extra Charges:</strong></p>
            <ul>
                <li>Videoke: Php 1,000</li>
                <li>Mobile System: Php 8,000 (4 hrs)</li>
                <li>Room: Php 2,500 (12 hrs)</li>
                <li>Photobooth: Php 5,000 (3 hrs)</li>
                <li>Mobile bar: Php 5,000 and up</li>
            </ul>
            <p><strong>Important Notes:</strong></p>
            <ul>
                <li>No lifeguard on duty – swim at your own risk.</li>
                <li>Children must be accompanied by adults.</li>
                <li>Clients must follow capacity limits and LGU guidelines.</li>
                <li>Overtime, excess guests, and damages incur additional charges.</li>
            </ul>
        </section>

        <section>
            <h2>Guidelines for Outside Caterers & Suppliers</h2>
            <ul>
                <li>No removal or rearranging of Villa Salud’s equipment without permission.</li>
                <li>Cooking is not allowed. Food warming only.</li>
                <li>No dishwashing in premises. Clean-up required.</li>
                <li>Sufficient garbage bags must be provided by suppliers.</li>
                <li>Staff must wear proper uniforms; no casual clothing allowed.</li>
                <li>No bathing for staff in venue.</li>
                <li>Ingress: AM – 6:00 AM | PM – 6:00 PM</li>
                <li>Vehicles cannot park inside Villa Salud. Coordinate for parking.</li>
                <li>Vicinity lights/fans allowed 30 mins before the event only.</li>
                <li>All equipment must be cleared post-event with gate pass.</li>
            </ul>
        </section>

        <section>
            <h2>Force Majeure Clause</h2>
            <p>Villa Salud is not liable for performance failures caused by uncontrollable events (e.g., natural disasters, war, governmental regulations). In such cases, Villa Salud reserves the right to provide alternative accommodations or menus as fulfillment of the contract.</p>
        </section>

        <div style="text-align: center; margin-top: 20px;">
            <button id="agreeButton" disabled>I Agree</button>
        </div>
    </div>
</div>

<div class="container">
    <div class="reservation-container">
        <h2>Manual Reservation Form</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

 <form method="POST" action="{{ route('patron.p_mreserve.submit') }}">
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            </div>

            {{-- Contact --}}
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}" required>
            </div>

            {{-- Event Type (ENUM) --}}
            <div class="form-group">
                <label for="event_type">Event Type</label>
                <select name="event_type" id="event_type" required>
                    <option disabled selected value="">Select Event Type</option>
                    <option value="Baptismal Package">Baptismal Package</option>
                    <option value="Birthday Package">Birthday Package</option>
                    <option value="Debut Package">Debut Package</option>
                    <option value="Wedding Package">Wedding Package</option>
                </select>
            </div>

            {{-- Other Event Type --}}
            <div class="form-group">
                <label for="other_event_type">Other Event Type (optional)</label>
                <input type="text" name="other_event_type" id="other_event_type" value="{{ old('other_event_type') }}">
            </div>

            {{-- Theme (ENUM) --}}
            <div class="form-group">
                <label for="theme_motif">Theme / Motif</label>
                <select name="theme_motif" id="theme_motif" required>
                    <option disabled selected value="">Select Theme</option>
                    <option value="Floral">Floral</option>
                    <option value="Rustic">Rustic</option>
                    <option value="Elegant">Elegant</option>
                    <option value="Beach">Beach</option>
                    <option value="Modern">Modern</option>
                </select>
            </div>

            {{-- Other Theme --}}
            <div class="form-group">
                <label for="other_theme_motif">Other Theme (optional)</label>
                <input type="text" name="other_theme_motif" id="other_theme_motif" value="{{ old('other_theme_motif') }}">
            </div>

            {{-- Venue (ENUM) --}}
            <div class="form-group">
                <label for="venue">Venue</label>
                <select name="venue" id="venue" required>
                    <option disabled selected value="">Select Venue</option>
                    <option value="Villa I">Villa I</option>
                    <option value="Villa II">Villa II</option>
                    <option value="Elizabeth Hall">Elizabeth Hall</option>
                    <option value="Private Venue">Private Venue</option>
                </select>
            </div>

            {{-- Other Venue --}}
            <div class="form-group">
                <label for="other_venue">Other Venue (optional)</label>
                <input type="text" name="other_venue" id="other_venue" value="{{ old('other_venue') }}">
            </div>

            {{-- Date --}}
            <div class="form-group">
                <label for="date">Event Date</label>
                <input type="date" name="date" id="date" value="{{ old('date') }}" required>
            </div>

            {{-- Time --}}
            <div class="form-group">
                <label for="time">Event Time</label>
                <input type="time" name="time" id="time" value="{{ old('time') }}" required>
            </div>

            {{-- Message --}}
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" rows="4" required>{{ old('message') }}</textarea>
            </div>

            <div class="form-group">
                <button type="submit">Submit Reservation</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/p_mreserve.js')
@endpush
