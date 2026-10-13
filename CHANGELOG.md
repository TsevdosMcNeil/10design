
# Change Log
All notable changes to this project will be documented in this file.
 
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).



## [1.2.5] - 2026-10-13

### Updated

 - [Hero Image Resolution](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1216757978936954)




## [1.2.4] - 2026-10-13

### Added

 - Deployment script


## [1.2.3] - 2026-10-13

### Updated

 - ["Press" Category to News Page](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1216757978936952)


## [1.2.2] - 2026-10-13

### Added

 - `Docs` folder


## [1.2.1] - 2026-10-13

### Fixed
- `php artisan optimize` / `config:cache` failed with "Your configuration files
  are not serializable". The search index `filter` options in
  `config/statamic/search.php` were defined as closures, which Laravel cannot
  write to the config cache.

### Changed
- Moved the search filter logic into a dedicated class,
  `App\SearchFilters\ExcludeFromSearch`, and referenced it from both the
  `exact` and `default` search indexes instead of inline closures.
  Behaviour is unchanged: entries with `exclude_from_search` enabled are still
  excluded from results.

### Notes
- After deploying, rebuild the search indexes with
  `php please search:update --all`.
- The issue never surfaced on the remote server because config caching
  was not being used there; the site worked because config was read live
  on each request.

## [1.2.0] - 2026-10-13

### Added

- Docker




## [1.1.8] - 2026-06-23


### Fix

 - Static cache is building elements with wrong host: Fix: Reject *any unrecognized Host*

## [1.1.7] - 2026-06-18

### Added

-  [Mailchimp: Platform Integration & Subscriber Migration](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1215527797827702)



## [1.1.6] - 2026-06-08

### Added

-  [10SB Redirect](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1215158221657505)

## [1.1.5] - 2026-06-08

### Fixed

-  [Single "News" URL typo](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1211792017084829) 


## [1.1.4] - 2026-01-22

### Updated

