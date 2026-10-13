// https://photoswipe.com/
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';
// dynamic caption plugin
import PhotoSwipeDynamicCaption from 'photoswipe-dynamic-caption-plugin';

const photoswipe = () => {
    /**
     * PhotoSwipeLightbox
     * https://photoswipe.com/
     */

    const arrowSVGString = '<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="15" cy="15" r="15" fill="#E8E1D6"/><path d="M15 10L10 15M15 20L10 15M10 15L19.5 15" stroke="black"/></svg>';

    const arrowNextSVGString = '<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="15" cy="15" r="15" fill="#E8E1D6"/><path d="M15 10L20 15M15 20L20 15M20 15L10.5 15" stroke="black"/></svg>';

    const closeSVGString = '<svg width="29" height="30" viewBox="0 0 32 31" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-[20px] md:w-[29px]"> <path d="M1.60938 30L29.6094 2.00003" stroke="black" stroke-width="3"></path><path d="M1.60938 1.96637L29.6094 29.9664" stroke="black" stroke-width="3"></path></svg>';

    const lightbox = new PhotoSwipeLightbox({
        arrowPrevSVG: arrowSVGString,
        arrowNextSVG: arrowNextSVGString,
        closeSVG: closeSVGString,
        gallery: '#people',
        children: 'a.photoswipeelement',
        zoom: false,
        counter: false,
        initialZoomLevel: 'fit',
        secondaryZoomLevel: 'fit',
        mainClass: 'pswp--gallery',
        pswpModule: () => import('photoswipe'),
        showHideOpacity: true,
        showHideAnimationType: 'fade',
    });

    // Parse custom data attributes
    lightbox.addFilter('itemData', (itemData, index) => {
        // data-pswp-webp-src
        const webpSrc = itemData.element.dataset.pswpWebpSrc;
        if (webpSrc) {
            itemData.webpSrc = webpSrc;
        }

        // data-pswp-bgcolour
        const bgColour = itemData.element.dataset.pswpBgcolour;
        if (bgColour) {
            itemData.bgColour = bgColour;
        }

        return itemData;
    });

    lightbox.on('contentLoad', (e) => {
        const { content, isLazy } = e;

        //Change the placeholder background colour
        if (content.data.bgColour) {
            const bgColourName = content.data.bgColour;
            //console.log(bgColourName);
            //console.log(content);

            let background_color = '#65B2E8';
            switch (bgColourName) {
                case 'lightblue':
                    background_color = '#65B2E8';
                    break;
                case 'blue':
                    background_color = '#0071CE';
                    break;
                case 'darkblue':
                    background_color = '#000F9F';
                    break;
            }

            var placeholder_css = '.pswp__img--placeholder { background: '+background_color+'; }';

            var head  = document.head || document.getElementsByTagName('head')[0];
            var stylerule = document.createElement('style');
            head.appendChild(stylerule);

            if (stylerule.styleSheet){
                // This is required for IE8 and below.
                stylerule.styleSheet.cssText = css;
            } else {
                stylerule.appendChild(document.createTextNode(placeholder_css));
            }


        }

        //Update the slider to show webp images (  use <picture> instead of <img>)
        if (content.data.webpSrc) {
            // prevent to stop the default behavior
            e.preventDefault();

            content.pictureElement = document.createElement('picture');

            const sourceWebp = document.createElement('source');
            sourceWebp.srcset = content.data.webpSrc;
            sourceWebp.type = 'image/webp';

            const sourceJpg = document.createElement('source');
            sourceJpg.srcset = content.data.src;
            sourceJpg.type = 'image/jpeg';

            content.element = document.createElement('img');
            content.element.src = content.data.src;
            content.element.setAttribute('alt', '');


            let className = 'pswp__img';
            if (content.data.bgColour) {
                className += ' bg-' + content.data.bgColour ;
            }

            content.element.className = className;

            content.pictureElement.appendChild(sourceWebp);
            content.pictureElement.appendChild(sourceJpg);
            content.pictureElement.appendChild(content.element);

            content.state = 'loading';

            if (content.element.complete) {
                content.onLoaded();
            } else {
                content.element.onload = () => {
                    content.onLoaded();
                };

                content.element.onerror = () => {
                    content.onError();
                };
            }
        }
    });


    // by default PhotoSwipe appends <img>,
    // but we want to append <picture>
    lightbox.on('contentAppend', (e) => {
        const { content } = e;
        if (content.pictureElement && !content.pictureElement.parentNode) {
            e.preventDefault();
            content.slide.container.appendChild(content.pictureElement);
        }
    });

    // for next/prev navigation with <picture>
    // by default PhotoSwipe removes <img>,
    // but we want to remove <picture>
    lightbox.on('contentRemove', (e) => {
        const { content } = e;
        if (content.pictureElement && content.pictureElement.parentNode) {
            e.preventDefault();
            content.pictureElement.remove();
        }
    });

    // Enable vertical scrolling
    lightbox.on('afterInit', () => {
        const pswp = lightbox.pswp;

        // Get the scrollable element (usually the caption)
        const scrollableElement = document.querySelector('.pswp--open [aria-hidden="false"] .pswp-caption-content--wrapper > div:last-child');

        if (scrollableElement) {
            // Prevent wheel events from reaching PhotoSwipe core
            scrollableElement.addEventListener('wheel', (e) => {
                e.stopPropagation();

                // Manually handle scrolling
                scrollableElement.scrollTop += e.deltaY;
            }, { passive: false });
        }

        // Alternative: Make entire content area scrollable
        // pswp.scrollWrap.addEventListener('wheel', (e) => {
        //     if (pswp.currSlide.currZoomLevel <= pswp.currSlide.zoomLevels.initial) {
        //         e.stopPropagation();
        //         window.scrollBy(0, e.deltaY);
        //     }
        // }, { passive: false });
    });


    /**
     * Dynamic caption plugin
     * https://github.com/dimsemenov/photoswipe-dynamic-caption-plugin
     */
    var cssId = 'photoswipeDynamicCaptionCSS';  // Load Css only once.
    if (!document.getElementById(cssId))
    {
        var head  = document.getElementsByTagName('head')[0];
        var link  = document.createElement('link');
        link.id   = cssId;
        link.rel  = 'stylesheet';
        link.type = 'text/css';
        link.href = 'https://unpkg.com/photoswipe-dynamic-caption-plugin/photoswipe-dynamic-caption-plugin.css';
        link.media = 'all';
        head.appendChild(link);
    }


    const captionPlugin = new PhotoSwipeDynamicCaption(lightbox, {
        // Plugins options, for example:
        type: 'aside',
    });

    lightbox.init();

    // Livewire cases
    if (window.Livewire) {
        window.Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            // Equivalent of 'message.sent'
            succeed(({ snapshot, effect }) => {
                // Equivalent of 'message.received'
                queueMicrotask(() => {
                    // Equivalent of 'message.processed'
                    if (lightbox.pswp) {
                        lightbox.init();
                    }
                })
            })
            fail(() => {
                // Equivalent of 'message.failed'
            })
        })
    }


    // Dynamic simple lightboxes
    const dynamicLightBoxes = document.querySelectorAll('.photoswipe-lightbox');
    dynamicLightBoxes.forEach((lightbox) => {
        const dynamicLightbox = new PhotoSwipeLightbox({
            arrowPrevSVG: '<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="15" cy="15" r="15" fill="#E8E1D6"/><path d="M15 10L10 15M15 20L10 15M10 15L19.5 15" stroke="black"/></svg>',
            arrowNextSVG: '<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="15" cy="15" r="15" transform="rotate(-180 15 15)" fill="#E8E1D6"/><path d="M15 20L20 15M15 10L20 15M20 15L10.5 15" stroke="black"/></svg>',
            closeSVG: '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 30.0332L30 2.0332" stroke="black" stroke-width="3"/><path d="M2 2L30 30" stroke="black" stroke-width="3"/></svg>',
            gallery: lightbox,
            children: 'a',
            pswpModule: () => import('photoswipe'),
            mainClass: 'pswp--dynamic',
            zoom: false,
            counter: false,
            initialZoomLevel: 'fit',
            secondaryZoomLevel: 'fit',
            showAnimationDuration: 0,
            hideAnimationDuration: 0,
            bgOpacity: 0.9
        });

        dynamicLightbox.init();
    });
}

export default photoswipe;
