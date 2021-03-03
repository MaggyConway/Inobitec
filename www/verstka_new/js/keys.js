let description = document.querySelectorAll('.keys-main__description');

[].forEach.call(description, function(el) {
    el.querySelector('.keys-main__description-show').addEventListener('click',function () {
        closeTab(el)
    })
});

function closeTab(el) {
    if (el.classList.contains('close')){
        el.classList.toggle('close');

        let showCase = el.querySelectorAll('.keys-main__description-showCase');

        [].forEach.call(showCase, function(elem) {
            elem.style.maxHeight = '0';
            elem.style.paddingBottom = '0';
        });

        let closeElem = el.querySelectorAll('.keys-close-elem');

        [].forEach.call(closeElem, function(elem) {
            elem.style.opacity = '0';
        });

        if (window.innerWidth > 1020){
            for (let i = 1;i < el.querySelectorAll('.keys-main__description-product').length;i++){
                el.querySelectorAll('.keys-main__description-product')[i].querySelectorAll('.keys-main__description-product-key p')[0].style.opacity = '0';
                el.querySelectorAll('.keys-main__description-product')[i].querySelectorAll('.keys-main__description-product-data p')[0].style.opacity = '0';
                el.querySelectorAll('.keys-main__description-product')[i].querySelectorAll('.keys-main__description-product-active p')[0].style.opacity = '0';
            }

            let descriptionProduct = el.querySelectorAll('.keys-main__description-product');

            [].forEach.call(descriptionProduct, function(elem) {
                elem.style.marginBottom = '0px';
            });
        }

        el.querySelector('.keys-main__description-show span').innerHTML = 'подробнее о заказе';
        el.querySelector('.keys-main__description-show img').style.transform = 'rotate(180deg)';
    } else {
        el.classList.toggle('close');

        let closeElem = el.querySelectorAll('.keys-close-elem');

        [].forEach.call(closeElem, function(elem) {
            elem.style.opacity = '1';
        });

        if (window.innerWidth > 1020){
            let descriptionShowCase = el.querySelectorAll('.keys-main__description-showCase');

            [].forEach.call(descriptionShowCase, function(elem) {
                elem.style.maxHeight = 162 + ((elem.querySelectorAll('.keys-main__description-showCase-row-wrapper').length - 1) * 80) + 'px';
            });

            for (let i = 1;i < el.querySelectorAll('.keys-main__description-product').length;i++){
                el.querySelectorAll('.keys-main__description-product')[i].querySelectorAll('.keys-main__description-product-key p')[0].style.opacity = '1';
                el.querySelectorAll('.keys-main__description-product')[i].querySelectorAll('.keys-main__description-product-data p')[0].style.opacity = '1';
                el.querySelectorAll('.keys-main__description-product')[i].querySelectorAll('.keys-main__description-product-active p')[0].style.opacity = '1';
            }
        } else {
            let descriptionShowCase = el.querySelectorAll('.keys-main__description-showCase');

            [].forEach.call(descriptionShowCase, function(elem) {
                elem.style.maxHeight = 192 + ((elem.querySelectorAll('.keys-main__description-showCase-row-wrapper').length - 1) * 110) + 'px';
            });

            for (let i = 1;i < el.querySelectorAll('.keys-main__description-product').length;i++){
                el.querySelectorAll('.keys-main__description-product')[i].querySelectorAll('.keys-main__description-product-key p')[0].style.opacity = '1';
                el.querySelectorAll('.keys-main__description-product')[i].querySelectorAll('.keys-main__description-product-data p')[0].style.opacity = '1';
                el.querySelectorAll('.keys-main__description-product')[i].querySelectorAll('.keys-main__description-product-active p')[0].style.opacity = '1';
            }
        }
        el.querySelector('.keys-main__description-show span').innerHTML = 'свернуть заказ';
        el.querySelector('.keys-main__description-show img').style.transform = 'rotate(0deg)';
    }
}
