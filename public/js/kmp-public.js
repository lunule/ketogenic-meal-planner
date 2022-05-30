/**
 * ================================================================================================
 * ================================================================================================
 * ================================================================================================
 * =========                                                                              =========
 * =========                                    HELPERS                                   =========
 * =========                                                                              =========
 * ================================================================================================
 * ================================================================================================
 * ================================================================================================
 */

// https://www.iambacon.co.uk/blog/prevent-transitionend-event-firing-twice
function whichTransitionEvent() {
    var el = document.createElement('fake');
    var transEndEventNames = {
        'WebkitTransition': 'webkitTransitionEnd', // Saf 6, Android Browser
        'MozTransition': 'transitionend', // only for FF < 15
        'transition': 'transitionend' // IE10, Opera, Chrome, FF 15+, Saf 7+
    };
    for (var t in transEndEventNames) {
        if (el.style[t] !== undefined) {
            return transEndEventNames[t];
        }
    }
}

const transEndEventName = whichTransitionEvent();

// Get object size
function objectSize(the_object) {
    var object_size = 0;
    for (key in the_object) {
        if (the_object.hasOwnProperty(key)) {
            object_size++;
        }
    };
    return object_size;
}

// Capitalize string's first letter
// @here https://stackoverflow.com/questions/1026069/how-do-i-make-the-first-letter-of-a-string-uppercase-in-javascript#answer-3291856
Object.defineProperty(String.prototype, 'capitalize', {
    value: function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    },
    enumerable: false
});

/**
 * ================================================================================================
 * ================================================================================================
 * ================================================================================================
 * =========                                                                              =========
 * =========                       JQUERY/DOCUMENTREADY BEGINS HERE                       =========
 * =========                                                                              =========
 * ================================================================================================
 * ================================================================================================
 * ================================================================================================
 */

