let addAnnouncement= document.querySelector(".addAnnouncement");
let seeAnnouncementBtn= document.querySelectorAll(".seeAnnouncements");
let createAnnouncement= document.querySelector(".createAnnouncement");
let viewAnnouncement= document.querySelector(".viewAnnouncement");
const container= document.querySelector(".container");
let addToTimelineBtn= document.querySelector(".addToTimelineBtn");
let addToTimelineFrom= document.querySelector(".addToTimelineFrom");
let body= document.querySelector("body");


// Function to disable vertical scrolling
function disableVerticalScroll() {
    body.classList.add('no-vertical-scroll');
}
  
  // Function to enable vertical scrolling
function enableVerticalScroll() {
    body.classList.remove('no-vertical-scroll');
}

const showAnnouncements= ()=> {
    window.scroll({ top: 10, left: 0, behavior: 'smooth' });
    console.log("Inside Create form!");
    createAnnouncement.classList.remove("hide");
    container.classList.add("blur");
    container.style.display = "block";
};

const showAddToTimelineForm= ()=> {
    window.scroll({ top: 100, left: 0, behavior: 'smooth' });
    console.log("Inside Timeline form!");
    addToTimelineFrom.classList.remove("hide");
    container.classList.add("blur");
    container.style.display = "block";
};

function copyID() {
    var textToCopy = document.getElementById("roomId").innerText;
    var cleanedText = textToCopy.replace('Code: ', '');
    var tempTextarea = document.createElement('textarea');
    tempTextarea.value = cleanedText;
    document.body.appendChild(tempTextarea);
    tempTextarea.select();
    tempTextarea.setSelectionRange(0, 99999);
    document.execCommand('copy');
    document.body.removeChild(tempTextarea);
};
function copyPass() {
    var textToCopy = document.getElementById("roomPass").innerText;
    var cleanedText = textToCopy.replace('Password: ', '');
    var tempTextarea = document.createElement('textarea');
    tempTextarea.value = cleanedText;
    document.body.appendChild(tempTextarea);
    tempTextarea.select();
    tempTextarea.setSelectionRange(0, 99999);
    document.execCommand('copy');
    document.body.removeChild(tempTextarea);
};
const showExistingAnnouncements= ()=>{
    window.scroll({ top: 10, left: 0, behavior: 'smooth' });
    console.log("Inside Join form!");
    viewAnnouncement.classList.remove("hide");
    container.classList.add("blur");
    container.style.display = "block";
};


document.querySelectorAll('.close-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.target.closest('.form').classList.add('hide');
        container.classList.remove("blur");
        enableVerticalScroll();
    });
});

addToTimelineBtn.addEventListener("click", ()=>{
    console.log("Add To Timeline button was clicked");
    showAddToTimelineForm();
    disableVerticalScroll();
});
addAnnouncement.addEventListener("click", ()=>{
    console.log("Add announcements button was clicked");
    showAnnouncements();
    disableVerticalScroll();

});
seeAnnouncementBtn.forEach(button => {
    button.addEventListener("click", () => {
        console.log("Create a room button was clicked");
        showExistingAnnouncements();
        disableVerticalScroll();
    });
});