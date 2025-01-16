const container= document.querySelector(".container");
let helpCreateLink= document.querySelector(".helpCreateLink");
let viewHelpMedia= document.querySelector(".viewHelpMedia");
let body= document.querySelector("body");
let startSec= document.querySelector(".startSec");

const showHelpForm= ()=> {
    window.scroll({ top: 100, left: 0, behavior: 'smooth' });
    console.log("Inside Help form!");
    viewHelpMedia.classList.remove("hide");
    startSec.classList.add("blur");
    container.classList.add("blur");
    container.style.display = "block";
};

function disableVerticalScroll() {
    body.classList.add('no-vertical-scroll');
}

function enableVerticalScroll() {
    body.classList.remove('no-vertical-scroll');
}
document.querySelectorAll('.close-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.target.closest('.form').classList.add('hide');
        container.classList.remove("blur");
        startSec.classList.remove("blur");
        enableVerticalScroll();
    });
});

helpCreateLink.addEventListener("click", ()=>{
    console.log("Get Help Linkk was clicked");
    showHelpForm();
    disableVerticalScroll();
});