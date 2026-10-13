// https://photoswipe.com/
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

const singleProject = () => {
  const singleProjectContainer = document.querySelector('main.single-project .project-content');

  // Exit if not on a single project page
  if (!singleProjectContainer) {
    return;
  }

  // Check if exists .photoswipe-carousel-wrapper > .photoswipe-lightbox, if so, prevent default behavior
  const photoswipeCarouselWrapper = singleProjectContainer.querySelector('.photoswipe-carousel-wrapper > .photoswipe-lightbox');
  if (photoswipeCarouselWrapper) {
    return;
  }

  // Get only visible images within figures
  const visibleFigureImages = Array.from(singleProjectContainer.querySelectorAll('figure img')).filter(img => {
    // Add class photoswipe-hover
    img.classList.add('photoswipe-hover');

    // Filter out images with zero dimensions (hidden or not rendered)
    return img.offsetWidth > 0 && img.offsetHeight > 0;
  });

  // Prepare gallery items with required data
  const galleryItems = visibleFigureImages.map(img => ({
    element: img, // Reference to the DOM element
    src: img.currentSrc || img.src, // Use currentSrc for responsive images
    width: img.naturalWidth,
    height: img.naturalHeight
  }));

  // Initialize PhotoSwipe Lightbox with custom configuration
  const lightbox = new PhotoSwipeLightbox({
    // Custom SVG icons
    arrowPrevSVG: '<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="15" cy="15" r="15" fill="#E8E1D6"/><path d="M15 10L10 15M15 20L10 15M10 15L19.5 15" stroke="black"/></svg>',
    arrowNextSVG: '<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="15" cy="15" r="15" transform="rotate(-180 15 15)" fill="#E8E1D6"/><path d="M15 20L20 15M15 10L20 15M20 15L10.5 15" stroke="black"/></svg>',
    closeSVG: '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 30.0332L30 2.0332" stroke="black" stroke-width="3"/><path d="M2 2L30 30" stroke="black" stroke-width="3"/></svg>',

    // Lightbox configuration
    pswpModule: () => import('photoswipe'),
    mainClass: 'pswp--dynamic',
    zoom: false,
    counter: false,
    initialZoomLevel: 'fit',
    secondaryZoomLevel: 'fit',
    showAnimationDuration: 0,
    hideAnimationDuration: 0,
    bgOpacity: 0.9,

    // Error handling
    errorMsg: 'The image cannot be loaded'
  });

  // Set the data source for the lightbox
  lightbox.options.dataSource = galleryItems.map(item => ({
    src: item.src,
    width: item.width,
    height: item.height
  }));

  // Add click handlers to all visible images
  visibleFigureImages.forEach((img, index) => {
    // Handle click event
    const handleClick = (e) => {
      e.preventDefault();
      e.stopPropagation();

      // Open lightbox at the correct index
      lightbox.loadAndOpen(index);
    };

    img.addEventListener('click', handleClick);

    // For accessibility
    img.setAttribute('role', 'button');
    img.setAttribute('aria-label', 'Enlarge image');
  });

  // Initialize the lightbox
  lightbox.init();
};

export default singleProject;