jQuery(document).ready(function($) {

    'use strict';

    /**
     * ============================================================================================
     * ============================================================================================
     * ============================================================================================
     * =========                                                                          =========
     * =========                            CONSTANTS & GLOBALS                           =========
     * =========                                                                          =========
     * ============================================================================================
     * ============================================================================================
     * ============================================================================================
     */

    const isDev = ($('body.devMode').length > 0);
    const isPlatinumMember = ($('body.isPlatinumMember').length > 0);
    const $listContainer = (isDev) ?
        $('.kmp-results-wrap.testing').find('.kmp-results__list') :
        $('.kmp-results-wrap:not(.testing)').find('.kmp-results__list');
    const $masonryContainer = $listContainer.find('.kmp-masonry').masonry({
        itemSelector: '.kmp-masonry-item',
        columnWidth: $listContainer.find('.kmp-masonry__grid-sizer')[0],
        gutter: 40,
        percentPosition: true,
        initLayout: true,
        originLeft: true,
    });
    // Needs cookies? Checking 2 classes is enough to find this out - if the body has
    // both classes, the feature should work, independently of the KMP_DEV_MODE constant's value
    // (aka the presence or non-presence of the devMode body class)
    const needsCookies = ($('body.withCookies.notPlatinumMember').length > 0);
    const restrictNum = parseInt(kmp.restrictNum);
    const ckExpMins = parseInt(kmp.ckExpMins);
    // The expiration time
    const ckExp = new Date(new Date().getTime() + ckExpMins * 60 * 1000);

    /**
     * ============================================================================================
     * ============================================================================================
     * ============================================================================================
     * =========                                                                          =========
     * =========                                jQuery HELPERS                            =========
     * =========                                                                          =========
     * ============================================================================================
     * ============================================================================================
     * ============================================================================================
     */

    /**
     * Update the kmp cookie
     */
    function cookieUpdate(ckExp, functionTasksArr) {
        //console.log(functionTasksArr);

        if (functionTasksArr.includes('updateCookie')) {
            const x = parseInt(Cookies.get('kmp-ck-plannerUsed'));
            Cookies.remove('kmp-ck-plannerUsed');
            Cookies.set('kmp-ck-plannerUsed', x + 1, { expires: ckExp, sameSite: 'strict' });
        }

        if (functionTasksArr.includes('addClass')) {
            $('body').addClass('generateMaxReached');
        }

        if (functionTasksArr.includes('removeClass')) {
            $('body').removeClass('generateMaxReached');
        }
    }

    /**
     * Animations upon clicking the step back link
     */
    function stepBackAnimate() {

        // Expand the app's direct parent container
        $('.kmp-app-wrap:hidden').slideDown('slow');

        // Collapse itself (the backstep div)
        $('.kmp-backstep-wrap').slideUp('slow');

        // Collapse the results header div
        $('.kmp-results-header-wrap').slideUp('slow');
        $('.kmp-results-wrap').slideUp('slow');
    }

    /**
     * Animations upon submitting the form OR using the testing block's Generate button
     */
    function formSubmitAnimate() {

        // Smooth scroll to top
        const target = $('.kmp-button--regenerate');
        const barheight = ($(window).width() > 768) ? parseInt(32) : parseInt(46);
        const offset = target.offset().top - barheight;

        $('html, body').animate({
            scrollTop: offset
        }, 1000, function() {
            // COLLAPSE the app's direct parent container
            $('.kmp-app-wrap').slideUp('slow');
            // EXPAND the meal card results wrap - this one includes the LOADER.
            $('.kmp-results-wrap:hidden').slideDown('slow');
        });
    }

    /**
     * Init cookie-related Tippy instances
     */
    function initCookieTippies(testingBtnElem, stepBackLinkElem, regenerateBtnElem) {

        // Add body class
        cookieUpdate(ckExp, ['addClass']);

        if (true === isDev) {
            // Init Tippy over the backstep link
            tippy(testingBtnElem, {
                maxWidth: 240,
                touch: true,
                trigger: 'mouseenter',
                interactive: true,
                interactiveBorder: 30,
                appendTo: document.body,
                allowHTML: true,
                theme: 'light',
                content: '<p class="kmp-tippy-notice--restricted">Unlimited plan generations is a feature for members only.<br /><a href="' + kmp.siteUrl + '/join/" target="_blank">JOIN TODAY!</a></p>',
            });
        }

        // Init Tippy over the Regenerate button
        tippy(stepBackLinkElem, {
            maxWidth: 240,
            touch: true,
            trigger: 'mouseenter',
            interactive: true,
            interactiveBorder: 30,
            appendTo: document.body,
            allowHTML: true,
            theme: 'light',
            content: '<p class="kmp-tippy-notice--restricted">Unlimited form submissions is a feature for members only.<br /><a href="' + kmp.siteUrl + '/join/" target="_blank">JOIN TODAY!</a></p>',
        });

        // Init Tippy over the regenerate button
        tippy(regenerateBtnElem, {
            maxWidth: 240,
            touch: true,
            trigger: 'mouseenter',
            // The below outcommented code is an alternative of what we can achieve now
            // by setting `interactive` to true.
            //
            // The difference: with the below outcommented code there's no need to use an
            // interactiveBorder - the tip disappears ONLY if you click somewhere
            //
            // And THE PROBLEM with this approach: it doesn't work if the tip content
            // has links (hence the `interactive` prop), such links still couldn't be
            // clicked.
            // That's why setting `interactive` to true is The Correct Approach here
            /*onShow(instance) {
                instance.setProps({trigger: 'click'});
            },
            onHide(instance) {
                instance.setProps({trigger: 'mouseenter'});
            },*/
            interactive: true,
            // `interactiveBorder` determines the size of the invisible border around
            // the tippy that will prevent it from hiding if the cursor left it.
            interactiveBorder: 30,
            // With `interactive` set to true, appendTo should be specified, otherwise
            // the tip gets appended to the parent node.
            // @here https://github.com/atomiks/tippyjs/issues/844
            appendTo: document.body,
            allowHTML: true,
            theme: 'light',
            content: '<p class="kmp-tippy-notice--restricted">Unlimited plan generations is a feature for members only.<br /><a href="' + kmp.siteUrl + '/join/" target="_blank">JOIN TODAY!</a></p>',
        });
    }

    function capitalizeString(stringToCapitalize) {
        const words = stringToCapitalize.split(" ");
        const exceptionsArr = [
            'a',
            'above',
            'across',
            'after',
            'an',
            'around',
            'at',
            'before',
            'behind',
            'below',
            'beside',
            'between',
            'by',
            'down',
            'during',
            'for',
            'from',
            'in',
            'inside',
            'of',
            'off',
            'on',
            'onto',
            'out',
            'outside',
            'the',
            'through',
            'to',
            'with',
        ];
        for (let i = 0; i < words.length; i++) {
            if (
                !exceptionsArr.includes(words[i]) ||
                (
                    exceptionsArr.includes(words[i]) &&
                    i === 0
                )
            ) {
                words[i] = words[i][0].toUpperCase() + words[i].substr(1);
            }
        }
        return words.join(" ");
    }

    /**
     * ============================================================================================
     * ============================================================================================
     * ============================================================================================
     * =========                                                                          =========
     * =========                             MAIN PLUGIN FUNCTIONALITY                    =========
     * =========                                                                          =========
     * ============================================================================================
     * ============================================================================================
     * ============================================================================================
     */

    // Fade in the app with a small delay compared to componentDidMount
    const appDisplayInterval = setInterval(appDisplayTimer, 100);

    function appDisplayTimer() {
        if ($('body.kmpAppDidMount').length > 0) {
            setTimeout(() => {
                clearInterval(appDisplayInterval);
                $('body').addClass('kmpAppBuilt');
            }, 150);
        }
    }

    // If the needsCookies value is false, delete the cookie, otherwise set up the body's
    // data-restrict-num attribute to be used later by the app + init the cookie
    if (false === needsCookies) {

        Cookies.remove('kmp-ck-plannerUsed');

    } else {

        // Commented out - the restrictNum and ckExpMins values are transferred
        // now via PHP with wp_localize_script

        /*$('body').attr('data-restrict-num', restrictNum);
        $('body').attr('data-ck-exp-mins', ckExpMins);*/

        if (undefined === Cookies.get('kmp-ck-plannerUsed')) {
            Cookies.set('kmp-ck-plannerUsed', 0, { expires: ckExp, sameSite: 'strict' });
        }
    }

    /**
     * COOKIE RELATED UPDATES ON PAGE LOAD / DOCUMENT READY
     * ----------------------------------------------------
     */
    if (
        needsCookies &&
        (restrictNum === parseInt(Cookies.get('kmp-ck-plannerUsed')))
    ) {

        /**
         * BODY CLASSES
         * ------------
         */
        cookieUpdate(ckExp, ['addClass']);

        /**
         * TESTING BLOCK
         * -------------
         */

        // Disable the testing button
        $('.kmp-testing-button').prop('disabled', true);

        const a = $('.kmp-testing-button--pseudo')[0];
        const b = $('.kmp-link--backstep')[0];
        const c = $('.kmp-button--regenerate')[0];

        initCookieTippies(a, b, c);
    }

    //console.log( needsCookies );
    //console.log( restrictNum );
    //console.log( ckExpMins );

    let valuesObj = {};
    let shouldUpdateChoicesSummary = false;

    //console.log('shouldUpdateChoicesSummary:' + shouldUpdateChoicesSummary);

    /**
     * Backstep link click callback
     * --------------------------------------------------------------------------------------------
     * Clicking the backstep link should
     * - leave the results block open
     * - slide down the form app
     * - slide up the backstep container
     */
    $('body').on('click', '.kmp-link--backstep', function(e) {

        e.preventDefault();
        $('.kmp-results-error--ajaxerror').fadeOut('fast', function() { $(this).remove(); });

        // Animate only if:
        // - the needsCookies const value is false OR
        // - ( the needsCookies const value is true AND
        //     the cookie value is less than the restrictNum value
        //   )
        if (
            (false === needsCookies) ||
            (
                (true === needsCookies) &&
                (restrictNum > parseInt(Cookies.get('kmp-ck-plannerUsed')))
            )
        ) {
            stepBackAnimate();
        }

        // note: even if the cookie restriction feature is active, there's no need to update the cookie value here.
        // The cookie value should be update upon 2 or 3 click events only:
        // - if isDev is true: testing button click
        // - form submit button click (handled in the React app)
        // - regenerate button click

    });

    /**
     * Listen for the formSubmitted event added in the app's Formik onSubmit event, and start
     * the database query phase
     * --------------------------------------------------------------------------------------------
     */
    $('body').on('formSubmitted', function(e) {

        //console.log(e.originalEvent.detail);
        $('.kmp-results-error--ajaxerror').fadeOut('fast', function() { $(this).remove(); });

        const values = e.originalEvent.detail;
        const userEmail = (
                kmp.isLoggedIn &&
                (
                    (!values.email) ||
                    ('' == values.email)
                )
            ) ? kmp.email :
            values.email;
        const userFirstName = (
                kmp.isLoggedIn &&
                (
                    (!values.firstName) ||
                    ('' == values.firstName)
                )
            ) ? kmp.firstName :
            values.firstName;
        const userLastName = (
                kmp.isLoggedIn &&
                (
                    (!values.lastName) ||
                    ('' == values.lastName)
                )
            ) ? kmp.lastName :
            values.lastName;

        valuesObj = {
            addDessert: values.addDessert,
            calTarget: values.calTarget,
            dietType1: values.dietType1,
            dietType2: values.dietType2,
            email: userEmail,
            fasting: values.fasting,
            mealComplexity: values.mealComplexity,
            mealsNumber: values.mealsNumber,
            firstName: userFirstName,
            lastName: userLastName,
            phone: values.phone,
            sensitivities: values.sensitivities,
            yourGoals: values.yourGoals
        };

        shouldUpdateChoicesSummary = true;
        //console.log('shouldUpdateChoicesSummary:' + shouldUpdateChoicesSummary);

        formSubmitAnimate();
        getMeals(valuesObj, $listContainer);

    })

    $('body').on('click', '.kmp-button--regenerate', function(e) {

        //console.log(valuesObj);

        $('.kmp-results-error--ajaxerror').fadeOut('fast', function() { $(this).remove(); });

        $('body').addClass('regenerating');
        //console.log(e.originalEvent.detail);

        // EXPAND the meal card results wrap - this one includes the LOADER.
        $('.kmp-results-wrap:hidden').slideDown('slow');

        // COLLAPSE the restriction/padlock message
        //
        // WHY? Because a new AJAX request might change the number of cards to display, and
        // basically the user's membership status, if she subscribed after the form submission
        // AND BEFORE the regeneration.
        if (isPlatinumMember)
            $('.kmp-results-restricted-wrap:visible').fadeOut('slow');

        shouldUpdateChoicesSummary = false;

        //console.log('shouldUpdateChoicesSummary:' + shouldUpdateChoicesSummary);

        if (false === needsCookies) {

            cookieUpdate(ckExp, ['removeClass']);
            getMeals(valuesObj, $listContainer);

        } else {

            // Update the formSubmitted cookie value IF the cookie value is less than the
            // restrictNum value
            if (restrictNum > parseInt(Cookies.get('kmp-ck-plannerUsed'))) {

                cookieUpdate(ckExp, ['updateCookie', 'removeClass']);
                getMeals(valuesObj, $listContainer);

            } else {

                cookieUpdate(ckExp, ['addClass']);

                //console.log(e);
                //console.log( $(this)[0] );

                const a = $('.kmp-testing-button--pseudo')[0];
                const b = $('.kmp-link--backstep')[0];
                const c = $(this)[0];

                initCookieTippies(a, b, c);
            }
        }
    });

    /**
     * Testing
     * --------------------------------------------------------------------------------------------
     */
    if (isDev) {

        const $valSrc = $('.kmp-testing-table tbody tr:first');

        //console.log( $valSrc );

        valuesObj = {
            addDessert: $valSrc.find('td').eq(0).text(),
            calTarget: $valSrc.find('td').eq(1).text(),
            dietType1: $valSrc.find('td').eq(2).text(),
            dietType2: $valSrc.find('td').eq(3).text(),
            email: $valSrc.find('td').eq(4).text(),
            fasting: $valSrc.find('td').eq(5).text(),
            mealComplexity: $valSrc.find('td').eq(6).text(),
            mealsNumber: $valSrc.find('td').eq(7).text(),
            firstName: $valSrc.find('td').eq(8).text(),
            lastName: $valSrc.find('td').eq(9).text(),
            phone: $valSrc.find('td').eq(10).text(),
            sensitivities: $valSrc.find('td').eq(11).text(),
            sensitivitiesOther: $valSrc.find('td').eq(12).text(),
            yourGoals: $valSrc.find('td').eq(13).text()
        };

        //console.log( valuesObj );

        $('.kmp-sql-testing button').on('click', function(e) {

            //console.log(e.originalEvent.detail);
            $('.kmp-results-error--ajaxerror').fadeOut('fast', function() { $(this).remove(); });

            if (false === needsCookies) {

                cookieUpdate(ckExp, ['removeClass']);

                // If a previous cookie update made the button disabled, enable it
                if (true == $(this).prop('disabled')) {
                    $(this).prop('disabled', false);
                }

                formSubmitAnimate();
                getMeals(valuesObj, $listContainer);

            } else {

                //console.log( restrictNum );
                //console.log( parseInt( Cookies.get('kmp-ck-plannerUsed') ) );
                //console.log( restrictNum > parseInt( Cookies.get('kmp-ck-plannerUsed') ) );

                // Update the formSubmitted cookie value IF the value IS LESS than the
                // restrictNum value
                if (restrictNum > parseInt(Cookies.get('kmp-ck-plannerUsed'))) {

                    cookieUpdate(ckExp, ['updateCookie', 'removeClass']);

                    // If a previous cookie update made the button disabled, enable it
                    if (true == $(this).prop('disabled')) {
                        $(this).prop('disabled', false);
                    }

                    formSubmitAnimate();
                    getMeals(valuesObj, $listContainer);

                } else {

                    cookieUpdate(ckExp, ['addClass']);
                    //console.log( $(this) );

                    /**
                     * BUTTON UPDATES
                     */

                    // Disable the testing button
                    $(this).prop('disabled', true);

                    const $nextPseudo = $(this).next('.kmp-testing-button--pseudo');
                    const a = $nextPseudo[0];
                    const b = $('.kmp-link--backstep')[0];
                    const c = $('.kmp-button--regenerate')[0];

                    initCookieTippies(a, b, c);
                }
            }
        });
    }

    function generateCards(results, $listContainer, appValsObj, merge) {

        const sum = function(a, b) { return a + b };
        let itemNumber = 0;

        $.each(results, function(i, val) {

            const dailyMealsObj = val.meals;
            //console.log(dailyMealsObj);

            let dayIndex = i.match(/\d+$/)[0],
                day,
                calories = 0;

            //console.log(i);
            //console.log(dayIndex);

            switch (dayIndex) {
                case '1':
                    day = 'Monday';
                    break;
                case '2':
                    day = 'Tuesday';
                    break;
                case '3':
                    day = 'Wednesday';
                    break;
                case '4':
                    day = 'Thursday';
                    break;
                case '5':
                    day = 'Friday';
                    break;
                case '6':
                    day = 'Saturday';
                    break;
                case '7':
                    day = 'Sunday';
                    break;
            }

            //console.log(day);

            $listContainer
                .find('.cards-list')
                .append('<li class="' + day.toLowerCase() + ' mealCard-' + i + ' kmp-flex-item kmp-masonry-item"><header class="kmp-flex-container"><div class="mealCard__day kmp-flex-item">' + day + '</div><div class="mealCard__calories kmp-flex-item"></div></header></li>');

            $.each(dailyMealsObj, function(mealIndex, mealVal) {

                //console.log(mealIndex);

                const meal = mealVal.meal;
                const merge = mealVal.merge;
                const mealError = mealVal.error;

                let iterateMeal = 0;

                if (undefined !== meal['id']) {

                    //console.log( meal );

                    let mealUrl,
                        mealPostUrl,
                        mealCals,
                        mealName,
                        mealIng,
                        mealCalories,
                        mealProtein,
                        mealFat,
                        mealNetCarbs;

                    if (merge) {

                        const subMealsArr = meal['id'].split('|');

                        //console.log( merge );
                        //console.log( subMealsArr );

                        // Make HTML
                        $listContainer
                            .find('.mealCard-' + i)
                            .append('<div class="mealCard__meal mealCard__meal--' + dayIndex + ' has-merge mealCard--' + i + '--' + mealIndex + '"><p class="meal__type">' + mealIndex.capitalize() + '</p></div>');

                        mealCals = 0;

                        for (
                            let subMealIndex = 0; subMealIndex < subMealsArr.length; subMealIndex++
                        ) {

                            //console.log( subMealIndex );

                            mealUrl = meal['url'].split('|')[subMealIndex],
                                mealPostUrl = meal['posturl'].split('|')[subMealIndex],
                                mealName = meal['name'].split('|')[subMealIndex],
                                mealIng = meal['ingredients'].split('|')[subMealIndex],
                                mealCalories = meal['calories'].split('|')[subMealIndex],
                                mealProtein = meal['protein'].split('|')[subMealIndex],
                                mealFat = meal['fat'].split('|')[subMealIndex],
                                mealNetCarbs = meal['netcarbs'].split('|')[subMealIndex];

                            const mealImg = ('' !== mealUrl) ?
                                '<a href="' + mealPostUrl + '" target="_blank"><img src="' + mealUrl + '" width="75" height="" alt="Image - ' + mealName + '" /></a>' :
                                '';
                            const imgClass = ('' !== mealImg) ? 'has-img' : 'no-img';
                            const mealTitle = ('' !== mealUrl) ?
                                '<a class="no-tippy" href="' + mealPostUrl + '" target="_blank">' + capitalizeString(mealName) + '</a>' :
                                capitalizeString(mealName);

                            let tippyTable = '<table class="tippy__meal-stats"><tbody>',
                                chartLabelsArr = [],
                                chartSeriesArr = [];

                            if (0 < parseInt(mealProtein)) {
                                tippyTable += '<tr class="tms-protein"><td>Protein</td><td>' + mealProtein + '</td></tr>';

                                chartLabelsArr.push('Protein');
                                chartSeriesArr.push(parseInt(mealProtein));
                            }
                            if (0 < parseInt(mealFat)) {
                                tippyTable += '<tr class="tms-fat"><td>Fat</td><td>' + mealFat + '</td></tr>';

                                chartLabelsArr.push('Fat');
                                chartSeriesArr.push(parseInt(mealFat));
                            }
                            if (0 < parseInt(mealNetCarbs)) {
                                tippyTable += '<tr class="tms-netcarbs"><td>Net Carbs</td><td>' + mealNetCarbs + '</td></tr>';

                                chartLabelsArr.push('Net Carbs');
                                chartSeriesArr.push(parseInt(mealNetCarbs));
                            }
                            tippyTable += '</tbody></table>';

                            //console.log( chartLabelsArr );
                            //console.log( JSON.stringify( chartLabelsArr ) );

                            // Make HTML
                            $listContainer
                                .find('.mealCard--' + i + '--' + mealIndex)
                                .append('<div class="kmp-flex-container ' + imgClass + '"><div class="meal__data meal__data--submeal kmp-flex-item"><h4 class="meal__name"><div class="tippy-trigger tippy-trigger--mealinfo"><i aria-hidden="true" class="fas fa-info-circle"></i></div>' + mealTitle + '</h4><p class="meal__desc">' + mealIng + '</p><div class="tippy-content"><div class="tc__cals">' + mealCalories + ' Calories</div><div class="ct-chart ct-golden-section ct-negative-labels" data-chartlabels="' + encodeURIComponent(JSON.stringify(chartLabelsArr)) + '" data-chartseries="' + encodeURIComponent(JSON.stringify(chartSeriesArr)) + '"></div>' + tippyTable + '</div></div><div class="meal__image kmp-flex-item">' + mealImg + '</div></div>');

                            // Update the subMeal calorie data
                            mealCals += parseInt(meal['calories'].split('|')[subMealIndex]);

                            //console.log( mealCals );

                        }

                        // Update the daily calorie data
                        calories += parseInt(mealCals);

                        const $calsPrevSibling = $listContainer
                            .find('.mealCard--' + i + '--' + mealIndex)
                            .find('.meal__type');

                        $('<p class="meal__cal">' + mealCals + ' Calories</p>')
                            .insertAfter($calsPrevSibling);

                    } else {

                        mealUrl = meal['url'],
                            mealPostUrl = meal['posturl'],
                            mealName = meal['name'],
                            mealCals = meal['calories'],
                            mealIng = meal['ingredients'],
                            mealCalories = meal['calories'],
                            mealProtein = meal['protein'],
                            mealFat = meal['fat'],
                            mealNetCarbs = meal['netcarbs'];

                        // Get one random meal, and remove it from the results array
                        // prevent meal repetition

                        /*
                        const index = Math.floor( Math.random()*results.length );
                        meal = results[index];
                        */

                        const mealImg = ('' !== mealUrl) ?
                            '<a href="' + mealPostUrl + '" target="_blank"><img src="' + mealUrl + '" width="75" height="" alt="Image - ' + mealName + '" /></a>' :
                            '';
                        const imgClass = ('' !== mealImg) ? 'has-img' : 'no-img';
                        const mealTitle = ('' !== mealUrl) ?
                            '<a class="no-tippy" href="' + mealPostUrl + '" target="_blank">' + capitalizeString(mealName) + '</a>' :
                            capitalizeString(mealName);

                        // Update the daily calories data with the current meal calorie data
                        calories += parseInt(mealCals);

                        let tippyTable = '<table class="tippy__meal-stats"><tbody>';
                        let chartLabelsArr = [];
                        let chartSeriesArr = [];

                        if (0 < parseInt(mealProtein)) {
                            tippyTable += '<tr class="tms-protein"><td>Protein</td><td>' + mealProtein + '</td></tr>';

                            chartLabelsArr.push('Protein');
                            chartSeriesArr.push(parseInt(mealProtein));
                        }
                        if (0 < parseInt(mealFat)) {
                            tippyTable += '<tr class="tms-fat"><td>Fat</td><td>' + mealFat + '</td></tr>';

                            chartLabelsArr.push('Fat');
                            chartSeriesArr.push(parseInt(mealFat));
                        }
                        if (0 < parseInt(mealNetCarbs)) {
                            tippyTable += '<tr class="tms-netcarbs"><td>Net Carbs</td><td>' + mealNetCarbs + '</td></tr>';

                            chartLabelsArr.push('Net Carbs');
                            chartSeriesArr.push(parseInt(mealNetCarbs));
                        }
                        tippyTable += '</tbody></table>';

                        //console.log( chartLabelsArr );
                        //console.log( JSON.stringify( chartLabelsArr ) );

                        // Make HTML
                        $listContainer
                            .find('.mealCard-' + i)
                            .append('<div class="mealCard__meal mealCard__meal--' + dayIndex + ' kmp-flex-container ' + imgClass + '"><div class="meal__data kmp-flex-item"><p class="meal__type">' + mealIndex.capitalize() + '</p><p class="meal__cal">' + mealCals + ' Calories</p><h4 class="meal__name"><div class="tippy-trigger tippy-trigger--mealinfo"><i aria-hidden="true" class="fas fa-info-circle"></i></div>' + mealTitle + '</h4><p class="meal__desc">' + mealIng + '</p><div class="tippy-content"><div class="tc__cals">' + mealCalories + ' Calories</div><div class="ct-chart ct-golden-section ct-negative-labels" data-chartlabels="' + encodeURIComponent(JSON.stringify(chartLabelsArr)) + '" data-chartseries="' + encodeURIComponent(JSON.stringify(chartSeriesArr)) + '"></div>' + tippyTable + '</div></div><div class="meal__image kmp-flex-item">' + mealImg + '</div></div>');

                    }
                } else {
                    if ('' !== mealError) {
                        // Make HTML
                        $listContainer
                            .find('.mealCard-' + i)
                            .append('<div class="mealCard__meal mealCard__meal--' + dayIndex + '"><div class="meal__data"><p class="meal__type">' + mealIndex.capitalize() + '</p><p class="meal__error">' + mealError + '</p></div></div>');

                    }
                    iterateMeal++;
                }

                // Finalize the daily calories data display
                $listContainer
                    .find('.mealCard-' + i + ' .mealCard__calories')
                    .text(calories + ' Calories');
            });
            itemNumber++;
        });

        $('.ct-chart').each(function(i, val) {

            const chartLabelsJSON = $(this).data('chartlabels');
            const chartSeriesJSON = $(this).data('chartseries');
            const data = {
                labels: JSON.parse(decodeURIComponent(chartLabelsJSON)),
                series: JSON.parse(decodeURIComponent(chartSeriesJSON))
            };
            const responsiveOptions = [
                ['screen and (min-width: 640px)', {
                    //labels outside of the pie:
                    //labelDirection: 'explode',
                    //labelOffset: 100,
                    //chartPadding: 30,
                    //viewport-specific label interpolation function
                    /*
                    labelInterpolationFnc: function(value) {

                        // ...

                    }
                    */
                }],
                ['screen and (min-width: 1024px)', {
                    //labelOffset: 80,
                    //chartPadding: 20
                }]
            ];

            new Chartist.Pie(
                $(this).get(0),
                data, {
                    labelInterpolationFnc: function(value, index, labels) {
                        //return Math.round(value / data.series.reduce(sum) * 100) + '%';
                        //console.log(data);
                        //console.log(data.labels);
                        //console.log(data.series);
                        const label = value;
                        const percentage = Math.round(data.series[index] / data.series.reduce(sum) * 100) + '%';

                        //return label + '--' + percentage;
                        return percentage;
                    },
                    //labels outside of the pie:
                    //labelDirection: 'explode',
                    labelOffset: 15,
                    //chartPadding: 30,
                    labelDirection: 'implode',
                },
                responsiveOptions
            );
        });

        // If user is not Platinum member, we append the two faded card images
        if ($('body.notPlatinumMember').length > 0) {
            let fakeCardIndex = 1;
            while (fakeCardIndex < 3) {
                const fakeCardUrl = kmp.pluginUrl + '/public/img/fadedCard0' + fakeCardIndex + '.png';
                $listContainer
                    .find('.cards-list')
                    .append('<li class="mealCard--fake kmp-flex-item kmp-masonry-item"><div><img src="' + fakeCardUrl + '" width="75" height="" alt="Card 0' + fakeCardIndex + ' Preview" /></div></li>');
                //console.log( fakeCardIndex );
                fakeCardIndex++;
                itemNumber++;
            };
        }
        //console.log(itemNumber);
        displayCards($listContainer, itemNumber);
    };

    function displayCards($listContainer, expectedItemNumber) {

        //console.log(expectedItemNumber);

        // Fade in the cards
        $listContainer.fadeIn('slow', function() {

            $masonryContainer.masonry('reloadItems');
            $masonryContainer.masonry('layout');

            //console.log($masonryContainer);

            $masonryContainer.masonry('once', 'layoutComplete', function() {

                //console.log('Masonry layout complete');

                $('body.kmp-results-loading').removeClass('kmp-results-loading');
                $('body.regenerating').removeClass('regenerating');

                // Expand the backstep div
                $('.kmp-backstep-wrap:hidden').slideDown('slow');

                // Expand the results header div
                $('.kmp-results-header-wrap:hidden').slideDown('slow');

                // Expand the restriction/padlock message
                if (!isPlatinumMember) {
                    $('.kmp-results-restricted-wrap:hidden').fadeIn('slow');
                }

                let effinTransitionIndex = 1;

                $('.kmp-results__loader-wrap').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(e) {

                    if (1 === effinTransitionIndex) {

                        $('body').addClass('masonryComplete');

                        tippy('.tippy-trigger--mealinfo', {
                            maxWidth: 'none',
                            touch: true,
                            //touch: 'hold',
                            //touch: ['hold', 500],
                            //trigger: 'mouseenter focus',
                            trigger: 'mouseenter',
                            //trigger: 'focusin',
                            //trigger: 'mouseenter click',
                            //trigger: 'manual',
                            //zIndex: 9999,
                            theme: 'light',
                            interactive: true,
                            interactiveBorder: 5,
                            appendTo: document.body,
                            allowHTML: true,
                            //content: 'Easy content',
                            //content: document.createElement('div'),
                            //content: (reference) => reference.getAttribute('title'),
                            // content Element.innerHTML
                            //content: $tippyContentWrap.innerHTML,
                            // content Element
                            //content: $tippyContent,
                            //interactive: true,
                            content(reference) {
                                return reference.closest('.meal__data').querySelectorAll('.tippy-content')[0].innerHTML;
                            },
                            popperOptions: {
                                placement: 'right',
                                modifiers: [{
                                    name: 'flip',
                                    options: {
                                        fallbackPlacements: ['top', 'left'],
                                    },
                                }, ],
                            },
                        });

                        /*
                        tippy('.meal__name a', {
                            maxWidth: 'none',
                            touch: true,
                            trigger: 'mouseenter',
                            allowHTML: true,
                            content(reference) {

                                return $('<p class="kmp-tippy-simplecontent">Click to open the Food Finder</p>')[0];

                            },
                        });
                        */

                        // Init the Regenerate tippy ONLY IF
                        // - the needsCookies const value is false OR
                        // - ( the needsCookies const value is true AND
                        //     the cookie value is less than the restrictNum value
                        //   )
                        if (
                            (false === needsCookies) ||
                            (
                                (true === needsCookies) &&
                                (restrictNum > parseInt(Cookies.get('kmp-ck-plannerUsed')))
                            )
                        ) {
                            // Tippy over the Regenerate button
                            tippy('.kmp-button--regenerate', {
                                maxWidth: 'none',
                                touch: true,
                                trigger: 'mouseenter',
                                theme: 'light',
                                allowHTML: true,
                                content: '<h3 class="tippy-header">Regenerate 🎉</h3>',
                                popperOptions: {
                                    placement: 'right',
                                    modifiers: [{
                                        name: 'flip',
                                        options: {
                                            fallbackPlacements: ['top', 'left'],
                                        },
                                    }, ],
                                },
                            });
                        } else {
                            const a = $('.kmp-testing-button--pseudo')[0];
                            const b = $('.kmp-link--backstep')[0];
                            const c = $('.kmp-button--regenerate')[0];
                            initCookieTippies(a, b, c);
                        }
                    }
                    effinTransitionIndex++;
                });
            });
        });
    }

    function getMeals(appValsObj, $listContainer) {

        // Regenerate.
        const msnry = $masonryContainer.data('masonry');

        // If masonry is active and has items
        if (
            (undefined !== msnry) &&
            (msnry.items.length > 1)
        ) {

            if (isDev) {
                $('.kstr-table tbody').find('tr').remove();
            }

            // Animate the list container height to 300px - which is actually the loader
            // div height
            $listContainer.animate({
                opacity: 0,
                height: 300,
            }, 500, function() {

                $('body').removeClass('masonryComplete');

                // destroy masonry

                // ================================================================================
                // note: If the below outcommented lines are used, you won't able to reinitialize
                //      upon regenerate.
                //
                //      - Remove the list items only,
                //      - Don't touch the masonry config here.
                //
                //      Then later, in the displayCards() function, you update masonry by running
                //      the $masonryContainer.masonry('reloadItems') and the
                //      $masonryContainer.masonry('layout') methods.
                // ================================================================================

                //$masonryContainer.masonry('destroy');
                //$masonryContainer.removeData('masonry'); // This line to remove masonry's data

                $masonryContainer.find('li:not(:first-child)').remove();

                $listContainer.removeAttr('style');

                // Destroy Tippy instances
                [...document.querySelectorAll('*')].forEach(node => {
                    if (node._tippy) {
                        node._tippy.destroy();
                    }
                });
                getMealsAjax(appValsObj, $listContainer);
            });
        } else {
            getMealsAjax(appValsObj, $listContainer);
        }
    }

    function getMealsAjax(appValsObj, $listContainer) {

        $.ajax({
            type: 'post',
            url: kmp.ajaxUrl,
            dataType: 'json',
            data: {
                'action': 'kmpGetMeals',
                'ajaxNonce': kmp.ajaxNonce,
                'valuesObj': appValsObj,
            },
            beforeSend: function() {
                $('body').addClass('kmp-results-loading');
            },
            success: function(response, textStatus, jqXHRObject) {

                //console.log(response);
                //console.log(objectSize(response));
                //console.log(textStatus);
                //console.log(jqXHRObject);
                //console.log( response );

                const calTarget = appValsObj['calTarget'];
                const mealsNumber = parseInt(appValsObj['mealsNumber']);
                const shouldMerge = response.merge;
                const $tbody = $('.kstr-table tbody');

                let resultsObj = response;
                delete(resultsObj.merge);

                //console.log( shouldMerge );
                //console.log( objectSize(resultsObj) );
                //console.log( resultsObj );

                // is development/staging
                if (isDev) {

                    $.each(resultsObj, function(i, val) {

                        const dailyMealsObj = val.meals;
                        //console.log(dailyMealsObj);

                        $('<tr><td colspan="13" class="' + i + '-header">' + i + '</td></tr>')
                            .appendTo($tbody);

                        $.each(dailyMealsObj, function(mealIndex, mealVal) {

                            //console.log(mealIndex);

                            const meal = mealVal.meal;
                            const merge = mealVal.merge;

                            //console.log(mealVal);
                            //console.log(mealVal.merge);
                            //console.log(merge);

                            if (undefined !== meal['id']) {

                                //console.log(merge);

                                //console.log(meal['id']);

                                let mealId,
                                    mealCals,
                                    mealMeal,
                                    mealName,
                                    mealCalVal,
                                    vegebc,
                                    vegabc,
                                    carbc,
                                    daibc,
                                    eggbc,
                                    begbc,
                                    advbc,
                                    subMealsArr = meal['id'].split('|'),
                                    subMealsLength = subMealsArr.length,
                                    vegebcArr = [],
                                    vegabcArr = [],
                                    carbcArr = [],
                                    daibcArr = [],
                                    eggbcArr = [],
                                    begbcArr = [],
                                    advbcArr = [];

                                if (merge) {

                                    mealId = '',
                                        mealCals = 0,
                                        mealMeal = '',
                                        mealName = '',
                                        mealCalVal = '';

                                    //console.log(meal);
                                    //console.log(subMealsArr);

                                    $.each(subMealsArr, function(subMealIndex, subMealVal) {

                                        /*
                                        //console.log(subMealIndex);
                                        //console.log(subMealVal);
                                        //console.log( meal['vegetarian'].split('|')[subMealIndex] );
                                        //console.log( meal['vegan'].split('|')[subMealIndex] );
                                        //console.log( meal['carnivore'].split('|')[subMealIndex] );
                                        //console.log( meal['dairyfree'].split('|')[subMealIndex] );
                                        //console.log( meal['eggfree'].split('|')[subMealIndex] );
                                        //console.log( meal['beginnerfriendly'].split('|')[subMealIndex] );
                                        //console.log( meal['advanced'].split('|')[subMealIndex] );
                                        */

                                        mealId += '<div>' + meal['id'].split('|')[subMealIndex] + '</div>',
                                            mealCals += parseInt(meal['calorierange'].split('|')[subMealIndex]),
                                            mealMeal += '<div>' + meal['meal'].split('|')[subMealIndex] + '</div>',
                                            mealName += '<div>' + meal['name'].split('|')[subMealIndex] + '</div>',
                                            mealCalVal += '<div>' + meal['calories'].split('|')[subMealIndex] + '</div>';

                                        if (1 == meal['vegetarian'].split('|')[subMealIndex]) {
                                            vegebcArr.push(subMealVal);
                                        }
                                        if (1 == meal['vegan'].split('|')[subMealIndex]) {
                                            vegabcArr.push(subMealVal);
                                        }
                                        if (1 == meal['carnivore'].split('|')[subMealIndex]) {
                                            carbcArr.push(subMealVal);
                                        }
                                        if (1 == meal['dairyfree'].split('|')[subMealIndex]) {
                                            daibcArr.push(subMealVal);
                                        }
                                        if (1 == meal['eggfree'].split('|')[subMealIndex]) {
                                            eggbcArr.push(subMealVal);
                                        }
                                        if (1 == meal['beginnerfriendly'].split('|')[subMealIndex]) {
                                            begbcArr.push(subMealVal);
                                        }
                                        if (1 == meal['advanced'].split('|')[subMealIndex]) {
                                            advbcArr.push(subMealVal);
                                        }
                                    });

                                    vegebc = (vegebcArr.length === subMealsLength) ? 'is' : 'not',
                                        vegabc = (vegabcArr.length === subMealsLength) ? 'is' : 'not',
                                        carbc = (carbcArr.length === subMealsLength) ? 'is' : 'not',
                                        daibc = (daibcArr.length === subMealsLength) ? 'is' : 'not',
                                        eggbc = (eggbcArr.length === subMealsLength) ? 'is' : 'not',
                                        begbc = (begbcArr.length === subMealsLength) ? 'is' : 'not',
                                        advbc = (advbcArr.length === subMealsLength) ? 'is' : 'not';

                                    /*
                                    //console.log( vegebc );
                                    //console.log( vegabc );
                                    //console.log( carbc );
                                    //console.log( daibc );
                                    //console.log( eggbc );
                                    //console.log( begbc );
                                    //console.log( advbc );
                                    */

                                } else {
                                    mealId = meal['id'],
                                        mealCals = meal['calorierange'],
                                        mealMeal = meal['meal'],
                                        mealName = meal['name'],
                                        mealCalVal = meal['calories'],
                                        vegebc = (1 == meal['vegetarian']) ? 'is' : 'not',
                                        vegabc = (1 == meal['vegan']) ? 'is' : 'not',
                                        carbc = (1 == meal['carnivore']) ? 'is' : 'not',
                                        daibc = (1 == meal['dairyfree']) ? 'is' : 'not',
                                        eggbc = (1 == meal['eggfree']) ? 'is' : 'not',
                                        begbc = (1 == meal['beginnerfriendly']) ? 'is' : 'not',
                                        advbc = (1 == meal['advanced']) ? 'is' : 'not';
                                }

                                //console.log( mealCalVal );

                                $('<tr class="' + i + '" />')
                                    .appendTo($tbody)
                                    .append('<td class="shown-as">' + mealIndex + '</td>')
                                    .append('<td class="id">' + mealId + '</td>')
                                    .append('<td class="caltarget">' + mealCals + '</td>')
                                    .append('<td class="meal">' + mealMeal + '</td>')
                                    .append('<td class="name">' + mealName + '</td>')
                                    .append('<td class="calories">' + mealCalVal + '</td>')
                                    .append('<td class="vegetarian ' + vegebc + '">' + meal['vegetarian'] + '</td>')
                                    .append('<td class="vegan ' + vegabc + '">' + meal['vegan'] + '</td>')
                                    .append('<td class="carnivore ' + carbc + '">' + meal['carnivore'] + '</td>')
                                    .append('<td class="dairyfree ' + daibc + '">' + meal['dairyfree'] + '</td>')
                                    .append('<td class="eggfree ' + eggbc + '">' + meal['eggfree'] + '</td>')
                                    .append('<td class="beginnerfriendly ' + begbc + '">' + meal['beginnerfriendly'] + '</td>')
                                    .append('<td class="advanced ' + advbc + '">' + meal['advanced'] + '</td>');

                            }
                        });
                    });
                }

                /* TESTING--- */
                //console.log( response );
                /* --- END TESTING */

                //console.log('shouldUpdateChoicesSummary:' + shouldUpdateChoicesSummary);
                if (true == shouldUpdateChoicesSummary) {
                    updateChoicesSummary(appValsObj);
                }
                generateCards(resultsObj, $listContainer, appValsObj, shouldMerge);
            },
            // If ajax error
            error: function(jqXHR, textStatus, errorThrown) {

                //console.log( jqXHR );
                //console.log( textStatus );
                //console.log( errorThrown );

                $('body').removeClass('kmp-results-loading regenerating');

                // Display the appropriate error message
                $('.kmp-results-error--ajaxerror').fadeOut('fast', function() { $(this).remove(); });
                $('<div class="kmp-results-error kmp-results-error--ajaxerror"><p>Something went wrong... Please try again later.</p></div>')
                    .appendTo('.kmp-results')
                    .fadeIn();
            }
        }); // EOF $.ajax

    }

    function updateChoicesSummary(appValsObj) {

        // If there was an earlier submission, empty the wrap element
        $('.kmp-choices-summary').empty();

        const calTarget = '<span>Calorie target: </span> ' + appValsObj.calTarget + ' Calories';
        const mealsNumber = '<span>Number of meals: </span>' + appValsObj.mealsNumber + ' Meals/Day';
        const hasDessert = ('no' !== appValsObj.addDessert);
        const hasDietType = ('no' !== appValsObj.dietType1) ||
            (
                ('no' == appValsObj.dietType1) &&
                ('no' !== appValsObj.dietType2)
            );
        const hasSensitivity = (
            ('no' !== appValsObj.sensitivities) &&
            ('other' !== appValsObj.sensitivities)
        );
        const hasComplexity = ('no' !== appValsObj.mealComplexity);
        const hasGoal = ('' !== appValsObj.yourGoals);

        let itemNumber = 0,
            yourGoals,
            addDessert,
            dietType,
            sensitivity,
            complexity;

        if (hasDessert) itemNumber++;
        if (hasDietType) itemNumber++;
        if (hasSensitivity) itemNumber++;
        if (hasComplexity) itemNumber++;

        //console.log(itemNumber);
        //console.log(hasDessert);
        //console.log(hasDietType);
        //console.log(hasSensitivity);
        //console.log(hasComplexity);
        //console.log(appValsObj);
        //console.log(appValsObj.yourGoals);
        //console.log();

        $('<div class="choices--main kmp-flex-container"><div class="main__calTarget kmp-flex-item">' + calTarget + '</div><div class="main__mealsNumber kmp-flex-item">' + mealsNumber + '</div></div>')
            .appendTo('.kmp-choices-summary');

        if (hasGoal) {
            $('<div class="choices--goal"><span>Your Goal: </span>' + appValsObj.yourGoals + '</div>')
                .appendTo('.kmp-choices-summary');
        }

        // Make HTML
        if (
            hasDessert ||
            hasDietType ||
            hasSensitivity ||
            hasComplexity
        ) {

            $('<div class="choices--sub kmp-flex-container item-number--' + itemNumber + '"></div>')
                .appendTo('.kmp-choices-summary');

            let subContent = '';

            if (hasDessert) {
                addDessert = appValsObj.addDessert;
                $('<div class="kmp-flex-item sub__addDessert"><h4>Dessert or Snack</h4><div class="sub__icon ' + addDessert.toLowerCase() + '"></div><div class="sub__icon-label">' + addDessert.capitalize() + '</div></div>')
                    .appendTo('.choices--sub');
            }

            if (hasDietType) {
                dietType = ('no' !== appValsObj.dietType1) ?
                    appValsObj.dietType1 :
                    appValsObj.dietType2;
                $('<div class="kmp-flex-item sub__dietType"><h4>Dietary Preference</h4><div class="sub__icon ' + dietType.toLowerCase() + '"></div><div class="sub__icon-label">' + dietType.capitalize() + '</div></div>')
                    .appendTo('.choices--sub');
            }

            if (hasSensitivity) {
                sensitivity = appValsObj.sensitivities;
                $('<div class="kmp-flex-item sub__sensitivity"><h4>Sensitivity or Allergy</h4><div class="sub__icon ' + sensitivity.toLowerCase() + '"></div><div class="sub__icon-label">' + sensitivity.capitalize() + '</div></div>')
                    .appendTo('.choices--sub');
            }

            if (hasComplexity) {
                complexity = appValsObj.mealComplexity;
                $('<div class="kmp-flex-item sub__mealComplexity"><h4>Meal Complexity</h4><div class="sub__icon ' + complexity.toLowerCase() + '"></div><div class="sub__icon-label">' + complexity.capitalize() + '</div></div>')
                    .appendTo('.choices--sub');
            }
        }
    }

    /**
     * Test the Rest API custom endpoints
     * --------------------------------------------------------------------------------------------
     */

    /*
    $.ajax({
        url: '/wp-json/kmp/v2/uid',
        method: 'GET',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-WP-Nonce', kmp.restNonce);
        }
    })
    .done(function(response) {

        let userHasPlatinum = false;
        let memberClass = 'notPlatinumMember';
        //console.log( response );

        if (
                ( response['uid'] > 0 ) &&
                ( response['plat'] == 1 )
        ) {
            userHasPlatinum = true;
            memberClass = 'isPlatinumMember';
        }

        //$('body').addClass(memberClass);

        // Will return your UID.
        //console.log(userHasPlatinum);
    });
    */

});

/**
 * ================================================================================================
 * ================================================================================================
 * ================================================================================================
 * =========                                                                              =========
 * =========                     SECONDARILY IMPORTANT / EXPERIMENTAL                     =========
 * =========                                                                              =========
 * ================================================================================================
 * ================================================================================================
 * ================================================================================================
 */

/*
document.addEventListener("DOMContentLoaded", function(event) {
    document.body.classList.add('loaded');
});
*/
