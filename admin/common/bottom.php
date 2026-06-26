    </main>
    <footer class="bg-card border-t border-gray-800 mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500 gap-2">
            <p>&copy; <?= date('Y') ?> SM YouTube Link Manager. All rights reserved.</p>
            <p>Admin Dashboard</p>
        </div>
    </footer>

    <script>
        // Disable Right Click
        document.addEventListener('contextmenu', event => event.preventDefault());

        // Disable Keyboard Shortcuts
        document.addEventListener('keydown', function(e) {
            // Disable F12, Inspect, Source
            if(e.keyCode === 123 || 
              (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) || 
              (e.ctrlKey && e.keyCode === 85)) {
                e.preventDefault();
                return false;
            }
            // Disable Ctrl+ / Ctrl- / Ctrl0 (Zoom)
            if (e.ctrlKey && (e.key === '+' || e.key === '-' || e.key === '0' || e.key === '=')) {
                e.preventDefault();
            }
        });

        // Disable Ctrl + Mouse Wheel Zoom
        document.addEventListener('wheel', function(e) {
            if (e.ctrlKey) {
                e.preventDefault();
            }
        }, { passive: false });
    </script>
</body>
</html>
