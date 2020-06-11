var $ = function(c) {
    return document.querySelector(c);
};

document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.about')) {
        var grid = $('.grid'),
            aboutMe = $('.about-me'),
            aboutMeWrapper = $('.about-me-wrapper'),
            contact = $('.contact'),
            contactWrapper = $('.contact-wrapper'),
            aboutSpan = 0,
            contactSpan = 0;
        
        window.addEventListener("resize", function() {
            resizeSection();
        })

        resizeSection();

        function resizeSection() {
            var columnCount = getComputedStyle(grid).getPropertyValue("grid-template-columns").split(" ").length,
                cellSize = window.innerWidth/columnCount,
                newAboutSpan = Math.ceil(aboutMeWrapper.clientHeight/cellSize),
                newContactSpan = Math.ceil(contactWrapper.clientHeight/cellSize);
        
            if (aboutSpan != newAboutSpan) {
                aboutSpan = newAboutSpan;
        
                console.log('changed');
                aboutMe.style.gridRowEnd = "span " + aboutSpan;
                contact.style.gridRowStart = aboutSpan + 3;
            }
            
            if (contactSpan != newContactSpan) {
                contactSpan = newContactSpan;

                contact.style.gridRowEnd = "span " + contactSpan;
             }
            
        }
    }
});
