const people = () => {
    function loadImages() {
        const personCards = document.querySelectorAll('.person-card');

        if (personCards.length > 0) {
            personCards.forEach((card) => {
                const image = card.querySelector('.person-image img');
                const overlay = card.querySelector('.overlay');

                if (!image) {
                    return;
                }

                if (image.complete) {
                    image.classList.add('loaded');
                    overlay.style.opacity = 0;
                } else {
                    image.addEventListener('load', function () {
                        image.classList.add('loaded');
                        overlay.style.opacity = 0;
                    });

                    image.addEventListener('error', function () {
                        overlay.style.opacity = 0;
                        console.error('Failed to load the image.');
                    });
                }
            });
        }
    }

    setTimeout(() => {
        loadImages();
    }, 2000);

    if (window.Livewire) {
        window.Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            // Equivalent of 'message.sent'
            succeed(({ snapshot, effect }) => {
                // Equivalent of 'message.received'
                queueMicrotask(() => {
                    // Equivalent of 'message.processed'
                    setTimeout(() => {
                        loadImages();
                    }, 0);
                })
            })
            fail(() => {
                // Equivalent of 'message.failed'
            })
        })
    }
}

export default people;
