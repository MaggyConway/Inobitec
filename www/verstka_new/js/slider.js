let currentSlide = 0,
    nextSlide = 0,
    slidesArr = document.querySelectorAll('.main-slider_content'),
    state = true,
    counter;

document.querySelector('.main-slider_navigation-arrows-left').addEventListener('click',goLeft);
document.querySelector('.main-slider_navigation-arrows-left').addEventListener('click',function () {
    clearTimeout(counter);
    lineCounter();
});

document.querySelector('.main-slider_navigation-arrows-right').addEventListener('click',goRight);
document.querySelector('.main-slider_navigation-arrows-right').addEventListener('click',function () {
    clearTimeout(counter);
    lineCounter();
});
/* */
function goRight() {
    if (state === true) {
        if (currentSlide < slidesArr.length - 1){
            nextSlide++;
        } else {
            nextSlide = 0;
        }

        state = false;

        nextCount();

        document.querySelector('.main-slider_bg-logo').classList.add('main-slider_bg-logo-active');

        slidesArr[currentSlide].querySelector('.main-slider_content-text').style.transform = 'translateX(-75%)';
        slidesArr[nextSlide].querySelector('.main-slider_content-text').style.transform = 'translateX(75%)';
        slidesArr[currentSlide].querySelector('.main-slider_content-text').style.opacity = '0';
        slidesArr[nextSlide].querySelector('.main-slider_content-text').style.opacity = '0';

        slidesArr[currentSlide].querySelector('.main-slider_content-image').style.transform = 'translateX(-100%)';
        slidesArr[nextSlide].querySelector('.main-slider_content-image').style.transform = 'translateX(100%)';
        slidesArr[currentSlide].querySelector('.main-slider_content-image').style.opacity = '0';
        slidesArr[nextSlide].querySelector('.main-slider_content-image').style.opacity = '0';
        setTimeout(function () {
            slidesArr[currentSlide].querySelector('.main-slider_content-button').style.transform = 'translateX(-75%)';
            slidesArr[nextSlide].querySelector('.main-slider_content-button').style.transform = 'translateX(75%)';
            slidesArr[currentSlide].querySelector('.main-slider_content-button').style.opacity = '0';
            slidesArr[nextSlide].querySelector('.main-slider_content-button').style.opacity = '0';
        },125);
        setTimeout(function () {
            slidesArr[currentSlide].querySelector('.main-slider_content-description').style.transform = 'translateX(-75%)';
            slidesArr[nextSlide].querySelector('.main-slider_content-description').style.transform = 'translateX(75%)';
            slidesArr[currentSlide].querySelector('.main-slider_content-description').style.opacity = '0';
            slidesArr[nextSlide].querySelector('.main-slider_content-description').style.opacity = '0';
        },250);
        setTimeout(function () {
            slidesArr[currentSlide].classList.remove('main-slider_content-active');
            slidesArr[nextSlide].classList.add('main-slider_content-active');
        },510);

        setTimeout(function () {
            slidesArr[nextSlide].querySelector('.main-slider_content-text').style.transform = 'translateX(0)';
            slidesArr[nextSlide].querySelector('.main-slider_content-text').style.opacity = '1';

            slidesArr[nextSlide].querySelector('.main-slider_content-image').style.transform = 'translateX(0)';
            slidesArr[nextSlide].querySelector('.main-slider_content-image').style.opacity = '1';
        },530);

        setTimeout(function () {
            slidesArr[nextSlide].querySelector('.main-slider_content-button').style.transform = 'translateX(0)';
            slidesArr[nextSlide].querySelector('.main-slider_content-button').style.opacity = '1';
        },625);

        setTimeout(function () {
            slidesArr[nextSlide].querySelector('.main-slider_content-description').style.transform = 'translateX(0)';
            slidesArr[nextSlide].querySelector('.main-slider_content-description').style.opacity = '1';
            document.querySelector('.main-slider_bg-logo').classList.remove('main-slider_bg-logo-active');
            state = true;
            if (currentSlide < slidesArr.length - 1){
                currentSlide++;
            } else {
                currentSlide = 0;
            }
        },780);
    }
}

