

// https://github.com/ganlanyuan/tiny-slider'
import {tns} from 'tiny-slider/src/tiny-slider.js';
import consoleHello from './consoleHello';

const tinyslider = () => {

    function listOfCarousels(wrapperCarousels) {
        //consoleHello(" load tiny-carousel-list");

        Array.from(wrapperCarousels).forEach((wrapperCarousel) => {

            const carousel = wrapperCarousel.querySelector('.tiny-carousel');

            //consoleHello(wrapperCarousel);
            //consoleHello(carousel);

            var carAutoplay = false;
            if(carousel.dataset.autoplay) {
                carAutoplay = carousel.dataset.autoplay;
            }

            var carNavigation = false;
            if(carousel.dataset.navigation) {
                carNavigation = carousel.dataset.navigation;
            }

            var carMode = 'carousel';
            if(carousel.dataset.transition) {
                carMode = carousel.dataset.transition;
            }

            var slider = tns({
                container: carousel,
                items: 1,
                slideBy: 'page',
                mode: carMode,
                autoplay: carAutoplay,
                autoplayButton: false,
                autoplayHoverPause: true,
                autoplayButtonOutput: false,
                controls: false,
                nav: false,
                navPosition: 'bottom',
            });

            var nextButton = null;
            var prevButton = null;
            if (wrapperCarousel.querySelector('.next-button')) {
                nextButton = wrapperCarousel.querySelector('.next-button');
                prevButton = wrapperCarousel.querySelector('.prev-button');
            }


            //consoleHello(nextButton);
            //consoleHello(prevButton);

            if (nextButton) {
                nextButton.onclick = function () {
                    slider.goTo('next');
                };
            }
            if (prevButton) {
                prevButton.onclick = function () {
                    slider.goTo('prev');
                };
            }

            function updateHeaderClass(info) {
              // Get the slide element that is currently active (info.index is 0-based)
              var currentSlide = info.slideItems[info.index];

              // Get the data-header attribute
              var newHeaderColour = currentSlide.getAttribute('data-headercolour');
              //Check if the newClass exists
              if (!newHeaderColour) {
                return
              }

              var newHeaderClass = 'header-color-' + newHeaderColour;

              var header = document.getElementById('mainHeader');

              //Remove all classes starting by 'header-color-'
              var classesToRemove = Array.from(header.classList).filter(className => className.startsWith('header-color-'));
              classesToRemove.forEach(className => {
                header.classList.remove(className);
              });

              //Add the new class to the header
              header.classList.add(newHeaderClass);
            }

            // Update header class on slide change
            slider.events.on('indexChanged', updateHeaderClass);

            // Set header class immediately after initialization
            updateHeaderClass(slider.getInfo());


        });
        return null;
    }


    var wrapperCarousels = document.getElementsByClassName("wrapper-carousel");
    if( wrapperCarousels.length > 0 ) {

        var cssId = 'tinyCss';  // Load Css only once.
        if (!document.getElementById(cssId))
        {
            var head  = document.getElementsByTagName('head')[0];
            var link  = document.createElement('link');
            link.id   = cssId;
            link.rel  = 'stylesheet';
            link.type = 'text/css';
            link.href = 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css';
            link.media = 'all';
            head.appendChild(link);
        }

        listOfCarousels(wrapperCarousels);

        // Each time livewire updates (i.e, every time a filter is clicked),
        // we need to launch the function again, if not the carousels won't work
        if(document.getElementsByClassName("livewire-filter").length > 0) {
            var carouselFilters = document.getElementsByClassName("livewire-filter");
            Array.from(carouselFilters).forEach((carouselFilter) => {
                //TODO find a way to launch the function oonce livewire has finished loading the new content, instead waiting 5 seconds
                carouselFilter.onclick = function () {
                    setTimeout(() => {
                        listOfCarousels(wrapperCarousels);
                    }, 5000)
                };

            });
        }

    }
}

export default tinyslider;
