<!-- Mobile Bottom Nav -->
<nav class="navbar fixed-bottom bg-success shadow-sm d-md-none">
    <div class="container d-flex justify-content-around">
        <a href="{{ route('user.user.home') }}" class="nav-link text-center text-light">
            <i class="fas fa-home fa-lg"></i>
            <div style="font-size: 12px;">Home</div>
        </a>
        <a href="{{ route('user.password.change') }}" class="nav-link text-center text-light">
            <i class="fas fa-bell fa-lg"></i>
            <div style="font-size: 12px;">Password Change</div>
        </a>
        <a href="{{ route('user.profile.view') }}" class="nav-link text-center text-light">
            <i class="fas fa-user fa-lg"></i>
            <div style="font-size: 12px;">Profile</div>
        </a>
    </div>
</nav>

<!-- Footer -->
<footer class="bg-dark text-white py-3 mt-4 d-none d-md-block shadow-sm">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <p class="mb-2 mb-md-0">© {{ date('Y') }} User Dashboard. All rights reserved.</p>
        <p class="mb-0">Developed by <a href="#" class="text-white">Nazrul Islam Suzon</a></p>
    </div>
</footer>
