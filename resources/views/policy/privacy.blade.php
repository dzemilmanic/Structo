@extends('layouts.app')
@vite(['resources/css/policy.css'])

@section('title', 'Privacy - Structo')

@section('content')
<div class="policy-container">
    <div class="policy-content">
        <h1>Privacy Policy</h1>
        <p class="last-updated">Last updated: {{ date('F d, Y') }}</p>

        <section class="policy-section">
            <h2>1. Information We Collect</h2>
            <p>We collect information that you provide directly to us, including:</p>
            <ul>
                <li>Name and contact information</li>
                <li>Account credentials</li>
                <li>Payment information</li>
                <li>Communication preferences</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>2. How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Provide and maintain our services</li>
                <li>Process your transactions</li>
                <li>Send you important updates</li>
                <li>Improve our services</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>3. Information Sharing</h2>
            <p>We do not sell your personal information. We may share your information with:</p>
            <ul>
                <li>Service providers</li>
                <li>Legal authorities when required</li>
                <li>Business partners with your consent</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>4. Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li>Access your personal information</li>
                <li>Correct inaccurate data</li>
                <li>Request deletion of your data</li>
                <li>Opt-out of marketing communications</li>
            </ul>
        </section>
    </div>
</div>
@endsection