
//dom
document.addEventListener('DOMContentLoaded', function () {
    
    const form = document.querySelector('.contactForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const messageInput = document.getElementById('message');
    const navBar = document.getElementById('navbar');

//form validation
    function validateForm() {
        const name = document.forms['myForm']['name'].value.trim();
        const email = document.forms['myForm']['email'].value.trim();
        const message = document.forms['myForm']['message'].value.trim();

        if (name === '') {
            alert('Name must be filled out');
            return false;
        }

        if (email === '') {
            alert('Email must be filled out');
            return false;
        }

        if (message === '') {
            alert('Message must be filled out');
            return false;
        }

        return true;
    }

//header color change 
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        if (!validateForm()) {
            return;
        }

        if (navBar.style.backgroundColor === 'rgb(19, 94, 75)') { 
                navBar.style.backgroundColor = '#4CB572'; 
        } 
        
        else {
            navBar.style.backgroundColor = '#135E4B'; 
        }

//message      
        alert('Thank you, ' + nameInput.value.trim() + '! Your message has been sent successfully.');
        form.reset();
    });
});