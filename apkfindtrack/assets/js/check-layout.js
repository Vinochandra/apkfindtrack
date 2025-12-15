// Debug script untuk cek layout
document.addEventListener('DOMContentLoaded', function() {
    console.log('Layout Check:');
    console.log('Sidebar:', document.getElementById('sidebar') ? 'Found' : 'Not Found');
    console.log('Header:', document.querySelector('.top-header') ? 'Found' : 'Not Found');
    console.log('Main Content:', document.getElementById('mainContent') ? 'Found' : 'Not Found');
    console.log('Content:', document.querySelector('.content') ? 'Found' : 'Not Found');
    
    // Check if CSS is loaded
    const stylesheets = Array.from(document.styleSheets);
    console.log('Stylesheets loaded:', stylesheets.length);
    
    // Force layout refresh
    if (document.querySelector('.content')) {
        document.querySelector('.content').style.display = 'block';
    }
});

