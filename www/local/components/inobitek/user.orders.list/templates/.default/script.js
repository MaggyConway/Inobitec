let description = document.querySelectorAll('.keys-main__description');

[].forEach.call(description, function(el) {
    let checkTitle =  el.querySelectorAll('.product-name');

    el.querySelector('.keys-main__description-show').addEventListener('click',function () {
        closeTab(el,checkTitle)
    });
    
    
    linksToOld = el.querySelectorAll('.js-showOldLic');
    [].forEach.call(linksToOld, function(elem,index) {
        elem.addEventListener('click',function () {
            console.log("test go to");
            goToLic(elem);
        })
    });
    //*if(linksToOld){
    //  linksToOld.addEventListener('click',function () {
    //    console.log("test go to");
    //    goToLic(linksToOld);
    //  });
    //}*/
    

    [].forEach.call(checkTitle, function(elem,index) {
        elem.addEventListener('click',function () {
            elem.querySelector('img').classList.toggle('active-image');
            openCheck(el,index);
        })
    });
});

function closeTab(el,checkTitle) {
    let productDescription = el.querySelectorAll('.keys-main__description-product'),
        title = el.querySelector('.keys-main__description-show'),
        CloseText = el.querySelector('.keys-main__description-show span').getAttribute('data-hide-text'),
        OpenText = el.querySelector('.keys-main__description-show span').getAttribute('data-show-text');
    title.classList.toggle('active-tab');
    title.classList.contains('active-tab') ? title.querySelector('span').innerHTML = CloseText : title.querySelector('span').innerHTML = OpenText;
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

function goToLic(el){
  var oldLicDomElem = document.getElementById(el.getAttribute("data-href"));
  if(!oldLicDomElem.classList.contains('active-product')){
    var parent = oldLicDomElem.parentElement;
    parent.querySelector('.keys-main__description-show').click();
  }
  oldLicDomElem.scrollIntoView();
  if(!oldLicDomElem.querySelector('.product-name img').classList.contains('active-image'))
    oldLicDomElem.querySelector('.product-name').click();
}

