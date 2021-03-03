let description = document.querySelectorAll('.keys-main__description');

[].forEach.call(description, function(el) {
    let checkTitle =  el.querySelectorAll('.product-name');

    el.querySelector('.keys-main__description-show').addEventListener('click',function () {
        closeTab(el,checkTitle)
    });

    [].forEach.call(checkTitle, function(elem,index) {
        elem.addEventListener('click',function () {
            elem.querySelector('img').classList.toggle('active-image');
            openCheck(el,index);
        })
    });
});

function closeTab(el,checkTitle) {
    let productDescription = el.querySelectorAll('.keys-main__description-product');

    [].forEach.call(productDescription, function(elem) {
        elem.classList.toggle('active-product');
    });

    [].forEach.call(checkTitle, function(elem,index) {
        el.querySelectorAll('.keys-main__description-showCase')[index].classList.remove('active-check');

        elem.querySelector('img').classList.remove('active-image');

        el.querySelectorAll('.keys-main__description-showCase')[index].removeAttribute('style');
    });
}

function openCheck(el,index) {
    let currentHeight = (el.querySelectorAll('.keys-main__description-showCase')[index].querySelectorAll('.keys-main__description-showCase-row').length * 155) + 40,
        mobileHeight = el.querySelectorAll('.keys-main__description-showCase')[index].querySelectorAll('.keys-main__description-showCase-row').length * 235;

    el.querySelectorAll('.keys-main__description-showCase')[index].classList.toggle('active-check');

    if (el.querySelectorAll('.keys-main__description-showCase')[index].classList.contains('active-check')){
        if (window.innerWidth > 1040){
            el.querySelectorAll('.keys-main__description-showCase')[index].style.maxHeight = currentHeight + 'px';
        } else {
            el.querySelectorAll('.keys-main__description-showCase')[index].style.maxHeight = mobileHeight + 'px';
        }
    } else {
        el.querySelectorAll('.keys-main__description-showCase')[index].removeAttribute('style');
    }
}
