//view point
var scroll = window.requestAnimationFrame ||
    function(callback) { window.setTimeout(callback, 1000/60)
    };

var elementsToShow = document.querySelectorAll('.show-on-scroll');

function loop(){
    elementsToShow.forEach(function(element){
        if (isElementInViewport(element)){
            element.classList.add('is-visible');
        }else{
            element.classList.remove('is-visible');
        }
    })
    scroll(loop);
}

function isElementInViewport(el){
    if(typeof jQuery === "function" && el instanceof jQuery){
        el = el[0];
    }
    var rect = el.getBoundingClientRect();
    return(
        (rect.top <=0 && rect.bottom >= 0) || (rect.bottom >= (window.innerHeight || document.documentElement.clientHeight) && rect.top <=(window.innerHeight || document.documentElement.clientHeight)) || (rect.top >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight))
    );
}

loop();

//navbar
const header = document.querySelector('.main-header');

window.addEventListener('scroll', () => {
    const scrollPos = window.scrollY;
    //console.log(scrollPos);
    if(scrollPos > 10){
        header.classList.add('scrolled');
    }else{
        header.classList.remove('scrolled');
    }
})

//select
$(".default_option").click(function(){
    $(this).parent().toggleClass("active");
  })
  
  $(".select_ul li").click(function(){
    var currentele = $(this).html();
    $(".default_option li").html(currentele);
    $(this).parents(".select_wrap").removeClass("active");
  })