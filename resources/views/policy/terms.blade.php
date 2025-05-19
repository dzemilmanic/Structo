@extends('layouts.app')
@vite(['resources/css/policy.css'])

@section('title', 'Terms - Structo')

@section('content')
<div class="policy-container">
    <div class="policy-content">
        <h1>Terms of Service</h1>
        <p class="last-updated">Last updated: {{ date('F d, Y') }}</p>

        <section class="policy-section">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using our services, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>
        </section>

        <section class="policy-section">
            <h2>2. User Responsibilities</h2>
            <p>You are responsible for:</p>
            <ul>
                <li>Maintaining account security</li>
                <li>Providing accurate information</li>
                <li>Complying with all applicable laws</li>
                <li>Respecting intellectual property rights</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>3. Service Modifications</h2>
            <p>We reserve the right to:</p>
            <ul>
                <li>Modify or discontinue services</li>
                <li>Update these terms at any time</li>
                <li>Restrict access to certain features</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>4. Limitation of Liability</h2>
            <p>We shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of our services.</p>
        </section>
    </div>
</div>
@endsection