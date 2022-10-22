var x = document.getElementById("product-pop-up");
var a=document.getElementById("click-btn-product");

function show() {
  x.style.display = "flex";
}

a.addEventListener("click", show);