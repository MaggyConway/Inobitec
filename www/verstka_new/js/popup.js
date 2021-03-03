/* --------- popup's function --------- */
let popupState = false;

function closePopup(el){
    el.style.opacity = '0';
    el.querySelector('.popup-main').style.transform = 'translateY(-64px)';
    setTimeout(function () {
        el.style.display = 'none';
        popupState = false;
    },500)
}

function Popup(elem) {
    let currentPopup = document.querySelector('#' + elem);
    currentPopup.style.display = 'flex';
    setTimeout(function () {
        currentPopup.style.opacity = '1';
        currentPopup.querySelector('.popup-main').style.transform = 'translateY(0)';
    },10);
    setTimeout(function () {
        popupState = true;
    },500);
    currentPopup.querySelector('.close').addEventListener('click',function () {
        closePopup(currentPopup);
    });
    document.addEventListener('click',function (event) {
        if (event.target.classList.contains('popup') && popupState && !event.target.classList.contains('input')){
            closePopup(currentPopup);
        }
    });
    document.addEventListener('keydown',function (event) {
        if (event.keyCode === 27  && popupState === true){
            closePopup(currentPopup);
        }
    });
}
/* --------- popup's function --------- */