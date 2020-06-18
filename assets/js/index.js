"use strict";

const site = {
    init : () => {

        // Alias declaration

        const $ = id => document.querySelector(id);

        // Adds target _blank to external links

        let anchors = document.getElementsByTagName('a');

        Array.from(anchors).forEach(anchor => {
            if (anchor.hostname != window.location.hostname) {
                anchor.setAttribute('target', '_blank');
            }
        })

        // The following gets the number of columns by spliting the
        // array provided by the computed prop from the 'grid' CSS class

        let columnCount = 0,
            grid = $('.grid'),
            gridStyle = getComputedStyle(grid);

        function updateColumnCount() {
            columnCount = gridStyle.getPropertyValue('grid-template-columns').split(' ').length;
        }

        updateColumnCount();

        let smallMatch = window.matchMedia('(max-width: 860px)'),
            mediumMatch = window.matchMedia('(max-width: 1200px)');

        smallMatch.addListener(updateColumnCount);
        mediumMatch.addListener(updateColumnCount);

        // Resize elements according to an object containing sets of
        // CSS classes and booleans;if the boolean is true, it will 
        // get the element's uncle and contract/expand its CSS' span prop

        function resizeElements(elements) {
            if ($('.home')) return; // Nothing to do on the home page

            let cellSize = window.innerWidth/columnCount;

            for (let key in elements) {
                let el = $(key),
                    wrapper = el.parentNode,
                    start = parseInt(getComputedStyle(wrapper).gridRowStart),
                    span = Math.ceil(el.clientHeight/cellSize);

                wrapper.style.gridRowEnd = start + span;
                wrapper.nextElementSibling.style.gridRowStart = elements[key] ? start + span + 1 : 'auto';
            }
        }

        // The following crops the viewport on the last element ('anchor')

        let anchor = document.getElementById('anchor'),
            main = document.getElementById('main');

        function cropViewport() {
            var viewportHeight = window.innerHeight,
                mainHeight = anchor.offsetTop + anchor.clientHeight;

            main.style.height = (viewportHeight > mainHeight ? viewportHeight : mainHeight) + 'px';
        }

        // Function to return the elements that need resizing with
        // whether it should set the element's uncle starting point
        // to the end of the element's span

        function getElementsToResize() {
            if ($('.about')) {
                return {'.about-me' : true, '.contact' : window.innerWidth < 860};
            }

            return {'.project-description' : true};
        }

        // Adds an event listener to the 'resize' event and
        // a timeout so that it only fires once every 500ms

        window.addEventListener('resize', () => {
            clearTimeout(window.resizedFinished);

            window.resizedFinished = setTimeout(() => {
                resizeElements(getElementsToResize());
                cropViewport();
            }, 500);
        })

        resizeElements(getElementsToResize());
        cropViewport();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    site.init();
});
