import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import collapse from '@alpinejs/collapse'
import focus from '@alpinejs/focus'
import morph from '@alpinejs/morph'
import intersect from '@alpinejs/intersect'
import precognition from 'laravel-precognition-alpine';

//Custom Modules
import consoleHello from './modules/consoleHello';
import preloader from './modules/preloader';
import tinyslider from './modules/tinyslider';
import photoswipe from './modules/photoswipe.js';
import people from './modules/people.js';
import highlightedmenu from './modules/highlightedMenu.js';
import singleProject from './modules/single-project.js';
import officeTimes from './modules/officeTimes.js';

// Call Alpine.
//window.Alpine = Alpine
Alpine.plugin([collapse, focus, morph, precognition, intersect])
//Alpine.start()

// Call Livewire only in Livewire pages
if(document.querySelector('main').classList.contains('livewirepage') || document.querySelector('section.livewirepage')){
  //If you are using full measure static caching, you need to make sure to only start Livewire once the CSRF token has been replaced.
  if (window.livewireScriptConfig?.csrf === 'STATAMIC_CSRF_TOKEN') {
    document.addEventListener('statamic:nocache.replaced', () => Livewire.start());
  } else {
      Livewire.start();
  }
  //Livewire.start()
}

photoswipe();

// Call custom modules.
const CDG = {

    onreadyFunctions: function() {
        //consoleHello('CDG is ready');
        preloader();
        tinyslider();
        people();
        highlightedmenu();
        singleProject();
        officeTimes();

        window.addEventListener("resize", function(){
            //consoleHello('window has resized');
            if(window.innerWidth < 768){
                //consoleHello('narrow');
            }
            else{
                //consoleHello('wide');
            }
         });
    },

    onloadFunctions: function() {
        //consoleHello('CDG is loaded');
    }
};

CDG.onreadyFunctions();

window.onload = function(event) {
    CDG.onloadFunctions();
};
