/* --------- buy_p function ---------  */

document.querySelector('.buy_p').addEventListener('click',function (event) {
    let currentShow = event.target.getAttribute('data-show'),
        currentClose = event.target.getAttribute('data-close');

   if (event.target.classList.contains('buy_p-gray-container-show-showtext') || event.target.classList.contains('btn-blue')){
       openCase(currentShow);
   }

    if (event.target.classList.contains('buy_p-gray-container-close-showtext')){
        closeCase(currentClose);
    }
});

function openCase(currentShow) {
    if (window.innerWidth < 767){
        document.querySelectorAll('.buy_p_showed-container')[currentShow].style.maxHeight = '2050px';
    } else {
        document.querySelectorAll('.buy_p_showed-container')[currentShow].style.maxHeight = '1450px';
    }

    document.querySelectorAll('.buy_p-gray-container-show')[currentShow].querySelectorAll('.buy_p-gray-container-show-wrapper')[1].style.display = 'none';
    document.querySelectorAll('.buy_p-gray-container-show')[currentShow].querySelectorAll('.buy_p-gray-container-show-wrapper')[0].style.display = 'flex';
}

function closeCase(currentClose) {
    document.querySelectorAll('.buy_p_showed-container')[currentClose].style.maxHeight = '0';

    document.querySelectorAll('.buy_p-gray-container-show')[currentClose].querySelectorAll('.buy_p-gray-container-show-wrapper')[0].style.display = 'none';
    document.querySelectorAll('.buy_p-gray-container-show')[currentClose].querySelectorAll('.buy_p-gray-container-show-wrapper')[1].style.display = 'flex';
}