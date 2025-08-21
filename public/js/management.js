document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('managementToggle');
    const sidebar = document.querySelector('.management-sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }
});

