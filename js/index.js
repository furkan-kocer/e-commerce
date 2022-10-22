const leftArr=document.getElementById("leftArr");
const rightArr=document.getElementById("rightArrow");
const leftArr2=document.getElementById("leftArr2");
const rightArr2=document.getElementById("rightArrow2");
const max=document.querySelectorAll('.slide').length;
let max2=document.querySelectorAll('.boxes').length;
let index=0;
let index1=0;
function slideRight(){
    index++;
    if(index==max){
        index=0;
    }
    gsap.to("#image", 1, {x: `${-index*100}%`})
}

function slideLeft(){
    index--;
    if(index<0){
        index=0;
    }
    gsap.to("#image", 1 , {x: `${-index*100}%`})
}

function slideRight2(){
    index1++;
    if(index1*5>=max2){
        index1=0;
    }
    gsap.to(".boxes-wrapper", 0.8, {x: `${-index1*500}%`})
    //gsap.to(".courasel-product", 0.5, {x: `${-index1*100}%`})
}

function slideLeft2(){
    index1--;
    if(index1<0){
        index1=0;
    }
    gsap.to(".boxes-wrapper", 0.8, {x: `${-index1*500}%`})
    //gsap.to(".courasel-product", 0.5 , {x: `${-index1*39}%`})
}



leftArr.addEventListener("click", slideLeft);
rightArr.addEventListener("click", slideRight);
leftArr2.addEventListener("click", slideLeft2);
rightArr2.addEventListener("click", slideRight2);

