// Force render semua elemen dan cek CSS
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== FindTrack Debug ===');
    
    // Cek apakah CSS ter-load
    const stylesheets = Array.from(document.styleSheets);
    let cssLoaded = false;
    stylesheets.forEach(function(sheet) {
        try {
            if (sheet.href && sheet.href.includes('style.css')) {
                cssLoaded = true;
                console.log('✓ CSS loaded:', sheet.href);
            }
        } catch(e) {
            // Cross-origin error, skip
        }
    });
    
    if (!cssLoaded) {
        console.error('✗ CSS tidak ter-load! Mencoba load ulang...');
        // Coba load ulang dengan beberapa path
        const paths = [
            '/apkfindtrack/assets/css/style.css',
            '../assets/css/style.css',
            '../../assets/css/style.css',
            'assets/css/style.css'
        ];
        
        paths.forEach(function(path) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = path;
            link.onload = function() {
                console.log('✓ CSS loaded from:', path);
            };
            link.onerror = function() {
                console.log('✗ Failed to load from:', path);
            };
            document.head.appendChild(link);
        });
    }
    
    // Cek elemen
    const sidebar = document.getElementById('sidebar');
    const header = document.querySelector('.top-header');
    const mainContent = document.getElementById('mainContent');
    const content = document.querySelector('.content');
    const cards = document.querySelectorAll('.card');
    
    console.log('Sidebar:', sidebar ? 'Found' : 'Not Found');
    console.log('Header:', header ? 'Found' : 'Not Found');
    console.log('Main Content:', mainContent ? 'Found' : 'Not Found');
    console.log('Content:', content ? 'Found' : 'Not Found');
    console.log('Cards:', cards.length);
    
    // Force display jika perlu
    if (mainContent) {
        mainContent.style.display = 'block';
        mainContent.style.visibility = 'visible';
    }
    if (content) {
        content.style.display = 'block';
        content.style.visibility = 'visible';
    }
    
    // Force body background
    document.body.style.backgroundColor = '#0f0f0f';
    document.body.style.color = '#ffffff';
});
