    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <h1>Student Attachment Management System</h1>
            <p>Streamline your industrial attachment process with our comprehensive management platform</p>
            @guest
                <a href="{{ route('login') }}" class="btn btn-hero">Get Started <i class="fas fa-arrow-right ms-2"></i></a>
            @else
                @if(Auth::user()->usertype == 'admin')
                    <a href="{{ url('/admin/dashboard') }}" class="btn btn-hero">Go to Dashboard <i class="fas fa-arrow-right ms-2"></i></a>
                @else
                    <a href="{{ url('/dashboard') }}" class="btn btn-hero">Go to Dashboard <i class="fas fa-arrow-right ms-2"></i></a>
                @endif
            @endguest
        </div>
    </section>
