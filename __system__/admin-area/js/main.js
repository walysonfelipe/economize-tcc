window.onscroll = function() {myFunction()};

var header = document.getElementById("headerSticky");
var sticky = header.offsetTop;
var btn = document.getElementById("buscaBox");
var teste = document.getElementById("after");

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
    teste.style.display = "block";
    btn.style.display = "block";
  } else {
    header.classList.remove("sticky");
    teste.style.display = "none";
    btn.style.display = "none";
  }
}

// MODAL

document.getElementById('btnModal').addEventListener('click', function() {
    document.querySelector('.modal').style.display = 'flex';
});

document.getElementById('buttonclose').addEventListener('click', function() {
  document.querySelector('.modal').style.display = 'none';
});

document.getElementById('btnRegistro').addEventListener('click', function() {
  document.querySelector('.modalRegistro').style.display = 'flex';
  document.querySelector('.modal').style.display = 'none';
});

document.getElementById('btnVoltaLog').addEventListener('click', function() {
  document.querySelector('.modalRegistro').style.display = 'none';
  document.querySelector('.modal').style.display = 'flex';
});

document.getElementById('buttoncloseRegistro').addEventListener('click', function() {
  document.querySelector('.modalRegistro').style.display = 'none';
});

var modal = document.getElementById('modal');
var modalRegistro = document.getElementById('myRegistro');

window.onclick = function(event) {
  if (event.target == modalRegistro) {
      modalRegistro.style.display = "none";
  } if (event.target == modal) {
      modal.style.display = "none";
  }
}

// Slideshow

var slideIndex = 1;

showSlidesByClick(slideIndex);

function showSlidesByClick(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
}

// Next Image
function plusSlides(n) {
  showSlidesByClick(slideIndex += n);
}
// Previous Image
function currentSlide(n) {
  showSlidesByClick(slideIndex = n);
}

// Auto Slideshow
showSlides();
function showSlides() {
    slideIndex++;
    //slides[slideIndex-1].style.display = "block";
    showSlidesByClick(slideIndex);
    setTimeout(showSlides, 3000); // Change image every 2 seconds
}

function mostra() {
  document.getElementById('modalMenuHeader').style.display = 'block';
}

function esconde() {
  document.getElementById('modalMenuHeader').style.display = 'none';
}

// Menu do menu

function showMenu1() {
  document.getElementById('linkMenu_1').style.display = 'flex';
}

function hideMenu1() {
  document.getElementById('linkMenu_1').style.display = 'none';
}

function showMenu2() {
  document.getElementById('linkMenu_2').style.display = 'flex';
}

function hideMenu2() {
  document.getElementById('linkMenu_2').style.display = 'none';
}

function showMenu3() {
  document.getElementById('linkMenu_3').style.display = 'flex';
}

function hideMenu3() {
  document.getElementById('linkMenu_3').style.display = 'none';
}

function showMenu4() {
  document.getElementById('linkMenu_4').style.display = 'flex';
}

function hideMenu4() {
  document.getElementById('linkMenu_4').style.display = 'none';
}

function showMenu5() {
  document.getElementById('linkMenu_5').style.display = 'flex';
}

function hideMenu5() {
  document.getElementById('linkMenu_5').style.display = 'none';
}

// STICK SLIDER

function registrar(pagina){
  $("#conteudo").load(pagina);
}