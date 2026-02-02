<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Caregiver Dashboard') - Childcare Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/caregiver/dashboard.css'])
    @yield('styles')
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('caregiver.partials.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        // Mobile menu toggle
        const mobileToggle = document.querySelector('.mobile-toggle');
        const sidebar = document.getElementById('sidebar');

        // Logic handled by inline onclick in buttons usually, but let's keep the listener just in case
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                // Ensure sidebar and mobileToggle exist before checking containment
                if (sidebar && mobileToggle && !sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
    @yield('scripts')
</body>

</html>
