
import consoleHello from './consoleHello';
import { DateTime } from 'luxon';

const officeTimes = () => {

  //consoleHello('officeTimes module is ready');

  const offices = document.querySelectorAll('.js-office');

  if (offices.length === 0) {
    //consoleHello('No office times found');
    return;
  }

  offices.forEach(office => {
    const officeSlug = office.dataset.office;
    const openingTime = office.dataset.openingTime;
    const closingTime = office.dataset.closingTime;
    const workingDays = office.dataset.workingDays.split(',').map(day => day.trim().toLowerCase());

    const openOffice = office.querySelector('.open_office');
    const closedOffice = office.querySelector('.closed_office');
    const spanTime = office.querySelector('.js-office-time');
    const timezone = spanTime.dataset.timezone;

    function updateTime() {
        const now = DateTime.now().setZone(timezone);
        const currentDay = now.weekdayLong.toLowerCase();
        const currentTime = now.toFormat('HH:mm');

        // Update time display
        spanTime.textContent = now.toFormat('h:mma');

        // Reset opacity
        openOffice.classList.remove(...Array.from(openOffice.classList).filter(c => c.startsWith('opacity-')));
        closedOffice.classList.remove(...Array.from(closedOffice.classList).filter(c => c.startsWith('opacity-')));

        // Check open/closed state
        //consoleHello(`${officeSlug}: Current day: ${currentDay}, Working days: ${workingDays.join(', ')}`);
        if (!workingDays.includes(currentDay)) {
            openOffice.classList.add('opacity-0');
            closedOffice.classList.add('opacity-100');
            return;
        }

        //consoleHello(`${officeSlug}: Current time: ${currentTime}, Opening time: ${openingTime}, Closing time: ${closingTime}`);
        if (currentTime >= openingTime && currentTime <= closingTime) {
            openOffice.classList.add('opacity-100');
            closedOffice.classList.add('opacity-0');
        } else {
            openOffice.classList.add('opacity-0');
            closedOffice.classList.add('opacity-100');
        }
    }

    updateTime();

    // Sync with real clock minute
    const now = new Date();
    const msUntilNextMinute = (60 - now.getSeconds()) * 1000 - now.getMilliseconds();
    //consoleHello(`Next update for ${officeSlug} in ${msUntilNextMinute} milliseconds`);
    setTimeout(() => {
        updateTime();
        setInterval(updateTime, 60000);
    }, msUntilNextMinute);
  });

}

export default officeTimes;