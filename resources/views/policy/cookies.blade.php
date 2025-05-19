@extends('layouts.app')
@vite(['resources/css/policy.css'])

@section('title', 'Cookies - Structo')

@section('content')
<div class="policy-container">
    <div class="policy-content">
        <h1>Cookie Policy</h1>
        <p class="last-updated">Last updated: {{ date('F d, Y') }}</p>

        <section class="policy-section">
            <h2>1. What Are Cookies</h2>
            <p>Cookies are small text files stored on your device that help us provide and improve our services.</p>
        </section>

        <section class="policy-section">
            <h2>2. Types of Cookies We Use</h2>
            <ul>
                <li><strong>Essential Cookies:</strong> Required for basic site functionality</li>
                <li><strong>Analytical Cookies:</strong> Help us understand how you use our site</li>
                <li><strong>Functional Cookies:</strong> Remember your preferences</li>
                <li><strong>Marketing Cookies:</strong> Help us deliver relevant advertisements</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>3. Managing Cookies</h2>
            <p>You can control cookies through your browser settings. Note that disabling certain cookies may limit your access to some features.</p>
        </section>

        <section class="policy-section">
            <h2>4. Third-Party Cookies</h2>
            <p>Some cookies are placed by third-party services that appear on our pages. We do not control these cookies.</p>
        </section>
    </div>
</div>
@endsection