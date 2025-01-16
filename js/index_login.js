document.addEventListener('DOMContentLoaded', function() {
    const signInBtn = document.querySelector('.sign-in-btn');
    const signUpBtn = document.querySelector('.sign-up-btn');
    const closeBtns = document.querySelectorAll('.close-btn');
    const signInPopup = document.getElementById('signInPopup');
    const signUpPopup = document.getElementById('signUpPopup');
    const blurBackground = document.getElementById('blurBackground');

    function openPopup(popup) {
        popup.classList.remove('hide');
        blurBackground.classList.remove('hide');
        blurBackground.style.display = 'block';
    }

    function closePopup() {
        signInPopup.classList.add('hide');
        signUpPopup.classList.add('hide');
        blurBackground.classList.add('hide');
        blurBackground.style.display = 'none';
    }

    signInBtn.addEventListener('click', () => openPopup(signInPopup));
    signUpBtn.addEventListener('click', () => openPopup(signUpPopup));
    closeBtns.forEach(btn => btn.addEventListener('click', closePopup));
    blurBackground.addEventListener('click', closePopup);
});
