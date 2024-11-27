document.addEventListener('load', function() {
    const links = document.querySelectorAll('.secondary-nav .secondary-link');
    let activeLink = null;

    links.forEach(link => {
        link.addEventListener('mouseenter', function() {
            if (!activeLink) {
                links.forEach(activeLink => activeLink.classList.remove('active'));
                this.classList.add('active');
            }
        });
        link.addEventListener('mouseleave', function() {
            if (!activeLink) {
                this.classList.remove('active');
            }
        });
        link.addEventListener('click', function(e) {
            e.preventDefault();
            if (activeLink) {
                activeLink.classList.remove('active');
            }
            this.classList.add('active');
            activeLink = this;
        });
    });
    const nav2 = document.querySelector('.secondary-nav');
    nav2.addEventListener('mouseleave', function() {
        if (!activeLink) {
            links.forEach(link => link.classList.remove('active'));
        }
    });

   

    const userIcon = document.querySelector('.user-icon');
    const userDropdown = document.querySelector('.user-dropdown');

    userIcon.addEventListener('click', function() {
        e.preventDefault();
        userDropdown.style.display = userDropdown.style.display === 'none'? 'block' : 'none';
    });

    document.addEventListener('click', function(e) {
        if (!userIcon.contains(e.target) && userDropdown.contains(e.target)) {
            userDropdown.style.display = 'none';
        }
    });
});