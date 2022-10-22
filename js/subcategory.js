var b = document.getElementById("user-pop-up1");
var c=document.getElementById("goto2");
var d = document.getElementById("backto1");
function show() {
    b.style.display = "grid";
  }
  function hide(){
      b.style.display="none";
  }
  window.onclick = function(e){
    if(e.target == b){
      b.style.display="none";
    }
  }
  d.addEventListener("click", hide);
  c.addEventListener("click", show);