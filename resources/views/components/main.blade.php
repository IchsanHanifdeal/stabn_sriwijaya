<!DOCTYPE html>
<html lang="en" data-theme="lemonade">

<head>
    @include('components.head')
    <style>
        .toast {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .toast-show {
            opacity: 1;
        }
    </style>

</head>

<body class="flex flex-col mx-auto min-h-screen">
    <main class="{{ $class ?? 'p-4' }}" role="main">
        {{ $slot }}

        <div id="toast-container" class="fixed bottom-5 z-50"></div>

        <script>
            function showToast(message, type) {
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.classList.add('toast', `toast-${type}`, 'shadow-lg', 'mb-4', 'bg-base-200', 'p-4', 'rounded-lg', 'flex',
                    'items-center', 'justify-between');
                toast.innerHTML = `
        <div class="flex-grow">${message}
        <button class="btn btn-sm btn-circle btn-ghost" onclick="this.parentElement.remove()">✕</button></div>
        `;
                toastContainer.appendChild(toast);

                setTimeout(() => {
                    toast.classList.add('toast-show');
                }, 100);

                setTimeout(() => {
                    toast.classList.remove('toast-show');
                    setTimeout(() => {
                        toast.remove();
                    }, 500);
                }, 5000);
            }

            @if (session('toast'))
                showToast('{{ session('toast.message') }}', '{{ session('toast.type') }}');
            @endif
        </script>

    </main>
</body>

</html>
