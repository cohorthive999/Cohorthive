let create= document.querySelector(".create");
let join= document.querySelector(".join");
let createForm= document.querySelector(".createMsg");
let joinForm= document.querySelector(".joinMsg");
let startSec= document.querySelector(".startSec");
let body= document.querySelector("body");


// Function to disable horizontal scrolling
function disableHorizontalScroll() {
    body.classList.add('no-vertical-scroll');
}
  
  // Function to enable horizontal scrolling
function enableHorizontalScroll() {
    body.classList.remove('no-vertical-scroll');
}


const showCreateForm= ()=> {
    window.scroll({ top: 80, left: 0, behavior: 'smooth' });
    console.log("Inside Create form!");
    createForm.classList.remove("hide");
    startSec.classList.add("blur");
};

const showJoinForm= ()=> {
    window.scroll({ top: 80, left: 0, behavior: 'smooth' });
    console.log("Inside Join form!");
    joinForm.classList.remove("hide");
    startSec.classList.add("blur");
};
create.addEventListener("click", ()=>{
    console.log("Create a room button was clicked")
    showCreateForm();
    disableHorizontalScroll();
});

join.addEventListener("click", ()=>{
    console.log("Join a room button was clicked")
    showJoinForm();
    disableHorizontalScroll();
});



document.querySelectorAll('.close-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.target.closest('.form').classList.add('hide');
        startSec.classList.remove("blur");
        enableHorizontalScroll();
    });
 });

