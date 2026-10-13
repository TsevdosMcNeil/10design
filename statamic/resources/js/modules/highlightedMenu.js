
import consoleHello from './consoleHello';
import getCookie from './getCookie';

const highlightedmenu = () => {

    var lastVisitCookie = getCookie('last_visit');

    if( lastVisitCookie === null ) {
        lastVisitCookie = '2016-04-21T18:45:54.379Z';
    }

    //Force old alstVisit for testing
    //lastVisitCookie = '2016-04-21T18:45:54.379Z';

    //console.log('lastVisitCookie', lastVisitCookie);  // '2025-04-24T18:45:54.379Z'

    const lastVisit = new Date(lastVisitCookie);

    //console.log('lastVisit', lastVisit);
    /*
    const latestUpdates = {
        Projects: "2025-04-24T19:45:54.379Z",
        News: "2025-04-24T19:45:54.379Z",
        Ideas: "2025-04-24T19:45:54.379Z"
    };
    */
    const latestUpdates = window.latestUpdates;
    //console.log('latestUpdates', latestUpdates);

    for (const [section, updatedAt] of Object.entries(latestUpdates)) {
        const updatedDate = new Date(updatedAt);
        if (updatedDate > lastVisit) {
            //console.log('This section was updated after the last visit: ', section);
            const menuItem = document.querySelector(`#mainDesktopNavigation [data-menu="${section}"]`);

            if (menuItem) {
                menuItem.classList.add('new-content');
            }
        }
    }


    //Update the cookie with the current time
    var now = new Date();
    var created = now.toISOString();
    var expireTime = '31536000'; //1 year
    document.cookie = 'last_visit='+created+';path=/;max-age='+expireTime+';SameSite=Strict';

}

export default highlightedmenu;
