var x = document.getElementById("user-pop-up");
var a=document.getElementById("goto")
var y = document.getElementById("backto");

function show() {
  x.style.display = "flex";
}
function hide(){
    x.style.display="none";
}
window.onclick = function(e){
  if(e.target == x){
    x.style.display="none";
  }
}
y.addEventListener("click", hide);
a.addEventListener("click", show);