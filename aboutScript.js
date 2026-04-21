//Author of Task Management System About Page: Ben Phan
//JS for the TMS About page

/*Originally tried: fade-in effects + highlighting the current nav page
    Both were kinda broken so I scrapped them
    Went with a scroll-to-top button instead, simpler and actually works
*/

var button = document.getElementById("scrollButton");

//Hide the button upon view of webpage
button.style.display = "none";

/*Show button once user scrolls down far enough
  Tested a few values (100, 200, etc.) but 500 felt best*/
window.addEventListener("scroll", function () {
    
    if (window.scrollY > 500) {
        button.style.display = "block";
    } else {
        button.style.display = "none";
    }

});

//When clicked, scroll back to the top smoothly
button.addEventListener("click", function () {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});

/*Used this as a reference when figuring it out:
https://stackoverflow.com/questions/69463146/how-can-i-make-a-button-that-scrolls-back-to-the-top-of-the-page-when-you-click
*/