-  [People navigation](https://app.asana.com/1/355454733208534/project/355454733208540/task/1211792017084821) - Custom tag to navigate to next and previous person in the people collection

## [1.1.3] - 2025-10-10

### Updated

-  Apply invalidation rules on delete as well as update
   - chore(statamic): run static caching rules on EntryDeleted/TermDeleted


## [1.1.2] - 2025-09-11

### Updated

- Flush chinese static cache after changes


## [1.1.1] - 2025-08-21

### Added

- [Glitch: Wrong colour](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1211099957307377) Fix the text size and icons color of the navigation menu.


## [1.1.0] - 2025-08-05

### Added

- [Project selection: Sticky Projects](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210461142028211) 


### Updated

- Flush projects static cache after sticky taxonomy projects are updated



## [1.0.20] - 2025-08-05

### Updated

- [Navigation Buttons](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1210962765499124) Adjust navigation buttons in themes and change spacing to 50px in idea, theme, and news templates.



## [1.0.19] - 2025-08-05

### Updated

- [Expertise alignment](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1210962766536354)



## [1.0.18] - 2025-08-04

### Fixed

- [Load More button](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1210948703744282) Chinese alignment


## [1.0.17] - 2025-08-04

### Updated

- [Search functionality](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210649814142922)

- [Search page: Hover arrow](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210647469923486)


-[Chinese Translation Ammends](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210694675894490)


## [1.0.16] - 2025-08-04

### Updated

-[Buttons Alignment](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1210085169815303)  - Replace navigation buttons in idea and news templates with arrow link component


## [1.0.15] - 2025-08-04

### Updated

-[Text Size](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1210948703744289) 


## [1.0.14] - 2025-08-04

### Updated

-[Projects - Index page](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1210948703744282) 
  - Update text colours


## [1.0.13] - 2025-08-01

### Updated

-[Related section - flexibility](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210649816919219) Add support for showing any related content in projects and ideas

- [Menu: Newsletter - arrow](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210647469923489)


## [1.0.12] - 2025-07-31

### Updated

- [Elements carousel: Link](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210482995907850)


## [1.0.11] - 2025-07-29

### Updated

- [Desktop Menu Vertical Position](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210410219478153)



## [1.0.10] - 2025-07-21

### Updated


- [No Results Found](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210842793885800)


- [Single Project: Captions](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210647472290339)

- [People: Arrows - hover colour](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210647472290351)

- [Single Idea:View next idea](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027343) Align arrow

 - [Ideas: Load more button scroll](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210556649980219)

 - [Single Theme: Back to themes](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027347) Top marging



## [1.0.9] - 2025-07-11

### Updated

 - [Chinese: Font amends](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879152775)
    Tracking updates:

    MENU  Original: -3% , Updated: -2%
    HOME: -4% → -4% (no changes here)
    FOOTER: -3% → -2%
    IDEAS Body text H4: -4% → -1%
    NEWS Title H2: -1% → -4%
    NEWS Body text H4: -4% → -1%
    STUDIO/GENERAL PAGE H3: -3% → -4%
    STUDIO/SINGLE H1: -4% → -7%


## [1.0.8] - 2025-07-11

### Updated

- [Single Theme: Back to themes](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027347)

- [Single Theme: Review translation](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027352)


## [1.0.7] - 2025-07-11

### Updated

- [Single Idea:Hide discover more ideas button](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879152769)

- [Single Idea:View next idea](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027343)

- [Single Idea:Review translation](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879152767)

- [Single Idea:Back to Ideas](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210541135848913)




## [1.0.6] - 2025-07-11

### Updated


- [Ideas: Load more button interaction](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210556649980219)

- [Ideas: Load more button](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210625571817935)

- [Ideas: Sidebar | Delete TAGS 1](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027332)

- [Ideas: Load more - Chinese](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027332)


- [Idea title](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027330) Add the title of each idea to the header of your page, just like we do now in projects.


- [News: Spacing](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210625570361308)

- [News: Load more button](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027335)

- [News: Load More - chinese](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027335)

- [News: CMS categories - restrict selection](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027339)


## [1.0.5] - 2025-07-11

### Updated

 - [Chinese: Font amends](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879152775)
    MENU. Bold 
    HOME: 50/64(H2)
    FOOTER: PAGES IN BOLD. 
    IDEAS: Body text: 26/36/
    NEWS: Title: 50/64/ (H2)
    NEWS: Body text: 26/36/ (H4)
    STUDIO/GENERAL PAGE: 40/55/ (H3)
    STUDIO/SINGLE: 62/80/ (H1)


## [1.0.4] - 2025-07-11

### Updated

 - [Filters colours](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879027325)

 - [Filters restriction](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210761879152773)

## [1.0.3] - 2025-06-23

### Added

- [Enquiries section layout](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210688732580562)
   - Only one line between two vertical elements
   - Mobile bottom line




## [1.0.2] - 2025-06-23

- [New typeface](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210556650975170)


- [Ashley Laptop: Project grid alignment](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210567958592044)

- [Themes: Responsive Template](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210556192754521)

- [Mobile: Themes ](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210284339347591)


- [Mobile: People | Cropped images](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210579904788273)


## [1.0.1] - 2025-06-19

- [Cookies message](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210184594536768)


- [Favicon](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210184594536768)



## [1.0.0] - 2025-06-19

- Go Live


## [0.7.11] - 2025-06-18

## Updated

- [Projects: 'size' section](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210579904788301)

- [Cookies message](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210184594536768)

- [Related cards](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210567960084405)


- [New typeface](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210556650975170)


- [Sticky projects based on taxonomy](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374124) Show stycky projects tooo low in the list


## [0.7.10] - 2025-06-17

## Updated

- [Responsive: Footer font size](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210519936374086)

- [Translation: New typeface](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210556650975170)

- [Home: Idea section](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210567960084398)


## [0.7.9] - 2025-06-16

## Updated

- [People thumbnail](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210449265822471)

- Static cache invalidation

[Sticky projects based on taxonomy](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374124) Update general sticky elements and filtered sticky elements behaviour.


[Add sticky elements to people](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210539569757738)


## [0.7.8] - 2025-06-16

## Updated

[Sticky projects based on taxonomy](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374124)



## [0.7.7] - 2025-06-16

## Updated

[Back to Ideas](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210541135848913)

[People: Filters](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210556650975178)

[Load more](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210556649980226)

[Ideas: Filtering system](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210399648517778)

[Projects: Location](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1210556650975190/story/1210561944580330)


## [0.7.6] - 2025-06-16

## Updated

- [Filters loading](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210519936374099)
  - Projects List View


- [Slow charge](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210367204012140) 
    - Remove Livewire filtering  (only for Grid)
    - Order items 
    - Taxonomy pages
    - Reset functionality


## [0.7.5] - 2025-06-16

## Updated

- [Themes Template](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210556192754521)


## [0.7.4] - 2025-06-16

## Updated

- [Filters loading](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210519936374099)

- [Caching Filtering](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210233582282701)

- [Slow charge](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210367204012140)




## [0.7.3] - 2025-06-12

## Added


- [Clone English Content to Chinese](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210531770818362) Custom script to clone multisite English to Chinese
  - Run `php artisan clone-collection-en-to-zh`


- [Clone English Content to Chinese](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210531770818362) Custom script to link Chinese trasnlation to English original article
  - Run `php artisan set-origins`

- [Clone English Content to Chinese](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210531770818362) Custom scritp to fix duplicated IDs when cloning multisite English to Chinese
   - Run `php artisan identify-duplicates`



## [0.7.2] - 2025-06-12

## Added

- [Image Ratio](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210521792447169) Add custom modifier to get the numeric value of the image fraction ratio


## [0.7.1] - 2025-06-11

- [Ideas: Cards](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210521792447172)

- [Themes: Next theme](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210367204012146) Last Theme Navigation CTA

- [Image Ratio](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210521792447169)



## [0.7.0] - 2025-06-11

- Tablet: Project Key Points

- [Tablet: Large titles](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210482995907844)

- [Tablet: Projects view](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210508886796731)

- [Tablet: Themes | Responsive text size](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210498530422185)



## [0.6.4] - 2025-06-10

- [Themes: Next theme](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210367204012146)

- [Logo Alignment](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210498522366044)

- [Footer glitch](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210508883782151)

- [Carousel: Header colour](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210476156429069)

- [Carousel: Links](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210482995907850)

## [0.6.3] - 2025-06-10

- [People: Alphabetical order](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210508883782148)


- [Video crops](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210471702220305)

- [Home: Project Grid - Chrome Issue](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210512285385000)


## [0.6.2] - 2025-06-10

 - [People: Filters order + name](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210399648517845)

 - [People: Roles](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210399648517847)

 - [People: Scrolling element](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210461142028204)

 - [People: Roles - Glitch](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210508883782146)

 - [People: Hover people cards](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210508883782156)


 - [People: Page colour](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210508883782158)





## [0.6.1] - 2025-06-09

- Migration: replace URLs Bash File

- Migration: Leading Issue

 - [Projects: Sticky Projects](app.asana.com/1/355454733208534/project/1208878041988932/task/1210461142028211)



## [0.6.0] - 2025-06-02

-[Mobile: Videos](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210459957727136)

- [Videos - Autoplay](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210461142028208)



## [0.5.30] - 2025-06-02

-[Turn off Spanish](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210399648517851)

- [Preloading](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210259761550583)

- [Thumbnail sizes](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210364876476929)

## [0.5.29] - 2025-06-02

- [Studio: Submenu spacing](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374073)

- [Studio: Text size](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210436861222019)

- [Studio: Lazy Loading](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1209868904374071/story/1210437221270424) 
  - Update video background
  - update Youtube embed videos
  - Update Youtube background videos

- [Contact: Auto Refresh](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210367204012153)

- [Preloading](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210259761550583)

- [Thumbnail sizes](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210364876476929)

- [Footer newsletter](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210367472304667)

- [Videos on the website](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210184594536774)
  - Videos as section background

## [0.5.28] - 2025-05-30

### Updates

- [Search function](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210367472304662)

- [Videos on the website](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210184594536774)
  - Videos added to Ideas, News and Projects header
  - Videos added to carousel

- [Bilibili Video Platform](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210387018076068)

## [0.5.27] - 2025-05-29

### Updates

- [Themes: Next theme](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210367204012146)

- [Home: Automatic Populated Collections](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210364881167437)

- [Ideas: Details](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210399648517780) 


## [0.5.26] - 2025-05-29

### Updates

- [Projects card - location](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210399648517849)

- [List view - Add expertise](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210340057409185)



## [0.5.25] - 2025-05-29

### Updates

- [People: Roles](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210399648517847)

- [People: Filters order + name](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210399648517845)



## [0.5.24] - 2025-05-28

### Updates

- [List view - Add expertise](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210340057409185)

- [Filters: Filtering layout](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210364876476944)

- [Projects: Year column alignment](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210340057409187)

- [People: Member Layout](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209921287308917) Remove Footer

## [0.5.23] - 2025-05-28

### Updates

- [News: Image selection](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210364876476927)

- [News: Thumbnail text box](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210364876476925)

- [News: Alignment list view](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210364876476934)

-[News: Order](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210364876476932)

- [News: Footer colour](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210367204012156)



## [0.5.22] - 2025-05-23

### Fixes

- Video component

- Youtube video

- Footer logo


### Updates

- Chinese translations

- Static Cache configuration




## [0.5.21] - 2025-05-22

### Fixes

- Link wit arrow hotfix


### Updates

- [Project categories gradient](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210340057409191)


## [0.5.20] - 2025-05-21

### Fixes

- [Ideas: Index](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1210327241387993) Fix font styles in Mobile

### Updates

- [List view - Add expertise](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1210340057409185/story/1210340195327770)

- People on Search Results

- Fixed wrong links due to site:url

- [Videos on Website: Files](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210184594536774)


## [0.5.19] - 2025-05-21

### Updates

- [Small Arrow Updates](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374073)

- [Filters: Sort Alphabetically](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1210259913418957/story/1210331081361670)

- [Show Title in two lines](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210327241387979)

- [Preloader](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210259761550583) Reduce preload execution time


## [0.5.18] - 2025-05-20

### Fixes

- Form languages

- Global search set to languages

- Language Switcher for Collection Index pages

- [Filtering an Search returns to English version](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210255810620734)



## [0.5.17] - 2025-05-20

### Fixes

 - Image styles of people image for mobile


 ### Updates

- [Footer: Logo](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210288648039186)

## [0.5.16] - 2025-05-19

### Updates

- [News: Filtered Results](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210259761550589)

- [Editor Area Text Colours](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210259913418968)

- [Member Layout](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209921287308917)

## [0.5.15] - 2025-05-18

### Updates

- [Line break space](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210259913418965)

- [Single Project: Related project](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210253361981758)
- [Single Project: Excerpt Alignment](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210259913418934)
- [Single Project: Key Points spacing](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210259913418940)

- Mobile: Single Project
- Mobile: Scroll trigered Element


## [0.5.14] - 2025-05-18

### Updates

- Remove locations taxonomy from people
- Mobile: Project Filters
- Mobile: People Filters
- Mobile: Ideas Filters
- Mobile: News Filters
- Mobile: Studio pages navigation

## [0.5.13] - 2025-05-17

### Add

- Fix small responsive styles
- Responsive styles
 


## [0.5.12] - 2025-05-16

### Udpates

- [Language links](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210253355109254)


## [0.5.11] - 2025-05-15

### Udpates

 - Add responsive styles

## [0.5.10] - 2025-05-14

### Udpates

- [Slugs in Chinese](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210235638213930)

- [Projects - Details missing](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1210253355109250/story/1210253368532962)

- [Projects - Expertise missing](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1210253355109252/story/1210253368532962)

 - [Related project](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1210253355109256/story/1210253368532962)

## [0.5.9] - 2025-05-13

### Udpates

 - [Related project](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1210253355109256/story/1210253368532962)



## [0.5.8] - 2025-05-13

### Fixes

- Glide images to fit

- Exclude filtered projects pages from static cache

- Remove Invalidation Rules

### Updates 

 - Change google map marker path


## [0.5.7] - 2025-05-12

### Fixes

- Fix fatal error with 'color' var

 - [Loading issues](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209869272377498)


### Updates 

 - [Themes | Cards](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210109527412599)

 - Enable Glide


## [0.5.6] - 2025-05-07

### Fixes

- Overwrite "peak" CSS rule on fluid-container;



## [0.5.6] - 2025-05-07

### Updates 

[Project | Thumbnail proportions in Grid](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209979769037221)

- [Status project](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1209868904374126/story/1210188322799209)


- [Back To Studio Arrow](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1209868904374071/story/1210184594536864)



## [0.5.5] - 2025-05-07

### Updates 
- [Contaact Form](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210184594536771)

- [Home - Header Logo](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210096052132339)

- [Google map - New marker](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209808642695541) 



## [0.5.4] - 2025-05-07

### Updates 

- [Projects: List view - 12 column design](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210109526936755) 


- [Projects: List view - animation bullets](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210172091302052) 

- [Projects: List](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210109526936745) Arrow default direction



## [0.5.3] - 2025-05-07

### Fixes

- [Title Cut off](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210169929356467)

### Updates 


- [Single Project: Expertise](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210169929356475)

- [People: Google Maps](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209808642695541) Remove Google details

- [Expertise](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210108375688303) Aligmnent elements to top

- [Themes: Header Image](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210109526936764)



## [0.5.2] - 2025-05-06

### Updates 

- Forms: AJAX Form Submit


## [0.5.1] - 2025-05-06

### Updates 

- Livewire Translations

- Translation Files

- Article Card Class




## [0.5.0] - 2025-05-05

### Added

- [Translations](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210108635992948)


## [0.4.44] - 2025-05-02

### Updates 

- [Pop up boxes pictures](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210109527412592)



## [0.4.43] - 2025-05-02

### Updates 

- [Reorder filtering functionality](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210109526936745)


- [Grid page - Load more action](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210109526936747)



## [0.4.42] - 2025-05-02

### Updates 

- Feedback updates



## [0.4.41] - 2025-04-30

### Updates 

- [General | Buttons alignment](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210070238547415)

- [People | Font Sizes](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209921287308917)




## [0.4.40] - 2025-04-29

### Updates 

- [Themes: Add Ideas/News](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210096052132352) New option to add News or Ideas


## [0.4.39] - 2025-04-29

### Updates 

- [Home | Header Logo](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210096052132339) 


- [Menu | Font size](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210043714855213) Vertical space reduced 5px

- [Categories bullet - animation](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210043714855220) Animation loop time changed from 2 seconds to 1.7 seconds

- [People | Tables](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209808642695541) Add marker image


## [0.4.38] - 2025-04-27

### Updates 


-[Projects | Single | Project details](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210058934802462)

-[ General | Buttons alignment](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210070238547415)


## [0.4.37] - 2025-04-25

### Updates 

- [Home | Themes](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209858371146210)

- [Projects: Categories](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209858371146205)
- [Categories] (https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374133)


- [Categories bullet - animation](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210043714855220)

- Font sizes

- Google map


## [0.4.36] - 2025-04-24

### Updates 

- [Change name to Expertise](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210014041667400)

- [Menu | Updated content](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210043714855208)

- [Menu | Font size](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210043714855213)

- [Expertise | Links](https://app.asana.com/1/355454733208534/inbox/355454733208538/item/1210070238547420/story/1210070688624083) 
    - Add filtering to URL



## [0.4.35] - 2025-04-24

### Updates 


- [Scroll Triggered Related Content](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374144)

- [People | Animation](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210000691884015)

- [Header Image shadow](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209979769958214)



## [0.4.34] - 2025-04-23

### Updates 

 - [ Home | Carosusel | Fonts ](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209909183148575)

 - [ Projects | Single | Fonts ](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209909183148592)

- [ Ideas | Filters | Fonts ](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209909183148601)


## [0.4.33] - 2025-04-22

### Updates 

[ Projects | Single | Large view images option ](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209873061755071)
    - Add lightbox gallery component



## [0.4.32] - 2025-04-22

### Updates 

- [Ideas| Index | Search bar](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210000691700776)

- [News| Index | Search bar ](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374206)

- [Projects | Index | Fonts](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209909183148590)

- [Projects | Index | List view](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210043714855216)

- [Projects | Index | List view](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210043714855216)

- [Menu | Font size](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210043714855213)

- [Bouncing arrow](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374215)
 - Arrow waits 1000ms to appear
 - Arrow waits 100ms to go down
 - Arrow waits 100ms to go up
 - Arrow waits 100ms to go down
 - rrow waits 1000ms to go up again

- [News | Single ](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1210043714855228)
    - Add "sand" colour to link arrow



- [Menu | Search tool](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209893570726072)


## [0.4.31] - 2025-04-22

### Fixes

- [Home | Themes](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209858371146210)



## [0.4.30] - 2025-04-17

### Updates 

- "No results found" message


## [0.4.29] - 2025-04-16

### Updates 

- Opacity-0 onload
- News List (40/46)
- Bouncing Arrow
- H1 test size (60/66)
- Fix arrow lisk default colours



## [0.4.28] - 2025-04-15

### Updates 

- Remaining Filters (except People)


## [0.4.27] - 2025-04-14

### Updates 

- Livewire Pagination



## [0.4.26] - 2025-04-11

### Fix

- [Scroll - cut off](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209921287308916) Fix font size in people modal

### Updates 

- Livewire Functionality

- [News: List rollover](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374209)




## [0.4.25] - 2025-04-11

### Updates 

- Search tool

### Fix

- General styles


## [0.4.24] - 2025-04-10

### Updates 

  - Office employees table


## [0.4.23] - 2025-04-09

### Updates 

  - Bouncing Arrows

## [0.4.22] - 2025-04-09

### Updates 

  - Font Sizes


## [0.4.21] - 2025-04-09

### Feedback Updates 

  - [Footer: linked logo](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209909183148550) Added link to image fieldset

  - [Ideas: Back to Ideas](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868933907922) 

  - [Studio: Submenu spacing](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374073) Arrow Position

    - [Home: News](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209858371146215) Hover effect

  - [Footer: Newsletter](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209858371146223) Add 14px

  - [Newsletter arrow](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209858371146241) Add 14px



## [0.4.21] - 2025-04-09

### Feedback Updates 

  - []() 
  - []() 

  - []() 
  - []() 

  - [Ideas: Back to Ideas](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868933907922) 

  - [Studio: Submenu spacing](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209868904374073) Arrow Position

    - [Home: News](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209858371146215) Hover effect

  - [Footer: Newsletter](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209858371146223) Add 14px

  - [Newsletter arrow](https://app.asana.com/1/355454733208534/project/1208878041988932/task/1209858371146241) Add 14px


## [0.4.20] - 2025-04-09

### Updates

 - Remove "content" folder from repository


## [0.4.19] - 2025-04-08

### Updates

 - Add specialisms colors, and arrow buttons in single templates of themes and news

## [0.4.18] - 2025-04-07

### Updates

  - [Home: News](https://app.asana.com/0/1208878041988932/1209858371146215) Update hover effect

  - [Home: Ideas](https://app.asana.com/0/1208878041988932/1209858371146218) 


## [0.4.17] - 2025-04-04

### Updates

  - [Ideas:Side bar](https://app.asana.com/0/1208878041988932/1209868904374163) scroll-triggered_related_content
  

## [0.4.16] - 2025-04-04

### Updates

  - [Home: News](https://app.asana.com/0/1208878041988932/1209858371146215)
  
  - [Home: Themes](https://app.asana.com/0/1208878041988932/1209858371146210)



## [0.4.15] - 2025-04-04

### Updates

 - [Newsletter arrow](https://app.asana.com/0/1208878041988932/1209858371146241)

 - [Footer: Newsletter](https://app.asana.com/0/1208878041988932/1209858371146223)

  - [Home: News](https://app.asana.com/0/1208878041988932/1209858371146215)


## [0.4.14] - 2025-04-04

### Updates

 - General customer feedback
 - Loading Animation
 - Line spacing
 - Read more
 - Colour rollover
 - Cross Icon
 - Sticky Header
 - Header Icons
 - Text Size
 - Titles
 - Images
 - Back to Ideas
 - Related Section


## [0.4.13] - 2025-04-03

### Updates

 - General customer feedback
 

## [0.4.12] - 2025-03-31

### Fixed

 - Default open accordion


## [0.4.11] - 2025-03-28

### Fixed

 - Preloader wrong z-index

## [0.4.10] - 2025-03-27

### Added

 - Collection: `Ideas`

### Updates

- Contact page & add missing links

### Fixed

- Rollback develop changes that were overwritten previous functionallity



## [0.4.9] - 2025-03-26

 ### Added

 - Collection: `News`
 - Taxonomy: `Categories`


## [0.4.8] - 2025-03-26

 ### Added

 -  New single theme
 -  Homepage Themes Section

## [0.4.7] - 2025-03-26

 ### Added

 -  Themes

  ### Updates

 -  Themes template
 -  Scroll Trigered Related Component

## [0.4.6] - 2025-03-26

 ### Added

 -  Preloader

  ### Updated

 -  Preloader animations


## [0.4.5] - 2025-03-26

 ### Added

 -  Projects Table


## [0.4.4] - 2025-03-26

 ### Added

 -  Projects Grid

 ### Fixed

 - Livewire & Alpine conflicts
 - Alpine filter error


## [0.4.3] - 2025-03-26

### Added

 -  Hierarchical Taxonomies in Statamic
 -  New placeholder locations
 -  New placeholder projects


## [0.4.2] - 2025-03-25

### Added

 -  Projects LiveWire component
 -  Projects LiveWire Taxonomies


## [0.4.1] - 2025-03-25

### Updated

 -  Update header & footer links

 ### Fixed

 -  Fix links component


## [0.4.0] - 2025-03-24

### Added

 -  Livewire


## [0.3.18] - 2025-03-24

### Fixed

 -  Fatal error in Project Card


## [0.3.17] - 2025-03-24

### Fixed

 -  Article fieldset

### Added

 - Office collection & card
 - Contact page


## [0.3.16] - 2025-03-22

### Added

 - Projects section & project single page
 - Add meta sections to projects template



## [0.3.15] - 2025-03-21

### Fix

 -  Homepage carousel no displaying images


## [0.3.14] - 2025-03-20

### Fix

 -  Replace url of glide-generated image by original image



## [0.3.13] - 2025-03-19

### Updated

 -  Update nav links

### Fixed

 -  Member error when there is no image

### Added

 - Member page


## [0.3.12] - 2025-03-19

 ### Fixed

 -  Javascript for accordion component

### Added

 - Our story page
 - Specialisms page


## [0.3.11] - 2025-03-18

### Updated

 - Footer

### Added

 - Studio Page


## [0.3.10] - 2025-03-17

### Fixed

 - Files wrong location


## [0.3.9] - 2025-03-17

### Added

 - Add site menu


## [0.3.8] - 2025-03-16

### Added

 - Add and modify global styles & new fieldsets

## [0.3.7] - 2025-03-16

### Added

- Footer
- Separator


## [0.3.6] - 2025-03-13

### Updated

 - [Hero Carousel](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1209642401632970)


### Added

 - [Fuentes y colores](https://app.asana.com/1/355454733208534/project/1209642401632940/task/1209642401632955/)


## [0.3.5] - 2025-03-11

### Added

- Logo
- `Bard Text Color` add-on


## [0.3.4] - 2025-03-11

### Updated

- Image block

### Added

- `Glide enabled|disabled` global option


## [0.3.3] - 2025-03-11

### Added

- `Projects` collection


## [0.3.2] - 2025-03-11

### Added

- Video block
- Video antlers
- Video Modifiers

## [0.3.1] - 2025-03-10

### Added

- Alpine Intersect
- Carousel component


## [0.3.0] - 2025-03-10

### Updated

- Statamic (and the rest of elements) updated to the latest version 
     - Statamic 5.50.0
     - Peak 20.6.2 
     - Tailwind 4.0.0


0.1.36
   Add SASS


0.1.35
   Remove webpack.mix.js (deprecated)

0.1.34
   By default, presets will NOT be automatically generated on upload. 
   To enable this feature, set the `generate_presets_on_upload` option to `true` in your `config/statamic/assets.php` file.

0.1.33
   Added global Glide option: Enable/Disable glide in images

0.1.32
   Added scrolldown button options (display_scrolldown_button, scroll_down_button_link)

0.1.31
   Added custom variants 'mobile-only', 'tablet-only'and 'mbtb-only' (mobile and tablet only)

0.1.30
    Updated "About Us" page with background colours

0.1.29
    Default size set to maximum for column element in all viewports

0.1.28
    Added scrolldown button antler

0.1.27
    Updated "About Us" page with collapsible section

0.1.26
    Added collapsed sections

0.1.25
    Added viewports to sections and columns

0.1.24
    Fixed Livewire filters.

0.1.23
    Fixed wrong taxonomy slug in Livewire class.

0.1.22
    Updated README.md. The versioning is now in the GIT tags.

0.1.21
    Updated gitignore. The content folder is on the repository again

0.1.20
    Updated "About Us" page with more video examples

0.1.19
    Updated Video Antlers

0.1.18
    Added slugify to section slug to avoid JS errors

0.1.17
    Updated README.md

0.1.16
    Added resources/views/typography/_figcaption.antlers.html

0.1.15
    Updated Video

0.1.14
    Added "tiny-slider": "^2.9.4"

0.1.13
    Added "photoswipe": "^5.4.0" and "photoswipe-dynamic-caption-plugin": "^1.2.7"

0.1.12
    Added "@csstools/postcss-oklab-function": "^3.0.1"

0.1.11
    Updated gitignore

0.1.10
    CSS style changed to allow embed videos from Youtube or Vimeo to adapt to any aspect ratio.

0.1.9
    Adding Video block

0.1.8
    First copy of the Statamic Peak theme