function goLeft() {
    if (state === true){
        if (currentSlide === 0){
            nextSlide = slidesArr.length - 1;
        } else {
            nextSlide--;
        }

        state = false;

        prevCount();

        document.querySelector('.main-slider_bg-logo').classList.add('main-slider_bg-logo-active');

        slidesArr[currentSlide].querySelector('.main-slider_content-text').style.transform = 'translateX(75%)';
        slidesArr[nextSlide].querySelector('.main-slider_content-text').style.transform = 'translateX(-75%)';
        slidesArr[currentSlide].querySelector('.main-slider_content-text').style.opacity = '0';
        slidesArr[nextSlide].querySelector('.main-slider_content-text').style.opacity = '0';

        slidesArr[currentSlide].querySelector('.main-slider_content-image').style.transform = 'translateX(100%)';
        slidesArr[nextSlide].querySelector('.main-slider_content-image').style.transform = 'translateX(-100%)';
        slidesArr[currentSlide].querySelector('.main-slider_content-image').style.opacity = '0';
        slidesArr[nextSlide].querySelector('.main-slider_content-image').style.opacity = '0';

        setTimeout(function () {
            slidesArr[currentSlide].querySelector('.main-slider_content-button').style.transform = 'translateX(75%)';
            slidesArr[nextSlide].querySelector('.main-slider_content-button').style.transform = 'translateX(-75%)';
            slidesArr[currentSlide].querySelector('.main-slider_content-button').style.opacity = '0';
            slidesArr[nextSlide].querySelector('.main-slider_content-button').style.opacity = '0';
        },125);
        setTimeout(function () {
            slidesArr[currentSlide].querySelector('.main-slider_content-description').style.transform = 'translateX(75%)';
            slidesArr[nextSlide].querySelector('.main-slider_content-description').style.transform = 'translateX(-75%)';
            slidesArr[currentSlide].querySelector('.main-slider_content-description').style.opacity = '0';
            slidesArr[nextSlide].querySelector('.main-slider_content-description').style.opacity = '0';
        },250);
        setTimeout(function () {
            slidesArr[currentSlide].classList.remove('main-slider_content-active');
            slidesArr[nextSlide].classList.add('main-slider_content-active');
        },510);

        setTimeout(function () {
            slidesArr[nextSlide].querySelector('.main-slider_content-text').style.transform = 'translateX(0)';
            slidesArr[nextSlide].querySelector('.main-slider_content-text').style.opacity = '1';

            slidesArr[nextSlide].querySelector('.main-slider_content-image').style.transform = 'translateX(0)';
            slidesArr[nextSlide].querySelector('.main-slider_content-image').style.opacity = '1';
        },530);

        setTimeout(function () {
            slidesArr[nextSlide].querySelector('.main-slider_content-button').style.transform = 'translateX(0)';
            slidesArr[nextSlide].querySelector('.main-slider_content-button').style.opacity = '1';
        },625);

        setTimeout(function () {
            slidesArr[nextSlide].querySelector('.main-slider_content-description').style.transform = 'translateX(0)';
            slidesArr[nextSlide].querySelector('.main-slider_content-description').style.opacity = '1';
            document.querySelector('.main-slider_bg-logo').classList.remove('main-slider_bg-logo-active');
            state = true;
            if (currentSlide > 0){
                currentSlide--;
            } else {
                currentSlide = slidesArr.length - 1;
            }
        },780);

    }
}

function nextCount() {
    let secondCount = document.createElement('p');

    secondCount.innerHTML = nextSlide + 1;

    document.querySelector('.main-slider_navigation-counter-current').appendChild(secondCount);

    document.querySelectorAll('.main-slider_navigation-counter-current p')[1].style.transform = 'translateY(-100%)';

    setTimeout(function () {
        document.querySelectorAll('.main-slider_navigation-counter-current p')[0].style.transform = 'translateY(100%)';
        document.querySelectorAll('.main-slider_navigation-counter-current p')[1].style.transform = 'translateY(0)';

        setTimeout(function () {
            document.querySelector('.main-slider_navigation-counter-current').removeChild(document.querySelectorAll('.main-slider_navigation-counter-current p')[0]);
        },150);
    },50);
}

function prevCount() {
    let secondCount = document.createElement('p');

    secondCount.innerHTML = nextSlide + 1;

    document.querySelector('.main-slider_navigation-counter-current').appendChild(secondCount);

    document.querySelectorAll('.main-slider_navigation-counter-current p')[1].style.transform = 'translateY(100%)';

    setTimeout(function () {
        document.querySelectorAll('.main-slider_navigation-counter-current p')[0].style.transform = 'translateY(-100%)';
        document.querySelectorAll('.main-slider_navigation-counter-current p')[1].style.transform = 'translateY(0)';

        setTimeout(function () {
            document.querySelector('.main-slider_navigation-counter-current').removeChild(document.querySelectorAll('.main-slider_navigation-counter-current p')[0]);
        },150);
    },50);
}



function lineCounter(){
    document.querySelector('.main-slider_navigation-counter-line-active').removeAttribute('style');
    document.querySelector('.main-slider_navigation-counter-line-active').style.width = '0';
    document.querySelector('.main-slider_navigation-counter-line-active').style.transition = '0';
    setTimeout(function () {
        document.querySelector('.main-slider_navigation-counter-line-active').style.transition = 'width linear 9s';
        document.querySelector('.main-slider_navigation-counter-line-active').style.width = '100%';
    },50);

    counter = setTimeout(function () {
        goRight();
        lineCounter();
    },9100);
}

lineCounter();

document.querySelector('.main-slider_navigation-arrows-left').addEventListener('mouseover',function () {
    document.querySelector('.svg-arrow-left').style.stroke = '#ffffff';
});
document.querySelector('.main-slider_navigation-arrows-left').addEventListener('mouseout',function () {
    document.querySelector('.svg-arrow-left').style.stroke = '#426DA9';
});

document.querySelector('.main-slider_navigation-arrows-right').addEventListener('mouseover',function () {
    document.querySelector('.svg-arrow-right').style.stroke = '#ffffff';
});
document.querySelector('.main-slider_navigation-arrows-right').addEventListener('mouseout',function () {
    document.querySelector('.svg-arrow-right').style.stroke = '#426DA9';
});

if (window.innerWidth <= 1020){
    $(function() {
        $(".main-slider").swipe( {
            swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
                if (direction === 'right'){
                    goLeft();
                    clearTimeout(counter);
                    lineCounter();
                } else if (direction === 'left') {
                    goRight();
                    clearTimeout(counter);
                    lineCounter();
                }
            },
        });
    });
}

window.onload = function() {
    if (window.innerWidth < 1020){
        document.querySelector('.main-slider').style.height = '100%'; //window.innerHeight - 32 + 'px';
    } else {
        document.querySelector('.main-slider').style.height = '100%'; //window.innerHeight + 'px';
    }
};

$(window).resize(function(event) {
    if (window.innerWidth < 1020){
        document.querySelector('.main-slider').style.height = '100%'; //window.innerHeight - 32 + 'px';
    } else {
        document.querySelector('.main-slider').style.height = '100%'; //window.innerHeight + 'px';
    }
});

